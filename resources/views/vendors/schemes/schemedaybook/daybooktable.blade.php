
@if($transactions->count()>0)
    @php 
        $type_arr = ["E"=>'EMI',"B"=>'BONUS','T'=>"TOKEN"];
        $act_arr = ["A"=>'PAY',"E"=>'EDIT',"U"=>'UPDATE',"D"=>'DELETE'];
       $balance  = $datalist['opening'];
		
		
		$currentPage = $transactions->currentPage();
		$sn = ($transactions->perPage()*($currentPage-1))+1;
    @endphp
	@if($currentPage==1)
	<tr class="table-info">
        <td>#</td>
        <td>{{ $datalist['date'] }}</td>
        <td class="text-center text-success"><b>{{ $datalist['opening'] }} Rs.</b></td>
        <td  class="text-center text-danger">0 Rs.</td>
        <td class="text-center text-secondary">{{ $datalist['opening'] }} Rs.</td>
        <td class="text-center text-info"><b>Opening</b></td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
    </tr>
    @else 
    <tr class="table-secondary">
        <td>#</td>
        <td>{{ @$datalist['date'] }}</td>
        <td class="text-center text-success"><b>{{ $balance }} Rs.</b></td>
        <td  class="text-center text-danger">0 Rs.</td>
        <td class="text-center text-secondary">{{ $balance }} Rs.</td>
        <td class="text-center text-info"><b>Carry</b></td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td></td>
    </tr>
    @endif
@foreach($transactions as $txnk=>$txns)
    @if($txns->affect !='N')
        {{ $balance+= $txns->amnt_in - $txns->amnt_out; }}
    @endif
    <tr class="{{ ($txns->affect=='N')?'table-warning':'' }}">
        <td>{{ $sn++ }}</td>
        <td>{{ date("H:i:s",strtotime($txns->entry_time)) }}</td>
        <td class="text-right text-success">{{ $txns->amnt_in }} Rs</td>
        <td class="text-right text-danger">{{ $txns->amnt_out }} Rs.</td>
        <td class="text-right text-secondary"><b>{{  $balance }}</b> Rs.</td>
        <td class="text-center">{{ $txns->holder }} </td>
        <td>{{ @$txns->scheme->scheme_head }}</td>
        <td>{{ @$txns->group->group_name }}</td>
        <td>{{ $txns->enroll->customer_name }}<hr class="m-1">{{ $txns->enroll->info->custo_fone }}</td>
        <td class="text-center text-info">
        {{ ($txns->action=="ENROLL")?$txns->action:$type_arr["{$txns->type}"]." ".$act_arr["{$txns->action}"] }} 
		@if($txns->affect=='N')
            <hr><small class="text-danger">Token Pay</small>
        @endif
        </td>
        <td class="text-center">
            <a href="{{ route("shopscheme.emipay",$txns->enroll->id)}}" class="btn btn-sm btn-outline-secondary"><li class="fa fa-eye"></li></a>
        </td>
    </tr>
    @endforeach
@else 
    <tr><td colspan="11" class="text-center text-danger">No Record Found !</td></tr>
@endif
