@extends('layouts.vendors.app')

@section('content')

@php

    //$data = component_array('breadcrumb' , 'Shop Settings',[['title' => 'Shop Settings']] ) ;
    //dd($shopbranch);
@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('setting.mpin','otp').'" class="btn btn-sm btn-outline-secondary" style="float:right;" data-toggle="modal" data-target="#mpin_modal" data-head="Change Mpin !" id="mpin_change"><i class="fa fa-refresh"></i> Change MPIN ?</a>'];
$data = new_component_array('newbreadcrumb',"Shop Settings") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
        <div class="col-md-12">
            <div class="card card-primary">
			{{--<div class="card-header">
                    <h3 class="card-title"> <x-back-button /> Settings</h3>
					<a href="{{ route('setting.mpin','otp') }}" class="btn btn-secondary" style="float:right;" data-toggle="modal" data-target="#mpin_modal" data-head="Change Mpin !" id="mpin_change">Change MPIN ?</a>
                </div>--}}
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title text-dark"> Profile 
                                        </h3><button type = "button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target=".shop_setting_modal" style="float:right"><li class="fa fa-edit"></li> </button>
                                </div>
								
                                <div class="card-body row">
                                    <div class="col-md-5 text-center p-2">
                                        <form action="{{ route('setting.profilephoto',['id'=>$shopbranch->id])}}" id="profile_image_form" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <a href="profile_image_placer" class="btn btn-sm btn-outline-danger" style="position:absolute;right:0;display:none;" id="profile_image_clear">X</a>
                                        <label class="form-control h-auto" for="profile_image" style="cursor:pointer;">
                                            @php 
                                                $prof_foto = ($shopbranch->image!="" && file_exists("{$shopbranch->image}"))?"{$shopbranch->image}":"assets/images/icon/browse.png";
                                            @endphp
                                        <img src="{{asset("{$prof_foto}")}}" class="img-responsive h-auto form-control" id="profile_image_placer"></label>
                                        <input type="file" name="profile_photo" id="profile_image" style="display:none;" accept="image/*">
                                        <button type="submit" name="add" value="foto" id="profile_image_upload" class="btn btn-info profile_image_placer" style="position:absolute;bottom:15px;right:0;display:none;">Save ?</button>
                                        </form>
                                    </div>
                                    <div class="col-md-7 p-2">
                                        <h3> <strong> <u> {{ $shopbranch->branch_name }}</u> </strong> </h3>
										<p class="form-control p-1"> <strong> GSTIN : </strong>  {{  $shopbranch->gst_num }} </p>
                                        <p> <strong> Mobile No : </strong>  {{ $shopbranch->mobile_no }} </p>
                                        <p> <strong> State : </strong> {{ states($shopbranch->state) }} </p>
                                        <p> <strong> District :  </strong> {{ districts($shopbranch->state,$shopbranch->district) }} </p>
                                        <p> <strong> Address : </strong>  {{  $shopbranch->address }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title text-dark"> Password</h3>
                                </div>
                                <div class="card-body p-2">
                                    <form id="password_form" action= "{{ route('settings.store') }}" method="post">
                                        @csrf
                                        <div clas="form-group">
                                            <label for="current" class="m-0">Current </label>
                                            <input type="text" name="current" id="current" class="form-control" placeholder="Current Password">
                                            <small class="text-danger alert-block" id="current_error"></small>
                                        </div>
                                        <div clas="form-group">
                                            <label for="create"  class="m-0">Create</label>
                                            <input type="password" name="create" id="create" class="form-control" placeholder="Create New Password">
                                            <small class="text-danger alert-block" id="create_error"></small>
                                        </div>
                                        <div clas="form-group">
                                            <label for="confirm"  class="m-0">Confirm</label>
                                            <input type="text" name="confirm" id="confirm" class="form-control" placeholder="Confirm Password">
                                            <small class="text-danger alert-block" id="confirm_error"></small>
                                        </div>
                                        <hr class="m-2">
                                        <div clas="form-group" style="text-align:center;">
                                            <button type="submit" class="btn btn-sm btn-danger" name="do" value="login" >Change</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .form-control.invalid{
                border:1px dotted red;
            }
            .alert-block{
                display:none;
            }
        </style>
          <!-- left column -->
          
            
          {{-- <div class="col-lg-6">
            <div class="card card-primary">

                <div class="card-header">
                    <h3 class="card-title"> Other Settings</h3>
                </div>
    
                <div class="card-body">

                <ul class="list-group">
                    <li class="list-group-item">Setting 1 </li> 
                    <li class="list-group-item">Setting 2 </li> 
                    <li class="list-group-item">Setting 3 </li> 
                    <li class="list-group-item">Setting 4 </li> 
                </ul>
          </div>
          </div>
          </div> --}}

      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
</section>

<div class="modal fade shop_setting_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"> Shop Details </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                <form id = "submitForm" method="POST" action="{{ route('settings.update',$shopbranch->id??0)}}" class = "myForm" enctype = "multipart/form-data">

                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-lg-7">
                            <label for="">Shop Name</label>
                            <input type="text" name="shop_name" class="form-control form-group" placeholder="Enter Shop Name" value="{{ old('shop_name', $shopbranch->branch_name) }}">
                        </div>
						<div class="col-lg-5">
                            <label for="" class="text-info">GSTIN</label>
                            <input type="text" name="shop_gst" class="form-control form-group" placeholder="Enter GST Number" value="{{ old('shop_gst', $shopbranch->gst_num) }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="">Mobile No</label>
                            <input type="text" name="mobile_no" class="form-control form-group" placeholder="Enter Mobile No" value="{{ old('mobile_no', $shopbranch->mobile_no) }}">
                        </div>

                        <div class="col-lg-6">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control form-group" placeholder="Enter email id" value="{{ old('email', $shopbranch->email) }}">
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="">State</label>
                                <select name="state" class="form-control select2" id="state">
                                    <option value="">Select State</option>
                                    @foreach (states() as $state)
                                        <option value="{{ $state->code }}" {{ old('state', @$shopbranch->state) == @$state->code ? 'selected' : '' }}> {{ $state->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="">District</label>
                                <select name="district" class="form-control" id="district">
                                    <option value="">Select District</option>
                                    @foreach (districts($shopbranch->state) as $district)
                                        <option value="{{ $district->code }}" {{ old('district', @$shopbranch->district) == @$district->code ? 'selected' : '' }}> {{ $district->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                
                        <div class="col-lg-12">
                            <label for="">Address</label>
                            <textarea name="address" class="form-control form-group" placeholder="Enter Address">{{ old('address', $shopbranch->address) }}</textarea>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 text-center my-3 ">
                            <button type = "submit" class="btn btn-danger"> Update </button> 
                        </div>
                    </div>

                </form>

            </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="mpin_modal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title text-primary" id="mpin_modal_tital">Change MPIN !</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close" onclick="$('#mpin_modal_body').empty();">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-2" id="mpin_modal_body">
        
        </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">

  $(document).ready(function() {
      $('#state').change(function() {
          var state = $(this).val();
          if (state) {
              $.ajax({
                  url: '/get-districts',
                  type: 'GET',
                  data: { state: state },
                  success: function(response) {
                      $('#district').empty();
                      $('#district').append('<option value="">Select District</option>');
                      $.each(response, function(key, district) {
                          $('#district').append('<option value="' + district.code + '">' + district.name + '</option>');
                      });
                  },
                  error: function(error) {
                      console.log('Error:', error);
                  }
              });
          } else {
              $('#district').empty();
              $('#district').append('<option value="">Select District</option>');
          }
      });
  });

</script>

<script>

  $(document).ready(function() {

	$("#profile_image").change(function(e){
        var file = this.files[0];
        var id = $(this).attr('id');
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+id+'_placer').attr('src', e.target.result);
            $("#"+id+"_clear").show();
            $("#"+id+"_upload").show();
        }
        reader.readAsDataURL(this.files[0]);
    });
    $("#profile_image_clear").click(function(e){
        e.preventDefault();
        $("#"+$(this).attr('href')).attr('src',"{{ asset("{$prof_foto}") }}");
        $(this).hide();
        $('.'+$(this).attr('href')).hide();
    });

	$("#profile_image_form").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var fd = new FormData(this);
        var files = $('#profile_image')[0].files[0];
        fd.append('file',files);
        $.ajax({
            url: path,
            type: 'POST',
            data: fd,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {
                  $('#loader').removeClass('hidden') ;
              },
              success: function(response) {
                  // Handle successful update
                  if(response.success){
                      success_sweettoatr(response.success);
					  window.open("{{route('settings.index')}}", '_self');
                  }else{
                    if(response.errors){
                        $.each(response.errors,function(i,v){
                            toastr.error(v);
                        });
                    }else{
                        toastr.error(response['failed']);
                    }
                  }
                  //window.open("{{route('settings.index')}}", '_self');
              },
              error: function(response) {
                
              },
        })
    });
      $('#submitForm').submit(function(e) {
      e.preventDefault(); // Prevent default form submission

      var formAction = $(this).attr('action') ;
      var formData = new FormData(this) ;

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
                  success_sweettoatr(response.success);
                  window.open("{{route('settings.index')}}", '_self');
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
      });

      $('.form-control').focus(function(){
        $(this).removeClass('invalid');
        $(this).next('small').empty().hide();
      });

      $('#password_form').submit(function(e){
        e.preventDefault();
        $('.form-control').removeClass('invalid');
        $('.alert-block').empty().hide();
        $.post($(this).attr('href'),$(this).serialize(),function(response){
            if(response.errors){
                $.each(response.errors,function (i,v){
                    $("#"+i).addClass('invalid');
                    $("#"+i+"_error").text(v).show(); 
                });
            }else{
                if(response.msg){
                    toastr.error(response.msg)
                }else{
                    $("#password_form").trigger('reset');
                    success_sweettoatr(response.success);
                }
            }
        });
      });
	  
	  
	  $("#mpin_change").click(function(){
        $("#mpin_modal_body").load($(this).attr('href'));
      });
  });

</script>

@endsection

