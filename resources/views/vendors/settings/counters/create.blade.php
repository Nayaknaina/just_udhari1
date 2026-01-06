  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Counters',[['title' => 'Counters']] ) ;

  @endphp

  <x-page-component :data=$data />

  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">

            <div class="card-header">
            <h3 class="card-title"><x-back-button /> Create </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('counters.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-12">
                    <label for="">Counter Name</label>
                    <input type="text" name="name" class="form-control form-group" placeholder="Enter Counter Name">
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
        window.open("{{route('counters.index')}}", '_self');
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
    });
    });
    });

  </script>

  @endsection

