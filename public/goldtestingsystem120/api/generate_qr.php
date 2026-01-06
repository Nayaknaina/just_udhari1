<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../vendor/phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['serial']) || !isset($_POST['domain'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Serial number and domain required']);
    exit;
}

try {
    $serial = trim($_POST['serial']);
    $domain = trim($_POST['domain']);
    
    if (empty($serial) || empty($domain)) {
        throw new Exception('Serial number and domain cannot be empty');
    }
    
    if (!preg_match('/^[A-Z0-9]+$/', $serial)) {
        throw new Exception('Invalid serial number format');
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Verify the report exists
    $check_stmt = $db->prepare("SELECT id FROM reports WHERE serial_number = ?");
    $check_stmt->execute([$serial]);
    if (!$check_stmt->fetch()) {
        throw new Exception('Report not found for serial: ' . $serial);
    }
    
    // Create QR code directory if it doesn't exist
    $qr_dir = '../uploads/qr_codes/';
    if (!file_exists($qr_dir)) {
        if (!mkdir($qr_dir, 0755, true)) {
            throw new Exception('Failed to create QR codes directory');
        }
    }
    
    $qr_data = "https://{$domain}/mobile_view.php?serial={$serial}&verify=1";
    
    // Validate the QR data before generation
    $validation = validateQRData($qr_data);
    if (!$validation['valid']) {
        throw new Exception('Invalid QR data: ' . $validation['message']);
    }
    
    // QR code filename
    $qr_filename = $serial . '_qr.png';
    $qr_path = $qr_dir . $qr_filename;
    
    error_log("Generating QR code for serial: {$serial}, data: {$qr_data}");
    
    $generation_success = QRcode::png($qr_data, $qr_path, QR_ECLEVEL_H, 8, 2);
    
    if (!$generation_success) {
        error_log("First QR generation attempt failed, trying alternative method");
        $generation_success = QRcode::png($qr_data, $qr_path, QR_ECLEVEL_M, 6, 1);
    }
    
    if (!$generation_success) {
        throw new Exception('QR code generation failed after multiple attempts');
    }
    
    // Verify the file was created and has proper content
    if (!file_exists($qr_path)) {
        throw new Exception('QR code file was not created');
    }
    
    $fileSize = filesize($qr_path);
    if ($fileSize === 0) {
        throw new Exception('QR code file is empty');
    }
    
    if ($fileSize < 100) {
        throw new Exception('QR code file appears to be corrupted (too small)');
    }

    $stmt = $db->prepare("
        INSERT INTO qr_records (serial, domain, qr_path, qr_data, created_at) 
        VALUES (?, ?, ?, ?, NOW()) 
        ON DUPLICATE KEY UPDATE 
            qr_path = VALUES(qr_path), 
            qr_data = VALUES(qr_data),
            updated_at = NOW()
    ");
    
    if (!$stmt->execute([$serial, $domain, 'uploads/qr_codes/' . $qr_filename, $qr_data])) {
        error_log("Failed to save QR record to database for serial: {$serial}");
        // Don't throw exception here, QR was generated successfully
    }

    // Update the reports table with QR code path
    $update_stmt = $db->prepare("UPDATE reports SET qr_code_path = ? WHERE serial_number = ?");
    if (!$update_stmt->execute(['uploads/qr_codes/' . $qr_filename, $serial])) {
        error_log("Failed to update report with QR code path for serial: {$serial}");
        // Don't throw exception here, QR was generated successfully
    }

    echo json_encode([
        'success' => true,
        'qr_path' => 'uploads/qr_codes/' . $qr_filename,
        'qr_data' => $qr_data,
        'qr_size' => $fileSize,
        'file_exists' => file_exists($qr_path),
        'generation_method' => 'Google Charts API',
        'error_correction' => 'High (30%)',
        'message' => 'QR code generated and saved successfully'
    ]);
    
} catch (Exception $e) {
    error_log("QR Generation Error for serial {$serial}: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'serial' => $serial ?? 'unknown',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
