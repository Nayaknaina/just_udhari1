
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr>
            <th>S.N.</th>
            <th>Role Name</th>
            <th>Permissions</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($roles as $key=>$role)
        <tr>
            <td>{{ $roles->firstItem() + $key }}</td>
            <td>{{ role_prefix_remover($role->name) }}</td>
            <td>
            @foreach($role->permissions as $permission)
            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-white bg-crimson rounded-full">{{ $permission->name }}</span>
            @endforeach
            </td>

            <td class="py-4 px-6 border-b border-grey-light text-right">
                <div class="d-flex">
                <a href="{{route('designations.edit',$role->id)}}" class="btn btn-outline-info btn-sm"> <i class="fa fa-edit"></i></a>
                </div>
            </td>
        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $roles,'type'=>1])

