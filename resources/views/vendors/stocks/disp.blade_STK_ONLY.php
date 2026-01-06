
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> 
        <th>S.N.</th>
        <th>Bill</th>
        <th>Name</th>
        <th>Rate</th>
        <th>Quantity/Weight</th>
        <th>Purity</th>
        <th>Amount</th>
        </tr>
    </thead>
    <tbody>

    @foreach($stocks as $key=>$row)
        <tr>
            <td>
                {{ $stocks->firstItem() + $key }}
            </td>
            <td><a href="{{ route('purchases.show',$row->purchase_id) }}" class="bill_show">{{ $row->purchase->bill_no }}</a></td>
            <td>
                {{$row->product_name }}
                <hr class="m-1">
                <b><i>{{ ucfirst($row->item_type) }}</i></b>
            </td>
            <td class="text-right">
                {{$row->rate}} Rs.
            </td>
            <td class="text-center">
                <b>INIT : </b>{{$row->quantity}} {{ ($row->unit=='grms')?'grms':''; }}
                <hr class="m-1">
                <b>AVAIL : </b>{{$row->available}} {{ ($row->unit=='grms')?'grms':''; }}
            </td>
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
            <td  class="text-right">{{ $row->amount }} Rs.</td>
        </tr>

    @endforeach

    </tbody>
  </table>
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
  @include('layouts.theme.datatable.pagination', ['paginator' => $stocks,'type'=>1])
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
