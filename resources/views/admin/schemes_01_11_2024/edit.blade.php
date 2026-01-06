@extends('layouts.admin.app')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Scheme</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Schemes </li>
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
          <h3 class="card-title"> <x-back-button /> Update </h3>
          </div>

          <div class="card-body">

          <form id = "submitForm" action="{{ route('schemes.update',$scheme->id)}}" class = "myForm" enctype="multipart/form-data">

              @csrf

              @method('put')

              <div class="row">

                <div class="col-lg-12">
                    <label for="heading"> Heading <span class = "text-danger"> * </span> </label>
                    <input type="text" name="heading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:30px;" id="heading" value="{{ $scheme->scheme_head }}">
                </div>

                <div class="col-lg-12">
                    <label for="subheading"> Sub Heading <span class = "text-danger"> * </span> </label>
                    <input type="text" name="subheading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:20px;" id="subheading" value="{{ $scheme->scheme_sub_head }}">
                </div>

                  <div class="col-lg-4 form-group ">
                      <label for="valid"> Validity <span class = "text-danger"> (Validity as month) * </span></label>
                      <div class="input-group">
                          <input type="number" name ="valid" id = "valid" class="form-control form-group text-center"  placeholder = "Enter Scheme Validity" min = "1" value="{{ $scheme->scheme_validity }}" >
                      </div>
                  </div>

                  <div class="col-lg-4 form-group ">
                      <label for="valid"> Scheme Amount <span class = "text-danger"> (Total Amt.) * </span></label>
                      <div class="input-group">
                          <input type="number" name ="scheme_amt" id = "scheme_amt" class="form-control form-group text-center"  placeholder = "Enter Scheme Amount" min = "1" value="{{ $scheme->scheme_amount }}" >
                      </div>
                  </div>

                  <div class="col-lg-4 form-group">
                      <label for="emi"> EMI Amt <span class = "text-danger"> ( (Monthly)) * </span></label>
                      <input type="number" name="emi_amt" id="emi_amt" class="form-control form-group text-center"  placeholder="Amount" min = "1" value="{{ $scheme->scheme_emi }}">
                  </div>

                  <div class="col-lg-4 form-group">
                    <label for="draw"> Lucky Draw <span class = "text-danger"> * </span></label>
                    <select name="lucky_draw" class="form-control" id="lucky_draw" onchange="toggleInterestSection()">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

                  <div class="col-lg-4 form-groups" id = "interest_main_sec" style = "display:block;" >
                      <label for="interest">Interest <span class = "text-danger"> * </span><small class="text-primary">( On EMI Pay )</small></label>
                      <div class="form-inline">
                          <label for="interest_no" class="form-control col-6 text-center"><input type="radio" name="interest" value="No" id="interest_no" checked onClick="if($(this).is(':checked')){ $('#interest_block').hide(); }" @if($scheme->interest_type=='No') checked @endif > No</label>
                          <label for="interest_yes"  class="form-control col-6 text-center"><input type="radio" name="interest" value="Yes" id="interest_yes" onClick="if($(this).is(':checked')){ $('#interest_block').show(); }" @if($scheme->interest_type=='Yes') checked @endif > Yes</label>
                      </div>
                  </div>

                  <div class="col-lg-4 form-group" style="display:@if($scheme->interest_type=='Yes') block @else none @endif;" id="interest_block">
                      <label for=""> Interest Value <span class = "text-danger"> * </span></label>
                      <div class="input-group">
                          <input type="text" name="interest_value" class="form-control form-group text-center"  placeholder="Interest Value" value="{{ scheme_interest_show($scheme->scheme_interest , $scheme->scheme_interest_scale ,$scheme->scheme_emi ,$scheme->scheme_interest_value ) }}" >
                          <span class="input-addon">
                              <select name="interest_scale" class="form-control">
                                  <option value="amt" @if($scheme->scheme_interest_scale=='amt') selected @endif>Rs.</option>
                                  <option value="per" @if($scheme->scheme_interest_scale=='per') selected @endif >%</option>
                              </select>
                          </span>
                      </div>
                  </div>

                  <div class="col-lg-12 form-group">
                      <label for="detail">Description <span class = "text-danger"> * </span> </label>
                      <textarea name="description" id="description" class="form-control  ckeditor" placeholder="Enter description">{{ $scheme->scheme_detail_one }}</textarea>
                  </div>

                  <div class="col-lg-12">
                      <label for=""> Meta Title  <span class = "text-danger"> * </span> </label>
                      <input type = "text" name="meta_title" class="form-control form-group" placeholder=" Meta Title" value="{{ $scheme->meta_title }}" >
                  </div>

                  <div class="col-lg-12">
                      <label for="">Meta Description  <span class = "text-danger"> * </span> </label>
                      <textarea name="meta_description" class="form-control form-group" placeholder="Enter Meta Description">{{ $scheme->meta_description }}</textarea>
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

@include('admin.schemes.content.js')

  <script>

          $('#submitForm').submit(function(e) {
              e.preventDefault() ; // Prevent default form submission
              var formAction = $(this).attr('action');
              var description = CKEDITOR.instances['description'].getData();
              var formData = new FormData(this) ;
              formData.append('description', description);

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
                      window.open("{{route('schemes.index')}}", '_self');
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

</script>

@endsection
