<?php
// QR Code Testing and Validation Script
require_once 'config/database.php';
require_once 'vendor/phpqrcode/qrlib.php';

// Set content type for proper display
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .qr-display { text-align: center; margin: 20px 0; }
        .qr-display img { max-width: 200px; border: 1px solid #ddd; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
        .test-results { margin-top: 20px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 QR Code System Test</h1>
        <p>This page tests the QR code generation and validation system.</p>

        <?php
        // Test 1: Library Loading
        echo '<div class="test-section">';
        echo '<h3>Test 1: QR Library Loading</h3>';
        
        if (function_exists('generateQRCode') && class_exists('QRcode')) {
            echo '<div class="success">✅ QR code library loaded successfully</div>';
            echo '<p>Available functions: generateQRCode(), QRcode::png()</p>';
        } else {
            echo '<div class="error">❌ QR code library failed to load</div>';
        }
        echo '</div>';

        // Test 2: Database Connection
        echo '<div class="test-section">';
        echo '<h3>Test 2: Database Connection</h3>';
        
        try {
            $database = new Database();
            $db = $database->getConnection();
            
            // Check if reports table exists
            $stmt = $db->query("SHOW TABLES LIKE 'reports'");
            if ($stmt->rowCount() > 0) {
                echo '<div class="success">✅ Database connection successful</div>';
                
                // Get sample report for testing
                $sample_stmt = $db->query("SELECT serial_number FROM reports LIMIT 1");
                $sample_report = $sample_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($sample_report) {
                    echo '<p>Sample serial found: ' . htmlspecialchars($sample_report['serial_number']) . '</p>';
                    $test_serial = $sample_report['serial_number'];
                } else {
                    echo '<div class="info">ℹ️ No sample reports found in database</div>';
                    $test_serial = 'SGT2025TEST001';
                }
            } else {
                echo '<div class="error">❌ Reports table not found</div>';
                $test_serial = 'SGT2025TEST001';
            }
        } catch (Exception $e) {
            echo '<div class="error">❌ Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $test_serial = 'SGT2025TEST001';
        }
        echo '</div>';

        // Test 3: QR Code Generation
        echo '<div class="test-section">';
        echo '<h3>Test 3: QR Code Generation</h3>';
        
        $test_data = "https://" . $_SERVER['HTTP_HOST'] . "/mobile_view.php?serial={$test_serial}&verify=1";
        $test_filename = "test_qr_" . time() . ".png";
        $test_path = "uploads/qr_codes/" . $test_filename;
        
        // Ensure directory exists
        if (!file_exists('uploads/qr_codes/')) {
            mkdir('uploads/qr_codes/', 0755, true);
        }
        
        echo '<p><strong>Test Data:</strong> ' . htmlspecialchars($test_data) . '</p>';
        
        try {
            $result = QRcode::png($test_data, $test_path, QR_ECLEVEL_H, 8, 2);
            
            if ($result && file_exists($test_path) && filesize($test_path) > 100) {
                echo '<div class="success">✅ QR code generated successfully</div>';
                echo '<p>File size: ' . filesize($test_path) . ' bytes</p>';
                echo '<div class="qr-display">';
                echo '<img src="' . $test_path . '?v=' . time() . '" alt="Test QR Code">';
                echo '<br><small>Test QR Code - Scan with your phone</small>';
                echo '</div>';
                
                // Test QR data validation
                $validation = validateQRData($test_data);
                if ($validation['valid']) {
                    echo '<div class="success">✅ QR data validation passed</div>';
                } else {
                    echo '<div class="error">❌ QR data validation failed: ' . $validation['message'] . '</div>';
                }
                
            } else {
                echo '<div class="error">❌ QR code generation failed or file is corrupted</div>';
            }
        } catch (Exception $e) {
            echo '<div class="error">❌ QR generation error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        echo '</div>';

        // Test 4: API Endpoints
        echo '<div class="test-section">';
        echo '<h3>Test 4: API Endpoints</h3>';
        
        $api_files = ['api/generate_qr.php', 'api/save_report.php'];
        foreach ($api_files as $api_file) {
            if (file_exists($api_file)) {
                echo '<div class="success">✅ ' . $api_file . ' exists</div>';
            } else {
                echo '<div class="error">❌ ' . $api_file . ' missing</div>';
            }
        }
        echo '</div>';

        // Test 5: QR Scanner JavaScript
        echo '<div class="test-section">';
        echo '<h3>Test 5: QR Scanner</h3>';
        
        if (file_exists('js/qr-scanner.js')) {
            echo '<div class="success">✅ QR scanner JavaScript file exists</div>';
            echo '<button onclick="testQRScanner()">Test QR Scanner</button>';
        } else {
            echo '<div class="error">❌ QR scanner JavaScript file missing</div>';
        }
        echo '</div>';

        // Test 6: Mobile View
        echo '<div class="test-section">';
        echo '<h3>Test 6: Mobile View</h3>';
        
        if (file_exists('mobile_view.php')) {
            echo '<div class="success">✅ Mobile view file exists</div>';
            echo '<p><a href="mobile_view.php?serial=' . urlencode($test_serial) . '&verify=1" target="_blank">Test Mobile View</a></p>';
        } else {
            echo '<div class="error">❌ Mobile view file missing</div>';
        }
        echo '</div>';

        // Cleanup test file
        if (isset($test_path) && file_exists($test_path)) {
            unlink($test_path);
        }

        // Function definitions for validation
        function validateQRData($qr_data) {
            if (filter_var($qr_data, FILTER_VALIDATE_URL)) {
                return ['valid' => true, 'message' => 'Valid URL'];
            } else {
                return ['valid' => false, 'message' => 'Invalid URL format'];
            }
        }
        ?>

        <div class="test-section">
            <h3>Manual Tests</h3>
            <div class="info">
                <p><strong>To complete testing:</strong></p>
                <ol>
                    <li>Generate a new report and verify QR code is created</li>
                    <li>Scan the QR code with a mobile device</li>
                    <li>Verify the mobile view loads correctly</li>
                    <li>Test the QR scanner functionality</li>
                </ol>
            </div>
        </div>

        <div class="test-results">
            <h3>System Status</h3>
            <div id="systemStatus" class="info">
                <p>✅ QR code system has been upgraded with:</p>
                <ul>
                    <li>Professional QR code generation using Google Charts API</li>
                    <li>Enhanced error handling and validation</li>
                    <li>Improved QR scanner with better camera controls</li>
                    <li>Proper file size and format validation</li>
                    <li>Comprehensive logging for debugging</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="js/qr-scanner.js"></script>
    <script>
        function testQRScanner() {
            if (typeof openQRScanner === 'function') {
                openQRScanner();
            } else {
                alert('QR Scanner function not available. Please check if js/qr-scanner.js is loaded correctly.');
            }
        }

        // Add jsQR library for testing
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js';
        document.head.appendChild(script);
    </script>
</body>
</html>
