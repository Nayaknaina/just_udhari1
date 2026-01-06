@if($ledger_data->count()>0)
    @php 
        $status_arr = ['-','+'];
        $stts_arr = ["minus","plus"];
        $num = $gold_minus = $gold_plus = $silver_minus = $silver_plus = $money_minus = $money_plus = 0;
    @endphp
	<tbody id="data_area">
    @foreach($ledger_data as $lk=>$ledger)
		@php 
			$num++;
		@endphp
         @if(!($ledger->custo_gold==0 && $ledger->custo_silver==0 && $ledger->custo_amount==0) )
        <tr>
            <td class="text-center">
			<label for="sms_{{ $lk }}">
                {{ $ledger_data->firstItem() +  $lk }}
                <input type="checkbox" id="sms_{{ $lk }}" name="sms_check[]" value="{{ $ledger->id }}" class="sms_send_check">
            </label>
            </td>
            {{--<td class="text-center">{{ date("d-m-Y H:i:a",strtotime($ledger->updated_at)) }}</td>--}}
            <td class="text-center">{{ $ledger->custo_name }}/({{ @$ledger->custo_num }})</td>
            <td class="text-center">{{ $ledger->custo_mobile }}</td>
            @php 
                $gold_text_class = (isset($ledger->custo_gold_status))?(($ledger->custo_gold_status==0)?"danger":"success"):"info";
                $silver_text_class = (isset($ledger->custo_silver_status))?(($ledger->custo_silver_status==0)?"danger":"success"):"info";
                $amount_text_class = (isset($ledger->custo_amount_status))?(($ledger->custo_amount_status==0)?"danger":"success"):"info";

                @${"amount_{$stts_arr[$ledger->custo_amount_status]}"} += $ledger->custo_amount;
                @${"gold_{$stts_arr[$ledger->custo_gold_status]}"} += $ledger->custo_gold;
                @${"silver_{$stts_arr[$ledger->custo_silver_status]}"} += $ledger->custo_silver;
				
			@endphp
            <td class="text-center text-{{ $amount_text_class }}">{!! ($ledger->custo_amount)?@$status_arr[$ledger->custo_amount_status].$ledger->custo_amount."&#8377;":'-' !!}</td>
            <td class="text-center text-{{ $gold_text_class }}">{{ ($ledger->custo_gold)?@$status_arr[$ledger->custo_gold_status].$ledger->custo_gold."gm":'-'  }}</td>
            <td class="text-center text-{{ $silver_text_class }}">{{ ($ledger->custo_silver)?@$status_arr[$ledger->custo_silver_status].$ledger->custo_silver."gm":'-'  }}</td>
            <td class="text-center"><a href="{{ route('udhar.txns',$ledger->id) }}" class="btn btn-sm btn-outline-info" ><i class="fa fa-eye"></i> Open</a></td>
        </tr>
		@endif
    @endforeach
    @if($num==$ledger_data->count())
		</tbody>
		<tfoot>
			<tr><td colspan="7" class="p-0 bg-primary"></td></tr>
			<tr class="bg-white" >
				<td rowspan="3" style="background:transparent;" class="text-center">
					<a href="{{ route('udhar.send') }}" class="btn btn-sm btn-outline-info" id="sms_send_btn"><i class="fa fa-paper-plane"> Send</i></a>
				</td>
				<th colspan="2" class="text-center">Udhar Out(-)</th>
				<td class="text-center text-danger">-{{ $amount_minus??0 }}&#8377;</td>
				<td class="text-danger text-center">-{{ $gold_minus??0 }}gm</td>
				<td class="text-danger text-center">-{{ $silver_minus??0 }}gm</td>
				<td rowspan="3" style="background:transparent;">
					<a href="{{ route('udhar.ledger',["print"]) }}" class="btn btn-default form-control btn-secondary p-0" style="font-size:200%;" target="_blank"><i class="fa fa-print"></i></a>
				</td>
			</tr>
			<tr class="bg-white">
				<th colspan="2" class="text-center">Udhar In(+)</th>
				<td class="text-success text-center">+{{ $amount_plus??0 }}&#8377;</td>
				<td class="text-success text-center">+{{ $gold_plus??0 }}gm</td>
				<td class="text-success text-center">+{{ $silver_plus??0 }}gm</td>
			</tr>
			<tr class="bg-white"> 
				@php 

					$gold_value = (@$gold_plus-@$gold_minus)??0;
					$silver_value =(@$silver_plus-@$silver_minus)??0;
					$amount_value = (@$amount_plus-@$amount_minus)??0;
					$gold_sum_class =  ($gold_value>0)?'success':"danger";
					$silver_sum_class = ($silver_value>0)?'success':"danger";
					$amount_sum_class = ($amount_value>0)?'success':"danger";
				@endphp
				<th colspan="2" class="text-center">Total :</th>
				<td class="text-{{ $amount_sum_class }} text-center">{{ $amount_value }}&#8377;</td>
				<td class="text-{{ $gold_sum_class }} text-center">{{ $gold_value }}gm</td>
				<td class="text-{{ $silver_sum_class }} text-center">{{ $silver_value }}gm</td>
			</tr>
		</tfoot>
    @endif
@else 
<tbody>
	<tr>
	<td class="text-center text-danger" colspan="8">No Record !</td>
	</tr>
</tbody>
@endif