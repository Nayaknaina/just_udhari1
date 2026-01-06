@if($txns->count() > 0)
    @php 
        $op = ['GG'=>"Girvi Grant",'GI'=>"Girvi Interest"];
        $sign = ['-','+'];        
        $hold = ['B'=>'Bank','S'=>'Shop'];
        $mode = ['on'=>'Online','off'=>'Cash'];
    @endphp
    @foreach($txns as $tk=>$txn)
        <tr>
            <td>{{ $txns->firstItem() + $tk  }}</td>
            <td>{{date('d-m-Y',strtotime($txn->created_at))  }}</td>
            <td class="{{ ($sign[$txn->txn_status]=='-')?'text-danger':'text-success' }}">{{ $sign[$txn->txn_status].$txn->pay_principal }} Rs.</td>
            <td class="{{ ($sign[$txn->txn_status]=='-')?'text-danger':'text-success' }}">{{ $sign[$txn->txn_status].$txn->pay_interest }} Rs.</td>
            <td>{{ $mode[$txn->pay_mode] }}</td>
            <td>
                <b class="text-info">{{ $op[$txn->operation] }}</b>
                <hr class="m-0 p-1">
                {{ $txn->remark  }}
            </td>
            <td>{{ $hold[$txn->amnt_holder] }}</td>
            <td>{{ date('d-m-Y',strtotime($txn->pay_date)) }}</td>
        </tr>
    @endforeach
@else 
<!--<tr><td colspan="8" class="text-center text-danger ">No Transactions !</td></tr>-->
@endif