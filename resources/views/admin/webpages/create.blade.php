@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1> Web Page  List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">  Web Page  </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-8">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"> <x-back-button /> Create </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('webpages.store')}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('post')

          <div class="row">

              <div class="col-lg-12">
                  <label for="">Title</label>
                  <input type="text" name="title" class="form-control form-group" placeholder="Enter Title" value = "">
              </div>

              <div class="col-lg-12">
                  <label for="">Description</label>
                   <textarea name="description" class = "form-control ckeditor"></textarea>
              </div>

              <div class="col-lg-12">
                  <label for="">Meta Title</label>
                  <input type="text" name="meta_title" class="form-control form-group" placeholder="Enter Meta Title" value = "">
              </div>

              <div class="col-lg-12">
                  <label for="">Meta Description</label>
                   <textarea name="meta_description" class = "form-control" placeholder="Enter Meta Description" ></textarea>
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

@section('javascript')

@include('layouts.common.ckeditor')

<script>

    $(document).ready(function() {

        $('#submitForm').submit(function(e) {

            e.preventDefault() ;
            var formAction = $(this).attr('action');
            var formData = new FormData(this);
            formData.append('description', CKEDITOR.instances['description'].getData());

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
                window.open("{{route('webpages.index')}}", '_self') ;
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