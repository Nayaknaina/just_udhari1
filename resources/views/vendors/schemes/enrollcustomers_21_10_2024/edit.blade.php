@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Customer Enroll',[['title' => 'Edit Enroll Customer']] ) ;

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
          <h3 class="card-title"><x-back-button /> Edit / Update </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" action="{{ route('enrollcustomer.update',$enrollcustomer->id )}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')

          <div class="row">

              <div class="col-md-8 form-group ">
                  <label for="scheme_id"> Schemes </label>
                  <select name="scheme_id" id = "scheme_id" class="form-control select2">
                      <option value="">Select</option>
                      @foreach ($schemes as $scheme )
                          <option value="{{ $scheme->id }}" {{ ($scheme->ss_status=='0')?'disabled':''; }} {{ ($enrollcustomer->scheme_id == $scheme->id)?"selected":"" }} >{{ $scheme->scheme_head }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="col-md-4 form-group ">
                  <label for="group_id"> Group </label>
                  <select name="group_id" id = "group_id" class="form-control select2">
                      <option value = "">Select</option>
                      @foreach ($groups as $group )
                          <option value="{{ $group->id }}" {{ ($enrollcustomer->group_id == $group->id)?"selected":"" }} >{{ $group->group_name }}</option>
                      @endforeach
                  </select>
              </div>

              <div class="col-lg-8 form-group" id="">
                  <label for="">Customer Name</label>
                  <input type="text" name="customer_name" class="form-control form-group customer_name" placeholder="Customer Name in Scheme" required value="{{ $enrollcustomer->customer_name }}">
              </div>
              <div class="col-lg-4 form-group">
                  <label for="">Assign ID</label>
                  <input type="text" name="assign_id" class="form-control form-group assign_id" placeholder="Assign ID" required value="{{ $enrollcustomer->assign_id }}">
              </div>
              <div class="col-lg-6 form-group">
                  <label for="">Token Amount</label>
                  <input type="text" name="token_amt" class="form-control form-group token_amt" placeholder="Token Amount" required value="{{ $enrollcustomer->token_amt }}">
              </div>
               
              <div class="col-lg-6 form-group">
                  <label for="emi_amt" id="emi_amt_label" class="emi_amt_label">EMI </label>
                  <input type="text" id="emi_amt" name="emi_amt" class="form-control form-group emi_amt" placeholder="Choosed EMI Amount" required value="{{ $enrollcustomer->emi_amnt }}">
              </div>
              <div class="form-group " id="customer_choice_container">
                  <div id="customer_names_container" class="row">
                  </div>
              </div>

          </div>

          <div class="row">
              <div class="col-12 text-center my-3 ">
                  <button type = "submit" class="btn btn-danger"> Update </button> 
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
              window.open("{{route('enrollcustomer.index')}}", '_self');
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

 

  $(document).ready(function() {
   
  $('#scheme_id').on('change', function() {
      var schemeId = $(this).val(); // Get the selected scheme_id
      
      // Clear previous options from the group_id dropdown
      $('#group_id').empty().append('<option value="">Select</option>');

      if (schemeId) {
          $.ajax({
              url: '{{ route("shopschemes.getgroup") }}',
              type: 'GET',
              data: { scheme_id: schemeId },
              success: function(response) {
                  // Populate the group_id dropdown with the received data
                  $.each(response[0], function(index, group) {
                      $('#group_id').append('<option value="' + group.id + '">' + group.group_name + '</option>');
                  });
                  const scheme = response[1];
                  var emi_sugg = '';
                  if(scheme.emi_range_start == scheme.emi_range_end){
                      $(document).find('.emi_range').remove();
                      $(".emi_amt").attr('readonly',true);
                  }else{
                      emi_sugg = '<small class="emi_range text-danger"><b> ( Range '+scheme.emi_range_start+' to '+scheme.emi_range_end+' )</b></small>';
                      $(".emi_amt_label").append(emi_sugg);
                      $(".emi_amt").attr('readonly',false);
                  }
                  $(".emi_amt").val(scheme.emi_range_start);
              },
              error: function(xhr, status, error) {
                  alert('An error occurred while fetching groups.');
              }
          });
      }
  });
});

</script>

@endsection

