@extends('layouts.website.app')

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> Contact Us </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> Contact </li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    {{-- <img src="{{ asset('assets/images/bg/banner.jpg') }}" alt="Images"> --}}
    </div>
</div>

<div class="contact-form-area pt-100 pb-70">
    <div class="container">
    <div class="section-title text-center">
    <h2>Let's Send Us a Message Below</h2>
    </div>
    <div class="row pt-45">
    <div class="col-lg-4">
    <div class="contact-info mr-20">
    <span>Contact Info</span>
    <h2>Let's Connect With Us</h2>
    <p>For your total peace of mind, it is outfitted with unique security tools that were created especially for jewelry businesses</p>
    <ul>
    <li>
    <div class="content">
    <i class="bx bx-phone-call"></i>
    <h3>Phone Number</h3>
    <a href="tel:12122555511">+91-7879404501</a>
    </div>
    </li>
    <li>
    <div class="content">
    <i class="bx bxs-map"></i>
    <h3>Address</h3>
    <span>H NO. 11, Jain MAndir Road , Chok Bazar , Bhopal 462001</span>
    </div>
    </li>
    <li>

    <div class="content">
    <i class="bx bx-message"></i>
    <h3>Contact Info</h3>
    <a href="#"><span class="__cf_email__" data-cfemail="5a323f3636351a2e3f39323f2274393537"> hambiresolution@gmail.com</span></a>
    </div>
    </li>
    </ul>
    </div>
    </div>
    <div class="col-lg-8">
    <div class="contact-form">
    <form id="contactForm" novalidate="true">
    <div class="row">
    <div class="col-lg-6">
    <div class="form-group has-error has-danger">
    <label>Your Name <span>*</span></label>
    <input type="text" name="name" id="name" class="form-control" required="" data-error="Please Enter Your Name" placeholder="Name">
    </div>
    </div>
    <div class="col-lg-6">
    <div class="form-group has-error has-danger">
    <label>Your Email <span>*</span></label>
    <input type="email" name="email" id="email" class="form-control" required="" data-error="Please Enter Your Email" placeholder="Email">
    </div>
    </div>
    <div class="col-lg-6">
    <div class="form-group has-error has-danger">
    <label>Phone Number <span>*</span></label>
    <input type="text" name="phone_number" id="phone_number" required="" data-error="Please Enter Your number" class="form-control" placeholder="Phone Number" oninput="digitonly(event,10)">
    </div>
    </div>
    <div class="col-lg-6">
    <div class="form-group has-error has-danger">
    <label>Your Subject <span>*</span></label>
    <input type="text" name="msg_subject" id="msg_subject" class="form-control" required="" data-error="Please Enter Your Subject" placeholder="Your Subject">
    </div>
    </div>
    <div class="col-lg-12 col-md-12">
    <div class="form-group has-error has-danger">
    <label>Your Message <span>*</span></label>
    <textarea name="message" class="form-control" id="message" cols="30" rows="8" required="" data-error="Write your message" placeholder="Your Message"></textarea>
    </div>
    </div>
    <div class="col-lg-12 col-md-12">
    <div class="agree-label">
    <input type="checkbox" id="chb1">
    <label for="chb1">
    Accept <a href="terms-condition.html">Terms &amp; Conditions</a> And <a href="privacy-policy.html">Privacy Policy.</a>
    </label>
    </div>
    </div>
    <div class="col-lg-12 col-md-12 text-center">
    <button type="submit" class="default-btn btn-bg-two border-radius-50 disabled">
    Send Message <i class="bx bx-chevron-right"></i>
    </button>
    <div id="msgSubmit" class="h3 text-center hidden"></div>
    <div class="clearfix"></div>
    </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>

@endsection

@section('javascript')

<script language="JavaScript" type="text/javascript">
    $(document).ready(function(){
      $('.carousel').carousel({
        interval: 2000
      })
    });
  </script>

@endsection
