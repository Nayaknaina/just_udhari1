
@php

    $info = $common_content['info'] ;
    $socials = $common_content['socials'] ;

    $social_true = (!$socials) ? false : true ;
    $info_true = (!$info) ? false : true ;

    if($info_true){

        $head_mail = $info['email'] ;
        $head_fone = "+91-".$info['mobile_no'] ;
        $logo = "assets/ecomm/logos/".$info['logo'] ;

    }else{

        $head_mail = 'example@gamil.com' ;
        $head_fone = "+91-9876543210";
        $logo =  'assets/ecomm/logos/no_logo.png';

    }

@endphp

<!-- Topbar Start -->

<div class="container-fluid">
    <div class="row bg-secondary py-2 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">

                <a class="text-dark" href="javascript:void(null);">{{ strtoupper($head_mail)}}</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="javascript:void(null);">{{ $head_fone }}</a>

            </div>
        </div>

        <div class="col-lg-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">

                @if(!$social_true)

                    <a class="text-dark px-2" href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="#">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="#">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="#">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="#">
                        <i class="fab fa-youtube"></i>
                    </a>

                @else

                    @php

                        $head_social_common = $common_content['socials']->toArray() ;

                    @endphp

                    @if(!empty($head_social_common))
                        @foreach($head_social_common as $si=>$social)
                        <a class="text-dark px-2" href="{{ $social['social_link']}}" target="_blank">
                            <i class="{{ $social['social_icon_src'] }}"></i>
                        </a>
                        @endforeach
                    @endif

                @endif

            </div>
        </div>
    </div>

    <div class="row align-items-center py-3 px-xl-5">

        <div class="col-lg-3 d-none d-lg-block">
            <a href="" class="text-decoration-none">
                <div class="box">
                    <div class="light-sweep">
                    <div class="lightSweep">
                     <img src="{{ asset($logo)}}" class="img-responsive logo_image">
                    </div>
                        <img src="{{ asset($logo)}}" class="img-responsive logo_image">
                    </div>
                    </div>
            </a>
        </div>

        <div class="col-lg-7 col-6 text-left">
            <form action="">
                <div class="input-group search-input-box">
                    <input type="text" class="form-control" placeholder="Search for products">
                    <div class="input-group-append">
                        <span class="input-group-text bg-transparent text-primary sr-icon-box ">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-2 col-6 text-right">
            <a href="{{ url("{$ecommbaseurl}wishlist") }}" class="btn card-wish">
                <i class="fas fa-heart text-primary"></i>
                <span class="badge">0</span>
            </a>
            <a href="{{ url("{$ecommbaseurl}cart") }}" class="btn card-wish">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge">0</span>
            </a>
        </div>

    </div>
</div>

<!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid">
    <div class="row border-top px-xl-5 top_header">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow d-flex align-items-center justify-content-between bg-primary text-white w-100"
                data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="<?= (@$index) ? 'show shadow' : ''; ?> collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light category_dropdown"
                id="navbar-vertical" style="width: calc(100% - 30px); z-index: 2;">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link" data-toggle="dropdown">Collection <i
                                class="fa fa-angle-down float-right mt-1"></i></a>
                        <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                            <a href="" class="dropdown-item">Men</a>
                            <a href="" class="dropdown-item">Women</a>
                            <a href="" class="dropdown-item">Child</a>
                            <a href="" class="dropdown-item">Neckles Set</a>
                        </div>
                    </div>
                    <a href="{{ url("{$ecommbaseurl}shop/ring")}}" class="nav-item nav-link">Rings</a>
                    <a href="{{ url("{$ecommbaseurl}shop/ear")}}" class="nav-item nav-link">Ear Pin</a>
                    <a href="{{ url("{$ecommbaseurl}shop/nose")}}" class="nav-item nav-link">Nose Pin</a>
                    <a href="{{ url("{$ecommbaseurl}shop/bngl")}}" class="nav-item nav-link">Bangle</a>
                    <a href="{{ url("{$ecommbaseurl}shop/brclt")}}" class="nav-item nav-link">Bracelate</a>
                    <a href="{{ url("{$ecommbaseurl}shop/chain")}}" class="nav-item nav-link">Chain</a>
                    <a href="{{ url("{$ecommbaseurl}shop/nkls")}}" class="nav-item nav-link">Neckles</a>
                    <a href="{{ url("{$ecommbaseurl}shop/maang")}}" class="nav-item nav-link">MaangTikka</a>
                    <a href="{{ url("{$ecommbaseurl}shop/mangal")}}" class="nav-item nav-link">Mangalshutra</a>
                </div>
            </nav>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0 top_menue_bar">
                <a href="{{$ecommbaseurl}}" class="text-decoration-none d-block d-lg-none">
                    <img src="{{ asset("assets/ecomm/logo.webp")}}" class="img-responsive logo_image">
                    <!-- <h1 class="m-0 display-5 font-weight-semi-bold"><span
                            class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1> -->
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @php
                    $$activemenu = 'active';
                @endphp
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">

                    <div class="navbar-nav mr-auto py-0">
                        <a href="{{ url("{$ecommbaseurl}") }}" class="nav-item nav-link {{ @$index }}">Home</a>
                        <a href="{{ url("{$ecommbaseurl}about") }}" class="nav-item nav-link {{ @$about }}">About</a>
                        <a href="{{ url("{$ecommbaseurl}scheme") }}" class="nav-item nav-link {{ @$scheme}}">Scheme</a>
                        <a href="{{ url("{$ecommbaseurl}shop") }}" class="nav-item nav-link {{ @$shop }}">Online Shop</a>

                        <!-- <a href="detail.php" class="nav-item nav-link">Shop Detail</a> -->

                        {{-- @if($branches)
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Branched</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    @foreach($branches as $key => $branch)
                                        <a href="{{ url($branch->branch_name)}}"
                                            class="dropdown-item">{{ $branch->branch_name . (($branch->vndr_parent == 0) ? "( Main )" : "")}}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif --}}

                        <a href="{{ url("{$ecommbaseurl}contact") }}"
                            class="nav-item nav-link {{ @$contact }}">Contact</a>

                        <a href="{{ url("{$ecommbaseurl}shop-location") }}" class="nav-item nav-link @{{  @$shop_location }}"> Shop Location </a>

                    </div>
                    <div class="navbar-nav ml-auto py-0">
                        <a href="{{ url("{$ecommbaseurl}login") }}" class="nav-item nav-link {{ @$login }}">Login</a>
                        <a href="{{ url("{$ecommbaseurl}register") }}"
                            class="nav-item nav-link {{ @$register }}">Register</a>
                    </div>
                </div>
            </nav>

        </div>
        <div class="col-lg-3 d-lg-none d-block">
            <a class="btn shadow d-flex align-items-center justify-content-between bg-primary text-white w-100 category_drop_btn_mob"
                data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light category_dropdown category_dropdown_mob"
                id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link" data-toggle="dropdown">Collection <i
                                class="fa fa-angle-down float-right mt-1"></i></a>
                        <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                            <a href="" class="dropdown-item">Men</a>
                            <a href="" class="dropdown-item">Women</a>
                            <a href="" class="dropdown-item">Child</a>
                            <a href="" class="dropdown-item">Neckles Set</a>
                        </div>
                    </div>
                    <a href="{{ url("{$ecommbaseurl}shop/ring")}}" class="nav-item nav-link">Rings</a>
                    <a href="{{ url("{$ecommbaseurl}shop/ear")}}" class="nav-item nav-link">Ear Pin</a>
                    <a href="{{ url("{$ecommbaseurl}shop/nose")}}" class="nav-item nav-link">Nose Pin</a>
                    <a href="{{ url("{$ecommbaseurl}shop/bngl")}}" class="nav-item nav-link">Bangle</a>
                    <a href="{{ url("{$ecommbaseurl}shop/brclt")}}" class="nav-item nav-link">Bracelate</a>
                    <a href="{{ url("{$ecommbaseurl}shop/chain")}}" class="nav-item nav-link">chain</a>
                    <a href="{{ url("{$ecommbaseurl}shop/nkls")}}" class="nav-item nav-link">Neckles</a>
                    <a href="{{ url("{$ecommbaseurl}shop/maang")}}" class="nav-item nav-link">MaangTikka</a>
                    <a href="{{ url("{$ecommbaseurl}shop/mangal")}}" class="nav-item nav-link">Mangalshutra</a>
                </div>
            </nav>
        </div>
    </div>
    <?php if (@$index) { ?>
    <div class="row px-xl-5">
        <div class="col-md-3">
        </div>
        <div class="col-md-9">
            <div id="header-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" style="height: 410px;">
                        <img class="img-fluid" src="{{ asset('assets/ecomm/images/slider/slide_1.webp')}}" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h4 class="text-light text-uppercase font-weight-medium mb-3">Keep This Secreat</h4>
                                <h3 class="display-4 text-white font-weight-semi-bold mb-4">You are more Beautifull</h3>
                                <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" style="height: 410px;">
                        <img class="img-fluid" src="{{ asset('assets/ecomm/images/slider/slide_2.webp')}}" alt="Im
                           age">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h4 class="text-light text-uppercase font-weight-medium mb-3">How You Looks</h4>
                                <h3 class="display-4 text-white font-weight-semi-bold mb-4">Beauty Enhancement</h3>
                                <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                    <div class="btn btn-dark" style="width: 45px; height: 45px;">
                        <span class="carousel-control-prev-icon mb-n2"></span>
                    </div>
                </a>
                <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                    <div class="btn btn-dark" style="width: 45px; height: 45px;">
                        <span class="carousel-control-next-icon mb-n2"></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<!-- Navbar End -->

