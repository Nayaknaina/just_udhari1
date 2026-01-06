<div class="col-md-12">
<label class="text-center text-white bg-dark form-control">
    {{ (@$date)?date('Y-M-d',strtotime(@$date)):'YYYY-MMM-DD' }} | SUMMERY
</label>
@php 
    $prev_summery = $data['prev'];
    $curr_summery = $data['curr'];
@endphp
<div class="table-responsive">
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
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>USUAL</td>
                <td>{{ @$prev_summery->gold_jewellery_net??0 }}</td>
                <td>{{ @$prev_summery->gold_jewellery_net??0 }}</td>
                <td>{{ (@$curr_summery->gold_jewellery_net+@$prev_summery->gold_jewellery_net)??0 }}</td>
            </tr>
            <tr>
                <td>LOOSE</td>
                <td>{{ @$prev_summery->gold_loose_net??0 }}</td>
                <td>{{ @$prev_summery->gold_loose_net??0 }}</td>
                <td>{{ (@$curr_summery->gold_loose_net+ @$prev_summery->gold_loose_net)??0 }}</td>
            </tr>
            <tr>
                <td>BULLION</td>
                <td>{{ @$prev_summery->gold_bullion_net??0 }}</td>
                <td>{{ @$prev_summery->gold_bullion_net??0 }}</td>
                <td>{{ (@$curr_summery->gold_bullion_net+@$prev_summery->gold_bullion_net)??0 }}</td>
            </tr>
            <tr>
                <td>OLD</td>
                <td>{{ @$prev_summery->gold_old_net??0 }}</td>
                <td>{{ @$prev_summery->gold_old_net??0 }}</td>
                <td>{{ (@$curr_summery->gold_old_net+@$prev_summery->gold_old_net)??0 }}</td>
            </tr>
        </tbody>
        <thead>
            <tr class="table-dark">
                <th>SILVER</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>USUAL</td>
                <td>{{ $prev_summery->silver_jewellery_net??0 }}</td>
                <td>{{ $prev_summery->silver_jewellery_net??0 }}</td>
                <td>{{ (@$curr_summery->silver_jewellery_net + @$prev_summery->silver_jewellery_net)??0 }}</td>
            </tr>
            <tr>
                <td>LOOSE</td>
                <td>{{ @$prev_summery->silver_loose_net??0 }}</td>
                <td>{{ @$prev_summery->silver_loose_net??0 }}</td>
                <td>{{ (@$curr_summery->silver_loose_net + @$prev_summery->silver_loose_net)??0 }}</td>
            </tr>
            <tr>
                <td>BULLION</td>
                <td>{{ @$prev_summery->silver_bullion_net??0 }}</td>
                <td>{{ @$prev_summery->silver_bullion_net??0 }}</td>
                <td>{{ (@$curr_summery->silver_bullion_net+@$prev_summery->silver_bullion_net)??0 }}</td>
            </tr>
            <tr>
                <td>OLD</td>
                <td>{{ @$prev_summery->silver_old_net??0 }}</td>
                <td>{{ @$prev_summery->silver_old_net??0 }}</td>
                <td>{{ (@$curr_summery->silver_old_net+@$prev_summery->silver_old_net)??0 }}</td>
            </tr>
        </tbody>
        <thead>
            <tr class="table-dark">
                <th>STONE</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>COUNT</td>
                <td>{{ @$prev_summery->stone_count??0 }}</td>
                <td>{{ @$prev_summery->stone_count??0 }}</td>
                <td>{{ (@$curr_summery->stone_count+@$prev_summery->stone_count)??0 }}</td>
            </tr>
            <tr>
                <td>COST</td>
                <td>{{ @$prev_summery->stone_cost??0 }}</td>
                <td>{{ @$prev_summery->stone_cost??0 }}</td>
                <td>{{ (@$curr_summery->stone_cost+@$prev_summery->stone_cost)??0 }}</td>
            </tr>
        </tbody>
        <thead>
            <tr class="table-dark">
                <th>ARTIFICIAL</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>COUNT</td>
                <td>{{ @$prev_summery->artificial_count??0 }}</td>
                <td>{{ @$prev_summery->artificial_count??0 }}</td>
                <td>{{ @$curr_summery->artificial_count +@$prev_summery->artificial_count??0 }}</td>
            </tr>
            <tr>
                <td>COST</td>
                <td>{{ @$prev_summery->artificial_cost??0 }}</td>
                <td>{{ @$prev_summery->artificial_cost??0 }}</td>
                <td>{{ (@$curr_summery->artificial_cost+@$prev_summery->artificial_cost)??0 }}</td>
            </tr>
        </tbody>
        <thead>
            <tr class="table-dark">
                <th>MONEY</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>CASH</td>
                <td>{{ @$prev_summery->money_cash??0 }}</td>
                <td>{{ @$prev_summery->money_cash??0 }}</td>
                <td>{{ (@$curr_summery->money_cash+@$prev_summery->money_cash) }}</td>
            </tr>
            <tr>
                <td>BANK</td>
                <td>{{ @$prev_summery->money_bank??0 }}</td>
                <td>{{ @$prev_summery->money_bank??0 }}</td>
                <td>{{ (@$curr_summery->money_bank+@$prev_summery->money_bank)??0 }}</td>
            </tr>
        </tbody>
    </table>
</div>
</div>