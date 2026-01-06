
<div class="container-fluid bg-secondary text-dark mt-5 pt-5">
    <div class="row px-xl-5 pt-5">

        <div class="col-lg-3 col-md-12 mb-5 pr-3 pr-xl-5">

            <a href="{{$ecommbaseurl}}" class="text-decoration-none d-block">
                    <img src="{{ asset($logo)}}" class="img-responsive logo_image">
            </a>

        </div>

        <div class="col-lg-6 col-md-12">

            <div class="row">

                <div class="col-md-6 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Quick Links</h5>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}") }}"><i
                                class="fa fa-angle-right mr-2"></i>Home</a>
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}about") }}"><i
                                class="fa fa-angle-right mr-2"></i>About</a>
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}shop") }}"><i
                                class="fa fa-angle-right mr-2"></i>Shop</a>
                        <a class="text-dark" href="{{ url("{$ecommbaseurl}contact") }}"><i
                                class="fa fa-angle-right mr-2"></i>Contact</a>
                    </div>
                </div>

                <div class="col-md-6 mb-5">
                    <h5 class="font-weight-bold text-dark mb-4">Important Links</h5>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}terms-conditions") }}"><i
                                class="fa fa-angle-right mr-2"></i>Terms &
                            Condition</a>
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}privacy-policy") }}"><i
                                class="fa fa-angle-right mr-2"></i>Privacy
                            Policies</a>
                        <a class="text-dark mb-2" href="{{ url("{$ecommbaseurl}desclaimer") }}"><i
                                class="fa fa-angle-right mr-2"></i>Desclaimer</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-12 mb-5 pr-3 pr-xl-5">
            <a href="" class="text-decoration-none">
                <h5 class="mb-4 font-weight-bold text-dark mb-4">Get InTouch</h1>
            </a>
            <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $address }}</p>
            <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $head_mail }}</p>
            <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $head_fone }}</p>
        </div>
    </div>

    <div class="row border-top border-light mx-xl-5 py-4">
        <div class="col-md-6 px-xl-0">
            <p class="mb-md-0 text-center text-md-left text-dark">
               Copy Rights &copy; <a class="text-dark font-weight-semi-bold" href="#"> {{ $web_title }} </a> | All Rights Reserved |  Developed By <a class="text-dark font-weight-semi-bold" href = "https://www.hambiresolutions.com/" target="_blank" > Hambire Solutions </a>
            </p>
        </div>

        <div class="col-md-6 px-xl-0 text-center text-md-right">
            <img class="img-fluid" src = "{{ asset('assets/ecomm/img/payments.png') }}" alt="">
        </div>

    </div>
</div>

<!-- Footer End -->

<div class="today_rate_block">
    <a href="#my_rate_block" class="btn btn-primary form-control d-none d-lg-block d-md-block" onclick="event.preventDefault();$($(this).attr('href')).toggle('slide');"><i class="fa fa-angle-down"></i> Todays rate</a>
     <a href="#my_rate_block" class="btn btn-primary form-control d-lg-none d-md-none d-block" onclick="$($(this).attr('href')).toggle('slide');"><i class="fa fa-angle-down"></i> Rate</a>
    <ul id="my_rate_block" style="display:none;">
        <li class="py-2 px-2"><h6>GOLD</h6><hr class="mb-1 mt-1">Rs. 350000/-</li>
        <li class="py-2 px-2"><h6>SILVER</h6><hr class="mb-1 mt-1">Rs. 100000/-</li>
    </ul>
</div>

<!-- Back to Top -->

<a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
