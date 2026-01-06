@if($counters->count()>0)
    @foreach($counters as $key=>$row)
        <tr>
            <td>{{ $counters->firstItem() + $key }}</td>
            <td>
                {{$row->name }}
            </td>
            <td class="text-center">
                {{$row->box_name }}
            </td>
            <td class="text-center">
                {{ $row->stock_name }}
                    <hr class="m-1">
                <b><i>
				@if(isset($row->stock->item_type))
				{{ ucfirst(@$row->stock->item_type) }} 
				@else 
					------
				@endif
				</i></b>
            </td>
			<td>
            @if(isset($row->stock->categories) && $row->stock->categories->count()>0)
                @foreach($row->stock->categories as $cati=>$catv)
                    {!! ($cati==0)?'':'<hr class="m-1">' !!}
                    {{ $catv->name }}
                @endforeach
            @else 
                <b class="text-danger">NA</b>
            @endif
            </td>
            <td class="text-center">
                <b> INIT- </b>{{$row->stock_quantity}}
                {{ ($row->stock_type!='artificial')?'grms':'' }}
                <hr class="m-1">
                <b> AVAIL- </b>{{$row->stock_avail}}
                {{ ($row->stock_type!='artificial')?'grms':'' }}
            </td>
            <td class="text-center">
			@if(isset($row->stock->purchase_id) && $row->stock->purchase_id!=0)
            <a href="{{ route('purchases.show',@$row->stock->purchase_id) }}" class="pop_out bill_show" data-header="Bill Detail">{{ @$row->stock->purchase->bill_no }}</a>
			@else
				<b class="text-warning"><i>NA</i></b>
			@endif
            </td>
            <td class="text-center">
                <a href="{{ route("stockcounters.edit",[$row->id,"to"=>"move"]) }}" class="btn btn-outline-success pop_out counter" data-header="Counter Move"><li class="fa fa-exchange"></li></a>
                <a href="{{ route("stockcounters.edit",[$row->id,"to"=>"pop"]) }}" class="btn btn-outline-warning pop_out counter" data-header="Counter Out" ><li class="fa fa-share-square"></li></a>
            </td>
            <td>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url='{{ route("stockcounters.destroy",$row->id) }}' >
                &cross;
                </button>
            </td>
        </tr>

    @endforeach
      
  
@else 
    <tr><td colspan="9" class="text-center text-danger">No Placement Yet !</td></tr>
@endif


