
@if($group_txns->count()>0)
	@php 
        $currentPage = $group_txns->currentPage();
        $sn = ($group_txns->perPage()*($currentPage-1))+1;
    @endphp
    @foreach($group_txns as $gk=>$group)
        <tr>
            <td>{{ $sn++ }}</td>
            <td>
                {{ $group->group_name }}
            </td>
            <td class="text-center">
                <b style="font-size:1rem;">{{ $group->total_enroll_count }}</b>/{{ $group->group_limit }}
            </td>
            @php 
                $total = $group->total_emi_choosed*$scheme->scheme_validity;
                $paid = $group->total_paid_amount;
                $remains = $total-$paid;
            @endphp
            <td class="text-center text-info">
                {{ $total??'0' }} Rs.
            </td>
            <td class="text-center text-success">
            {{ $paid??'0' }} Rs.
            </td>
            <td class="text-center text-danger">
            {{ $remains??'0' }} Rs.
            </td>
            <td class="text-center">
            <a href="{{ route("shopscheme.enrollgroup",[$group['id'],'data'=>'all']) }}" class="btn btn-outline-info">
                <li class="fa fa-eye"></li>
            </a>
            </td>
        </tr>
    @endforeach
@else 
    <tr><td colspan="8" class="text-center text-danger">No Record Found !</td></tr>
@endif
