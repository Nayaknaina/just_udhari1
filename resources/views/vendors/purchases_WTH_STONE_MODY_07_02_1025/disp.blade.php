
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Supplier</th>
        <th style="border:1px solid;width:10%">Bill No </th>
        <th style="border:1px solid;width:10%">Bill Date</th>
        <th style="border:1px solid;width:10%">Batch No</th>
        <th style="border:1px solid;width:10%">Total Quantity</th>
        <th style="border:1px solid;width:10%">Total Weight</th>
        <th style="border:1px solid;width:10%">Total Fine Weight</th>
        <th style="border:1px solid;width:10%">Total Amount</th>
        <th style="border:1px solid;width:10%">Pay Amount</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($purchases as $key=>$row)
        <tr>
            <td>{{ $purchases->firstItem() + $key }}</td>
            <td>{{$row->supplier->supplier_name }}</td>
            <td><a href="{{ route('purchases.show',$row->id) }}" class="bill_show">{{$row->bill_no }}</a></td>
            <td>{{$row->bill_date }}</td>
            <td>{{$row->batch_no }}</td>
            <td>{{$row->total_quantity }}</td>
            <td>{{$row->total_weight }}</td>
            <td>{{$row->total_fine_weight }}</td>
            <td>{{$row->total_amount }}</td>
			<td>
				<a href="{{ route('purchases.txns',["bill"=>$row->id]) }}" class="bill_pay">
                    {{$row->pay_amount }}
                </a>
			</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success editButton" href="{{route('purchases.edit', $row->id)}}">Edit</a>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('purchases.destroy',$row->id) }}">
                    Delete
                </button>

                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>
    <style>
        .bill_show,.bill_pay{
            color:blue;
            border:1px solid lightgray;
            padding:1px 2px;
        }
        .bill_show:hover,.bill_pay:hover{
            color:black;            
        }
    </style>
  @include('layouts.theme.datatable.pagination', ['paginator' => $purchases,'type'=>1])

  @include('layouts.vendors.js.passwork-popup')


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