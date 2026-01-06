@if($txns_data->count()>0)
    @php 
        $src_arr = ["S"=>'Sell Bill','P'=>'Purchase Bill','D'=>'Loan','C'=>"Conversion"];
        $gold_out_sum= $gold_in_sum= $silver_out_sum= $silver_in_sum= $amount_out_sum= $amount_in_sum =0;
        $status = ["out","in"];
        $num = 0;
    @endphp
	<tbody id="data_area">
    @foreach($txns_data as $tk=>$txn)
        @php  
            @${"gold_{$status[$txn->gold_udhar_status]}"}  = $txn->gold_udhar;
            @${"silver_{$status[$txn->silver_udhar_status]}"}  = $txn->silver_udhar;
            @${"amount_{$status[$txn->amount_udhar_status]}"}  = $txn->amount_udhar;
            $num++;
        @endphp
    <tr class="{{ ($txn->source=='C')?'bg-danger':'' }}">
        <td class="text-center">
            {{-- $txns_data->firstItem() +  $tk --}}
            {{ $tk+1 }}
        </td>
        <td class="text-center">
            {{ date('Y-m-d H:i:s',strtotime($txn->updated_at)) }}
        </td>
        <td class="text-center">
            {{ $src_arr[$txn->source] }}
			@if($txn->custom_remark!="")
				<hr class="m-1">
				<small class='text-info'>
				<i>{{ $txn->custom_remark }}</i>
				</small>
			@endif
        </td>
        @php 
            $brace_open = ($txn->source=='C')?"(":"";
            $brace_close = ($txn->source=='C')?")":"";
        @endphp
        <td class="text-center">
            {!! (@$amount_out)?"$brace_open-".$amount_out."&#8377;$brace_close":'-'!!}
			@if($txn->amount_udhar_status==0  && $txn->amount_udhar_holder=="B")
                <hr class="m-0 p-0">
                <small style="color:blue;">( ONLINE)</small>
            @endif
        </td>
        <td class="text-center">
            {!! (@$amount_in)?"$brace_open+".$amount_in."&#8377;$brace_close":'-' !!}
			@if($txn->amount_udhar_status==1  && $txn->amount_udhar_holder=="B")
                <hr class="m-0 p-0">
                <small style="color:blue;">( ONLINE)</small>
            @endif
        </td>
        <td class="text-center">
            {{ (@$gold_out)?"$brace_open-".$gold_out."gm$brace_close":'-' }}
        </td>
        <td class="text-center">
            {{ (@$gold_in)?"$brace_open+".$gold_in."gm$brace_close":'-' }}
        </td>
        <td class="text-center">
            {{ (@$silver_out)?"$brace_open-".$silver_out."gm$brace_close":'-' }}
        </td>
        <td class="text-center">
            {{ (@$silver_in)?"$brace_open+".$silver_in."gm$brace_close":'-' }}
        </td>
        <td >
		
			@php 
				$gold_out_sum += @$gold_out??0;
				$gold_in_sum += @$gold_in??0;
				$silver_out_sum += @$silver_out??0;
				$silver_in_sum += @$silver_in??0;
				$amount_out_sum += @$amount_out??0;
				$amount_in_sum += @$amount_in??0;
			@endphp
            @php 
                /*$gold_class = (in_array(@$status[$txn->gold_udhar_status],$status))?(($status[$txn->gold_udhar_status]=='in')?'success':'danger'):'info';
                $silver_class = (in_array(@$status[$txn->silver_udhar_status],$status))?(($status[$txn->silver_udhar_status]=='in')?'success':'danger'):'info';
                $amount_class = (in_array(@$status[$txn->amount_udhar_status],$status))?(($status[$txn->amount_udhar_status]=='in')?'success':'danger'):'info';*/
            @endphp
            {{--<ul  class="txn_summery_ul text-secondary" >
                <li><b>A :</b> <span class="text-{{ $amount_class }}">{{ ($amount_class!="info")?(($amount_class=='danger')?'-':'+'):'' }}{{ $txn->amount_udhar??0 }}&#8377;</span></li>
                <li ><b>G :</b> <span class="text-{{ $gold_class }}">{{ ($gold_class!="info")?(($gold_class=='danger')?'-':'+'):'' }}{{ $txn->gold_udhar??0 }}gm</span></li>
                <li><b>S :</b> <span class="text-{{ $silver_class }}">{{ ($silver_class!="info")?(($silver_class=='danger')?'-':'+'):'' }}{{ $txn->silver_udhar??0 }}gm</span></li>
            </ul>--}}
			@php 
				$gold_txn_sum = ($gold_in_sum - $gold_out_sum)??0;
				$gold_txn_sum = (($gold_txn_sum!=0)?(($gold_txn_sum>0)?'+':''):'').$gold_txn_sum;
				$silver_txn_sum = ($silver_in_sum - $silver_out_sum)??0;
				$silver_txn_sum = (($silver_txn_sum!=0)?(($silver_txn_sum>0)?'+':''):'').$silver_txn_sum;
				$amount_txn_sum = ($amount_in_sum - $amount_out_sum)??0;
				$amount_txn_sum = (($amount_txn_sum!=0)?(($amount_txn_sum>0)?'+':''):'').$amount_txn_sum;
			@endphp 
			<ul  class="txn_summery_ul text-secondary" >
                <li ><b>G :</b> <span class="text-{{ ($gold_txn_sum!=0)?(($gold_txn_sum>0)?'success':'danger'):'info' }}">{{ $gold_txn_sum }}gm</span></li>
                <li><b>S :</b> <span class="text-{{ ($silver_txn_sum!=0)?(($silver_txn_sum>0)?'success':'danger'):'info' }}">{{ $silver_txn_sum }}gm</span></li>
                <li><b>A :</b> <span class="text-{{ ($amount_txn_sum!=0)?(($amount_txn_sum>0)?'success':'danger'):'info' }}">{{ $amount_txn_sum }}&#8377;</span></li>
            </ul>
        </td>
        <td class="text-center">
            {!! $txn->remark??'-' !!}
        </td>
    </tr>
    @if($num==$txns_data->count())
	</tbody>
	<tfoot>
        <tr class="">
            <td colspan="3"></td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {!! (@$amount_out_sum)?'-'.$amount_out_sum.' &#8377;':'-' !!}
                </span>
            </td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {!! (@$amount_in_sum)?'+'.$amount_in_sum.' &#8377;':'-' !!}
                </span>
            </td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {{ (@$gold_out_sum)?'-'.$gold_out_sum.' gm':'-' }} 
                </span>
            </td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {{ (@$gold_in_sum)?'+'.$gold_in_sum.' gm':'-' }}
                </span>
            </td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {{ (@$silver_out_sum)?'-'.$silver_out_sum.' gm':'-' }}
                </span>
            </td>
            <td class="text-center p-1">
                <span class="form-control p-1">
                    {{ (@$silver_in_sum)?'+'.$silver_in_sum.' gm':'-' }}
                </span>
            </td>
            <td colspan="2"></td>
        </tr>
        @php 
            $all_gold = ((@$gold_in_sum??0)-@$gold_out_sum)??false;
            $all_silver = ((@$silver_in_sum??0)-@$silver_out_sum)??false;
            $all_amount = ((@$amount_in_sum??0)-@$amount_out_sum)??false;
        @endphp
        <tr class="">
            <th colspan="3" class="text-center">TOTAL</th>
            @php 
                $gold_final_sum_class = ($all_gold)?(($all_gold<0)?'danger':'success'):'info';
                $silver_final_sum_class = ($all_silver)?(($all_silver<0)?'danger':'success'):'info';
                $amount_final_sum_class = ($all_amount)?(($all_amount<0)?'danger':'success'):'info'
            @endphp
            <th colspan="2" class="text-center p-1">
                <span class="form-control p-1 text-{{ $amount_final_sum_class }} border-{{ $amount_final_sum_class }}">
                    {!! ($all_amount)?(($all_amount>0)?"+$all_amount":"$all_amount").'&#8377;':'-' !!}
                </span>
            </th>
            <th colspan="2" class="text-center p-1">
                <span class="form-control p-1 text-{{ $gold_final_sum_class }} border-{{ $gold_final_sum_class }}"> 
                    {{ ($all_gold)?(($all_gold>0)?"+$all_gold":"$all_gold").'gm':'-' }}
                </span>
            </th>
            <th colspan="2" class="text-center p-1">
                <span class="form-control p-1 text-{{ $silver_final_sum_class }} border-{{ $silver_final_sum_class }}"> 
                    {{ ($all_silver)?(($all_silver>0)?"+$all_silver":"$all_silver").'gm':'-' }}
                </span>
            </th>
            <td colspan="2"></td>
        </tr>
	</tfoot>
    @endif
    @php 
        @${"gold_{$status[$txn->gold_udhar_status]}"}  = false;
        @${"silver_{$status[$txn->silver_udhar_status]}"}  =false;
        @${"amount_{$status[$txn->amount_udhar_status]}"}  = false;
    @endphp
    @endforeach
@else 
<tbody>
	<tr>
	<td class="text-center text-danger" colspan="11">No Record !</td>
	</tr>
</tbody>
@endif