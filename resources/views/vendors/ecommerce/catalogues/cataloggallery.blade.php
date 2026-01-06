@extends('layouts.vendors.app')

@section('content')

@php

//$data = component_array('breadcrumb' , 'Ecommerce Product List',[['title' => 'Ecommerce Product']] ) ;
$anchor  = ["<a href='".route('catalogues.index')."' class='btn btn-sm btn-outline-info'><i class='fa fa-list'></i> All</a>"];
$path = ["Catelogue"=>route('catalogues.index')];
$data = new_component_array('newbreadcrumb' , 'Catelogue Gallery',$path ) ; 

@endphp
<x-new-bread-crumb :data=$data :anchor=$anchor />
 {{--<x-page-component :data=$data />--}}
 <style>
  #gall_image_label{
      position:relative;
      background: #d3d3d38c;
      border: 1px dashed gray;
      align-content:center;
  }
  #gall_image_label>span{
      position:absolute;
      font-size:200%;
      top:0;
      left:0;
      transform: rotate(45deg);
      text-shadow: 1px 2px 3px lightgray;
  }
  #gall_img_prev_close{
      position:absolute;
      right:0;
      top:0;
      display:none;
  }
  #gall_image_label.processing >i{
      width: 100%;
      position: absolute;
      height: 100%;
      top: 0;
      left: 0;
      align-content: center;
      background: #000000ab;
      color: white;
      display:block;
  }
</style> 
 
  <section class="content">
    <div class="container-fluid">
      <div class="row">
      <!-- left column -->
        <div class="col-md-12">
        <!-- general form elements -->
          <div class="card card-primary">
            <!--<div class="card-header">
              <h3 class="card-title"><x-back-button />  Cataloge Gallery </h3>
            </div>-->
            <div class="card-body">
              <div class = "row">
                @php 
                  $catgall = $catalogue->catalogeimages;
                @endphp
				
				<label class="col-12 col-xs-12 col-md-3 text-center btn btn-default m-0" style="min-height:200px;height:auto;" for="gall_image" id="gall_image_label">
                  <span>&#128206;</span>
                  <img src="" alt="Upload Image" id="gall_img_prev" class="img-thumbnail">
                      <a href="javascript:void(null);"  id="gall_img_prev_close" onclick="$('#gall_img_prev').attr('src','');$(this).hide();" class="btn btn-sm btn-outline-danger">&cross;</a>
                  <form name="gall_form" action="{{ route('catalogues.gallery') }}" id="gall_form" enctype="multipart/form-data">
                      @csrf
                      <input type="file" name="gall_image" style="display:none;" id="gall_image">
                      <input type="hidden" name="cat_id" value="{{ $catalogue->id }}">
                  </form>
                  <small id="img_error" class="text-danger" style="font-weight:bold;"></small>
                  <i style="display:none;"><span class="fa fa-spinner fa-spin"></span>Processing...</i>
              </label>
				
                @if($catgall->count()>0)
                  @foreach($catgall as $ctg => $gll)
                    <div class="col-md-3 col-6 gall_block">
                      <a href="{{ route('gallery.delete',$gll->id) }}" class="btn btn-sm btn-danger gall_image_delete" style="position:absolute;">&cross;</a>
                      <img src="{{ asset("ecom/cataloge/{$gll->images}") }}" class="img-responsive img-thumbnail img-fluid">
                    </div>
                  @endforeach
                @else 
                  <div class="col-md-4 col-12 alert alert-warning text-center m-auto">
                    No Cataloge Gallery !
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </div><!-- /.container-fluid -->
    </div>
  </section>
  
@endsection

@section('javascript')
<script>
  $(".gall_image_delete").click(function(e){
    e.preventDefault();
    const div = $(this).closest('div.gall_block');
    $.get($(this).attr('href'),"",function(response){
      if(response.success){
        div.remove();
        success_sweettoatr(response.success);
      }else{
        toastr.error(response.error) ;
      }
    });
  });
  
  
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
    $("#gall_image_label").addClass('processing');
    e.preventDefault(); // Prevent the default form submission
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
          $("#img_error").show
        });
        // $('#response').html('<p style="color:red;">Upload failed.</p>');
      }
    });
  });
</script>
@endsection