@extends('layouts.vendors.app')

@section('css')

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<style>
   
    #slider_area{

      list-style:none;
      padding:0;
      /* border:1px dashed gray; */

    }

    #home_content_area{

      /* border:1px dashed gray; */

    }

    .slider_item{

      margin:15px 0;

    }

    .slider_item > .card{

      /* background:lightgray; */

    }

    .slider_item > .card > img{

      height: 40vh;
      width: inherit;
      margin: auto;
      width: 100%;
      object-fit: cover;

    }

    .slider_action_btn{

      position:absolute ;
      top : 20px ;

    }

    .slider_vis_btn{

      position:absolute ;
      top: 50px;
      left: 10px;
      padding: 5px 10px 5px 10px;

    }

    ul.slider_action_btn{

      padding:0;
      list-style:none;

    }

    .slider_action_btn:hover{

      opacity:1;

    }

    .slider_edit_btn{

      left: 10px;
      position: absolute;
      top: 5px;
      padding: 5px 10px 5px 10px;

    }

    .slider_del_btn{

      right:15px;
      /* opacity:0.5; */
      top: 20px;
      padding: 5px 10px 5px 10px;

    }

    .disabled{
       border:2px dotted red;
	   opacity:0.3;
    }

</style>

@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sliders List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sliders</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid --
    </section>-->
@php $data = new_component_array('newbreadcrumb',"Ecomm Sliders") @endphp 
<x-new-bread-crumb :data=$data />
<section class="content">
  <div class="container-fluid">

    <div class="row justify-content-center">
      <div class="col-md-6">
      <div class="card ">
      <div class="card-body">
        <form id = "home_slider_form" action="{{route("ecomslider")}}" method="post" role="form" >
        @csrf

        <div class="form-group">
            <label for="image" class="required_label">Slider Image</label> 
            <img src="" class="img-responsive" id="slide_prev" style = "max-height:40vh;height:auto;margin:auto;border:1px solid gray; width: 100%; margin-bottom: 10px;object-fit: cover;">
            <input type="file" name="image" value="" id="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="top">Top Title</label> 
            <input type="text" name="top" value="" id="top" class="form-control">
        </div>

        <div class="form-group">
            <label for="bottom">Bottom Text</label> 
            <input type="text" name="bottom" value="" id="bottom" class="form-control">
        </div>

        <div class="form-group">
            <input type="hidden" name="save" value="slider">
            <button type="submit" name="save" value="slider" id = "save" class="btn-primary btn btn-md">
            Save Slider
            </button>
        </div>
        </form>

      </div>
      </div>
      </div>

      <div class="col-md-12">
        <div class="card ">
            <div class="card-body">
                <ul id="slider_area" class="row">
                </ul>
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

<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>

<script>

    const host =  window.location.origin ;

    $(document).ready(function(){

      $.get("{{ route('ecomslider.loaddata')}}","",function(response){
        if(response.status==true){
            var sliders = response.sliders;
            var content = response.content;
            var count = sliders.length;
            var num = 0;
            $(sliders).each(function(i,v){
              append_slider(v);
              num++;
            });
            if(num==count){
              $("#page_loading").hide();
            }
        }else{
          alert(response.msg) ;
        }
      });
    });

    $("#slider_area").sortable({
            update: function(event, ui) {
            $("#process_await").show();
            var uniaues = [];
            $("#slider_area > li").each(function(i,v){
                uniaues.push($(v).attr('id').replace('slide_',""));
            })
            if(uniaues.length>0){
                var data = $.param({array:uniaues});
                console.log(data);
                $.get("{{ route('ecomslider.orderslider') }}",data,function(response){
                $("#process_await").hide();
                alert(response.msg);
                })
            }
            } //end update        
    })

    $("#image").change(function(){
      var file = $(this).get(0).files[0];
      if(file){
        var reader = new FileReader();
 
        reader.onload = function(){
            $("#slide_prev").attr("src", reader.result);
        }
        reader.readAsDataURL(file);
      }
    })

    var requestexist = false ;

    $("#home_slider_form").submit(function(e){
      e.preventDefault();
      if(!requestexist){
        requestexist = true;
        var formData = new FormData(this);
        var action = $(this).attr('action');
        $.ajax({
          url:action,
          type:'POST',
          data:formData,
          cache:false,
          contentType:false,
          processData:false,
          beforeSend:function(){

          },
          error:function(xhr,status,error){
            requestexist = false;
            var msg = xhr.responseJSON.msg;
            alert(msg);
          },
          success: function (response) {
            requestexist = false;
            if(response.valid==true){
                if(response.status==true){
                  var sliders = response.data;
                  var act = (response.update)?response.update:false;
                  if(!act){
                    $(sliders).each(function(i,v){
                       append_slider(v);
                    })
                  }else{
                    $(document).find("#slide_"+act).remove();
                    append_slider(sliders);
                    $("#home_slider_form").attr('action',"{{ url('ecomslider')}}");
                    $('button[value="slider"]').text('Save Slider');
                  }
                  $("#slide_prev").attr('src',"");
                  $("#home_slider_form").trigger('reset');
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
    })

    function append_slider(slider){

        const routes = {
            editSlide: "{{ route('ecomslider.editslide', ':id') }}",
            slideStatus: "{{ route('ecomslider.slidestatus', ':id') }}",
            deleteSlide: "{{ route('ecomslider.deleteslide', ':id') }}"
        } ;

        const slider_disabled = (slider.slider_status == 0) ? 'disabled' : '';
        var li = '<li class="slider_item col-lg-6" id="slide_' + slider.slider_unique + '">';
        li += '<div class="card text-center ' + slider_disabled + '">';
        li += '<img class="card-img-top img-responsive img-fluid" src="' + host + "/" + slider.slider_image + '" alt="Card image caption">';
        li += '<div class="card-body">';
        li += '<p class="card-text">' + slider.slider_top_text + '</p>';
        li += '<h5 class="card-title text-black col-12">' + slider.slider_bottom_text + '</h5>';
        li += '</div>';
        li += '</div>';
        li += '<ul class="slider_action_btn">';

        // Edit button
        li += '<li><a href="' + routes.editSlide.replace(':id', slider.slider_unique) + '" class="btn btn-xs btn-default slider_edit_btn"><i class="fa fa-edit"></i></a></li>';

        // Visibility button
        const slider_vis = (slider.slider_status == 0) ? 'eye' : 'eye-slash';
        const slider_btn = (slider.slider_status == 0) ? 'success' : 'warning';
        li += '<li><a href="' + routes.slideStatus.replace(':id', slider.slider_unique) + '" class="btn btn-xs btn-' + slider_btn + ' slider_vis_btn"><i class="fa fa-' + slider_vis + '"></i></a></li>';

        li += '</ul>';

        // Delete button
        li += '<a href="' + routes.deleteSlide.replace(':id', slider.slider_unique) + '" class="btn btn-xs btn-danger slider_del_btn slider_action_btn"><i class="fa fa-times"></i></a>';

        li += '</li>';

        // Append the constructed HTML to the slider area
        $("#slider_area").append(li);

    }

    $(document).on('click','.slider_edit_btn',function(e){

      e.preventDefault();
      var url = $(this).attr('href') ;

      if(!requestexist){

        $("#process_await").show() ;
        requestexist = true ;

        const route = "{{ route('ecomslider.update', ':id') }}" ;

        $.get(url,"",function(response){

          if(response.status==true){

            var slide = response.slide ;
            $("#slide_prev").attr('src',host+"/"+slide.slider_image) ;
            $("#top").val(slide.slider_top_text) ;
            $("#bottom").val(slide.slider_bottom_text) ;
            $("#home_slider_form").attr('action', route.replace(':id', slide.slider_unique)) ;
            $('button[value="slider"]').text('Update Slider') ;
            $(window).scrollTop(0) ;

          }else{

            alert(response.msg) ;

          }

            $("#process_await").hide() ;
            requestexist = false ;

        });
      }else{
        alert("Request Already Sent !");
      }
    });

    $(document).on('click','.slider_vis_btn',function(e){
      e.preventDefault();
      var q = confirm("Sure to Change Visibility !");
      if(q==true){
        $("#process_await").show();
        var url = $(this).attr('href');
        var self = $(this);
        if(!requestexist){
          requestexist = true;
          $.get(url,"",function(response){
            requestexist = false;
            if(response.status==true){
              $(document).find("#slide_"+response.slide).find('div.card').toggleClass('disabled');
              self.toggleClass('btn-success btn-warning');
              self.find('i').toggleClass('fa-eye fa-eye-slash');
            }
            $("#process_await").hide();
            alert(response.msg);
          });
        }else{
          alert("Request Already Sent !");
        }
      }
    });

    $(document).on('click','.slider_del_btn',function(e){
      e.preventDefault();
      var q = confirm("Sure to Delete Slide !");
      if(q==true){
        $("#process_await").show();
        var url = $(this).attr('href');
        if(!requestexist){
          requestexist = true;
          $.get(url,"",function(response){
            requestexist = false; 
            if(response.status==true){
              $(document).find("#slide_"+response.slide).remove();
            }
            $("#process_await").hide();
            alert(response.msg);
          })
        }else{
          alert("Request Already Sent !");
        }
      }
    });

</script>

@endsection