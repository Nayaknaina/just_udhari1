<script>
    let itemRowCount = 0;
    let cachedCategoryOptions = ""; // Store fetched categories

    $(document).ready(function() {
        fetchCategories();
    });

    function fetchCategories() {
        $.get('{{ route("girvi.cats") }}', 'cats=true', function(response) {
            if (response.cats && response.cats.length > 0) {
                cachedCategoryOptions = "";
                $.each(response.cats, function(i, v) {
                    // Avoid duplicating Gold/Silver if they come from DB, or handle duplicates
                    if(v.name !== 'Gold' && v.name !== 'Silver') {
                         cachedCategoryOptions += `<option value="${v.name}">${v.name}</option>`;
                    }
                });
                
                // Populate existing dropdowns (e.g. the first one)
                $('select[name^="category"]').each(function() {
                    $(this).append(cachedCategoryOptions);
                });
            }
        });
    }

    // --- Customer Load Listener (Summary) ---
    $(document).on('customer_loaded', function(event, response) {
        let summaryCard = $('#cust_summary_block');
        let nameField = $('#cust_summary_name');
        let countField = $('#cust_summary_count');
        let dueField = $('#cust_summary_due');

        if(response) {
            let name = $('.fetch_custo_name').text() || "Customer Selected";
            nameField.text(name);

            setTimeout(function(){
                 let totalDue = (typeof old_girvi_sum !== 'undefined') ? old_girvi_sum : 0;
                 let count = 0;
                 if(response.old && response.old.length > 0) {
                     response.old.forEach(function(rec){
                         count += rec.items ? rec.items.length : 0;
                     });
                 }
                 
                 countField.text(count);
                 dueField.text('₹ ' + totalDue.toFixed(0));
                 summaryCard.fadeIn();
            }, 100);
            
        } else {
             summaryCard.hide();
        }
    });

    // --- Row Management ---
    $('#btn_add_more_item').click(function() {
        itemRowCount++;
        let template = $('#item_row_template_js').html();
        
        // Use placeholder for now, re-index immediately after
        let newRowHtml = template
            .replace(/INDEX_PLACEHOLDER/g, itemRowCount)
            .replace(/ITEM_NUMBER_PLACEHOLDER/g, '#') 
            .replace(/item_row_0/g, 'item_row_' + itemRowCount);
            
        let $newRow = $(newRowHtml);
        
        // Append cached categories to the new row's select
        $newRow.find('select[name^="category"]').append(cachedCategoryOptions);

        $('#items_container').append($newRow);
        
        reindexItems();
    });

    function removeItemRow(index) {
        $('#item_row_' + index).remove();
        calculateTotals();
        reindexItems();
    }
    
    // Core function to keep numbering sequential (Girvi #1, Girvi #2...)
    function reindexItems() {
        $('.girvi-number-badge').each(function(index) {
            $(this).text('GIRVI #' + (index + 1));
        });
        
        // Optional: Re-index ID attributes if strictly needed, but might break event listeners if not careful.
        // For visual numbering, the above is enough.
    }

    // --- Image Preview ---
    function previewImage(input, index) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $(`#camera_icon_${index}`).hide();
                $(`#preview_img_${index}`).attr('src', e.target.result).show();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- Dynamic Visibility ---
    function toggleWeights(index) {
        let category = $(`select[name="category[${index}]"]`).val();
        let section = $(`#weights_section_${index}`);
        
        if(category === 'Gold' || category === 'Silver') {
            section.slideDown();
        } else {
            section.slideUp();
        }
    }

    // --- Calculations ---
    function calculateItemRow(index) {
        let category = $(`select[name="category[${index}]"]`).val();
        let rate = parseFloat($(`#rate_${index}`).val()) || 0;
        let valueField = $(`#value_${index}`);

        if(category === 'Gold' || category === 'Silver') {
            let net = parseFloat($(`#net_${index}`).val()) || 0;
            let pure = parseFloat($(`#pure_${index}`).val()) || 0;
            
            let fine = (net * pure) / 100;
            $(`#fine_${index}`).val(fine.toFixed(2));
            
            let value = fine * rate;
            valueField.val(value.toFixed(0));
            // Value is calculated, so maybe read-only? 
            // The old code didn't strictly force it, but good practice.
            // valueField.prop('readonly', true); 
        } else {
            // valueField.prop('readonly', false);
        }

        calculateTotals();
    }

    function calculateTotals() {
        let totalValuation = 0;
        $('input[name^="value"]').each(function() {
            let val = parseFloat($(this).val()) || 0;
            totalValuation += val;
        });

        $('#total_valuation').val(totalValuation.toFixed(0));
        $('#total_valuation_display').text('₹ ' + totalValuation.toFixed(0));
        
        calculateLoanDetails();
    }

    // Loan Listeners
    $('#grant_amount, #interest_rate, #tenure_months, #issue_date, #interest_type').on('input change', function() {
        calculateLoanDetails();
    });

    function calculateLoanDetails() {
        let grant = parseFloat($('#grant_amount').val()) || 0;
        let rate = parseFloat($('#interest_rate').val()) || 0;
        let tenure = parseFloat($('#tenure_months').val()) || 0;
        let type = $('#interest_type').val();
         let issueDateStr = $('#issue_date').val();

        if (issueDateStr && tenure) {
            let issueDate = new Date(issueDateStr);
            issueDate.setMonth(issueDate.getMonth() + parseInt(tenure));
            let returnDateStr = issueDate.toISOString().split('T')[0];
            $('#return_date').val(returnDateStr);
        }

        let interestVal = 0;
        if(grant > 0 && rate > 0 && tenure > 0) {
            if (type === 'si') {
                interestVal = (grant * rate * tenure) / 100;
            } else {
                 interestVal = grant * (Math.pow((1 + rate / 100), tenure)) - grant;
            }
        }

        let principalVal = grant;
        let totalPayable = principalVal + interestVal;

        $('#interest_payable_disp').text('₹ '+ interestVal.toFixed(0));
        $('#interest_val').val(interestVal.toFixed(0));

        $('#total_payable_disp').text('₹ ' + totalPayable.toFixed(0));
        $('#payable_val').val(totalPayable.toFixed(0));
        
        $('#principal_val').val(principalVal);
    }
    
    function toggleLoanDetails() {
         $('#loan_details_section').collapse('toggle');
         $('#loan_toggle_container').toggle();
    }
    
    function setPaymentMode(mode, elem) {
        $('#pay_medium').val(mode);
        $('.payment-option').removeClass('active');
        $(elem).addClass('active');
    }

    // --- Category Creation Listener ---
    $(document).on('categoryformsubmit', function(e) {
        let data = e.detail;
        if(data.errors) {
             $.each(data.errors, function(i, v){
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
            let newOption = `<option value="${data.item.name}">${data.item.name}</option>`;
            $('select[name^="category"]').append(newOption);
            
            // Update cache so new rows get it too
            cachedCategoryOptions += newOption;
        }
    });

    // --- Form Submission ---
    function submitGirviForm(action) {
        let grant = $('#grant_amount').val();
         if(!grant || grant <= 0) {
            if(!$('#loan_details_section').hasClass('show')){
                 toggleLoanDetails();
                 alert('Please enter Loan Amount.');
                 return;
            }
             alert('Please enter Loan Amount.');
             return;
         }
        
        let formData = new FormData($('#girvi_form')[0]);
        formData.append('do', action);

        $.ajax({
            url: "{{ route('girvi.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    alert(response.success);
                    location.reload();
                } else if(response.errors) {
                     let errorMsg = "";
                     $.each(response.errors, function(key, value) {
                         errorMsg += value + "\n";
                     });
                     alert(errorMsg);
                } else {
                    alert('Something went wrong!');
                }
            },
            error: function(err) {
                 console.error(err);
                 alert('Server Error. Please try again.');
            }
        });
    }
</script>
