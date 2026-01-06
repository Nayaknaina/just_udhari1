<h6 class="text-primary">Pay Option => AMOUNT</h6>
<div class="form-group mb-1">
    <input type="hidden" name="option" id="option" value="amount">
    <select class="form-control pay_input_field" id="deposite_to" name="deposite_to">
        <option value="">Deposite To ?</option>
        <option value="0" class="shop" data-to="shop">Shop</option>
        @php 
            $banks = getallbanks('b');
        @endphp
        @if($banks->count() > 0)
            @foreach($banks as $bi=>$bank)
                <option value="{{ $bank->id}}" class="bank" data-to="{{ $bank->name }}"> {{ $bank->name}}</option>
            @endforeach
        @endif
    </select>
</div>
<div class="form-group mb-1">
    <select class="form-control pay_input_field" id="medium" name="medium" >
        <option value="">Medium ?</option>
        <option value="cash" class="shop_medium medium_option" >Cash</option>
        <option value="check" class="shop_medium medium_option">Check</option>
        <option value="upi" class="bank_medium medium_option">UPI</option>
        <option value="net" class="bank_medium medium_option">Net Bank</option>
    </select>
</div>
<div class="form-group mb-1">
    <input type="text" name="amount" id="amount" value="" class="form-control pay_input_field" placeholder="Amount(Rs)">
</div>
<div class="row">
<div class="form-group col-8 mb-1">
    <textarea id="remark" class="form-control pay_input_field" id="remark" name="remark"></textarea>
</div>
<div class="form-group col-4 text-center mb-1" style="align-content: end;">
    <button type="button" id="pay_ok" class="btn btn-success btn-sm" data-source="amount">&check; Ok </button>
</div>
</div>


<script>
    $('.pay_option_btn').removeClass('active');
    $('#pay_option_amount').addClass('active');
    $('option.medium_option').hide();
    $("#deposite_to").change(function(e){
        let selected = $(this).find("option:selected");
        const cls = selected.attr('class');
        $('option.medium_option').hide();
        $(`option.${cls}_medium`).show();
    });
</script>