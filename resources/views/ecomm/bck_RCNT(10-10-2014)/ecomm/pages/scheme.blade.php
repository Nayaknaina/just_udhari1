@extends('ecomm.site')
@section('title', "Schemes")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section ">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Our Scheme</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Scheme</p>
        </div>
    </div>
</div>
<!-- Page Header End -->
<!-- Offer Start -->
<div class="container offer pt-5">
    <div class="row px-xl-5">

        @php

            $schemes =
                        [
                            ['title'=>'Subh Lakshmi Yojnaa','description'=>'Now get jewellery warth of 50,000 in 5000 *','image'=>'assets/ecomm/scheme_girl.png' , 'url' => 'Subh-Lakshmi-Yojnaa'] ,
                            ['title'=>'Labh Laksmi Yojnaa','description'=>'10% Interest on Deposit','image'=>'assets/ecomm/scheme_girl.png' , 'url' => 'Labh-Laksmi-Yojnaa'] ,
                        ]
                    ;

        @endphp

        @foreach ($schemes as $scheme )

            <div class="col-md-6 pb-4">
                <div class="position-relative text-center text-md-right schemes-box ">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset($scheme['image'])}}" alt="" class="img-responsive offer_new_img">
                        </div>
                        <div class="col-md-8">
                            <div class="position-relative" style="z-index: 1;">
                                <h4 class="mb-4 font-weight-semi-bold">{{ $scheme['title'] }}</h4>
                                <h5 class="text-uppercase text-primary">{{ $scheme['description'] }}</h5>
                                <a href = "{{ route('schemes',$scheme['url']) }}" class="btn btn-outline-primary py-md-2 px-md-3">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>



@endsection
