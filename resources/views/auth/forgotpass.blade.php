@extends('layouts.website.app')

@section('content')

{{-- <div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3>Log In</h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li>Log In</li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    <img src="{{ asset('assets/images/shape/inner-shape.png') }}" alt="Images">
    </div>
</div> --}}
<style>
    #otp_timer_li{
        opacity:0.3;
    }
    #otp_timer_li.active{
        opacity:1;
    }
</style>
<div class="user-area pt-100 pb-70">
    <div class="container">
    <div class="row align-items-center">
    <div class="col-lg-6">
    <div class="user-img">
    <img src="assets/images/user-img.jpg" alt="Images">
    </div>
    </div>
    <div class="col-lg-6">
    <div class="user-form">
    <div class="contact-form">
    <h2>Forgot Password </h2>

    <div id="error-alert" class="alert alert-danger mt-2" style="display: none;"></div>
    <div id="success-alert" class="alert alert-success mt-2" style="display: none;"></div>

     <form id = "forgotpasswordform" method="post" autocomplete="off" action="{{ url('forget-password') }}">

        @csrf
        <div class="row">
            <div class="col-12" id="step_one">
                <div class="form-group">
                    <input type="text" id="mobile"  name = "mobile" class="form-control" required data-error="Please Enter Registered Mobile Number" placeholder="Enter Mobile Number" >
                    <small style="color:#ee5d19">OTP will be send to the above mobile number !</small>
                </div>
                <div class="form-group text-center">
                    <a href="{{ url('forget-password/sendotp') }}" class="default-btn btn-bg-two otp_button" >Reset Password ?</a>
                </div>
            </div>
            <div class="col-lg-12" id="step_two" style="display:none;">
                <div class="form-group">
                    <input type="text" id="mobile_prev"  name = "mobile_prev" class="form-control" required disabled data-error="Please Enter Registered Mobile Number" placeholder="Enter Mobile Number" style="background:lightgray;">
                </div>
                <div class="form-group">
                    <input type="text" id="otp"  name = "otp" class="form-control" required data-error="Please Enter the OTP" placeholder="Enter OTP">
                    <ul class="row px-3" style="list-style:none;">
                        <li class="col-6 text-left text-primary" id="otp_timer_li"><i id="otp_timer">00</i> sec.</li>
                        <li class="col-6" style="text-align:right">
                            <small class="text-primary" id="act_response_block"><a href="{{ url('forget-password/sendotp') }}" class="otp_button" id="otp_button"><u>Send Otp ?</u></a></small>
                        </li>
                    </ul>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="new" placeholder="New Password">
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="confirm" placeholder="Confirm Password">
                </div>
                <div class="col-lg-12 text-center">
                    <button type="submit" class="default-btn btn-bg-two">Verify & Change </button>
                </div>
            </div>
            <div class="col-lg-12 ">
                <p class="account-desc"> Not a Member?<a href="{{ url('register') }}"> Register Now</a> </p>
            </div>
        </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
</div>

@endsection

@section('javascript')

<script>

    $(document).ready(function() {
        $('.form-control').focus(function(){
            $(".alert").hide('slow');
        });
        $('#forgotpasswordform').submit(function(e) {
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(),function(response){
                if(response.errors){
                    if(typeof response.errors != 'string'){
                        var errorHtml = '<ul>' ;
                        $.each(response.errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>' ;
                        });
                        errorHtml += '</ul>' ;
                        $('#error-alert').html(errorHtml).show() ;
                    }else{
                        $('#error-alert').text(response.errors).show();
                    }
                }else{
                    $("#success-alert").html(response.success).show();
                    setTimeout(location.href="{{ url('login') }}",1500);
                }
            });
        });

        var otp_button = $("#otp_button").clone();
        otp_button.html('<u>Resend Otp ?</u>');
        const otp_loader = $('<span><i class="fa fa-spinner fa-spin"></i> Sending...</span>');
        var res_msg = "";
        //var timerInterval = false;
        var otpExpirationTime =  60; // seconds
        var timerInterval = false;
        // Function to update the timer
        function updateTimer() {

            // Format the timer (mm:ss)
            $('#otp_timer').text((otpExpirationTime < 10 ? '0' : '') + otpExpirationTime);

            // Decrease the remaining time
            if (otpExpirationTime > 0) {
                otpExpirationTime--;
            } else {
                clearInterval(timerInterval);
                $("#otp_timer_li").removeClass('active');
                $('#act_response_block').html(otp_button);
                $('#error-alert').html('OTP expired !').show();
                // Optionally, you can disable the OTP input here or trigger other actions
            }
        }

       //timerInterval = setInterval(updateTimer, 1000);

        // // Call the function immediately to display the initial timer
        // updateTimer();
        function resetotp(){
            $('#act_response_block').html(otp_button);
        }
       
        $(document).on('click',".otp_button",function(e){
            e.preventDefault();
            $("#otp").val('');
			var has_id = $(this).attr('id')??false;
            const mobile = $('#mobile').val();
            if(mobile!="" && mobile.length ==10){
				if(has_id && has_id=='otp_button'){
                    $(this).replaceWith(otp_loader);
                }
                $.get($(this).attr('href'),"mobile="+mobile,function(response){
                    if(response.errors){
                        setTimeout(resetotp,2000);
						if(!has_id){
                            $('#error-alert').html(response.errors).show();
                        }else{
                            $('#act_response_block').html(response.errors);
                        }
                    }else{
						if(!has_id){
                            $("#step_one").hide('slow');
                            $("#step_two").show('slow');
                            $("#mobile_prev").val(mobile);
                        }
                        clearInterval(timerInterval);
                        otpExpirationTime = 60;
                        res_msg = response.success;
                        $('#act_response_block').html(res_msg);
                        $("#otp_timer_li").addClass('active');
                        timerInterval = setInterval(updateTimer, 1000);
                        updateTimer();
                    }
                });
            }else{
                alert("Enter Valid 10 Digit Mobile Number !");
            }
        });
    });
    $("#forgotpasswordform").trigger('reset');
</script>


@endsection