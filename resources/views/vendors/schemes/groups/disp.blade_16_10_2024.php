
<table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "bg-info">
        <tr>
            <th >S.N.</th>
            <th >Group Name</th>
            <th >Schemes</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($groups as $key=>$row)

        <tr>
            <td>{{ $groups->firstItem() + $key }}</td>
            <td>{{ $row->group_name}} </td>
            <td>{{ @$row->schemes->scheme_head}} </td>
            <td class="text-center"> <div class="">
                <a class="btn btn-outline-success editButton" href="{{route('group.edit', $row->id)}}">Edit</a>
                
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('group.destroy',$row->id) }}">
                    Delete
                </button>

                </div>
            </td>
        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.vendors.js.passwork-popup')
  @include('layouts.theme.datatable.pagination', ['paginator' => $groups,'type'=>1])


