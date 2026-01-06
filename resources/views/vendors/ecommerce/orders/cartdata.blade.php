@if($cart->count()>0)
	@foreach($cart as $ck=>$cv)
		<tr>
			<td class="text-center">{{ $cv->first_item+1 }}</td>
			<td class="text-center">
				{{ $cv->owncustomer->custo_full_name }}
				<hr class="m-1 p-0">
				{{ $cv->owncustomer->custo_fone }}
			</td>
			<td class="text-center">
				<img src="{{ url("ecom/products/{$cv->product->thumbnail_image}") }}" class="img-responsive img-thumbnail" style="width:250px;height:auto;">
			</td>
			<td class="text-center">
				{{ $cv->product->name }}
			</td>
			<td class="text-center">{{ $cv->quantity }}</td>
			<td class="text-right">{{ $cv->curr_cost }} Rs.</td>
			<td class="text-center">{{ date("d-M-Y H:i:a",strtotime($cv->created_at)) }}</td>
			<td></td>
		</tr>
	@endforeach 
@else 
	<tr><td colspan="8" class="text-danger text-center">No Items !</td></tr>
@endif