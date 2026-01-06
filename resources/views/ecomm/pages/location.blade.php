
@extends('ecomm.site')

@section('title', "Shop Location")

@section('content')

<style>

   .map iframe {

        width : 100% !important ;
        height : 100% !important ;

    }

</style>

<!-- Page Header Start -->

<div class="container-fluid bg-secondary breadcrumb-section p-0 d-md-block d-none">
  <div class="d-flex flex-column align-items-center justify-content-center p-2" >
    <!--<h1 class="font-weight-semi-bold mb-3">Shop Location</h1>-->
	<h3 class="font-weight-semi-bold text-uppercase mb-3 text-white">Shop Location</h3>
    <div class="d-inline-flex">
      <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
      <p class="m-0 px-2">-</p>
      <p class="m-0">Location</p>
    </div>
  </div>
</div>

<div class="container-fluid bg-secondary mb-5 p-0 d-block d-md-none">
	<ul class="mob_breadcrumb w-100">
		<li class="page_head p-1 text-center"><h3 class="font-weight-semi-bold text-uppercase text-white m-0">Shop Location</h3></li>
		<li class="page_path px-2">
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Shop Location</p>
			</div>
		</li>
	</ul>
</div>
<!-- Page Header End -->

<div class="container-fluid offer py-5 px-2">
  <div class="row px-xl-5">

<div class="col-md-12">

        <div class = "map" style = "height:500px" >

            @php

                $info = $common_content['info'] ;

            @endphp

            @if($info && $info['map'])

                {!! $info['map'] !!}

           @endif

        </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')

    <script>

        $.post("{{ url("api/{$ecommbaseurl}shop-location") }}","",function(response){ }) ;

    </script>

@endsection
