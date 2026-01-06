@extends('layouts.vendors.app')

@section('css')

    <style>

        label.radio{

            background:#d7d7d7;

        }

        label.radio.checked{

            background:white;

        }
		.switch{
          height:20px;
          width:50px;
          border:1px solid lightgray;
          display:block;
          float:right;
          position: relative;
        }
        .switch:after{
          width:100%; 
          position:absolute;  
          height:100%; 
          text-align:center;    
          font-size:70%;
          padding:1px;
        }
        .switch.on:after{
          content:"Online";
          background:lightgreen;
          right:0;
          color:green;
        }
        .switch.off:after{
          content:"Offline";
          background:pink;
          left:0;
          color:red;
        }
        #term_content_form.deactive{
          border:1px dashed red;
        }
    </style>

@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Terms & Conditions',[['title' => 'Pages']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php $data = new_component_array('newbreadcrumb',"Ecomm Tern&Cond") @endphp 
<x-new-bread-crumb :data=$data /> 
<section class="content">
<div class="container-fluid">

  <div class="row justify-content-center">
    <div class="col-md-12">
    <div class="card ">
    <div class="card-body">
        <div class="row">
       <div class="col-md-12 current_status">

       <form action="{{ route("termsandconditions") }}" method="post" role="form" id = "term_content_form"  class="{{ (@$existterm->term_status==0)?'deactive':'' }}">

        @csrf

          <div class="form-group col-md-12">
               <label for="intro" class="required_label col-12">Terms & Conditions <a href="{{ route("termsandconditions.status") }}" class="switch {{ (@$existterm->term_status==0)?'off':'on' }}"></a></label>
               <textarea class="form-control ckeditor" id = "info" >{{ @$existterm->term_content }}</textarea>
          </div>

          <div class="form-group col-md-12 text-center">
               <input type="hidden" name="save" value="content">
               <button type="submit" name="save" value="content" id="save" class="btn-primary btn btn-md pull-right">
               Save Content
               </button>
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
</div>
</div>
</section>

@endsection

@section('javascript')

@include('layouts.common.ckeditor')

  <script>

    var requestexist = false;
    $("#term_content_form").submit(function(e){
      e.preventDefault();
      if(!requestexist){
          $("#process_await").show();
        //   requestexist = true;

        var action = $(this).attr('action');
        var formData = new FormData(this);
          var info = CKEDITOR.instances['info'].getData();
          formData.append('info', info) ;

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

        alert("Request Already Send , Please Wait ") ;

      }
    });


	 $('.switch').click(function(e){
      e.preventDefault();
      const self = $(this);
      $.get($(this).attr('href'),"",function(response){
        if(response.status){
          self.toggleClass('on off');
          $('form').toggleClass('deactive');
        }
      })
    });
  </script>

@endsection
