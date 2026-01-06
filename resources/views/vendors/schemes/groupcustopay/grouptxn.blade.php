@php 
    $choosed_emi = $enrollcusto->emi_amnt;
    $scheme_start = $enrollcusto->schemes->scheme_date;
    $scheme_validity = $enrollcusto->schemes->scheme_validity;
    $deposited = $enrollcusto->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt')??0;
    $start_month_num = date('m',strtotime($scheme_start));
    $end_month_num = $start_month_num+$scheme_validity;
    $month_noun = date("M",strtotime($scheme_start));
    $month_arr[] = $month_noun;
    for($i=1;$i<$scheme_validity;$i++){
    array_push($month_arr,date("M",strtotime("{$month_noun}+$i Month")));
    }
    $paid_emi_num_arr = [];
    $msg_block = "";
    $month_option = "";
    foreach($enrollcusto->emipaid as $key=>$array){
        if(in_array($array['action_taken'],['A','U'])){
            if(isset($paid_emi_num_arr[$array['emi_num']])){
            $paid = $paid_emi_num_arr[$array['emi_num']][1]+$array['emi_amnt'];
            $paid_emi_num_arr[$array['emi_num']] = [$array['action_taken'],$paid];
            }else{
            $paid_emi_num_arr[$array['emi_num']] = [$array['action_taken'],$array['emi_amnt']];
            }
        }
    }
    $total_bonus = $total_emi_paid  = 0;
    $start_month = date("Y-m",strtotime($enrollcusto->schemes->scheme_date));
    $paid = $enrollcusto->emipaid->count('emi_num')??0;
    $pre_paid = $curr_paid = null;
    $prev_emi = 0;
    $bonus_grant = ($enrollcusto->schemes->lucky_draw==1)?false:( ($enrollcusto->schemes->scheme_interest=='Yes')?true:false);
@endphp
<ul style="" class="" id="color_indecator">
    <li>&nbsp;Addedd</li>
    <li style="{{ (!$bonus_grant)?'display:none;':'' }}">&nbsp;No Bonus</li>
    <li>&nbsp;Edited</li>
    <li>&nbsp;Updated</li>
    <li>&nbsp;Deleted</li>
</ul>
<style>
#color_indecator > li{
    display:inline;
}
#color_indecator > li:before{
    content:" ";
    padding-left:8px;
    padding-right:8px;
}
#color_indecator > li:nth-child(1):before{
    color:white;
    background:white;
    border:1px solid lightgray;
}
#color_indecator > li:nth-child(2):before{
    color:#ffbd203d !important;
    background:#ffbd203d !important;
    border:1px solid orange;
}
#color_indecator > li:nth-child(3):before{
    color:#bee2f054 !important;
    background:#bee2f054 !important;
    border:1px solid #bee2f0;
}
#color_indecator > li:nth-child(4):before{
    color:#c8c8ff7a !important;
    background:#c8c8ff7a !important;
    border:1px solid #c8c8ff;
}
#color_indecator > li:nth-child(5):before{
    color:#ff95951c !important;
    background:#ff95951c !important;
    border:1px solid #ff9595;
}
.updated_value{
    border-bottom:1px dotted blue;
    font-size:120%;
}
tr.clicked{
    border:2px dotted blue;
}
tr.highlight{
    border:2px solid dotted blue;
    box-shadow: 1px 2px 3px 5px #0058ff;
}
table.emi-pay-table>thead>tr>th {
    color: white;
    background: #66778c;
}
table.emi-pay-table>tbody>tr.table-my-warning{
    background-color:#ffbd203d !important;
}
table.emi-pay-table>tbody>tr.table-my-warning{
    background-color:#ffbd203d !important;
}
table.emi-pay-table>tbody>tr.table-my-info{
    background-color:#bee2f054 !important;;
}
table.emi-pay-table>tbody>tr.table-my-primary{
    background-color:#c8c8ff7a !important;;
}
table.emi-pay-table>tbody>tr.table-my-danger{
    background-color:#ff95951c !important;
}
table.emi-pay-table>tbody>tr.no-bonus>td,table.emi-pay-table>tbody>tr.no-bonus>th{
    color:orange;
    font-weight:bold;
}
ul.scheme_ul {
    list-style: none;
    border-bottom: 1px solid gray;
    /* font-size: 120%;
    font-weight: bold; */
    overflow: hidden;
}

ul.custo_ul {
    list-style: none;
    overflow: hidden;
    padding: 0;
}

ul.custo_ul>li {
    padding-top: 10px;
}

ul.custo_ul>li>span {
    float: right;
}

ul.scheme_ul>li {
    padding: 10px;
}

ul.scheme_ul>li:nth-child(1) {
    background: #c6c6c6;
}

ul.scheme_ul>li:nth-child(2) {
    background: lightgray;
}

ul.scheme_ul>li:nth-child(3) {
    background: #eaeaea;
}

ul.scheme_ul>li:nth-child(4) {
    background: #eaeaea;
}

ul.scheme_ul>li:after {
    content: " ";
    display: block;
    width: 0;
    height: 0;
    border-top: 50px solid transparent;
    border-bottom: 50px solid transparent;
    position: absolute;
    top: 50%;
    margin-top: -50px;
    left: 100%;
    z-index: 1;
}

ul.scheme_ul>li:nth-child(1):after {
    border-left: 30px solid #c6c6c6;
}

ul.scheme_ul>li:nth-child(2):after {
    border-left: 30px solid lightgray;
}

label.form-control {
    background: lightgray;
}

label.form-control.sn {
    width: max-content;
}

label.form-control.sn.active {
    background: #a6eea66e;
    color: #2507b5;
}

</style>
<div class="table-responsive">
    <table id="CsTable" class="table table_theme tabel-bordered emi-pay-table dataTable">
        <thead>
            <tr>
                <th>ENTRY</th>
                <th>MONTH</th>
                <th>EMI</th>
                @if($bonus_grant)
                <th>BONUS</th>
                @endif
                <th>DATE</th>
                <th>PAY MODE</th>
                <th>PAY MEDIUM</th>
                <th>REMARK</th>
                <th>UPDATE f</th>
            </tr>
        </thead>
        <tbody> 
        @foreach($enrollcusto->emipaid as $emik=>$emi)
            @php 
            $no_bonus = "";
            $curr_bonus_amount = null;
            $emi_date = "{$start_month}-15";
            $month_plus = $emi->emi_num-1; 
            $date = new DateTime($emi_date);
            $now_date = $date->modify("+{$month_plus} Month")->format("Y-m-d");
            $total_emi_paid += $emi->emi_amnt;
            @endphp
            @if($bonus_grant)
            @if($emi->emi_date <= $now_date && in_array($emi->action_taken,['A','U']))
                @php 
                $total_bonus += $curr_bonus_amount= ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='per')?($emi->emi_amnt*$enrollcusto->schemes->interest_rate)/100:$enrollcusto->schemes->interest_amt):0;
                @endphp
            @else
                @php
                $no_bonus = '-my-warning';
                @endphp
            @endif
            @endif
            @php 
            $table_class_arr = ['D'=>'-my-danger','E'=>'-my-info','U'=>'-my-primary'];
            if($emi->action_taken !='A' ){
                $no_bonus = "{$table_class_arr[$emi->action_taken]}";
            }
            @endphp
            @if($emi->emi_num !=0)
            <tr class="table{{ $no_bonus }} {{ ($bonus_grant && $curr_bonus_amount==0)?'no-bonus':'' }}" id="parent_{{ $emi->id  }}">

            <td style="color:unset;font-weight:unset;">
                {{ date("d-m-Y H:i:a",strtotime($emi->created_at)) }}
            </td>
            <td class="text-center">
                {{ date("M",strtotime($now_date)) }}
            </td>
            <td class="text-center">
                @if(!empty($emi->action_on))
                <a href="#parent_{{ $emi->action_on }}" class="uadated_value">{{  $emi->emi_amnt }}</a>
                @else 
                {{  $emi->emi_amnt }}
                @endif
            </td>
            @if($bonus_grant)
            <td class="text-center">
                {{ $curr_bonus_amount??0 }}
                ({{ ($emi->bonus_type=='T')?'Token':'Bonus'}})
            </td>
            @endif
            <td>
                {{ date("d-m-Y",strtotime($emi->emi_date)) }}
            </td>
            <td>
                {{ @$emi->pay_mode }}
            </td>
            <td>
                {{ @$emi->pay_medium }}
            </td>
            <td class="text-center">{{ $emi->remark }}</td>
            <td style="color:unset;font-weight:unset;">
                {{ date("d-m-Y H:i:a",strtotime($emi->updated_at)) }}
            </td>
            </tr> 
            @endif
        @endforeach
        </tbody>
    </table>
</div>