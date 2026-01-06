<div class="table-responsives">
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr>
            <th >S.N.</th>
            <th >Scheme Name</th>
            <th >Group Name</th>
            <th >Enrollment</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>
    @php 
        $currentPage = $scheme_groups->currentPage();
        $sn = ($scheme_groups->perPage()*($currentPage-1))+1;
    @endphp
    @if($scheme_groups->count()>0)
        @foreach($scheme_groups as $key=>$row)
        <tr>
            <td>{{ $scheme_groups->firstItem() + $key }}</td>
            <td>{{ $row->schemes->scheme_head }}</td>
            <td>{{ $row->group_name }}</td>
            <td>
                {!! '<b style="font-size:15px;">'.$row->members->count().'</b>'." / ".$row->group_limit !!}</td>
            <td class="text-center">
                <a class="btn btn-outline-success editButton m-auto" href="{{route('group.edit', $row->id)}}">Edit</a>
                    
                <button type="button" class="btn btn-danger m-auto" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('group.destroy',$row->id) }}">
                    Delete
                </button>
            </td>
        </tr>
        @endforeach
    @else 

    @endif

    </tbody>
  </table>
</div>
  @include('layouts.vendors.js.passwork-popup')
  <div class="col-12">
  @include('layouts.theme.datatable.pagination', ['paginator' => $scheme_groups,'type'=>1])
  </div>


