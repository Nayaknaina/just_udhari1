
@if($datalist->count()>0)
    
@foreach($datalist as $txnk=>$txns)
    <tr>
        <td class="text-center">{{ $txnk+1 }}</td>
        <td class="text-center">{{ $txns['date'] }}</td>
        <td class="text-right">{{ $txns['opening'] }} Rs.</td>
        <td class="text-right">{{ $txns['closing'] }} Rs.</td>
        <td class="text-center">
            <a href="{{ route('shopscheme.daybook',['date'=>$txns['date'],'open'=>$txns['opening']]) }}" class="btn btn-sm btn-outline-secondary"><li class="fa fa-eye"></li></a>
        </td>
    </tr>
    @endforeach
@else 
    <tr><td colspan="5" class="text-center text-danger">No Record Found !</td></tr>
@endif
