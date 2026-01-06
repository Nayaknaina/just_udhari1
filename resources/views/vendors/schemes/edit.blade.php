  @extends('layouts.vendors.app')

  @section('content')

  {{--@php

  $data = component_array('breadcrumb' , 'Schemes Info Edit',[['title' => 'Schemes']] ) ;

  @endphp
    <x-page-component :data=$data />--}}
  @php 
	$data = new_component_array('newbreadcrumb',"Edit/Update Schemes") 
  @endphp 

	<x-new-bread-crumb :data=$data /> 
  <section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <!-- general form elements -->
            <div class="card">

                <div class="card-header">
                    <h2 class="card-title text-secondary">{{ $schemedetail->schemes->scheme_head }}</h2>
                </div>

                <div class="card-body">

                    <form id = "submitForm" method="POST" action="{{ route('shopscheme.update',$schemedetail->id)}}" class = "myForm" enctype="multipart/form-data">

                        @csrf

                        @method('put')

                        <div class="row">
                            @csrf
                            <div class="col-lg-8 p-0">
                                <div class="col-lg-12 form-group">
                                    <label for="heading"> Heading <span class = "text-danger"> * </span> </label>
                                    <input type="text" name="heading" class="form-control h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:30px;" id="heading" value="{{ $schemedetail->scheme_head??$schemedetail->schemes->scheme_head }}" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="subheading"> Sub Heading <span class = "text-danger"> * </span> </label>
                                    <input type="text" name="subheading" class="form-control h-auto" placeholder="Enter Title" style="font-weight:bold;font-size:20px;" id="subheading" value="{{ $schemedetail->scheme_sub_head??$schemedetail->schemes->scheme_sub_head }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4 pt-4">
                                <label for="scheme_image" class="form-control text-center p-0 bg-gray" style="height:200px;" id="scheme_image_label">
                                    <img src="{{ ($schemedetail->scheme_img!="")?asset($schemedetail->scheme_img):'' }}" id="ascheme_prev" class="img-responsive" style="height:inherit;width:inherit;">
                                </label>
                                <input type="file" name="scheme_image" id="scheme_image" style="display:none;" accept="image/*">
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="validity">Validity <small class="text-danger"><b>(Validity as month) *</b></small></label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="validity" name="validity" value="{{ $schemedetail->scheme_validity }}" required>
                                    <span class="input-group-text" id="basic-addon1"><b>Month</b></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="scheme_amnt">Scheme Amount <small class="text-danger"><b>(Total Amt.) *</b></small></label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="scheme_amnt" name="scheme_amnt" value="{{ $schemedetail->total_amt }}" required>
                                    <span class="input-group-text" id="basic-addon1"><b>Rs.</b></span>
                                </div>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="emi_amnt">EMI Amount <small class="text-danger"><b>(Monthly) *</b></small></label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center" id="emi_amnt_from" name="emi_amnt_from" value="{{ $schemedetail->emi_range_start }}" required>
                                    <span class="input-group-text" id="">to</span>
                                    <input type="number" class="form-control text-center" id="emi_amnt_to" name="emi_amnt_to" value="{{ $schemedetail->emi_range_end}}" required>
                                    <span class="input-group-text" id=""><b>Rs.</b></span>
                                </div>
                            </div>
                            @if($schemedetail->lucky_draw==0 && $schemedetail->scheme_interest=="Yes")
                                <div class="col-md-4 form-group">
                                    <label for="emi_date">EMI Date <small class="text-danger"><b>(EMI Payment DUE date) *</b></small></label>
									<div class="input-group mb-3">
									  <div class="input-group-prepend">
										<span class="input-group-text">Day &nbsp;<b>1</b>&nbsp; to &nbsp;</span>
									  </div>
									  <input type="number" name="emi_date" id="emi_date" value="{{ $schemedetail->emi_date }}" class="form-control text-center" placeholder="Day Digit Only (1-30)" required>
										<span class="input-group-text">&nbsp;Day&nbsp;</span>
									</div>
                                </div>
                                <div class="col-lg-4 form-group" style="" id="interest_block">
                                    <label for="int_value"> Interest Value <span class="text-danger"> * </span></label>
                                    @php 
                                        $int_type = $schemedetail->interest_type;
                                        $int_val = ($int_type=='amt')?$schemedetail->interest_amt:$schemedetail->interest_rate;
                                        $$int_type = "selected";
                                    @endphp
                                    <div class="input-group">
                                        <input type="text" name="interest_value" class="form-control form-group text-center" placeholder="Interest Value" id="int_value" value="{{ $int_val}}">
                                        <span class="input-addon">
                                            <select name="interest_scale" class="form-control" required>
                                                <option value="amt" {{ @$amt }} >Rs.</option>
                                                <option value="per" {{ @$per }} >%</option>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @php 
                                $today = date("Y-m-d",strtotime('now'));
                                $end_date = ($schemedetail->scheme_date!="")?date("Y-m-d",strtotime("{$schemedetail->scheme_date}+{$schemedetail->scheme_validity} Month")):false;
                                $initiated = ($schemedetail->scheme_date!="")?(($schemedetail->scheme_date<=$today)?true:false):null;
                            @endphp
                            <div class="col-md-4">
								@php 
									$schm_launch_date = ($end_date && $end_date <$today)?'':$schemedetail->launch_date;
								@endphp 
                                <label for="launch_date">E-Comm Launch Date <small class="text-danger"><b>(Enroll Start)</b></small></label>
                                <input type="date" name="launch_date" id="launch_date" class="form-control text-center" value="{{ @$schm_launch_date }}" required>
                            </div>
                            @if($schemedetail->scheme_date_fix=='1')
								@php 
									$schm_start_date = ($end_date && $end_date <$today)?'':$schemedetail->scheme_date;
								@endphp 
                            <div class="col-md-4 form-group">
                                <label for="start_date">Scheme Start Date <small class="text-danger"><b>(Enroll End)</b></small></label>
                                <input type="date" name="start_date" id="launch_date" class="form-control text-center" value="{{ @$schm_start_date }}" {{ ($schemedetail->scheme_date_fix=='1')?'required':'' }}>
                            </div>
                            @endif
                            @if($schemedetail->schemes->scheme_detail_one!="")
                            <div class="col-lg-12 form-group">
                                <label for="detail">Description<span class = "text-danger"> * </span> </label>
                                <textarea name="detail_one" id="detail_one" class="form-control  ckeditor" placeholder="Enter description">{!!  $schemedetail->scheme_detail_one??$schemedetail->schemes->scheme_detail_one !!}</textarea>
                            </div>
                            @endif
							<div class="col-lg-12 form-group">
                                <label for="scheme_rule">Rule & Conditions</label>
                                <textarea name="scheme_rule" id="scheme_rule" class="form-control  ckeditor" placeholder="Enter Rules">{!!  @$schemedetail->scheme_rules !!}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center my-3 ">
                            @if($end_date && $end_date <$today)
                                <button type = "submit" class="btn btn-danger"> Relaunch </button>
                                @elseif($initiated)
                                <strong class="btn btn-danger">Initiated</strong>
                                @else
                                <button type = "submit" class="btn btn-danger"> Customise </button>
                            @endif
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
            background:lightgray;
        }
        #scheme_image_label:after{
            content:"+ Image ";
            position:absolute;
            color:gray;
            opacity:0.2;
            bottom:0;
            font-size:200%;
            display:block;
            margin:25%;
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
            if(file){
                var reader = new FileReader();
                $("#scheme_image_label").removeClass('border-danger');
                reader.onload = function(){
                    $("img#ascheme_prev").attr("src", reader.result);
                }
                reader.readAsDataURL(file);
            }
        });
        $('#submitForm').submit(function(e) {
            e.preventDefault();
            var go_ahead = true;
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
            /*var file = $("#scheme_image").get(0).files[0];
            if($("#scheme_image").hasClass('required') && !file){
                $("#scheme_image_label").addClass('border-danger');
                $('html ,body').animate({ scrollTop:$("#scheme_image_label").offset().top});
                go_ahead = false;
            }*/
            
            // Send AJAX request
            if(go_ahead){
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
            }
        })
    });

</script>


  @endsection

