
@if($datalist->count()>0)
   
@foreach($datalist as $txnk=>$txns)
    <tr>
        <td class="text-center">{{ $sn++ }}</td>
        <td class="text-center">{{ $txns['task_date'] }}</td>
        <td class="text-right">{{ $txns['open'] }} Rs.</td>
        <td class="text-right">{{ $txns['close'] }} Rs.</td>
        <td class="text-center">
            <a href="{{ route('shopscheme.daybook',['date'=>$txns['task_date'],'open'=>$txns['open']]) }}" class="btn btn-sm btn-outline-secondary"><li class="fa fa-eye"></li></a>
        </td>
    </tr>
    @endforeach
@else 
    <tr><td colspan="5" class="text-center text-danger">No Record Found !</td></tr>
@endif
