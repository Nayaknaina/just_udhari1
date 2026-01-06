@extends('layouts.vendors.app')

@section('content')

    @php

     //$data = component_array('breadcrumb' , 'Designation' ,[['title' => 'Designation']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('designations.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Designations"=>route('designations.index')];
$data = new_component_array('newbreadcrumb',"New Designation",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <section class="content">
  <div class="container-fluid">
  <div class="row">
  <!-- left column -->
  <div class="col-md-12">
  <!-- general form elements -->
  <div class="card card-primary ">
  {{--<div class="card-header">
    <x-back-button />  Create
  </div>--}}

  <div class="card-body">
  <form  id = "submitForm" method="POST" action="{{ route('designations.store')}}">
  @csrf
  @method('post')

  <div class="row justify-content-center">

  <div class="col-lg-3">
  <div class="form-group">
    <label for="role_name" class="text-gray-700 select-none font-medium">Designation/Role Name</label>
    <input id="role_name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter role" />
    <input id="role_suffix" type="hidden" name="role_suffix" value="{{ old('role_suffix',$role_suffix) }}" class="form-control"  />
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
                      <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$permission->id}}"  >
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
                      <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$child->id}}" > <span class="ml-2 text-gray-500">{{ $child->name }}</span>
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

  @endsection


@section('javascript')

<script>

    $(document).ready(function() {

        $('#submitForm').submit(function(e) {

            e.preventDefault() ;
            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    // $('.btn').prop("disabled", true) ;
                    // $('#loader').removeClass('hidden') ;
                },
                success: function(response) {
                // Handle successful update
                toastr.success(response.success);
                window.open("{{route('designations.create')}}", '_self') ;
                },
                error: function(response) {
                if (response.status === 422) {
                    var errors = response.responseJSON.errors;
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
                    console.log(response.responseText);
                }
                }
            }) ;
        });

    });

</script>

@endsection
