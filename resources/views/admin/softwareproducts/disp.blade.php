
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr>
            <th>S.N.</th>
            <th>Title</th>
            <th>Price</th>
            <th> Image</th>
            <th>Banner Image</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($softwareproducts as $key=>$row)
        <tr>
            <td>{{ $softwareproducts->firstItem() + $key }}</td>
            <td>{{$row->title }}</td>
            <td>{{$row->price}}</td>
            <td> @if($row->image)  <img src="{{ asset('assets/images/thumbnail/'.$row->image.'')}}" class="img-fluid tb_img img-thumbnail" > <a> @else <span class="bg-warning p-1 "  > No Image </span> @endif </td>
            <td> @if($row->banner_image) <img src="{{ asset('assets/images/banner/'.$row->banner_image.'')}}" class="img-fluid tb_img img-thumbnail">  @else <span class="bg-warning p-1 "  > No Banner Image </span> @endif </td>
            <td>{{@$row->roles->name}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('softwareproducts.edit', $row->id)}}">Edit</a>
                <form action="{{ route('softwareproducts.destroy',$row->id) }}" method="POST">
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

  @include('layouts.theme.datatable.pagination', ['paginator' => $softwareproducts,'type'=>1])
  @include('layouts.common.image_popup')

