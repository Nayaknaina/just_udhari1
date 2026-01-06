
@if($transactions->count()>0)
    @php 
        $type_arr = ["E"=>'EMI',"B"=>'BONUS','T'=>"TOKEN"];
        $act_arr = ["A"=>'PAY',"E"=>'EDIT',"U"=>'UPDATE',"D"=>'DELETE'];
        $balance = 0;
        $num = 1;
    @endphp
    <tr class="table-info">
        <td>#</td>
        <td>{{ $datalist['date'] }}</td>
        <td class="text-center text-success"><b>{{ $datalist['opening'] }} Rs.</b></td>
        <td  class="text-center text-danger">0 Rs.</td>
        <td class="text-center text-secondary">{{ $datalist['opening'] }} Rs.</td>
        <td class="text-center">--</td>
        <td class="text-center">--</td>
        <td class="text-center text-info"><b>Opening</b></td>
        <td></td>
    </tr>
@foreach($transactions as $txnk=>$txns)
    @if($txns->affect !='N')
        {{ $balance+= $txns->amnt_in - $txns->amnt_out; }}
    @endif
    <tr>
        <td>{{ $num++ }}</td>
        <td>{{ date("H:i:s",strtotime($txns->entry_time)) }}</td>
        <td class="text-right text-success">{{ $txns->amnt_in }} Rs</td>
        <td class="text-right text-danger">{{ $txns->amnt_out }} Rs.</td>
        <td class="text-right text-secondary"><b>{{  $balance }}</b> Rs.</td>
        <td class="text-center">{{ $txns->holder }} </td>
        <td class="text-center">{{ $txns->affect }} </td>
        <td class="text-center text-info">
        {{ ($txns->action=="ENROLL")?$txns->action:$type_arr["{$txns->type}"]." ".$act_arr["{$txns->action}"] }} 
        </td>
        <td class="text-center">
            <a href="#" class="btn btn-sm btn-outline-secondary"><li class="fa fa-eye"></li></a>
        </td>
    </tr>
    @endforeach
@else 
    <tr><td colspan="9" class="text-center text-danger">No Record Found !</td></tr>
@endif
