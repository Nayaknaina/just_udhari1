@extends('layouts.vendors.app')

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Designation ',[['title' => 'Designations']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('designations.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Designations"=>route('designations.index')];
$data = new_component_array('newbreadcrumb',"Edit Designation",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <section class="content">
  <div class="container-fluid">
  <div class="row">
  <!-- left column -->
  <div class="col-md-12">
  <!-- general form elements -->
  <div class="card ">
  <div class="card-body">
  <form method="POST" action="{{ route('designations.update',$designation->id)}}" id="submitForm">
  @csrf
  @method('put')

  <div class="row justify-content-center">

  <div class="col-lg-4">
    <div class="form-group">
    <label for="role_name" class="text-gray-700 select-none font-medium">Designation Name</label>
        <input id="role_name" type="text" name="name" value="{{ old('name',role_prefix_remover($designation->name)) }}" placeholder="Placeholder" class="form-control" />
        <input id="role_suffix" type="hidden" name="role_suffix" value="{{ old('name',$role_suffix) }}" placeholder="Placeholder" class="form-control" />
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
                    <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$permission->id}}" @if(count($designation->permissions->where('id',$permission->id))) checked @endif >
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
                    <input type = "checkbox" class = "form-checkbox sub-head" data-main="main{{ $permission->id }}" name = "permissions[]" value = "{{$child->id}}" @if(count($designation->permissions->where('id',$child->id))) checked @endif > <span class="ml-2 text-gray-500">{{ $child->name }}</span>
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

  <div class="col-lg-12 text-center">
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
        $(document).ready(function(){
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
                    window.open("{{route('designations.edit',$designation->id)}}", '_self') ;
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
