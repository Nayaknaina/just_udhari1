<tbody>
    @if($data->count()>0)
        @php 
            $data_open = $open_txn['open'];
            $data_txn = $open_txn['txn'];

            $gold_open = number_format(($data_open->gold_net_sum??0),3);
            $silver_open = number_format(($data_open->silver_net_sum??0),3);
            $stone_open = number_format(($data_open->stone_count_sum??0),1);
            $art_open = number_format(($data_open->art_count_sum??0),1);
            $money_open = number_format(($data_open->money_sum??0),2);

            $gold_txn = number_format(($data_txn->gold_net_sum??0),3);
            $silver_txn = number_format(($data_txn->silver_net_sum??0),3);
            $stone_txn = number_format(($data_txn->stone_count_sum??0),1);
            $art_txn = number_format(($data_txn->art_count_sum??0),1);
            $money_txn = number_format(($data_txn->money_sum??0),2);

            //$gold_close = $gold_open + $gold_txn;

            //$silver_close = $silver_open + $silver_txn;

            //$stone_close = $stone_open + $stone_txn;

            //$art_close = $art_open + $art_txn;

            //$amnt_close = str_replace(',','',$money_open) + str_replace(',','',@$money_txn);

            $gold_in = $gold_out = $silver_in = $silver_out = $stone_in = $stone_out = $art_in = $art_out = $amnt_in = $amnt_out = $frnch_in = $frnch_out = 0;
            $src_arr = ['imp'=>'Stock Import','ins'=>"Stock Save",'sll'=>'Sell','prc'=>'Purchase','udh'=>'Udhar','cut'=>'Bhav Cut','sch'=>'Scheme'];
            
        @endphp
        @foreach($data as $key=>$txn)
            @php 
                $object = strtolower($txn->object);
                $tr_class = "tr_".strtolower($txn->action);
            @endphp
            <tr class="text-center {{ $tr_class }}">
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
                    <a href="#" class="text-info"><i>
                        {{ @$src_arr["{$txn->source}"]??'--' }}</i>
                    <hr class="m-0 p-0">
                    {{ (!in_array($txn->source,['ins','imp','udh','cut','sch']))?$txn->reference:(in_array($txn->source,['ins','imp'])?"Entry {$txn->reference}":"") }}
                    </a>
                </td>
                <td>{{ @$txn->money_type??'--' }}</td>
                <td class="text-success">
                    {!! (@$txn->amnt_null)?'<strike>'.$txn->amnt_null.'Rs</strike>':(($txn->amnt_plus)?$txn->amnt_plus.' Rs.':'--') !!}
                </td>
                <td class="text-danger">
                    {!! (@$txn->amnt_null)?'<strike>'.$txn->amnt_null.'Rs</strike>':(($txn->amnt_minus)?$txn->amnt_minus.' Rs.':'--') !!}
                </td>
                <td class="text-center" style="font-weight:bold;">{{ number_format(($txn->money_balance??0),2).'Rs' }}</td>

                <td>{{ $txn->gold_type??'--' }}</td>
                <td>{{ ($txn->karet)?$txn->karet.'K':'--' }}</td>
                <td class="text-success">{{ ($txn->gold_plus)?$txn->gold_plus." Gm":'--' }}</td>
                <td class="text-danger">{{ ($txn->gold_minus)?$txn->gold_minus." Gm":'--' }}</td>                
                <td class="text-dark" style="font-weight:bold;">{{ number_format(($txn->gold_balance??0),3).'Gm' }}</td>
                
                <td>{{ $txn->silver_type??'--' }}</td>
                <td>{{ ($txn->purity)?$txn->purity.'%':'--' }}</td>
                <td class="text-success">{{ ($txn->silver_plus)?$txn->silver_plus." Gm":'--' }}</td>
                <td class="text-danger">{{ ($txn->silver_minus)?$txn->silver_minus." Gm":'--' }}</td>
                <td class="text-dark" style="font-weight:bold;">{{ number_format(($txn->silver_balance??0),3).'Gm' }}</td>                

                <td class="text-success">{{ $txn->stone_plus??'--' }}</td>
                <td class="text-danger">{{ $txn->stone_minus??'--' }}</td>
                <td class="text-center" style="font-weight:bold;">{{ $txn->stone_balance??'--' }}</td>
                

                <td class="text-success">{{ $txn->art_plus??'--' }}</td>
                <td class="text-danger">{{ $txn->art_minus??'--' }}</td>
                <td class="text-center" style="font-weight:bold;">{{ $txn->artificial_balance??'--' }}</td>

                <td class="text-success">{{ $txn->frnch_plus??'--' }}</td>
                <td class="text-danger">{{ $txn->frnch_minus??'--' }}</td>
                <td class="text-center" style="font-weight:bold;">{{ $txn->franchise_balance??'--' }}</td>
                @php 
                    $gold_in+= ($txn->gold_plus??0);
                    $gold_out+= ($txn->gold_minus??0);
                    $silver_in+= ($txn->silver_plus??0);
                    $silver_out+= ($txn->silver_minus??0);
                    $stone_in+= ($txn->stone_plus??0);
                    $stone_out+= ($txn->stone_minus??0);
                    $art_in+= ($txn->art_plus??0);
                    $art_out+= ($txn->art_minus??0);
                    $frnch_in+= ($txn->frnch_plus??0);
                    $frnch_out+= ($txn->frnch_minus??0);
                    $amnt_in+= ($txn->amnt_plus??0);
                    $amnt_out+= ($txn->amnt_minus??0);

                    /*$gold_close -= ($txn->gold_plus??0);
                    $gold_close += ($txn->gold_minus??0);
                    $silver_close -= ($txn->silver_plus??0);
                    $silver_close += ($txn->silver_minus??0);
                    $stone_close -= ($txn->stone_plus??0);
                    $stone_close += ($txn->stone_minus??0);
                    $art_close -= ($txn->art_plus??0);
                    $art_close += ($txn->art_minus??0);
                    $amnt_close -= ($txn->amnt_plus??0);
                    $amnt_close += ($txn->amnt_minus??0);*/
                @endphp
            </tr>
        @endforeach
    @else 
    <tr><td colspan="27" class="text-center text-danger">NO Record !</td></tr>
    @endif
</tbody>
@if($data->count()>0)
<tfoot id="data_foot">
  <tr>

    <td colspan="5" class="text-center">
        <button type="button" name="" class="btn btn-dark btn-sm p-0 px-2 py-1 m-auto" onclick="printdaybook()">
            <i class="fa fa-print"></i> Print
        </button>
    </td>
    <td class="text-success bg-white">{{ $amnt_in }} Rs</td>
    <td class="text-danger bg-white">{{ $amnt_out }} Rs</td>

    <td colspan="3"></td>
    <td class="text-success bg-white">{{ $gold_in }} Gm.</td>
    <td class="text-danger bg-white">{{ $gold_out }} Gm.</td>

    <td colspan="3"></td>
    <td class="text-success bg-white">{{ $silver_in }} Gm.</td>
    <td class="text-danger bg-white">{{ $silver_out }} Gm.</td>

    <td></td>
    <td class="text-success bg-white">{{ $stone_in }}</td>
    <td class="text-danger bg-white">{{ $stone_out }}</td>

    <td></td>
    <td class="text-success bg-white">{{ $art_in }}</td>
    <td class="text-danger bg-white">{{ $art_out }}</td>

    <td></td>
    
    <td class="text-success bg-white">{{ $frnch_in }}</td>
    <td class="text-danger bg-white">{{ $frnch_out }}</td>

    <td></td>
  </tr>
</tfoot>
@endif