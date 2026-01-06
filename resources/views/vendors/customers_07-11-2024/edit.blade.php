@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Customers',[['title' => 'Customers']] ) ;

@endphp

<x-page-component :data=$data />
 
<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

          <div class="card-header">
          <h3 class="card-title"> <x-back-button /> Edit Customer </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('customers.update',$customer->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')

          <div class="row">

              <div class="col-lg-6">
                  <label for="">Customer Name</label>
                  <input type="text" name="custo_full_name" class="form-control form-group" placeholder="Enter Customer Name" value="{{ $customer->custo_full_name }}">
              </div>

              <div class="col-lg-6">
                  <label for="">Mobile No</label>
                  <input type="number" name="custo_fone" class="form-control form-group" placeholder="Enter Mobile No" value="{{ $customer->custo_fone }}" min = "1">
              </div>

              <div class="col-lg-12">
                  <label for="">Address</label>
                  <textarea name="address"class="form-control form-group" placeholder="Enter Address">{{ $customer->custo_address }}</textarea>
              </div>

          </div>

          <div class="row">
              <div class="col-12 text-center my-3 ">
                  <button type = "submit" class="btn btn-danger"> Submit </button>
                  <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
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

<script type="text/javascript">

  $(document).ready(function() {
      $('#state').change(function() {
          var state = $(this).val();
          if (state) {
              $.ajax({
                  url: '/get-districts',
                  type: 'GET',
                  data: { state: state },
                  success: function(response) {
                      $('#district').empty();
                      $('#district').append('<option value="">Select District</option>');
                      $.each(response, function(key, district) {
                          $('#district').append('<option value="' + district.code + '">' + district.name + '</option>');
                      });
                  },
                  error: function(error) {
                      console.log('Error:', error);
                  }
              });
          } else {
              $('#district').empty();
              $('#district').append('<option value="">Select District</option>');
          }
      });
  });

</script>

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
                  $('.btn').prop("disabled", true) ;
                  $('#loader').removeClass('hidden') ;
              },
              success: function(response) {
                  success_sweettoatr(response.success);
                  window.open("{{route('customers.index')}}", '_self');
              },
              error: function(response) {

                  $('input').removeClass('is-invalid');
                  $('.btn-outline-danger').prop("disabled", false);
                  $('.btn').prop("disabled", false);
                  $('#loader').addClass('hidden');

              var errors = response.responseJSON.errors ;

                  if (response.status === 422) {

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

