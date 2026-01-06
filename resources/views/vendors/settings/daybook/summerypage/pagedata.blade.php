@if(!empty($sum_data))
    @php 
        $today = ($date)?(($date==date('Y-m-d',strtotime('now')))?'yes':'no'):false;
        $txn = $sum_data['txn'];
        $open = $sum_data['open'];
    @endphp
    
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
            @if($today){
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
    </tbody>
@endif
