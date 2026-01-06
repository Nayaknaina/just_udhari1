
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr> <th style="">S.N.</th>
        <th style="width:10%">Name</th>
        <th style="width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($stockcategories as $key=>$row)
        <tr>
            <td>{{ $stockcategories->firstItem() + $key }}</td>
            <td>{{$row->name }}</td>
            <td>
                <div class="d-flex justify-content-betw een">
                <a class="btn btn-outline-success" href="{{route('stockcategories.edit', $row->id)}}">Edit</a>
                <form action="{{ route('stockcategories.destroy',$row->id) }}" method="POST">
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

  @include('layouts.theme.datatable.pagination', ['paginator' => $stockcategories,'type'=>1])

