@extends('layouts.admin.app')

@section('css')

  <style>

    .table ul li { 

        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-content: center;
        align-items: center;
        justify-content: space-between;
        padding-right: 30px;

    } 

  </style>

@endsection

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
  <div class="col-sm-6">
  <h1>Permissions List</h1>
  </div>
  <div class="col-sm-6">
  <ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Permissions</li>
  </ol>
  </div>
  </div>
  </div><!-- /.container-fluid -->
  </section>

  <section class="content">
  <div class="container-fluid">
  <div class="row">
  <!-- left column -->
  <div class="col-md-12">
  <!-- general form elements -->
  <div class="card ">
  <div class="card-header">

  {{-- @can('Permission create') --}}
  <a class = "btn btn-outline-primary" href = "{{route('permissions.create')}}"><i class="fa fa-plus"></i>  Add New </a>
  {{-- @endcan --}}

  </div>
  
  <div class="card-body">

  <table id="myTable" class="table table-bordered table-striped table-hover" style="width:100%">
  <thead class="bg-info">
  <tr>
  <th>SN</th>
  <th>Permission Head</th>
  <th>Permission Name</th>
  <th>Action</th>
  </tr>
  </thead>
  <tbody>

  @foreach($permissions as  $key=> $permission)

  <tr>
  <td>{{ ++$key }}</td>
  <td>{{ $permission->name }}</td> 
  <td> 

    <ul>

    @foreach ($permission->children as $children)

    <li> {{ $children->name }} <div class="d-flex">
      {{-- @can('permission edit') --}}
      <a href="{{route('permissions.edit',$children->id)}}" class="btn btn-outline-info btn-sm"> <i class="fa fa-edit"></i></a>
      {{-- @endcan --}}

      {{-- @can('permission delete') --}}
      <form action="{{ route('permissions.destroy', $children->id) }}" method="POST" class="inline"  >
      @csrf
      @method('delete')
      <button class="btn btn-outline-danger btn-sm " onclick="return confirm('Are you sure you want to delete this Category post?')" > <i class="fa fa-trash"></i></button>
      </form>
      {{-- @endcan --}}

    </div>
     </li> 

    @endforeach

  </ul>
  </td> 
  <td class="py-4 px-6 border-b border-grey-light text-right">
  <div class="d-flex">
  {{-- @can('permission edit') --}}
  <a href="{{route('permissions.edit',$permission->id)}}" class="btn btn-outline-info btn-sm"> <i class="fa fa-edit"></i></a>
  {{-- @endcan --}}

  {{-- @can('permission delete') --}}
  <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="inline"  >
  @csrf
  @method('delete')
  <button class="btn btn-outline-danger btn-sm " onclick="return confirm('Are you sure you want to delete this Category post?')" > <i class="fa fa-trash"></i></button>
  </form>
  {{-- @endcan --}}

</div>

  </td>
  </tr>
  
  @endforeach

  </tbody>
  </table>
  
  </div>
  </div>
  
  </div>
  </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  </section>

  @endsection
  