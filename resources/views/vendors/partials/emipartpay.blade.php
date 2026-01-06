<h5 class="modal-title">EMI Pay In Parts
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true" class="text-danger">&times;</span>
    </button>
</h5>
<hr class="m-2 p-0">

<form action="{{ url("vendors/shopschemes/emipart/{$enrollcusto->id}") }}" method="post" id="part_pay_form">
    @csrf
    <input type="hidden" name="emi_num" value="{{ $curr_emi }}">
    <ul class="row emi_info_ul">
        <li class="col-6"><label for="pay_date">PAYMENT DATE</label><input type="date" name="date" class="form-control  w-auto" value="{{ date("Y-m-d",strtotime('now') )}}" ></li>
        <li class="col-6"><label>CHOOSED EMI</label><label class="form-control text-info bg-white" style="width:fit-content;">{{ $enrollcusto->emi_amnt }} Rs.</label></li>
    </ul>
    <div class="table-responsive">
        <table class="table tabel-bordered emi-pay-table">
            <thead class="sub_pay_form_field">
                <tr>
                    <th>
                        <a href="#sub_pay_tr" class="btn btn-xs btn-primary px-2 py-0" id="add_more_pay_part_tr" style="font-size:20px;"><span>&#10012;</span></a>
                    </th>
                    <th>SUB AMOUNT</th>
                    <th>PAY MODE</th>
                    <th>PAY MEDIUM</th>
                    <th>REMARK</th>
                </tr>
            </thead>
            <tbody class="sub_pay_form_field">
                <tr id="sub_pay_tr">
                    <td class="text-center">
                        <div class="col-12 p-0">
                            <label class="form-control bg-white m-0 emi_num">
                                <input type="hidden" name="emi[]" value="{{ $curr_emi }}">
                                <input type="hidden" name="bonus[]" value="0">
                                <input type="hidden" name="type[]" value="B">
                                {{ $curr_emi }}
                            </label>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control amnt" id="amnt" name="amnt[]" value="" placeholder="Part Amount" required>
                    </td>
                    <td>
                        <select class="form-control mode" id="mode" name="mode[]" required>
                            <option value="">Select</option>
                            <option value="SYS" selected>System</option>
                            <option value="ECOMM">E-Commerce</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control medium" id="medium" name="medium[]" required>
                            <option value="">Select</option>
                            @php
                            $medium_arr = ['Cash','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Draw','Vendor'];

                            @endphp
                            @foreach($medium_arr as $mk=>$med)
                            <option value="{{ $med }}">{{ $med }}</option>
                            @endforeach
              
                        </select>
                    </td>
                    <td>
                        <textarea name="remark[]" id="remark" class="form-control remark" placeholder="Remark About Payment" >EMI Paid</textarea>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-center">
                        <button type="submit" name="pay" value="part" class="btn btn-md btn-success" id="part_pay_button" data-target="#part_pay_form" data-action="submit">Pay ?</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<style>
    .sub_pay_form_field > tr > td {
        padding:0px;
    }
    ..sub_pay_form_field > tr > th  {
        padding:2px!important;
        vertical-align: baseline!important;
    }
    .sub_pay_form_field>tr{
        background:lightgray;
    }
    .pay_part_addon_fields{
        position:absolute;
        top:-5px;
        left:0;
        /* border:1px solid;
        border-radius:10px; */
    }
    .emi_info_ul {
        list-style:none;
    }
</style>
<script>
    var field = 1;
    $('#add_more_pay_part_tr').click(function(e){
        e.preventDefault();
        if(field<=2){
            var org_tr = $(this).attr("href");
            var tr = $(org_tr).clone();
            tr.attr('id',"");
            var anchor = '<a href="#" onclick="event.preventDefault();removeaddonpayfield($(this));" class="text-danger px-1  pay_part_addon_fields">&cross;</a>';
            $(tr.find('.emi_num')).append(anchor);
            tr.find('.amnt,.mode,.medium').val("");
            $(tr).insertAfter(org_tr);
            field++;
        }else{
            toastr.error("Can't Add More !");
        }
    });
    
    function removeaddonpayfield(element){
        $(element.parent('label').parent('div').parent('td').parent('tr')).remove();
        field--;
    }

    $("#part_pay_button").click(function(e){
        e.preventDefault();
        data_element = $(this).data('target');
        data_action = $(this).data('action');
        launchmpinmodal();
        //return false;
    })
    $('#part_pay_form').submit(function(e){
        e.preventDefault();
        var formdata = $(this).serialize();
        var action = $(this).attr('action');
        $.post(action,formdata,function(response){
            //var res = JSON.parse(response);
            if(response.errors){
               // var errors = response.responseJSON.errors;
                $.each(response.errors, function(field, messages) {
                var $field = $('[name="' + field + '"]');
                toastr.error(messages[0]);
                $field.addClass('is-invalid');
              });
            }else{
                if(response.status){
                    success_sweettoatr(response.msg);
                    window.open("{{route("shopscheme.emipay",$enrollcusto->id) }}", '_self');
                }else{
                    toastr.error(response.msg);
                }
            }
        });
    });
</script>