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

            <form id = "submitForm" method="POST" action="{{ route('schemes.store')}}" class = "myForm" enctype="multipart/form-data">

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
                    <label for="valid"> Validity <span class = "text-danger"> * </span></label>
                    <div class="input-group">
                        <input type="text" name="valid" id="valid" class="form-control form-group text-center"  placeholder="Number Only">
                        <span class="input-addon">
                            <select name="valid_scale" class="form-control">
                                <option value="d">Day</option>
                                <option value="m" selected >Month</option>
                                <option value="y">Year</option>
                            </select>
                        </span>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <label for="emi">EMI <span class = "text-danger"> * </span></label>
                    <input type="text" name="emi" id="emi" class="form-control form-group text-center"  placeholder="Amount">
                </div>
                <div class="col-lg-3 form-groups">
                    <label for="interest">Interest <span class = "text-danger"> * </span><small class="text-primary">( On EMI Pay )</small></label>
                    <div class="form-inline">
                        <label for="interest_no" class="form-control col-6 text-center"><input type="radio" name="interest" value="0" id="interest_no" checked onClick="if($(this).is(':checked')){ $('#interest_block').hide(); }"> NO</label>
                        <label for="interest_yes"  class="form-control col-6 text-center"><input type="radio" name="interest" value="1" id="interest_yes" onClick="if($(this).is(':checked')){ $('#interest_block').show(); }"> Yes</label>
                    </div>
                </div>
                <div class="col-lg-3 form-group" style="display:none;" id="interest_block">
                    <label for=""> Interest Value <span class = "text-danger"> * </span></label>
                    <div class="input-group">
                        <input type="text" name="interest_value" class="form-control form-group text-center"  placeholder="Number Only">
                        <span class="input-addon">
                            <select name="interest_scale" class="form-control">
                                <option value="amnt" selected s>Rs.</option>
                                <option value="perc"  >%</option>
                            </select>
                        </span>
                    </div>
                </div>
                <!-- <div class="col-lg-6">
                    <label for=""> Thumbnail Image </label>
                    <input type="file" name="thumbnail_image" class="form-control form-group"  >
                </div>

                <div class="col-lg-6">
                    <label for=""> Banner Image </label>
                    <input type="file" name="banner_image1" class="form-control form-group"  >
                </div> -->

                <div class="col-lg-12 form-group">
                    <label for="detail">Detail  <span class = "text-danger"> * </span> </label>
                    <textarea name="detail" id="detail" class="form-control  ckeditor" placeholder="Enter description"></textarea>
                </div>
                <div class="col-12 form-group">
                    <label for="detail">Table Detail </label>
                    <div class="table-responsive">
                    </div>
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

    <script>

        $(document).ready(function() {

            $('#submitForm').submit(function(e) {
            e.preventDefault() ; // Prevent default form submission

                var formAction = $(this).attr('action');
                var detail = CKEDITOR.instances['detail'].getData();
                var formData = new FormData(this) ;
                formData.append('detail', detail);

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
        });

  </script>

  @endsection

