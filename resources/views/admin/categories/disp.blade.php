
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Name</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($categories as $key=>$row)
        <tr>
            <td>{{ $categories->firstItem() + $key }}</td>
            <td>{{$row->name }}</td>
            <td>
                <div class="d-flex justify-content-betw een">
                <a class="btn btn-outline-success" href="{{route('productcategories.edit', $row->id)}}">Edit</a>
                <form action="{{ route('productcategories.destroy',$row->id) }}" method="POST">
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

  @include('layouts.theme.datatable.pagination', ['paginator' => $categories,'type'=>1])

