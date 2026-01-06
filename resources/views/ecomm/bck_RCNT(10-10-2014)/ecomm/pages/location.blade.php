@extends('ecomm.site')
@section('title', "About")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section ">
  <div class="d-flex flex-column align-items-center justify-content-center px-2 py-2" >
    <h1 class="font-weight-semi-bold mb-3">Shop Location</h1>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Location</p>
    </div>
  </div>
</div>
<!-- Page Header End -->

<div class="container-fluid offer pt-5">
  <div class="row px-xl-5">

<div class="col-md-12">

        <div class = "map" style = "height:500px" >

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3667.4867322666364!2d77.41447627437307!3d23.18892601010158!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x397c43911144bc0f%3A0x47b3799e8d82ee6a!2sMG%20Jewellers!5e0!3m2!1sen!2sin!4v1722860812113!5m2!1sen!2sin" style = "width:100%;height:100%;" frameborder="0"></iframe>

        </div>
    </div>
  </div>
</div>

@endsection
