<?php
// Comprehensive QR System Test Script
require_once 'config/database.php';
require_once 'vendor/phpqrcode/qrlib.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR System Test Suite</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .test-result { margin: 5px 0; padding: 5px; }
        .qr-preview { margin: 10px 0; text-align: center; }
        .qr-preview img { border: 2px solid #ddd; border-radius: 5px; }
        .code-block { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; margin: 10px 0; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
        .stats { display: flex; gap: 20px; margin: 20px 0; }
        .stat-item { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; flex: 1; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 QR System Test Suite</h1>
        <p>Comprehensive testing of the Gold Testing System QR functionality</p>

        <?php
        $test_results = [];
        $total_tests = 0;
        $passed_tests = 0;

        function addTestResult($name, $passed, $message, $details = '') {
            global $test_results, $total_tests, $passed_tests;
            $total_tests++;
            if ($passed) $passed_tests++;
            
            $test_results[] = [
                'name' => $name,
                'passed' => $passed,
                'message' => $message,
                'details' => $details
            ];
        }

        function displayTestResult($name, $passed, $message, $details = '') {
            $icon = $passed ? '✅' : '❌';
            $class = $passed ? 'success' : 'error';
            echo "<div class='test-result'>";
            echo "<span class='{$class}'>{$icon} {$name}: {$message}</span>";
            if ($details) {
                echo "<div class='code-block'>{$details}</div>";
            }
            echo "</div>";
            
            addTestResult($name, $passed, $message, $details);
        }
        ?>

        <!-- Test 1: System Requirements -->
        <div class="test-section">
            <h3>1. System Requirements Check</h3>
            
            <?php
            // PHP Version
            $php_version = phpversion();
            $php_ok = version_compare($php_version, '7.4.0', '>=');
            displayTestResult('PHP Version', $php_ok, "PHP {$php_version} " . ($php_ok ? 'is supported' : 'is too old (requires 7.4+)'));

            // Required Extensions
            $extensions = ['pdo', 'pdo_mysql', 'gd', 'curl'];
            foreach ($extensions as $ext) {
                $loaded = extension_loaded($ext);
                displayTestResult("Extension: {$ext}", $loaded, $loaded ? 'loaded' : 'missing');
            }

            // Directory Permissions
            $dirs = ['uploads/', 'uploads/qr_codes/', 'uploads/images/'];
            foreach ($dirs as $dir) {
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $writable = is_writable($dir);
                displayTestResult("Directory: {$dir}", $writable, $writable ? 'writable' : 'not writable');
            }
            ?>
        </div>

        <!-- Test 2: QR Library Functions -->
        <div class="test-section">
            <h3>2. QR Library Function Tests</h3>
            
            <?php
            // Class availability
            $qr_class = class_exists('QRcode');
            displayTestResult('QRcode Class', $qr_class, $qr_class ? 'available' : 'missing');

            $qr_encode_class = class_exists('QRencode');
            displayTestResult('QRencode Class', $qr_encode_class, $qr_encode_class ? 'available' : 'missing');

            // Function availability
            $functions = ['generateQRCode', 'validateQRData', 'getQRInfo'];
            foreach ($functions as $func) {
                $exists = function_exists($func);
                displayTestResult("Function: {$func}", $exists, $exists ? 'available' : 'missing');
            }

            // QR Data Validation Test
            if (function_exists('validateQRData')) {
                $test_data = "https://example.com/mobile_view.php?serial=SGT2025123456&verify=1";
                $validation = validateQRData($test_data);
                displayTestResult('QR Data Validation', $validation['valid'], $validation['message']);
                
                // Test invalid data
                $invalid_validation = validateQRData('');
                displayTestResult('Invalid Data Handling', !$invalid_validation['valid'], 'Empty data correctly rejected');
            }
            ?>
        </div>

        <!-- Test 3: QR Code Generation -->
        <div class="test-section">
            <h3>3. QR Code Generation Tests</h3>
            
            <?php
            $test_serial = "TEST" . date('YmdHis');
            $qr_dir = 'uploads/qr_codes/';
            $qr_filename = $test_serial . '_test_qr.png';
            $qr_path = $qr_dir . $qr_filename;
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $domain = $_SERVER['HTTP_HOST'];
            $qr_data = $protocol . '://' . $domain . '/mobile_view.php?serial=' . $test_serial . '&verify=1';

            try {
                // Test QR generation
                $start_time = microtime(true);
                $result = QRcode::png($qr_data, $qr_path, QR_ECLEVEL_H, 6, 2);
                $generation_time = round((microtime(true) - $start_time) * 1000, 2);
                
                if ($result && file_exists($qr_path)) {
                    $file_size = filesize($qr_path);
                    $size_ok = $file_size > 100;
                    
                    displayTestResult('QR Generation', $size_ok, 
                        $size_ok ? "Generated successfully ({$file_size} bytes, {$generation_time}ms)" : "File too small ({$file_size} bytes)");
                    
                    if ($size_ok) {
                        echo "<div class='qr-preview'>";
                        echo "<p><strong>Generated QR Code:</strong></p>";
                        echo "<img src='{$qr_path}?v=" . time() . "' alt='Test QR Code' style='max-width: 200px;'>";
                        echo "<p><small>Data: {$qr_data}</small></p>";
                        echo "</div>";
                        
                        // Test different sizes
                        $sizes = [4, 6, 8, 10];
                        foreach ($sizes as $size) {
                            $size_path = $qr_dir . $test_serial . "_size{$size}_qr.png";
                            $size_result = QRcode::png($qr_data, $size_path, QR_ECLEVEL_H, $size, 2);
                            if ($size_result && file_exists($size_path)) {
                                $size_bytes = filesize($size_path);
                                displayTestResult("QR Size {$size}", true, "Generated ({$size_bytes} bytes)");
                            }
                        }
                    }
                } else {
                    displayTestResult('QR Generation', false, 'Failed to generate QR code');
                }
                
            } catch (Exception $e) {
                displayTestResult('QR Generation', false, 'Exception: ' . $e->getMessage());
            }
            ?>
        </div>

        <!-- Test 4: Database Integration -->
        <div class="test-section">
            <h3>4. Database Integration Tests</h3>
            
            <?php
            try {
                $database = new Database();
                $db = $database->getConnection();
                
                if ($db) {
                    displayTestResult('Database Connection', true, 'Connected successfully');
                    
                    // Check required tables
                    $required_tables = [
                        'reports' => 'Main reports table',
                        'qr_records' => 'QR tracking table'
                    ];
                    
                    foreach ($required_tables as $table => $description) {
                        $stmt = $db->prepare("SHOW TABLES LIKE ?");
                        $stmt->execute([$table]);
                        $exists = $stmt->fetch() !== false;
                        displayTestResult("Table: {$table}", $exists, $exists ? $description . ' exists' : $description . ' missing');
                        
                        if ($exists) {
                            // Check table structure
                            $columns = $db->query("DESCRIBE {$table}")->fetchAll(PDO::FETCH_ASSOC);
                            $column_count = count($columns);
                            displayTestResult("Table Structure: {$table}", $column_count > 0, "{$column_count} columns found");
                        }
                    }
                    
                    // Test data operations
                    if (isset($qr_path) && file_exists($qr_path)) {
                        try {
                            // Test insert
                            $stmt = $db->prepare("
                                INSERT INTO qr_records (serial, domain, qr_path, qr_data, scan_count, created_at) 
                                VALUES (?, ?, ?, ?, 0, NOW())
                            ");
                            $insert_result = $stmt->execute([$test_serial, $domain, $qr_path, $qr_data]);
                            displayTestResult('Database Insert', $insert_result, 'QR record inserted successfully');
                            
                            // Test select
                            $stmt = $db->prepare("SELECT * FROM qr_records WHERE serial = ?");
                            $stmt->execute([$test_serial]);
                            $record = $stmt->fetch(PDO::FETCH_ASSOC);
                            displayTestResult('Database Select', $record !== false, 'QR record retrieved successfully');
                            
                            // Test update
                            $stmt = $db->prepare("UPDATE qr_records SET scan_count = scan_count + 1 WHERE serial = ?");
                            $update_result = $stmt->execute([$test_serial]);
                            displayTestResult('Database Update', $update_result, 'QR record updated successfully');
                            
                            // Cleanup test record
                            $stmt = $db->prepare("DELETE FROM qr_records WHERE serial = ?");
                            $stmt->execute([$test_serial]);
                            
                        } catch (Exception $e) {
                            displayTestResult('Database Operations', false, 'Error: ' . $e->getMessage());
                        }
                    }
                    
                } else {
                    displayTestResult('Database Connection', false, 'Failed to connect');
                }
                
            } catch (Exception $e) {
                displayTestResult('Database Connection', false, 'Exception: ' . $e->getMessage());
            }
            ?>
        </div>

        <!-- Test 5: API Endpoints -->
        <div class="test-section">
            <h3>5. API Endpoint Tests</h3>
            
            <?php
            $api_files = [
                'api/save_report.php' => 'Report saving API',
                'api/get_report.php' => 'Report retrieval API',
                'api/generate_qr.php' => 'QR generation API',
                'api/delete_report.php' => 'Report deletion API'
            ];
            
            foreach ($api_files as $file => $description) {
                $exists = file_exists($file);
                $readable = $exists && is_readable($file);
                displayTestResult("API: {$file}", $readable, $readable ? $description . ' accessible' : $description . ' missing/unreadable');
            }
            
            // Test key PHP files
            $php_files = [
                'mobile_view.php' => 'Mobile report view',
                'view_report.php' => 'Desktop report view',
                'debug_qr.php' => 'QR debug tool'
            ];
            
            foreach ($php_files as $file => $description) {
                $exists = file_exists($file);
                displayTestResult("File: {$file}", $exists, $exists ? $description . ' exists' : $description . ' missing');
            }
            ?>
        </div>

        <!-- Test Results Summary -->
        <div class="test-section">
            <h3>📊 Test Results Summary</h3>
            
            <div class="stats">
                <div class="stat-item">
                    <div style="font-size: 24px; font-weight: bold; color: #28a745;"><?php echo $passed_tests; ?></div>
                    <div>Tests Passed</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 24px; font-weight: bold; color: #dc3545;"><?php echo $total_tests - $passed_tests; ?></div>
                    <div>Tests Failed</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 24px; font-weight: bold; color: #007bff;"><?php echo $total_tests; ?></div>
                    <div>Total Tests</div>
                </div>
                <div class="stat-item">
                    <div style="font-size: 24px; font-weight: bold; color: #17a2b8;"><?php echo round(($passed_tests / $total_tests) * 100, 1); ?>%</div>
                    <div>Success Rate</div>
                </div>
            </div>
            
            <?php
            $success_rate = ($passed_tests / $total_tests) * 100;
            
            if ($success_rate >= 90) {
                echo "<div class='success'>🎉 Excellent! Your QR system is working perfectly.</div>";
            } elseif ($success_rate >= 75) {
                echo "<div class='warning'>⚠️ Good! Minor issues detected, but system should work.</div>";
            } else {
                echo "<div class='error'>❌ Critical issues detected. Please fix the failed tests.</div>";
            }
            ?>
        </div>

        <!-- Cleanup and Actions -->
        <div class="test-section">
            <h3>🧹 Cleanup & Actions</h3>
            
            <?php
            // Cleanup test files
            $test_files = glob('uploads/qr_codes/' . $test_serial . '*');
            $cleaned_count = 0;
            foreach ($test_files as $file) {
                if (unlink($file)) {
                    $cleaned_count++;
                }
            }
            
            if ($cleaned_count > 0) {
                echo "<div class='info'>🗑️ Cleaned up {$cleaned_count} test files</div>";
            }
            ?>
            
            <button onclick="location.reload()">🔄 Run Tests Again</button>
            <button onclick="window.open('debug_qr.php', '_blank')">🔧 Open QR Debug Tool</button>
            <button onclick="window.open('index.html', '_blank')">📝 Test Report Generation</button>
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds if there are failures
        <?php if ($success_rate < 100): ?>
        console.log('Some tests failed. Auto-refresh disabled. Fix issues and run manually.');
        <?php endif; ?>
        
        // Add timestamp
        document.addEventListener('DOMContentLoaded', function() {
            const timestamp = new Date().toLocaleString();
            document.querySelector('h1').innerHTML += ` <small style="color: #666;">(${timestamp})</small>`;
        });
    </script>
</body>
</html>
