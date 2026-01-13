<div class="row m-0 p-1">

    <!-- Customer Summary (Same as Receive UI) -->
    <div class="col-12 p-0 mb-2">
        <div class="customer-summary-card" id="ret_cust_summary_block" style="display: block;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                     <span class="d-block font-xs text-primary font-weight-bold">CUSTOMER DETAILS</span>
                     <h6 class="m-0 text-dark font-weight-bold fetch_custo_name">Select Customer</h6>
                     <small class="text-muted fetch_custo_girvi_num">GRV-ID</small>
                </div>
                <div class="text-right">
                     <span class="badge badge-pill badge-light text-primary border border-primary px-3 py-1" style="font-size: 0.8rem;">
                        Active Items: <b id="ret_active_count">0</b>
                     </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Items List -->
    <div class="col-12 p-0 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2" style="background: #fff; padding: 10px 15px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
            <div class="d-flex align-items-center">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                    <i class="fa fa-hand-holding-usd text-danger"></i>
                </div>
                <h6 class="m-0 font-weight-bold text-dark section-title">Items to Return</h6>
            </div>
            
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="check_all_return_items">
                <label class="custom-control-label font-weight-bold text-muted font-xs pt-1" for="check_all_return_items">SELECT ALL</label>
            </div>
        </div>

        <!-- Scrollable Item List -->
        <div class="girvi_item_scroll" id="return_items_container" style="background: #f8f9fa; border-radius: 10px; min-height: 200px;">
            <!-- Placeholder with Demo Button -->
            <div class="text-center py-5" id="return_placeholder">
                <i class="fa fa-search fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-muted mb-3">Search for a customer to view active items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary btn-roundhalf" onclick="loadReturnDemoData()">
                    <i class="fa fa-flask"></i> Load Demo Data
                </button>
            </div>
        </div>
    </div>

    <!-- Totals & Actions -->
    <div class="col-12 p-0">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
            <div class="card-body p-3">
                
                <!-- Calculation Row -->
                <div class="row text-center mb-3">
                    <div class="col-4 border-right">
                        <small class="text-muted d-block font-xs font-weight-bold">PRINCIPAL</small>
                        <h6 class="mb-0 font-weight-bold text-dark" id="ret_total_principal">₹ 0</h6>
                    </div>
                    <div class="col-4 border-right">
                        <small class="text-muted d-block font-xs font-weight-bold">INTEREST</small>
                        <h6 class="mb-0 font-weight-bold text-danger" id="ret_total_interest">₹ 0</h6>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block font-xs font-weight-bold">PAYABLE</small>
                        <h5 class="mb-0 font-weight-bold text-success" id="ret_total_payable">₹ 0</h5>
                    </div>
                </div>

                <hr class="my-2 opacity-50">

                <!-- Payment & Action Row (Detailed) -->
                <div class="row align-items-center">
                    <div class="col-12 mb-2">
                       <label class="font-xs text-muted mb-2 ml-1 font-weight-bold d-block">PAY MODE</label>
                       
                       <!-- Custom Payment Toggle -->
                       <style>
                            .payment-toggle-container {
                                display: flex;
                                background: #f1f3f5;
                                padding: 4px;
                                border-radius: 50px;
                                position: relative;
                            }
                            .payment-option {
                                flex: 1;
                                text-align: center;
                                padding: 8px 5px;
                                font-size: 0.85rem;
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
                       
                       <div class="payment-toggle-container mb-3">
                            <input type="hidden" name="return_medium" id="ret_pay_medium" value="cash">
                            <div class="payment-option active" onclick="setReturnPaymentMode('cash', this)">
                                <i class="fa fa-money-bill-wave"></i> Cash
                            </div>
                            <div class="payment-option" onclick="setReturnPaymentMode('on', this)">
                                <i class="fa fa-university"></i> Online
                            </div>
                            <div class="payment-option" onclick="setReturnPaymentMode('mix', this)">
                                <i class="fa fa-exchange-alt"></i> Mix
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
                                            <span class="input-group-text bg-light border-0 text-success"><i class="fa fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark" id="ret_split_cash" placeholder="0" oninput="calculateReturnSplit('cash')" style="height: 35px;">
                                    </div>
                                </div>
                                <div class="col-6 pl-2">
                                    <label class="font-xs text-muted font-weight-bold mb-1">ONLINE</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0 text-info"><i class="fa fa-university"></i></span>
                                        </div>
                                        <input type="number" class="form-control btn-roundhalf bg-light border-0 font-weight-bold text-dark" id="ret_split_online" placeholder="0" oninput="calculateReturnSplit('online')" style="height: 35px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-gradient-success btn-block btn-roundhalf font-weight-bold py-3 shadow-sm" id="btn_process_return" style="font-size: 1rem;">
                            <i class="fa fa-check mr-1"></i> Confirm & Release
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Scoped Script for Return Functionality
    
    window.isReturnDemo = false;

    // --- GLOBAL HELPER FUNCTIONS ---
    
    window.loadReturnDemoData = function() {
        console.log("Loading Demo Data...");
        window.isReturnDemo = true;
        
        let dummyResponse = {
            girvi: { custo_name: 'Rahul Demo Customer', girvi_id: 'DEMO-001' },
            new: [{
                items: [
                    {
                        id: 'd1', detail: 'Gold Ring 22k', category: 'Gold', 
                        principal: 15000, interest: 450, interest_rate: 1.5,
                        status: '1', image: 'main/assets/img/product/girvi/ring.png', receipt: '101'
                    },
                    {
                        id: 'd2', detail: 'Silver Anklet', category: 'Silver', 
                        principal: 5000, interest: 150, interest_rate: 1.5,
                        status: '1', image: '', receipt: '102'
                    }
                ]
            }],
            old: [{
                items: [
                    {
                        id: 'd3', detail: 'Diamond Pendant', category: 'Diamond', 
                        principal: 50000, interest: 1200, interest_rate: 2.0,
                        status: '1', image: '', receipt: '99'
                    }
                ]
            }],
            girvy_return_date: '2026-02-01'
        };

        $('.fetch_custo_name').text(dummyResponse.girvi.custo_name);
        $('.fetch_custo_girvi_num').text(dummyResponse.girvi.girvi_id);
        
        // Ensure render function exists
        if(typeof window.renderReturnItems === 'function') {
            window.renderReturnItems(dummyResponse);
        } else {
            console.error("renderReturnItems function not found!");
            alert("Error: Script not loaded correctly. Please refresh.");
        }
    }

    window.renderReturnItems = function(data) {
        let container = $('#return_items_container');
        container.empty();
        
        let allItems = [];
        if(data.new && Array.isArray(data.new)) data.new.forEach(batch => batch.items && batch.items.forEach(item => allItems.push(item)));
        if(data.old && Array.isArray(data.old)) data.old.forEach(batch => batch.items && batch.items.forEach(item => allItems.push(item)));

        let activeItems = allItems.filter(item => item.status == '1');
        $('#ret_active_count').text(activeItems.length);

        if(activeItems.length === 0) {
            container.html(`
                <div class="text-center py-5">
                    <i class="fa fa-box-open fa-2x text-muted opacity-50 mb-2"></i>
                    <h6 class="text-muted">No active items found</h6>
                    <button class="btn btn-sm btn-link" onclick="loadReturnDemoData()">Try Demo Data</button>
                </div>
            `);
            resetReturnTotals();
            return;
        }

        activeItems.forEach(item => {
            let p = parseFloat(item.principal || 0);
            let i = parseFloat(item.interest || 0);
            let total = p + i;
            let imgHtml = (item.image && item.image !== '') 
                ? `<img src="/${item.image}" style="width: 100%; height: 100%; object-fit: cover;">`
                : `<div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-secondary"><i class="fa fa-gem"></i></div>`;
            
            let html = `
                <div class="card mb-2 shadow-sm border-0 return-item-card" id="card_${item.id}" style="border-radius: 10px; overflow: hidden; transition: all 0.2s;">
                    <div class="d-flex align-items-center p-2 m-0 w-100">
                        
                        <div class="pr-3 pl-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input return-check" id="ret_item_${item.id}" value="${item.id}" data-p="${p}" data-i="${i}" data-action="release">
                                <label class="custom-control-label" for="ret_item_${item.id}"></label>
                            </div>
                        </div>
                        
                        <div class="mr-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: #f8f9fa;">
                            ${imgHtml}
                        </div>
                        
                        <div class="flex-grow-1" style="min-width: 0;">
                            <h6 class="mb-0 text-dark font-weight-bold text-truncate" style="font-size: 0.9rem;">${item.detail || 'Item'}</h6>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">${item.category} • GRV-${item.receipt}</small>
                            
                            <div class="btn-group btn-group-toggle mt-1 action-toggle-group" data-id="${item.id}" style="display:none;">
                                <label class="btn btn-xs btn-outline-primary active py-0 px-2" onclick="setReturnAction('${item.id}', 'release', this)">
                                    <input type="radio" name="action_${item.id}" checked> Release
                                </label>
                                <label class="btn btn-xs btn-outline-info py-0 px-2" onclick="setReturnAction('${item.id}', 'part', this)">
                                    <input type="radio" name="action_${item.id}"> Part Pay
                                </label>
                                <label class="btn btn-xs btn-outline-warning py-0 px-2" onclick="setReturnAction('${item.id}', 'interest', this)">
                                    <input type="radio" name="action_${item.id}"> Interest
                                </label>
                            </div>

                            <!-- Part Payment Input (Hidden Default) -->
                            <div class="mt-2 part-pay-container" id="part_input_container_${item.id}" style="display:none; max-width: 120px;">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0 bg-light text-muted pl-1 pr-1">₹</span>
                                    </div>
                                    <input type="number" class="form-control border-0 bg-light font-weight-bold p-1 part-pay-field" 
                                        id="part_val_${item.id}" data-id="${item.id}" value="" 
                                        placeholder="Total Amt" onkeyup="updatePartPay('${item.id}', this.value)" onchange="updatePartPay('${item.id}', this.value)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right pl-2">
                            <h6 class="mb-0 font-weight-bold text-success item-total-display" id="display_total_${item.id}">₹ ${total}</h6>
                            <small class="text-danger font-weight-bold" style="font-size: 0.65rem;">
                                <span id="display_breakup_${item.id}">P: ${p} + I: ${i}</span>
                            </small>
                        </div>

                    </div>
                </div>
            `;
            container.append(html);
        });
        
        $('.return-check').off('change').on('change', function() {
            let id = $(this).val();
            let checked = $(this).prop('checked');
            if(checked) $(`#card_${id} .action-toggle-group`).fadeIn(200);
            else {
                $(`#card_${id} .action-toggle-group`).hide();
                $(`#part_input_container_${id}`).hide();
            }
            window.calcReturnTotals();
        });
    }

    window.setReturnAction = function(id, action, el) {
        event.preventDefault(); 
        event.stopPropagation();
        
        $(el).parent().find('label').removeClass('active');
        $(el).addClass('active');

        let checkbox = $(`#ret_item_${id}`);
        checkbox.attr('data-action', action);

        // UI Logic
        if(action === 'part') {
            $(`#part_input_container_${id}`).slideDown(200);
            
            // Clean slate: Empty input, placeholder instructions
            let field = $(`#part_val_${id}`);
            field.val(''); 
            field.attr('placeholder', 'Enter Amt');
            field.focus();
            
            // Update immediately with 0/empty to reset view to "Total"
            window.updatePartPay(id, '');
        } else {
            $(`#part_input_container_${id}`).slideUp(200);
            
            let p = parseFloat(checkbox.data('p'));
            let i = parseFloat(checkbox.data('i'));

            if(action === 'release') {
                $(`#display_total_${id}`).text('₹ ' + (p + i));
                $(`#display_breakup_${id}`).html(`P: ${p} + I: ${i}`);
                $(`#display_total_${id}`).removeClass('text-warning text-info text-danger').addClass('text-success');
                $(`#display_breakup_${id}`).removeClass('text-dark text-muted');
            } else if(action === 'interest') {
                $(`#display_total_${id}`).text('₹ ' + i);
                $(`#display_breakup_${id}`).text(`Interest Only`);
                $(`#display_total_${id}`).removeClass('text-success text-info text-danger').addClass('text-warning');
                $(`#display_breakup_${id}`).removeClass('text-dark text-muted');
            }
            window.calcReturnTotals();
        }
    }

    window.updatePartPay = function(id, val) {
        let amount = parseFloat(val) || 0;
        let checkbox = $(`#ret_item_${id}`);
        let p = parseFloat(checkbox.data('p'));
        let i = parseFloat(checkbox.data('i'));
        let totalDue = p + i;
        
        // Logic: 
        // If Amount > 0: Show Breakdown (Paid + Balance).
        // If Amount == 0: Show Standard Total (P + I).
        
        if(amount <= 0) {
            // Restore "Release" style view
            $(`#display_total_${id}`).text('₹ ' + totalDue);
            $(`#display_breakup_${id}`).html(`P: ${p} + I: ${i}`);
            $(`#display_total_${id}`).removeClass('text-warning text-info text-danger').addClass('text-success');
            // Store 0 custom
            checkbox.attr('data-custom', 0);
        } else {
            // Updated Part Pay View
            let paidInterest = Math.min(amount, i);
            let paidPrincipal = Math.max(0, amount - i); 
            let remainingPrincipal = p - paidPrincipal;
            
            $(`#display_total_${id}`).text('₹ ' + amount);
            
            let breakdownHtml = `
                <div style="font-size: 0.7rem; line-height: 1.2;" class="text-right">
                    <span class="text-success font-weight-bold">Paid: ${amount}</span> 
                    <span class="text-dark">(I:${paidInterest} + P:${paidPrincipal})</span><br>
                    <span class="text-danger font-weight-bold" style="font-size: 0.75rem;">Bal: ${remainingPrincipal}</span>
                </div>
            `;
            
            if(amount < i) {
                 breakdownHtml += `<div class="text-danger small font-weight-bold mt-1">Interest (${i}) Pending!</div>`;
            }

            $(`#display_breakup_${id}`).html(breakdownHtml);
            $(`#display_total_${id}`).removeClass('text-success text-warning').addClass('text-info');
            
            checkbox.attr('data-custom', amount);
        }

        window.calcReturnTotals();
    }

    window.calcReturnTotals = function() {
        let p_sum = 0, i_sum = 0;
        
        $('.return-check:checked').each(function() {
            let action = $(this).attr('data-action');
            let p = parseFloat($(this).data('p'));
            let i = parseFloat($(this).data('i'));

            if(action === 'release') {
                p_sum += p;
                i_sum += i;
            } else if (action === 'interest') {
                i_sum += i; 
            } else if (action === 'part') {
                let custom = parseFloat($(this).attr('data-custom')) || 0;
                
                // If custom is 0, technically nothing is paid in part pay mode.
                // Or should we assume they MUST pay something? 
                // Currently calc treats 0 as 0 paid.
                
                let paidInterest = Math.min(custom, i);
                let paidPrincipal = Math.max(0, custom - i);
                
                p_sum += paidPrincipal;
                i_sum += paidInterest;
            }
        });
        
        $('#ret_total_principal').text('₹ '+ p_sum.toLocaleString());
        $('#ret_total_interest').text('₹ '+ i_sum.toLocaleString());
        $('#ret_total_payable').text('₹ '+ (p_sum + i_sum).toLocaleString());
        
        $('.return-item-card').removeClass('border-primary bg-light border-warning border-info');
        $('.return-check:checked').each(function() {

            let card = $(this).closest('.return-item-card');
            let action = $(this).attr('data-action');
            card.addClass('bg-light');
            
            if(action === 'release') card.addClass('border-primary');
            else if(action === 'interest') card.addClass('border-warning');
            else if(action === 'part') card.addClass('border-info');
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
        
        $(document).on('customer_loaded', function(e, response) {
            console.log("Return UI: Data Recieved", response);
            window.isReturnDemo = false;
            window.renderReturnItems(response);
        });

        $('#check_all_return_items').change(function() {
            let checked = $(this).prop('checked');
            $('.return-check').prop('checked', checked).trigger('change');
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
            if(!confirm("Are you sure you want to process this transaction?")) return;

            let btn = $(this);
            let originalText = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            btn.prop('disabled', true);

            let returnItems = [];
            selected.each(function() {
                let id = $(this).val();
                let action = $(this).attr('data-action');
                let custom = $(this).attr('data-custom') || 0;
                
                returnItems.push({
                    id: id,
                    action: action,
                    amount: custom // Only relevant for part pay
                });
            });

            let payload = {
                _token: $('input[name="_token"]').val(),
                operation: 'return',
                custo: $('#custo').val(), // Hidden input from main form
                type: $('#type').val(),   // Hidden input from main form
                return_medium: $('#ret_pay_medium').val(),
                return_items: returnItems
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