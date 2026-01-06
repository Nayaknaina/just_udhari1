<div class="table-responsive">
    <table class="table table_theme table-bordered">
        <thead>
            <tr>
                <th width="25%">
                    <select name="pay_medium" class="form-control text-center pay_table_input" id="pay_medium" >
                        <option value=""><b>Payment?</b></option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="net">Net Bank</option>
                        <option value="upi">UPI</option>
                    </select>
                </th>
                <th width="30%" >
                    <input type="text" name="pay_amnt" id="pay_amnt" class="form-control pay_table_input text-center" placeholder="Amount Rs.">
                </th>
                <th width="40%">
                    <input type="text" name="pay_rmrk" id="pay_rmrk" class="form-control pay_table_input" placeholder="Remark">
                </th>
                <th class="text-center" width="5%">
                    <button type="button" class="btn btn-sm btn-success  m-0  py-0 px-2" id="pay_ok">&check;</button>
                </th>
            </tr>
        </thead>
        <tbody class="billing" id="payment_data">
            
        </tbody>
        <tfoot class="bordered">
            <tr>
                <th class="text-center">TOTAL</th>
                <td><input type="text" id="paytotal" name="paytotal[]" class="form-control no-hover no-border text-center" value="" style="font-weight:bold;"></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $("#pay_ok").click(function(){
        const method = $("#pay_medium").val()??false;
        const method_label = $("#pay_medium option:selected").text();
        var amount = $("#pay_amnt").val()??false;
        var remark = $("#pay_rmrk").val()??"Bill Payment !";
        remark = (remark!="")?remark:"Bill Payment !";
        var metal_stream = wghtstream = false;
        var total = 0;
        ok = false;
        if(method){
            let cal_amnt = 0;
            if(method=='gold' || method=='silver'){
                var net = $("#net").val()??false;
                var fine = $("#fine").val()??false;
                var rate = $("#rate").val()??false;
                if(net && fine && rate){
                    if(+fine <= +net){
                        cal_amnt = rate * fine;
                        if(cal_amnt == amount){
                            metal_stream = `<ul class="m-0 p-0 text-center" style="list-style:none;font-size:70%;">
                                                <li><b>Net : </b>${net} Gm.</li>
                                                <li><b>Fine : </b>${fine} Gm.</li>
                                                <li><b>Rate : </b><i>${rate} Rs.</i></li>
                                            </ul>`;
                            wghtstream = JSON.stringify({'net':net,'fine':fine,'rate':rate});
                            ok = true;
                        }else{
                            toastr.error("Invalid Amount !")
                            $("#pay_amnt").addClass('is-invalid').focus();
                        }
                    }else{
                        toastr.error("Invalid Fine Weight !");
                        $("#fine").val($("#fine").val().slice(0,-1));
                        $("#fine").addClass('is-invalid').focus();
                    }
                }else{
                    toastr.error("Please Enter Net/Fine Weight & Rate at Payment!");
                }
            }else if(amount){
                ok = true;
            }else{
                toastr.error("Enter the Payment Amount !");
                $("#pay_amnt").addClass('is-invalid').focus();
            }
        }else{
            toastr.error("Select Payment Method !");
            $("#pay_medium").addClass('is-invalid').focus();
        }
        
        if(ok){
            var tr = `<tr>
                            <td class="text-center">
                                <input type="hidden" name="paymedium[]" value="${method}" class="form-control no-hover no-border" readonly>
                                ${method_label}
                            </td>
                            <td>`;
                            if(metal_stream){
                    tr+= `${metal_stream}<input type="hidden" name="payquant" value="${wghtstream}">`;            
                            }
                tr+=     `<input type="text" name="paymamnt[]" value="${amount}" class="form-control no-hover no-border"  style="font-weight:bold;" readonly>
                           </td>
                            <td>
                                <input type="text" name="payremark[]" value="${remark}" class="form-control no-hover no-border" readonly>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-danger remove_pay m-0 py-0 px-2" onclick="removepay($(this));">
                                    &times;
                                </button>
                            </td>
                        </tr>`;
            var total = 0;
            $("#payment_data").append(tr);
            totalpayments();
            $('.pay_table_input').val("");
            $("#pay_medium").val("").trigger('change');

        }
    });
    function removepay(input){
        input.closest('tr').remove();
        totalpayments();
    }

    function totalpayments(){
        var total = 0;
        $.each($(document).find('[name="paymamnt[]"]'),function(i,v){
            total+= +$(v).val();
        }); 
        $('#paytotal').val(total);
        var bill_total = $('#total').val()??0;
        var bill_balance = (+bill_total - +total).toFixed(2);
        $("#payment").val(Math.round(total));
        $("#balance").val(Math.abs(Math.round(bill_balance)));
        if(bill_balance < 0){
            $("#balance").removeClass('text-danger').addClass('text-success');
        }else{
            $("#balance").removeClass('text-success').addClass('text-danger');
        }
    }
</script>