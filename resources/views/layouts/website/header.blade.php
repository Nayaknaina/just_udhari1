
    <div class="preloader">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <div class="navbar-area">

        <div class="mobile-nav">
            <a href="{{ url('/') }}" class="logo">
                <img src = "{{ asset('assets/images/logo/logo.png') }}" class="logo-one" alt="Logo">
            </a>
        </div>

        <div class="main-nav">
            <div class="container-fluid">
                <div class="container-max">
                    <nav class="navbar navbar-expand-md navbar-light ">
                        <a class="navbar-brand" href="{{ url('/') }}">
                        <img src = "{{ asset('assets/images/logo/logo.png') }}" class="logo-one" alt="Logo">
                        </a>
                         <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                            <ul class="navbar-nav m-auto">
                                <li class="nav-item">
                                    <a href="{{ url('/') }}" class="nav-link {{ isActive('/')  }}"> Home  </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('products/jwellery-software') }}" class="nav-link {{ isActive('products/jwellery-software')  }}"> Jwellery Software  </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('products/money-leading-software') }}"  class="nav-link {{ isActive('products/money-leading-software')  }}"> Money Leading Software  </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('software-tutorial') }}" class="nav-link {{ isActive('software-tutorial')  }}"> Software Tutorial  </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('whats-new')}}" class="nav-link {{ isActive('whats-new') }}"> What`s New  </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('faq') }}" class="nav-link {{ isActive('faq') }}"> Faq </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('contact') }}" class="nav-link {{ isActive('contact') }}"> Contact  </a>
                                </li>
                            </ul>

                            <div class="nav-side d-display nav-side-mt">
                                <div class="nav-side-item">
                                    <div class="get-btn">
                                        <a href="{{ url('/login') }}" class="default-btn btn-bg-two border-radius-50"> Login <i class="bx bx-chevron-right"></i></a>
                                        <a href="#free-demo" class="default-btn btn-bg-three border-radius-50">Get Free Demo <i class="bx bx-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="side-nav-responsive">
            <div class="container-max">
                {{-- <div class="dot-menu"> --}}
                    <div class="mob-login">
                    <a href="{{ url('/login') }}" class="default-btn btn-bg-two border-radius-50"> Login <i class="bx bx-chevron-right"></i></a>
                    {{-- <div class="circle-inner">
                        <div class="in-circle circle-one"></div>
                        <div class="in-circle circle-two"></div>
                        <div class="in-circle circle-three"></div>
                    </div> --}}
                </div>
                {{-- </div> --}}
                <div class="container">
                    <div class="side-nav-inner">
                        <div class="side-nav justify-content-center align-items-center">
                            {{-- <div class="side-nav-item nav-side">
                                <div class="search-box">
                                    <i class="bx bx-search"></i>
                                </div>
                                <div class="get-btn">
                                    <a href="#" class="default-btn btn-bg-two border-radius-50">Get A Quote <i class="bx bx-chevron-right"></i></a>
                                    <a href="{{ url('/login') }}" class="default-btn btn-bg-two border-radius-50"> Login <i class="bx bx-chevron-right"></i></a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
