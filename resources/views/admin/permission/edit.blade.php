@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
  <div class="col-sm-6">
  <h1>Edit Permissions </h1>
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
  <div class="row justify-content-center">
  <!-- left column -->
  <div class="col-md-6">
  <!-- general form elements -->
  <div class="card ">
  <div class="card-header">
  <a class = "btn btn-outline-primary" href = "{{route('permissions.index')}}"><i class="fa fa-arrow-left"></i>  Back </a>
  </div>
  
  <div class="card-body">
  <form method="POST" action="{{ route('permissions.update',$permission->id)}}">
  @csrf
  @method('put')

  <div class = "row justify-content-center ">

    <div class="col-lg-12 form-group">
        <label for=""> Parent <span class = "text-danger"> </span> </label>
        <select name="parent_id" class = "form-control select2 ">
            <option value="">Select</option>
            @foreach ($permissions as $per )
            <option value = "{{ $per->id }}" @if (@$permission->parent->id ==$per->id) selected @endif >{{ $per->name }}</option>
            @endforeach
        </select>
    </div>

  <div class = "col-lg-12">
  <div class = "form-group">
  <label for="role_name" class="text-gray-700 select-none font-medium">Permission Name</label>
  <input id="role_name" type="text" name="name" value="{{ old('name',$permission->name) }}" placeholder="Enter permission"  class="form-control" />
  </div>
  </div>

  <div class="text-center mt-16">
  <button type="submit" class="btn btn-outline-success ">Update</button>
  </div>
  </div>

  </form>

  </div>
  </div>
  
  </div>
  </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  </section>

  @endsection
  