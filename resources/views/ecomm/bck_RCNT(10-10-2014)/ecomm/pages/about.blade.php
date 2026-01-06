@extends('ecomm.site')
@section('title', "About")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section ">
  <div class="d-flex flex-column align-items-center justify-content-center px-2 py-2" >
    <h1 class="font-weight-semi-bold mb-3">About Us</h1>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">About</p>
    </div>
  </div>
</div>
<!-- Page Header End -->

<div class="container offer pt-5">
  <div class="row px-xl-5">
    <div class="col-md-4 text-center">
      <img src="{{ asset("assets/ecomm/images/about.webp") }}" alt="" class="img-responsive offer_new_img" >
  </div>
<div class="col-md-8">
      <div class="position-relative border-1 text-center text-md-right text-white mb-2 py-5 px-5">

        <div class="position-relative" >
          <h1 class="text-uppercase text-primary mb-3">About Us</h1>
          <p class="mb-4 font-weight-semi-bold" style="color:black;text-align:justify;text-indent:40%;">MG jewellers (since 1973) in manufacturing and (since 1999) serving in Kolar, Bhopal.
We started as makers of the jewellery and then as some small traders in the town and today with all due support from our family like customers we have reached so far with a customer base of 25k+.

MG jewellers has already won hearts of millions in Bhopal & we also wholeheartedly welcome our new customers to join us and be a part of our family.
Feel free to contact for any queries at any time. We will be HAPPY TO SERVE :)</p>
          <a href="{{url("{$ecommbaseurl}contact") }}" class="btn btn-outline-primary py-md-2 px-md-3">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid  my-5">
  <div class="row about_bottom_section justify-content-md-center px-xl-5">
    <div class="col-md-6 col-12 py-5">
      <div class="text-center mb-2 pb-2">
        <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Why Choose Us</span></h2>
        <p>A Effective Description that make you different from other & Your Speciality.
        </p>
      </div>
      <div class="form-group text-center">
        <a class="btn btn-primary px-4" href="{{ url("{$ecommbaseurl}shop")}}">Shop Now</a>
        <a class="btn btn-primary px-4" href="{{ url("{$ecommbaseurl}contact")}}">Contact Us</a>
      </div>
    </div>
  </div>
</div>

@endsection
@section('javascript')
<script>
    $.post("{{ url("api/{$ecommbaseurl}about") }}","",function(response){

    })
</script>
@endsection
