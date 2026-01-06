@extends('ecomm.site')
@section('title', "Contact")
@section('content')
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

<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section p-0 d-md-block d-none">
  <div class="d-flex flex-column align-items-center justify-content-center px-2 py-2" >
    <!--<h1 class="font-weight-semi-bold mb-3">Contact Us</h1>-->
	<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Contact Us</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Contact</p>
    </div>
  </div>
</div>


<div class="container-fluid bg-secondary p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Contact Us</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Contact</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->


<!-- Contact Start -->
<div class="container-fluid pt-5" id="contact_section_page">
  <!--<div class="text-center mb-4">
    <h2 class="section-title px-5"><span class="px-2">Contact For Any Queries</span></h2>
  </div>-->
  <div class="row px-xl-5">
    <div class="col-lg-7 mb-5">
      <div class="contact-form">
        <div id="success"></div>
        <form name="sentMessage" id="contactForm" novalidate="novalidate">
          <div class="control-group">
            <input type="text" class="form-control" id="name" placeholder="Your Name" required="required"
              data-validation-required-message="Please enter your name" />
            <p class="help-block text-danger"></p>
          </div>
          <div class="control-group">
            <input type="email" class="form-control" id="email" placeholder="Your Email" required="required"
              data-validation-required-message="Please enter your email" />
            <p class="help-block text-danger"></p>
          </div>
          <div class="control-group">
            <input type="text" class="form-control" id="subject" placeholder="Subject" required="required"
              data-validation-required-message="Please enter a subject" />
            <p class="help-block text-danger"></p>
          </div>
          <div class="control-group">
            <textarea class="form-control" id="message" placeholder="Message" required="required"
              data-validation-required-message="Please enter your message"></textarea>
            <p class="help-block text-danger"></p>
          </div>
          <div>
            <button class="btn btn-outline-primary py-2 px-4" type="submit" id="sendMessageButton">Send
              Message</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-lg-5 mb-5" id="contact_info_area">
      <h5 class="font-weight-semi-bold mb-3">Get In Touch</h5>
      <p> We already won hearts of millions of people & we also wholeheartedly welcome our new customers to join us and be a part of our family. </p>
      <div class="d-flex flex-column mb-3">
        <h5 class="font-weight-semi-bold mb-3">Store 1</h5>
        <p class="mb-2">
			<i class="fa fa-map-marker-alt text-primary mr-3"></i>
			<a href="https://maps.app.goo.gl/1CPFdxvgGEVYoh8J6" style="color:gray;" target="_blank">
				{!! $address !!}
			</a>
		</p>
        <p class="mb-2">
			<i class="fa fa-envelope text-primary mr-3"></i>
			<a href="mailto:{{ $head_mail }}" style="color:gray;" target="_blank">
			{{$head_mail}}
			</a>
		</p>
        <p class="mb-2">
			<i class="fa fa-phone-alt text-primary mr-3"></i>
			<a href="https://wa.me/{{ $head_fone }}" style="color:gray;" target="_blank">
			{{$head_fone}}
			</a>
		</p>
      </div>
      <!-- <div class="d-flex flex-column">
        <h5 class="font-weight-semi-bold mb-3">Store 2</h5>
        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
      </div> -->
    </div>
  </div>
</div>
<!-- Contact End -->
@endsection

@push('js')
	<script src="{{ asset('assets/ecomm/mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('assets/ecomm/mail/contact.js') }}"></script>
@endpush
