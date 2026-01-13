@if($ledger->count()>0)
<tbody>
    @foreach($ledger as $key=>$led)
        <tr>
            <td>{{ $ledger->firstItem() +  $key }}</td>
            <td>
                {{ $led->custo_name }}
            </td>
            <td>
                {{ $led->custo_mobile }}
            </td>
            <td>
                <ul>
                    <li><b>BATCH : </b><span>{{ $led->batchs_count }}</span></li>
                    <li><hr class="m-0"></li>
                    <li><b>ITEMS : </b><span>{{ $led->items_count }}</span></li>
                </ul>
            </td>
            <td class="text-info">
                {{ $led->batchs_sum_principle }} Rs.
            </td>
            <td class="text-info">
                {{ $led->batchs_sum_interest }} Rs.
            </td>
            <td class="text-success">
                {{ $led->txns_sum_pay_principal }} Rs.
            </td>
            <td class="text-success">
                {{ $led->txns_sum_pay_interest }} Rs.
            </td>
            <td class="text-{{ ($led->balance_principal==0)?'warning':(($led->balance_principal<0)?'danger':'success') }}">
                {{ $led->balance_principal }} Rs.
            </td>
            <td class="text-{{ ($led->balance_interest==0)?'warning':(($led->balance_interest<0)?'danger':'success') }}">
                {{ $led->balance_interest }} Rs.
            </td>
            <td>
                <a href="{{ route('girvi.custotxns',$led->id) }}" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-eye"></i>
                </a>
            </td>
        </tr>
    @endforeach
</tbody>
<tfoot>
    
</tfoot>
@else 
<tr><td colspan="11" class="text-center text-danger">No Girvi Items !</td></tr>
@endif