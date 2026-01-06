
@if($all_schemes->count()>0)
    @foreach($all_schemes as $si=>$scheme)
    <tr class={{ ($scheme->status==0)?'table-danger':'' }}>
        <td class="text-center">{{ $all_schemes->firstItem() + $si }}</td>
        <td class="text-center">{{ ucfirst($scheme->type) }}</td>
        <td class="text-center">
            {{ $scheme->title }}
            @if($scheme->detail)
                <hr class="m-1">
                {{ $scheme->detail }}
            @endif
        </td>
        <td class="text-center">{{ date('d-M-Y',strtotime($scheme->start)) }}</td>
        <td class="text-center">
            @if($scheme->fix_emi==1)
                {{ $scheme->emi_quant }}{{ ($scheme->type=='gold')?'Grm':'Rs' }}
            @else 
                Not Fix
			@endif
        </td>
        <td class="text-center">{{ $scheme->validity }} Month</td>
        <td class="text-center">
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					&#8942;
				</button>
				<ul class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton" style="min-width:max-content;">
					<li class="w-auto p-1">
						<a class="btn btn-outline-info w-100" href="{{ route('anjuman.scheme.edit', $scheme->id)}}" data-redirect="false" data-mpin-check='true' >
							<i class="fa fa-edit"></i> Edit
						</a>
					</li>
					@if(($scheme->status==1))
					<li class="w-auto p-1">
						<a class="btn btn-outline-danger w-100" href="{{ route('anjuman.scheme.delete', $scheme->id)}}" data-redirect="false" data-mpin-check='true' >
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
