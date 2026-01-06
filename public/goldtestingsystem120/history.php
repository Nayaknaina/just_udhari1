<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $dateFilter = $_GET['date'] ?? date('Y-m-d'); // Default to today's date
    $customerFilter = $_GET['customer'] ?? '';
    
    // Build query with filters
    $query = "SELECT * FROM reports WHERE 1=1";
    $params = [];
    
    if (!empty($dateFilter)) {
        $query .= " AND DATE(created_at) = ?";
        $params[] = $dateFilter;
    }
    
    if (!empty($customerFilter)) {
        $query .= " AND customer_name LIKE ?";
        $params[] = '%' . $customerFilter . '%';
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/history.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="history-container-iframe">
        <div class="history-header-enhanced mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="history-title">
                    <h3 class="mb-1 text-primary fw-bold">Recent Reports</h3>
                    <span class="report-count badge bg-secondary"><?php echo count($reports); ?> reports found</span>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()" title="Refresh">
                    <i class="refresh-icon">↻</i> Refresh
                </button>
            </div>
            
           <div class="filter-section bg-light p-2 rounded-3 mb-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        
        <!-- Date Filter -->
        <input type="date" class="form-control form-control-sm" id="dateFilter"
               value="<?php echo htmlspecialchars($dateFilter); ?>" 
               onchange="applyFilters()" style="max-width: 180px;">

        <!-- Customer Filter -->
        <input type="text" class="form-control form-control-sm" id="customerFilter"
               placeholder="Search Customer..."
               value="<?php echo htmlspecialchars($customerFilter); ?>"
               onkeyup="debounceFilter()" autocomplete="off" style="max-width: 220px;">

        <!-- Buttons -->
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                Clear
            </button>
            <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                Apply
            </button>
        </div>
    </div>
</div>

        </div>

        <?php if (empty($reports)): ?>
            <div class="empty-state-enhanced text-center py-5">
                <div class="empty-icon mb-3" style="font-size: 3rem;">📄</div>
                <h5 class="text-muted mb-2">No reports found</h5>
                <p class="text-muted small mb-3">
                    <?php if (!empty($dateFilter) || !empty($customerFilter)): ?>
                    <?php else: ?>
                    <?php endif; ?>
                </p>
                <?php if (!empty($dateFilter) || !empty($customerFilter)): ?>
                    <button class="btn btn-outline-primary btn-sm" onclick="clearFilters()">
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="history-table-container shadow-sm rounded-3 overflow">
                <table class="history-table table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="fw-semibold">Serial</th>
                            <th class="fw-semibold">Customer</th>
                            <th class="fw-semibold">Date</th>
                            <th class="fw-semibold">Elements</th>
                            <th class="fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr class="history-row" data-serial="<?php echo $report['serial_number']; ?>">
                                <td class="serial-cell">
                                    <div class="serial-info">
                                        <span class="serial-number fw-bold text-primary"><?php echo htmlspecialchars($report['serial_number']); ?></span>
                                        <?php if ($report['image_path']): ?>
                                            <span class="image-indicator badge bg-info ms-2" title="Has image">📷</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="customer-cell">
                                    <div class="customer-info">
                                        <strong class="text-dark"><?php echo htmlspecialchars($report['customer_name']); ?></strong>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($report['sample_description']); ?></small>
                                    </div>
                                </td>
                                <td class="date-cell">
                                    <div class="date-info">
                                        <span class="date fw-semibold"><?php echo date('d/m/Y', strtotime($report['created_at'])); ?></span>
                                        <small class="time text-muted d-block"><?php echo date('H:i', strtotime($report['created_at'])); ?></small>
                                    </div>
                                </td>
                                <td class="elements-cell">
                                    <div class="element-tags">
                                        <?php if ($report['gold_au']): ?>
                                            <span class="element-tag gold badge bg-warning text-dark">AU: <?php echo $report['gold_au']; ?>%</span>
                                        <?php endif; ?>
                                        <?php if ($report['silver_ag']): ?>
                                            <span class="element-tag silver badge bg-secondary">AG: <?php echo $report['silver_ag']; ?>%</span>
                                        <?php endif; ?>
                                        <?php if ($report['copper_cu']): ?>
                                            <span class="element-tag copper badge bg-danger">CU: <?php echo $report['copper_cu']; ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="actions-cell text-center">
                                    <div class="action-buttons d-flex gap-1 justify-content-center">
                                        <button onclick="printReport('<?php echo $report['serial_number']; ?>')" 
                                                class="btn btn-success btn-sm" title="Print Report">
                                            🖨️
                                        </button>
                                        <!-- <button onclick="showQR('<?php echo $report['serial_number']; ?>', '<?php echo $report['qr_code_path']; ?>')" 
                                                class="btn btn-warning btn-sm" title="QR Code">
                                            📱
                                        </button> -->
                                        <button onclick="deleteReport('<?php echo $report['serial_number']; ?>')" 
                                                class="btn btn-danger btn-sm" title="Delete Report">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

 <div class="modal fade modal-disabled" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="qr-display">
                    <img id="qrImage" src="/placeholder.svg" alt="QR Code" class="img-fluid rounded border">
                    <p id="qrSerial" class="mt-3 fw-semibold text-primary"></p>
                </div>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let filterTimeout;
        
        function applyFilters() {
            const dateFilter = document.getElementById('dateFilter').value;
            const customerFilter = document.getElementById('customerFilter').value;
            
            const params = new URLSearchParams();
            if (dateFilter) params.append('date', dateFilter);
            if (customerFilter) params.append('customer', customerFilter);
            
            window.location.href = 'history.php?' + params.toString();
        }
        
        function clearFilters() {
            document.getElementById('dateFilter').value = '<?php echo date('Y-m-d'); ?>';
            document.getElementById('customerFilter').value = '';
            window.location.href = 'history.php?date=<?php echo date('Y-m-d'); ?>';
        }
        
        function debounceFilter() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 1000);
        }

        function printReport(serial) {
            window.open(`print_report.php?serial=${serial}`, '_blank');
        }

        function showQR(serial, qrPath) {
            document.getElementById('qrImage').src = qrPath;
            document.getElementById('qrSerial').textContent = `Serial: ${serial}`;
            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
            modal.show();
        }

        function deleteReport(serial) {
            if (confirm(`Are you sure you want to delete report ${serial}? This action cannot be undone.`)) {
                fetch('api/delete_report.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ serial: serial })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showAlert('Report deleted successfully!', 'success');
                        // Reload the page after a short delay
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('Error deleting report: ' + data.error, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error deleting report. Please try again.', 'danger');
                });
            }
        }
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }




        function showQR(serial, qrPath) {
    const qrModal = document.getElementById('qrModal');
    if (qrModal.classList.contains('modal-disabled')) {
        // Modal disabled hai, kuch mat karo
        return;
    }

    document.getElementById('qrImage').src = qrPath;
    document.getElementById('qrSerial').textContent = `Serial: ${serial}`;
    const modal = new bootstrap.Modal(qrModal);
    modal.show();
}

    </script>
</body>
</html>
