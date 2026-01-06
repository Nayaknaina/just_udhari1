@extends('layouts.vendors.app')

@section('css')
     <style>
     label.radio{
          background:#d7d7d7;
     }
     label.radio.checked{
          background:white;
     }
     </style>
@endsection

@section('content')

@php

$data = component_array('breadcrumb' , 'Contact Information',[['title' => 'Contact']] ) ;

@endphp

<x-page-component :data=$data />
 
<section class="content">
    <div class="container-fluid">

      <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card ">
        <div class="card-body">

      <div class="row">
       <div class="col-md-12 current_status">
       <form action="{{ route("contactinformation") }}" method="post" role="form"  id="contact_content_form">
          @csrf

          <div class="row">

            <div class="form-group col-md-4">
                <label for="emailone" class="required_label"> Website Logo </label>
                <input type="file" name="web_logo" class="form-control" id="web_logo" value="" accept="image/*">
            </div>

            <div class="form-group col-md-4">
                <label for="emailone" class="required_label">Email One</label>
                <input type="email" name="emailone" class="form-control" id="emailone" value="">
            </div>

            <div class="form-group col-md-4">
                <label for="emailtwo">Email Two</label>
                <input type="email" name="emailtwo" class="form-control" value="" id="emailtwo" >
            </div>

            <div class="form-group col-md-6">
                <label for="contactone" class="required_label">Contact One</label>
                <input type="text" name="contactone" class="form-control" value="" id="contactone" minlength="10" maxlength="10">
            </div>

            <div class="form-group col-md-6">
                <label for="contacttwo">Contact Two</label>
                <input type="text" name="contacttwo" class="form-control" value="" id="contacttwo"  minlength="10" maxlength="10">
            </div>

            <div class="form-group col-md-6">
                <label for="greet" class="required_label"> Location </label>
                <textarea name="map_iframe" class="form-control" value="" id="map_iframe" ></textarea>
            </div>

            <div class="form-group col-md-6">
                <label for="greet" class="required_label">Greeting</label>
                <textarea name="greet" class="form-control" value = "" id="greet" ></textarea>
            </div>

            <div class="form-group col-md-12">
                <label for="addr" class="required_label">Address</label>
                <textarea name="addr" class="form-control" value="" id="addr" ></textarea>
            </div>

            <div class="form-group col-md-12 text-center">
                <input type="hidden" name="save" value="content">
                <button type="submit" name="save" value="content" id="save" class="btn-primary btn btn-md pull-right"> Save Content </button>
            </div>
          </div>
          </form>
     </div>

     <div class="col-md-12 current_status" id="contact_curr_status" style="display:none;">
          <hr style="border-top:1px dotted gray;">
     </div>

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
</section>

@endsection

@section('javascript')

  <script>

    const host=  window.location.origin;
    $(document).ready(function(){

      $.get("{{ route('contactinformation.data')}}","",function(response){
           var content = response.content;
           if(content){
               $("#greet").val(content.contact_greet);
               $("#addr").val(content.contact_addr);
               $("#emailone").val(content.contact_email_one);
               $("#emailtwo").val(content.contact_email_two );
               $("#contactone").val(content.contact_fone_one);
               $("#contacttwo").val(content.contact_fone_two);
               $(".radio").removeClass('checked');
               const vis_email = content.contact_email_vis;
               const vis_fone = content.contact_fone_vis;
               $('input[name="emailvis"][value="'+vis_email+'"]').parent('label').addClass('checked');
               $('input[name="emailvis"][value="'+vis_email+'"]').prop('checked',true);
               $('input[name="fonevis"][value="'+vis_fone+'"]').parent('label').addClass('checked');
               $('input[name="fonevis"][value="'+vis_fone+'"]').prop('checked',true);
               $("#contact_curr_status").show();
           }else{
               $("#contact_curr_status").hide();
           }
        $("#page_loading").hide();
      });
    });

    var requestexist = false;
    $("#contact_content_form").submit(function(e){
      e.preventDefault();
      if(!requestexist){
          $("#process_await").show();
          requestexist = true;
          var formData = $(this).serialize();
          var action = $(this).attr('action');
          $.post(action,formData,function(response){
               requestexist = false;
               if(response.valid==true){
                    if(response.status==true){
                        //  $("#status").addClass('btn-success');
                        //  $(".current_status").addClass('bg-danger');
                         $("#contact_curr_status").show();
                    }
                    alert(response.msg);
               }else{
                    alert("Validation Error !");
               }
               $("#process_await").hide();
        });
      }else{
        alert("Request Already Send , Please Wait ");
      }
    });

</script>

@endsection
