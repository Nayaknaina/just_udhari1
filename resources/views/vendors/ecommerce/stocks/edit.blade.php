  @extends('layouts.vendors.app')

  @section('content')

  @php
	$anchor_arr = ["<a href='".route('ecomstocks.index')."' class='btn btn-outline-info btn-sm'><i class='fa fa-list'></i> All Unlisted</a>"];
	$path_arr = ["E-Comm Not Listed"=>route('ecomstocks.index')];
	//$data = component_array('newbreadcrumb' , 'Stocks List',[['title' => 'Stocks']] ) ;
	$data = new_component_array('newbreadcrumb' , 'List to Ecomm ',$path_arr ) ;
	
  @endphp
	<x-new-bread-crumb :data=$data :anchor=$anchor_arr />
	{{--<x-page-component :data=$data />--}}

  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('ecomstocks.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            <div class="row">

                <div class="col-lg-6 p-1">
                    <label for=""> Name <span class = "text-danger"> * </span> </label>
                    <input type="text" name="name" class="form-control form-group" placeholder="Enter Product Name" value = "{{ $ecomstock->product_name }}" >
                </div>
                @php 
                    $type = @$ecomstock->item_type??false;
                    if($type && $type=='genuine'){
                        $rate_required = 'no';
                    }else{
                        $rate_required = 'yes';
                    }
                    $qnt_lbl = ($type=='genuine')?'Weight':'Quantity';
                @endphp
                <div class="col-md-2  p-1">
                    <label for="">{{ $qnt_lbl }} <small style="color:blue;">{!! ($qnt_lbl=='Quantity')?'Pcs':'Gm' !!}</small></label>
                    <input type="text" name="net_weight" class="form-control form-group text-center" placeholder="Enter {{ $qnt_lbl }}" value = "{{ $ecomstock->quantity }}" readonly  >
                </div>
                @if($type=='genuine')
                    <div class="col-md-2  p-1">
                        <label for="">Purchase <small style="color:blue;">Rs</small></label>
                        <input type="text" name="amount" class="form-control form-group" placeholder="Enter Amount" value = "{{ $ecomstock->rate }}" readonly  >
                    </div>
                    <div class="col-md-2  p-1">
                        <label for="">Labour <small style="color:blue;">Rs</small></label>
                        <input type="text"  id="rate" name="rate"  class="form-control form-group" placeholder="Enter Amount" value = "{{ $ecomstock->labour_charge }}"  readonly >
                    </div>
                    <div class="col-lg-2  p-1" id="charge">
                        <label for="">Labour <small style="color:blue;">Rs/grm</small></label>
                        <input type="number" id="strike" name="strike" class="form-control form-group" placeholder="Enter Rate" min = "1" step = "any" value="" >
                    </div>
                    <style>
                        #charge:after{
                            content:"E-Comm";
                            margin-bottom: 1rem;
                            position: absolute;
                            top:25px;
                            color:blue;
                            font-size:80%;
                            font-weight:bold;
                        }
                    </style>
                @else 
                    <div class="col-lg-2  p-1 mb-3">
                        <label for="">Sell Price{!! ($type=='artificial')?"<small style='color:blue;'>/Unit</small>":"" !!} <span class = "text-danger"> * </span> </label>
                        <input type="number" id="rate" name="rate" class="form-control form-group m-0" placeholder="Enter Rate" min = "1" step = "any" value="" >
                    </div>
                    <div class="col-lg-2  p-1 mb-3">
                        <label for="">Strike Price{!! ($type=='artificial')?"<small style='color:blue;'>/Unit</small>":"" !!}  </label>
                        <input type="number" id="strike" name="strike" class="form-control form-group m-0" placeholder="Enter Rate" min = "1" step = "any" value="" >
                    </div>
                    <div class="col-md-2  p-1 mb-3">
                        <label for="">Mark Price <small style="color:blue;">Sell</small></label>
                        <input type="text" name="amount" class="form-control form-group m-0" placeholder="Enter Amount" value = "{{ $ecomstock->rate }}" readonly  >
                    </div>
                @endif
                <input type="hidden" name="rate_apply" value="{{ $rate_required }}">
                
                <div class="col-lg-5  p-1 mb-3">
                    <label for=""> Thumbnail Image <small class="text-info"><b>( MAX 1 MB )</b></small>
                    <span class = "text-danger"> * </span> </label>
                    <input type="file" id="sr_images" name = "sr_images" class="form-control form-group m-0" accept="image/*"    id="sr_images">
                    <small class="text-danger" style="display:none;" id="sr_images_size"></small>
                </div>

                <div class="col-lg-5  p-1 mb-3">
                    <label for=""> More Images <span class = "text-danger"><small class="text-info"><b>( MAX 1 MB )</b></small> (Multiple * )</span> </label>
                    <input type="file" name="more_images[]" class="form-control form-group m-0" multiple accept="image/*"  id="more_images">
                    <small class="text-danger" style="display:none;" id="more_images_size"></small>
                </div>

                <div class="col-lg-12 p-1 mb-3">
                    <label for=""> Description <span class = "text-danger"> * </span> </label>
                    <textarea id = "description" class = "form-control form-group m-0" placeholder = "Enter Description" name="description"></textarea>
                </div>

                <div class="col-lg-12 p-1 mb-3">
                    <label for=""> Meta Title </label>
                    <input type="text" name="meta_title" class="form-control form-group m-0" placeholder="Enter Meta Title" value = "" >
                </div>

                <div class="col-lg-12 p-1 mb-3">
                    <label for=""> Meta Description </label>
                    <textarea name="meta_description" class="form-control form-group m-0" placeholder="Enter Meta Desciption"></textarea>
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

@include('layouts.common.editor_96')
<script>

    $(document).ready(function() {
        replaceWithEditor($('#description'));
        // $('#description').each(function() {
        //     replaceWithEditor($(this));
        // });
        $('#submitForm').submit(function(e) {

            e.preventDefault(); // Prevent default form submission

            // var description = CKEDITOR.instances['description'].getData() ;
            var formData = new FormData(this) ;
            // formData.append('description', description) ;
            var formAction = $(this).attr('action') ;
            //var description = CKEDITOR.instances['description'].getData();
            //formData.append('description', description);


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

        const maxSizeInMB = 1; // Maximum allowed file size (5 MB)
        const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
        $("#sr_images").on('change',function(event){
            const file = event.target.files[0];
            const errorMessage = $('#sr_images_size');
            errorMessage.text('').hide(); // Clear previous errors
            if (file) {
                if(file.size > maxSizeInBytes){
                    errorMessage.text("Image Size exceeds "+maxSizeInMB+" MB Limit !");
                    errorMessage.show();
                    $(this).val(''); 
                }
            }
        });

        $("#more_images").on('change',function(event){
            var files = event.target.files;
            const errorMessage = $('#more_images_size');
            errorMessage.text('').hide(); // Clear previous errors
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (file.size > maxSizeInBytes) {
                errorMessage.text("Image "+(i+1)+"'s Size exceeds "+maxSizeInMB+" MB Limit !");
                errorMessage.show();
                // Clear the selected files
                $(this).val('');
                break; // Stop checking after the first error
                }
            }
        });
    });
    
  </script>

  @endsection

