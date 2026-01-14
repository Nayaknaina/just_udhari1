<div class="row m-0 p-1">

    <!-- Customer Summary (Compact) -->
    <div class="col-12 p-0 mb-1">
        <div class="customer-summary-card py-2 px-3" id="ret_cust_summary_block" style="display: block; border-radius: 12px; background: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                     <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm mr-2" style="width: 35px; height: 35px;">
                        <i class="fa fa-user text-primary font-sm"></i>
                     </div>
                     <div>
                         <h6 class="m-0 text-dark font-weight-bold fetch_custo_name" style="font-size: 0.9rem; line-height: 1.1;">Select Customer</h6>
                         <small class="text-muted fetch_custo_girvi_num font-xs">GRV-ID</small>
                     </div>
                </div>
                <div>
                     <span class="badge badge-pill badge-primary shadow-sm px-2 py-1 font-xs">
                        Active: <b id="ret_active_count" class="ml-1">0</b>
                     </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Items List (Compact) -->
    <div class="col-12 p-0 mb-2">


        <div class="d-flex justify-content-between align-items-center mb-1 bg-white rounded-lg shadow-sm px-2 py-1">
            <h6 class="m-0 font-weight-bold text-dark font-sm pl-1">Items to Return</h6>
            <div class="custom-control custom-checkbox scale-90">
                <input type="checkbox" class="custom-control-input" id="check_all_return_items">
                <label class="custom-control-label font-weight-bold text-muted font-xs pt-1" for="check_all_return_items">SELECT ALL</label>
            </div>
        </div>

        <!-- Scrollable Item List (Compact Ht) -->
        <div class="girvi_item_scroll" id="return_items_container" style="background: #fff; border-radius: 10px; min-height: 120px; max-height: 30vh; overflow-y: auto; border: 1px solid #f0f0f0;">
            <!-- Placeholder -->
            <div class="text-center py-5" id="return_placeholder">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                    <i class="fa fa-search fa-lg text-secondary opacity-50"></i>
                </div>
                <h6 class="text-muted font-xs">Search Customer to View Items</h6>
            </div>
        </div>
    </div>

    <!-- Totals & Actions (Compact) -->
    <div class="col-12 p-0">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
            <div class="card-body p-2">
                
                <!-- Calculation Row (Compact) -->
                <div class="row text-center mb-2 mx-0 bg-light rounded py-2 border">
                    <div class="col-4 border-right px-1">
                        <small class="text-muted d-block font-xs font-weight-bold text-uppercase">Principal</small>
                        <h6 class="mb-0 font-weight-bold text-dark font-sm" id="ret_total_principal">0</h6>
                    </div>
                    <div class="col-4 border-right px-1">
                        <small class="text-muted d-block font-xs font-weight-bold text-uppercase">Interest</small>
                        <h6 class="mb-0 font-weight-bold text-danger font-sm" id="ret_total_interest">0</h6>
                    </div>
                    <div class="col-4 px-1">
                        <small class="text-muted d-block font-xs font-weight-bold text-uppercase">Payable</small>
                        <h6 class="mb-0 font-weight-bold text-success font-sm" id="ret_total_payable">0</h6>
                    </div>
                </div>

                <hr class="my-1 opacity-50">

                <!-- Payment & Action Row -->
                <div class="row align-items-center">
                    <div class="col-12 mb-2">
                       <!-- Compact Toggle -->
                       <style>
                            .payment-toggle-container {
                                display: flex;
                                background: #f1f3f5;
                                padding: 2px;
                                border-radius: 50px;
                            }
                            .payment-option {
                                flex: 1;
                                text-align: center;
                                padding: 5px 2px;
                                font-size: 0.8rem;
                                font-weight: bold;
                                color: #888;
                                cursor: pointer;
                                border-radius: 50px;
                                transition: all 0.2s;
                            }
                            .payment-option.active {
                                background: #fff;
                                color: #ff6e26 !important;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                            }
                            .scale-90 { transform: scale(0.9); transform-origin: right center; }
                       </style>
                       
                       <div class="payment-toggle-container mb-0">
                            <input type="hidden" name="return_medium" id="ret_pay_medium" value="cash">
                            <div class="payment-option active" onclick="setReturnPaymentMode('cash', this)">
                                <i class="fa fa-money-bill-wave mr-1"></i> Cash
                            </div>
                            <div class="payment-option" onclick="setReturnPaymentMode('on', this)">
                                <i class="fa fa-university mr-1"></i> Online
                            </div>
                            <div class="payment-option" onclick="setReturnPaymentMode('mix', this)">
                                <i class="fa fa-exchange-alt mr-1"></i> Mix
                            </div>
                        </div>
                    </div>

                    <!-- Split Payment Section (Hidden) -->
                    <div class="col-12 mb-3 px-3" id="ret_split_payment_section" style="display:none;">
                         <div class="bg-white rounded shadow-sm border p-3" style="border-color: #f0f0f0 !important;">
                            <h6 class="font-xs text-muted font-weight-bold mb-3 text-uppercase border-bottom pb-2">Split Return Payment</h6>
                            <div class="row">
                                <div class="col-6 pr-2">
                                    <label class="font-xs text-muted font-weight-bold mb-1">CASH</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0 text-success pl-2 pr-1"><i class="fa fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark font-xs" id="ret_split_cash" placeholder="Cash" oninput="calculateReturnSplit('cash')" style="height: 30px;">
                                    </div>
                                </div>
                                <div class="col-6 pl-2">
                                    <label class="font-xs text-muted font-weight-bold mb-1">ONLINE</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0 text-info pl-2 pr-1"><i class="fa fa-university"></i></span>
                                        </div>
                                        <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark font-xs" id="ret_split_online" placeholder="Online" oninput="calculateReturnSplit('online')" style="height: 30px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-gradient-success btn-block btn-roundhalf font-weight-bold py-2 shadow-sm" id="btn_process_return" style="font-size: 0.95rem;">
                            <i class="fa fa-check-circle mr-1"></i> Confirm & Release
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Scoped Script for Return Functionality (`return.blade.php`)

    window.returnItems = []; // Stores the currently loaded active items

    // --- 1. GLOBAL LISTENER: Triggered when a customer is searched & loaded in `common.blade.php` ---
    $(document).on('customer_loaded', function(e, data) {
         // console.log("Girvi Return Controller: Customer Data Received", data);
         toastr.info("Syncing Customer Data...", "Return Tab");
         renderReturnItems(data);
    });

    // --- 2. RENDER LOGIC: Converts Backend Data to HTML List ---
    window.renderReturnItems = function(data) {
        console.log("Return Render INIT:", data);
        if(!data) { console.error("No Data passed to renderReturnItems"); return; }
        let container = $('#return_items_container');
        container.empty();
        window.returnItems = []; 
        let activeItems = [];

        // 1. Update Header Info
        if(data && data.girvi) {
             $('#ret_cust_summary_block').find('.fetch_custo_name').text(data.girvi.custo_name || 'Customer');
             $('#ret_cust_summary_block').find('.fetch_custo_girvi_num').text(data.girvi.girvi_id ? 'GRV-'+data.girvi.girvi_id : '');
        }

        // 2. Parse Items (MERGE OLD & NEW to show EVERYTHING)
        let batches = [];
        if(data && data.new) {
             let newB = Array.isArray(data.new) ? data.new : Object.values(data.new);
             batches = batches.concat(newB);
        }
        if(data && data.old) {
             let oldB = Array.isArray(data.old) ? data.old : Object.values(data.old);
             batches = batches.concat(oldB);
        }

        // --- VISUAL CONFIRMATION ---
        if(batches.length === 0) {
             console.warn("No Batches Found!");
             if(typeof toastr !== 'undefined') toastr.warning("No Data Found in Database!", "Debugger");
             
             // FORCE INJECT TEST ITEM to prove UI works
             activeItems.push({
                 id: 99999,
                 detail: "⚠️ NO DATA (System Test Item)",
                 category: "Test",
                 receipt: "000",
                 image: "",
                 principal: 1000,
                 interest: 100,
                 payable: 1100,
                 action: 'release', 
                 selected: false,
                 amount: 0
             });
        } else {
            if(typeof toastr !== 'undefined') toastr.success(`Found ${batches.length} Batches`, "Debugger");
        }
            batches.forEach(batch => {
            if(batch.items) {
                let b_items = [];
                // Safe Array Conversion
                if(Array.isArray(batch.items)) b_items = batch.items;
                else if(typeof batch.items === 'object' && batch.items !== null) b_items = Object.values(batch.items);

                b_items.forEach(item => {
                    // FORCE DEFAULTS
                    let p = parseFloat(item.principal) || 0;
                    let i = parseFloat(item.interest) || 0;
                    
                    // Try Flip Logic Safely
                    try {
                         if((item.flip == 1 || item.flip == '1') && item.activeflip) {
                            p = parseFloat(item.activeflip.post_p) || p;
                            i = parseFloat(item.activeflip.post_i) || i;
                         }
                    } catch(e) { console.error("Flip Logic Error", e); }

                    // Push to list (Show ALL items for this customer context)
                    activeItems.push({
                        id: item.id,
                        detail: item.detail || 'Unknown Item',
                        category: item.category || 'General',
                        receipt: item.receipt || '---',
                        image: item.image || '',
                        principal: p,
                        interest: i,
                        payable: p + i,
                        action: 'release', 
                        selected: false,
                        amount: 0 // Initialize amount
                    });
                });
            }
            });
        }

        // 3. Update UI State
        console.log("Active Items Parsed:", activeItems.length);
        window.returnItems = activeItems;
        $('#ret_active_count').text(activeItems.length);
        
        // 4. Empty State
        if(activeItems.length === 0) {
            container.html(`
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fa fa-box-open fa-lg text-secondary opacity-50"></i>
                    </div>
                    <h6 class="text-muted font-xs">No items found.</h6>
                </div>
            `);
            resetReturnTotals();
            return;
        }

        // 5. Render List
        let html = '<div class="list-group list-group-flush">';
        activeItems.forEach((item, index) => {
            let imgHtml = item.image 
                ? `<img src="/${item.image}" class="rounded-lg shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">`
                : `<div class="bg-light rounded-lg d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;"><i class="fa fa-gem text-muted"></i></div>`;

            html += `
                <div class="list-group-item p-2 border-0 mb-1 shadow-sm rounded-lg return-item-row" id="ret_row_${item.id}" style="background:#fff; transition: all 0.2s;">
                    <div class="d-flex align-items-center">
                        <div class="mr-2">
                             <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input return-item-check" id="ret_check_${item.id}" value="${index}">
                                <label class="custom-control-label" for="ret_check_${item.id}"></label>
                            </div>
                        </div>
                        <div class="mr-3">${imgHtml}</div>
                        <div class="flex-grow-1">
                             <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 font-weight-bold text-dark font-sm text-truncate" style="max-width: 150px;">${item.detail}</h6>
                                <span class="badge badge-light border text-secondary font-xs py-0">#${item.receipt}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted font-xs">P: <b>${item.principal.toFixed(2)}</b></small>
                                <small class="text-danger font-xs">I: <b>${item.interest.toFixed(2)}</b></small>
                            </div>
                        </div>
                    </div>
                    <!-- Action Toggle -->
                    <div class="mt-2 action-toggle-group" id="action_toggle_${item.id}" style="display:none;">
                         <div class="btn-group btn-group-sm btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-outline-success active font-xs">
                                <input type="radio" name="action_${item.id}" value="release" checked onchange="updateReturnAction(${index}, 'release')"> Release
                            </label>
                            <label class="btn btn-outline-primary font-xs">
                                <input type="radio" name="action_${item.id}" value="interest" onchange="updateReturnAction(${index}, 'interest')"> Interest
                            </label>
                            <label class="btn btn-outline-info font-xs">
                                <input type="radio" name="action_${item.id}" value="part" onchange="updateReturnAction(${index}, 'part')"> Part Pay
                            </label>
                        </div>
                        <div class="mt-2" id="part_input_container_${item.id}" style="display:none;">
                            <input type="number" class="form-control form-control-sm font-xs" placeholder="Enter Amount" oninput="updatePartPayAmount(${index}, this.value)">
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.html(html);

        // 6. Bind Events
        $('.return-item-check').off('change').on('change', function() {
            let idx = $(this).val();
            let isChecked = $(this).prop('checked');
            
            window.returnItems[idx].selected = isChecked;
            
            if(isChecked) {
                $(`#ret_row_${window.returnItems[idx].id}`).find('.action-toggle-group').slideDown(200);
                 $(`#ret_row_${window.returnItems[idx].id}`).addClass('bg-light');
            } else {
                $(`#ret_row_${window.returnItems[idx].id}`).find('.action-toggle-group').slideUp(200);
                 $(`#ret_row_${window.returnItems[idx].id}`).removeClass('bg-light');
            }
            calculateReturnTotals();
        });
    }
    }

    window.updateReturnAction = function(index, action, el) {
        // Safe check
        if(!window.returnItems[index]) { console.error("Item not found:", index); return; }
        
        let id = window.returnItems[index].id;
        window.returnItems[index].action = action;

        // UI Updates
        if(el) {
            $(el).parent().parent().find('label').removeClass('active');
            $(el).parent().addClass('active');
        }

        let checkbox = $(`#ret_item_${id}`); // Note: ID in DOM might need verify, assumes ret_item_ID exists? 
        // Wait, checkboxes are ret_check_${id}. 
        // Let's use the row container or just direct ID access for breakdown divs.
        
        // UI Logic
        if(action === 'part') {
            $(`#part_input_container_${id}`).slideDown(200);
            
            // Clean slate
            let field = $(`#part_input_container_${id}`).find('input');
            field.val(window.returnItems[index].amount || ''); 
            field.attr('placeholder', 'Enter Amt');
            field.focus();
            
            window.updatePartPayAmount(index, field.val());
        } else {
            $(`#part_input_container_${id}`).slideUp(200);
            
            let item = window.returnItems[index];
            let p = parseFloat(item.principal);
            let i = parseFloat(item.interest);

            if(action === 'release') {
                $(`#ret_row_${id}`).find('.d-flex.justify-content-between small:first b').text(p.toFixed(2));
                $(`#ret_row_${id}`).find('.d-flex.justify-content-between small:last b').text(i.toFixed(2));
                $(`#ret_row_${id}`).find('.d-flex.justify-content-between small:last').removeClass('text-muted').addClass('text-danger');
            } else if(action === 'interest') {
                 // Interest Only: Show Interest Payable, maybe dim principal?
                 // For now just standard view rely on TOTAL calc
            }
            window.calcReturnTotals();
        }
    }

    window.updatePartPayAmount = function(index, val) {
        if(!window.returnItems[index]) return;
        
        let id = window.returnItems[index].id;
        let amount = parseFloat(val) || 0;
        window.returnItems[index].amount = amount;
        
        // No complex breakdown UI update needed unless specific request? 
        // Keeping it simple as per "optimize" request.
        
        window.calcReturnTotals();
    }

    window.calcReturnTotals = function() {
        let p_sum = 0, i_sum = 0;
        
        // Use Data State, not DOM state for calculation (More Robust)
        window.returnItems.forEach(item => {
            if(item.selected) {
                let p = parseFloat(item.principal);
                let i = parseFloat(item.interest);
                
                if(item.action === 'release') {
                    p_sum += p;
                    i_sum += i;
                } else if(item.action === 'interest') {
                    i_sum += i;
                    // Principal remains unpaid in Interest Only mode (for the calculation of what is being PAID now)
                } else if(item.action === 'part') {
                    let custom = parseFloat(item.amount) || 0;
                    
                    // Logic: First pay Interest, then Principal
                    let paidInterest = Math.min(custom, i);
                    let paidPrincipal = Math.max(0, custom - i);
                    
                    p_sum += paidPrincipal;
                    i_sum += paidInterest;
                }
            }
        });

        $('#ret_total_principal').text('₹ '+ p_sum.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#ret_total_interest').text('₹ '+ i_sum.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#ret_total_payable').text('₹ '+ (p_sum + i_sum).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        // UI Polish: Update borders based on state
        $('.return-item-row').removeClass('border-primary border-warning border-info bg-light');
        window.returnItems.forEach(item => {
            if(item.selected) {
                let row = $(`#ret_row_${item.id}`);
                row.addClass('bg-light');
                if(item.action === 'release') row.addClass('border-primary');
                else if(item.action === 'interest') row.addClass('border-warning');
                else if(item.action === 'part') row.addClass('border-info');
            }
        });
    }

    window.resetReturnTotals = function() {
            $('#ret_total_principal, #ret_total_interest, #ret_total_payable').text('₹ 0');
            $('#check_all_return_items').prop('checked', false);
    }

    window.setReturnPaymentMode = function(mode, el) {
        $('#ret_pay_medium').val(mode);
        $(el).siblings().removeClass('active');
        $(el).addClass('active');

        if(mode === 'mix') {
            $('#ret_split_payment_section').slideDown(200);
            // Parse currency string "₹ 1,500.00" -> 1500
            let totalStr = $('#ret_total_payable').text().replace(/[^\d.-]/g, '');
            let total = parseFloat(totalStr) || 0;
            
            $('#ret_split_cash').val(total);
            $('#ret_split_online').val(0);
        } else {
            $('#ret_split_payment_section').slideUp(200);
        }
    };

    window.calculateReturnSplit = function(source) {
        let totalStr = $('#ret_total_payable').text().replace(/[^\d.-]/g, '');
        let total = parseFloat(totalStr) || 0;
        
        let cash = parseFloat($('#ret_split_cash').val()) || 0;
        let online = parseFloat($('#ret_split_online').val()) || 0;

        if(source === 'cash') {
            let remain = total - cash;
            $('#ret_split_online').val(Math.max(0, remain)); 
        } else {
            let remain = total - online;
            $('#ret_split_cash').val(Math.max(0, remain));
        }
    }

    // --- EVENT LISTENERS ---
    $(document).ready(function() {
        
        // 1. Auto-Load if Data Exists (Fix for Tab Switching / Race Conditions)
        if(window.lastGirviData) {
             console.log("Return Tab: Auto-loading existing data");
             window.renderReturnItems(window.lastGirviData);
        }

        // 2. Real-time update
        $(document).on('customer_loaded', function(e, response) {
             // console.log("Return UI: Data Recieved", response);
             window.isReturnDemo = false;
             window.renderReturnItems(response);
        });

        $('#check_all_return_items').change(function() {
            let checked = $(this).prop('checked');
            $('.return-item-check').click(); // Trigger click to fire handlers
            // Alternatively manually loop update
            window.returnItems.forEach((item, index) => {
                 let cb = $(`#ret_check_${item.id}`);
                 if(cb.prop('checked') !== checked) {
                     cb.prop('checked', checked).trigger('change');
                 }
            });
        });

        $('#btn_process_return').click(function() {
            let selected = $('.return-check:checked');
            
            if(selected.length === 0) {
                if(typeof toastr !== 'undefined') toastr.warning('Please select items to return!');
                else alert('Please select items to return!');
                return;
            }

            // --- DEMO MODE CHECK ---
            if(window.isReturnDemo) {
                let btn = $(this);
                let originalText = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                btn.prop('disabled', true);

                setTimeout(function() {
                    if(typeof toastr !== 'undefined') toastr.success('Success! Items Returned Successfully (Demo).');
                    else alert('Success! Items Returned Successfully (Demo).');

                    selected.each(function() {
                        let id = $(this).val();
                        $('#card_' + id).slideUp(500, function() { 
                            $(this).remove(); 
                            if($('#return_items_container').children().length === 0) {
                                $('#return_items_container').html(`
                                    <div class="text-center py-5">
                                        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                                        <h6 class="text-success font-weight-bold">All items returned!</h6>
                                        <button class="btn btn-sm btn-link" onclick="loadReturnDemoData()">Reload Demo</button>
                                    </div>
                                `);
                            }
                        });
                    });

                    let currentCount = parseInt($('#ret_active_count').text());
                    let newCount = Math.max(0, currentCount - selected.length);
                    $('#ret_active_count').text(newCount);

                    btn.html(originalText);
                    btn.prop('disabled', false);
                    resetReturnTotals();

                }, 1500);
                return;
            }
            // --- END DEMO MODE ---

            // --- BACKEND SUBMISSION ---
            let selectedItems = window.returnItems.filter(i => i.selected);

            if(!confirm("Are you sure you want to process this transaction for " + selectedItems.length + " items?")) return;

            let btn = $(this);
            let originalText = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            btn.prop('disabled', true);

            let payloadItems = selectedItems.map(item => {
                return {
                    id: item.id,
                    action: item.action,
                    amount: item.amount || 0
                };
            });

            let payload = {
                _token: $('input[name="_token"]').val(),
                operation: 'return',
                custo: $('#custo').val(), // Hidden input from main form
                type: $('#type').val(),   // Hidden input from main form
                return_medium: $('#ret_pay_medium').val(),
                return_items: payloadItems
            };

            // Get URL from main form or fallback
            let url = $('#girvi_form').attr('action') || '/vendor/girvi/store';

            $.ajax({
                url: url,
                type: 'POST',
                data: payload,
                success: function(res) {
                    btn.html(originalText);
                    btn.prop('disabled', false);

                    if(res.success) {
                        if(typeof toastr !== 'undefined') toastr.success(res.success);
                        else alert(res.success);
                        
                        // Remove processed items from UI
                        selected.each(function() {
                             let id = $(this).val();
                             $('#card_' + id).slideUp(500, function() { $(this).remove(); });
                        });
                        
                        // Reset Count & Totals
                        let currentCount = parseInt($('#ret_active_count').text());
                        let newCount = Math.max(0, currentCount - selected.length);
                        $('#ret_active_count').text(newCount);
                        resetReturnTotals();

                        // Reload list if needed or empty
                        if(newCount === 0) {
                             $('#return_items_container').html(`
                                <div class="text-center py-5">
                                    <h6 class="text-success font-weight-bold">Transaction Complete!</h6>
                                </div>
                            `);
                        }

                    } else if(res.errors) {
                        let msg = Object.values(res.errors).join('\n');
                        if(typeof toastr !== 'undefined') toastr.error(msg);
                        else alert(msg);
                    } else if(res.error) {
                         if(typeof toastr !== 'undefined') toastr.error(res.error);
                         else alert(res.error);
                    }
                },
                error: function(err) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    if(typeof toastr !== 'undefined') toastr.error('Server Error!');
                    else alert('Server Error!');
                    console.error(err);
                }
            });
        });

    });
</script>