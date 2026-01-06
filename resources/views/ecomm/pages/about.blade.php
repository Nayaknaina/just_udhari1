@extends('ecomm.site')
@section('title', "About")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section p-0 d-md-block d-none">
  <div class="d-flex flex-column align-items-center justify-content-center px-2 py-2" >
	<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">About Us</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">About</p>
    </div>
  </div>
</div>

<div class="container-fluid bg-secondary p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">About Us</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">About</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<div class="container-fluid offer p-5" id="about_section_page">
  <div class="row px-xl-5">

    @if($content)

        <div class="col-md-4 text-center">
            <img src="{{ asset($content->about_image) }}" alt="" class="img-responsive offer_new_img" loading="lazy">
        </div>

        <div class="col-md-8">
            <div class="position-relative border-1 text-center text-md-right   mt-2 mb-3 py-1 px-5 border-default about-content-border bg-white">

                <div class="position-relative" >
                <h1 class="text-uppercase text-primary mb-3">About Us</h1>
                <p class="mb-4 font-weight-semi-bold" style="color:black;text-align:justify;text-indent:40%;">
                    {!!  $content->about_desc  !!}
                </p>
                <a href="{{url("{$ecommbaseurl}contact") }}" class="btn btn-outline-primary py-md-2 px-md-3">Contact Us</a>
                </div>
            </div>
        </div>

    @endif

  </div>
</div>

@endsection

@section('javascript')

    <script>

	{{--$.post("{{ url("api/{$ecommbaseurl}about") }}","",function(response){ }) ;--}}

    </script>

@endsection
