
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
            <td>{{$row->bill_no }}</td>
            <td>{{$row->bill_date }}</td>
            <td>{{$row->batch_no }}</td>
            <td>{{$row->total_quantity }}</td>
            <td>{{$row->total_weight }}</td>
            <td>{{$row->total_fine_weight }}</td>
            <td>{{$row->total_amount }}</td>
            <td>{{$row->pay_amount }}</td>
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

  @include('layouts.theme.datatable.pagination', ['paginator' => $purchases,'type'=>1])

  @include('layouts.vendors.js.passwork-popup')

