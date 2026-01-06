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
        $pre_day = $list->account->pretxn($list->created_at);
        $pre_day = $list->account->userpretxn($list->created_at,$list->id);
        
        $amount_old = $pre_day->amount;
        $gold_old = $pre_day->gold;
        $silver_old = $pre_day->silver;

        //$amount_old = ($pre_day->amount)?$pre_day->amount:$list->amount_curr;
        //$gold_old = ($pre_day->gold)?$pre_day->gold:$list->gold_curr;
        //$silver_old = ($pre_day->silver)?$pre_day->silver:$list->silver_curr;

        $amount_in = $amount_out = $gold_in = $gold_out = $silver_in = $silver_out = 0;
        @${"amount_{$var_arr[$list->amount_udhar_status]}"} = $list->amount_udhar;
        @${"gold_{$var_arr[$list->gold_udhar_status]}"} = $list->gold_udhar;
        @${"silver_{$var_arr[$list->silver_udhar_status]}"} = $list->silver_udhar;
        $amount_final = $amount_old + ($amount_in-$amount_out);
        $gold_final = $gold_old + ($gold_in-$gold_out);
        $silver_final = $silver_old + ($silver_in-$silver_out);
    @endphp 
    <tr class="table-{{ (in_array($list->action,['E','D']))?(($list->action=='E')?'info':'danger'):"" }}">
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
        <td class="text-info">{{ sprintf('%+g',($list->action=="E")?$list->amount_curr:$amount_old) }} &#8377;</td>
        <td class="text-success">{!! ($amount_in)?"+".$amount_in."&#8377;":'-' !!}</td>
        <td class="text-danger">{!! ($amount_out)?"-".$amount_out."&#8377;":'-' !!}</td>
        <td style="color:blue;">{{ sprintf('%+g',$amount_final) }} &#8377;</td>
        <td class="text-info">{{ sprintf('%+g',$gold_old) }} gm</td>
        <td class="text-success">{{ ($gold_in)?"+".$gold_in."gm":'-' }}</td>
        <td class="text-danger">{{ ($gold_out)?"-".$gold_out."gm":'-' }}</td>
        <td style="color:blue;">{{ sprintf('%+g',$gold_final) }} gm</td>
        <td class="text-info">{{ sprintf('%+g',$silver_old) }} gm</td>
        <td class="text-success">{{ ($silver_in)?"+".$silver_out."gm":'-' }}</td>
        <td class="text-danger">{{ ($silver_out)?"-".$silver_out."gm":'-' }}</td>
        <td style="color:blue;">{{ sprintf('%+g',$silver_final) }} gm</td>
        <td>{!! $list->remark??'-' !!}</td>
        <td>
			@if(in_array($list->source,['D','C']))
				@if($list->action=="E")
					<a href="javascript:void(null);" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('udhar.list.remove',$list->id) }}"><i class="fa fa-times">&nbsp;Delete</i></a>
				@elseif($list->action!="D")
				<div class="dropdown">
					<button class="btn btn-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					&#8643;&#9783;
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width:unset;">
						@if($list->source!='C')
						<a class="dropdown-item text-info editButton " href="{{ route('udhar.edit',$list->id)}}" ><i class="fa fa-edit"></i> Edit</a>
						@endif
						<a class="dropdown-item text-success" href="{{ route('udhar.show',$list->id) }}" target="_blank"><i class="fa fa-print"> </i> Print</a>
						<a href="javascript:void(null);" class="dropdown-item text-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('udhar.list.remove',$list->id) }}"><i class="fa fa-times"></i> Delete</a>
					</div>
				</div>
				@else 
				<span class="text-danger" style="font-size:200%;">&#8856; </span>
				@endif
			@endif
        </td>
    </tr>
    @php 
        $do_cal = (!in_array($list->action,["E","D"]))?true:false;
        $total_amnt_in +=  ($do_cal)?$amount_in:0;
        $total_amnt_out += ($do_cal)?$amount_out:0;
        $total_gld_in += ($do_cal)?$gold_in:0;
        $total_gld_out += ($do_cal)?$gold_out:0;
        $total_slvr_in += ($do_cal)?$silver_in:0;
        $total_slvr_out += ($do_cal)?$silver_out:0;
        $num++;
    @endphp
    @if($num==$today_list->count())
	</tbody>
	<tfoot>
        <tr class="bg-dark foot">
            <td colspan="2" class="text-white">TOTAL SUMMERY</td>
            <td colspan="2" class="text-right text-white">AMOUNT</td>
            <td class="border-success text-success bg-white">
                {!! "+".$total_amnt_in."&#8377;" !!}
            </td>
            <td class="border-danger text-danger bg-white">
                {!! "-".$total_amnt_out."&#8377;" !!}
            </td>
            <td colspan="2" class="text-right text-white">GOLD</td>
            <td class="border-success text-success bg-white">
                {{ "+".$total_gld_in."gm" }}
            </td>
            <td class="border-danger text-danger bg-white">
                {{ "-".$total_gld_out."gm" }}
            </td>
            <td colspan="2" class="text-right text-white">SILVER</td>
            <td class="border-success text-success bg-white">
                {{ "+".$total_slvr_in."gm" }}
            </td>
            <td class="border-danger text-danger bg-white">
                {{ "-".$total_slvr_out."gm" }}
            </td>
            <td colspan="3">
                
            </td>
        </tr>
	</tfoot>
    @endif
    @endforeach
@else
<tbody id="data_area">
	<tr>
		<td class="text-center text-danger" colspan="17">No Record !</td>
	</tr>
</tbody>
@endif
