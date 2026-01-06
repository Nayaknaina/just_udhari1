<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $serials = $input['serials'] ?? [];
    
    if (empty($serials) || !is_array($serials)) {
        throw new Exception('Serial numbers array required');
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    $deleted_count = 0;
    
    foreach ($serials as $serial) {
        // Get report data to delete associated files
        $stmt = $db->prepare("SELECT image_path, qr_code_path FROM reports WHERE serial_number = ?");
        $stmt->execute([$serial]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($report) {
            // Delete associated files
            if ($report['image_path'] && file_exists('../' . $report['image_path'])) {
                unlink('../' . $report['image_path']);
            }
            
            if ($report['qr_code_path'] && file_exists('../' . $report['qr_code_path'])) {
                unlink('../' . $report['qr_code_path']);
            }
            
            // Delete from database
            $delete_stmt = $db->prepare("DELETE FROM reports WHERE serial_number = ?");
            $delete_stmt->execute([$serial]);
            $deleted_count++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'deleted_count' => $deleted_count,
        'message' => "$deleted_count report(s) deleted successfully"
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
