@if($stocks->count() > 0)
    @foreach($stocks as $key=>$row)
        <tr>
            <th>
                {{ $stocks->firstItem() + $key }} 
            </th>
            <td class="text-center">
                @if($row->bill_num!='NA')
                <a href="{{ route('purchases.show',$row->purchase_id) }}" class="bill_show">
                    {{ $row->bill_num }}
                </a>
                @else 
                <b class="text-primary">NA</b>
                @endif
				@if($metal!='stone')
                <hr class="m-1 p-0">
                <a href="{{ route('stocks.show',$row->id) }}" class="bill_show">View Item</a>
				@endif
            </td>
            <td>
                {{$row->product_name }}
				@if($metal!='stone')
                <hr class="m-1">
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
			@endif
            </td>
			@if($metal=='stone')
            <!---------STONE STOCK------------------------>
            <td class="text-center">
                {{ $row->category->name }}
            </td>
            <td class="text-center">
                {{ $row->caret }}
            </td>
            <td class="text-center">
                {{ $row->amount }} Rs
            </td>
            <!---------END STONE STOCK------------------------>
            @else
			<td>
                @if($row->categories->count()>0)
                    @foreach($row->categories as $cati=>$catv)
                       {!! ($cati==0)?'':'<hr class="m-1">' !!}
                        {{ $catv->name }}
                    @endforeach
                @else 
                    <b class="text-danger">NA</b>
                @endif
            </td>
            <td class="text-right">
                {{$row->rate}} Rs.
            </td>
            <td class="text-center">
                @php 
                    $qnt_unit = ($row->unit=='grms')?'grms':'pcs';
                @endphp
                <b>INITIAL : </b>{{$row->quantity}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>AVAILABLE : </b>{{$row->available}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>COUNTER : </b>{{$row->counter}} {{ $qnt_unit }}
            </td>
            @if($metal !='artificial')
            <td>
                @if($row->item_type!="artificial")
                @php 
                    $property = json_decode($row->property,true);
                @endphp
                <b>CARET : </b>{{ $row->caret }}K
                <hr class="m-1">
                <b>PURITY : </b>{{ $property['purity'] }}%
                <hr class="m-1">
                <b>FINE Wt : </b>{{ $property['fine_weight'] }}
                @else 
                    <b>----</b>
                @endif
            </td>
            @endif
            <td  class="text-right">{{ $row->amount }} Rs.</td>
			@endif
            <td class="text-center">
                @php 
                    $to_place = $row->available - $row->counterplaced->sum('stock_avail');
                @endphp
                @if($to_place>0)
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                <input type="hidden" name="to_place[]" value="{{ $to_place }}" disabled >
                <label class="placer_label" >
                    <input type="text" name="quantity[]" value="{{ $to_place }}" class="form-control text-center true" placeholder="{{ ($row->item_type!='artificial')?(($row->item_type!='stone')?'Grms':'Caret'):'Count' }}" disabled {{ (in_array($row->item_type,["genuine",'stone']))?'readonly':'' }}>

                    <input type="checkbox" id="stock_{{ $stocks->firstItem() + $key }}" name="stock[]" value="{{ $row->id }}" class="stock_check inline_check_input" >
                    @if(!in_array($row->item_type,['artificial','stone'])) 
                        <span class="unit">G</span>
                    @endif
                </label>
                @else 
                    <i class="text-warning">All Placed</i>
                @endif
            </td>
			<td class="text-center">
				@php 
                    $stock_avail = ($row->available==0)?$row->counterplaced->sum('stock_avail'):$row->available;
                @endphp
				@if($stock_avail>0 && !in_array($row->item_type,['loose','artificial']))
					<input type="checkbox" class="tag_check" name="tag[]" value="{{ $row->id }}">
				@else 
					<b><i>NA</i></b>
				@endif
            </td>
            <td class="text-center">
                <a href="{{ route('stocks.edit',$row->id) }}" class="btn btn-sm btn-outline-info editButton"><li class="fa fa-edit"></li></a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('stocks.destroy',$row->id) }}">
                    <li class="fa fa-times"></li>
                </button>          
            </td>
        </tr>

    @endforeach
@else 
    @php  $colspan = ($metal !='artificial')?11:9 @endphp
    <tr><td colspan="{{ $colspan }}" class="text-danger text-center">No Stock !</td></tr>
@endif


