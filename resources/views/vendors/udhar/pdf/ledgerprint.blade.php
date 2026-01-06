<style>
    table{
        width:100%;
        border-collapse: collapse;
        overflow:visible;
    }
    td,th{
        border-bottom:1px solid black;
        border-left:1px solid black;
        text-align:center;
        text-wrap:nowrap;
        width:auto;
        padding:5px;
        /* line-height:normal; */
        font-size:150%;
        line-height:normal;
        box-sizing: border-box;
    }
    tr >th:first-child,tr >td:first-child{
        border-left:unset;
    }
    td.wrap-text{
        text-wrap:initial;
    }
    ul > li{
        width:100%;
    }
    table{
        border:1px solid black;
    }
    table#top_table{
        border:none;
    }
    table#top_table>tbody>tr>td{
        border:none;
    }
</style>
@if($ledger_data->count()>0)
        <table id="top_table" style="width:100%">
            <tbody>
                <tr>
                    <td style="width:50%;text-align:left;"><b>Print-Time : {{ date("d-m-Y / H:i:s") }}</b></td>
                    <td style="width:50%;text-align:right;"><b>Ledger</b></td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th style="width:5%">SN</th>
                    <th style="width:40%">Name</th>
                    <th style="width:10%">Contact</th>
                    <th style="width:15%">Amount</th>
                    <th style="width:15%">GOLD</th>
                    <th style="width:15%">SILVER</th>
                </tr>
            </thead>
            @php 
                $sn = 1;
                $amnt_out = $amnt_in =  $silver_out = $silver_in = $gold_out = $gold_in = $num = 0;
            @endphp
            <tbody>
                @foreach($ledger_data as $txnk=>$ledger)
                @php 
                    $amnt_out+=($ledger->custo_amount_status==0)?-$ledger->custo_amount:0;
                    $amnt_in+=($ledger->custo_amount_status==1)?+$ledger->custo_amount:0;
                    $gold_out+=($ledger->custo_gold_status==0)?-$ledger->custo_gold:0;
                    $gold_in+=($ledger->custo_gold_status==1)?+$ledger->custo_gold:0;
                    $silver_out+=($ledger->custo_silver_status==0)?-$ledger->custo_silver:0;
                    $silver_in+=($ledger->custo_silver_status==1)?+$ledger->custo_silver:0;
                    $num++;
                @endphp
                <tr>
                    <td >{{ $sn++ }}</td>
                    <td >
                    {{ $ledger->custo_name }}/({{ @$ledger->custo_num }})
                    </td>
                    <td >
                    {{ $ledger->custo_mobile }}
                    </td>
                    <td > 
						{{ ($ledger->custo_amount)?(($ledger->custo_amount_status==0)?"-":"+").number_format($ledger->custo_amount, ($ledger->custo_amount == (int)$ledger->custo_amount ? 0 : 3), '.', '')." Rs":'-' }}
                        
                    </td>
                    <td > 
                         {{ ($ledger->custo_gold)?(($ledger->custo_gold_status==0)?"-":"+").number_format($ledger->custo_gold, ($ledger->custo_gold == (int)$ledger->custo_gold ? 0 : 3), '.', '')." Gm":'-' }}
                    </td>
                    <td >
                        {{ ($ledger->custo_silver)?(($ledger->custo_silver_status==0)?"-":"+").number_format($ledger->custo_silver, ($ledger->custo_silver == (int)$ledger->custo_silver ? 0 : 3), '.', '')." Gm":'-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if($num==$ledger_data->count()) 
            <tfoot>
                <tr>
                    <th colspan="3" class="text-center">Udhar Out(-)</th>
                    <th class="text-danger text-center"> {{ number_format($amnt_out, ($amnt_out == (int)$amnt_out ? 0 : 3), '.', '') }} Rs</th>
                    <th class="text-danger text-center">{{ number_format($gold_out, ($gold_out == (int)$gold_out ? 0 : 3), '.', '') }} gm</th>
                    <th class="text-center text-danger">{{ number_format($silver_out, ($silver_out == (int)$silver_out ? 0 : 3), '.', '') }} gm</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-center">Udhar In(+)</th>
                    <th class="text-danger text-center"> {{ ($amnt_in>0)?"+".number_format($amnt_in, ($amnt_in == (int)$amnt_in ? 0 : 3), '.', ''):0 }} Rs</th>
                    <th class="text-danger text-center">{{ ($gold_in>0)?"+".number_format($gold_in, ($gold_in == (int)$gold_in ? 0 : 3), '.', ''):0  }} gm</th>
                    <th class="text-center text-danger">{{ ($silver_in>0)?"+".number_format($silver_in, ($silver_in == (int)$silver_in ? 0 : 3), '.', ''):0 }} gm</th>
                </tr>
                <tr>
					@php 
                        $ttl_amnt = $amnt_in-$amnt_out;
                        $ttl_gold = $gold_in-$gold_out;
                        $ttl_slvr = $silver_in-$silver_out;
                    @endphp
                    <th colspan="3" class="text-center">TOTAL</th>
                    <th class="text-danger text-center"> {{ (($ttl_amnt >0)?'+':"").number_format($ttl_amnt, ($ttl_amnt == (int)$ttl_amnt ? 0 : 3), '.', '') }} Rs</th>
                    <th class="text-danger text-center">{{ (($ttl_gold >0)?'+':"").number_format($ttl_gold, ($ttl_gold == (int)$ttl_gold ? 0 : 3), '.', '') }} gm</th>
                    <th class="text-center text-danger">{{ (($ttl_slvr >0)?'+':"").number_format($ttl_slvr, ($ttl_slvr == (int)$ttl_slvr ? 0 : 3), '.', '')  }} gm</th>
                </tr>
            </tfoot>
            @endif
        </table>
    @else 
    <p class="text-center">No data !</p>
@endif
<style>
    @media print {
    table#top_table {
        border-right: 1px solid black !important;
    }
}
</style>