
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> 
        <th>S.N.</th>
        <th>Bill</th>
        <th>Name</th>
        <th>Rate</th>
        <th>Quantity</th>
        <th>Weight</th>
        <th>Purity</th>
        <th>Amount</th>
        </tr>
    </thead>
    <tbody>

    @foreach($stocks as $key=>$row)
        <tr>
            <td>{{ $stocks->firstItem() + $key }}</td>
            <td><a href="{{ route('purchases.show',$row->purchase->id) }}" class="bill_show">{{ $row->purchase->bill_no }}</a></td>
            <td>
                {{$row->name }}
                @if($row->item_type=="artificial")
                <hr class="m-1">
                <b><i>Artificial</i></b>
                @endif
            </td>
            <td class="text-right">
                @if($row->item_type!="artificial")
                {{$row->rate}} Rs.
                @else 
                    <b>----</b>
                @endif
            </td>
            <td class="text-center">{{$row->quantity}}</td>
            <td>
                @if($row->item_type!="artificial")
                <b>GROSS: </b>{{$row->gross_weight}}
                <hr class="m-1">
                <b>NET: </b>{{$row->net_weight}}
                <hr class="m-1">
                <b>FINE: </b>{{$row->fine_weight}}
                @else 
                    <b>----</b>
                @endif
            </td>
            <td>
                @if($row->item_type!="artificial")
                <b>PURITY: </b>{{$row->purity}}
                <hr class="m-1">
                <b>FINE: </b>{{$row->fine_purity}}
                @else 
                    <b>----</b>
                @endif
            </td>
            <td  class="text-right">{{round($row->quantity*$row->amount)}} Rs.</td>
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
