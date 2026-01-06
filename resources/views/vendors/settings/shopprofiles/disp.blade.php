
<table id="CsTable" class="table table-bordered table-hover">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Name</th> 
        <th style="border:1px solid;width:10%">Mobile No</th> 
        <th style="border:1px solid;width:10%">Address</th>
        <th style="border:1px solid;width:10%">Supplier No</th>
        <th style="border:1px solid;width:10%">Branch Name</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($suppliers as $key=>$row)
        <tr>
            <td>{{ $suppliers->firstItem() + $key }}</td>
            <td>{{$row->supplier_name }}</td> 
            <td>{{$row->mobile_no}}</td> 
            <td>{{$row->address}}</td> 
            <td>{{$row->unique_id}}</td> 
            <td>{{@$row->branch->branch_name}}</td>
            <td>
                <div class="d-flex justify-content-between"> 
                <a class="btn btn-outline-success" href="{{route('suppliers.edit', $row->id)}}">Edit</a>
                <form action="{{ route('suppliers.destroy',$row->id) }}" method="POST"> 
                @csrf
                @method('DELETE') 
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button> 
                </form> 
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $suppliers,'type'=>1])

