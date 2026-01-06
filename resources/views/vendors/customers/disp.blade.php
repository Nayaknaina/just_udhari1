@if($customers->count()>0)
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr> <th>S.N.</th>
        <th style="width:10%">Image</th>
        <th style="width:10%">Name</th>
        <th style="width:10%">Mobile No</th>
        <th style="width:10%">E-Mail</th>
        <th style="width:10%">Address</th>
        <th style="width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($customers as $key=>$row)
        <tr>
            <td>{{ $customers->firstItem() + $key }}</td>
            @php 
                $prf_img = ($row->custo_img!="" && file_exists($row->custo_img))?$row->custo_img:'assets/images/icon/browse.png';
            @endphp
            <td class="text-center"><img src="{{ asset("{$prf_img}") }}" class="img-responsive img-thumbnail"  style="width: 150px;height:auto;"></td>
            <td>{{$row->custo_full_name }}</td>
            <td>{{$row->custo_fone}}</td>
            <td>{{$row->custo_mail}}</td>
            <td>{{$row->custo_address}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('customers.edit', $row->id)}}">Edit</a>
               <!-- <form action="{{ route('customers.destroy',$row->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this Category post?')">Delete</i></button> -
            </form>-->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('customers.destroy',$row->id)}}">
                    Delete
                </button>
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $customers,'type'=>1])
  @else 
    <div class="alert alert-outline-danger text-center text-danger">No Record !</div>
  @endif
  @include('layouts.vendors.js.passwork-popup')

