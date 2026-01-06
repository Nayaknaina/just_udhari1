  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Schemes Info Edit',[['title' => 'Schemes']] ) ;

  @endphp

  <x-page-component :data=$data />

  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <!-- general form elements -->
            <div class="card  card-primary">

                <div class="card-header">
                    <h2 class="card-title">{{ $schemedetail->schemes->scheme_head }}</h2>
                </div>

                <div class="card-body">

                    <form id = "submitForm" method="POST" action="{{ route('shopscheme.update',$schemedetail->id)}}" class = "myForm" enctype="multipart/form-data">

                        @csrf

                        @method('put')

                        <div class="row">

                            <div class="col-lg-8 row">
                                <div class="col-lg-12">
                                    <label for="heading"> Heading <span class = "text-danger"> * </span> </label>
                                    <input type="text" name="heading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:30px;" id="heading" value="{{ $schemedetail->scheme_head??$schemedetail->schemes->scheme_head }}">
                                </div>
                                <div class="col-lg-12">
                                    <label for="subheading"> Sub Heading <span class = "text-danger"> * </span> </label>
                                    <input type="text" name="subheading" class="form-control form-group h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:20px;" id="subheading" value="{{ $schemedetail->scheme_sub_head??$schemedetail->schemes->scheme_sub_head }}">
                                </div>
                            </div>
                            <div class="col-lg-4 pt-4">
                                <label for="scheme_image" class="form-control text-center p-0 bg-gray" style="height:200px;" id="scheme_image_label">
                                    <img src="{{ asset($schemedetail->scheme_img) }}" id="ascheme_prev" class="img-responsive" style="height:inherit;">
                                </label>
                            <input type="file" name="scheme_image" id="scheme_image" style="display:none;" accept="image/*">
                            </div>
                            @if($schemedetail->schemes->scheme_detail_one!="")
                            <div class="col-lg-12 form-group">
                                <label for="detail">Detail One <span class = "text-danger"> * </span> </label>
                                <textarea name="detail_one" id="detail_one" class="form-control  ckeditor" placeholder="Enter description">{!!  $schemedetail->scheme_detail_one??$schemedetail->schemes->scheme_detail_one !!}</textarea>
                            </div>
                            @endif
                            @if($schemedetail->schemes->scheme_detail_two!="")
                            <div class="col-lg-12 form-group">
                                <label for="detail">Detail Two  </label>
                                <textarea name="detail_two" id="detail_two" class="form-control  ckeditor" placeholder="Enter description">{!!  $schemedetail->scheme_detail_two??$schemedetail->schemes->scheme_detail_two !!}</textarea>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-12 text-center my-3 ">
                                <button type = "submit" class="btn btn-danger"> Submit </button>
                            </div>
                        </div>

                    </form>

                </div>

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
  </section>
    <style>
        #scheme_image_label{
            cursor:pointer;
        }
        #scheme_image_label:after{
            content:"+ Image ";
            position:absolute;
            color:lightgray;
            opacity:0.2;
            bottom:0;
            font-size:200%;
            left:15px;
        }
        .cke_notification_warning{
            display:none;
        }
        th>input{
            font-weight:bold!important;
        }
        th{
            position:relative;
        }
    </style>
  @endsection

  @section('javascript')
  @include('layouts.common.ckeditor')
  <script type="text/javascript">

    $(document).ready(function() {
        $("#scheme_image").change(function(){
            var file = $(this).get(0).files[0];
            console.log(file);
            if(file){
                var reader = new FileReader();
                reader.onload = function(){
                    $("#ascheme_prev").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        });
        $('#submitForm').submit(function(e) {
            e.preventDefault();
            var formAction = $(this).attr('action');
            var formData = new FormData(this) ;
            @if($schemedetail->schemes->scheme_detail_one!="")
                var detail_one = CKEDITOR.instances['detail_one'].getData();
                formData.append('detail_one', detail_one);
            @endif
            @if($schemedetail->schemes->scheme_detail_two!="")
                var detail_two = CKEDITOR.instances['detail_two'].getData();
                formData.append('detail_two', detail_two);
            @endif
// Send AJAX request

            $.ajax({
                  url: formAction,
                  type: 'POST',
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  beforeSend: function() {
                      $('.btn').prop("disabled", true) ;
                      $('#loader').removeClass('hidden') ;
                  },
                  success: function(response) {
                      // Handle successful update
                      toastr.success(response.success);
                      window.open("{{route('shopscheme.index')}}", '_self');
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
        })
    });

</script>

  <script>


  </script>

  @endsection

