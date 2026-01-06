<div class="col-md-12 p-0">
    <label class="text-center text-white bg-dark form-control">
        {{ (@$date)?date('d-M-Y',strtotime(@$date)):'YYYY-MMM-DD' }} | SUMMERY
    </label>
    @php 
        $prev_summery = $data['prev'];
        $curr_summery = $data['curr'];
    @endphp
    <div class="table-responsive open_close_summery_area px-2">
        <table class="table table_theme">
            <thead>
                <tr class="table-dark">
                    <th></th>
                    <th class="text-whilte">OPENING</th>
                    <th class="text-whilte">Txn</th>
                    <th  class="text-whilte">CLOSING</th>
                </tr>
            </thead>
            <thead>
                <tr class="table-dark">
                    <th>GOLD</th>
                    <th>
                        @php 
                            $gold_open = (@$prev_summery->gold_jewellery_net+@$prev_summery->gold_loose_net+@$prev_summery->gold_bullion_net+@$prev_summery->gold_old_net)??0;
                        @endphp
                        {{ number_format($gold_open,3) }}
                    </th>
                    <th>
                        @php 
                            $gold_txn = (@$curr_summery->gold_jewellery_net+@$curr_summery->gold_loose_net+@$curr_summery->gold_bullion_net+@$curr_summery->gold_old_net)??0
                        @endphp
                        {{ number_format($gold_txn,3) }}
                    </th>
                    <th>
                        @if($date != date('Y-m-d',strtotime('now')))
                            @php 
                                $gold_close = $gold_open + $gold_txn
                            @endphp
                            {{ number_format($gold_close,3) }} 
                        @else 
                            0.000
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody id="gold_detail" class="detail_body">
                <tr class="text-center">
                    <td>USUAL</td>
                    <td>{{ number_format((@$prev_summery->gold_jewellery_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->gold_jewellery_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->gold_jewellery_net+@$prev_summery->gold_jewellery_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>LOOSE</td>
                    <td>{{ number_format((@$prev_summery->gold_loose_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->gold_loose_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->gold_loose_net+ @$prev_summery->gold_loose_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center"> 
                    <td>BULLION</td>
                    <td>{{ number_format((@$prev_summery->gold_bullion_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->gold_bullion_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->gold_bullion_net+@$prev_summery->gold_bullion_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>OLD</td>
                    <td>{{ number_format((@$prev_summery->gold_old_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->gold_old_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->gold_old_net+@$prev_summery->gold_old_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>OTHER</td>
                    <td>{{ number_format((@$prev_summery->gold_other_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->gold_other_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->gold_old_net+@$prev_summery->gold_old_net)??0),3) }}
                    @endif
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr class="table-dark">
                    <th>SILVER</th>
                    <th>
                        @php 
                            $silver_open = (@$prev_summery->silver_jewellery_net+@$prev_summery->silver_loose_net+@$prev_summery->silver_bullion_net+@$prev_summery->silver_old_net)??0;
                        @endphp
                        {{ number_format($silver_open,3) }}
                    </th>
                    <th>
                        @php 
                            $silver_txn = (@$curr_summery->silver_jewellery_net+@$curr_summery->silver_loose_net+@$curr_summery->silver_bullion_net+@$curr_summery->silver_old_net)??0
                        @endphp
                        {{ number_format($silver_txn,3) }}
                    </th>
                    <th>
                        @if($date != date('d-m-Y',strtotime('now')))
                            @php 
                                $silver_close = $silver_open + $silver_txn
                            @endphp
                            {{ number_format($silver_close,3) }}
                        @else 
                            0.000
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody  id="silver_detail" class="detail_body">
                <tr class="text-center">
                    <td>USUAL</td>
                    <td>{{ number_format(($prev_summery->silver_jewellery_net??0),3) }}</td>
                    <td>{{ number_format(($curr_summery->silver_jewellery_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->silver_jewellery_net + @$prev_summery->silver_jewellery_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center"> 
                    <td>LOOSE</td>
                    <td>{{ number_format((@$prev_summery->silver_loose_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->silver_loose_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->silver_loose_net + @$prev_summery->silver_loose_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>BULLION</td>
                    <td>{{ number_format((@$prev_summery->silver_bullion_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->silver_bullion_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->silver_bullion_net+@$prev_summery->silver_bullion_net)??0),3) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>OLD</td>
                    <td>{{ number_format((@$prev_summery->silver_old_net??0),3) }}</td>
                    <td>{{ number_format((@$curr_summery->silver_old_net??0),3) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.000
                    @else
                    {{ number_format(((@$curr_summery->silver_old_net+@$prev_summery->silver_old_net)??0),3) }}
                    @endif
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr class="table-dark">
                    <th>STONE</th>
                    <th>
                        @php 
                            $stone_open = $prev_summery->stone_count??0;
                        @endphp
                        {{ $stone_open }}
                    </th>
                    <th>
                        @php 
                            $stone_txn = $curr_summery->stone_count??0;
                        @endphp
                        {{ $stone_txn }}
                    </th>
                    <th>
                        @if($date != date('Y-m-d',strtotime('now')))
                            {{ ($stone_open + $stone_txn)??0 }}
                        @else 
                            0
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody  id="stone_detail" class="detail_body">
                <tr class="text-center">
                    <td>COUNT</td>
                    <td>{{ @$prev_summery->stone_count??0 }}</td>
                    <td>{{ @$curr_summery->stone_count??0 }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0
                    @else
                    {{ (@$curr_summery->stone_count+@$prev_summery->stone_count)??0 }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>COST</td>
                    <td>{{ number_format((@$prev_summery->stone_cost??0),2) }}</td>
                    <td>{{ number_format((@$curr_summery->stone_cost??0),2) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.00
                    @else
                    {{ number_format(((@$curr_summery->stone_cost+@$prev_summery->stone_cost)??0),2) }}
                    @endif
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr class="table-dark">
                    <th>ARTIFICIAL</th>
                    <th>
                        @php 
                            $atr_open = $prev_summery->artificial_count??0;
                        @endphp
                        {{ $atr_open }}
                    </th>
                    <th>
                        @php 
                            $atr_txn = $curr_summery->artificial_count??0;
                        @endphp
                        {{ $atr_txn }}
                    </th>
                    <th>
                        @if($date != date('Y-m-d',strtotime('now')))
                            {{ ($atr_open + $atr_txn)??0 }}
                        @else 
                            0
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody  id="artificial_detail" class="detail_body">
                <tr class="text-center">
                    <td>COUNT</td>
                    <td>{{ @$prev_summery->artificial_count??0 }}</td>
                    <td>{{ @$curr_summery->artificial_count??0 }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.00
                    @else
                    {{ @$curr_summery->artificial_count +@$prev_summery->artificial_count??0 }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>COST</td>
                    <td>{{ number_format((@$prev_summery->artificial_cost??0),2) }}</td>
                    <td>{{ number_format((@$curr_summery->artificial_cost??0),2) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.00
                    @else
                    {{ number_format(((@$curr_summery->artificial_cost+@$prev_summery->artificial_cost)??0),2) }}
                    @endif
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr class="table-dark">
                    <th>MONEY</th>
                    <th>
                        @php 
                            $money_open = @$prev_summery->money_cash + @$prev_summery->money_bank;
                        @endphp
                        {{ number_format($money_open,2) }}
                    </th>
                    <th>
                        @php 
                            $money_txn = @$curr_summery->money_cash + @$curr_summery->money_bank;
                        @endphp
                        {{ number_format($money_txn,2) }}
                    </th>
                    <th>
                        @if($date!=date('Y-m-d',strtotime('now')))
                            {{ number_format((($money_open + $money_txn)??0),2) }}
                        @else 
                            0.00
                        @endif
                    </th>
                </tr>
            </thead>
            <tbody id="money_detail" class="detail_body">
                <tr class="text-center">
                    <td>CASH</td>
                    <td>{{ number_format((@$prev_summery->money_cash??0),2) }}</td>
                    <td>{{ number_format((@$curr_summery->money_cash??0),2) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.00
                    @else
                    {{ number_format(((@$curr_summery->money_cash+@$prev_summery->money_cash)??0),2) }}
                    @endif
                    </td>
                </tr>
                <tr class="text-center">
                    <td>BANK</td>
                    <td>{{ number_format((@$prev_summery->money_bank??0),2) }}</td>
                    <td>{{ number_format((@$curr_summery->money_bank??0),2) }}</td>
                    <td>
                    @if($date==date('Y-m-d',strtotime('now')))
                        0.00
                    @else
                    {{ number_format(((@$curr_summery->money_bank+@$prev_summery->money_bank)??0),2) }}
                    @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
