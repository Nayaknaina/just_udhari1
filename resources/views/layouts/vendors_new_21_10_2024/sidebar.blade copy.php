<aside class="main-sidebar sidebar-dark-primary elevatio n-4">

    <a href="{{ route('vendors.home') }}" class="brand-link">
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
                            <a href="#" class="d-block"> {{  strtoupper(Auth::user()->name) }} </a>
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
                                <a href = "{{ route('vendors.home') }}" class="nav-link ">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p> Dashboard </p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p> E-commerce Admin
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>

                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item ">
                                            <a href = "{{ route('catalogues.index') }}" class="nav-link ">
                                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                                <p> Ecommerce Dashborad </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>  Products
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                            </a>
                                                <ul class="nav nav-treeview" style="display: none;">
                                                    <li class="nav-item">
                                                        <a href="{{ route('ecomproducts.index') }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> Listed </p>
                                                        </a>
                                                    </li>

                                                    <li class="nav-item">
                                                        <a href="{{ route('ecomstocks.index') }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p>  Not Listed </p>
                                                        </a>
                                                    </li>

                                                </ul>
                                        </li>

                                        <li class="nav-item ">
                                            <a href = "{{ route('catalogues.index') }}" class="nav-link ">
                                                <i class="fa fa-circle nav-icon "></i>
                                                <p> Catalogues </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>  Ecom Categories
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                            </a>
                                                <ul class="nav nav-treeview" style="display: none;">
                                                    <li class="nav-item">
                                                        <a href="{{ route('ecomcategories.show',1) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(1) }} </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('ecomcategories.show',2) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(2) }} </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('ecomcategories.show',3) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(3) }} </p>
                                                        </a>
                                                    </li>
                                                </ul>
                                        </li>

                                        <li class="nav-item">
                                            <a href = "{{ route('customers.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>  Customers </p>
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
                                                        <a href = "{{ route('ecomslider') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Slider </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('aboutcontent') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> About Us </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('ecommwebinformations.index') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Web Information </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('sociallink') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Social Links </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('termsandconditions') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Terms & Conditions </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('privacypolicy') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Privacy Policy </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href = "{{ route('desclaimer') }}" class="nav-link">
                                                        <i class = "far fa-circle nav-icon"></i>
                                                        <p> Desclaimer </p>
                                                        </a>
                                                    </li>
                                                </ul>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Udhari
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Add Udhari </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Ledger </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Girvi
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> New Girvi </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Old Girvi </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Ledger </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Billing
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Rough Estimate </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> GST Bill </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Intregation
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Payment Gateway </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Whatsapp API </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> SMS API </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> RFID & Barcode
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> RFID </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Bar Code </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Inventory
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                        <a href="{{ route('purchases.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> Purchase </p>
                                        </a>
                                        </li>

                                        <li class="nav-item">
                                        <a href="{{ route('stocks.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> Jewellery Stock </p>
                                        </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Schemes
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('shopscheme.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p> All Scheme </p>
                                            </a>
                                        </li>
                                        @php
                                            $user = Auth::user();
                                            $scheme_menu = schememenu($user->shop_id);
                                        @endphp
                                        @if(count($scheme_menu)>0)
                                            @foreach($scheme_menu as $sk=>$scheme)
                                                <li class="nav-item">
                                                    <a href="{{ route('shopscheme.show',$scheme->id) }}" class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{  $scheme->schemes->scheme_head }} </p>
                                                    </a>
                                                </li>
                                            @endforeach

                                        @endif
                                        <li class="nav-item">
                                            <a href="{{ route('shopscheme.due') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p> Due Amount </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('shopscheme.pay') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p> Add Money </p>
                                            </a>
                                        </li>
                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="{{ route('comingsoon') }}" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Jwellery Repair
                                </p>
                                </a>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> Old Jwellery Exchange
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Refine </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Sell </p>
                                            </a>
                                        </li>

                                    </ul>
                            </li>

                            <li class="nav-item ">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p> HR Management
                                <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                    <ul class="nav nav-treeview" style="display: none;">

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Sallery </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('comingsoon') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p> Attendance </p>
                                            </a>
                                        </li>

                                    </ul>
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
                                            <a href = "{{ route('suppliers.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Suppliers </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href = "{{ route('shopbranches.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Shop Branches </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href = "{{ route('counters.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Shop Counters </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href = "{{ route('designations.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Employee Designations/Roles </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href = "{{ route('employees.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Employees </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href = "{{ route('settings.index') }}" class="nav-link">
                                            <i class = "far fa-circle nav-icon"></i>
                                            <p> Settings </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>  Product Categories
                                            <i class="fas fa-angle-left right"></i>
                                            </p>
                                            </a>
                                                <ul class="nav nav-treeview" style="display: none;">
                                                    <li class="nav-item">
                                                        <a href="{{ route('categories.show',1) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(1) }} </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('categories.show',2) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(2) }} </p>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('categories.show',3) }}"  class="nav-link">
                                                        <i class="far fa-circle nav-icon"></i>
                                                        <p> {{ category_label(3) }} </p>
                                                        </a>
                                                    </li>
                                                </ul>
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
