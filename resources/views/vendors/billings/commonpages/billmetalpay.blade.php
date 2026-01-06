<h6 class="text-primary">Pay Option => METAL</h6>

<div class="input-group mb-1">
    <input type="hidden" name="option" id="option" value="metal">
    <select class="form-control pay_input_field" id="metal" name="metal" style="border-radius:5px 0 0 5px!important;">
        <option value="">Metal ?</option>
        <option value="gold" id="gold" data-rate="{{ $data->gold_rate }}">Gold</option>
        <option value="silver" id="silver" data-rate="{{ $data->silver_rate/1000 }}">Silver</option>
    </select>
    <input type="text" name="pay_tunch" id="tunch" value="" placeholder="tunch" class="form-control text-center pay_input_field" style="border-radius:0 5px 5px 0!important;">
</div>

<div class="input-group mb-1">
    <input type="text" name="pay_gross" id="gross" value="" placeholder="Gross" class="form-control text-center pay_input_field" style="border-radius:5px 0 0 5px!important;">
    <input type="text" name="pay_net" id="net" value="" placeholder="Net" class="form-control text-center pay_input_field" style="border-radius:0!important;">
    <input type="text" name="pay_fine" id="fine" value="" placeholder="Fine" class="form-control text-center pay_input_field" style="border-radius:0 5px 5px 0!important;">
</div>

<div class="input-group mb-1" id="rate_amount">
    <input type="text" name="pay_rate" id="rate" value="" placeholder="Rate" class="form-control text-center text-info pl-3 pay_input_field" style="border-radius:5px 0 0 5px!important;">
    <input type="text" name="amount" id="amount" value="" placeholder="Amount" class="form-control text-center pay_input_field" style="border-radius:0 5px 5px 0!important;">
</div>

<div class="row">
    <div class="form-group col-8 mb-1">
        <textarea id="remark" class="form-control pay_input_field" id="remark" name="remark"></textarea>
    </div>
    <div class="form-group col-4 text-center mb-1" style="align-content: end;">
        <button type="button" id="pay_ok" class="btn btn-success btn-sm" data-source="metal">&check; Ok </button>
    </div>
</div>
<style>
     #rate_amount:after{
        content:'Rs';
        position:absolute;
        bottom:0;
        z-index:3;
     }
     #rate_amount:before{
        position:absolute;
        font-size:60%;
        font-weight:bold;
        color:blue;
        top:-5px;
        background:white;
        z-index:4;
     }
    #rate_amount.active.gold:before{
        content:'(24K/Gm)';
    }
    #rate_amount.active.silver:before{
        content:'(1Gm)';
    }
</style>

<script>
    $('.pay_option_btn').removeClass('active');
    $('#pay_option_metal').addClass('active');
    $("#metal").change(function(e){
        let selected = $(this).find("option:selected");
        const id = selected.attr('id');
        const rate = selected.data('rate')??false;
        $("#rate_amount").removeClass('active gold silver');
        if(rate){
            $("#rate").val(rate);
            $("#rate_amount").addClass(`active ${id}`);
        }else{
           $("#rate").val(''); 
        }
        calculatecost();
    });

    
    $("#tunch").on('input',function(){
        const metal = $("#metal").val()||false;
        if(!metal){
            toastr.error("Metal Required !");
             $(this).val($(this).val().slice(0,-1));
             $("#metal").focus();
             return false;
        }
        createfine();
    });

    $("#gross").on('input',function(){
        const metal = $("#metal").val()||false;
        if(!metal){
            toastr.error("Metal Required !");
            $(this).val($(this).val().slice(0,-1));
            $("#metal").focus();
            return false;
        }else{
            const gross = $(this).val()??false;
            $("#net").val(gross);
        }
        createfine();
    });
    $("#net").on('input',function(){
        const gross = $("#gross").val()??false;
        if(!gross){
            toastr.error("Gross Required !");
            $(this).val($(this).val().slice(0,-1));
            $("#gross").focus();
            return false;
        }
        createfine();
    });

    $("#fine").on('input',function(){
        const gross = $("#gross").val()??false;
        if(!gross){
            toastr.error("Gross Required !");
            $(this).val($(this).val().slice(0,-1));
            $("#gross").focus();
            return false;
        }else{
            const net = $("#net").val()||false;
            if(!net){
                toastr.error("Net Required !");
                $(this).val($(this).val().slice(0,-1));
                $("#net").focus();
                return false;
            }else{
                const fine = $(this).val()??false;
                if(!fine){
                    createfine();
                }else{
                    calculatecost();
                }
            }
        }
    });

    function createfine(){
        const gross = $("#gross").val()||false;
        var net = $("#net").val()||false;
        net = (net)?net:gross;
        const tunch =  $("#tunch").val()??false;
        if(net){
            const fine = +((tunch)?((+net * +tunch)/100):net);
            $("#fine").val(fine.toFixed(3));
        }else{
            $("#fine").val('');
        }
        calculatecost();
    }

    function calculatecost(){
        const metal = $("#metal").val()??false;
        var amount = 0;
        if(metal){
            const fine = $("#fine").val()??0;
            const rate = $("#rate").val()??0;
            amount = +fine * +rate;
        }
        amount = (amount==0)?'':amount.toFixed(2);
        $("#amount").val(amount);
    }
</script>