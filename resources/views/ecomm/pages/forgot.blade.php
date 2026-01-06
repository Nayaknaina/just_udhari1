@extends('ecomm.site')
@section('title', 'Customer Reset Password')
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

        #block_await {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #dfdedee3;
            padding-top: 100px;
        }

        #block_await>.content {
            margin: auto;
            width: max-content;
            color: gray;
            text-align: center;
            font-size: 3rem;
            padding: 5px;
            /* border:1px solid black; */
            /* background:white; */
        }

        .invalid {
            border: 1px solid #fbbdbd !important;
            box-shadow: 1px 2px 3px gray;
        }

        .help-block {
            color: #e3747f !important;
        }
        #step_two > .control-group{
            position:relative;
            padding-bottom:2%;
        }
        #step_two > .control-group > label{
            position:absolute;
            top:-25%;
            left:1%;
            /* text-shadow:1px 2px 3px ; */
        }
</style>

@endsection
@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5 breadcrumb-section p-0 d-md-block d-none">
        <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
            <!--<h1 class="font-weight-semi-bold text-uppercase mb-3">Forget Password</h1>-->
			<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Forget Password</h3>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Forget Password</p>
            </div>
        </div>
    </div>
	
	<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
		<ul class="mob_breadcrumb w-100">
			<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Forget Password</h3></li>
			<li class="page_path px-2">
				<div class="d-inline-flex">
					<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
					<p class="m-0 px-2">-</p>
					<p class="m-0">Forget Password</p>
				</div>
			</li>
		</ul>
	</div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Password Reset</span></h2>
        </div>
        <div class="row px-xl-5">

            <div class="col-lg-7 mb-5">
                <h2 class="font-weight-semi-bold mb-3">Store Name</h5>
                    <p style="font-size:25px;"> {{ $web_title }}</p>
                    <div class="d-flex flex-column mb-3">
                        <h5 class="font-weight-semi-bold mb-3">Contact & Address</h5>
						<p class="mb-2 d-flex"><i class="fa fa-map-marker-alt text-primary mr-3"></i> {!! $address  !!} </p>
						<p class="mb-2 d-flex"><i class="fa fa-envelope text-primary mr-3"></i> {{ $head_mail  }}</p>
						<p class="mb-2 d-flex"><i class="fa fa-phone-alt text-primary mr-3"></i> {{ $head_fone  }}</p>
                    </div> 
            </div>

            <div class="col-lg-5 mb-5 custo_entry_block_right">
                <div class="contact-form">

                    @include('ecomm.content.alert')

                    <div id="action_response" class="alert">

                    </div>
                    <form name="forgotpassform" id="forgotpassform" novalidate="novalidate" role="form"
                        action="{{ url("{$ecommbaseurl}forgot") }}">
                        @csrf
                        <div class="col-12" id="step_one">
                            <div class="control-group form-group">
                                <label for="user">Username/Mobile</label>
                                <input type="text" class="form-control" id="username" placeholder="Username" name="username" required="required" data-validation-required-message="Please enter your username" />
                                <small class="help-block text-danger" id="username_error"></small>
                                <p class="text-info col-12 p-0"><small><b>NOTE : </b>OTP will send to the Entered Mobile number !</small></p>
                            </div>
                            <div class="control-group form-group">
                                <a href="{{ url("{$ecommbaseurl}forgot/sendotp") }}" class="btn btn-primary py-2 px-4 otpbtn" >Next ></a>
                            </div>
                        </div>
                        <div class="col-12" id="step_two" style="display:none;">
                            <div class="control-group form-group">
                                <input type="text" class="form-control" id="username_prev" placeholder="Username" name="username_prev" required="required" data-validation-required-message="Please enter your username" disabled />
                            </div>
                            <div class="control-group form-group">
                                <label for="user" class="bg-light">OTP </label>
                                <input type="text" class="form-control" id="otp" placeholder="Enter OTP" name="otp"  required="required" data-validation-required-message="Please enter your username" />
                                <small class="help-block text-danger" id="otp_error"></small>
                                <ul class="row col-12 p-0 m-0" style="list-style:none;display:inline-flex;">
                                    <li class="col-6 text-left">
                                        <small><span id="otp_timer">00</span>&nbsp;sec.</small>
                                    </li>
                                    <li class="col-6 text-right">
                                        <small id="otp_btn_block">
                                            <a href="{{ url("{$ecommbaseurl}forgot/sendotp") }}" class="otpbtn" type="submit" id="otpbtn" >
                                                <u>Resent OTP ?</u>
                                            </a>
                                        </small>
                                    </li>
                                </ul>
                            </div>
                            <div class="control-group form-group">
                                <label for="create" class="bg-light">New Password</label>
                                <input type="password" class="form-control" id="create" placeholder="Create Password"  name="create" required="required" data-validation-required-message="New password Required" />
                                <small class="help-block text-danger" id="create_error"></small>
                            </div>
                            <div class="control-group form-group">
                                <label for="confirm" class="bg-light">Confirm Password</label>
                                <input type="text" class="form-control" id="confirm" placeholder="Re-Enter Password" name="confirm" required="required" data-validation-required-message="Please enter your password" />
                                <small class="help-block text-danger" id="confirm_error"></small>
                            </div>
                            <div class="control-group form-group">
                                <button class="btn btn-primary py-2 px-4" type="submit" name="pass" value="chsnge" id="pass_change_btn">Verify & Change</button>
                            </div>
                        </div>

                        <p class="pt-2">If you don't have a account ?<a href="{{ url("{$ecommbaseurl}register") }}" class=""> <u>Register Here</u> </a></p>

                    </form>
                </div>
                <div id="block_await" style="display:none;">
                    <div class="content">
                        <li class="fa fa-spinner fa-spin"></li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection
@section('javascript')
    <script>
        $('.form-control').focus(function(e){
            $("#action_response").empty();
            $(this).removeClass('invalid');
            $(this).next('small').empty();
        });


        var otpExpirationTime =  60; // seconds
        var timerInterval = false;
       


        const otp_button = $("a#otpbtn").clone();
        
        const otp_loader = $('<span><i class="fa fa-spinner fa-spin"></i> Sending...</span>');

        function resetotp(){
            $('#otp_btn_block').html(otp_button);
        }

         // Function to update the timer
        function updateTimer() {

            // Format the timer (mm:ss)
            $('#otp_timer').text((otpExpirationTime < 10 ? '0' : '') + otpExpirationTime);

            // Decrease the remaining time
            if (otpExpirationTime > 0) {
                otpExpirationTime--;
            } else {
                clearInterval(timerInterval);
                $('#action_response').removeClass('text-success').addClass('text-danger').html('OTP expired !').show();
                resetotp();
            }
        }

        $(document).on('click','.otpbtn',function(e){
            e.preventDefault();
            var mobile = $("#username").val();
            
            const id = $(this).attr('id')??false;
            if(mobile!=""){
                if(id && id=='otpbtn'){
                    $(this).replaceWith(otp_loader);
                }
                $.get($(this).attr('href'),'mobile='+mobile,function(response){
                    if(response.errors){
                        setTimeout(resetotp,2000);
                        if(typeof response.errors !='string'){
                            $.each(response.errors,function(i,v){
                                $("#"+i).addClass('invalid');
                                var err_stream = "";
                                $.each(v,function(vi,vv){
                                    err_stream+=vv;
                                    if(vi!=0){
                                        err_stream+='<br>';
                                    }
                                });
                                $("#"+i+"_error").html(err_stream);
                            });
                        }else{
                            $("#action_response").addClass('text-danger').html(response.errors).show();
                        }
                    }else{
                        if(!id){
                            $("#step_one").hide();
                            $("#step_two").show();
                            $("#username_prev").val(mobile);
                        }
                        clearInterval(timerInterval);
                        otpExpirationTime = 60;
                        $("#otp_btn_block").html($('<span class="text-success">&check;OTP Sent !</span>'));
                        $("#action_response").removeClass('text-danger').addClass('text-success').html(response.success).show();
                        timerInterval = setInterval(updateTimer, 1000);
                        updateTimer();
                    }
                });
            }else{
                $("#username_error").text("Please Enter a Valid Username/Mobile Number !");
            }
        })
        $("#forgotpassform").submit(function(e) {
            e.preventDefault();
            if (validateform()) {
                $("#block_await").show();
                var action = $(this).attr('action');
                var data = $(this).serializeArray()
                $.post(action, data, function(response) {
                    $("#action_response").empty();
                    $('.form-control').removeClass('invalid');
                    $("#action_response").removeClass('alert-danger alert-success');
                    $("#block_await").hide();
                    if (response.success) {
                        $("#action_response").addClass("alert-success").empty().text(response.success);
                        setTimeout(function(){ location.href="{{ url("{$ecommbaseurl}login") }}";},1000);
                    } else {
                        $("#action_response").addClass('text-danger');
                        if(typeof response.errors!='string'){
                            $.each(response.errors,function(ele,msg_arr){
                                $("input[name='"+ele+"']").addClass('invalid');
                                $('#'+ele+'_error').text(msg_arr[0]);
                            });
                        }else{
                            $("#action_response").addClass('text-danger').empty().text(response.errors);
                        }
                    }
                }).fail(function(response) {
                    //alert(response.msg);
                });
            }
        });

        function validateform() {
            //console.lof(data_arr.serializeArray());
            var valid = true;
            var field_error_arr = {
                "username": "Username required !",
            };
            $("#loginform > div > .form-control").each(function(i, v) {
                var id = $(this).attr('id');
                if ($(this).val() == "") {
                    valid = false;
                    $(this).addClass('invalid');
                    $(this).next('.help-block').text(field_error_arr[id]);
                }
            });
            return valid;
        }
    </script>
@endsection
