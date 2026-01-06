
@if($stocks->count() > 0)
    @foreach($stocks as $key=>$row)
        @php 
            
            $quant = ($row->item_type=="artificial")?$row->quantity:$row->gross_weight;
            $placable_quant = $quant- $row->counter->sum('stock_quantity')

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
                <hr class="m-1">
                @if($row->item_type !="other")
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
                @else 
                <b><i>Loose</i></b>
                @endif
            </td>
            <td class="text-center {{ ($row->item_type !="artificial")?'unit':'' }}">
                {{ $placable_quant }}
            </td>
            <td>
                <input type="hidden" name="item_type[]" value="{{ $row->item_type }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
                <input type="text" name="quantity[]" value="{{ $placable_quant }}" class="form-control text-center" placeholder="{{ ($row->item_type!='artificial')?'Grms':'Count' }}" disabled {{ ($row->item_type =="genuine")?'readonly':'' }}>
            </td>
            <td  class="text-center">
                <label class="form-control" style="width:fit-content;" for="stock_{{ $stocks->firstItem() + $key }}">
                    @if($row->exist==0 || $placable_quant > 0)
                    <input type="checkbox" name="stock[]" value="{{ $row->id }}" id="stock_{{ $stocks->firstItem() + $key }}" class="stock_check" >
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
