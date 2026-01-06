<script>
    $(document).on('click','#pay_ok',function(e){
        var source = $(this).data('source');
        let funName = `${source}validation`;
		const edit = $(this).closest("#option_pages").hasClass('edit');
        if (typeof window[funName] === "function") {
            window[funName](edit); // call function dynamically
        } else {
            toastr.error(`Unable to Process ${source} Payment !`);
        }
    });

    function amountvalidation(edit=false){
        const to = $("#deposite_to").val()??false;
        if(!to){
            toastr.error("Select the '<b>Deposite To</b>'");
            $("#deposite_to").addClass('is-invalid');
            return false;
        }
        const medium = $("#medium").val()??false;
        if(!medium){
            toastr.error("Select the '<b>Medium of Payment !</b>'");
            $("#medium").addClass('is-invalid');
            return false;
        }
        const amount = $("#amount").val()??false;
        if(!amount){
            toastr.error("Enter the '<b>Amount !</b>'");
            $("#amount").addClass('is-invalid');
            return false;
        }
        const mode = (to!=0)?'bank':'shop'
        const mode_title = $("#deposite_to").find('option:selected').data('to');
        addpayment(edit,amount,medium,1,to,mode,mode_title,medium);
    }

    function schemevalidation(edit=false){
        const scheme = $("#scheme").val()??false;
        if(!scheme){
            toastr.error("Select the '<b>Scheme !</b>'");
            $("#scheme").addClass('is-invalid');
            return false;
        }
        const amount = $("#amount").val()??false;
        const avail_amount = $("#scheme_balance").val();

        if(!amount || (+amount >  +avail_amount)){
            toastr.error("Enter the valid '<b>Amount !</b>'");
            $("#amount").addClass('is-invalid');
            return false;
        }
        const mode_id = scheme;
        const mode = "scheme";
        const mode_label = $("#scheme").find('option:selected').data('label')
        const medium = 'vendor';
        addpayment(edit,amount,medium,1,mode_id,mode,mode_label,medium);
    }

    function metalvalidation(edit=false){
        const metal = $("#metal").val()??false;
        if(!metal){
            toastr.error("Select the '<b>Metal !</b>'");
            $("#metal").addClass('is-invalid');
            return false;
        }
        const gross = $("#gross").val()??false;
        if(!gross){
            toastr.error("Missing '<b>Gross !</b>'");
            $("#gross").addClass('is-invalid');
            return false;
        }
        const net = $("#net").val()??false;
        if(!net){
            toastr.error("Missing '<b>Net !</b>'");
            $("#net").addClass('is-invalid');
            return false;
        }
        const fine = $("#fine").val()??false;
        if(!net){
            toastr.error("Missing '<b>Fine !</b>'");
            $("#fine").addClass('is-invalid');
            return false;
        }
        const rate = $("#rate").val()??false;
        if(!rate){
            toastr.error("Missing '<b>Rate !</b>'");
            $("#rate").addClass('is-invalid');
            return false;
        }
        const amount = $("#amount").val()??false;
        if(!amount){
            toastr.error("Missing '<b>Amount !</b>'");
            $("#amount").addClass('is-invalid');
            return false;
        }
        const tunch = $("#tunch").val()??'NA';
        const prop_json = {'tunch':tunch,'gross':gross,"net":net,"fine":fine};
        addpayment(edit,amount,metal,rate,0,'metal','metal',metal,JSON.stringify(prop_json));
    }

    function addpayment(edit,amount,medium,rate,id=0,mode,mode_title="",medium_title="",prop_json=false){
        
        const option = $("#option").val();
        const remark = $("#remark").val()||"Sale Bill Payment";
        let pay_tr = `<tr class="source_${option}">
                        <td>
                        <input type="hidden" name="pay[option][]" value="${option}">
                        <input type="hidden" name="pay[mode][]" value="${mode}">
                        <input type="hidden" name="pay[reference][]" value="${id}">
                        <input type="hidden" name="pay[title][]" value="${mode_title}">
                        <input type="hidden" name="pay[medium][]" value="${medium}">
                        <input type="hidden" name="pay[rate][]" value="${rate}">
                        <input type="hidden" name="pay[amount][]" value="${amount}">
                        <input type="hidden" name="pay[remark][]" value="${remark}">`;
                    if(option=='metal'){
             pay_tr+=`<input type="hidden" name="payprop" value='${prop_json}'>` ;        
                    }
            pay_tr+=`1
                        </td>
                        <td style="text-transform: capitalize;">
                            ${mode_title}
                        </td>
                        <td style="text-transform: capitalize;">
                            ${medium_title}
                        </td>
                        <td style="text-transform: capitalize;">
                            ${amount}
                        </td>
                        <td style="text-transform: capitalize;">
                            ${remark}
                        </td>
                        <td class="text-center">
                            <button type="button"  class="btn btn-outline-danger btn-sm px-1 py-0 remove_payment" data-minus="${amount}">&cross;</button>
                        </td>
                        </tr>`;
        $("#no_payment").remove();
        $("#all_pays").append(pay_tr);
        $('.pay_input_field').val("");
        $('.pay_input_field_amnt').text("");
        reduceremains(edit);
    }

    function reduceremains(edit=false){
        const bill_total = $("#final").val()??false;
        if(bill_total){
            var total_paid = 0;
            $("input[type='hidden'][name='pay[amount][]']").each(function(i,v){
                total_paid+= +$(v).val()??0;
            });
			if(edit){
                total_paid+= +($("#payment").val()??0);
            }
            $("#payment").val(total_paid);
            const remains = +bill_total - +total_paid;
            let blnc_fld = $("#balance");
            const blnc_class = (remains < 0 )?'text-success':'text-danger';
            blnc_fld.removeClass('text-success text-danger').addClass(`${blnc_class}`);
            blnc_fld.val(Math.abs(remains));
        }
    }

    $(document).on('click','.remove_payment',function(e){
        const del_pay = $(this).data('minus')??0;
        const bill_total = $("#final").val()??false;
        const ttl_pay = $("#payment").val()||false;
        if(bill_total && ttl_pay){
            const balance = $("#balance").val()||0;
            let update_pay =  +ttl_pay - +del_pay;
            let update_balance =  +bill_total - +update_pay;
            if(+update_balance == +bill_total){
                const dflt_tr = `<tr id="no_payment">
                <td colspan="6" class="text-center text-danger">No Payment Yet !</td>
                </tr>`;
                $("#all_pays").empty().append(dflt_tr);
                $("#balance").removeClass('text-success text-danger').addClass('text-danger');
            }
            $("#payment").val(update_pay);
            $("#balance").val(update_balance);
            $(this).closest('tr').remove();
        }else{
            toastr.error("Invalid Operation Performed !");
        }
    });
</script>