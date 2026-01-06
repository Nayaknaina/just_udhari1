@extends('ecomm.site')

@section('title', "Registration")
@php

$info = $common_content['info'] ;
$socials = $common_content['socials'] ;

$social_true = (!$socials) ? false : true ;
$info_true = (!$info) ? false : true ;

if($info_true){

    $web_title = $info['title'] ;
    $head_mail = $info['email'] ;
    $head_fone = "+91-".$info['mobile_no'] ;
    $logo = "assets/ecomm/logos/".$info['logo'] ;
    $address = $info['address'] ;
  $web_color = $info['web_color'] ;

}else{

    $web_title = '' ; 
    $head_mail = 'example@gamil.com' ;
    $head_fone = "+91-9876543210";
    $logo =  'assets/ecomm/logos/no_logo.png';
    $address =  '23/4 Bhopal MP 404133 ';
    $web_color =  'black';

}

@endphp

@section('stylesheet')

<style>

    #block_await{
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:#dfdedee3;
        padding-top:100px;
    }
    #block_await > .content{
        margin:auto;
        width:max-content;
        color:gray;
        text-align:center;
        font-size:3rem;
        padding:5px;
        /* border:1px solid black; */
        /* background:white; */
    }
    .invalid{
    border:1px solid #fbbdbd!important;
    box-shadow:1px 2px 3px gray;
    }
    .help-block{
    color:#e3747f!important;
    }
	button.button_anchor{
		border: unset;
		background: unset;
		border-radius: unset;
		box-shadow: unset;
		text-decorate:inderline;
		
	}
	button.button_anchor:hover{
		background:unset!important;
		border: unset;
		border-radius: unset;
		box-shadow: unset;
		text-decoration:underline!important;
		color:blue!important;
	}
	button.button_anchor:focus,
	button.button_anchor:active {
		outline: none;   /* removes default focus outline */
		box-shadow: none; /* some browsers add glow */
		border: none;    /* removes border if you added one */
	}
</style>

@endsection

@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary  breadcrumb-section p-0 d-md-block d-none">
  <div class="text-center d-flex flex-column align-items-center justify-content-center  px-2 py-2">
    <h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Customer Registration</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Register</p>
    </div>
  </div>
</div>

<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center">
		<h3 class="font-weight-semi-bold text-uppercase text-white m-0">Customer Registration</h3>
		</li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Register</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<!-- Contact Start -->
<div class="container-fluid pt-5">
  <!--<div class="text-center mb-4">
    <h2 class="section-title px-5"><span class="px-2">Customer Registration</span></h2>
  </div>-->
  <div class="row px-xl-5">
    

    <div class="col-lg-7 mb-5">
    <div class="customer_in_ui">
      <form id = "RegisterForm" action = "{{ url("{$ecommbaseurl}register")}}" method="post">

        @csrf

      <div class = "row" id = "registration_step">

          <div class="form-group col-md-6">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Your Full Name" >
          </div>

          <div class="form-group col-md-6">
            <label for="mobile">Mobile</label>
            <input type="text" class="form-control"  id="mobile" name="mobile" placeholder="Your Mobile Number" />
          </div>

          <div class="form-group col-12">
             <label for="address">Address</label>
            <textarea class="form-control" rows="" id="address" name="address" placeholder="Address" ></textarea>
          </div>

          <div class="form-group col-12">
              <button type = "submit" class="btn btn-outline-primary py-2 px-4" > Register </button>
          </div>

      </div>

      <div class = "varify_step" id="varify_step" style="display:none;">

        <a href = "#registration_step" id = "back_to_reg" class="btn btn-default btn-circle btn-sm" style="position:absolute;margin:8rem 0;z-index: 3;left:0;" ><li class="fa fa-angle-left"></li>&nbsp;</a>

        <div class="col-md-6" style="margin:auto;">

            <h4 class="text-center">Verify Mobile Number</h4>

            <hr>

            <div class="row" id = "verification_form" >
				

                <div class="form-group col-md-12">
                    <label for="">Enter OTP</label>
                    <input type="text" class="form-control"  id="otp" name="otp" placeholder="Enter OTP" />
                </div>
                <div class="form-group col-md-12">
                    <label for="">Create Password</label>
                    <input type="text" class="form-control" id="password" name="password" placeholder="Create a Password"  />
                </div>

                <div class="form-group col-12">
                    <button class="btn btn-outline-primary py-2 px-4 " type="submit" >Verify & Register</button>
                    <button class="btn btn-outline-primary py-2 px-4 button_anchor" type="submit" id = "SendOTP" > Resend OTP ?</button>
                    <input type = "hidden" name = "progress_step" id = "progress_step"  value = "1" >
                </div>

            </div>
        </div>

    </div>

    </form>
    </div>
    <div id="progressBarContainer" class="progress mt-1" style="height: 30px; display: none;">
        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated"
             role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            0%
        </div>
    </div>
    <div class="alert alert-success mt-1" role="alert" style="display: none;" id="success-alert">
        <!-- Success message will be displayed here -->
    </div>

    <div class="alert alert-danger mt-1" role="alert" style="display: none;" id="error-alert">
        <!-- error message will be displayed here -->
    </div>

    </div>
	
    <div class="col-lg-5 mb-5 custo_entry_block_right">
        <h2 class="font-weight-semi-bold mb-3">Store Name</h2>
		<p style="font-size:25px;"> {{ $web_title }}</p>
		<div class="d-flex flex-column mb-3">
			<h5 class="font-weight-semi-bold mb-3">Contact & Address</h5>
			<p class="mb-2 d-flex"><i class="fa fa-map-marker-alt text-primary mr-3"></i> {!! $address  !!} </p>
			<p class="mb-2 d-flex"><i class="fa fa-envelope text-primary mr-3"></i> {{ $head_mail  }}</p>
			<p class="mb-2 d-flex"><i class="fa fa-phone-alt text-primary mr-3"></i> {{ $head_fone  }}</p>
		</div> 
    </div>
  </div>
</div>
<!-- Contact End -->

@endsection

@section('javascript')

<script>

  $("#SendOTP").click(function(e){

    $('#progress_step').val(1) ;

  })

    $(document).ready(function() {

        // Define the updateProgressBar function
        function updateProgressBar(percent) {
            $('#progressBar').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
        }

        $('#RegisterForm').submit(function(e) {
            e.preventDefault();
            $('#progressBarContainer').show();
            updateProgressBar(0);
            $('.submit-btn').prop('disabled', true);
            var formData = new FormData(this);
            var action = $(this).attr('action') ;
            var progress_step = $('#progress_step').val() ;

            $.ajax({
                url: action , // Your form action URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    // Upload progress
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            updateProgressBar(Math.round(percentComplete * 100));
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {

                    updateProgressBar(100) ;

                    if(response.errors) {

                        $('#error-alert').text(response.errors).show() ;
                        $('#success-alert').hide() ;

                    } else {

                        $('#error-alert').hide() ;
                        progress_step++ ;

                        if(progress_step==2) {

                            $("#registration_step").hide() ;
                            $("#varify_step").show() ;

                        }

                        var sucsMsg = `${response.success}`;
                        $('#success-alert').text(response.success).show() ;
                        $('#progress_step').val(progress_step) ;

                        if(progress_step >2) {
                            //window.location.href = "{{ url("{$ecommbaseurl}login") }}";
                            window.location.href = "{{ url("{$ecommbaseurl}dashboard") }}";
                        }

                    }
                },
                error: function(response) {
                    if (response.status === 422) {

                        $('.invalid-feedback').remove();
                        $('.is-invalid').removeClass('is-invalid').css('border-color', '');
                        $('.is-valid').removeClass('is-valid').css('border-color', '');

                        var errors = response.responseJSON.errors;
                        for (var field in errors) {
                            var input = $('[name="' + field + '"]');
                            input.addClass('is-invalid').css('border-color', 'red');
                            input.after('<div class="invalid-feedback">' + errors[field][0] + '</div>');
                        }
                    } else {

                        $('#error-alert').text('An error occurred. Please try again.').show() ;

                    }
                },
                complete: function() {
                    // Re-enable the submit button after the request is complete
                    $('.submit-btn').prop('disabled', false) ;
                    setTimeout(function() {
                        $('#progressBarContainer').hide();
                        updateProgressBar(0); // Reset progress bar to 0% for future requests
                    }, 1000);
                }
            });
        });

        $("#back_to_reg").click(function(e) {

            e.preventDefault();
            $(this).parent('div').hide() ;
            $($(this).attr('href')).show() ;
            $("#otp,#password").val("");
            $('#progress_step').val(1) ;
        }) ;

    });

</script>

@endsection
