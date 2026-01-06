@extends('ecomm.site')
{{-- @section('title', "MG Jewellers ") --}}
@section('content')


<!-- Featured Start -->
<!--<div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-6 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>-->
<!-- Featured End -->


<!-- Products Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">New Arrival</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">
            @php                                                                                                    $curr_dir = "assets/ecomm/images/newarrived/";
                    $new_arrivals = scandir($curr_dir);
                @endphp
                @foreach($new_arrivals as $nind => $img)
                    @if($nind > 1)
                        <div class="card product-item border-1">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="{{ asset("{$curr_dir}{$img}") }}" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6>
                                    <h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="{{ url("{$ecommbaseurl}shop") }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View
                                    Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i
                                        class="fas fa-shopping-cart text-primary mr-1"></i>Add To
                                    Cart</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--Products End-->


<!-- Subscribe Start -->
<div class="container-fluid bg-secondary my-5">
    <div class="row justify-content-md-center py-5 px-xl-5">
        <div class="col-md-6 col-12 py-5">
            <div class="text-center mb-2 pb-2">
                <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Why Choose Us</span></h2>
                <p style="text-align:justify;text-indent:40%;">manufacturing and (since 1999) serving in India,
We started as makers of the jewellery and then as some small traders in the town and today with all due support from our family like customers we have reached so far with a customer base of 25k+.

We already won hearts of millions of people & we also wholeheartedly welcome our new customers to join us and be a part of our family.
Feel free to contact for any queries at any time. We will be HAPPY TO SERVE :)</p>
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
        <div class="col-md-6 pb-4">
            <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('assets/ecomm/scheme_girl.png')}}" alt="" class="img-responsive offer_new_img">
                    </div>
                    <div class="col-md-8">
                        <div class="position-relative" style="z-index: 1;">
                            <h5 class="text-uppercase text-primary mb-3">Now get jewellery warth of 50,000 in 5000 *</h5>
                            <h1 class="mb-4 font-weight-semi-bold">Subh Lakshmi Yojnaa</h1>
                            <a href="{{ url("{$ecommbaseurl}scheme")}}" class="btn btn-outline-primary py-md-2 px-md-3">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 pb-4">
            <div class="position-relative bg-secondary text-center text-md-left text-white mb-2 py-5 px-5">
                <div class="row">
                   
                    <div class="col-md-8">
                        <div class="position-relative" style="z-index: 1;">
                            <h5 class="text-uppercase text-primary mb-3">Now get jewellery warth of 50,000 in 5000 *</h5>
                            <h1 class="mb-4 font-weight-semi-bold">Labh Laksmi Yojnaa</h1>
                            <a href="{{ url("{$ecommbaseurl}scheme")}}" class="btn btn-outline-primary py-md-2 px-md-3">Explore</a>
                        </div>
                    </div>
                     <div class="col-md-4  text-center">
                        <img src="{{ asset('assets/ecomm/scheme_girl.png')}}" alt="" class="img-responsive offer_new_img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Offer End -->



<!-- Categories Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Shop By Matterial</span></h2>
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

    })
</script>    
@endsection