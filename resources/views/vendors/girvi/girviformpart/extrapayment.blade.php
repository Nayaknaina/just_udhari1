<style>
    ul{
        list-style:none;
        padding:0;
    }
    ul > li > span{
        float:right;
    }
    .bg-lighter{
        background: #f4f4f4;
    }
</style>
<div class="col-12 p-0" id="extra_pay_page_data">
    <h6 class="text-center p-1" style="border:1px solid gray;border-radius:10px;"><b>GRV_B-{{ $batch->receipt }}</b> ({{ $batch->item_count}} Items)</h6>
    <ul class="row m-0">
        <li class="col-md-6"><b>Valuation : </b><span>{{ ($batch->girvi_value)??'-' }} ₹</span></li>
        <li class="col-md-6"><b>Issue :</b><span>{{ ($batch->girvi_issue)??'-' }} ₹</span></li>
    </ul>
    <ul class="row m-0">
        <li class="col-md-4"><b>Interest : </b><span>{{ ($batch->interest_type)??'-' }}</span></li>
        <li class="col-md-4"><b>Rate :</b><span>{{ ($batch->interest_rate)??'-' }} %</span></li>
        <li class="col-md-4"><b>Value :</b><span>{{ ($batch->interest)??'-' }} ₹</span></li>
    </ul>
    <ul class="row m-0">
        <li class="col-md-6 form-control h-32px btn-roundhalf bg-lighter" style="line-height: initial;"><b>Principal :</b><span>{{ ($batch->principle)??'-' }} ₹</span></li>
        <li class="col-md-6 form-control h-32px btn-roundhalf bg-lighter" style="line-height: initial;"><b>Interest :</b><span>{{ ($batch->interest)??'-' }} ₹</span></li>
    </ul>
    <hr class="m-2" style="border-top:1px dashed orange;">
    @php 
        $diff_amnt = $batch->girvi_value - $batch->girvi_issue;
    @endphp 
    <form action="{{ route('girvi.operation') }}/extrapayment" role="form"  id="girvi_op_extra_payment" outocomplete="off" >
    <div class="row">
            @csrf
            <input type="hidden" name="ep_pre_batch" value="{{ $batch->id }}">
            <div class="form-group col-md-6 mb-1">
                <div class="input-group position-relative  mb-0">
                    <input type="text" name="extra_pay" class="form-control h-32px btn-roundhalf floatdigit text-center" placeholder="Extra pay amount" value="{{ $diff_amnt }}" id="extra_pay">
                    <span class="gm-inside">₹</span>
                </div>
            </div>
            <div class="form-group col-md-6 mb-1">
                <select name="medium" class="form-control h-32px btn-roundhalf  text-center" id="medium">
                    <option value="">Medium ?</option>
                    <option value="on">Online</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            <div class="col-12 text-center">
                <hr class="m-2" style="border-top:1px solid orange;">
                <button type="submit" name="pay" value="extra" class="btn btn-sm btn-success">Pay</button>
            </div>
        </div>
    </form>
</div>
<script>
    var diff = {{ $diff_amnt }}
    $("#extra_pay").on('input',function(){
        const extra = $(this).val()??false;
        if(extra){
            if(extra > diff){
                $(this).val(extra.slice(0,-1));
                toastr.error("Extra Payment Can't be Greater than <b> "+diff+"</b> !");
            }
        }
    });

    $("#girvi_op_extra_payment").submit(function(e){
        e.preventDefault();
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.errors){
                let errors = response.errors;
                let field = false;
                $.each(errors,function(ei,ev){
                    field = $('[name="'+ei+'"]');
                        //$('[name="'+ei+'"]').addClass('is-invalid');
                    field.addClass('is-invalid');
                    let msg = false;
                    $.each(ev,function(vi,vv){
                        if(msg){
                            msg+='\n';
                        }else{
                            msg = vv;
                        }
                        toastr.error(msg);
                    });
                })
            }else if(response.error){
                toastr.error(response.error);
            }else{
                success_sweettoatr(response.success);
                //location.reload();
            }
        });
    });
</script>