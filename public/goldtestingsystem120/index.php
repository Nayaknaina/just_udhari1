<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shree Gold Testing - Report Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-lg border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <form id="reportForm" class="report-form" enctype="multipart/form-data">
                            <div class="mb-4">
                                <h2 class="h5 fw-bold text-primary mb-3 pb-2 border-bottom border-warning">Basic
                                    Information</h2>
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="customer_name" class="form-label fw-semibold">Customer
                                                    Name</label>
                                                <input type="text" class="form-control rounded-pill" id="customer_name"
                                                    name="customer_name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="weight" class="form-label fw-semibold">Weight
                                                    (grams)</label>
                                                <input type="number" class="form-control rounded-pill" id="weight"
                                                    name="weight" step="0.001" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="carat" class="form-label fw-semibold">Carat</label>
                                                <input type="number" class="form-control rounded-pill" id="carat"
                                                    name="carat" step="0.01" min="0" max="24">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="sample_description" class="form-label fw-semibold">Sample
                                                    Description</label>
                                                <input type="text" class="form-control rounded-pill"
                                                    id="sample_description" name="sample_description" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="date_time" class="form-label fw-semibold">Date &
                                                    Time</label>
                                                <input type="datetime-local" class="form-control" id="date_time"
                                                    name="date_time" required readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="image" class="form-label fw-semibold">Sample Image</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*">
                                                <div class="border border-2 border-dashed rounded mt-2 p-3 text-center"
                                                    id="imagePreview" style="min-height: 100px;">
                                                    <small class="text-muted">No image selected</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4" id="goldFormulaSection" style="display: none;">
                                <h2 class="h5 fw-bold text-primary mb-3 pb-2 border-bottom border-warning">Gold
                                    Composition</h2>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title text-warning">Pure Gold</h6>
                                                <h4 class="text-primary" id="goldPercentage">0%</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="card-title text-secondary">Alloy Metals</h6>
                                                <h4 class="text-primary" id="alloyPercentage">0%</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-info">Common Alloys</h6>
                                                <small class="text-muted" id="alloyTypes">Copper, Silver, Zinc,
                                                    Nickel</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h2 class="h5 fw-bold text-primary mb-3 pb-2 border-bottom border-warning">Element
                                    Concentration (%)</h2>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-4">
                                        <label for="gold_au" class="form-label fw-semibold">Gold (AU)%</label>
                                        <input type="number" class="form-control rounded-pill" id="gold_au"
                                            name="gold_au" step="0.01" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="silver_ag" class="form-label fw-semibold">Silver (AG)%</label>
                                        <input type="number" class="form-control rounded-pill" id="silver_ag"
                                            name="silver_ag" step="0.01" min="0" max="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="copper_cu" class="form-label fw-semibold">Copper (CU)%</label>
                                        <input type="number" class="form-control rounded-pill" id="copper_cu"
                                            name="copper_cu" step="0.01" min="0" max="100">
                                    </div>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                        id="expandElements" onclick="toggleElements()">
                                        <span class="expand-icon">+</span>
                                        <span class="expand-text ms-2">Show More Elements</span>
                                    </button>
                                </div>

                                <div class="collapse" id="additionalElements">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label for="zinc_zn" class="form-label fw-semibold">Zinc (ZN)%</label>
                                            <input type="number" class="form-control rounded-pill" id="zinc_zn"
                                                name="zinc_zn" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="cadmium_cd" class="form-label fw-semibold">Cadmium (CD)%</label>
                                            <input type="number" class="form-control rounded-pill" id="cadmium_cd"
                                                name="cadmium_cd" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="iridium_ir" class="form-label fw-semibold">Iridium (IR)%</label>
                                            <input type="number" class="form-control rounded-pill" id="iridium_ir"
                                                name="iridium_ir" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tin_sn" class="form-label fw-semibold">Tin (SN)%</label>
                                            <input type="number" class="form-control rounded-pill" id="tin_sn"
                                                name="tin_sn" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="nickel_ni" class="form-label fw-semibold">Nickel (NI)%</label>
                                            <input type="number" class="form-control rounded-pill" id="nickel_ni"
                                                name="nickel_ni" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="lead_pb" class="form-label fw-semibold">Lead (PB)%</label>
                                            <input type="number" class="form-control rounded-pill" id="lead_pb"
                                                name="lead_pb" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ruthenium_ru" class="form-label fw-semibold">Ruthenium
                                                (RU)%</label>
                                            <input type="number" class="form-control rounded-pill" id="ruthenium_ru"
                                                name="ruthenium_ru" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="platinum_pt" class="form-label fw-semibold">Platinum
                                                (PT)%</label>
                                            <input type="number" class="form-control rounded-pill" id="platinum_pt"
                                                name="platinum_pt" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="cobalt_co" class="form-label fw-semibold">Cobalt (CO)%</label>
                                            <input type="number" class="form-control rounded-pill" id="cobalt_co"
                                                name="cobalt_co" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="palladium_pd" class="form-label fw-semibold">Palladium
                                                (PD)%</label>
                                            <input type="number" class="form-control rounded-pill" id="palladium_pd"
                                                name="palladium_pd" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="osmium_os" class="form-label fw-semibold">Osmium (OS)%</label>
                                            <input type="number" class="form-control rounded-pill" id="osmium_os"
                                                name="osmium_os" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ferrum_fe" class="form-label fw-semibold">Ferrum (FE)%</label>
                                            <input type="number" class="form-control rounded-pill" id="ferrum_fe"
                                                name="ferrum_fe" step="0.01" min="0" max="100">
                                        </div>
                                        <div class="col-12">
                                            <label for="others" class="form-label fw-semibold">Others%</label>
                                            <textarea class="form-control rounded-3" id="others" name="others" rows="2"
                                                placeholder="Other elements and their concentrations"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-3 justify-content-center pt-3 border-top border-2 border-dashed">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold"
                                    id="submitBtn">
                                    <span class="btn-text">Generate</span>
                                    <div class="spinner-border spinner-border-sm ms-2 d-none" id="loadingSpinner"></div>
                                </button>
                                <button type="reset"
                                    class="btn btn-outline-primary px-4 py-2 rounded-3 fw-semibold">Clear</button>
                            </div>
                        </form>

                        <!-- Overlay + Popup -->
                        <div id="successOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
                            style="background: rgba(0,0,0,0.5); z-index: 2000;">

                            <div class="position-absolute top-50 start-50 translate-middle w-auto">
                                <div class="alert alert-success shadow-lg rounded-4 border-0 text-center p-4 animate__animated"
                                    id="successMessage">

                                    <!-- Success Icon -->
                                    <div class="mb-3">
                                        <i
                                            class="bi bi-check-circle-fill text-white bg-success rounded-circle p-3 fs-1"></i>
                                    </div>

                                    <!-- Heading -->
                                    <h4 class="alert-heading mb-2">Report Generated!</h4>

                                    <!-- Serial Info -->
                                    <p class="mb-3 small text-dark">
                                        Serial Number: <span class="fw-bold text-success" id="generatedSerial"></span>
                                    </p>

                                    <!-- Action Button -->
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-success px-4" id="viewReportBtn">
                                            <i class="bi bi-eye-fill me-1"></i> View Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Animate.css -->
                        <link rel="stylesheet"
                            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

                        <script>
                            function showSuccessPopup(serial) {
                                const overlay = document.getElementById("successOverlay");
                                const popup = document.getElementById("successMessage");
                                const serialSpan = document.getElementById("generatedSerial");

                                // Set Serial Number
                                serialSpan.textContent = serial;

                                // Show overlay + popup
                                overlay.classList.remove("d-none");
                                popup.classList.add("animate__zoomIn");

                                // Auto close after 3 sec
                                setTimeout(() => {
                                    popup.classList.remove("animate__zoomIn");
                                    popup.classList.add("animate__fadeOut");

                                    setTimeout(() => {
                                        overlay.classList.add("d-none");
                                        popup.classList.remove("animate__fadeOut");
                                    }, 800); // fadeOut duration
                                }, 3000);
                            }
                        </script>

                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card shadow-lg border-0 rounded-4 h-100">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h3 class="h5 mb-0 fw-bold">History</h3>
                    </div>
                    <div class="card-body p-0">
                        <iframe id="historyFrame" title="History" src="history.php"
                            class="w-100 border-0 rounded-bottom-4"
                            style="height: calc(100vh - 200px); min-height: 400px;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        function toggleElements() {
            const additionalElements = document.getElementById('additionalElements');
            const expandBtn = document.getElementById('expandElements');
            const expandIcon = expandBtn.querySelector('.expand-icon');
            const expandText = expandBtn.querySelector('.expand-text');

            const bsCollapse = new bootstrap.Collapse(additionalElements, {
                toggle: false
            });

            if (additionalElements.classList.contains('show')) {
                bsCollapse.hide();
                expandIcon.textContent = '+';
                expandText.textContent = 'Show More Elements';
                expandBtn.classList.remove('btn-primary');
                expandBtn.classList.add('btn-outline-secondary');
            } else {
                bsCollapse.show();
                expandIcon.textContent = '−';
                expandText.textContent = 'Show Less Elements';
                expandBtn.classList.remove('btn-outline-secondary');
                expandBtn.classList.add('btn-primary');
            }
        }


    </script>
</body>

</html>