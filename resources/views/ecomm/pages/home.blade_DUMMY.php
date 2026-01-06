@extends('ecomm.site')

@section('content')

<!-- Products Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">New Arrival</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">

            </div>
        </div>
    </div>
</div>

<!--Products End-->

<!-- Subscribe Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section ">
    <div class="row justify-content-md-center py-5 px-xl-5">
        <div class="col-md-6 col-12 py-5">
            <div class="text-center mb-2 pb-2">
                <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Why Choose Us</span></h2>

                <p style="text-align:justify;text-indent:40%;">
                    {!! @$about_content->about_desc !!}
                </p>

            </div>

            <div class="form-group text-center">
                <a class="btn btn-primary px-4" href="{{ url("{$ecommbaseurl}shop")}}">Shop Now</a>
                <a class="btn btn-primary px-4" href="{{ url("{$ecommbaseurl}contact")}}">Contact Us</a>
            </div>

        </div>
    </div>
</div>

<!-- Subscribe End -->

<!-- Products Start -->

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Shop By Category</span></h2>
    </div>
    @php
        $categories = ["Rings" => ["rings","ring","ring"], "Ear Ring" => ['ear_rings','ear','ear'], "Nose Pins" => ['nosepin','nose','nose'], "Mangal Sutra" => ['mangalsootra','mangal','mangal'], "Maang Tikka" => ['maangtikka','maang'], "Bangles" => ['bangels','bngl'], "Bracelet" => ['bracelet','brclt'], "Neckles" => ['nackles_set','nkls'], "Chain" => ['chains','chain']];
    @endphp
    <div class="row px-xl-5 pb-3">

        @foreach ($categories as $cati => $cats)
            @php
            $cat_dir = "assets/ecomm/products/".$cats[0]."/".$cats[1]."_".rand(3,5).".webp";
            @endphp
             <div class="col-lg-3 col-md-6 col-sm-12 col-6 pb-1">
                <div class="card product-item border-1 mb-4">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img class="img-fluid w-100" src="{{ asset("{$cat_dir}")}}" alt="">
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{ $cati}}</h6>
                    </div>
                    <div class="card-footer text-center d-flex justify-content-between bg-light border">
                        <a href="{{ url("{$ecommbaseurl}shop/{$cats[1]}")}}" class="btn btn-sm text-dark p-0 w-100">
                            <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
		
    </div>
</div>
<!-- Products End -->

<!-- Offer Start -->
<div class="container-fluid offer pt-5">
    <div class="row px-xl-5">

        @foreach($schemes as $sch)

            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                    <div class="row">

                        @if($sch->scheme_img)

                            <div class="col-md-4 text-center">
                                <img src="{{ asset('assets/images/schemes/'.$sch->scheme_img)}}" class="img-responsive offer_new_img" >
                            </div>

                        @endif

                        <div class="col-md-8">
                            <div class="position-relative" style="z-index: 1;">
                                <h5 class="text-uppercase text-primary mb-3"> {{ $sch->scheme_sub_head }} </h5>
                                <h1 class="mb-4 font-weight-semi-bold">{{ $sch->scheme_head }}</h1>
                                <a href="{{ url("{$ecommbaseurl}scheme")}}" class="btn btn-outline-primary py-md-2 px-md-3">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
<!-- Offer End -->

<!-- Categories Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Shop By Material</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 pb-1 col-6">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">15 Products</p>
                <a href="{{ url("{$ecommbaseurl}shop")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/gold_coins.webp")}}" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">Gold</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 pb-1 col-6">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">15 Products</p>
                <a href="{{ url("{$ecommbaseurl}shop")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/silver_coins.webp")}}" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">Silver</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 pb-1 col-6">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">15 Products</p>
                <a href="{{ url("{$ecommbaseurl}shop")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/gems_coins.webp")}}" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">Gems</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 pb-1 col-6">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">15 Products</p>
                <a href="{{ url("{$ecommbaseurl}shop")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/platinum_coins.webp")}}" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">Platinum</h5>
            </div>
        </div>
    </div>
</div>
<!-- Categories End -->

<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Our Collection</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset("assets/ecomm/products/ear_rings/ear_2.webp")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">Woman Jewellery</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset("assets/ecomm/products/chains/chain_2.jpg")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">Men Jewellery</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1  col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset("assets/ecomm/products/child_jwlr.webp")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">Child Jewellery</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 col-6 pb-1  col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset("assets/ecomm/products/nackles_set/nkls_12.webp")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">Neckles Set</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Products End -->

<!-- Vendor Start -->
<!--<div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-1.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-2.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-3.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-4.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-5.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-6.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-7.jpg" alt="">
                    </div>
                    <div class="vendor-item border p-4">
                        <img src="<?= @$host;?>assets/img/vendor-8.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>-->
<!-- Vendor End -->

@endsection

@section('javascript')

<script>

    $.post("{{ url("api/{$ecommbaseurl}") }}","",function(response){

    });

</script>

@endsection
