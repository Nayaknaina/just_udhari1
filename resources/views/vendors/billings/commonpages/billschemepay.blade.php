<h6 class="text-primary">Pay Option => SCHEME</h6>
<div class="form-group mb-1">
    <input type="hidden" name="option" id="option" value="scheme">
    <select class="form-control pay_input_field" id="scheme" name="scheme">
        <option value="" data-balance="">Scheme ?</option>
        @if($data->count() > 0)
            @foreach($data as $si=>$scheme)
                <option value="{{ $scheme->id??''}}" data-balance="{{ $scheme->balance_remains }}" data-label="{{ $scheme->scheme_head}}"> {{ $scheme->scheme_head}}</option>
            @endforeach
        @endif
    </select>
</div>
<div class="input-group mb-1">
    <style>
        #avail_scheme_balance:after{
            content:"/-Rs";
        }
    </style>
    <label class="input-group-text p-0 px-2 ">BALANCE : </label>
    <input type="hidden" name="scheme_balance" id="scheme_balance" value="" class="pay_input_field">
    <label class="input-group-text form-control text-info pay_input_field_amnt" id="avail_scheme_balance" style="border-radius:0 5px 5px 0;"></label>
</div>
<div class="form-group mb-1">
    <input type="text" name="amount" id="amount" value="" class="form-control pay_input_field" placeholder="Amount(Rs)">
</div>
<div class="row">
<div class="form-group col-8 mb-1">
    <textarea id="remark" class="form-control pay_input_field" id="remark" name="remark"></textarea>
</div>
<div class="form-group col-4 text-center mb-1" style="align-content: end;">
    <button type="button" id="pay_ok" class="btn btn-success btn-sm" data-source="scheme">&check; Ok </button>
</div>
</div>


<script>
    $('.pay_option_btn').removeClass('active');
    $('#pay_option_scheme').addClass('active');
    $("#scheme").change(function(e){
        let selected = $(this).find("option:selected");
        const balance  = selected.data('balance');
        $('#avail_scheme_balance').text(balance);
        $('#scheme_balance').val(balance);
    });
</script>