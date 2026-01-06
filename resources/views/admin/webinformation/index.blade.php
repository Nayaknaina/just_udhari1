@extends('layouts.admin.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Web Information </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Web Information </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <!-- left column -->
    <div class="col-md-8">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-body">

        <form id="submitForm" method="POST" action="{{ route('webinformation.update', $webinformation->id) }}" class="myForm" enctype="multipart/form-data">

            @csrf
            @method('put')

            <div class="row">

                <div class="col-lg-6 dss ">
                    <label for="name">Name</label>
                    <input type="text" class="form-control form-group" id="name" name="name" value="{{ old('name', $webinformation->name) }}" placeholder="Enter Name" required>
                </div>

                <div class="col-lg-6">
                    <label for="logo">Logo</label>
                    <input type="file" class="form-control form-group" id="web_logo" name="web_logo">
                    @if($webinformation->logo)
                        <small class="form-text text-muted">Current Logo: <img src="{{ asset( $webinformation->logo) }}" alt="Current Logo" width="100"></small>
                    @endif
                </div>

                <div class="col-lg-6">
                    <label for="mobile_no">Mobile No</label>
                    <input type="text" class="form-control form-group" id="mobile_no" name="mobile_no" value="{{ old('mobile_no', $webinformation->mobile_no) }}" placeholder="Enter Mobile No">
                </div>

                <div class="col-lg-6">
                    <label for="whatsapp_no">Whatsapp No</label>
                    <input type="text" class="form-control form-group" id="whatsapp_no" name="whatsapp_no" value="{{ old('whatsapp_no', $webinformation->whatsapp_no) }}" placeholder="Enter Whatsapp No">
                </div>

                <div class="col-lg-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control form-group" id="email" name="email" value="{{ old('email', $webinformation->email) }}" placeholder="Enter Email">
                </div>

                <div class="col-lg-12">
                    <label for="address">Address</label>
                    <textarea class="form-control form-group" id="address" name="address" placeholder="Enter Address">{{ old('address', $webinformation->address) }}</textarea>
                </div>

                <div class="col-lg-12">
                    <label for="map">Map</label>
                    <textarea class="form-control form-group" id="map" name="map" placeholder="Enter Map">{{ old('map', $webinformation->map) }}</textarea>
                </div>

                <div class="col-12 text-center my-3">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>

            </div>
        </form>

    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>

@endsection

@section('javascript')

<script>

    $(document).ready(function() {

        $('#submitForm').submit(function(e) {

            e.preventDefault() ;
            var formAction = $(this).attr('action');
            var formData = new FormData(this);

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.btn').prop("disabled", true) ;
                    $('#loader').removeClass('hidden') ;
                },
                success: function(response) {
                // Handle successful update
                toastr.success(response.success);
                window.open("{{route('webinformation.index')}}", '_self') ;
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
