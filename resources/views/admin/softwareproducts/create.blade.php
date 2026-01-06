  @extends('layouts.admin.app')

  @section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Software</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Product</li>
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

            <form id = "submitForm" method="POST" action="{{ route('softwareproducts.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-6">
                    <label for=""> Title <span class = "text-danger"> * </span> </label>
                    <input type="text" name="title" class="form-control form-group" placeholder="Enter Title">
                </div>

                <div class="col-lg-6">
                    <label for="">Price  <span class = "text-danger"> * </span>  </label>
                    <input type="number" name="price" class="form-control form-group" placeholder="Enter Price">
                </div>

                <div class="col-lg-6">
                    <label for=""> Thumbnail Image </label>
                    <input type="file" name="thumbnail_image" class="form-control form-group"  >
                </div>

                <div class="col-lg-6">
                    <label for=""> Banner Image </label>
                    <input type="file" name="banner_image1" class="form-control form-group"  >
                </div>

                <div class="col-lg-12 form-group">
                    <label for="">Description  <span class = "text-danger"> * </span> </label>
                    <textarea name="description" class="form-control  ckeditor" placeholder="Enter description"></textarea>
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
                var description = CKEDITOR.instances['description'].getData();
                var formData = new FormData(this) ;
                formData.append('description', description);

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
                        window.open("{{route('softwareproducts.index')}}", '_self');
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

