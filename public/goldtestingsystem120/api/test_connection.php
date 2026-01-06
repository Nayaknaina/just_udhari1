<?php
header('Content-Type: application/json');

try {
    require_once '../config/database.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        // Test query
        $stmt = $db->query("SELECT 1");
        $result = $stmt->fetch();
        
        echo json_encode([
            'success' => true,
            'message' => 'Database connection successful',
            'server_info' => $db->getAttribute(PDO::ATTR_SERVER_VERSION)
        ]);
    } else {
        throw new Exception('Failed to connect to database');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
