@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

    //$data = component_array('breadcrumb' , 'Product Image List',[['title' => 'Products']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
	@php 
	$anchor = ['<a href="'.route('ecomproducts.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All Listed</a>'] ;
	$path = ["Ecomm Product"=>route('ecomproducts.index')];
	$data = new_component_array('newbreadcrumb',"Ecomm Product Gallery",$path) ;
	@endphp 
		<x-new-bread-crumb :data=$data :anchor=$anchor/>
    <section class="content">
        <div class="container-fluid">
        <div class="row">
        <!-- left column -->
        <div class="col-md-12">
        <!-- general form elements -->
        <div class="card ">
        <div class="card-header">
        </div>

        <div class="card-body">

        <form action="">
        <div class="row">

        <div class="col-6 col-lg-2 form-group">
        <label for="">Show entries</label>
        @include('layouts.theme.datatable.entry')
        </div>

        </div>

        </form>

<hr class="col-12 m-0 py-2" style="border-top:1px solid lightgray;">
        <div id = "pagination-result"></div>

        </div>
        </div>

        </div>
        </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>

    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
    </div>

@endsection

  @section('javascript')

  @include('layouts.theme.js.datatable')

  <script>

    function getresult(url) {
    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
        },
        success: function (data) {
            $("#pagination-result").html(data.html);
        },
        error: function () {},
    });
    }

    getresult(url);

    $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);

        });

    function changeEntries() {

        getresult(url);

    }

  </script>
<script>
    const maxSizeInMB = 1; // Maximum allowed file size (5 MB)
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
    $(document).on('change','#gall_image',function(){
      const file = event.target.files[0];
      const errorMessage = $('#img_error');
      errorMessage.text(''); // Clear previous errors
      if (file) {
          if(file.size > maxSizeInBytes){
              errorMessage.text("Image Size exceeds "+maxSizeInMB+" MB Limit !");
              $(this).val(''); 
          }else{
              const reader = new FileReader();
              reader.onload = function(e) {
                  $('#gall_img_prev').attr('src',e.target.result);
                 /* $("#gall_img_prev_close").show();*/
              };
              reader.readAsDataURL(file);
              $(document).find("#gall_form").submit();
          }
      }
    });
    
    $(document).on('submit',"#gall_form",function(e){
        e.preventDefault(); // Prevent the default form submission
		$("#gall_image_label").addClass('processing');
        const formData = new FormData(this); // Create FormData object
        const path = $(this).attr('action');
        $.ajax({
          url: path, // Server-side script to handle the upload
          type: 'POST',
          data: formData,
          contentType: false, // Important for file upload
          processData: false, // Prevent jQuery from processing the data
          success: function(response) {
			$("#gall_image_label").removeClass('processing');
            if(response.success){
              toastr.success(response.success);
              location.reload();
            }else{
              toastr.error(response.errors);
            }
          },
          error: function(response) {
			$("#gall_image_label").removeClass('processing');
            $.each(response,function(i,v){
             $("#img_error").empty().text(v);
             $("#img_error").show();
            });
            // $('#response').html('<p style="color:red;">Upload failed.</p>');
          }
        });
    });

</script>
@include('layouts.vendors.js.passwork-popup')
  @endsection
