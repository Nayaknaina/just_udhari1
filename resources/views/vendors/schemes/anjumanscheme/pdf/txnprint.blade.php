<style>
    @page{
        margin:0;
        padding:0;
        padding:0px;
    }
table{
    margin:0;
    padding:0px 5px;
    width:100%;
    font-size:80%;
}

</style>
@php 
    $enroll = $scheme_txn->enroll;
    $enroll_name = $enroll->custo_name;
    $customer_name = $enroll->customer->custo_full_name;
    $customer_num = $enroll->customer->custo_num;
    $customer_mob = $enroll->customer->custo_fone;
    $scheme_name = $scheme_txn->scheme->title;
    $scheme_type = strtoupper($scheme_txn->scheme->type);
    $unit = ($scheme_type=='GOLD')?'Gm.':'Rs.';
    $deposite = $withdraw = 0;
    if(in_array($scheme_txn->txn_action,['A','U'])){
        $deposite = ($scheme_txn->txn_status==1)?$scheme_txn->txn_quant:0;
        $withdraw = ($scheme_txn->txn_status==0)?$scheme_txn->txn_quant:0;
    }
    $txns = $enroll->txns;
    $total_deposite = $total_withdraw = 0; 
    $total_deposite = $txns->whereIn('txn_action',['A','U'])->where('txn_status',1)->where('txn_date','<=',$scheme_txn->txn_date)->sum('txn_quant')??0;
    $total_withdraw = $txns->whereIn('txn_action',['A','U'])->where('txn_status',0)->where('txn_date','<=',$scheme_txn->txn_date)->sum('txn_quant')??0;
@endphp
<table>
    <tbody>
        <tr>
            <th style="text-align:center;border-bottom:2px double black;">ANJUMAN SCHEME </th>
        </tr>
        <tr>
            <th style="text-align:center;">{{ $scheme_type }}</th>
        </tr>
        <tr>
            <td style="text-align:center;border-bottom:1px solid black;">{{ $scheme_name }}</td>
        </tr>
    </tbody>
</table>
<table>
    <tbody id="txn_data">
        <tr>
            <td colspan=2 style="text-align:center;border-bottom:1px dashed black;">
                {{ $enroll_name }} / {{ $customer_num }} - ({{ $customer_mob }})
            </td>
        </tr>
        <tr>
            <td>DEPOSITE</td><td style="text-align:right;"> +{{$deposite}} {{ $unit }}</td>
        </tr>
        <tr>
            <td>WITHDRAW</td><td style="text-align:right;">-{{$withdraw}} {{ $unit }}</td>
        </tr>
        <tr>
            <td>DATE</td><td style="text-align:right;">{{ date('d-M-Y',strtotime($scheme_txn->txn_date)) }}</td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr><th colspan="2" style="text-align:left;border-top:1px dotted black;border-bottom:1px dotted black;">TOTAL</th></tr>
    </thead>
    <tbody id="txn_summery">
        <tr>
            <td>DEPOSITE</td><td style="text-align:right;">+{{ $total_deposite }} {{ $unit }}</td>
        </tr>
        <tr>
            <td>WITHDRAW</td><td style="text-align:right;">-{{ $total_withdraw }} {{ $unit }}</td>
        </tr>
    </tbody>
</table>