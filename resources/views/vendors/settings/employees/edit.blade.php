@extends('layouts.vendors.app')

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Employees',[['title' => 'Employees']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('employees.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Employees"=>route('employees.index')];
$data = new_component_array('newbreadcrumb',"Edit Employee",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/>  
<section class="content">
  <div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id = "submitForm" method="POST" action="{{ route('employees.update',$user->id)}}" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-lg-8">

                <div class="card card-default">
                <div class="card-header p-1">
                    <h3 class="card-title text-secondary">Update Info </h3>
                </div>

                <div class="card-body">
                <div class="row">

                <div class="col-lg-6 form-group">
                    <label class="text-gray-700 select-none font-medium">Shop Branch</label>
                    <select name="branch_id" id="" class="form-control">
                    <option value=""> Select</option>
                    @foreach ($branches as $branch)
                    <option value="{{$branch->id}}" {{ ($branch->id == old('branch_id' , $user->branch_id) ? 'selected' : '' ) }} > {{$branch->branch_name}} </option>
                    @endforeach
                    </select>
                </div>

                <div class="col-lg-6 form-group">
                    <label for="name" class="text-gray-700 select-none font-medium">User Name</label>
                    <input id="name" type="text" name="name"  value="{{ old('name',$user->name) }}" placeholder="Enter name" class="form-control" />
                    @if ($errors->has('name'))
                    <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="col-lg-6 form-group">
                    <label for="email" class="text-gray-700 select-none font-medium">Email</label>
                    <input id="email" type="text" name="email"  value="{{ old('email',$user->email) }}" placeholder="Enter email" class="form-control"  />
                    @if ($errors->has('email'))
                    <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="col-lg-6 form-group">
                    <label for="mobile_no" class="text-gray-700 select-none font-medium">Mobile No</label>
                    <input id="mobile_no" type="text" name="mobile_no" value="{{ old('mobile_no',$user->mobile_no) }}" placeholder="Enter Mobile No" class="form-control" pattern=".{10,10}" title = "Please Enter 10 Digit Mobile No" maxlength = "10" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" />
                    @if ($errors->has('mobile_no'))
                    <div class="alert alert-danger">{{ $errors->first('mobile_no') }}</div>
                    @endif
                </div>

                <div class = "col-lg-12 form-group">

                    <label for="mpin" class="text-gray-700 select-none font-medium">Role</label>
                    <div class="row">
                    @foreach($roles as $key => $role)

                    <div class="col-3">
                    <label class="inline-flex items-center mt-3">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" name="roles[]" value="{{$role->name}}"  @if(count($user->roles->where('id',$role->id))) checked  @endif >
                    <span class="ml-2 text-gray-700">{{ role_prefix_remover($role->name) }}</span>
                    </label>
                    </div>

                    @endforeach

                    </div>

                    @if ($errors->has('roles'))
                    <div class="alert alert-danger">{{ $errors->first('roles') }}</div>
                    @endif

                </div>

                <div class="col-12">
                    <div class="text-center mt-16 mb-16">
                        <button type="submit" class="btn btn-outline-success ">Update</button>
                    </div>
                </div>

                </div>
                </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card card-default">
                <div class="card-header p-1">
                    <h3 class="card-title text-secondary"> Update Password </h3>
                </div>

                <div class="card-body">
                <div class="row">
    
                    <div class="col-lg-12 form-group">
                        <label for="password" class="text-gray-700 select-none font-medium">Password</label>
                        <input id="password" type="password" name="password" value="{{ old('password') }}" placeholder="Enter password" class="form-control"  autocomplete="new-password" />
                        @if ($errors->has('password'))
                        <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="col-lg-12 form-group">
                        <label for="mpin" class="text-gray-700 select-none font-medium">MPIN</label>
                        <input id="mpin" type="password" name="mpin" placeholder="Enter MPIN" class="form-control"   />
                        @if ($errors->has('mpin'))
                        <div class="alert alert-danger">{{ $errors->first('mpin') }}</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <div class="text-center mt-16 mb-16">
                        <button type="submit" class="btn btn-outline-success ">Update</button>
                        </div>
                    </div>

                </div>
                </div>
                </div>
            </div>

        </div>
    </form>
    </div>< 
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
                        $('input').removeClass('is-invalid');
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
