@extends('layout.admin.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
  <div class="col-sm-6">
  <h1>Roles List</h1>
  </div>
  <div class="col-sm-6">
  <ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="#">Home</a></li>
  <li class="breadcrumb-item active">Roles</li>
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
  <a class = "btn btn-outline-primary" href = "{{route('admin.roles.index')}}"><i class="fa fa-arrow-left"></i>  Back </a>
  </div>
  
  <div class="card-body">
  <form method="POST" action="{{ route('admin.roles.store')}}">
  @csrf
  @method('post')
  
  <div class="row justify-content-center">
  
  <div class="col-lg-3">
  <div class="form-group">
  <label for="role_name" class="text-gray-700 select-none font-medium">Role Name</label>
  <input id="role_name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter role" />
  </div> 
  </div>
  
  <div class="col-lg-3">
  <div class="form-group">
  <label for="role_name" class="text-gray-700 select-none font-medium">Role Level</label>
  <input id="role_level" type="number" name="role_level" value="{{ old('role_level') }}" class="form-control" placeholder="Enter Level" min="1" />
  </div> 
  </div>

  <div class="col-lg-12 form-group">
  <h3 class="text-xl my-4 text-gray-600">Permissions</h3>
  <div class = "row">

  @foreach($permissions as $permission) 

  <div class="col-lg-4">
  <div class="flex flex-col justify-cente">
  <div class="flex flex-col">
  <label class="inline-flex items-center mt-3">
  <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="permissions[]" value="{{$permission->id}}"
  ><span class="ml-2 text-gray-700">{{ $permission->name }}</span>
  </label>
  </div>
  </div>
  </div>
  @endforeach
  </div>
  </div>

  <div class="text-center mt-16">
  <button type="submit" class="btn btn-success">Submit</button>
  </div>
  </div>
  </div>
  
  </form>
  
  </div>
  </div>
  
  </div> <!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  </section>

  </div>
 

  @endsection
  