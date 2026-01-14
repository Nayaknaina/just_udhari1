<style>
    /* Global fixes for visibility and alignment */
    .form-control {
        color: #333 !important; /* Darker text for better visibility */
        font-weight: 600;
    }
    ::placeholder {
        color: #999 !important;
        opacity: 1;
    }

    /* Customer Summary Card */
    .customer-summary-card {
        background: linear-gradient(to right, #e3f2fd, #bbdefb);
        border-radius: 12px;
        padding: 6px 15px;
        margin-bottom: 2px;
        border-left: 5px solid #2196f3;
        display: none; /* Hidden by default */
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Attractive Payment Toggle */
    .payment-toggle-container {
        display: flex;
        background: #f1f3f4;
        border-radius: 50px;
        padding: 4px;
        position: relative;
        border: 1px solid #ddd;
    }
    .payment-option {
        flex: 1;
        text-align: center;
        padding: 8px 5px;
        border-radius: 50px;
        cursor: pointer;
        font-weight: bold;
        color: #555;
        transition: all 0.3s;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }
    .payment-option.active {
        background: #fff;
        color: #ff6e26;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .payment-option:hover:not(.active) {
        background: rgba(255,255,255,0.5);
    }

    /* Collapsible Section Styles */
    .loan-section-toggle {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .girvi_item_scroll {
        max-height: 55vh;
        overflow-y: auto;
        padding: 4px;
        scrollbar-width: thin;
        scrollbar-color: #ff6e26 #f1f1f1;
    }
    
    .btn-gradient-primary {
        background: linear-gradient(135deg, #ff6e26 0%, #ff9f43 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(255, 110, 38, 0.4);
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #ff5e13 0%, #ff8c33 100%);
        color: white;
        transform: translateY(-1px);
    }
    
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #444;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

</style>

<div class="row m-0 p-1">
    
    <!-- Customer Summary (Appears on Selection) -->
    <div class="col-12 p-0 mb-1">
        <div class="customer-summary-card" id="cust_summary_block" style="padding: 4px 10px; margin-bottom: 2px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                     <span class="d-block font-xs text-primary font-weight-bold" style="font-size: 0.7rem;">CUSTOMER HISTORY</span>
                     <h6 class="m-0 text-dark font-weight-bold" id="cust_summary_name" style="font-size: 0.9rem;">Customer Name</h6>
                </div>
                <div class="text-right">
                     <span class="badge badge-pill badge-light text-primary border border-primary px-2 py-0" style="font-size: 0.75rem;">
                        Active Loans: <b id="cust_summary_count">0</b>
                     </span>
                     <div class="font-xs text-muted mt-0">
                         Pending: <span class="text-danger font-weight-bold" id="cust_summary_due">₹ 0</span>
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="col-12 p-0 mb-2">
        <div class="d-flex justify-content-between align-items-center mb-1" style="background: #fff; padding: 5px 10px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
            <div class="d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 28px; height: 28px;">
                    <i class="fa fa-cubes text-warning" style="font-size: 0.9rem;"></i>
                </div>
                <h6 class="m-0 font-weight-bold text-dark section-title">Items to Receive</h6>
            </div>
            
            <div class="btn-group shadow-sm" role="group">
                 <button type="button" class="btn btn-sm btn-outline-secondary btn-roundhalf font-sm px-2 py-0 border-right-0 font-weight-bold" data-toggle="modal" data-target="#item_category_modal" title="Add New Category" style="border-top-right-radius: 0; border-bottom-right-radius: 0; color: #555; font-size: 0.75rem;">
                    <i class="fa fa-plus"></i> Add Category
                </button>
                 <button type="button" class="btn btn-sm btn-primary btn-roundhalf font-sm px-3 py-0 font-weight-bold" id="btn_add_more_item" style="border-top-left-radius: 0; border-bottom-left-radius: 0; font-size: 0.75rem;">
                    <i class="fa fa-plus-circle"></i> Add Item
                </button>
            </div>
        </div>
        
        <div class="girvi_item_scroll" id="items_container">
            <!-- Initial Item -->
             @include('vendors.girvi.girviformpart.item_row_template', ['index' => 0])
        </div>
        
        <!-- Total Valuation Display -->
        <div class="p-2 mt-2 text-right" style="background: #fcfcfc; border-top: 1px dashed #e0e0e0;">
            <span class="text-muted font-sm mr-2">Total Valuation:</span>
            <span class="font-weight-bold text-success" style="font-size: 1.2rem;" id="total_valuation_display">₹ 0</span>
        </div>
    </div>

    <!-- Loan Toggle Button -->
    <div class="col-12 p-0 text-center mb-2" id="loan_toggle_container">
        <button type="button" class="btn btn-gradient-primary btn-roundhalf px-4 py-1 font-weight-bold shadow-lg loan-section-toggle" onclick="toggleLoanDetails()" style="font-size: 0.9rem;">
            <i class="fa fa-hand-holding-usd mr-1"></i> Add Loan & Issue Cash
        </button>
    </div>

    <!-- Loan Details Section (Hidden by Default) -->
    <div class="col-12 p-0 collapse" id="loan_details_section">
        
        <!-- Header with Close -->
        <div class="d-flex align-items-center mb-2 px-1">
            <h6 class="m-0 font-weight-bold text-dark section-title flex-grow-1">
                <i class="fa fa-file-contract text-primary mr-1"></i> New Loan Agreement
            </h6>
             <button type="button" class="btn btn-xs btn-outline-danger btn-circle" onclick="toggleLoanDetails()" title="Cancel Loan">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
            <div class="card-body p-2">
                
                <!-- Hidden Valuation Field -->
                <input type="hidden" name="valuation" id="total_valuation" value="0">

                <!-- Grant Amount (Big Input) -->
                <div class="form-group mb-2">
                    <label class="font-xs text-muted mb-0 ml-2 font-weight-bold">LOAN PRINCIPAL AMOUNT</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-0 text-dark font-weight-bold pl-3" style="font-size: 1.2rem;">₹</span>
                        </div>
                        <input type="number" class="form-control form-control-sm border-0 bg-light font-weight-bold text-dark" id="grant_amount" name="grant" placeholder="0.00" style="font-size: 1.3rem; border-radius: 12px; height: 40px;">
                    </div>
                </div>

                <!-- Terms Grid -->
                <div class="row mb-3">
                    <div class="col-4 pr-1">
                        <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">INTEREST %</label>
                        <input type="number" step="0.01" class="form-control btn-roundhalf text-center bg-white border shadow-sm" id="interest_rate" name="interest" placeholder="Rate">
                    </div>
                    <div class="col-4 px-1">
                         <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">TENURE (M)</label>
                         <input type="number" class="form-control btn-roundhalf text-center bg-white border shadow-sm" id="tenure_months" name="tenure" value="12">
                    </div>
                     <div class="col-4 pl-1">
                         <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">TYPE</label>
                         <select class="form-control btn-roundhalf p-0 pl-2 bg-white border shadow-sm" name="interesttype" id="interest_type" style="font-size: 0.8rem;">
                             <option value="si">Simple</option>
                             <option value="ci">Compound</option>
                         </select>
                    </div>
                </div>

                <!-- Dates -->
                <div class="row mb-3">
                     <div class="col-6 pr-1">
                         <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">ISSUE DATE</label>
                         <input type="date" class="form-control btn-roundhalf shadow-sm border" name="issue" id="issue_date" value="{{ date('Y-m-d') }}">
                     </div>
                     <div class="col-6 pl-1">
                         <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">RETURN DATE</label>
                         <input type="date" class="form-control btn-roundhalf bg-light border-0" name="return" id="return_date" readonly>
                     </div>
                </div>

                <!-- Calculation Summary -->
                <div class="p-3 mb-3" style="background: #fcfcfc; border-radius: 12px; border: 1px solid #eee;">
                     <!-- Hidden Fields -->
                     <input type="hidden" name="principal_val" id="principal_val">
                     <input type="hidden" name="interest_val" id="interest_val">
                     <input type="hidden" name="payable" id="payable_val">
                     
                     <div class="row text-center">
                         <div class="col-6 border-right">
                             <small class="text-muted d-block font-xs text-uppercase font-weight-bold">Interest</small>
                             <span class="text-danger font-weight-bold h6" id="interest_payable_disp">₹ 0</span>
                         </div>
                         <div class="col-6">
                             <small class="text-muted d-block font-xs text-uppercase font-weight-bold">Net Payable</small>
                             <span class="text-primary font-weight-bold h5" id="total_payable_disp">₹ 0</span>
                         </div>
                     </div>
                </div>

                <!-- Payment Selection (Custom Toggle) -->
                <!-- Payment Selection (Custom Toggle) -->
                <label class="font-xs text-muted mb-1 ml-1 font-weight-bold">PAYMENT MODE</label>
                
                 <!-- Custom Payment Toggle Styles -->
               <style>
                    .payment-toggle-container {
                        display: flex;
                        background: #f1f3f5;
                        padding: 2px;
                        border-radius: 50px;
                        position: relative;
                        margin-bottom: 0.5rem !important;
                    }
                    .payment-option {
                        flex: 1;
                        text-align: center;
                        padding: 4px 5px;
                        font-size: 0.8rem;
                        font-weight: bold;
                        color: #888;
                        cursor: pointer;
                        border-radius: 50px;
                        transition: all 0.3s;
                    }
                    .payment-option:hover {
                        color: #555;
                    }
                    .payment-option.active {
                        background: #fff;
                        color: #ff6e26 !important; /* Theme color */
                        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    }
               </style>

                <div class="payment-toggle-container mb-2">
                    <input type="hidden" name="medium" id="pay_medium" value="cash">
                    <div class="payment-option active" onclick="setReceivePaymentMode('cash', this)">
                        <i class="fa fa-money-bill-wave"></i> Cash
                    </div>
                    <div class="payment-option" onclick="setReceivePaymentMode('on', this)">
                        <i class="fa fa-university"></i> Online
                    </div>
                    <div class="payment-option" onclick="setReceivePaymentMode('mix', this)">
                        <i class="fa fa-exchange-alt"></i> Mix
                    </div>
                </div>

                <!-- Split Payment Inputs (Hidden by default) -->
                <!-- Split Payment Inputs (Hidden by default) -->
                <div class="mb-3 px-3" id="split_payment_section" style="display: none;">
                    <div class="bg-white rounded shadow-sm border p-3" style="border-color: #f0f0f0 !important;">
                        <h6 class="font-xs text-muted font-weight-bold mb-3 text-uppercase border-bottom pb-2">Split Payment Details</h6>
                        <div class="row">
                            <div class="col-6 pr-2">
                                <label class="font-xs text-muted font-weight-bold mb-1">CASH</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0 text-success"><i class="fa fa-money-bill-wave"></i></span>
                                    </div>
                                    <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark" id="split_cash" name="split_cash" placeholder="0" oninput="calculateSplit('cash')" style="height: 35px;">
                                </div>
                            </div>
                            <div class="col-6 pl-2">
                                <label class="font-xs text-muted font-weight-bold mb-1">ONLINE</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0 text-info"><i class="fa fa-university"></i></span>
                                    </div>
                                    <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark" id="split_online" name="split_online" placeholder="0" oninput="calculateSplit('online')" style="height: 35px;">
                                </div>
                            </div>
                        </div>
                        <small class="text-danger font-weight-bold mt-2 d-none" id="split_error"><i class="fa fa-exclamation-circle"></i> Total must match Loan Amount!</small>
                    </div>
                </div>

                 <button type="button" onclick="submitGirviForm('save')" class="btn btn-gradient-primary btn-block btn-roundhalf font-weight-bold py-2 shadow-lg" style="font-size: 1rem;">
                    <i class="fa fa-check-circle mr-1"></i> CONFIRM & SAVE RECORD
                </button>

            </div>
        </div>
    </div>
</div>

<template id="item_row_template_js">
     @include('vendors.girvi.girviformpart.item_row_template', ['index' => 'INDEX_PLACEHOLDER'])
</template>

<script>
    function toggleLoanDetails() {
         $('#loan_details_section').collapse('toggle');
         $('#loan_toggle_container').toggle();
    }
    


    function setReceivePaymentMode(mode, elem) {
        $('#pay_medium').val(mode);
        $('.payment-option').removeClass('active');
        $(elem).addClass('active');

        if(mode === 'mix') {
            $('#split_payment_section').slideDown(200);
            // Default split: Full Cash, 0 Online
            let total = parseFloat($('#grant_amount').val()) || parseFloat($('#total_payable_disp').text().replace(/[^\d.-]/g, '')) || 0;
            $('#split_cash').val(total);
            $('#split_online').val(0);
        } else {
            $('#split_payment_section').slideUp(200);
        }
    }

    function calculateSplit(source) {
        // limit to grant_amt
        let total = parseFloat($('#grant_amount').val()) || parseFloat($('#total_payable_disp').text().replace(/[^\d.-]/g, '')) || 0;
        let cash = parseFloat($('#split_cash').val()) || 0;
        let online = parseFloat($('#split_online').val()) || 0;

        if(source === 'cash') {
            let remain = total - cash;
            $('#split_online').val(Math.max(0, remain));
        } else {
            let remain = total - online;
            $('#split_cash').val(Math.max(0, remain));
        }
    }

    // --- Category Creation Listener ---
    $(document).on('categoryformsubmit', function(e) {
        let data = e.detail;
        if(data.errors) {
             $.each(data.errors, function(i, v){
                 // Assuming toastr is available
                 if(typeof toastr !== 'undefined') toastr.error(v[0]);
                 else alert(v[0]);
             });
        } else if(data.error) {
            if(typeof toastr !== 'undefined') toastr.error(data.msg);
            else alert(data.msg);
        } else {
            if(typeof toastr !== 'undefined') toastr.success('Category Added!');
            
            $("#category_modal_close").trigger('click');
            
            // Add to all existing category dropdowns
            // Using Name as value to be consistent with 'Gold'/'Silver'
            let newOption = `<option value="${data.item.name}">${data.item.name}</option>`;
            $('select[name^="category"]').append(newOption);
        }
    });
</script>
