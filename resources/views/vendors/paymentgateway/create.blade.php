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
            <h3 class="card-title"> <x-back-button /> Create Customer </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('customers.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">
                <div class="col-md-4 ">
                    <label class="col-12 form-control text-center" for="custo_image" id="custo_image_aimer" style="cursor:pointer;height:250px;min-height:auto;">
                        <img src="{{ asset('assets/images/icon/browse.png') }}" class="img-responsive img-thumbnail img-dummy"  id="custo_image_placer">
                        <a href="#custo_image_placer" id="remove_image">&cross;</a>
                    </label>
                    <input type="file" name="custo_image" id="custo_image" style="display:none;">
                </div>
                <style>
                    #custo_image_placer{
                        max-height:100%;
                        height:auto;
                        margin:auto;
                    }
                    .img-dummy{
                        opacity:0.3;
                    }
                    #remove_image{
                        display:none;
                        position:absolute;
                        right:0;
                        top:0;
                        border:1px solid red;
                        color:red;
                        padding:0 4px;
                        border-radius:50%;
                        background: white;
                        box-shadow: 1px 2px 3px 2px white;
                    }
                    #remove_image:hover{
                        background:red;
                        color:white;
                    }
                </style>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Customer Name<sup class="text-danger">*</sup></label>
                        <input type="text" name="custo_full_name" class="form-control form-group" placeholder="Enter Customer Name">
                    </div>
    
                    <div class="form-group">
                        <label for="custo_fone">Mobile No<sup class="text-danger">*</sup></label>
                        <input type="number" name="custo_fone" class="form-control form-group" placeholder="Enter Mobile No" min = "1" >
                    </div>
    
                    <div class="form-group">
                        <label for="custo_mail">E-Mail</label>
                        <input type="text" name="custo_mail" class="form-control form-group" placeholder="Enter E-Mail Address"  >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control s" placeholder="Enter Address" rows="8"></textarea>
                    </div>
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

        $("#remove_image").click(function(e){
            e.preventDefault();
            var placer = $(this).attr('href');
            $(placer).attr('src',"{{ asset('assets/images/icon/browse.png') }}");
            $(placer).addClass('img-dummy');
            $(this).hide();
        })

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#custo_image_placer').attr('src', e.target.result);
                    $('#custo_image_placer').removeClass('img-dummy');
                    $("#remove_image").show();
                }
    
                reader.readAsDataURL(input.files[0]);
            }
        }
    
        $("#custo_image").change(function(){
            readURL(this);
        });

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
                    // Handle successful update
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

