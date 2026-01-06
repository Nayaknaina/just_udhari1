<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Testing System - Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .setup-container {
            background: white;
            color: #1f2937;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .setup-header h1 {
            color: #1e3a8a;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .setup-step {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #d97706;
        }
        .setup-step h3 {
            color: #1e3a8a;
            margin-bottom: 1rem;
        }
        .code-block {
            background: #1f2937;
            color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            margin: 0.5rem 0;
        }
        .success { color: #059669; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .warning { color: #d97706; font-weight: bold; }
        .btn {
            background: #1e3a8a;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            margin: 0.5rem 0.5rem 0.5rem 0;
        }
        .btn:hover { background: #1e40af; }
        .requirements { list-style: none; }
        .requirements li { 
            padding: 0.5rem 0; 
            border-bottom: 1px solid #e5e7eb;
        }
        .requirements li:before {
            content: "✓ ";
            color: #059669;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1>Gold Testing System Setup</h1>
            <p>Complete installation and configuration guide</p>
        </div>

        <div class="setup-step">
            <h3>1. System Requirements</h3>
            <ul class="requirements">
                <li>PHP 7.4 or higher with GD extension</li>
                <li>MySQL 5.7 or higher</li>
                <li>Apache/Nginx web server</li>
                <li>Write permissions for uploads directory</li>
                <li>HTTPS enabled (recommended for QR codes)</li>
            </ul>
        </div>

        <div class="setup-step">
            <h3>2. Database Setup</h3>
            <p>Run the following SQL script to create the database:</p>
            <div class="code-block">
CREATE DATABASE gold_testing_system;
USE gold_testing_system;

-- Run the SQL script from scripts/01_create_database.sql
            </div>
            <p class="warning">Update database credentials in config/database.php</p>
        </div>

        <div class="setup-step">
            <h3>3. Directory Permissions</h3>
            <p>Create and set permissions for upload directories:</p>
            <div class="code-block">
mkdir -p uploads/images uploads/qr_codes
chmod 755 uploads uploads/images uploads/qr_codes
            </div>
        </div>

        <div class="setup-step">
            <h3>4. QR Code Library</h3>
            <p>The phpqrcode library is included. For better QR codes, download the full library:</p>
            <div class="code-block">
wget https://sourceforge.net/projects/phpqrcode/files/phpqrcode-2010100721_1.1.4.zip
unzip phpqrcode-2010100721_1.1.4.zip -d vendor/
            </div>
        </div>

        <div class="setup-step">
            <h3>5. Configuration</h3>
            <p>Update the following configuration files:</p>
            <ul>
                <li><strong>config/database.php</strong> - Database credentials</li>
                <li><strong>api/save_report.php</strong> - Domain name for QR codes</li>
                <li><strong>api/generate_qr.php</strong> - QR code settings</li>
            </ul>
        </div>

        <div class="setup-step">
            <h3>6. Testing</h3>
            <p>Test the system components:</p>
            <button class="btn" onclick="testDatabase()">Test Database</button>
            <button class="btn" onclick="testUploads()">Test Uploads</button>
            <button class="btn" onclick="testQR()">Test QR Generation</button>
            <div id="testResults"></div>
        </div>

        <div class="setup-step">
            <h3>7. Access URLs</h3>
            <ul>
                <li><strong>Main Form:</strong> index.html</li>
                <li><strong>View Report:</strong> view_report.php?serial=SERIAL_NUMBER</li>
                <li><strong>Mobile View:</strong> mobile_view.php?serial=SERIAL_NUMBER</li>
                <li><strong>Print Report:</strong> print_report.php?serial=SERIAL_NUMBER</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button class="btn" onclick="window.location.href='index.html'">Launch Application</button>
        </div>
    </div>

    <script>
        function testDatabase() {
            fetch('api/test_connection.php')
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('testResults');
                    if (data.success) {
                        results.innerHTML = '<p class="success">✓ Database connection successful</p>';
                    } else {
                        results.innerHTML = '<p class="error">✗ Database connection failed: ' + data.error + '</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('testResults').innerHTML = '<p class="error">✗ Test failed: ' + error + '</p>';
                });
        }

        function testUploads() {
            // Create a test file upload
            const formData = new FormData();
            const testFile = new Blob(['test'], { type: 'text/plain' });
            formData.append('test', testFile, 'test.txt');

            fetch('api/test_upload.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('testResults');
                    if (data.success) {
                        results.innerHTML = '<p class="success">✓ Upload directory writable</p>';
                    } else {
                        results.innerHTML = '<p class="error">✗ Upload test failed: ' + data.error + '</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('testResults').innerHTML = '<p class="error">✗ Upload test failed: ' + error + '</p>';
                });
        }

        function testQR() {
            fetch('api/generate_qr.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'serial=TEST123&domain=' + window.location.hostname
            })
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('testResults');
                    if (data.success) {
                        results.innerHTML = '<p class="success">✓ QR code generation working</p>';
                    } else {
                        results.innerHTML = '<p class="error">✗ QR generation failed: ' + data.error + '</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('testResults').innerHTML = '<p class="error">✗ QR test failed: ' + error + '</p>';
                });
        }
    </script>
</body>
</html>
