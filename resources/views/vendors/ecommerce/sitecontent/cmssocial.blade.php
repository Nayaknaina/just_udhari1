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

//$data = component_array('breadcrumb' , 'Social Links',[['title' => 'Social Links']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
 
@php $data = new_component_array('newbreadcrumb',"Ecomm Social Links") @endphp 
<x-new-bread-crumb :data=$data />
<section class="content">
 <div class="container-fluid">

   <div class="row justify-content-center">
     <div class="col-md-6">
     <div class="card ">
     <div class="card-body">
       <form action="{{ route("sociallink") }}" method="post" role="form"  id="social_content_form">
          @csrf
          <div class="form-group col-md-12">
               <label >Facebook</label>
               <input type="url" name="social[facebook]" class="form-control" id="facebook" value="">
          </div>
          <div class="form-group col-md-12">
               <label>Twitter</label>
               <input type="url" name="social[twitter]" class="form-control" id="twitter" value="">
          </div>
          <div class="form-group col-md-12">
               <label >LinkedIn</label>
               <input type="url" name="social[linkedin]" class="form-control" id="linkedin" value="">
          </div>
          <div class="form-group col-md-12">
               <label >Instagram</label>
               <input type="url" name="social[instagram]" class="form-control" id="instagram" value="">
          </div>
          <div class="form-group col-md-12">
               <label for="youtube">YouTube</label>
               <input type="url" name="social[youtube]" class="form-control" id="youtube" value="">
          </div>
          <div class="form-group col-md-12 text-center">
               <input type="hidden" name="save" value="content">
               <button type="submit" name="save" value="content" id="save" class="btn-primary btn btn-md pull-right"> Save Content </button>
               {{-- <a href="{{ route("sociallink.status")}}"  id="status" class="btn btn-md btn_status">Change Visibility</a> --}}
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

<script>

    const host=  window.location.origin;
    $(document).ready(function(){

      $.get("{{ route('sociallink.data')}}","",function(response){
          var content = response.content;
          var status = 0;
           if(content){
                $.each(content,function(i,v){
                    if(i==0){
                         status = v.social_status;
                    }
                    $("#"+v.social_icon_name).val(v.social_link) ;

                })
            //    $("#status").addClass((status==0)?'btn-success':'btn-danger');
            //    $(".current_status").addClass((status==0)?'bg-danger':'bg-success');

           }
        $("#page_loading").hide();
      });
    });


    var requestexist = false;
    $("#social_content_form").submit(function(e){
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
                         $("#status").addClass('btn-success');
                         $(".current_status").addClass('bg-danger');
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
              $(".current_status").toggleClass('bg-danger bg-success');
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
