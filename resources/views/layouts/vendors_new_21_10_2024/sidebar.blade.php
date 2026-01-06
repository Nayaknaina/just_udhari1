<div class="sidebar-wrapper sidebar-theme">

    <nav id = "compactSidebar">

        <div class="theme-logo">
            <a href="{{ url('vendors/') }}">
                <img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">
            </a>
        </div>

        <ul class="menu-categories">

            <li class="menu menu-single">
                <a href="{{ route('vendors.home') }}" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/dashboard.png')}}" class = "img-fluid">
                        </div>
                    </div>
              <span>Dashboard</span>
             </a>
            </li>

            <li class="menu">
                <a href="#ecommerce-admin" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                           {{-- <i class="fa fa-shopping-cart"></i> --}}
                            <img src = "{{asset('main/assets/img/ds-icon/mobile-shopping.png')}}" class = "img-fluid">

                        </div>
                    </div>
              <span>E-comm Admin</span>
             </a>
            </li>

            <li class="menu">
                <a href="#Inventory" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class="fa-solid fa-warehouse"></i> --}}
                            
                            <img src = "{{asset('main/assets/img/ds-icon/list-items.png')}}" class = "img-fluid">

                        </div>
                    </div>
                
              <span>Inventory</span>
             </a>
            </li>

            <li class="menu">
                <a href="#schemes" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class="fas fa-tasks"></i> --}}
                            
                            <img src = "{{asset('main/assets/img/ds-icon/scheme.png')}}" class = "img-fluid">

                        </div>
                    </div>
                
              <span>Schemes</span>
             </a>
            </li>

            <li class="menu">
                <a href="#miscellaneous" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class="fa fa-info-circle" aria-hidden="true"></i> --}}
                            <img src = "{{asset('main/assets/img/ds-icon/miscellaneous.png')}}" class = "img-fluid">

                        </div>
                    </div>
                
              <span>Miscellaneous</span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#udhari" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class = "fas fa-copy" aria-hidden="true"></i> --}}
                            <img src = "{{asset('main/assets/img/ds-icon/loan.png')}}" class = "img-fluid">
                        </div>
                    </div>
                
              <span> Udhari </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#girvi" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> Girvi </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#billing" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> Billing </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#intregation" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> Intregation </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#RFID-Barcode" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> RFID & Barcode </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#Jwellery-Repair" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> Jwellery Repair </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#Old-Jwellery-Exchange" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span> Old Jwellery Exchange </span>
             </a>
            </li>

            <li class="menu menu-single">
                <a href="#HR-Management" data-active="false" class="menu-toggle">
                    <div class="base-menu">
                        <div class="base-icons">
                            <i class = "fas fa-copy" aria-hidden="true"></i>
                        </div>
                    </div>
                
              <span>  HR Management </span>
             </a>
            </li>

        </ul>

        <div class="sidebar-bottom-actions">

            <div class="dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('main/assets/img/user-icon.jpg') }}" class="img-fluid" alt="avatar">
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="dropdown-inner">

                        <div class="dropdown-item">
                            <a href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span> Profile</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <form id="logout-form" action="{{ route('vendors.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('vendors.logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </nav>

     @include('layouts.vendors.subsidebar')

</div>
