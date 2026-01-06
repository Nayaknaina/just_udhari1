@if($today_list->count()>0)
    @php 
    $stts_arr = ['-','+'];
    $var_arr = ['out','in'];
    $source_arr = ["S"=>"Sell","P"=>"Purchase","D"=>"Udhar","C"=>"Conversion"]; 
    $amount_old = $total_amnt_in = $total_amnt_out = $gold_old = $total_gld_in = $total_gld_out =  $silver_old = $total_slvr_in = $total_slvr_out  = $num = 0;
    @endphp
	<tbody id="data_area">
    @foreach($today_list as $tk=>$list)
    @php  
		$cnvrs = ($list->source=="C")?true:false;
        $amount_old = $gold_old  = $silver_old = 0;
        if($cnvrs){
            $cncrst_data = $list->conversion;
            $vrbl_arr = ["A"=>'amount',"S"=>"silver","G"=>"gold"];
            ${"{$vrbl_arr["{$cncrst_data->from}"]}_old"} = $cncrst_data->curr_from;
            ${"{$vrbl_arr["{$cncrst_data->to}"]}_old"} = $cncrst_data->curr_to;
        }else{
            $pre_day = $list->account->pretxn($list->updated_at);
            $amount_old = $pre_day->amount??0;
            $gold_old = $pre_day->gold??0;
            $silver_old = $pre_day->silver??0;
        }
        //$pre_day = $list->account->pretxn($list->created_at);
        //$amount_old = $pre_day->amount??0;
        //$gold_old = $pre_day->gold??0;
        //$silver_old = $pre_day->silver??0;
        $amount_in = $amount_out = $gold_in = $gold_out = $silver_in = $silver_out = 0;
        @${"amount_{$var_arr[$list->amount_udhar_status]}"} += $list->amount_udhar;
        @${"gold_{$var_arr[$list->gold_udhar_status]}"} += $list->gold_udhar;
        @${"silver_{$var_arr[$list->silver_udhar_status]}"} += $list->silver_udhar;
        $amount_final = $amount_old + ($amount_in-$amount_out);
        $gold_final = $gold_old + ($gold_in-$gold_out);
        $silver_final = $silver_old + ($silver_in-$silver_out);
    @endphp 
    <tr>
        <td>{{ $today_list->firstItem() +  $tk }}</td>
        <td>{{ date("Y-m-d h:i:a",strtotime($list->created_at)) }}</td>
        <td >
            {{ @$list->account->custo_num }}
            <hr class="m-1 p-0">
            {{ @$list->account->custo_name }}
            <hr class="m-1 p-0">
            {{ @$list->account->custo_mobile }}
            <hr class="m-1 p-0">
            <b>{{ $source_arr[$list->source] }}</b>
        </td>
        <td class="text-info">{!! $amount_old."&#8377;" !!}</td>
        <td class="text-success">{!! ($amount_in)?"+".$amount_in."&#8377;":'-' !!}</td>
        <td class="text-danger">{!! ($amount_out)?"-".$amount_out."&#8377;":'-' !!}</td>
        <td style="color:blue;">{!! ($amount_final)?$amount_final."&#8377;":'-' !!}</td>
        <td class="text-info">{{ $gold_old."gm" }}</td>
        <td class="text-success">{{ ($gold_in)?"+".$gold_in."gm":'-' }}</td>
        <td class="text-danger">{{ ($gold_out)?"-".$gold_out."gm":'-' }}</td>
        <td style="color:blue;">{{ $gold_final."gm" }}</td>
        <td class="text-info">{{ $silver_old."gm" }}</td>
        <td class="text-success">{{ ($silver_in)?"+".$silver_out."gm":'-' }}</td>
        <td class="text-danger">{{ ($silver_out)?"-".$silver_out."gm":'-' }}</td>
        <td style="color:blue;">{{ $silver_final."gm" }}</td>
        <td>{!! $list->remark??'-' !!}</td>
        <td class="text-center">
			<div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                &#8643;&#9783;
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width:unset;">
					@if(!$cnvrs)
                    <a class="dropdown-item text-info editButton " href="{{ route('udhar.edit',$list->id)}}" ><i class="fa fa-edit"></i> Edit</a>
					@endif
                    <a class="dropdown-item text-success" href="{{ route('udhar.show',$list->id) }}" target="_blank"><i class="fa fa-print"> </i> Print</a>
                    <a href="javascript:void(null);" class="dropdown-item text-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('udhar.list.remove',$list->id) }}"><i class="fa fa-times"></i> Delete</a>
                </div>
            </div>
		</td>
    </tr>
    @php 
        $total_amnt_in +=  $amount_in;
        $total_amnt_out += $amount_out;
        $total_gld_in += $gold_in;
        $total_gld_out += $gold_out;
        $total_slvr_in += $silver_in;
        $total_slvr_out += $silver_out;
        $num++;
    @endphp
    @if($num==$today_list->count())
	</tbody>
	<tfoot>
        <tr class="foot">
            <th colspan="2" class="">TOTAL SUMMERY</th>
            <th colspan="2" class="text-right">AMOUNT</th>
            <td class="border-success text-success bg-white">
                {!! "+".$total_amnt_in."&#8377;" !!}
            </td>
            <td class="border-danger text-danger bg-white">
                {!! "-".$total_amnt_out."&#8377;" !!}
            </td>
            <th colspan="2" class="text-right">GOLD</th>
            <td class="border-success text-success bg-white">
                {{ "+".$total_gld_in."gm" }}
            </td>
            <td class="border-danger text-danger bg-white">
                {{ "-".$total_gld_out."gm" }}
            </td>
            <th colspan="2" class="text-right">SILVER</th>
            <td class="border-success text-success bg-white">
                {{ "+".$total_slvr_in."gm" }}
            </td>
            <td class="border-danger text-danger bg-white">
                {{ "-".$total_slvr_out."gm" }}
            </td>
            <td colspan="3"></td>
        </tr>
	</tfoot>
    @endif
    @endforeach
@else
<tbody>
	<tr>
		<td class="text-center text-danger" colspan="17">No Record !</td>
	</tr>
</tbody>
@endif