
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr> <th style="">S.N.</th>
        <th style="width:10%">Name</th>
        <th style="width:10%">Incharge Name</th>
        <th style="width:10%">Mobile No</th>
        <th style="width:10%">Branch Type</th>
        <th style="width:10%">Address</th>
        <th style="width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($shopbranches as $key=>$row)
        <tr>
            <td>{{ $shopbranches->firstItem() + $key }}</td>
            <td>{{$row->branch_name }}</td>
            <td>{{$row->name }}</td>
            <td>{{$row->mobile_no}}</td>
            <td> @if($row->branch_type==0) Main Branch @else  Sub Branch @endif </td>
            <td>{{$row->address}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('shopbranches.edit', $row->id)}}">Edit</a>
                @if($row->branch_type!=0)
                <form action="{{ route('shopbranches.destroy',$row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button>
                </form>
                @endif
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $shopbranches,'type'=>1])

