
@if($enrollments->count()>0)
    @foreach($enrollments as $ei=>$enroll)
    <tr class={{ ($enroll->status==0)?'table-danger':'' }}>
        <td class="text-center">{{ $enrollments->firstItem() + $ei }}</td>
        <td class="text-center">
            {{ ucfirst($enroll->custo_name) }}
            <hr class="m-1">
            {{ $enroll->customer->custo_mobile }}</td>
        <td class="text-center">
            {{ $enroll->scheme->title }}
            @if($enroll->scheme->detail)
                <hr class="m-1">
                {{ $enroll->scheme->detail }}
            @endif
        </td>
        <td class="text-center">{{ date('d-M-Y',strtotime($enroll->enroll_date)) }}</td>
		<td class="text-center text-info">{{ $enroll->remark }}</td>
        <td class="text-center">
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					&#8942;
				</button>
				<ul class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton" style="min-width:max-content;">
					<li class="w-auto p-1">
						<a href="{{ route('anjuman.schemeenroll.edit', $enroll->id)}}" class="w-100 btn btn-sm btn-outline-info" data-redirect="false" data-mpin-check='true'><i class="fa fa-edit"></i> Edit</a>
					</li>
					@if(($enroll->status==1))
					<li class="w-auto p-1">
						<a class="w-100 btn btn-sm btn-outline-danger" href="{{ route('anjuman.schemeenroll.delete', $enroll->id)}}" data-redirect="false" data-mpin-check='true' >
							<i class="fa fa-trash"></i> Delete
						</a>
					</li>
					@endif
				</ul>
			</div>
        </td>
    </tr>
    @endforeach
@else 
<tr><td colspan="7" class="text-center text-danger">No Record !</td></tr>
@endif
