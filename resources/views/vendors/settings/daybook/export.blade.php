<style>
    @page {
        size: A4;
        margin: 5mm; /* top right bottom left */
    }

    body {
        margin: 0;
        padding: 0;
    }
    table{
        width:100%;
        border-collapse: collapse;
        word-wrap: anywhere;
    }
    table:not(:last-child) {
        padding-bottom: 15px;
    }
    td,th{
        border:1px solid gray;
    }
    th{
        padding:0 5px;
    }
    table#source_table td{
        text-align:right;
    }
    /*table#data_table > thead th,table#data_table > tbody td{
        font-size:80%;
    }*/
    table#source_table tbody >tr>th:first-child{
        text-align:left;
    }
    td{
        text-align:center;
    }
    td hr,th hr{
        border:none;
        border-top:1px solid lightgray;
        margin:1px 0;
    }
    table#page_header>thead>tr>th{
        border:unset;
    }
    /*table#page_header>thead>tr:first-child>th{
        font-size:110%;
    }*/
    table#page_header>thead>tr:first-child>th:first-child{
        text-align:left;
    }
    table#page_header>thead>tr:first-child>th:last-child{
        text-align:right;
    }
    table#page_header>thead>tr:last-child>th{
        text-align:center;
        font-size:110%;
    }
    td.no-border{
        padding:5px;
        border:unset;
    }
</style>
@php 
    $data = $data_txn['data']; 
    $open_txn = $data_txn['open_txn']; 
    
    $today = ($date)?(($date==date('Y-m-d',strtotime('now')))?'yes':'no'):false;
    
    $txn = $sum_data['txn'];
    $open = $sum_data['open'];
@endphp
<table id="page_header">
    <thead>
        <tr>
            <th>Print At : {{ date('Y-m-d H:i:s a',strtotime('now')) }}</th>
            <th>DAY-BOOK</th>
        </tr>
        <tr>
            <th colspan="2">{{ date('d-M-Y',strtotime($date)) }}</th>
        </tr>
    </thead>
</table>
<table class="m-auto" id="date_summery_table">
    <thead>
        <tr>
            <th>#</th>
            <th>GOLD</th>
            <th>SILVER</th>
            <th>STONE</th>
            <th>ARTIFICIAL</th>
            <th>MONEY</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>OPENING</th>
            <td class="summery_data">{{ number_format(($open->gold_net_sum??0),3) }} Gm.</td>
            <td class="summery_data">{{ number_format(($open->silver_net_sum??0),3) }} Gm.</td>
            <td class="summery_data">{{ number_format((($open->stone_cost_sum)?$open->stone_cost_sum:0),2) }} Rs{{ ($open->stone_count_sum)?"<small>".$open->stone_count_sum."</small>":'' }}</td>
            <td class="summery_data">{{ number_format((($open->art_cost_sum)?$open->art_cost_sum:0),2) }} Rs.{{ ($open->art_count_sum)?"<small>".$open->art_count_sum."</small>":'' }}</td>
            <td class="summery_data">{{ number_format(($open->money_sum??0),2) }} Rs.</td>
        </tr>
        <tr>
            <th>TRANSACTION</th>
            <td class="summery_data">{{ number_format((($txn->gold_net_sum)?$txn->gold_net_sum:0),3) }} Gm</td>
            <td class="summery_data">{{ number_format((($txn->silver_net_sum)?$txn->silver_net_sum:0),3) }} Gm.</td>
            <td class="summery_data">{{ number_format((($txn->stone_cost_sum)?$txn->stone_cost_sum:0),2) }} Rs.{{ ($txn->stone_count_sum)?"<small>".$txn->stone_count_sum."</small>":'' }}</td>
            <td class="summery_data">{{ number_format((($txn->art_cost_sum)?$txn->art_cost_sum:0),2) }} Rs.{{ ($txn->art_count_sum)?"<small>".$txn->art_count_sum."</small>":'' }}</td>
            <td class="summery_data">{{ number_format(($txn->money_sum??0),2) }} Rs</td>
        </tr>
        <tr>
            <th>CLOSING</th>
            @if($today)
                @if($today=='yes')
                    <td class="summery_data">0.000 Gm</td>
                    <td class="summery_data">0.000 Gm</td>
                    <td class="summery_data">0.00 Rs.</td>
                    <td class="summery_data">0.00 Rs.</td>
                    <td class="summery_data">0.00 Rs.</td>
                @else
                    @php 
                        $gold_close = (($open->gold_net_sum??0) + ($txn->gold_net_sum??0));
                        $silver_close = (($open->silver_net_sum??0) + ($txn->silver_net_sum??0));
                        $stone_cost_close = (($open->stone_cost_sum??0) + ($txn->stone_cost_sum??0));
                        $stone_count_close = (($open->stone_count_sum??0) + ($txn->stone_count_sum??0));
                        $art_cost_close = (($open->art_cost_sum??0) + ($txn->art_cost_sum??0));
                        $art_count_close = (($open->art_count_sum??0) + ($txn->art_count_sum??0));
                        $money_close = (($open->money_sum??0) + ($txn->money_sum??0));
                    @endphp
                    <td class="summery_data">{{ number_format(@$gold_close,3) }} Gm.</td>
                    <td class="summery_data">{{ number_format(@$silver_close,3) }} Gm.</td>
                    <td class="summery_data">{{ number_format($stone_cost_close,2).'Rs' }}.{{ ($stone_count_close!=0)?"<small>{$stone_count_close}</small>":'' }}</td>
                    <td class="summery_data">{{ number_format($art_cost_close,2).'Rs' }}.{{ ($art_count_close!=0)?"<small>{$art_count_close}</small>":'' }}</td>
                    <td class="summery_data">{{ number_format(@$money_close,2) }} Rs.</td>
                @endif
            @endif
        </tr>
        <tr>
            <td colspan="6" class="no-border"></td>
        </tr>
    </tbody>
</table>
@if($source->count()>0)
    @php 
        $src_full_name = ['imp'=>'Stock Import','ins'=>'Stock Save','sll'=>'Sell','prc'=>'purches','udh'=>'Udhar','cut'=>'Bhav Cut','sch'=>'Scheme'];
        $ini_count = count($src_full_name);
        $src_amnt_plus = $src_amnt_minus = $src_gold_plus = $src_gold_minus = $src_silver_plus = $src_silver_minus = $src_stone_rs_plus = $src_stone_rs_minus = $src_stone_num_plus = $src_stone_num_minus = $src_art_rs_plus = $src_art_rs_minus = $src_art_num_plus = $src_art_num_minus = 0;
    @endphp
<table id="source_table">
    <thead>
        <tr>
            <th rowspan="2">SOURCE</th>
            <th colspan="2">AMOUNT</th>
            <th colspan="2">GOLD</th>
            <th colspan="2">SILVER</th>
            <th colspan="2">STONE</th>
            <th colspan="2">ARTIFICIAL</th>
        </tr>
        <tr>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
            <th>IN</th>
            <th>OUT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($source as $key=>$src)
         <tr>
             <th>{{ strtoupper(@$src_full_name[$key]) }}</th>
             <td>{{ $src->money_sum_plus??0 }} Rs</td>
             <td>{{ $src->money_sum_minus??0 }} Rs</td>
             <td>{{ $src->gold_net_plus }} Gm</td>
             <td>{{ $src->gold_net_minus }} Gm</td>
             <td>{{ $src->silver_net_plus }} Gm</td>
             <td>{{ $src->silver_net_minus }} Gm</td>
             <td>{{ $src->stone_cost_plus??0 }}Rs <small>({{$src->stone_count_plus??0}})</small></td>
             <td>{{ $src->stone_cost_minus??0 }}Rs <small>({{$src->stone_count_minus??0}})</small></td>
             <td>{{ $src->art_cost_plus??0 }}Rs <small>({{$src->art_count_plus??0}})</small></td>
             <td>{{ $src->art_cost_minus??0 }}Rs <small>({{$src->art_count_minus??0}})</small></td>
         </tr>
            @php 
                $src_amnt_plus+=$src->money_sum_plus??0;
                $src_amnt_minus+=$src->money_sum_minus??0;
                $src_gold_plus+=$src->gold_net_plus??0;
                $src_gold_minus+=$src->gold_net_minus??0;
                $src_silver_plus+=$src->silver_net_plus??0;
                $src_silver_minus+=$src->silver_net_minus??0;

                $src_stone_rs_plus+=$src->stone_cost_plus??0;
                $src_stone_rs_minus+=$src->stone_cost_minus??0;
                $src_stone_num_plus+=$src->stone_count_plus??0;
                $src_stone_num_minus+=$src->stone_count_minus??0;
                
                $src_art_rs_plus+=$src->art_cost_plus??0;
                $src_art_rs_minus+=$src->art_cost_minus??0;
                $src_art_num_plus+=$src->art_count_plus??0;
                $src_art_num_minus+=$src->art_count_minus??0;
                if(isset($src_full_name[$key])){
                    unset($src_full_name[$key]);
                }
            @endphp 
        @endforeach
        @if($ini_count > count($src_full_name))
          @foreach($src_full_name as $key=>$source)
            <tr>
                <th>{{ strtoupper($source) }}</th>
                <td>0 Rs</td>
                <td>0 Rs</td>
                <td>0 Gm</td>
                <td>0 Gm</td>
                <td>0 Gm</td>
                <td>0 Gm</td>
                <td>0 Rs <small>(0)</small></td>
                <td>0 Rs <small>(0)</small></td>
                <td>0 Rs <small>(0)</small></td>
                <td>0 Rs <small>(0)</small></td>
          </tr>
          @endforeach 
        @endif
        <tr>
            <td colspan="11" class="no-border"></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th>TOTAL</th>
            <th>{{ $src_amnt_plus }} Rs.</th>
            <th>{{ $src_amnt_minus }} Rs.</th>
            <th>{{ $src_gold_plus }} Gm.</th>
            <th>{{ $src_gold_minus }} Gm.</th>
            <th>{{ $src_silver_plus }} Gm.</th>
            <th>{{ $src_silver_minus }} Gm.</th>
            <th>{{ $src_stone_rs_plus }}Rs <small>({{$src_stone_num_plus}})</small></th>
            <th>{{ $src_stone_rs_minus }}Rs <small>({{$src_stone_num_minus}})</small></th>
            <th>{{ $src_art_rs_plus }}Rs <small>({{$src_art_num_plus}})</small></th>
            <th>{{ $src_art_rs_minus }}Rs <small>({{$src_art_num_minus}})</small></th>
        </tr>
    </tfoot>
</table>
@endif
<table id="data_table" class="table table_theme m-0">
    <thead id="data_thead">
        <!-- First row: main groups -->
        <tr class="text-center">
            <th rowspan="2">SN.</th>
            <th rowspan="2">TIME</th>
            <th rowspan="2">C NAME/NO.</th>
            <th rowspan="2">SOURCE</th>
            <th colspan="3">AMOUNT</th>
            <th colspan="4">GOLD<small class="text-dark"><b>(Net)</b></small></th>
            <th colspan="4">SILVER<small class="text-dark"><b>(Net)</b></small></th>
            <th colspan="2">STONE<small class="text-dark"><b>(Pc.)</b></small></th>
            <th colspan="2">ARTIFICIAL<small class="text-dark"><b>(Pc.)</b></small></th>
        </tr>
        
        <!-- Second row: opening/closing sub columns -->
        <tr class="text-center">
            <th>TYPE</th>
            <th>IN/OUT</th>
            <th>BALANCE</th>

            <th>TYPE</th>
            <th>K</th>
            <th>IN/OUT</th>
            <th>BALANCE</th>

            <th>TYPE</th>
            <th>%</th>
            <th>IN/OUT</th>
            <th>BALANCE</th>

            <th>IN/OUT</th>
            <th>BALANCE</th>

            <th>IN/OUT</th>
            <th>BALANCE</th>
        </tr>
    </thead>
              @php 
                $act_arr = ['E'=>'Edit','U'=>'Update','D'=>'Delete'];
              @endphp
    <tbody>
        @if($data->count()>0)
            @php
                $gold_in = $gold_out = $silver_in = $silver_out = $stone_in = $stone_out = $art_in = $art_out = $amnt_in = $amnt_out = 0;
                $src_arr = ['imp'=>'Stock Import','ins'=>"Stock Save",'sll'=>'Sell','prc'=>'Purchase','udh'=>'Udhar','cut'=>'Bhav Cut','sch'=>'Scheme'];
            @endphp
            @foreach($data as $key=>$txn)
                @php 
                    $object = strtolower($txn->object);
                    $tr_class = "tr_".strtolower($txn->action);
                @endphp
                <tr class="text-center">
                    <td>{{ $txn->first_item + ($key+1) }}</td>
                    <td>{{ $txn->created_at->format('h:i:s a') }}</td>
                    <td>
                        @if(!in_array($txn->source,['ins','imp']) && !empty(@$txn->customer)) 
                            @php 
                                $customer = $txn->customer;
                            @endphp
                            {{ @$customer->custo_full_name??@$customer->supplier_name }}({{ @$customer->custo_fone??@$customer->mobile_no }})
                            <hr class="m-0 p-1">
                            {{ @$customer->custo_num??@$customer->supplier_num }}
                        @else 
                            --
                        @endif
                    </td>
                    <td>
                        {{ @$src_arr["{$txn->source}"]??'--' }}</i>
                        <hr class="m-0 p-0">
                        {{ (!in_array($txn->source,['ins','imp','udh','cut','sch']))?$txn->reference:(in_array($txn->source,['ins','imp'])?"Entry {$txn->reference}":"") }}
                        @if(@$act_arr[$txn->action])
                            {!! '<i>'.@$act_arr[$txn->action].'</i>' !!}
                        @endif
                    </td>
                    <td>{{ @$txn->money_type??'--' }}</td>
                    <td class="text-success">
                        {!! (@$txn->amnt_null)?'<strike>'.$txn->amnt_null.'</strike>':(($txn->amnt_plus)??-$txn->amnt_minus)??0 !!} 
                    </td>
                    <td class="text-center" style="font-weight:bold;">
                        {{ number_format(($txn->money_balance??0),2) }}
                    </td>

                    <td>{{ $txn->gold_type??'--' }}</td>
                    <td>{{ ($txn->karet)?$txn->karet.'K':'--' }}</td>
                    <td class="text-success">
                        {!! (@$txn->gold_null)?'<strike>'.$txn->gold_null.'Rs</strike>':(($txn->gold_plus)??-$txn->gold_minus)??'--' !!}
                    </td>                
                    <td class="text-dark" style="font-weight:bold;">
                        {{ number_format(($txn->gold_balance??0),3) }}
                    </td>
                    
                    <td>{{ $txn->silver_type??'--' }}</td>
                    <td>{{ ($txn->purity)?$txn->purity.'%':'--' }}</td>
                    <td class="text-success">
                        {!! (@$txn->silver_null)?'<strike>'.$txn->silver_null.'Rs</strike>':(($txn->silver_plus)??-$txn->silver_minus)??'--' !!}
                    </td>
                    <td class="text-dark" style="font-weight:bold;">
                        {{ number_format(($txn->silver_balance??0),3) }}
                    </td>                

                    <td class="text-success">
                        {!! (@$txn->stone_null)?'<strike>'.$txn->stone_null.'Rs</strike>':(($txn->stone_plus)??-$txn->stone_minus)??'--' !!}
                    </td>
                    <td class="text-center" style="font-weight:bold;">
                        {{ $txn->stone_balance??'--' }}
                    </td>
                    
                    <td class="text-success">
                        {!! (@$txn->srt_null)?'<strike>'.$txn->srt_null.'Rs</strike>':(($txn->art_plus)??-$txn->art_minus)??'--' !!}
                    </td>
                    <td class="text-center" style="font-weight:bold;">{{ $txn->artificial_balance??'--' }}</td>
                </tr>
                @php 
                    $gold_in+= ($txn->gold_plus??0);
                    $gold_out+= ($txn->gold_minus??0);
                    $silver_in+= ($txn->silver_plus??0);
                    $silver_out+= ($txn->silver_minus??0);
                    $stone_in+= ($txn->stone_plus??0);
                    $stone_out+= ($txn->stone_minus??0);
                    $art_in+= ($txn->art_plus??0);
                    $art_out+= ($txn->art_minus??0);
                    $amnt_in+= ($txn->amnt_plus??0);
                    $amnt_out+= ($txn->amnt_minus??0);
                @endphp
            @endforeach
        @else 
            <tr>
                <td colspan="24">
                    Na
                </td>
            </tr>
        @endif
    </tbody>
    @if($data->count()>0)
    <tfoot id="data_foot">
        <tr>

            <th colspan="5" class="text-center">
                TOTAL
            </th>
            <th class="text-success bg-white" colspan="2">{{ $amnt_in - $amnt_out }} Rs</th>

            <th colspan="2"></th>
            <th class="text-success bg-white"  colspan="2">{{ $gold_in - $gold_out }} Gm.</th>

            <th colspan="2"></th>
            <th class="text-success bg-white" colspan="2">{{ $silver_in - $silver_out }} Gm.</th>

            <th class="text-success bg-white" colspan="2">{{ $stone_in - $stone_out }}</th>

            <th class="text-success bg-white" colspan="2">{{ $art_in - $art_out }}</th>
        </tr>
    </tfoot>
    @endif
</table>