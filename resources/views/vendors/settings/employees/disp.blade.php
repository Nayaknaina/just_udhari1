
<table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
    <thead class = "">
        <tr> <th style="">S.N.</th>
        <th style="width:10%">Name</th>
        <th style="width:10%">Mobile No</th>
        <th style="width:10%">Designation</th>
        <th style="width:10%">Status</th>
        <th style="width:10%">Action</th>
        </tr>
    </thead>
    <tbody>

    @foreach($users as $key=>$user)

        <tr>
            <td>{{ $users->firstItem() + $key }}</td>
            <td>{{$user->name }}</td>
            <td>{{$user->mobile_no}}</td>
            <td>
                @if($user->roles)
                <ul>
                    @foreach ($user->roles as $role )
                    <li> {{ role_prefix_remover($role->name) }} </li>
                    @endforeach
                </ul>
                @endif
            </td>
            <td>{{$user->status}}</td>
            <td>
                <div class="d-flex justify-content-between">
                <a class="btn btn-outline-success" href="{{route('employees.edit', $user->id)}}"> Edit </a>
                </div>
            </td>

        </tr>

    @endforeach

    </tbody>
  </table>

  @include('layouts.theme.datatable.pagination', ['paginator' => $users,'type'=>1])

