<h5 class="modal-title">EMI <small>Edit/Update</small>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true" class="text-danger">&times;</span>
    </button>
</h5>
<hr class="mt-0">
<form action="{{ route("shopscheme.emi.update") }}" method="post" id="edit_pay_form">
    <div class="row">
    @csrf
    <input type="hidden" name="emi" value="{{ $emi_data->id }}">
    <input type="hidden" name="num" value="{{ $emi_data->emi_num }}">
    <input type="hidden" name="enroll" value="{{ $emi_data->enroll_id }}">
        <div class="form-group col-12 ">
            <label for="amnt">EMI</label>
            <div class="input-group">
            <input type="number" name="amnt" id="amnt" class="form-control  text-center" value="{{ $emi_data->emi_amnt }}" style="font-weight:bold;">
            <span class="input-group-text px-1 bg-white" id="basic-addon2"><b>Rs.</b></span>
            </div>
        </div>
        <div class="form-group col-12">
            <label for="medium">Pay Medium</label>
            <select class="form-control  text-center" id="medium" name="medium" required>
                <option value="">SELECT</option>
                @php 
                $medium_arr = ['Cash','Token','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Draw','Vendor'];
                
                @endphp
                @foreach($medium_arr as $mk=>$med)
                <option value="{{ $med }}" {{ ($med==$emi_data->pay_medium)?'selected':"" }} >{{ $med }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 ">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control text-center" value="{{ $emi_data->emi_date }}">
        </div>
        <div class="form-group col-12">
            <label for="mode">Pay Mode</label>
            <select class="form-control text-center" id="mode" name="mode"  required>
                @php 
                    $pay_mode = strtolower($emi_data->pay_mode);
                    $$pay_mode = 'selected';
                @endphp 
                <option value="" >SELECT</option>
                <option value="SYS" {{ @$sys }} >System</option>
                <option value="ECOMM" {{ @$ecomm }} >E-Commerce</option>
            </select>
        </div>
        <div class="form-group col-12">
            <label for="remark_edit">Remark</label>
            <textarea name="remark" id="remark_edit" class="form-control  text-center" plpacecholder="Payment Related Remarks" required rows="1">{{ $emi_data->remark }}</textarea>
        </div>
        <div class="form-group col-12 text-center">
            <hr class="m-0 mb-2">
            <button type="submit" name="pay" value="part" class="btn btn-md btn-success" id="update_pay_button" data-target="#edit_pay_form" data-action="submit">Update</button>
        </div>
    </div>
</form>
<script>
    $("#update_pay_button").click(function(e){
        e.preventDefault();
        data_element = $(this).data('target');
        data_action = $(this).data('action');
        launchmpinmodal();
    })
    $('#edit_pay_form').submit(function(e){
        e.preventDefault();
        var formdata = $(this).serialize();
        var action = $(this).attr('action');
        $.post(action,formdata,function(response){
            //var res = JSON.parse(response);
            if(response.errors){
                $.each(response.errors, function(field, messages) {
                var $field = $('[name="' + field + '"]');
                toastr.error(messages[0]);
                $field.addClass('is-invalid');
              });
            }else{
                if(response.status){
                    success_sweettoatr(response.msg);
                    window.open("{{route("shopscheme.emipay",$emi_data->enroll_id) }}", '_self');
                }else{
                    toastr.error(response.msg);
                }
            }
        });
    });
	 $('#remark_edit').on('input', function () {
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>