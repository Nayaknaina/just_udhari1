<?php
require_once 'config/database.php';

$serial = $_GET['serial'] ?? '';
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
    <link rel="stylesheet" href="css/report.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="report-container">
        <div class="report-template">
            <!-- Template overlay with precise positioning -->
            <div class="template-background">
                <img src="public/images/template-blank.png" alt="Report Template" class="template-image">
            </div>
            
            <!-- Overlay content positioned exactly over template -->
            <div class="overlay-content">
                <!-- Header Information -->
                <div class="header-info">
                    <div class="serial-number"><?php echo htmlspecialchars($report['serial_number']); ?></div>
                    <div class="contact-cell">Cell: 8142414411<br>N. Sainath</div>
                </div>
                
                <!-- Basic Information -->
                <div class="basic-info">
                    <div class="date-time">
                        <span class="label">Date/Time:</span>
                        <span class="value"><?php echo date('d/m/Y H:i', strtotime($report['date_time'])); ?></span>
                    </div>
                    <div class="customer-name">
                        <span class="label">Name:</span>
                        <span class="value"><?php echo htmlspecialchars($report['customer_name']); ?></span>
                    </div>
                    <div class="sample-desc">
                        <span class="label">Sample:</span>
                        <span class="value"><?php echo htmlspecialchars($report['sample_description']); ?></span>
                    </div>
                    <div class="weight-carat">
                        <span class="label">Wt:</span>
                        <span class="value"><?php echo $report['weight'] ? $report['weight'] . 'g' : ''; ?></span>
                        <span class="label">Carat:</span>
                        <span class="value"><?php echo $report['carat'] ? $report['carat'] . 'K' : ''; ?></span>
                    </div>
                </div>
                
                <!-- Sample Image -->
                <?php if ($report['image_path']): ?>
                <div class="sample-image">
                    <img src="<?php echo htmlspecialchars($report['image_path']); ?>" alt="Sample Image">
                </div>
                <?php endif; ?>
                
                <!-- QR Code -->
                <?php if ($report['qr_code_path']): ?>
                <div class="qr-code">
                    <?php if (file_exists($report['qr_code_path'])): ?>
                        <img src="<?php echo htmlspecialchars($report['qr_code_path']); ?>?v=<?php echo time(); ?>" alt="QR Code" onerror="this.style.display='none';">
                    <?php else: ?>
                        <div class="qr-error">QR Code not found</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Element Concentrations Table -->
                <div class="elements-table">
                    <div class="elements-left">
                        <div class="element-row">
                            <span class="element">Gold (AU):</span>
                            <span class="concentration"><?php echo $report['gold_au'] ? $report['gold_au'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Copper (CU):</span>
                            <span class="concentration"><?php echo $report['copper_cu'] ? $report['copper_cu'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Silver (AG):</span>
                            <span class="concentration"><?php echo $report['silver_ag'] ? $report['silver_ag'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Zinc (ZN):</span>
                            <span class="concentration"><?php echo $report['zinc_zn'] ? $report['zinc_zn'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Cadmium (CD):</span>
                            <span class="concentration"><?php echo $report['cadmium_cd'] ? $report['cadmium_cd'] : '0.00'; ?>%</span>
                        </div>
                        
                        <?php if ($report['others']): ?>
                        <div class="element-row">
                            <span class="element">Others:</span>
                            <span class="concentration"><?php echo htmlspecialchars($report['others']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="elements-middle">
                        <div class="element-row">
                            <span class="element">Iridium (IR):</span>
                            <span class="concentration"><?php echo $report['iridium_ir'] ? $report['iridium_ir'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Tin (SN):</span>
                            <span class="concentration"><?php echo $report['tin_sn'] ? $report['tin_sn'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Nickel (NI):</span>
                            <span class="concentration"><?php echo $report['nickel_ni'] ? $report['nickel_ni'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Lead (PB):</span>
                            <span class="concentration"><?php echo $report['lead_pb'] ? $report['lead_pb'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Ruthenium (RU):</span>
                            <span class="concentration"><?php echo $report['ruthenium_ru'] ? $report['ruthenium_ru'] : '0.00'; ?>%</span>
                        </div>
                    </div>
                    
                    <div class="elements-right">
                        <div class="element-row">
                            <span class="element">Platinum (PT):</span>
                            <span class="concentration"><?php echo $report['platinum_pt'] ? $report['platinum_pt'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Cobalt (CO):</span>
                            <span class="concentration"><?php echo $report['cobalt_co'] ? $report['cobalt_co'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Palladium (PD):</span>
                            <span class="concentration"><?php echo $report['palladium_pd'] ? $report['palladium_pd'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Osmium (OS):</span>
                            <span class="concentration"><?php echo $report['osmium_os'] ? $report['osmium_os'] : '0.00'; ?>%</span>
                        </div>
                        
                        <div class="element-row">
                            <span class="element">Ferrum (FE):</span>
                            <span class="concentration"><?php echo $report['ferrum_fe'] ? $report['ferrum_fe'] : '0.00'; ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="report-actions">
            <button onclick="window.print()" class="btn-print">Print Report</button>
            <?php if ($report['qr_code_path'] && file_exists($report['qr_code_path'])): ?>
            <!-- <button onclick="downloadQR()" class="btn-download">Download QR</button> -->
            <?php endif; ?>
            <!-- <button onclick="openMobileView()" class="btn-mobile">Mobile View</button> -->
            <button onclick="window.close()" class="btn-close">Close</button>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.report-template');
            const opt = {
                margin: 0.5,
                filename: 'gold_report_<?php echo $report['serial_number']; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        function downloadQR() {
            const qrImage = document.querySelector('.qr-code img');
            if (qrImage) {
                const link = document.createElement('a');
                link.download = 'QR_<?php echo $report['serial_number']; ?>.png';
                link.href = qrImage.src;
                link.click();
            }
        }

        function openMobileView() {
            const protocol = window.location.protocol;
            const host = window.location.host;
            const mobileUrl = `${protocol}//${host}/mobile_view.php?serial=<?php echo $report['serial_number']; ?>&verify=1`;
            window.open(mobileUrl, '_blank');
        }
    </script>
</body>
</html>
