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
        padding:2px;
        text-align:center;
    }
    table#main_head{
        border-bottom:2px double black;
    }
    table#main_head>thead>tr>td{
        font-size:80%;
        text-align:center;
    }
    table#main_head>thead>tr>th{
        font-size:150%;
    }
    table#main_head>thead>tr:first-child>td{
        border-bottom:1px solid black;
    }
    tr.data_head{
        font-size:80%;
    }
    tr.data_value,tr.total{
        font-size:75%;
    }
    tr.data_value > td{
        text-wrap: anywhere;
    }
    tr.data_value > td:nth-child(1),
    tr.bhav_cut>td:nth-child(1),
    tr.total > td:nth-child(1){
        width:30%;
    }
    tr.data_value > td:nth-child(2),
    tr.data_value > td:nth-child(3),
    tr.total > td:nth-child(2),
    tr.total > td:nth-child(3){
        width:35%;
    }
    tr.bhav_cut{
        font-size:65%;
    }
    tr.bhav_cut > td{
        border-bottom:1px solid gray;
        border-top:1px solid gray;
    }
    table.info_table{
        border-bottom:1px double black;
    }
    table.info_table>thead>tr:first-child>th{
        border-bottom:1px dashed  black;
    }
    table.info_table>thead>tr>th{
        border-bottom:1px dotted black;
    }
    tr.final > th{
        border-top:1px solid black;
    }
  </style>
@if($udhartxn->count()>0)
<table id="main_head">
    <thead>
        <tr>
            <td >Bahi Khata (Ledger)</td>
        </tr>
        <tr>
            <th>{{ auth()->user()->shopbranch->branch_name }}</th>
        </tr>
        <tr>
            <td >Phone : {{ auth()->user()->shopbranch->mobile_no }}</td>
        </tr>
        <tr>
            <td >Customer Name/No. : {{ $ac->custo_name }}/{{ $ac->custo_num }}</td>
        </tr>
        <tr>
            <td>{{ date("d-m  H:i:a",strtotime('now')) }}</td>
        </tr>
    </thead>
</table>
    @php 
        $txn_data = $udhartxn->toArray();
        $goldData = array_filter(array_map(function ($row) {
            if(!empty($row['gold_udhar']) ){
                return [
                    'gold' =>$row['gold_udhar'],
                    'status' => $row['gold_udhar_status'],
                    'source'=>$row['source'],
                    'date'=>$row['updated_at'],
                    'remark'=>$row['remark']
                ];
            }
        }, $txn_data));
    
        $silverData = array_filter(array_map(function ($row) {
            if(!empty($row['silver_udhar'])){
                return [
                    'silver' =>$row['silver_udhar'],
                    'status' => $row['silver_udhar_status'],
                    'source'=>$row['source'],
                    'date'=>$row['updated_at'],
                    'remark'=>$row['remark']
                ];
            }
        }, $txn_data));
    
        $amountData = array_filter(array_map(function ($row) {
            if(!empty($row['amount_udhar'])){
                return [
                    'amount' =>$row['amount_udhar'],
                    'status' => $row['amount_udhar_status'],
                    'source'=>$row['source'],
                    'date'=>$row['updated_at'],
                    'remark'=>$row['remark']
                ];
            }
        }, $txn_data));
    @endphp
    @if(count($goldData) > 0)
    @php $ttl_gold_out = $ttl_gold_in = $gold_count = 0 @endphp
    <table class="info_table">
        <thead>
            <tr><th colspan="3">Udhar Gold</th></tr>
            <tr class="data_head">
                <th>Date</th>
                <th>Gold Out</th>
                <th>Gold In</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goldData as $gk=>$gold)
            @php 
                $gold_quote_open = ($gold['source']=='C')?"(":'';
                $gold_quote_close = ($gold['source']=='C')?")":'';
                $gold_out = ($gold['status']==0)?$gold['gold']:false;
                $gold_in = ($gold['status']==1)?$gold['gold']:false;
                $ttl_gold_out += $gold_out;
                $ttl_gold_in += $gold_in;
                $gold_count++;
                $pre_src = $gold['source'];
            @endphp
            <tr class="data_value">
                <td>{{ date('d-m-Y',strtotime($gold['date'])) }}</td>
                <td>{{ ($gold_out)?"{$gold_quote_open} -{$gold_out} gm {$gold_quote_close}":"-" }}</td>
                <td>{{ ($gold_in)?"{$gold_quote_open} +{$gold_in} gm {$gold_quote_close}":"-" }}</td>
            </tr>
            @if($gold['source']=='C')
                <tr class="bhav_cut">
                    <td>
                        BhavCut
                    </td>
                    <td colspan="2" >
                        {!! $gold['remark'] !!}
                    </td>
                </tr>
            @endif
            @if(count($goldData)==$gold_count)
                @php 
                    $border_top = ($pre_src=='C')?'style="border-top:1px solid black"':'';
                    $final_gold = $ttl_gold_in-$ttl_gold_out;
                @endphp
                <tr class="total">
                    <th {{ $border_top }}>TOTAL</th>
                    <th {{ $border_top }}>{{ ($ttl_gold_out)?"-{$ttl_gold_out} gm":'' }}</th>
                    <th {{ $border_top }}>{{ ($ttl_gold_in)?"+{$ttl_gold_in} gm":'' }}</th>
                </tr>
                <tr class="final">
                    <th>FINAL</th>
                    <th colspan="2">{{ $final_gold }} gm</th>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
    @if(count($silverData) > 0)
    @php $ttl_silver_out = $ttl_silver_in = $silver_count = 0 @endphp
    <table class="info_table">
        <thead>
            <tr><th colspan="3">Udhar Silver</th></tr>
            <tr class="data_head">
                <th>Date</th>
                <th>Silver Out</th>
                <th>Silver In</th>
            </tr>
        </thead>
        <tbody>
            @foreach($silverData as $sk=>$silver)
            @php 
                $silver_quote_open = ($silver['source']=='C')?"(":'';
                $silver_quote_close = ($silver['source']=='C')?")":'';
                $silver_out = ($silver['status']==0)?$silver['silver']:false;
                $silver_in = ($silver['status']==1)?$silver['silver']:false;
                $ttl_silver_out +=$silver_out;
                $ttl_silver_in +=$silver_in;
                $silver_count++;
                $pre_src = $silver['source'];
            @endphp
            <tr class="data_value">
                <td>{{ date('d-m-Y',strtotime($silver['date'])) }}</td>
                <td>{{ ($silver_out)?"{$silver_quote_open} -{$silver_out} gm {$silver_quote_close}":"-" }}</td>
                <td>{{ ($silver_in)?"{$silver_quote_open} +{$silver_in} gm {$silver_quote_close}":"-" }}</td>
            </tr>
            @if($silver['source']=='C')
                <tr class="bhav_cut">
                    <td>
                        BhavCut
                    </td>
                    <td colspan="2">
                        {!! $silver['remark'] !!}
                    </td>
                </tr>
            @endif
            @if(count($silverData)==$silver_count)
                @php 
                    $border_top = ($pre_src=='C')?'style="border-top:1px solid black"':'';
                    $final_silver = $ttl_silver_in-$ttl_silver_out;
                @endphp
                <tr class="total">
                    <th {{ $border_top }}>TOTAL</th>
                    <th {{ $border_top }}>{{ ($ttl_silver_out)?"-{$ttl_silver_out} gm":'' }}</th>
                    <th {{ $border_top }}>{{ ($ttl_silver_in)?"+{$ttl_silver_in} gm":'' }}</th>
                </tr>
                <tr class="final">
                    <th>FINAL</th>
                    <th colspan="2">{{ $final_silver }} gm</th>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
    @if(count($amountData) > 0)
    @php $ttl_amnt_out = $ttl_amnt_in = $amnt_count = 0 @endphp
    <table class="info_table">
        <thead>
            <tr><th colspan="3">Udhar Amount</th></tr>
            <tr class="data_head">
                <th>Date</th>
                <th>Amount Out</th>
                <th>Amount In</th>
            </tr>
        </thead>
        <tbody>
            @foreach($amountData as $ak=>$amnt)
            @php 
                $amount_quote_open = ($amnt['source']=='C')?"(":'';
                $amount_quote_close = ($amnt['source']=='C')?")":'';
                $amnt_out = ($amnt['status']==0)?$amnt['amount']:false;
                $amnt_in = ($amnt['status']==1)?$amnt['amount']:false;
                $ttl_amnt_out+=$amnt_out;
                $ttl_amnt_in+=$amnt_in;
                $amnt_count++;
                $pre_src = $amnt['source'];
            @endphp
            <tr class="data_value">
                <td>{{ date('d-m-Y',strtotime($amnt['date'])) }}</td>
                <td>{{ ($amnt_out)?"{$amount_quote_open}-{$amnt_out} Rs {$amount_quote_close}":"-" }}</td>
                <td>{{ ($amnt_in)?"{$amount_quote_open}+{$amnt_in} Rs {$amount_quote_close}":"-" }}</td>
            </tr>
            @if($amnt['source']=='C')
                <tr class="bhav_cut">
                    <td style="width:30%;">
                        BhavCut
                    </td>
                    <td colspan="2">
                        {!! $amnt['remark'] !!}
                    </td>
                </tr>
            @endif
            @if(count($amountData)==$amnt_count)
                @php 
                    $border_top = ($pre_src=='C')?'style="border-top:1px solid black"':'';
                    $final_amnt = $ttl_amnt_in-$ttl_amnt_out;
                @endphp
                <tr class="total">
                    <th {{ $border_top }}>TOTAL</th>
                    <th {{ $border_top }}>{{ ($ttl_amnt_out)?"-{$ttl_amnt_out} Rs":'' }}</th>
                    <th {{ $border_top }}>{{ ($ttl_amnt_in)?"+{$ttl_amnt_in} Rs":'' }}</th>
                </tr>
                <tr class="final">
                    <th>FINAL</th>
                    <th colspan="2">{{ $final_amnt }} Rs</th>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
@else 
    <p style="text-align:center;width:100%;">No Tsns !</p>
@endif