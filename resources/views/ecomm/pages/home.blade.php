@extends('ecomm.site')

@section('content')



<div class="container-fluid offer pt-5">
	<div class="text-center mb-4">
        <!--<h2 class="section-title px-5"><span class="px-2">Shop By Category</span></h2>-->
        <h2 class="cstm-section-title px-5 mb-3"><span class="">Schemes</span></h2>
    </div>
    <div class="row px-xl-5 scheme-container">
         @foreach($schemes as $sch)
        <div class=" col-md-4 pb-4">
            <div class="scheme-card">
                <div class="scheme-content">
                    <h4>{{ $sch->scheme_sub_head }}</h4>
                    <h2>{{ $sch->scheme_head }}</h2>
                    
                </div>
                <div class="scheme-detail-block ">
                    <a href="{{ url("{$ecommbaseurl}scheme")}}" class="explore">Explore</a>
                </div>

            </div>
        </div>
        @endforeach        
    </div>
</div>

<!-- Products Start -->
@if(count($category_product)>0)
<div class="container-fluid pt-5 " id="home_category_shop">
    <div class="text-center mb-4">
        <!--<h2 class="section-title px-5"><span class="px-2">Shop By Category</span></h2>-->
		<h2 class="cstm-section-title px-5 mb-3"><span class="">Shop By Category</span></h2>
    </div>
    <div class="row px-xl-5 py-5 content">
        @foreach($category_product as $cati=>$cat)
        @for($ci=0;$ci<=3;$ci++)
        <div class="col-lg-3 col-md-6 col-sm-12 col-6">
            <div class="card home product-item border-1">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ url("ecom/products/{$cat->thumbnail_image}")}}" alt="" loading="lazy">
                </div>
                <div class="card-body border-top text-center p-0">
                    <h4 class="text-truncate mb-0  p-3 round-border">{{ $cat->name }}</h4>
                </div>
                <div class="card-footer text-center d-flex justify-content-between  p-0 bg-transparent">
                    <a href="{{ url("{$ecommbaseurl}shop/{$cat->slug}")}}" class="btn p-3 p-0 w-100 btn-default-alt">
                        <i class="fas fa-eye mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        @endfor
        @endforeach



    </div>
</div>
@endif
<!-- Products End -->


{{-- -
<!-- Offer Start -->
@if($schemes->count()>0)
<div class="container-fluid offer pt-5">
	<div class="text-center mb-4">
        <!--<h2 class="section-title px-5"><span class="px-2">Shop By Category</span></h2>-->
        <h2 class="cstm-section-title px-5 mb-3"><span class="">Schemes</span></h2>
    </div>
    <div class="row px-xl-5">

        @foreach($schemes as $sch)

            <div class="col-md-6 pb-4">
			
                <div class="position-relative text-center text-md-right schemes-box ">
                <!--<div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">-->
                    <div class="row">

                        @if($sch->scheme_img)

                            <div class="col-md-4 text-center">
                                <img src="{{ asset('assets/images/schemes/'.$sch->scheme_img)}}" class="img-responsive offer_new_img" loading="lazy">
                            </div>

                        @endif

                        <div class="col-md-8">
                            <div class="position-relative" style="z-index: 1;">
                                <h5 class="text-uppercase text-primary mb-3 scheme_head"> {{ $sch->scheme_sub_head }} </h5>
                                <h1 class="mb-4 font-weight-semi-bold scheme_desc">{{ $sch->scheme_head }}</h1>
                                <a href="{{ url("{$ecommbaseurl}scheme")}}" class="btn btn-outline-primary py-md-2 px-md-3">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
@endif
--}}
<!-- Offer End -->

<!-- Categories Start -->
 
 @if(count($matter_menu) > 0)
<div class="container-fluid pt-5" id="shop_by_metal">
    <div class="text-center mb-4">
        <!-- <h2 class="section-title px-5"><span class="px-2">Shop By Matterial</span></h2> -->
        <h2 class="cstm-section-title px-5 mb-3"><span class="">Shop By Material</span></h2>
    </div>
    @php 
        $mat_arr = ["Gold"=>"gold_metal.png","Silver"=>"silver_metal.png","Gems"=>"gems_coins.webp",'Platinum'=>"platinum_coin.webp","Artificial"=>"artificial_metal.png",'Stone'=>'stone_metal.png'];
    @endphp
    <div class="row px-xl-5 p-5 content" >
        @foreach($matter_menu as $mind=>$matter)
		
        <div class="col-lg-3 col-md-6 pb-1 col-6 m-auto text-center">
            <div class="cat-item d-flex flex-column border mb-4 matterial_block" >
				@if($matter->name!="Franchise Jewellery")
                <!-- <p class="text-right">15 Products</p> -->
                <a href="{{ url("{$ecommbaseurl}shop/{$matter->slug}")}}" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="{{ asset("assets/ecomm/images/{$mat_arr[$matter->name]}")}}" alt="" loading="lazy">
                </a>
                <h5 class="font-weight-semi-bold m-0">{{ $matter->name }}</h5>
				@endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
<!-- Categories End -->


<!-- Catagol Start -->
 @if($catalog->count()>0)
<div class="container-fluid my-5 pt-2" id="catelog_area">
	<div class="text-center mb-2 pb-2">
        <!-- <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Our Catalog</span></h2> -->
        <h2 class="cstm-section-title px-5 mb-3"><span class="">Our Catalogue</span></h2>
    </div>
    <div class="row justify-content-md-center content">
        <div class="col-12  home_about py-5" >
            <div class="catalog slideshow-container text-center">
                <a href="javascript:void(null);" id="catalog-slide-right">&#10095;</a>
                <ul class="catalog slides p-0" style="list-style: none;display: inline-flex;position: relative;width: 100%;overflow:hidden;justify-content: center;">
                    @foreach($catalog as $ctlgk=>$clog)

                    <li class="catalog slide col-md-4 col-6">
                        <img src="{{ url("ecom/cataloge/{$clog->images}") }}" alt="{{ $clog->name }}" class="img-thumbnail img-responsive" style="height:200px;min-height:auto;" loading="lazy">
                    </li>
                    @endforeach
                    <!-- Add more images if needed -->
                </ul>
                <a href="javascript:void(null);" id="catalog-slide-left">&#10094;</a>
            </div>
            <div class="form-group text-center m-0">
                <a class="btn btn-outline-primary px-4" href="{{ url("{$ecommbaseurl}cateloge")}}">View All</a>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Catagol End --> 

<!-- Products Start -->
@if(count($collection_product)>0)
<div class="container-fluid pt-5"  id="home_collection_shop">
	<div class="text-center mb-4">
        <h2 class="cstm-section-title px-5 mb-3"><span class="">Our Collection</span></h2>
    </div>
    <div class="row px-xl-5  py-5 content">
        @foreach($collection_product as $colli=>$coll)
         @for($cci=0;$cci<=3;$cci++)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 col-6">
            <div class="card product-item border-1 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ url("ecom/products/{$cat->thumbnail_image}")}}" alt="" loading="lazy">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">{{ $coll->name }}</h6>
                </div>
                <div class="card-footer text-center d-flex justify-content-between bg-light border p-0">
                    <a href="{{ url("{$ecommbaseurl}shop/{$coll->slug}") }}" class="btn p-3 p-0 w-100 btn-default-alt">
                        <i class="fas fa-eye  mr-1"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>
        @endfor
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

{{--$.post("{{ url("api/{$ecommbaseurl}") }}","",function(response){

    });--}}

</script>

@endsection
