<div class="sidebar-wrapper sidebar-theme">
@php  
    $target_link = explode('.',Route::currentRouteName());
    $trunck = $target_link[0]??false;
    $$trunck = 'show';
    $branch = $target_link[1]??false;
    $$branch = 'active';
@endphp
<style>
    /*-----For Side Bar at nav-------*/
    #open_menu{
        transform: rotate(-90deg); 
        transform-origin: top right;
        background:#ff6e26;
        background:white;
        color: white;
        color: #ff6e26;
        font-weight: bold;
        padding: 0 5px 0 20px;
        box-shadow:1px 2px 3px gray;
        position: absolute;
        right:0;
        width: fit-content;
        height:auto;
        /* top:50%; */
        border-right: 1px solid #ff6e26;
        border-bottom: 2px solid #ff6e26;
        display:none;
    }
    #open_menu:before{
        width: 30px;
        height: 100%;
        content: "";
        left: -15px;
        position: absolute;
        background:#ff6e26;
        background:white;
        border:x solid black;
        transform: skew(50deg);
        box-shadow:1px 2px -3px gray;
        border-left:3px solid #ff6e26; 
        //border-bottom: 1px solid #ff6e26;
        z-index:-1;
    }
    
    #open_menu:after{
        content:"\27A7";
        position:absolute;
        transform: rotate(90deg);
        left: 0;
    }
    li.menu.active{
        box-shadow: rgba(255, 120, 0, 0.67) 0px 2px 4px 0px, rgb(255, 72, 0) 0px 2px 16px 0px;
        background:white;
		border-radius: 0 30% 30% 0;
		border:1px dashed #ff6e26;
    }
</style>

    <nav id = "compactSidebar">
		<a href="javascript:void(null);" id="open_menu">
			Active Side Bar
		</a>
        <div class="theme-logo">
            <a href="{{ url('vendors/') }}">
                <img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">
            </a>
        </div>

        <ul class="menu-categories">

            <li class="menu menu-single">
                <a href="{{ route('vendors.home') }}" data-active="false" class="menu-toggle" data-target="{{ route('vendors.home') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/dashboard-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/dashboard.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>
                <span>Dashboard</span>
                </a>
            </li>

			<li class="menu  {{ @$ecomorders }}">
                <a href="#sample-pages" data-active="false" class="menu-toggle" >
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>
              <span>Sample Pages</span>
             </a>
            </li>


            <li class="menu  {{ @$ecomorders }}">
                <a href="#ecommerce-admin" data-active="false" class="menu-toggle" data-target="{{ route('ecomdashboard') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/e-comma-admin.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>
              <span>E-comm Admin</span>
             </a>
            </li>   

            <li class="menu">
                <a href="#Inventory" data-active="false" class="menu-toggle" data-target="{{ route('stocks.home') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class="fa-solid fa-warehouse"></i> --}}

                            <img src = "{{asset('main/assets/img/ds-icon/invertory-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/invertory.svg')}}" class = "img-fluid icon_png2">

                        </div>
                    </div>

              <span>Inventory</span>
             </a>
            </li>

            <li class="menu">
                <a href="#schemes" data-active="false" class="menu-toggle" data-target="{{ route('shopscheme.due') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/schemes-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/schemes.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span>Schemes</span>
             </a>
            </li>

            <li class="menu">
                <a href="#miscellaneous" data-active="false" class="menu-toggle"  data-target="{{ route('shopbranches.index') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class="fa fa-info-circle" aria-hidden="true"></i> --}}
                            <img src = "{{asset('main/assets/img/ds-icon/miscellaneous-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/miscellaneous.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span>Miscellaneous</span>
             </a>
            </li>

            <li class="menu">
                <a href="#udhari" data-active="false" class="menu-toggle"  data-target="{{ route('udhar.ledger') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            {{-- <i class = "fas fa-copy" aria-hidden="true"></i> --}}
                            <img src = "{{asset('main/assets/img/ds-icon/udhari-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/udhari.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Udhari </span>
             </a>
            </li>

            <li class="menu {{ @$girvi }}">
                <a href="#girvi" data-active="false" class="menu-toggle"  data-target="{{ route('girvi.grirvilist') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/girvi-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/girvi.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Girvi </span>
             </a>
            </li>
			
            <li class="menu">
                <a href="#billing" data-active="false" class="menu-toggle"  data-target="{{ route('sells.index') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/billing-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/billing.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Billing </span>
             </a>
            </li>

            <li class="menu">
                <a href="#intregation" data-active="false" class="menu-toggle"  data-target="{{ route('textmsgeapi.index') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/integration-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/integration.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Intregation </span>
             </a>
            </li>

            <li class="menu">
                <a href="#RFID-Barcode" data-active="false" class="menu-toggle"  data-target="{{ route('idtags.match') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/RFID-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/RFID.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> RFID & Barcode </span>
             </a>
            </li>

            <li class="menu">
                <a href="#Jwellery-Repair" data-active="false" class="menu-toggle"  data-target="{{ route('jewellery.repair') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/repair-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/repair.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Jwellery Repair </span>
             </a>
            </li>

            <li class="menu">
                <a href="#Old-Jwellery-Exchange" data-active="false" class="menu-toggle"  data-target="{{ route('jewellery.exchange','list') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/exchange-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/exchange.svg')}}" class = "img-fluid icon_png2">
                        </div>
                    </div>

              <span> Old Jwellery Exchange </span>
             </a>
            </li>

            <li class="menu">
                <a href="#HR-Management" data-active="false" class="menu-toggle"  data-target="{{ route('human.resource','list') }}">
                    <div class="base-menu">
                        <div class="base-icons">
                            <img src = "{{asset('main/assets/img/ds-icon/staff-inactive.svg')}}" class = "img-fluid icon_png">
                            <img src = "{{asset('main/assets/img/ds-icon/staff.svg')}}" class = "img-fluid icon_png2">
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
                            <a href="{{ route('settings.index') }}">
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
