
<table id="CsTable" class="table table_theme table-striped table-bordered text-wrap align-middle ">
    <thead class = "">
        <tr> <th style="">S.N.</th>
        <th style="width:10%">Supplier</th>
        <th style="width:10%">Bill No </th>
        <th style="width:10%">Bill Date</th>
        <th style="width:10%">Batch No</th>
        <th style="width:10%">Total Quantity</th>
        <th style="width:10%">Total Weight</th>
        <th style="width:10%">Total Fine Weight</th>
        <th style="width:10%">Total Amount</th>
        <th style="width:10%">Paid</th>
        <th style="width:10%">Remains</th>
        <th style="width:10%">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($purchases as $key=>$row)
        <tr>
            <td>{{ $purchases->firstItem() + $key }}</td>
            <td>{{$row->supplier->supplier_name }}</td>
            <td>
                <a href="{{ route('purchases.show',$row->id) }}" class="bill_show">
                    {{$row->bill_no }}
                </a>
            </td>
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
            {{$row->remain }}
            </td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-info editButton" href="{{route('purchases.edit', $row->id)}}"><i class="fa fa-edit"></i></a>
				<div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        &cross; <li class="fa fa-caret-down"></li>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <button type="button" class="btn btn-outline-danger  dropdown-item" data-toggle="modal" data-target="#blockingmodal" data-url="{{ route('purchases.delete',$row->id) }}">
                            Bill Only ?
                            </button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-outline-warning dropdown-item " data-toggle="modal" data-target="#confirmDeleteModal" data-label="Stock Return & Delete !" data-delete-url="{{ route('purchases.destroy',$row->id) }}">Stock Too ?</button>
                        </li>
                    </ul>
                </div>
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
