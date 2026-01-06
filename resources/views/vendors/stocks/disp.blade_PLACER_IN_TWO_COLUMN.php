@if($stocks->count() > 0)
    @foreach($stocks as $key=>$row)
        <tr>
            <th>
                {{ $stocks->firstItem() + $key }}
            </th>
            <td>
                @if($row->bill_num!='NA')
                <a href="{{ route('purchases.show',$row->purchase_id) }}" class="bill_show">
                    {{ $row->bill_num }}
                </a>
                @else 
                <b class="text-primary text-center">NA</b>
                @endif
            </td>
            <td>
                {{$row->product_name }}
                <hr class="m-1">
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
            </td>
            @if($metal !='artificial')
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
            @endif
            <td class="text-right">
                {{$row->rate}} Rs.
            </td>
            <td class="text-center">
                @php 
                    $qnt_unit = ($row->unit=='grms')?'grms':'';
                @endphp
                <b>INITIAL : </b>{{$row->quantity}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>AVAILABLE : </b>{{$row->available}} {{ $qnt_unit }}
                <hr class="m-1">
                <b>COUNTER : </b>{{$row->counter}} {{ $qnt_unit }}
            </td>
            @if($metal !='artificial')
            <td>
                @php 
                    $property = json_decode($row->property,true);
                    //print_r($property);
                @endphp
                @if($row->item_type!="artificial")
                <b>PURITY : </b>{{ $property['purity'] }}
                <hr class="m-1">
                <b>FINE : </b>{{ $property['fine_purity'] }}
                @else 
                    <b>----</b>
                @endif
            </td>
            @endif
            <td  class="text-right">{{ $row->amount }} Rs.</td>
            <td>
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                <input type="hidden" name="to_place[]" value="{{ $to_place }}" disabled>
                <label class="placer_label">
                    <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <input type="checkbox" id="stock_{{ $stocks->firstItem() + $key }}" name="stock[]" value="{{ $row->id }}" class="stock_check inline_check_input" >
                </label>
                <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <span class="input-group-append ">
                    <input type="checkbox" id="stock_{{ $stocks->firstItem() + $key }}" name="stock[]" value="{{ $row->id }}" class="stock_check form-control">
                </span>
                </div>
            </td>
            <td class="{{ ($row->item_type!='artificial')?'grms':'Count' }} text-center">
                @php 
                    $to_place = $row->available - $row->counterplaced->sum('stock_avail');
                @endphp
                @if($to_place>0)
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                <input type="hidden" name="to_place[]" value="{{ $to_place }}" disabled>
                <input type="text" name="quantity[]" value="{{ $to_place }}" class="form-control text-center true" placeholder="{{ ($row->item_type!='artificial')?'Grms':'Count' }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                @else 
                    {{ $to_place }}
                @endif
            </td>
            <td> 
                <label for="stock_{{ $stocks->firstItem() + $key }}" class="form-control text-success" style="width:fit-content;">
                        @if($to_place > 0 )
                        <input type="checkbox" id="stock_{{ $stocks->firstItem() + $key }}" name="stock[]" value="{{ $row->id }}" class="stock_check">
                        @else 
                            &check; 
                        @endif
                </label>
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


