@extends('layouts.admin.app')

@section('content')

<section class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
          <h1>Web Informations</h1>
          </div>
          <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active"> Ecommerce</li>
          </ol>
          </div>
      </div>
  </div><!-- /.container-fluid -->
</section>

<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

            <div class="card-header">
                Set Ups
            </div>

          <div class="card-body">

            @php

                $method =  (!empty($data)) ? 'put' : 'post' ;
                $logo  = (!empty($data)) ? $data->logo : 'no_logo.png' ;

            @endphp

          <form id = "submitForm" method="POST" action="{{ route('ecommsetups',$branch_data->id) }}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('post')

          <div class="row">

            <div class="form-group col-md-6">
                <label for="emailone" class="required_label"> Website Domain  </label>
                <input type="text" name="web_domain" class="form-control" id="web_domain" value = "{{@$branch_data->domain_name}}" >
            </div>

            <div class="form-group col-md-6">
                <label for="emailone" class="required_label"> Website Title  </label>
                <input type="text" name="web_title" class="form-control" id="web_title" value = "{{@$data->web_title}}" >
            </div>

            <div class="col-md-6">
                <div class="form-group col-md-12">
                    <label for="email" class="required_label">Email One</label>
                    <input type="email" name="email" class="form-control" id="email"  value = "{{@$data->email}}">
                </div>
    
                <div class="form-group col-md-12">
                    <label for="emailtwo">Email Alternate </label>
                    <input type = "email" name="email_2" class="form-control"  id = "email_2"  value = "{{@$data->email_2}}">
                </div>
                </div>
    
            <div class="form-group col-md-3">
                <label for="emailone" class="required_label"> Website Logo </label>
                <input type="file" name="web_logo" class="form-control" id="image" accept="image/*">
            </div>

            <div class="form-group col-md-3">
                <label for="" class="required_label"> Preview Logo </label>
                <img src = "{{ asset('assets/ecomm/logos/' .$logo) }}" class="img-responsive" id="img_prev" style = "max-height:15vh;height:auto;margin:auto; width: 100%; margin-bottom: 10px;object-fit: contain;">
            </div>

            <div class="form-group col-md-6">
                <label for="mobile_no" class="required_label"> Mobile No </label>
                <input type="text" name="mobile_no" class="form-control"  id="mobile_no" minlength="10" maxlength="10" value = "{{@$data->mobile_no}}">
            </div>

            <div class="form-group col-md-6">
                <label for="mobile_no_2">Mobile No Alternate</label>
                <input type="text" name="mobile_no_2" class="form-control"  id="mobile_no_2"  minlength="10" maxlength="10" value = "{{@$data->mobile_no_2}}">
            </div>

            <div class="form-group col-md-6">
                <label for="greet" class="required_label"> Location </label>
                <textarea name="map_iframe" class="form-control"  id="map_iframe" >{{@$data->map}}</textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="address" class="required_label">Address</label>
                <textarea name="address" class="form-control" id="address" >{{@$data->address}}</textarea>
            </div>

          </div>

          <div class="row">
              <div class="col-12 text-center my-3 ">
                  <button type = "submit" class="btn btn-danger"> Submit </button>
                  <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                  <input class="form-control" type = "hidden" name="branch_id" value = "{{ $branch_data->id }}" >
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

    $("#image").change(function(){
        var file = $(this).get(0).files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                $("#img_prev").attr("src", reader.result);
            }
            reader.readAsDataURL(file) ;
        }
    }) ;

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
      toastr.success(response.success);
    //   window.open("{{route('ecommwebinformations.index')}}", '_self');
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

