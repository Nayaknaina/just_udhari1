
<table id="CsTable" class="table table-bordered table-hover">
    <thead class = "bg-info">
        <tr> <th style="border:1px solid;">S.N.</th>
        <th style="border:1px solid;width:10%">Image</th>
        <th style="border:1px solid;width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($images as $key=>$row)
        <tr>
            <td>{{ $images->firstItem() + $key }}</td>
            <td> <img src="{{ asset('ecom/products/'.$row->images) }}" class="img-fluid tb_img" > </td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success edit " id = "{{$row->id}}">Edit</a>
                <form action="{{ route('images.destroy',$row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Product Gallery image?')">Delete</i></button>
                </form>
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $images,'type'=>1])
