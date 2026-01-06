
@if($stocks->count() > 0)
    @foreach($stocks as $key=>$row)
        @php 

            $placable_quant = ($row->item_type=="other")?$row->gross_weight-$row->counter->sum('stock_quantity'):$row->quantity-$row->counter->sum('stock_quantity');

        @endphp 
        <tr>
            <td>
                {{ $stocks->firstItem() + $key }}
            </td>
            <td>
                <a href="{{ route('purchases.show',$row->purchase->id) }}" class="bill_show">{{ $row->purchase->bill_no }}</a>
            </td>
            <td>
                {{$row->name }}
                @if($row->item_type=="artificial")
                <hr class="m-1">
                <b><i>Artificial</i></b>
                @elseif($row->item_type=="other")
                <hr class="m-1">
                <b><i>Loose</i></b>
                @endif
            </td>
            <td class="text-center">
                
                @if($row->item_type!="other")
                    {{ $placable_quant }}
                @else
                    <b>-------</b>
                @endif
            </td>
            <td class="text-center">
                @if($row->item_type=="other")
                    {{ $placable_quant }}
                @else
                    <b>-------</b>
                @endif
            </td>
            <td>
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled>
                <input type="text" name="quantity[]" value="{{ $placable_quant }}" class="form-control text-center" placeholder="{{ ($row->item_type=='other')?'Grms':'Count' }}" disabled>
            </td>
            <td  class="text-center">
                <label class="form-control" style="width:fit-content;" for="stock_{{ $stocks->firstItem() + $key }}">
                    @if($row->exist==0 || $placable_quant > 0)
                    <input type="checkbox" name="stock[]" value="{{ $row->id }}" id="stock_{{ $stocks->firstItem() + $key }}" class="stock_check">
                    @else 
                        <span style="color:green;">&check;</span>
                    @endif
                </label>
            </td>
        </tr>

    @endforeach
@else 
    <tr><td colspan="7" class="text-center text-danger">No Items !</td></tr>
@endif
  <style>
        .bill_show{
            color:blue;
            border:1px solid lightgray;
            padding:1px 2px;
        }
        .bill_show:hover{
            color:black;            
        }
    </style>
  <script>
    $('.bill_show').click(function(e){
        e.preventDefault();
        $("#bill_modal_body").empty().load($(this).attr('href'));
        $("#bill_modal").modal();
        // $.get($(this).attr('href'),"",function(response){
        //     $("#bill_modal_body").empty().append(response);
        // });
    });
</script>
