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
        padding:2px;
        line-height:normal;
        /* line-height:normal; */
        font-size:120%;
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
@if($udhartxn->count()>0)
    @if(!empty($ac))
        <table id="top_table" style="width:100%">
            <tbody>
                <tr>
                    <td style="width:50%;text-align:left;"><b>Print-Time : {{ date("d-m-Y / H:i:s") }}</b></td>
                    <td style="width:50%;text-align:right;"><b>( Customer Transaction )</b></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;"><h2 style="margin:0;">{{ $ac->custo_num }} / {{ $ac->custo_name }}</h2></td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Date/Time</th>
                    <th>Source</th>
                    <th>Remark</th>
                    <th>Gold In (+)</th>
                    <th>Gold Out (-)</th>
                    <th>Silver In (+)</th>
                    <th>Silver Out (-)</th>
                    <th>Amount in (+)</th>
                    <th>Amount Out (-)</th>
                    <th>Total</th>
                    <th>Bhav Cut</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $sn = 1;
                    $src_arr = ["S"=>'Sell Bill','P'=>'Purchase Bill','D'=>'Loan','C'=>"Conversion"];
                    $amnt = $silver = $gold = 0;
                @endphp
                    @foreach($udhartxn as $txnk=>$txn)
                    <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ date('d-m-Y / h:i:a',strtotime($txn->updated_at)) }}</td>
                        <td>{{ $src_arr[$txn->source] }}</td>
                        @php 
                        $brace_open = ($txn->source=='C')?"(":"";
                        $brace_close = ($txn->source=='C')?")":"";
                        $gold_plus = ($txn->gold_udhar)?(($txn->gold_udhar_status==1)?$txn->gold_udhar:0):0;
                        $gold_minus = ($txn->gold_udhar)?(($txn->gold_udhar_status==0)?$txn->gold_udhar:0):0;
                        $silver_plus = ($txn->silver_udhar)?(($txn->silver_udhar_status==1)?$txn->silver_udhar:0):0;
                        $silver_minus = ($txn->silver_udhar)?(($txn->silver_udhar_status==0)?$txn->silver_udhar:0):0;
                        $amnt_plus = ($txn->amount_udhar)?(($txn->amount_udhar_status==1)?$txn->amount_udhar:0):0;
                        $amnt_minus = ($txn->amount_udhar)?(($txn->amount_udhar_status==0)?$txn->amount_udhar:0):0;
                    @endphp
                    <td class="wrap-text">
                    {!! $txn->remark !!}
                    </td>
                    <td>
                        {{ ($gold_plus)?"$brace_open +{$gold_plus} gm $brace_close":'-' }}
                    </td>
                    <td>
                        {{ ($gold_minus)?"$brace_open -{$gold_minus} gm $brace_close":'-' }}
                    </td>
                    <td>
                        {{ ($silver_plus)?"$brace_open +{$silver_plus} gm $brace_close":'-' }}
                    </td>
                    <td>
                        {{ ($silver_minus)?"$brace_open -{$silver_minus} gm $brace_close":'-' }}
                    </td>
                    <td>
                        {{ ($amnt_plus)?"$brace_open +{$amnt_plus} Rs $brace_close":'-' }}
                    </td>
                    <td>
                        {{ ($amnt_minus)?"$brace_open -{$amnt_minus} Rs $brace_close":'-' }}
                    </td>
                    @php 
                    $amnt += $amnt_plus-$amnt_minus;
                    $silver += $silver_plus-$silver_minus;
                    $gold += $gold_plus-$gold_minus;
                    @endphp 
                    <td>
						<p style="display:block;width:100%;margin:0;padding:0;"><b>G :</b> {{ $gold }} gm</p>
                        <hr style="margin:0;border:none;border-top:1px dotted gray;">
                        <p style="display:block;width:100%;margin:0;padding:0;"><b>S :</b> {{ $silver }} gm</p>
                        <hr style="margin:0;border:none;border-top:1px dotted gray;">
                        <p style="display:block;width:100%;margin:0;padding:0;"><b>A :</b> {{ $amnt }} Rs</p>
                    </td>
                    <td class="wrap-text">
                    {!! $txn->remark !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else 
    <p class="text-center">{{ ($ac->custo_name??"Customer")."'s" }} Txns not Found !</p>
    @endif 
@endif
<style>
    @media print {
    table#top_table {
        border-right: 1px solid black !important;
    }
}
</style>