<?php
require_once 'config/database.php';

$serial = $_GET['serial'] ?? '';
$verify = $_GET['verify'] ?? '';

if (empty($serial)) {
    die('Serial number required');
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare("SELECT * FROM reports WHERE serial_number = ?");
    $stmt->execute([$serial]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$report) {
        die('Report not found');
    }
    
    if ($verify === '1') {
        $scan_stmt = $db->prepare("
            UPDATE qr_records 
            SET scan_count = scan_count + 1, last_scanned = NOW() 
            WHERE serial = ?
        ");
        $scan_stmt->execute([$serial]);
    }
    
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Testing Report - <?php echo htmlspecialchars($report['serial_number']); ?></title>
    <link rel="stylesheet" href="css/mobile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Added PWA and mobile optimization meta tags -->
    <meta name="theme-color" content="#1a365d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Gold Test Report">
    <link rel="apple-touch-icon" href="public/images/template-blank.png">
</head>
<body>
    <div class="mobile-container">
        <!-- Header -->
        <header class="mobile-header">
            <div class="header-content">
                <h1>Shree Gold Testing</h1>
                <p>X.R.F. Point Assay Report</p>
                <div class="serial-badge">
                    Serial: <?php echo htmlspecialchars($report['serial_number']); ?>
                    <!-- Added verification badge for QR scanned reports -->
                    <?php if ($verify === '1'): ?>
                    <span class="verified-badge">✓ Verified</span>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Report Content -->
        <main class="mobile-content">
            <!-- Basic Information Card -->
            <div class="info-card">
                <h2>Report Details</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Date & Time</span>
                        <span class="value"><?php echo date('d/m/Y H:i', strtotime($report['date_time'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Customer Name</span>
                        <span class="value"><?php echo htmlspecialchars($report['customer_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Sample</span>
                        <span class="value"><?php echo htmlspecialchars($report['sample_description']); ?></span>
                    </div>
                    <?php if ($report['weight']): ?>
                    <div class="info-item">
                        <span class="label">Weight</span>
                        <span class="value"><?php echo $report['weight']; ?> grams</span>
                    </div>
                    <?php endif; ?>
                    <?php if ($report['carat']): ?>
                    <div class="info-item">
                        <span class="label">Carat</span>
                        <span class="value"><?php echo $report['carat']; ?>K</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sample Image -->
            <?php if ($report['image_path']): ?>
            <div class="image-card">
                <h2>Sample Image</h2>
                <div class="image-container">
                    <img src="<?php echo htmlspecialchars($report['image_path']); ?>" alt="Sample Image" onclick="openImageModal(this.src)">
                </div>
            </div>
            <?php endif; ?>

            <!-- Element Concentrations -->
            <div class="elements-card">
                <h2>Element Concentrations (%)</h2>
                <div class="elements-list">
                    <?php
                    $elements = [
                        'gold_au' => 'Gold (AU)',
                        'copper_cu' => 'Copper (CU)',
                        'silver_ag' => 'Silver (AG)',
                        'zinc_zn' => 'Zinc (ZN)',
                        'cadmium_cd' => 'Cadmium (CD)',
                        'iridium_ir' => 'Iridium (IR)',
                        'tin_sn' => 'Tin (SN)',
                        'nickel_ni' => 'Nickel (NI)',
                        'lead_pb' => 'Lead (PB)',
                        'ruthenium_ru' => 'Ruthenium (RU)',
                        'platinum_pt' => 'Platinum (PT)',
                        'cobalt_co' => 'Cobalt (CO)',
                        'palladium_pd' => 'Palladium (PD)',
                        'osmium_os' => 'Osmium (OS)',
                        'ferrum_fe' => 'Ferrum (FE)'
                    ];
                    
                    foreach ($elements as $key => $name) {
                        echo "<div class='element-item'>";
                        echo "<span class='element-name'>{$name}</span>";
                        echo "<span class='element-value'>" . ($report[$key] ? $report[$key] : '0.00') . "%</span>";
                        echo "</div>";
                    }
                    
                    if ($report['others']) {
                        echo "<div class='element-item'>";
                        echo "<span class='element-name'>Others</span>";
                        echo "<span class='element-value'>" . htmlspecialchars($report['others']) . "</span>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Enhanced QR code display section -->
            <?php if ($report['qr_code_path']): ?>
            <div class="qr-card">
                <h2>QR Code</h2>
                <div class="qr-container">
                    <!-- Enhanced QR display with better error handling -->
                    <?php if (file_exists($report['qr_code_path'])): ?>
                        <img src="<?php echo htmlspecialchars($report['qr_code_path']); ?>?v=<?php echo time(); ?>" alt="QR Code" class="qr-image" onerror="this.parentElement.innerHTML='<p class=qr-error>QR Code not available</p>';">
                        <p class="qr-instruction">Scan this QR code to view this report</p>
                    <?php else: ?>
                        <p class="qr-error">QR Code file not found</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contact Information -->
            <div class="contact-card">
                <h2>Contact Information</h2>
                <div class="contact-info">
                    <p><strong>Cell:</strong> 8142414411</p>
                    <p><strong>Contact Person:</strong> N. Sainath</p>
                    <p><strong>Address:</strong> Shree Complex, D.No. 7-3-3(1), 1st Floor, Apaschi Mandiram Street, Beside RK Textiles, Vulliveedi Jn 1st Lane(Right), Ward - 7, Vizianagaram - 535 001, A.P.</p>
                </div>
            </div>
        </main>

        <!-- Action Buttons -->
        <div class="mobile-actions">
            <button onclick="shareReport()" class="btn-share">Share Report</button>
            <button onclick="window.open('view_report.php?serial=<?php echo $report['serial_number']; ?>', '_blank')" class="btn-full">Full View</button>
            <!-- Enhanced download QR button with better error handling -->
            <?php if ($report['qr_code_path'] && file_exists($report['qr_code_path'])): ?>
            <!-- <button onclick="downloadQR()" class="btn-download">Download QR</button> -->
            <?php endif; ?>
            <!-- Added certificate download button -->
            <!-- <button onclick="downloadCertificate()" class="btn-certificate">Save Certificate</button> -->
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeImageModal()">
        <div class="modal-content">
            <span class="close" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="/placeholder.svg" alt="Full Size Image">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        function shareReport() {
            if (navigator.share) {
                navigator.share({
                    title: 'Gold Testing Report - <?php echo $report['serial_number']; ?>',
                    text: 'View my gold testing report',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Report link copied to clipboard!');
                });
            }
        }

        function downloadQR() {
            const qrImage = document.querySelector('.qr-image');
            if (qrImage && qrImage.src) {
                const link = document.createElement('a');
                link.download = 'QR_<?php echo $report['serial_number']; ?>.png';
                link.href = qrImage.src;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('QR code not available for download');
            }
        }

        function downloadCertificate() {
            const protocol = window.location.protocol;
            const host = window.location.host;
            const certificateUrl = `${protocol}//${host}/view_report.php?serial=<?php echo $report['serial_number']; ?>`;
            
            // Try to use Web Share API for mobile devices
            if (navigator.share) {
                navigator.share({
                    title: 'Gold Testing Certificate - <?php echo $report['serial_number']; ?>',
                    text: 'Gold Testing Certificate',
                    url: certificateUrl
                }).catch(err => {
                    // Fallback to opening in new tab
                    window.open(certificateUrl, '_blank');
                });
            } else {
                // Fallback for browsers without Web Share API
                window.open(certificateUrl, '_blank');
            }
        }

        // Add touch gestures for better mobile experience
        let touchStartY = 0;
        let touchEndY = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartY = e.changedTouches[0].screenY;
        });

        document.addEventListener('touchend', function(e) {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartY - touchEndY;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe up - scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Swipe down - scroll to bottom
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                }
            }
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install button
            const installBtn = document.createElement('button');
            installBtn.textContent = 'Install App';
            installBtn.className = 'btn-install';
            installBtn.onclick = () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                    installBtn.remove();
                });
            };
            
            document.querySelector('.mobile-actions').appendChild(installBtn);
        });
    </script>
</body>
</html>
