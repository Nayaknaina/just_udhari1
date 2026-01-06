  @extends('layouts.admin.app')

  @section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>New Scheme</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Scheme</li>
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
            <h3 class="card-title"> <x-back-button /> Create </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" action="{{ route('schemes.store')}}" class = "myForm" enctype="multipart/form-data">

                @csrf

                @method('post')

                <div class="row">

                    <div class="col-lg-12">
                        <label for="heading"> Heading <span class = "text-danger"> * </span> </label>
                        <input type="text" name="heading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:30px;" id="heading">
                    </div>

                    <div class="col-lg-12">
                        <label for="subheading"> Sub Heading <span class = "text-danger"> * </span> </label>
                        <input type="text" name="subheading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:20px;" id="subheading">
                    </div>

                    <div class="col-lg-3 form-group ">
                        <label for="valid"> Validity <span class = "text-danger"> (Validity as month) * </span></label>
                        <div class="input-group">
                            <input type="number" name ="valid" id = "valid" class="form-control form-group text-center"  placeholder = "Enter Scheme Validity" min = "1" >
                        </div>
                    </div>

                    <div class="col-lg-3 form-group ">
                        <label for="valid"> Scheme Amount <span class = "text-danger"> (Total Amt.) * </span></label>
                        <div class="input-group">
                            <input type="number" name ="scheme_amt" id = "scheme_amt" class="form-control form-group text-center"  placeholder = "Enter Scheme Amount" min = "1" >
                        </div>
                    </div>

                    <div class="col-lg-3 form-group">
                        <label for="emi"> EMI Amt <span class = "text-danger"> (Monthly) * </span></label>
                        <input type="number" name="emi_amt" id="emi_amt" class="form-control form-group text-center"  placeholder="Amount" min = "1">
                    </div>

                    <div class="col-lg-3 form-group">
                        <label for="start"> Start Date  <span class = "text-danger"> * </span></label>
                        <div class="form-inline">
                            <label for="start_fix" class="form-control col-6 text-center start">
                                <input type="radio" name="start" value="1" id="start_fix"  checked> FIXED 
                            </label>
                            <label for="start_enroll"  class="form-control col-6 text-center start">
                                <input type="radio" name="start" value="0" id="start_enroll" > ENROLLED
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-4 form-group">
                        <label for="draw"> Lucky Draw <span class = "text-danger"> * </span></label>
                        <!-- <select name="lucky_draw" class="form-control" id="lucky_draw" onchange="toggleInterestSection()">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select> -->
                        <div class="form-inline">
                            <label for="lucky_draw_no" class="form-control col-6 text-center lucky_draw"><input type="radio" name="lucky_draw" value="0" id="lucky_draw_no" onclick="toggleinterest()"   checked required> No </label>
                            <label for="lucky_draw_yes"  class="form-control col-6 text-center lucky_draw"><input type="radio" name="lucky_draw" value="1" id="lucky_draw_yes" onclick="toggleinterest()" required> Yes</label>
                        </div>
                    </div>

                    <div class="col-lg-4 form-groups" id = "interest_main_sec" style = "display:block;" >
                        <label for="interest">Interest <span class = "text-danger"> * </span><small class="text-primary">( On EMI Pay )</small></label>
                        <div class="form-inline">
                            <label for="interest_no" class="form-control col-6 text-center"><input type="radio" name="interest" value="No" id="interest_no"  onclick="if($(this).is(':checked')){ $('#interest_block').hide(); }" checked required> No </label>
                            <label for="interest_yes"  class="form-control col-6 text-center"><input type="radio" name="interest" value="Yes" id="interest_yes" onclick="if($(this).is(':checked')){ $('#interest_block').show(); }" required> Yes</label>
                        </div>
                    </div>

                    <div class="col-lg-4 form-group" style="display:none;" id="interest_block">
                        <label for=""> Interest Value <span class = "text-danger"> * </span></label>
                        <div class="input-group">
                            <input type="text" name="interest_value" class="form-control form-group text-center"  placeholder="Interest Value">
                            <span class="input-addon">
                                <select name="interest_scale" class="form-control">
                                    <option value="per" selected >%</option>
                                    <option value="amt" >Rs.</option>
                                </select>
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-12 form-group">
                        <label for="detail">Description <span class = "text-danger"> * </span> </label>
                        <textarea name="description" id="description" class="form-control  ckeditor" placeholder="Enter description"></textarea>
                    </div>

                    <div class="col-lg-12">
                        <label for=""> Meta Title  <span class = "text-danger"> * </span> </label>
                        <input type = "text" name="meta_title" class="form-control form-group" placeholder=" Meta Title"  >
                    </div>

                    <div class="col-lg-12">
                        <label for="">Meta Description  <span class = "text-danger"> * </span> </label>
                        <textarea name="meta_description" class="form-control form-group" placeholder="Enter Meta Description"></textarea>
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

