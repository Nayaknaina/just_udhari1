  @extends('layouts.vendors.app')

  @section('content')

  
@php
$anchor_arr = ["<a href='".route('catalogues.index')."' class='btn btn-outline-info btn-sm'><i class='fa fa-list'></i> All</a>"];
$path_arr = ["Catelogue"=>route('catalogues.index')];
//$data = component_array('breadcrumb' , 'Catalogues',[['title' => 'Catalogues']] ) ;
$data = new_component_array('newbreadcrumb' , 'New Catalogue',$path_arr ) ;
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

            <!--<div class="card-header">
            <h3 class="card-title"> <x-back-button /> Create </h3>
            </div>-->

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('catalogues.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row">

                <div class="col-lg-12">
                    <label for=""> Catalogue Name </label>
                    <input type="text" name = "name" class="form-control form-group" placeholder="Enter Catalogue Name">
                </div>

                <div class="col-lg-6 mb-3">
                    <label for=""> Catalogue Thumnail Image </label>
                    <input type = "file" name="ct_images" class="form-control form-group m-0" accept="image/*" id="ct_images">
					<small class="text-danger" style="display:none;" id="ct_images_size"></small>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for=""> Catalogue More Image </label>
                    <input type = "file" name="mr_images[]" class="form-control form-group m-0" accept="image/*" multiple id="mr_images">
					<small class="text-danger" style="display:none;" id="mr_images_size"></small>
                </div>

                <div class="col-lg-6">
                    <label for=""> Weight </label>
                    <input type = "number" name = "weight" class="form-control form-group" placeholder = "Enter Weight"  min = '1' step='any' onkeyup="limitDecimalPlaces(this, 3)" >
                </div>

                <div class="col-lg-6 dss" >
                    <label for=""> Price </label>
                    <input  id="short_order_input" type = "number" name = "price" class="form-control form-group" placeholder = "Enter Price" min = '1'  step='any' onkeyup="limitDecimalPlaces(this, 2)" >
                </div>

                <div class="col-lg-6">
                    <div class = "form-group" >
                    <label for=""> Metal </label>
                    <select name = "metals" class = "form-control select2 ">
                        <option value=""> Select </option>
                     @foreach ($metals as $metal)
                        <option value = "{{ $metal->id }}"> {{ $metal->name }} </option>
                     @endforeach
                    </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class = "form-group" >
                    <label for=""> Collections </label>
                    <select name = "collections" class = "form-control select2 " >
                        <option value=""> Select </option>
                     @foreach ($collections as $collection)
                        <option value = "{{ $collection->id }}"> {{ $collection->name }} </option>
                     @endforeach
                    </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class = "form-group" >
                    <label for=""> Category </label>
                    <select name = "categories" class = "form-control select2 ">
                        <option value=""> Select </option>
                     @foreach ($categories as $category)
                        <option value = "{{ $category->id }}"> {{ $category->name }} </option>
                     @endforeach
                    </select>
                    </div>
                </div>

                <div class="col-lg-6">
                    <label for=""> Show Order </label>
                    <input type="number" name="short_order" class="form-control form-group" placeholder="Enter Show Order" min='1' step='1' >
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

<script>

    function limitDecimalPlaces(element, places) {

        let value = element.value;
        let regex = new RegExp(`^(\\d*\\.\\d{0,${places}}).*$`);
        let match = value.match(regex);
        if (match) {
            element.value = match[1];
        }

    }

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
				// $('.btn').prop("disabled", true);
				// $('#loader').removeClass('hidden');
				},
				success: function(response) {
				// Handle successful update
				success_sweettoatr(response.success);
				window.open("{{route('catalogues.index')}}", '_self');
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
		
		
    const maxSizeInMB = 1; // Maximum allowed file size (5 MB)
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
    $("#ct_images").on('change',function(event){
        const file = event.target.files[0];
        const errorMessage = $('#ct_images_size');
        errorMessage.text('').hide(); // Clear previous errors
        if (file) {
            if(file.size > maxSizeInBytes){
                errorMessage.text("Image Size exceeds "+maxSizeInMB+" MB Limit !");
                errorMessage.show();
                $(this).val(''); 
            }
        }
    });

    $("#mr_images").on('change',function(event){
        var files = event.target.files;
        const errorMessage = $('#mr_images_size');
        errorMessage.text('').hide(); // Clear previous errors
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if (file.size > maxSizeInBytes) {
            errorMessage.text("Image "+(i+1)+" Size exceeds "+maxSizeInMB+" MB Limit !");
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

