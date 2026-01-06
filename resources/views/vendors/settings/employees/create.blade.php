@extends('layouts.vendors.app')

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Employees List',[['title' => 'Employees']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('employees.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Employees"=>route('employees.index')];
$data = new_component_array('newbreadcrumb',"New Employee",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <section class="content">
  <div class="container-fluid">
  <div class="row">
  <!-- left column -->
  <div class="col-md-12">
  <!-- general form elements -->
  <div class="card card-primary">
  <!--<div class="card-header">
  <h3 class="card-title"><x-back-button /> Create </h3>
  </div>-->

  @if(session('success'))
  <div class="alert alert-success">
  {{ session('success') }}
  </div>
  @endif

  <!-- /.card-header -->

  <div class="card-body">

  <form method="POST" action="{{ route('employees.store')}}" enctype="multipart/form-data" id = "submitForm"  >

  @csrf

  @method('post')

  <div class="row">

  <div class="col-lg-4 form-group">
  <label class="text-gray-700 select-none font-medium">Shop Branch</label>
    <select name="branch_id" id="" class="form-control">
        <option value=""> Select</option>
        @foreach ($branches as $branch)
        <option value="{{$branch->id}}" {{ (old('branch')==$branch->id)? 'selected' :'' }}> {{$branch->branch_name}} </option>
        @endforeach
    </select>
  </div>

  <div class="col-lg-4 form-group">
  <label for="name" class="text-gray-700 select-none font-medium"> Name</label>
  <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter name" class="form-control"  />
  @if ($errors->has('name'))
  <div class="alert text-danger">{{ $errors->first('name') }}</div>
  @endif
  </div>

  <div class="col-lg-4 form-group">
  <label for="email" class="text-gray-700 select-none font-medium">Email</label>
  <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter email" class="form-control"  />
  @if ($errors->has('email'))
  <div class="alert text-danger">{{ $errors->first('email') }}</div>
  @endif
  </div>

  <div class="col-lg-4 form-group">
  <label for="mobile_no" class="text-gray-700 select-none font-medium">Mobile No</label>
  <input id="mobile_no" type="text" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Enter Mobile No" class="form-control" pattern=".{10,10}" title = "Please Enter 10 Digit Mobile No" maxlength = "10" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" />
  @if ($errors->has('mobile_no'))
  <div class="alert text-danger">{{ $errors->first('mobile_no') }}</div>
  @endif
  </div>

  <div class="col-lg-4 form-group">
  <label for="password" class="text-gray-700 select-none font-medium">Password</label>
  <input id="password" type="password" name="password" value="{{ old('password') }}" placeholder="Enter password" class="form-control"  autocomplete="new-password" />
  @if ($errors->has('password'))
  <div class="alert text-danger">{{ $errors->first('password') }}</div>
  @endif
  </div>

  <div class="col-lg-4 form-group">
  <label for="mpin" class="text-gray-700 select-none font-medium"> MPIN </label>
  <input id="mpin" type="password" name="mpin" placeholder="Re-enter password" class="form-control" value="{{ old('mpin') }}" autocomplete="off" />
  @if ($errors->has('mpin'))
  <div class="alert text-danger">{{ $errors->first('mpin') }}</div>
  @endif
  </div>

  <div class="col-lg-12 form-group">

  <label for="mpin" class="text-gray-700 select-none font-medium">Role</label>
  <div class="row">
  @foreach($roles as $key => $role)

  <div class="col-3">

  <label class="inline-flex items-center mt-3">
  <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="roles[]" value="{{$role->name}}" {{ (in_array($role->id, old('roles', []))) ? 'checked' : '' }}>
  <span class="ml-2 text-gray-700">{{ role_prefix_remover($role->name) }}</span>
  </label>
  </div>
  @endforeach

  </div>

  @if ($errors->has('roles'))
      <div class="alert text-danger">{{ $errors->first('roles') }}</div>
  @endif

  </div>

  <div class="col-12">
  <div class="text-center mt-16 mb-16">
  <button type="submit" class="btn btn-outline-success ">Submit</button>
  </div>
  </div>

  </div>

  </form>

  </div>
  </div>

  </div>
  <!-- /.row -->
  </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  </section>

  @endsection

  @section('javascript')

    <script>

        $(document).ready(function() {
        $('#submitForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;
            // Send AJAX request
            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                // $('.btn').prop("disabled", true);
                // $('#loader').removeClass('hidden');
                },
                success: function(response) {
                // Handle successful update
                success_sweettoatr(response.success);
                window.open("{{route('employees.index')}}", '_self');
                },
                error: function(response) {
                    var errors = response.responseJSON.errors ;
                    if (response.status === 422) {
                        $('.form-control').removeClass('is-invalid') ;
                        $('.btn-outline-danger').prop("disabled", false);
                        $('.btn').prop("disabled", false);
                        $('#loader').addClass('hidden');
                        $.each(errors, function(field, messages) {
                        var $field = $('[name="' + field + '"]');
                        toastr.error(messages[0]) ;
                        $field.addClass('is-invalid') ;
                        });
                    } else {
                        toastr.error(errors) ;
                    }
                }
            });
        });
        });

    </script>

  @endsection
