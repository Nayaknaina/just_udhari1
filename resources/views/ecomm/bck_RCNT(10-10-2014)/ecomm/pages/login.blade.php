@extends('ecomm.site')
@section('title', "Customer Login")
@section('content')


<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5">
  <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2" >
    <h1 class="font-weight-semi-bold text-uppercase mb-3">Customer Login</h1>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Login</p>
    </div>
  </div>
</div>
<!-- Page Header End -->

<!-- Contact Start -->
<div class="container-fluid pt-5">
  <div class="text-center mb-4">
    <h2 class="section-title px-5"><span class="px-2">Customer Login</span></h2>
  </div>
  <div class="row px-xl-5">

    <div class="col-lg-7 mb-5">
      <h2 class="font-weight-semi-bold mb-3">Store Name</h5>
      <p style="font-size:25px;"> MG Jeweller</p>
      <div class="d-flex flex-column mb-3">
        <h5 class="font-weight-semi-bold mb-3">Contact & Address</h5>
        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Shop No-5 Sagar Homes 3, Secter -A,In Front Of Manik Motors,Sarvadharm Kolar Road, Kolar Road, Bhopal, 462042, Madhya Pradesh</p>
        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>mgjewellers.bpl@gmail.com</p>
        <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>+91-9826262335</p>
      </div>
    </div>
    <div class="col-lg-5 mb-5 custo_entry_block_right">
      <div class="contact-form">
        <div id="success"></div>
        <form name="sentMessage" id="contactForm" novalidate="novalidate">
          <div class="control-group">
            <label for="user">Username</label>
            <input type="text" class="form-control" id="user" placeholder="Username" required="required"
              data-validation-required-message="Please enter your username" />
            <p class="help-block text-danger"></p>
          </div>
          <div class="control-group">
            <label for="pass">Password</label>
            <input type="password" class="form-control" id="pass" placeholder="Password" required="required"
              data-validation-required-message="Please enter your password" />
            <p class="help-block text-danger"></p>
          </div>
          <div class="control-group text-right">
            <button class="btn btn-primary py-2 px-4" type="submit" id="sendMessageButton">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Contact End -->

@endsection