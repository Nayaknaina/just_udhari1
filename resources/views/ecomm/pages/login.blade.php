@extends('ecomm.site')
@section('title', 'Customer Login')
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

       /* ===== Login Page Styling ===== */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #fdfbfb, #ebedee);
  margin: 0;
  padding: 0;
}

/* Card Style for Form */
.contact-form {
  background: #fff;
  padding: 35px;
  border-radius: 18px;
  box-shadow: 0px 8px 25px rgba(0,0,0,0.12);
  animation: fadeInUp 0.7s ease;
}

/* Input Fields */
.contact-form .form-control {
  border: 1px solid #ccc;
  border-radius: 12px;
  padding: 12px 15px;
  transition: all 0.3s ease;
}

.contact-form .form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0,123,255,0.4);
  transform: scale(1.02);
}

/* Labels */
.contact-form label {
  font-weight: 600;
  margin-bottom: 6px;
  display: inline-block;
}

/* Error messages */
.help-block.text-danger {
  font-size: 13px;
  margin-top: 3px;
  transition: opacity 0.3s;
}

/* Login Button */
#loginbutton {
  margin-top: 10px;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
}

#loginbutton:hover {
  background: #007bff;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0px 6px 15px rgba(0,123,255,0.35);
}

/* Links */
a {
  color: #007bff;
  transition: color 0.3s;
}

a:hover {
  color: #0056b3;
  text-decoration: underline;
}

/* Right side block */
.custo_entry_block_right {
  background: #fff;
  border-radius: 18px;
  padding: 30px;
  box-shadow: 0px 6px 18px rgba(0,0,0,0.1);
  animation: fadeInRight 0.8s ease;
}

/* Spinner */
#block_await li {
  font-size: 28px;
  color: #007bff;
}

/* ===== Animations ===== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/*page -------------------hader*/







</style>

@endsection
@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary breadcrumb-section p-0 d-md-block d-none">
        <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
            <!--<h1 class="font-weight-semi-bold text-uppercase mb-3">Customer Login</h1>-->
			<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Customer Login</h3>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Login</p>
            </div>
        </div>
    </div>
	
	
	<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
		<ul class="mob_breadcrumb w-100">
			<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Customer Login</h3></li>
			<li class="page_path px-2">
				<div class="d-inline-flex">
					<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
					<p class="m-0 px-2">-</p>
					<p class="m-0">Login</p>
				</div>
			</li>
		</ul>
	</div>
    <!-- Page Header End -->


    <!-- Contact Start -->
     
     
    <div class="container-fluid pt-5">
        <!--<div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Customer Login</span></h2>
        </div>-->
        <div class="row px-xl-5">


            <div class="col-lg-5 mb-5 ">
                <div class="contact-form  customer_in_ui">

                    @include('ecomm.content.alert')

                    <div id="action_response" class="alert">

                    </div>
                    <form name="loginform" id="loginform" novalidate="novalidate" role="form"
                        action="{{ url("{$ecommbaseurl}login") }}">
                        @csrf
                        <div class="control-group form-group">
                            <label for="user">Username/Mobile</label>
                            <input type="text" class="form-control" id="user" placeholder="Username" name="username"
                                required="required" data-validation-required-message="Please enter your username" />
                            <small class="help-block text-danger" id="username_error"></small>
                        </div>
                        <div class="control-group form-group">
                            <label for="pass">Password</label>
                            <input type="password" class="form-control" id="pass" placeholder="Password"
                                name="password" required="required"
                                data-validation-required-message="Please enter your password" />
                            <small class="help-block text-danger" id="password_error"></small>
                        </div>
						
                        <div class="control-group form-group row">
                            <div class="col-6">
                                <label for="remember">
                                    <input type="checkbox" id="remember" name="remember" value="remember"> Remember Me ?
                                </label>
                                <button class="btn btn-outline-primary py-2 px-4" type="submit" name="do" value="login" id="loginbutton">Login</button>
                            </div>
                            <div class="col-6 text-center">
                                <strong><a href="{{ url("{$ecommbaseurl}forgot") }}">Forgot Password ?</a></strong>
                            </div>
                            <br>
                        </div>
						<p>If you don't have a account ?<a href="{{ url("{$ecommbaseurl}register") }}" class=""> <u>Register Here</u> </a></p>
                    </form>
                </div>
                <div id="block_await" style="display:none;">
                    <div class="content">
                        <li class="fa fa-spinner fa-spin"></li>
                    </div>
                </div>
            </div>
			
            <div class="col-lg-7 mb-5 custo_entry_block_right">
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
		$('.form-control').focus(function(e){
            $("#action_response").empty();
            $(this).removeClass('invalid');
            $(this).next('small').empty();
        });
        $("#loginform").submit(function(e) {
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
                    if (response.valid) {
                        //alert(response.msg);
                        if (response.status) {
                            $("#action_response").addClass("alert-success");
                            $("#action_response").empty().text(response.msg);
                            window.location.href = "{{ url("{$ecommbaseurl}dashboard") }}";
                        }else{
                            $("#action_response").addClass("alert-danger");
                            $("#action_response").empty().text(response.msg);
                        }
                    } else {
                        $.each(response.errors,function(ele,msg_arr){
                            $("input[name='"+ele+"']").addClass('invalid');
                            $('#'+ele+'_error').text(msg_arr[0]);
                        });
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
                "user": "Username required !",
                "pass": "Password Required !"
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
