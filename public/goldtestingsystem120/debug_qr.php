<?php
// QR Code Debug and Regeneration Tool
require_once 'config/database.php';
require_once 'vendor/phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if ($_POST['action'] === 'regenerate_qr') {
            $serial = $_POST['serial'] ?? '';
            
            if (empty($serial)) {
                throw new Exception('Serial number required');
            }
            
            // Check if report exists
            $stmt = $db->prepare("SELECT * FROM reports WHERE serial_number = ?");
            $stmt->execute([$serial]);
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$report) {
                throw new Exception('Report not found');
            }
            
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $domain = $_SERVER['HTTP_HOST'];
            $qr_data = $protocol . '://' . $domain . '/mobile_view.php?serial=' . $serial . '&verify=1';
            $qr_filename = $serial . '_qr.png';
            $qr_dir = __DIR__ . '/uploads/qr_codes/';
            $qr_path_full = $qr_dir . $qr_filename;
            $qr_path = 'uploads/qr_codes/' . $qr_filename;
            
            // Ensure directory exists
            if (!file_exists($qr_dir)) {
                mkdir($qr_dir, 0755, true);
            }
            
            // Remove old QR file if exists
            if (file_exists($qr_path_full)) {
                unlink($qr_path_full);
            }
            
            error_log("Debug QR: Generating QR for: " . $qr_data);
            error_log("Debug QR: File path: " . $qr_path_full);
            
            $result = QRcode::png($qr_data, $qr_path_full, QR_ECLEVEL_H, 8, 2);
            
            if ($result && file_exists($qr_path_full) && filesize($qr_path_full) > 100) {
                // Update database
                $update_stmt = $db->prepare("UPDATE reports SET qr_code_path = ? WHERE serial_number = ?");
                $update_stmt->execute([$qr_path, $serial]);
                
                // Update or insert QR record
                $qr_stmt = $db->prepare("
                    INSERT INTO qr_records (serial, domain, qr_path, qr_data, scan_count, created_at) 
                    VALUES (?, ?, ?, ?, 0, NOW())
                    ON DUPLICATE KEY UPDATE 
                    qr_path = VALUES(qr_path), 
                    qr_data = VALUES(qr_data),
                    created_at = NOW()
                ");
                $qr_stmt->execute([$serial, $domain, $qr_path, $qr_data]);
                
                error_log("Debug QR: Successfully generated QR code: " . $qr_path_full);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'QR code regenerated successfully',
                    'qr_path' => $qr_path,
                    'qr_data' => $qr_data,
                    'file_size' => filesize($qr_path_full)
                ]);
            } else {
                error_log("Debug QR: QR generation failed for: " . $serial);
                throw new Exception('QR code generation failed - file not created or too small');
            }
        }
        
        elseif ($_POST['action'] === 'regenerate_all') {
            $stmt = $db->query("SELECT serial_number FROM reports ORDER BY date_time DESC");
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $success_count = 0;
            $error_count = 0;
            $errors = [];
            
            foreach ($reports as $report) {
                $serial = $report['serial_number'];
                
                try {
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                    $domain = $_SERVER['HTTP_HOST'];
                    $qr_data = $protocol . '://' . $domain . '/mobile_view.php?serial=' . $serial . '&verify=1';
                    $qr_filename = $serial . '_qr.png';
                    $qr_dir = __DIR__ . '/uploads/qr_codes/';
                    $qr_path_full = $qr_dir . $qr_filename;
                    $qr_path = 'uploads/qr_codes/' . $qr_filename;
                    
                    if (!file_exists($qr_dir)) {
                        mkdir($qr_dir, 0755, true);
                    }
                    
                    if (file_exists($qr_path_full)) {
                        unlink($qr_path_full);
                    }
                    
                    $result = QRcode::png($qr_data, $qr_path_full, QR_ECLEVEL_H, 8, 2);
                    
                    if ($result && file_exists($qr_path_full) && filesize($qr_path_full) > 100) {
                        $update_stmt = $db->prepare("UPDATE reports SET qr_code_path = ? WHERE serial_number = ?");
                        $update_stmt->execute([$qr_path, $serial]);
                        
                        $qr_stmt = $db->prepare("
                            INSERT INTO qr_records (serial, domain, qr_path, qr_data, scan_count, created_at) 
                            VALUES (?, ?, ?, ?, 0, NOW())
                            ON DUPLICATE KEY UPDATE 
                            qr_path = VALUES(qr_path), 
                            qr_data = VALUES(qr_data),
                            created_at = NOW()
                        ");
                        $qr_stmt->execute([$serial, $domain, $qr_path, $qr_data]);
                        
                        $success_count++;
                    } else {
                        $error_count++;
                        $errors[] = "Failed to generate QR for: " . $serial;
                    }
                    
                } catch (Exception $e) {
                    $error_count++;
                    $errors[] = "Error with " . $serial . ": " . $e->getMessage();
                }
                
                // Small delay to prevent overwhelming the API
                usleep(500000); // 0.5 seconds
            }
            
            echo json_encode([
                'success' => true,
                'message' => "Bulk regeneration completed: {$success_count} success, {$error_count} errors",
                'success_count' => $success_count,
                'error_count' => $error_count,
                'errors' => array_slice($errors, 0, 10) // Limit error messages
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Debug QR Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// HTML interface
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Debug Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #fafafa; }
        .report-item { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #eee; background: white; margin: 5px 0; border-radius: 5px; }
        .report-info { flex: 1; }
        .qr-preview { width: 100px; height: 100px; margin: 0 10px; }
        .qr-preview img { width: 100%; height: 100%; object-fit: contain; border: 1px solid #ddd; border-radius: 3px; }
        button { background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin: 2px; transition: background 0.3s; }
        button:hover { background: #0056b3; }
        button:disabled { background: #6c757d; cursor: not-allowed; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .status { padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .status.good { background: #d4edda; color: #155724; }
        .status.bad { background: #f8d7da; color: #721c24; }
        .status.warning { background: #fff3cd; color: #856404; }
        .loading { display: none; color: #007bff; font-weight: bold; }
        .stats { display: flex; gap: 20px; margin: 10px 0; }
        .stat-item { background: white; padding: 15px; border-radius: 5px; text-align: center; flex: 1; }
        .stat-number { font-size: 24px; font-weight: bold; color: #007bff; }
        .stat-label { font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 QR Code Debug & Management Tool</h1>
        
        <!-- Statistics Section -->
        <div class="debug-section">
            <h3>System Statistics</h3>
            <div class="stats">
                <?php
                try {
                    $database = new Database();
                    $db = $database->getConnection();
                    
                    $total_reports = $db->query("SELECT COUNT(*) FROM reports")->fetchColumn();
                    $qr_with_files = $db->query("SELECT COUNT(*) FROM reports WHERE qr_code_path IS NOT NULL")->fetchColumn();
                    
                    // Check actual file existence
                    $stmt = $db->query("SELECT qr_code_path FROM reports WHERE qr_code_path IS NOT NULL");
                    $valid_qr_count = 0;
                    while ($row = $stmt->fetch()) {
                        if (file_exists($row['qr_code_path']) && filesize($row['qr_code_path']) > 100) {
                            $valid_qr_count++;
                        }
                    }
                    
                    echo '<div class="stat-item">';
                    echo '<div class="stat-number">' . $total_reports . '</div>';
                    echo '<div class="stat-label">Total Reports</div>';
                    echo '</div>';
                    
                    echo '<div class="stat-item">';
                    echo '<div class="stat-number">' . $qr_with_files . '</div>';
                    echo '<div class="stat-label">QR Records</div>';
                    echo '</div>';
                    
                    echo '<div class="stat-item">';
                    echo '<div class="stat-number">' . $valid_qr_count . '</div>';
                    echo '<div class="stat-label">Valid QR Files</div>';
                    echo '</div>';
                    
                    echo '<div class="stat-item">';
                    echo '<div class="stat-number">' . ($qr_with_files - $valid_qr_count) . '</div>';
                    echo '<div class="stat-label">Missing QR Files</div>';
                    echo '</div>';
                    
                } catch (Exception $e) {
                    echo '<div class="status bad">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="debug-section">
            <h3>Recent Reports & QR Status</h3>
            <div class="loading" id="loading">Processing...</div>
            
            <?php
            try {
                $database = new Database();
                $db = $database->getConnection();
                
                $stmt = $db->query("
                    SELECT serial_number, customer_name, qr_code_path, date_time 
                    FROM reports 
                    ORDER BY date_time DESC 
                    LIMIT 30
                ");
                
                while ($report = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $qr_exists = $report['qr_code_path'] && file_exists($report['qr_code_path']);
                    $qr_size = $qr_exists ? filesize($report['qr_code_path']) : 0;
                    $qr_valid = $qr_exists && $qr_size > 100;
                    
                    echo '<div class="report-item">';
                    echo '<div class="report-info">';
                    echo '<strong>' . htmlspecialchars($report['serial_number']) . '</strong><br>';
                    echo 'Customer: ' . htmlspecialchars($report['customer_name']) . '<br>';
                    echo 'Date: ' . date('Y-m-d H:i', strtotime($report['date_time'])) . '<br>';
                    
                    if ($qr_valid) {
                        echo '<span class="status good">✓ QR OK (' . number_format($qr_size) . ' bytes)</span>';
                    } elseif ($qr_exists) {
                        echo '<span class="status warning">⚠ QR Small (' . $qr_size . ' bytes)</span>';
                    } else {
                        echo '<span class="status bad">✗ QR Missing</span>';
                    }
                    
                    echo '</div>';
                    
                    echo '<div class="qr-preview">';
                    if ($qr_exists && $qr_valid) {
                        echo '<img src="' . htmlspecialchars($report['qr_code_path']) . '?v=' . time() . '" alt="QR Code">';
                    } else {
                        echo '<div style="background: #f8f9fa; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 12px; border: 1px dashed #ccc;">No QR</div>';
                    }
                    echo '</div>';
                    
                    echo '<div class="actions">';
                    echo '<button onclick="regenerateQR(\'' . htmlspecialchars($report['serial_number']) . '\')" id="btn-' . htmlspecialchars($report['serial_number']) . '">Regenerate QR</button>';
                    echo '<button onclick="viewReport(\'' . htmlspecialchars($report['serial_number']) . '\')" class="btn-success">View Report</button>';
                    echo '<button onclick="testQR(\'' . htmlspecialchars($report['serial_number']) . '\')" class="btn-success">Test QR</button>';
                    echo '</div>';
                    
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="status bad">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
        
        <div class="debug-section">
            <h3>Bulk Operations</h3>
            <button onclick="regenerateAllQR()" class="btn-danger" id="bulkBtn">Regenerate All QR Codes</button>
            <button onclick="testAllQR()" class="btn-success">Test All QR Codes</button>
            <button onclick="cleanupQR()" class="btn-danger">Cleanup Invalid QR Files</button>
            <div id="bulkResult" style="margin-top: 10px;"></div>
        </div>
    </div>

    <script>
        async function regenerateQR(serial) {
            const btn = document.getElementById('btn-' + serial);
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Generating...';
            
            try {
                const response = await fetch('debug_qr.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=regenerate_qr&serial=${encodeURIComponent(serial)}`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('✓ QR code regenerated successfully!\nFile size: ' + result.file_size + ' bytes');
                    location.reload();
                } else {
                    alert('✗ Error: ' + result.error);
                }
            } catch (error) {
                alert('✗ Network error: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
        
        function viewReport(serial) {
            window.open(`mobile_view.php?serial=${encodeURIComponent(serial)}&verify=1`, '_blank');
        }
        
        function testQR(serial) {
            window.open(`view_report.php?serial=${encodeURIComponent(serial)}`, '_blank');
        }
        
        async function regenerateAllQR() {
            if (!confirm('⚠️ This will regenerate ALL QR codes. This may take several minutes. Continue?')) {
                return;
            }
            
            const btn = document.getElementById('bulkBtn');
            const resultDiv = document.getElementById('bulkResult');
            const loading = document.getElementById('loading');
            
            btn.disabled = true;
            btn.textContent = 'Processing...';
            loading.style.display = 'block';
            resultDiv.innerHTML = '';
            
            try {
                const response = await fetch('debug_qr.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=regenerate_all'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = `
                        <div class="status good">
                            ✓ Bulk regeneration completed!<br>
                            Success: ${result.success_count}<br>
                            Errors: ${result.error_count}
                            ${result.errors && result.errors.length > 0 ? '<br>First few errors:<br>' + result.errors.join('<br>') : ''}
                        </div>
                    `;
                    
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    resultDiv.innerHTML = `<div class="status bad">✗ Error: ${result.error}</div>`;
                }
            } catch (error) {
                resultDiv.innerHTML = `<div class="status bad">✗ Network error: ${error.message}</div>`;
            } finally {
                btn.disabled = false;
                btn.textContent = 'Regenerate All QR Codes';
                loading.style.display = 'none';
            }
        }
        
        function testAllQR() {
            alert('🔍 QR testing feature will check all QR codes for validity. Coming soon!');
        }
        
        function cleanupQR() {
            if (confirm('⚠️ This will remove invalid QR files from the server. Continue?')) {
                alert('🧹 Cleanup feature coming soon!');
            }
        }
    </script>
</body>
</html>
