@extends('layouts.admin.app')

@section('content')

    @php

        $data = component_array('breadcrumb' , 'Whats New List' ,['title' => 'Whats New'] ) ; 

    @endphp

    <x-page-component :data=$data />

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-8">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"> <x-back-button /> Edit </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('whatsnew.update',$whatsnew->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')

          <div class="row">

              <div class="col-lg-12">
                  <label for="">Title</label>
                  <input type="text" name="title" class="form-control form-group" placeholder="Enter Title" value = "{{ $whatsnew->title }}">
              </div>

              <div class="col-lg-12">
                  <label for=""> Image </label>
                  <input type="file" name="image_file" class="form-control form-group"  accept="image/*" >
              </div>

              <div class="col-lg-12">
                  <label for="">Description</label>
                   <textarea name="description" class = "form-control ckeditor" placeholder="Enter Description" >{{ $whatsnew->description }}</textarea>
              </div>

          </div>

          <div class="row">
              <div class="col-12 text-center my-3 ">
                  <button type = "submit" class="btn btn-danger"> Submit </button>
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

@include('layouts.common.ckeditor')

@section('javascript')

<script>

    $(document).ready(function() {

        $('#submitForm').submit(function(e) {

            e.preventDefault() ;
            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;
            formData.append('description', CKEDITOR.instances['description'].getData()) ;

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
                window.open("{{route('whatsnew.index')}}", '_self') ;
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
