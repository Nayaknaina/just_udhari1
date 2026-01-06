@extends('layouts.vendors.app')

@section('content')

@php

//$data = component_array('breadcrumb' , 'About Us',[['title' => 'About Us']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php $data = new_component_array('newbreadcrumb',"Ecomm About") @endphp 
<x-new-bread-crumb :data=$data />
  <section class="content">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-md-12">
            <div class="card ">
            <div class="card-body">
            <form action="{{ route("aboutcontent") }}" method="post" role="form"  id="about_content_form">
                <div class="row">

                @csrf

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="image" class="required_label">  Image </label>
                        <img src = "{{ asset(@$about->about_image) }}" class="img-responsive" id="about_prev" style = "max-height:30vh;height:auto;margin:auto;border:1px solid gray; width: 100%; margin-bottom: 10px;object-fit: cover;">
                        <input type="file" name="image" value="" id="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="short_description"> Introduction / Short Description  </label>
                    <textarea id = "short_description" rows="10" name="short_description" class="form-control">{{ @$about->about_sort }}</textarea>
                </div>

                <div class="form-group col-md-12">
                    <label for="description"> About Us / Detail </label>
                    <textarea id = "description" class="form-control ckeditor">{{ @$about->about_desc }}</textarea>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="meta_title"> Meta Title </label>
                            <textarea id ="meta_title" name="meta_title" class="form-control">{{ @$about->meta_title }}</textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="meta_description"> Meta Description </label>
                            <textarea id = "meta_description" name="meta_description" class="form-control ">{{ @$about->meta_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <input type="hidden" name="save" value="slider">
                    <button type="submit" name="save" value = "content" id="save" class="btn-primary btn btn-md pull-right"> Save Content </button>
                </div>
                </div>
            </form>

        <div id="process_await" class="text-center" style="display:none;">
            <div class="content">
                <li class="fa fa-spinner fa-spin"></li> Processing !
            </div>
        </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</section>

@endsection

@section('javascript')

@include('layouts.common.ckeditor')

<script>

    $("#image").change(function(){
      var file = $(this).get(0).files[0];
      if(file){
        var reader = new FileReader();
        reader.onload = function(){
            $("#about_prev").attr("src", reader.result);
        }
        reader.readAsDataURL(file) ;
      }
    }) ;

    var requestexist = false ;

    $("#about_content_form").submit(function(e){

      e.preventDefault() ;
      if(!requestexist){
        requestexist = true;
        var formData = new FormData(this);
        var action = $(this).attr('action');
        var description = CKEDITOR.instances['description'].getData();
        formData.append('description', description);

        $.ajax({
          url:action,
          type:'POST',
          data:formData,
          cache:false,
          contentType:false,
          processData:false,
          beforeSend:function(){
               $("#process_await").show();
          },
          error:function(xhr,status,error){
            requestexist = false;
            var msg = xhr.responseJSON.msg;
            alert(msg);
          },
          success: function (response) {
            requestexist = false;
            $("#process_await").hide();
            if(response.valid==true){
                if(response.status==true){
                    $("#status").addClass('btn-success');
                    // $("#about_content_form > div").addClass('bg-danger');
                }
                alert(response.msg)
            }else{
              alert("Validation Error !");
            }
          },
        });
      }else{
        alert("Request Already Send , Please Wait ");
      }
    });

    $("#status").click(function(e){
      e.preventDefault();
      var q = confirm("Sure to Change Visibility !");
      if(q==true){
        var url = $(this).attr('href');
        var self = $(this);
        if(!requestexist){
          $("#process_await").show();
          requestexist = true;
          $.get(url,"",function(response){
          $("#process_await").hide();
            requestexist = false;
            if(response.status==true){
              $("#status").toggleClass('btn-success btn-danger');
              $("#about_content_form > div").toggleClass('bg-danger bg-success');
            }
            alert(response.msg);
          });
        }else{
          alert("Request Already Sent !");
        }
      }
    });
  </script>
@endsection
