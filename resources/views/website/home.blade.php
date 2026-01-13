@extends('layouts.website.app')

@section('css')

    <style>

        @media only screen and (max-width: 768px) {

            .main-slider .banner-btn .default-btn {
                    font-weight: 600;
                    padding: 12px 16px;
                    font-size: 17px;
            }
            .banner-btn {
                position: absolute;
                bottom: 10%;
                padding-left: 0%;
                width: 100%;
                text-align: center;
            }

            .btn-bg-two {
                background-color: #FFF;
            }

            .default-btn {
                display: inline-block;
                padding: 12px 32px;
                color: #ee5d19;
                text-align: center;
                position: relative;
                overflow: hidden;
                z-index: 1;
            }
            .btn-bg-one {
                background-color: #f5f4f1;
            }

        }

    </style>

@endsection

@section('content')

<div class="banner-area">
    <div class="main-slider">
       <img src="{{ asset('assets/images/bg/jwellybannerofficial.webp') }}" alt="" class="d-none d-lg-block">
<img src="{{ asset('assets/images/bg/jwellybannerofficial_mobile.webp') }}" alt="" class="d-block d-lg-none" style="width: 100%;object-fit: cover;">
        <div class="banner-btn">
            <a href="#" class="default-btn btn-bg-two border-radius-50"> Start Now - It`s Free </a>
            <a href="#" class="default-btn btn-bg-one border-radius-50 ml-20"> Schedule Demo <i class="bx bx-chevron-right"></i></a>
            </div>
    </div>
</div>

<div class="about-area ptb-70">
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-lg-4">
                <div class="about-play">
                <img src="{{ asset('assets/images/JwellyERP.webp') }}" alt="About Images">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-content ml-25">
                    <div class="section-title">
                    <h2> Why Just Udhari  </h2>
                    </div>
                    <div class="row">
                    <div class="col-lg-12">

                    @php

                        $why_ju = [
                            ['title' => 'For your total peace of mind, it is outfitted with unique security tools that were created especially for jewelry businesses'] ,
                            ['title' => 'The Jwelly Owner mobile app gives you control over your business'] ,
                            ['title' => 'India`s Best Selling Jewelry Software Award for 2019'] ,
                            ['title' => 'Over 25 Years of Jewellery Business Management Research'] ,
                            ['title' => 'An extensive network of services and a fully customizable package'] ,
                            ['title' => 'Over 50,000 happy customers throughout India'] ,
                            ['title' => 'Accounts, Stock, CRM, Loyalty Programs, Cataloging, and Mobile App: The Whole Solution'] ,
                            ['title' => 'Serving every market segment: manufacturing, wholesale, retail, loose gold, diamonds, and money lending'] ,
                        ] ;

                    @endphp

                    <ul class="about-list text-start">

                    @foreach ($why_ju as $why )

                        <li><i class="bx bxs-check-circle"></i> {{ $why['title']  }}</li>

                    @endforeach

                    </ul>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class = "demo-section" id="free-demo">

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title pb-4 text-center ">
                    <h2> GET YOUR FREE DEMO NOW </h2>
                    <div class="bottomLine"></div>
                </div>
            </div>

            <div class="col-lg-6">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" class = "form-control" placeholder = "Enter Name" >
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" class = "form-control" placeholder = "Enter Email" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class = "form-control" placeholder = "Enter Phone No" >
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for = "">
                            <input type = "checkbox" class = "form-co ntrol" placeholder = "Enter Phone No" > I agree to receive the promotional SMS messages from the Just Udhari. </label>
                        </div>
                    </div>

                    <div class="col-lg-12 ">
                        <button type="submit" class="default-btn btn-bg-two">
                        Log In Now
                        </button>
                    </div>

                </div>

            </div>

            <div class="col-lg-6">
                <img src="{{ asset('assets/images/jewellery-soft.webp') }}" class = "img-fluid">
            </div>
        </div>
    </div>

</section>

<section class = "growth-area">
    <div class="container">
        <div class="row">
        <div class="col-lg-4">
            <img src = "{{ asset('assets/images/increase-sale-screen.webp') }}" class = "img-fluid">
        </div>
        <div class="col-lg-8">
            <div class="section-title pb-4 ">
                <h2> INCREASE YOUR SALE WITH ONLINE STORE </h2>
            </div>
            <ul class="growth-list text-start">
                <li><i class="bx bxs-check-circle"></i> E-Commerce Website </li>
                <li><i class="bx bxs-check-circle"></i> Search Engine Optimisation </li>
                <li><i class="bx bxs-check-circle"></i> Jewellery Customisation</li>
                <li><i class="bx bxs-check-circle"></i> Social Media Marketing </li>
                <li><i class="bx bxs-check-circle"></i> Virtual Try On </li>
                <li><i class="bx bxs-check-circle"></i> Area Wise </li>
                <li><i class="bx bxs-check-circle"></i> Flexible Rates Option </li>
                <li><i class="bx bxs-check-circle"></i> Gender Wise </li>
                <li><i class="bx bxs-check-circle"></i> Payment Gateway </li>
                <li><i class="bx bxs-check-circle"></i> Age Group Wise </li>
                <li><i class="bx bxs-check-circle"></i> Shipping & Tracking </li>
                <li><i class="bx bxs-check-circle"></i> Occasion Wise </li>
            </ul>
        </div>
        </div>
    </div>
</section>

     @php

         $jwl  = [

            ['title'=>'Jewellery Software', 'images' => 'jewellery-soft.webp']  ,
            ['title'=>'Jewellery E-Commerce', 'images' => 'ecom-soft.webp']  ,
            ['title'=>'Money Lending Software', 'images' => 'money-lend-soft.webp']  ,
            ['title'=>'Gold Scheme Management', 'images' => 'gold-scheme.webp']  ,

        ]

     @endphp

<section class="services-area-two pt-100 pb-70">
    <div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="section-title pb-4 text-center ">
                <h2> OUR PRODUCTS </h2>
                <div class="bottomLine"></div>
            </div>
        </div>
    </div>

        <div class="row pt-45">

        @foreach ($jwl as  $stf)

            <div class="col-lg-3">
            <div class="services-item">
            <a href="#">
            <img src ="{{ asset('assets/images/thumbnail/'.$stf['images'].'') }}" alt="Images">
            </a>
            <div class="content">
            <h3><a href="#"> {{ $stf['title'] }} </a></h3>
            </div>
            </div>
            </div>

        @endforeach

        </div>
    </div>
</section>

<section class="features-section pt-30 pb-30">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="section-title pb-4 text-center ">
                    <h2> OUR FEATURES </h2>
                    <div class="bottomLine"></div>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/save-time.webp') }}" alt="save your time">
                        <h5 class="mb-0"> Save Time Grow Your Business </h5>
                    </div>
                    <p class=""> Access your company via PC software and the Just Udhari mobile app. By managing several branches with a single click, Just Udhari streamlines your operations and saves you time </p>
                </div>
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex  align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/profit.webp') }}" alt="Profit and Loss">
                        <h5 class="mb-0"> Stay One-Step Ahead </h5>
                    </div>
                    <p class=""> By continuously monitoring your daily <b> profit and loss </b> , Just Udhari will help you stand out. Just Udhari will constantly assist you in expanding your business and will keep you informed of your daily <b> profit and loss</b> </p>
                </div>
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex  align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/cash.webp') }}" alt="Cash Manage">
                        <h5 class="mb-0"> Cash Flow Management </h5>
                    </div>
                    <p class=""> With the help of our Just Udhari software, observe how your business grows. Keep a <b> close eye on your money and manage </b> it as best you can.</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="FeaturesImg"><img src ="{{ asset('assets/images/software/moblie-app.webp') }}" alt="Just Udhari mobile app" title="mobile app" class="img-responsive"></div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 d-md-block d-none">
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex  align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/accounts.webp') }}" alt="Manage-Accounts">
                        <h5 class="mb-0"> Automated Accounting </h5>
                    </div>
                    <p class=""> Don't worry about those difficult computations anymore. Just Udhari accounting and billing software is now available to relieve your worry. Let the top AI algorithm take care of you while you relax. You may now easily handle your <b> GST transactions and bookkeeping </b></p>
                </div>
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex  align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/business.webp') }}" alt="Enhance Your Business">
                        <h5 class="mb-0"> Expand Your Business </h5>
                    </div>
                    <p class=""> The store management software from Just Udhari makes it simple to expand your company.You will <b> now be able to manage your customers with accurate analysis and repeat business.</b> </p>
                </div>
                <div class="featuresLeft mb-20">
                    <div class="featureHead d-flex  align-items-center justify-content-start"><img src="{{ asset('assets/images/icon/power-per.webp') }}" alt="Powerful Performance">
                        <h5 class="mb-0"> Powerful performance </h5>
                    </div>
                    <p class="">Our Jewellery Software's most recent and updated technology will give you a quick and safe accounting system. <b> We offer centralized desktop, mobile, and cloud-based solutions for seamless connectivity for your convenience.</b> </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mobile-sec mobileContent pt-40 pb-40 position-relative">
    <div class="container">
        <div class="row d-flex flex-wrap ">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="mobileconPara">
                    <h2 class="text-left mt-10 mb-10">India's Finest Jewelry Apps!</h2>
                        <h4 class="text-left mb-20">Transition your Retail, Super Market, Manufacturing Businesses Digital Way</h4>
                    <div class="mobileBtn mt-40">
                        <div class="d-flex align-items-center ">
                            <div class="mr-10"> <button class="btn">Experience Now</button> </div>
                            <div class=""> <button class="btn orangBtn">Schedule a Demo</button> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="mobileImg mb-20"> <img class="img-responsive center-block" src="{{ asset('assets/images/software/product_banner.png')}}" alt="Businesses Digital Way"> </div>
            </div>
        </div>
    </div>
</section>

<section class="juproduct jwlbg">

    <div class="container">
        <div class="row FeaturesSec">
            <!--DemoSec-->
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                <div class="SoftwareImg mb-20"> <img class="img-responsive" src="{{ asset('assets/images/shwroom.jpeg') }}" alt="Just Udhari Jewellery Software" title="Jewellery Software"> </div>
            </div>
            <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                <div class="SoftwareCon">
                    <div class="softHeading">
                        <h5> Jewellery Software </h5>
                    </div>
                    <div class="softPara">
                        <h5> Why Just Udhari Jewellery Software? </h5>
                        <p class="text-justify mt-10 mb-0">A one-stop shop to help all jewelry producers, distributors, and retailers boost sales and productivity. The best-rated and most reliable jewelry program, utilized by jewelers globally as well as in India, is called Just Udhari. Because of the software's significant capabilities, uniqueness, and ease of use, our clients are satisfied. It is made to work for both small and large businesses.</p>
                        <ul class="listStyle">
                            <li><i class="fa fa-angle-double-right"></i> Best AI Accounts </li>
                            <li><i class="fa fa-angle-double-right"></i> RFID inventory &amp; stock management </li>
                            <li><i class="fa fa-angle-double-right"></i> GST &amp; Loan reports </li>
                            <li><i class="fa fa-angle-double-right"></i> Bar-code tracking</li>
                            <li><i class="fa fa-angle-double-right"></i> Cloud system </li>
                            <li><i class="fa fa-angle-double-right"></i> Android mobile apps &amp; e-commerce integration </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-md-12">
                <div class="softFeatr">
                    <nav>
                        <div class="nav nav-tabs softFeature" id="nav-tab" role="tablist">
                          <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Bar-code / RFID Tags</button>
                          <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false"> Jewellery Stock Report </button>
                          <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false"> Jewellery Catalogue </button>
                        </div>
                      </nav>
                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6>Bar-code / RFID Tags</h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog mb-10">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/jewellery-barcode.png')}}" alt="Drag and Drop Tags"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> <b>Drag &amp; Drop :</b> Customize your barcode the way you want. also, get the best printed tags with our thermal of laser ink-jet printers. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex  tabInnerLog">
                                                        <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/rfid-tag.png')}}" alt="Barcode RFID Tags"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> <b>RFID Tags:</b> tally your stock in the Fastest way possible. RFID tag solution. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex  tabInnerLog mb-10">
                                                        <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/rfid-system.png')}}" alt="RFID Gate System Tags"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> <b>RFID Gate System:</b> Just Udhari Jewellery Software provide you the best security with RFID gate the gate alarm buzzes when non-billed stock passes though it. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex  tabInnerLog">
                                                        <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/prn-setting.png')}}" alt="PRN Setting Tags"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> <b>PRN Setting:</b> Get instant barcode as soon as you add the stock. getting your product barcode is easy now. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 d-lg-block d-none col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/rfid-scanner.jpg')}}" alt="Rfid Scanner" title="Rfid Scanner"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"><div class="tabinnerSec">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <div class="LeftCon">
                                        <h6> Jewellery Stock Report </h6>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="d-flex align-items-start tabInnerLog mb-10">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/trending-icon.png')}}" alt="Trending Product List"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> <b>Trending Product List with Store Management:</b> Keep a track of the most wanted products in your store to the least wanted. Just Udhari makes it Convenient for you to increase your sales.
                                                            </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex  tabInnerLog">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/filter.png')}}" alt="Specified Fillters"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> <b>Specified Fillters:</b> In Stock Management Report each columns has specified fillter to get the precise report. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="d-flex  tabInnerLog">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/stock-report.png')}}" alt="Stock Report"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> <b>Stock Report:</b> The Stock Reports gives you a glimpse of all your stock Available, purchase, sold out or even deleted. More over you will get these records with images. </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/report.jpg')}}" alt="jewellery Stock Report"> </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"><div class="tabinnerSec">
                            <div class="row">
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <div class="LeftCon">
                                        <h6> Jewellery Catalogue </h6>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="d-flex align-items-start tabInnerLog mb-10">
                                                    <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/ecom-portal.png')}}" alt="Ecom Portal"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> Search jewellery stock with images or by price range like e-commerce portals and also check the product information &amp; price quote. Get the information for a particular product and quote price.
                                                            </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex  tabInnerLog">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/calculator.png')}}" alt="Calculation"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> Provide the quotation to the clients with a single click. No TDS Calculation to be done. let the software reduce the work. </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="d-flex  tabInnerLog">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/amazing-filter.png')}}" alt="Amazing filter"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> Amazing filter for precise search. search jewellery by price range or weight range. </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex  tabInnerLog">
                                                    <div class="tabBImg"> <img class="img-responsive" src="{{ asset('assets/images/icon/price-final.png')}}" alt="Jewellery catalogue"> </div>
                                                    <div class="tabContent">
                                                        <p class="mb-0"> Jewellery catalogue shows the complete details about the product bifurcation like making charges, stone valuation, Tax amount &amp; final price. </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/jewelley-catalogue.jpg')}}" alt="jewellery catalogue"> </div>
                                </div>
                            </div>
                        </div>
                        </div>

                      </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mobile-sec mobileContent pt-40 pb-40 position-relative">
    <div class="container">
        <div class="row d-flex flex-wrap ">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="mobileconPara">
                    <h2 class="text-left mt-10 mb-10">India's Best Money Lending Software!
                        </h2><h4 class="text-left mb-20">Transition your Retail, Super Market, Manufacturing Businesses Digital Way</h4>

                    <div class="mobileBtn mt-40">
                        <div class="d-flex align-items-center ">
                            <div class=""> <button class="btn orangBtn">View more </button> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="mobileImg mb-20"> <img class="img-responsive center-block" src="/assets/images/software/product_banner.png" alt="Businesses Digital Way"> </div>
            </div>
        </div>
    </div>
</section>

<section class = " juproduct jwlbg ">

     <div class="container">
        <div class="row FeaturesSec">
            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                <div class="SoftwareImg mb-20"> <img class="img-responsive" src="{{ asset('assets/images/product/money-lending.jpg')}}" alt="money lending software"> </div>
            </div>
            <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                <div class="SoftwareCon">
                    <div class="softHeading">
                        <h5> Money Lending Software </h5>
                    </div>
                    <div class="softPara">
                        <h5> Rank#1 Software for Girvi or Money Lending </h5>
                        <p class="text-justify mt-10"> Now add loans with a single click. Just Udhari's Money Lending software is the only software to provide you with the best loan assistance. Just Udhari automatically calculates the loan interest amount even if there is a change
                            in the metal value software provide both interest options like simple and compound interest with calculation for monthly, Quarterly, Half yearly options. </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-10">
            <div class="col-md-12">
                <div class="softFeatr">
                <nav>
                    <div class="nav nav-tabs softFeature "  id="nav-tab" role="tablist">
                        <button  class="nav-link active "  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#emi"> Girvi/Loan/EMI/Finance </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#customer"> Customer Management </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#money"> Money Deposit Options </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#loans"> Third Party Loan's </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#sms"> SMS Notifications </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#firm"> Multi Firm Management </button>
                        <button  class="nav-link"  data-bs-toggle="tab" data-toggle="tab" data-bs-target = "#features2"> Features </button>
                    </div>
                </nav>
                    <div class="tab-content">
                        <div id="emi" class="tab-pane  in active">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6> Just Udhari is a leading software to manage all type of loans. <b>Gold Loan, Personal Loan, unsecured Loan, EMI Loans or Daily Collection finance Loan</b> it is early accessible &amp; very user friendly. </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/finance.png')}}" alt="Auto calculate"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Auto calculate simple / compound interest with yearly, after 2 years or after 3 years compounding, also provide the option to calculate full month, half month, weekly and day wise interest. </p>
                                                            <p>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/gold-loan.png')}}" alt="finance loans"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Gold loans, personal loans, unsecured loans, monthly EMI loans or daily collection finance loans. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/money-deposit2.jpg')}}" alt="Money-Deposit Loan"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="customer" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6> Manage customer records with multiple photos, complete KYC with fingerprints, digital signature, references and show all active transactions at customer info-graphic home page. </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/cust-report.png')}}" alt="Customer records"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Customer records with multiple photos, complete KYC with finger prints, digital signature, references and show all active transactions at customer info-graphic home page. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/details.png')}}" alt="customer details"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Manage your customer details &amp; Update Customer Information. Search customer with phone number or any unique information. Complete customer management solution to increase your revenues. </p>
                                                            <p>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/customer_m2.jpg')}}" alt=" Customer Management"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="money" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6> Just Udhari provides different money deposit options, user can pay amount in principal amount, interest amount. Also deposit full interest amount and update new loan date with releasing the existing loan. </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/saving-money.png')}}" alt="Quick Deposit"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Quick Deposit &amp; updation of loans in the faster way possible. Accept money deposits with simple &amp; compound interest. Increase your profits by using the Just Udhari money lending Software.
                                                                </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/money-deposit.png')}}" alt="Money deposit"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Money deposit in principle amount, interest payment, new loan date change, partial principle payment, simple money deposit and deposit with interest left, software has different deposit options.
                                                                </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/money-deposit3.jpg')}}" alt="Money Deposit Options"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="loans" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6>Third-party or money-lender gold loan transfers can be handled easily by Just Udhari. You can check all the active or released loan transactions of money lenders with a single click. </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/transaction.png')}}" alt="Easy Transactions"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Maintain easy Transactions with money lenders and provide loans to the client. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/release-loan.png')}}" alt="Released Loans"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Check money lending reports with released loans transactions and achieve loan transaction. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/gold-loan-emi-2.jpg')}}" alt="Third Party Loans"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sms" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6> You can send promotional and transactional SMS messages to your customers. With one click you can send SMS to your all customers and check the SMS log success or fail report </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/sms.png')}}" alt="Send default message"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Customize your messages a send default messages to client with just a click. Always be in touch with clients with SMS. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/mobile-sms.png')}}" alt="SMS messages"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> You can send promotional and transactional SMS messages to your customers. On one click you can send SMS to your all customer's and also check the SMS log report. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/sms-noti-2.jpg')}}" alt="SMS Notification"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="firm" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                        <div class="LeftCon">
                                            <h6> In the Multi-firms or branches option, you can create more than one firm for your business transactions. Also, you can manage multiple branches from the administrator panel and check all branches transactions.
                                                </h6>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/firm.png')}}" alt="multiple firms"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> Most Useful feature of Just Udhari to create multiple firms &amp; access your business in systematic way. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                    <div class="d-flex align-items-start tabInnerLog">
                                                        <div class="tabBImg mt-10"> <img class="img-responsive" src="{{ asset('assets/images/icon/firm-management.png')}}" alt="Multi firm option"> </div>
                                                        <div class="tabContent">
                                                            <p class="mb-0"> In Multi-firm option you can create more than one firms for your business transactions. Also you can manage multiple branches from administrator panel and check all transactions. </p>
                                                            <p> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <div class="RightCon"> <img class="img-responsive" src="{{ asset('assets/images/product/firm-management-3.jpg')}}" alt="Multi Firm Management"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="features2" class="tab-pane ">
                            <div class="tabinnerSec">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <div class="LeftCon">
                                            <ul class="leftList">
                                                <li> <i class="fa fa-angle-double-right"></i> Account Management </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Deposit Receipt Print </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Interest Calculation </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Loans Scheme's </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Deposit Money </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Daily Entries Report </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Profit &amp; Loss Report </li>
                                                <li> <i class="fa fa-angle-double-right"></i> SMS Notifications </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <div class="LeftCon">
                                            <ul class="leftList">
                                                <li> <i class="fa fa-angle-double-right"></i> Withdraw Money </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Account Statement </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Loss Loan's Report </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Interest Provisions </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Accounting Features </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Pass Book Printing </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Auto Database Backup </li>
                                                <li> <i class="fa fa-angle-double-right"></i> General Ledger </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <div class="LeftCon">
                                            <ul class="leftList">
                                                <li> <i class="fa fa-angle-double-right"></i> Term Deposit Scheme </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Receipt / Payment </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Members Profile </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Savings Accounts </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Branch Management </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Thumb Impression </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Customer Photo </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Scanned Documents </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                        <div class="LeftCon">
                                            <ul class="leftList">
                                                <li> <i class="fa fa-angle-double-right"></i> Daily Balance Analysis </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Loan Installments </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Accounting Entries </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Expense Management </li>
                                                <li> <i class="fa fa-angle-double-right"></i> Shares Assignment </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>

    </section>

    <section class="mobile-sec mobileContent pt-40 pb-40 position-relative">
        <div class="container">
            <div class="row d-flex flex-wrap ">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="mobileImg mb-20"> <img class="img-responsive center-block" src="{{ asset('assets/images/software/product_banner.png')}}" alt="Businesses Digital Way"> </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="mobileconPara">
                        <h3 class="text-left mt-10 mb-10">Transition your Retail, Super Market, Manufacturing Businesses Digital Way</h3>
                        <h4 class="text-left mb-20">We have made GST Compliance, Adoption Simple &amp; Easy </h4>
                        <div class="mobileBtn mt-40">
                            <div class="d-flex align-items-center ">
                                <div class="mr-10"> <button class="btn">Experience Now</button> </div>
                                <div class=""> <button class="btn orangBtn">Schedule a Demo</button> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="position-relative mobile-sec pb-40 pt-20">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                    <div class="section-title pb-4 text-center ">
                        <h2> Access Your Business Anytime, Anywhere </h2>
                        <div class="bottomLine"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-lg-7 col-md-7">
                    <div class="businessFeature">
                        <p>Keep tabs on your business no matter where you are. With Just Udhari mobile app, you can capture your expenses.Being location independent is growing in popularity. Working away from a traditional way can suit any type of consultant or professional
                            that works at a computer and communicates largely via phone and email or messaging. </p>
                        <h4 class="mt-10">Attractive Features of Mobile App</h4>
                        <ul class="mobileList">
                            <li><i class="fa fa-hand-o-right"></i>Working away from the office is not just a lifestyle choice but can also be good for business</li>
                            <li><i class="fa fa-hand-o-right"></i>Access your business with a single click.</li>
                            <li><i class="fa fa-hand-o-right"></i>Provide invoice and transactions from the comfort of your home.</li>
                            <li><i class="fa fa-hand-o-right"></i>Attractive features of Just Udhari mobile app </li>
                            <li><i class="fa fa-hand-o-right"></i>Keep Track of your profit &amp; Losses thousand the day.</li>
                            <li><i class="fa fa-hand-o-right"></i>Better analysis better gain.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-lg-5 col-md-5">
                    <div class="mobileAppImg"><img alt="Access Your Business Anytime Anywhere" class="img-responsive center-block" src="{{ ('assets/images/software/moblie-app.webp') }}"></div>
                </div>
            </div>
        </div>
    </section>

@endsection
