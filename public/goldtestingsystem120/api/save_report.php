<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$qr_lib_path = '../vendor/phpqrcode/qrlib.php';
if (!file_exists($qr_lib_path)) {
    error_log("QR library not found at: " . $qr_lib_path);
    // Try alternative path
    $qr_lib_path = __DIR__ . '/../vendor/phpqrcode/qrlib.php';
}

if (file_exists($qr_lib_path)) {
    require_once $qr_lib_path;
    error_log("QR library loaded successfully from: " . $qr_lib_path);
} else {
    error_log("QR library not found at any expected location");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    $serial_number = 'SGT' . date('Y') . sprintf('%06d', rand(100000, 999999));
    
    $check_stmt = $db->prepare("SELECT id FROM reports WHERE serial_number = ?");
    $attempts = 0;
    do {
        $check_stmt->execute([$serial_number]);
        if ($check_stmt->fetch()) {
            $serial_number = 'SGT' . date('Y') . sprintf('%06d', rand(100000, 999999));
            $attempts++;
        } else {
            break;
        }
    } while ($attempts < 10);
    
    if ($attempts >= 10) {
        throw new Exception('Unable to generate unique serial number');
    }
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/images/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception('Invalid image type. Only JPEG, PNG, and GIF allowed.');
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_filename = $serial_number . '_' . time() . '.' . $file_extension;
        $image_path_full = $upload_dir . $image_filename;
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path_full)) {
            throw new Exception('Failed to upload image');
        }
        $image_path = 'uploads/images/' . $image_filename;
    }
    
    $stmt = $db->prepare("
        INSERT INTO reports (
            serial_number, customer_name, sample_description, weight, carat,
            gold_au, copper_cu, silver_ag, zinc_zn, cadmium_cd, iridium_ir, tin_sn,
            nickel_ni, lead_pb, ruthenium_ru, platinum_pt, cobalt_co,
            palladium_pd, osmium_os, ferrum_fe, others, image_path, date_time
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $result = $stmt->execute([
        $serial_number,
        $_POST['customer_name'] ?? '',
        $_POST['sample_description'] ?? '',
        !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
        !empty($_POST['carat']) ? floatval($_POST['carat']) : null,
        !empty($_POST['gold_au']) ? floatval($_POST['gold_au']) : null,
        !empty($_POST['copper_cu']) ? floatval($_POST['copper_cu']) : null,
        !empty($_POST['silver_ag']) ? floatval($_POST['silver_ag']) : null,
        !empty($_POST['zinc_zn']) ? floatval($_POST['zinc_zn']) : null,
        !empty($_POST['cadmium_cd']) ? floatval($_POST['cadmium_cd']) : null,
        !empty($_POST['iridium_ir']) ? floatval($_POST['iridium_ir']) : null,
        !empty($_POST['tin_sn']) ? floatval($_POST['tin_sn']) : null,
        !empty($_POST['nickel_ni']) ? floatval($_POST['nickel_ni']) : null,
        !empty($_POST['lead_pb']) ? floatval($_POST['lead_pb']) : null,
        !empty($_POST['ruthenium_ru']) ? floatval($_POST['ruthenium_ru']) : null,
        !empty($_POST['platinum_pt']) ? floatval($_POST['platinum_pt']) : null,
        !empty($_POST['cobalt_co']) ? floatval($_POST['cobalt_co']) : null,
        !empty($_POST['palladium_pd']) ? floatval($_POST['palladium_pd']) : null,
        !empty($_POST['osmium_os']) ? floatval($_POST['osmium_os']) : null,
        !empty($_POST['ferrum_fe']) ? floatval($_POST['ferrum_fe']) : null,
        $_POST['others'] ?? null,
        $image_path
    ]);

    if (!$result) {
        throw new Exception('Failed to save report to database');
    }

    $qr_success = false;
    $qr_path = null;
    $qr_data = null;
    $qr_error = null;
    
    try {
        // Get the correct domain
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        //$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $domain = ($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']."/public/goldtestingsystem120":'localhost';
        $qr_data = $protocol . '://' . $domain . '/mobile_view.php?serial=' . $serial_number . '&verify=1';

        $qr_dir = __DIR__ . '/../uploads/qr_codes/';
        if (!file_exists($qr_dir)) {
            if (!mkdir($qr_dir, 0755, true)) {
                throw new Exception('Failed to create QR codes directory');
            }
        }

        $qr_filename = $serial_number . '_qr.png';
        $qr_path_full = $qr_dir . $qr_filename;

        error_log("Generating QR code for: " . $qr_data);
        error_log("QR file path: " . $qr_path_full);

        // Check if QRcode class exists
        if (!class_exists('QRcode')) {
            throw new Exception('QRcode class not available - library not loaded properly');
        }

        // Generate QR code with better error handling
        $qr_generated = QRcode::png($qr_data, $qr_path_full, QR_ECLEVEL_H, 8, 2);
        
        if ($qr_generated !== false) {
            // Verify the file was actually created and has content
            if (file_exists($qr_path_full) && filesize($qr_path_full) > 100) {
                $qr_success = true;
                $qr_path = 'uploads/qr_codes/' . $qr_filename;

                // Update report with QR path
                $update_stmt = $db->prepare("UPDATE reports SET qr_code_path = ? WHERE serial_number = ?");
                $update_stmt->execute([$qr_path, $serial_number]);

                // Save QR record
                try {
                    $qr_stmt = $db->prepare("
                        INSERT INTO qr_records (serial, domain, qr_path, qr_data, scan_count, created_at) 
                        VALUES (?, ?, ?, ?, 0, NOW())
                    ");
                    $qr_stmt->execute([$serial_number, $domain, $qr_path, $qr_data]);
                } catch (Exception $e) {
                    error_log("Failed to save QR record: " . $e->getMessage());
                }
                
                error_log("QR code generated successfully: " . $qr_path);
            } else {
                $qr_error = "QR file not created or too small: " . $qr_path_full;
                error_log($qr_error);
            }
        } else {
            $qr_error = "QR generation function returned false";
            error_log($qr_error);
        }
    } catch (Exception $e) {
        $qr_error = "QR generation failed: " . $e->getMessage();
        error_log($qr_error);
    }

    echo json_encode([
        'success' => true,
        'serial_number' => $serial_number,
        'qr_generated' => $qr_success,
        'qr_path' => $qr_path,
        'qr_data' => $qr_data,
        'qr_error' => $qr_error,
        'image_uploaded' => !empty($image_path),
        'image_path' => $image_path,
        'message' => 'Report saved successfully' . ($qr_success ? ' with QR code' : ' (QR code generation failed)'),
        'view_url' => $protocol . '://' . $domain . '/view_report.php?serial=' . $serial_number,
        'mobile_url' => $qr_data
    ]);

} catch (Exception $e) {
    error_log("Save report error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
        'debug_info' => [
            'post_data' => array_keys($_POST),
            'files_data' => array_keys($_FILES),
            'qr_lib_exists' => class_exists('QRcode') ? 'yes' : 'no'
        ]
    ]);
}
?>
