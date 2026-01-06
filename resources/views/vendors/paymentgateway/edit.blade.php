@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Payment Gateway',[['title' => 'Payment Gateway']] ) ;

@endphp

<x-page-component :data=$data />
@php 
$anchor = ['<a href="'.route('mygateway.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Payment Gaeways"=>route('mygateway.index')];
$data = new_component_array('newbreadcrumb',"Payment-Gateway Setup",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
          <!-- left column -->
            <div class="col-md-6">
            <!-- general form elements -->
                <div class="card card-default">

                    <div class="card-header">
                        <h3 class="card-title text-secondary">SetUp 
                            
                        </h3>
                        <label class="switch" >
                            <a href="{{ route('mygateway.show',$setting->id) }}" class="gateway_state" id="gateway_state"><span class="slider round {{ ($setting->state=='prod')?'active':'' }}"></span></a>
                            
                        </label>
                        
                    </div>

                    <div class="card-body">

                        <form id = "submitForm" method="POST" action="{{ route('mygateway.update',$setting->id)}}" class = "myForm" >

                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <label class="form-control h-auto">
                                        <img src="{{ asset("{$setting->origin->icon}") }}" class="img-responsive img-thumbnail" style="width:150px;min-width:auto;">
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <ul style="list-style:none;padding:0;">
                                        <li class="col-12" ><label>NAME</label><span style="float:right">{{ $setting->name??'NA' }}</span></li>
                                        <li class="col-12" ><label>TEST URL</label><span style="float:right">{{ $setting->origin->test_url }}</span></li>
                                        <li class="col-12" ><label>PROD URL</label><span style="float:right">{{ $setting->origin->prod_url }}</span></li>
                                    </ul>
                                </div>
                                <hr class="p-1 m-0 col-12">
                                <div class="col-md-12">
                                    <h5>Parameters</h5>
                                    <hr class="p-1 m-0 col-12">
                                    @php 
                                        $param_arr = json_decode($setting->parameter,true);
                                    @endphp
                                    @if(count($param_arr) > 0)
                                    @foreach($param_arr as $plabel=>$pvalue)
                                    <div class="form-group">
                                        <label for="address">{{ $plabel }}</label>
                                        <input type="text" class="form-control" name="{{ $plabel }}" value="{{ $pvalue }}">
                                    </div>
                                    @endforeach
                                    @else 
                                        <div class="alert alert-warning">No Parameters !</div>
                                    @endif
                                </div>
                                <hr class="p-1 m-0 col-12">
                                <div class="col-12 text-center my-3 ">
                                    <button type = "submit" class="btn btn-danger"> Set </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
</section>
<style>

/**The Switch With Anchor */
.switch {
  position: relative;
  display: inline-block;
  width: 22px;
  height: 14px;
  float:right;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ffb845;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "T";
  height: 10px;
  width: 10px;
  left: 1px;
  bottom: 1px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  font-size:8px;
  text-align:center;
  color:red;
}
.slider.active{
    background-color: #09e306;
}
.slider.active:before{
  -webkit-transform: translateX(8px);
  -ms-transform: translateX(8px);
  transform: translateX(8px);
  content:"P";
  color:green;
}
.slider.round {
  border-radius: 34px;
  border:1px solid white;
}

.slider.round:before {
  border-radius: 50%;
} 
/**END The Switch With Anchor */
</style>
@endsection

@section('javascript')

<script>

  $(document).ready(function() {

        $("#gateway_state").click(function(e){
            e.preventDefault();
            var self = $(this).find('span');
            //$(this).find('span').toggleClass('active');
            $.get($(this).attr('href'),"",function(response){
                //alert(response.success);
                if(response.success){
                    self.toggleClass('active');
                    toastr.success(response.success);
                }else{
                    toastr.error(response.error);
                }
            });
        })
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
                  success_sweettoatr(response.success);
                  window.open("{{route('mygateway.index')}}", '_self');
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
  });

</script>

@endsection

