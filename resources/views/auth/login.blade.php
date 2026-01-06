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
    <h2>Log In</h2>

    <div id="error-alert" class="alert alert-danger mt-2" style="display: none;"></div>
    <div id="success-alert" class="alert alert-success mt-2" style="display: none;"></div>

    <form id = "loginForm" method="post">

        @csrf

    <div class="row">
    <div class="col-lg-12 ">
    <div class="form-group">
    <input type="text"  name = "mobile_no" class="form-control" required data-error="Please enter your Username or Email" placeholder="Username or Email">
    </div>
    </div>
    <div class="col-12">
    <div class="form-group">
    <input class="form-control" type="password" name="password" placeholder="Password">
    </div>
    </div>
    <div class="col-lg-12 form-condition">
    <div class="agree-label">
	<label for="remember" class="form-check-label m-0" id="chb1"><input type="checkbox" name="remember" id="remember" class="form-check-input"> &nbsp;Remember Me</label>
    {{--<label for="remember" class="form-check-label" id="chb1">Remember Me</label>
    <input type="checkbox" name="remember" id="remember" class="form-check-input">--}}
    <label for="chb1"> <a class="forget" href="{{ url('forget-password') }}">Forgot My Password?</a>
    </label>
    </div>
    </div>
    <div class="col-lg-12 ">
    <button type="submit" class="default-btn btn-bg-two">
    Log In Now
    </button>
    </div>
    <div class="col-12">
    <p class="account-desc">
    Not a Member?
    <a href="{{ url('register') }}">Register Now</a>
    </p>
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
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("login") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#success-alert').text(response.success).show();
                    $('#error-alert').hide();
                    window.location.href = 'vendors'; // Redirect to dashboard or intended URL
                },
                error: function(response) {

                    if (response.status === 422) {

                        var errors = response.responseJSON.errors ;
                        var errorHtml = '<ul>' ;
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>' ;
                        });
                        errorHtml += '</ul>' ;
                        $('#error-alert').html(errorHtml).show() ;

                    }else{

                        var response = response.responseJSON ;
                        $('#error-alert').html(response.errors.mobile_no ).show() ;

                    }
                    
                }
            });
        });
    });

</script>


@endsection