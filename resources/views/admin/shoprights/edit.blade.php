@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
  <div class="col-sm-6">
  <h1>Edit Roles </h1>
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
  {{-- <a class = "btn btn-primary" href = "{{route('blogs.create')}}"><i class="fa fa-plus"></i>  Add New </a> --}}
  </div>

  <div class="card-body">
  <form method="POST" action="{{ route('roles.update',$role->id)}}">
  @csrf
  @method('put')

  <div class="row justify-content-center">

  <div class="col-lg-4">
    <div class="form-group">
    <label for="role_name" class="text-gray-700 select-none font-medium">Software Product Name</label>
        <input id="role_name" type="text" name="product_name" value="{{ old('product_name',@$role->product->title) }}" placeholder="Placeholder" class="form-control" readonly  />
        <input id="role_name" type="hidden" name="name" value="{{ old('name',$role->name) }}" placeholder="Placeholder" class="form-control" readonly  />
    </div>
  </div>

  <div class="col-lg-12 form-group">
  <div class = "row align-items-center">

   <div class="col-lg-12">
      <div class="card p-3 bg-info text-bold ">
      <div class="row">
      <div class="col-lg-1"> # </div>
      <div class="col-lg-3"> Permission Head </div>
      <div class="col-lg-8"> Permission </div>
      </div>
      </div>
    </div>

  @foreach($permissions as $permission)

    <div class = "card-body">
        <div class = "row">
            <div class="col-lg-1"> <label> <input type = "checkbox" class = "form-checkbox main-head " id = "main{{ $permission->id }}" >  All </label> </div>
            <div class="col-lg-3">

                <label class="inline-flex items-center mt-3">
                <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$permission->id}}" @if(count($role->permissions->where('id',$permission->id))) checked @endif >
                <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                </label>

            </div>

            <div class="col-lg-8">

            @if($permission->children)

            <div class="row">

            @foreach ($permission->children as $child)

                <div class="col-lg-3">
                <div class="card p-2 m-2 text-center">
                <label class="inline -flex items-center mt-3">
                <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$child->id}}" @if(count($role->permissions->where('id',$child->id))) checked @endif > <span class="ml-2 text-gray-500">{{ $child->name }}</span>
                </label>
                </div>
                </div>

            @endforeach

            </div>

            @endif

            </div>
        </div>
  </div>

  <div class="col-lg-12 m-2" style="border: 1px dotted rgb(0,0,0,.3)"></div>

  @endforeach

  </div>
  </div>

  <div class="col-lg-4">
  <button type="submit" class="btn btn-success">Update</button>
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


  @section('javascript')


    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const mainHeads = document.querySelectorAll('.main-head') ;
            const subHeads = document.querySelectorAll('.sub-head') ;

                mainHeads.forEach(mainHead => {
                mainHead.addEventListener('change', function() {
                    const subHeadCheckboxes = document.querySelectorAll(`.sub-head[data-main="${mainHead.id}"]`);
                    subHeadCheckboxes.forEach(subHead => {
                        subHead.checked = mainHead.checked;
                    });
                });
            });

            function updateMainHeads() {
                subHeads.forEach(subHead => {
                    const mainHeadId = subHead.getAttribute('data-main');
                    const mainHead = document.getElementById(mainHeadId);
                    const relatedSubHeads = document.querySelectorAll(`.sub-head[data-main="${mainHeadId}"]`);
                    mainHead.checked = Array.from(relatedSubHeads).every(sub => sub.checked);
                });
            }

            subHeads.forEach(subHead => {
                subHead.addEventListener('change', function() {

                    const mainHeadId = subHead.getAttribute('data-main');
                    const mainHead = document.getElementById(mainHeadId);
                    const relatedSubHeads = document.querySelectorAll(`.sub-head[data-main="${mainHeadId}"]`);
                    mainHead.checked = Array.from(relatedSubHeads).every(sub => sub.checked) ;

                });
            });

            updateMainHeads() ;

        });

    </script>

  @endsection
