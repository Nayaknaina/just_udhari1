@php 
    //dd($txns);
    $txnslist = $txns->emipaid->whereIn('action_taken',['A','U']);
    $payable = $txns->schemes->scheme_validity*$txns->emi_amnt;
    $sum = $txnslist->sum('emi_amnt');
    $bonus = $txnslist->sum('bonus_amnt');
    $bonus_grant = ($txns->schemes->lucky_draw==1)?false:( ($txns->schemes->scheme_interest=='Yes')?true:false);
    $start_month = date("Y-m",strtotime($txns->schemes->scheme_date));
    $emi_date = "{$start_month}-15";
    $total_bonus   = 0;
@endphp

<div class="col-12">
    <div class="row">
        <div class="col-md-4" >
            <ul class="row emi_ul_info form-control h-auto m-auto">
                <li class="col-12"><strong>NAME</strong><span>{{ $txns->customer_name }}</span></li>
                <li class="col-12"><strong>ID</strong><span>{{ $txns->assign_id }}</span></li>
            </ul>
        </div>
        <div class="col-md-8">
            <ul class="row emi_ul m-auto">
                <li class="col-md-3 col-6 form-control h-auto text-warning"><strong>EMI</strong><hr><span>{{ $txns->emi_amnt }}</span></li>
                <li class="col-md-3 col-6 form-control h-auto text-danger"><strong>PAYABLE</strong><hr><span>{{ $payable }}</span></li>
                <li class="col-md-3 col-6 form-control h-auto text-success"><strong>PAID</strong><hr><span>{{ $sum }}</span></li>
                @if($txnslist->max('emi_num')==$txns->schemes->scheme_validity)
                <li class="col-md-3 col-6 form-control h-auto text-info "><strong>BONUS</strong><hr><span>{{ $bonus }}</span></li>
                @else
                <li class="col-md-3 col-6 form-control h-auto text-info " style="opacity:0.3;border:1px dashed red;"><strong>BONUS</strong><hr><span  id="final_bonus"></span></li>
                @endif
                
            </ul>
        </div>
        <div class="col-12 p-3 text-right">
            
            <a href="{{ url("{$ecommbaseurl}emipay/{$txns->id}")}}" class="btn btn-sm btn-success emi_pay_button">
                <i class="fa fa-rupee"></i> Pay ?
            </a>
        </div>
    </div>
</div>
<style>
.fa-rupee:before{
    content: "\f156";
}
.emi_ul,.emi_ul_info{
    box-shadow: 1px 2px 3px 2px;
}
.emi_ul,.emi_ul_info{
    list-style:none;
    padding:0;
}
.emi_ul_info > li > span{
    float:right;
}
.emi_ul_info > li{
    margin:3px 0 3px 0;
}
.emi_ul>li>hr{
    padding:0;
    margin:3px 0 3px 0;
}
.emi_ul>li{
   text-align:center;
}
</style>

<div class="col-12 p-0">
    <div class=" table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="bg-secondary">
                    <th>Entry</th>
                    <th>Month</th>
                    <th>Emi</th>
                    @if($bonus_grant)
                    <th>Bonus</th>
                    @endif
                    <th>Pay Mode</th>
                    <th>Pay Medium</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($txnslist->toArray()))
                    @foreach($txnslist as $txni=>$txn) 
                        @if($txn->emi_num!=0)
                            <tr>
                                <td>{{ date("d-m-Y",strtotime($txn->emi_date)) }}</td>
                                @php 
                                    $month_plus = $txn->emi_num-1; 
                                    $date = new DateTime($emi_date);
                                    $now_date = $date->modify("+{$month_plus} Month")->format("Y-m-d");
                                @endphp
                                <td>{{ date("M",strtotime($now_date)) }}</td>
                                <td>{{ $txn->emi_amnt }} Rs.</td>
                                @if($bonus_grant)
                                    @php 
                                        $total_bonus += $curr_bonus_amount= ($txns->schemes->scheme_interest=='Yes')?(($txns->schemes->interest_type=='per')?($txn->emi_amnt*$txns->schemes->interest_rate)/100:$txns->schemes->interest_amt):0;
                                    @endphp
                                    <td>
                                    {{ $curr_bonus_amount }} Rs.
                                    </td>
                                @endif
                                <td>{{ $txn->pay_mode }}</td>
                                <td>{{ $txn->pay_medium }}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center text-danger">No TXNs !</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<script>
    $("#final_bonus").empty().append('{{ $total_bonus }}');
</script>

