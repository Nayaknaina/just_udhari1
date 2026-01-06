<style>
@page {
      margin: 10px;
    }
table{
    width:100%;
    border-collapse: collapse;
}
td,th{
    margin:0;
    padding:5px 0;
}
#top_block{
    font-size:70%;
    border-bottom:1px solid black;
}
table#top_block > tbody >tr >td:first-child{
    text-align:left;
}
table#top_block > tbody >tr >td:last-child{
    text-align:right;
}
#data_block{
    text-align:center;
    border-bottom:2px double black;
}
#vendor{
    font-size:120%;
}
.txn_info{
    border-bottom:1px solid black;
    font-size:80%;
}
.txn_info >tr:first-child>th{
    border-bottom:1px dashed black;
}
.txn_info > tbody > tr >td:first-child{
    width:30%;
}
.txn_info > tbody > tr >td:last-child{
    width:70%;
}
td.value{
    text-align:right;
}
.txn_info > tbody > tr.final>td{
    border-top:1px dotted black;
    font-weight:bold;
}
</style>
@php
    $udhar_arr = ["S"=>"Sell","P"=>"Purchase","D"=>"Loan","C"=>"Conversion"];
@endphp
<table id="top_block">
    <tbody>
        <tr>
            <td>Print Time : {{ date('s-m-Y H:i:s') }}</td>
            <td>UDHAR ({{ $udhar_arr[$udhar->source] }})</td>
        </tr>
    </tbody>
</table>
<table  id="data_block">
    <thead>
        <tr id="vendor">
            <th>{{ auth()->user()->shopbranch->branch_name }}</th>
        </tr>
        <tr >
            <td>Phone : {{ auth()->user()->shopbranch->mobile_no }}</td>
        </tr>
        <tr>
            <td>Customer Name/No. : {{ $udhar->account->custo_name }}/{{ $udhar->account->custo_num }}</td>
        </tr>
        <tr>
            <td>{{ date("d-m  H:i:a",strtotime($udhar->updated_at)) }}</td>
        </tr>
    </thead>
</table>
@php 
$pre_day = $udhar->account->pretxn($udhar->updated_at);
$amount_old = $pre_day->amount??0;
$gold_old = $pre_day->gold??0;
$silver_old = $pre_day->silver??0;
@endphp
<table class="txn_info">
    @php 
        $amnt_out = ($udhar->amount_udhar_status=='0')?"-{$udhar->amount_udhar}":0;
        $amnt_in = ($udhar->amount_udhar_status=='1')?"+{$udhar->amount_udhar}":0;
    @endphp
    <thead>
        <tr>
            <th colspan="2">Udhar Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Old</td>
            <td class="value">{{ $amount_old }} Rs</td>
        </tr>
        <tr>
            <td>Out(-)</td>
            <td class="value">{{ $amnt_out }} Rs</td>
        </tr>
        <tr>
            <td>In(+)</td>
            <td class="value">{{ $amnt_in }} Rs</td>
        </tr>
        @php $final_amnt = $amount_old+$amnt_in+$amnt_out @endphp 
        <tr class="final">
            <td>Final Amount</td>
            <td class="value">{{ $final_amnt }} Rs</td>
        </tr>
    </tbody>
</table>

<table  class="txn_info">
    @php 
        $gold_out = ($udhar->gold_udhar_status=='0')?"-{$udhar->gold_udhar}":0;
        $gold_in = ($udhar->gold_udhar_status=='1')?"+{$udhar->gold_udhar}":0;
    @endphp
    <thead>
        <tr>
            <th colspan="2">Udhar Gold</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Old</td>
            <td class="value">{{ $gold_old }} gm</td>
        </tr>
        <tr>
            <td>Out(-)</td>
            <td class="value">{{ $gold_out }} gm</td>
        </tr>
        <tr>
            <td>In(+)</td>
            <td class="value">{{ $gold_in }} gm</td>
        </tr>
        @php $final_gold = $gold_old+$gold_in+$gold_out @endphp 
        <tr class="final">
            <td>Final Gold</td>
            <td class="value">{{ $final_gold }} gm</td>
        </tr>
    </tbody>
</table>
<table  class="txn_info">
    @php 
        $silver_out = ($udhar->silver_udhar_status=='0')?"-{$udhar->silver_udhar}":0;
        $silver_in = ($udhar->silver_udhar_status=='1')?"+{$udhar->silver_udhar}":0;
    @endphp
    <thead>
        <tr>
            <th colspan="2">Udhar Silver</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Old</td>
            <td class="value">{{ $silver_old }} gm</td>
        </tr>
        <tr>
            <td>Out(-)</td>
            <td class="value">{{ $silver_out }} gm</td>
        </tr>
        <tr>
            <td>In(+)</td>
            <td class="value">{{ $silver_in }} gm</td>
        </tr>
        @php $final_silver = $silver_old+$silver_in+$silver_out @endphp 
        <tr class="final">
            <td>Final Silver</td>
            <td class="value">{{ $final_silver }} gm</td>
        </tr>
    </tbody>
</table>
