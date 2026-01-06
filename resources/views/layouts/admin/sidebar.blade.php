<aside class="main-sidebar sidebar-dark-primary elevatio n-4">

<a href="{{ url('/home') }}" class="brand-link">
    <img src = "{{ asset('assets/images/logo/logo.png') }}" alt="JUST UDHARI" class = "brand-image br-image-dp-type " >
    <img src = "{{ asset('assets/images/favicon.png') }}" alt="JUST UDHARI" class = "brand-image br-image-dp-type1 " >
</a>

<div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
    <div class="os-resize-observer-host observed">
        <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
    </div>
    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
        <div class="os-resize-observer"></div>
    </div>
    <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 549px;"></div>
    <div class="os-padding">
        <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
            <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">

                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src = "{{ asset('theme/dist/img/user.png') }}" class="img-circle elevation-2" alt = "User Image">
                        </div>

                    <div class="info">
                        <a href="#" class="d-block"> {{  strtoupper(@Auth::guard('superadmin')->user()->name) }} </a>
                    </div>
                </div>

                <div class="form-inline">

                    <div class="sidebar-search-results">
                        <div class="list-group">
                            <a href="#" class="list-group-item">
                                <div class="search-title"><strong class="text-light"></strong>N<strong class="text-light"></strong>o<strong class="text-light"></strong> <strong class="text-light"></strong>e<strong class="text-light"></strong>l<strong class="text-light"></strong>e
                                    <strong
                                        class="text-light"></strong>m<strong class="text-light"></strong>e<strong class="text-light"></strong>n<strong class="text-light"></strong>t<strong class="text-light"></strong> <strong class="text-light"></strong>f<strong class="text-light"></strong>o
                                        <strong
                                            class="text-light"></strong>u<strong class="text-light"></strong>n<strong class="text-light"></strong>d<strong class="text-light"></strong>!<strong class="text-light"></strong></div>
                                <div class="search-path"></div>
                            </a>
                        </div>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item ">
                            <a href = "{{ route('dashboard') }}" class="nav-link ">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p> Dashboard </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href = "{{ route('softwareproducts.index') }}" class="nav-link ">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p> Software Products </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href = "{{ route('schemes.index') }}" class="nav-link ">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p> Schemes </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href = "{{ route('users.index') }}" class="nav-link ">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p> Shop Users </p>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p> Miscellaneous
                            <i class="fas fa-angle-left right"></i>
                            </p>
                            </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>  Product Categories
                                        <i class="fas fa-angle-left right"></i>
                                        </p>
                                        </a>
                                            <ul class="nav nav-treeview" style="display: none;">
                                                <li class="nav-item">
                                                    <a href="{{ route('productcategories.show',1) }}"  class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p> {{ category_label(1) }} </p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('productcategories.show',2) }}"  class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p> {{ category_label(2) }} </p>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('productcategories.show',3) }}"  class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p> {{ category_label(3) }} </p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('webinformation.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Web Information </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('webpages.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Web Pages </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('faqs.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Faq </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('whatsnew.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Whats New </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('roles.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Roles </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href = "{{ route('permissions.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Permissions </p>
                                        </a>
                                    </li>
									<li class="nav-item">
                                        <a href = "{{ route('paymentgateway.index') }}" class="nav-link">
                                        <i class = "far fa-circle nav-icon"></i>
                                        <p> Payment Gateway </p>
                                        </a>
                                    </li>
                                </ul>
                        </li>

                    </ul>
                </nav>

            </div>
        </div>
    </div>

    <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
        <div class="os-scrollbar-track">
            <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
        </div>
    </div>
    <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
        <div class="os-scrollbar-track">
            <div class="os-scrollbar-handle" style="height: 40.4709%; transform: translate(0px, 0px);"></div>
        </div>
    </div>
    <div class="os-scrollbar-corner"></div>
</div>

</aside>
