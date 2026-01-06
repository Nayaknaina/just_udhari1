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
                <hr class="m-1 p-0">
                <a href="{{ route('stocks.show',$row->id) }}" class="bill_show">View Item</a>
            </td>
            <td>
                {{$row->product_name }}
                <hr class="m-1">
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
            </td>
            <td class="text-center">
                @if($row->categories->count()>0)
                    @foreach($row->categories as $cati=>$catv)
                       {!! ($cati==0)?'':'<hr class="m-1">' !!}
                        {{ $catv->name }}
                    @endforeach
                @else 
                    <b class="text-danger">NA</b>
                @endif
            </td>
            <td class="text-center">
            @if($row->item_type=="artificial") <b>SELL </b><hr class="m-1 p-0">@endif{{$row->rate}} Rs.
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
            <td>
                @php 
                    $property = json_decode($row->property,true);
                @endphp
                @if($row->item_type!="artificial")
                <b>PURITY : </b>{{ $property['purity'] }}
                <hr class="m-1">
                <b>FINE : </b>{{ $property['fine_purity'] }}
                @else 
                    <b>----</b>
                @endif
            </td>
            <td  class="text-right">{{ $row->amount }} Rs.</td>
            <td class="text-center">
                <a class="btn btn-outline-success" href="{{route('ecomstocks.edit', $row->id)}}"><i class="fa fa-external-link"></i></a>          
            </td>
        </tr>

    @endforeach
    
@else 
    <tr><td colspan="9" class="text-danger text-center">No Stock !</td></tr>
@endif


