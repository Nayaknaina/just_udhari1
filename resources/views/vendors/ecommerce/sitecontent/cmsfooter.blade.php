@extends('admin.ecomm.ecommframe')
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
<div class="container" style="padding:100px 0;">
  <div class="row">
       <div class="col-md-12 current_status">
       <form action="{{ url("admin/ecomm/footercontent") }}" method="post" role="form"  id="footer_content_form">
          @csrf
          <div class="form-group col-md-12">
               <label for="intro" class="required_label">Sort info</label>
               <textarea name="intro" class="form-control" value="" id="intro" ></textarea>
          </div>
          <div class="form-group col-md-12">
               <input type="hidden" name="save" value="content">
               <button type="submit" name="save" value="content" id="save" class="btn-primary btn btn-md pull-right">
               Save Content
               </button>
               <a href="{{ url("admin/ecomm/footercontent/status")}}"  id="status" class="btn btn-md btn_status">Change Visibility</a>
          </div>
          </form>
     </div>
     <div id="process_await" class="text-center" style="display:none;">
          <div class="content">
               <li class="fa fa-spinner fa-spin"></li> Processing !
          </div>
     </div>
     </div>
  </div>
</div>
@endsection
@section('js')
  <script>

    const host=  window.location.origin;
    $(document).ready(function(){

      $.get(window.location.href+"/footerdata","",function(response){
           var content = response.content;
           if(content){
               $("#intro").val(content.foot_content);
               $("#status").addClass((content.foot_status==0)?'btn-success':'btn-danger');
               $(".current_status").addClass((content.foot_status==0)?'bg-danger':'bg-success');
           }
        $("#page_loading").hide();
      });
    });


    var requestexist = false;
    $("#footer_content_form").submit(function(e){
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