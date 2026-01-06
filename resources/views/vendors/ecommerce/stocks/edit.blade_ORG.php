  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Stocks List',[['title' => 'Stocks']] ) ;

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
            <h3 class="card-title">Edit stock</h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('ecomstocks.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            <div class="row">

                <div class="col-lg-6">
                    <label for=""> Name <span class = "text-danger"> * </span> </label>
                    <input type="text" name="name" class="form-control form-group" placeholder="Enter Product Name" value = "{{ $ecomstock->product_name }}" >
                </div>
                
                <div class="col-lg-3">
                    <label for="">Sell Price <span class = "text-danger"> * </span> </label>
                    <input type="number" name="rate" class="form-control form-group" placeholder="Enter Rate" min = "1" step = "any" value="">
                </div>
                <div class="col-lg-3">
                    <label for="">Strike Price </label>
                    <input type="number" name="strike" class="form-control form-group" placeholder="Enter Rate" min = "1" step = "any" value="">
                </div>
                <div class="col-md-6">
                    <label for="">Weight </label>
                    <input type="text" name="net_weight" class="form-control form-group" placeholder="Enter Rate" value = "{{ $ecomstock->quantity }}" readonly  >
                </div>
                <div class="col-md-6">
                    <label for="">Purchase Amount</label>
                    <input type="text" name="amount" class="form-control form-group" placeholder="Enter Amount" value = "{{ $ecomstock->rate }}" readonly  >
                </div>
                <div class="col-lg-6">
                    <label for=""> Thumbnail Image <span class = "text-danger"> * </span> </label>
                    <input type="file" name = "sr_images" class="form-control form-group" accept="image/*"  >
                </div>

                <div class="col-lg-6">
                    <label for=""> More Images <span class = "text-danger"> (Multiple * )</span> </label>
                    <input type="file" name="more_images[]" class="form-control form-group" multiple accept="image/*" >
                </div>

                <div class="col-lg-12">
                    <label for=""> Description <span class = "text-danger"> * </span> </label>
                    <textarea id = "description" class = "form-control form-group ckeditor" placeholder = "Enter Description"></textarea>
                </div>

                <div class="col-lg-12">
                    <label for=""> Meta Title </label>
                    <input type="text" name="meta_title" class="form-control form-group" placeholder="Enter Meta Title" value = "" >
                </div>

                <div class="col-lg-12">
                    <label for=""> Meta Description </label>
                    <textarea name = "meta_description" class = "form-control form-group " placeholder = "Enter Meta Desciption"></textarea>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger"> Submit </button> 
                    <input type="hidden" name = "stock_id" value = "{{ $ecomstock->id }}">
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

            e.preventDefault(); // Prevent default form submission

            // var description = CKEDITOR.instances['description'].getData() ;
            var formData = new FormData(this) ;
            // formData.append('description', description) ;
            var formAction = $(this).attr('action') ;
			
            var description = CKEDITOR.instances.description.getData();
			
            formData.append('description', description);


            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                $('.btn').prop("disabled", true);
                $('#loader').removeClass('hidden');
                },
                success: function(response) {
                    // Handle successful update
                    toastr.success(response.success);
                    window.open("{{route('ecomstocks.index')}}", '_self');
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

