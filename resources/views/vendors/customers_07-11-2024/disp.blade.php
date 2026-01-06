
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Name</th>
        <th style="border:1px solid;width:10%">Mobile No</th>
        <th style="border:1px solid;width:10%">Address</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($customers as $key=>$row)
        <tr>
            <td>{{ $customers->firstItem() + $key }}</td>
            <td>{{$row->custo_full_name }}</td>
            <td>{{$row->custo_fone}}</td>
            <td>{{$row->custo_address}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('customers.edit', $row->id)}}">Edit</a>
                <form action="{{ route('customers.destroy',$row->id) }}" method="POST">
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

  @include('layouts.theme.datatable.pagination', ['paginator' => $customers,'type'=>1])

