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
                @if($row->stock_type!='genuine')
                    <hr class="m-1">
                <b><i>{{ ($row->stock_type=='artificial')?"Artificial":"Loose"}} </i></b>
                @endif
            </td>
            <td class="text-center">
                @if($row->stock_type!='other')
                {{$row->stock_quantity}}
                @else 
                    <b>-----</b>
                @endif
            </td>
            <td class="text-center">
                @if($row->stock_type=='other')
                    {{$row->stock_quantity}}
                @else 
                    <b>-----</b>
                @endif
                {{$row->quantity}}
            </td>
            <td class="text-center">
            <a href="{{ route('purchases.show',$row->stock->purchase_id) }}" class="bill_show">{{ @$row->stock->purchase->bill_no }}</a>
            </td>
            <td></td>
        </tr>

    @endforeach
      
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
@else 
    <tr><td colspan="8" class="text-center text-danger">No Item/Product !</td></tr>
@endif


