@extends('ecomm.site')
@section('title', "Desclaimer")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section p-0 d-md-block d-none">
  <div class="d-flex flex-column align-items-center justify-content-center p-2">
    <!--<h1 class="font-weight-semi-bold text-uppercase mb-3">Desclaimer</h1>-->
	<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Desclaimer</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Desclaimer</p>
    </div>
  </div>
</div>

<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Desclaimer</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Desclaimer</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<!-- Contact Start -->
<div class="container-fluid pt-5">
  <div class="text-center mb-4">
    <h2 class="section-title px-5"><span class="px-2">Desclaimer</span></h2>
  </div>
  <div class="row px-xl-5">
    <div class="col-lg-12 mb-5">
      {!! @$content->desc_content !!}
    </div>
  </div>
</div>
<!-- Contact End -->
@endsection
