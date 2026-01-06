
    <footer class="footer-area footer-bg">
        <div class="container">
            <div class="footer-top pt-100 pb-70">
                <div class="row align-items-center justify-content-between ">
                    <div class="col-lg-4">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <a href="{{ url('/') }}"> <img src = "{{ asset('/assets/images/logo/logo.png')}}" alt="Images"></a>
                            </div>
                            <p> For your total peace of mind, it is outfitted with unique security tools that were created especially for jewelry businesses
                            </p>
                            <div class="footer-call-content">
                                <h3>Talk to Our Support</h3>
                                <span><a href="tel:917879404501">+91-7879404501</a></span>
                                <i class="bx bx-headphone"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="footer-widget pl-2">
                            <h3>Services</h3>
                            <ul class="footer-list">
                                <li>
                                    <a href="{{ url('products/jwellery-software') }}" ><i class="bx  bx-chevron-right"></i> Jwellery Software </a>
                                </li>
                                <li>
                                    <a href="{{ url('products/money-leading-software') }}" ><i class="bx  bx-chevron-right"></i> Money Leading Software </a>
                                </li>
                                <li>
                                    <a href="{{ url('software-tutorial') }}" ><i class="bx  bx-chevron-right"></i> Software Tutorial </a>
                                </li>
                                <li>
                                    <a href="{{url('whats-new')}}" ><i class="bx  bx-chevron-right"></i>  What`s New </a>
                                </li>
                                <li>
                                    <a href="{{ url('/faq') }}" ><i class="bx  bx-chevron-right"></i> Faq </a>
                                </li>
                                <li>
                                    <a href="{{ url('/contact') }}" > <i class="bx bx-chevron-right"></i> Contact </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- <div class="col-lg-4">
                        <div class="footer-widget">
                            <h3>Newsletter</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum finibus molestie molestie. Phasellus ac rutrum massa, et volutpat nisl. Fusce ultrices suscipit nisl.</p>
                            <div class="newsletter-area">
                                <form class="newsletter-form" data-toggle="validator" method="POST">
                                    <input type="email" class="form-control" placeholder="Enter Your Email" name="EMAIL" required autocomplete="off">
                                    <button class="subscribe-btn" type="submit"> <i class="bx bxs-paper-plane"></i> </button>
                                    <div id="validator-newsletter" class="form-result"></div>
                                </form>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="copy-right-area">
                <div class="copy-right-text">
                    <p>
                        Copyright ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Hambire Solutions . All Rights Reserved | Developed by
                        <a href="https://www.hambiresolutions.com/" target="_blank" > Hambire Solutions </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
