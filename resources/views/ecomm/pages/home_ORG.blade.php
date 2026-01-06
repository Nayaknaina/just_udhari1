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
@if(count($category_product)>0)
<div class="container pt-5 " id="home_category_shop">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Shop By Category</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        @foreach($category_product as $cati=>$cat)
        <div class="col-lg-3 col-md-6 col-sm-12 col-6 pb-1">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ url("ecom/products/{$cat->thumbnail_image}")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">{{ $cat->name }}</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop/{$cat->slug}")}}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<!-- Products End -->

<!-- Offer Start -->
<div class="container-fluid offer pt-5">
    <div class="row px-xl-5">

        @foreach($schemes as $sch)

            <div class="col-md-6 pb-4">
			
                <div class="position-relative text-center text-md-right schemes-box ">
                <!--<div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">-->
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
 @if(count($matter_menu) > 0)
<div class="container pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Shop By Matterial</span></h2>
    </div>
    @php 
        $mat_arr = ["Gold"=>"gold_coins.webp","Silver"=>"silver_coins.webp","Gems"=>"gems_coins.webp",'Platinum'=>"platinum_coin.webp","Artificial"=>"artificial_coin.webp"];
    @endphp
    <div class="row px-xl-5 pb-3">
        @foreach($matter_menu as $mind=>$matter)
        <div class="col-lg-3 col-md-6 pb-1 col-6 m-auto text-center">
            <div class="cat-item d-flex flex-column border mb-4 matterial_block" style="padding: 30px;">
                <!-- <p class="text-right">15 Products</p> -->
                <a href="{{ url("{$ecommbaseurl}shop/{$matter->slug}")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/{$mat_arr[$matter->name]}")}}" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">{{ $matter->name }}</h5>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<!-- Categories End -->


<!-- Catagol Start -->
 @if($catalog->count()>0)
<div class="container-fluid bg-secondary my-5 p-2">
    <div class="row justify-content-md-center py-2 px-xl-5">
        <div class="col-12 py-2 home_about" >
            <div class="text-center mb-2 pb-2">
                <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Our Catalog</span></h2>
            </div>
            <div class="catalog slideshow-container text-center">
                <a href="javascript:void(null);" id="catalog-slide-right">&#10095;</a>
                <ul class="catalog slides p-0" style="list-style: none;display: inline-flex;position: relative;width: 100%;overflow:hidden;justify-content: center;">
                    @foreach($catalog as $ctlgk=>$clog)
                    <li class="catalog slide col-md-4 col-6">
                        <img src="{{ url("ecom/cataloge/{$clog->images}") }}" alt="{{ $clog->name }}" class="img-thumbnail img-responsive" style="height:200px;min-height:auto;">
                    </li>
                    @endforeach
                    <!-- Add more images if needed -->
                </ul>
                <a href="javascript:void(null);" id="catalog-slide-left">&#10094;</a>
            </div>
            <div class="form-group text-center">
                <a class="btn btn-primary px-4" href="{{ url("{$ecommbaseurl}cateloge")}}">View All</a>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Catagol End -->

<!-- Products Start -->
@if(count($collection_product)>0)
<div class="container pt-5"  id="home_collection_shop">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Our Collection</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        @foreach($collection_product as $colli=>$coll)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ url("ecom/products/{$cat->thumbnail_image}")}}" alt="">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">{{ $coll->name }}</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border">
                    <a href="{{ url("{$ecommbaseurl}shop/{$coll->slug}") }}" class="btn btn-sm text-dark p-0 w-100">
                        <i class="fas fa-eye text-primary mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
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
