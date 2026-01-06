<style>
    .brand-badge {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    background: var(--primary);
    background: #ff8a3d;
    color: #fff;
    font-weight: 800;
    box-shadow: var(--shadow-100);
    }


    /* Notification wrapper */
.notif-icon-wrap {
    position: absolute;
    right: 150px;          /* desktop default */
    top: 50%;
    transform: translateY(-50%);
    z-index: 1000;
}

/* Bell icon */
.notif-icon {
    font-size: 20px;
    cursor: pointer;
    color: #fff;
}

/* Mobile view fix */
@media (max-width: 768px) {
    .notif-icon-wrap {
        /*right: 55px;      
        top: 18px;
        transform: none;*/
        left: 0px;
        top: 0px;
        transform: none;
        position: relative;
    }

    .notif-icon {
        font-size: 18px;
        color: #fff;
    }
}
header.header {
    position: relative;
}

</style>
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm" >

        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </a>

        <ul class="navbar-item flex-row search-ul">
			
			<li class="menu_toggle_btns d-none d-md-block collapse">
                <button class="btn btn-sm ico_btn btn-outline-dark py-1 m-2 cstm_menu_toggle" id="cstm_menu_collapse"  style="padding:9px;">
                    &#10092;&#10092;
                </button>
                <button class="btn btn-sm ico_btn btn-dark py-1 m-2 cstm_menu_toggle" id="cstm_menu_expande"  style="padding:7px;">
                    <!-- &#926;&#10093; -->
                     <i class="fa fa-list"></i>
                    <!-- &#8667; -->
                </button>
            </li>
            <li class="nav-item align-self-center search-animated menu-logo d-md-block d-none w-100 ml-0 ">
			{{--<a href="{{ url('vendors/') }}">
                    <img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">
                    <span>Just Udhari</span>
                </a>--}}
				<a href="{{ url('vendors/') }}" style="display: inline-flex;align-items: center;">
                    <div class="brand-badge">JU</div>
                    {{--<img src = "{{ asset('assets/images/favicon.png') }}" class="navbar-logo" alt="logo">--}}
                    <span>Just Udhari</span>
                </a>
            </li>
			
            <li class="nav-item align-self-center search-animated w-100">
                
                <form class="form-inline search-full form-inline search" role="search">
                    <div class="search-bar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" class="form-control search-form-control  ml-lg-auto  w-100" placeholder="Type here to search">
                        <ul id="searchResults" class="list-group" style=" display: block;margin: auto;position: absolute;width: 100%;"></ul>
                    </div>

                </form>
            </li>
        </ul>
		<ul class="navbar-item flex-row navbar-dropdown d-md-none" style="margin-left: auto;">
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/images/favicon.png') }}" class="img-responsive img-thumbnail form-control w-auto p-0 m-0 ">
                </a>
            </li>
        </ul>
        @include('layouts.vendors.sortcut')
                
                <!-- Notification Icon -->
                <div class="notif-icon-wrap">
                    <i class="fa fa-bell notif-icon" id="openNotif"></i>
                    <span id="notifCount"
                        style="
                            position:absolute;
                            top:-5px;
                            right:-5px;
                            background:red;
                            color:#fff;
                            border-radius:50%;
                            padding:2px 6px;
                            font-size:12px;
                            display:none;
                        ">
                    </span>
                </div>
        <ul class="navbar-item flex-row navbar-dropdown">

            {{-- <li class="nav-item dropdown message-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg><span class="badge badge-primary"></span>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="messageDropdown">
                    <div class="">
                        <a class="dropdown-item">
                            <div class="">

                                <div class="media">
                                    <div class="user-img">
                                        <div class="avatar avatar-xl">
                                            <span class="avatar-title rounded-circle">KY</span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Kara Young</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                        <a class="dropdown-item">
                            <div class="">
                                <div class="media">
                                    <div class="user-img">
                                        <div class="avatar avatar-xl">
                                            <span class="avatar-title rounded-circle">DA</span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Daisy Anderson</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item">
                            <div class="">

                                <div class="media">
                                    <div class="user-img">
                                        <div class="avatar avatar-xl">
                                            <span class="avatar-title rounded-circle">OG</span>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Oscar Garner</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </li> --}}
            {{-- <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">

                        <div class="dropdown-item">
                            <div class="media server-log">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>
                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class="">Server Rebooted</h6>
                                        <p class="">45 min ago</p>
                                    </div>

                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item">
                            <div class="media ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class="">Licence Expiring Soon</h6>
                                        <p class="">8 hrs ago</p>
                                    </div>

                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item">
                            <div class="media file-upload">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class="">Kelly Portfolio.pdf</h6>
                                        <p class="">670 kb</p>
                                    </div>

                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li> --}}
            
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @php 
                    $image = app('userd')->shopbranch->image;
                    //echo $image;
                @endphp 
                @if($image!="" && file_exists("{$image}"))
                <img src="{{ asset("{$image}") }}" class="img-responsive img-thumbnail form-control w-auto p-0 m-0 ">
                @else 
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                @endif
             {{-- ------------------- shop name short code  --}}


             <style>
                .shop-initials {
                        position: relative;
                        cursor: pointer;
                        font-weight: 600;
                    }

                    /* Tooltip box */
                    .shop-initials::after {
                        content: attr(data-fullname);
                        position: absolute;
                        bottom: -42px;
                        right: 0;
                        background: linear-gradient(135deg, #ff8a3d, #ff8a3d);
                        color: #fff;
                        padding: 8px 14px;
                        border-radius: 50px;
                        font-size: 16px;
                        font-weight: 500;
                        white-space: nowrap;
                        opacity: 0;
                        transform: translateY(-6px) scale(0.95);
                        pointer-events: none;
                        transition: all 0.25s ease;
                        box-shadow: 0 12px 30px rgba(112, 255, 248, 0.29);
                        z-index: 999;
                    }

                    /* Small arrow */
                    .shop-initials::before {
                        content: "";
                        position: absolute;
                        bottom: -10px;
                        right: 14px;
                        border-width: 6px;
                        border-style: solid;
                        border-color: transparent transparent #1f1f1f transparent;
                        opacity: 0;
                        transition: opacity 0.25s ease;
                    }

                    /* Hover effect */
                    .shop-initials:hover::after,
                    .shop-initials:hover::before {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }

             </style>
@php
    $name = trim(app('userd')->shopbranch->name);
    $words = preg_split('/\s+/', $name);

    if (count($words) > 1) {
        $initials = strtoupper(
            substr($words[0], 0, 1) .
            substr(end($words), 0, 1)
        );
    } else {
        $initials = strtoupper(substr($words[0], 0, 1));
    }
@endphp

<span class="d-none d-lg-block shop-initials"
      data-fullname="{{ $name }}"
      style="position:absolute; right:0; top:0px; margin-right:85px;">
    {{ $initials }}
</span>
{{-- ------------------- shop name short code end --}}

                    </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">

                        <div class="dropdown-item">
                            <div class="media server-log">
                                <div class="media-body">
                                    <div class="data-info ">
                                       <a href = "{{ route('settings.index') }}">  <h6 class=""><i class = "fa fa-user"></i> Profile </h6></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item">
                            <div class="media server-log">
                                <div class="media-body">
                                    <div class="data-info ">
                                        <form id="logout-form1" action="{{ route('vendors.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <a href="{{ route('vendors.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                                            <h6 class=""><i class = "fa fa-power-off"></i> Log-out </h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </li>

        </ul>

    </header>
</div>
<style>
@media (max-width: 768px) {

    header.header{
        background: #ff6e26;
        color: #fff !important;
    }

    /* All links + icons white */
    header.header a,
    header.header span,
    header.header svg,
    header.header i {
        color: #fff !important;
        fill: #fff !important;
    }

    /* Search bar */
    .navbar .search-ul {
        flex-basis: 100%;
        width: 100%;
        order: 2;
        margin: auto !important;
        padding: 5px;
        background: #ff6e26 !important;
    }

    .navbar .search-bar,
    .navbar .search-form-control {
        width: 100%;
    }

    .navbar .search-form-control {
        color: #000;               /* input text readable */
        background: #fff;          /* white input */
    }
    .navbar .navbar-item .nav-item.search-animated svg{
        /*display: none;*/
        color: #515365 !important;
    }

    .navbar .search-form-control::placeholder {}
}
</style>
<style>

    /* ------------------------------------------ */
    /* ✅ NOTIFICATION STYLES ADDED BELOW ONLY     */
    /* ------------------------------------------ */

    .notif-icon-wrap {
        margin-left: auto;
        margin-right: 15px;
        display:flex;
        align-items:center;
    }

    .notif-icon {
        font-size: 20px;
        color: #ff6e26;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        transition: 0.2s;
    }

    .notif-icon:hover {
        background: #ff6e262e;
    }

    .notif-panel {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        background: #fff;
        box-shadow: -4px 0px 12px rgba(0, 0, 0, 0.15);
        border-radius: 12px 0 0 12px;
        transition: right 0.3s ease;
        z-index: 9999;
        padding: 15px;
    }

    .notif-panel.active {
        right: 0;
    }

    .notif-header {
        font-weight: 700;
        font-size: 18px;
        display: flex;
        justify-content: space-between;
    }

    .notif-header i {
        cursor: pointer;
        color: #999;
        font-size: 20px;
    }

    .notif-body {
        margin-top: 20px;
        font-style: italic;
        height:100vh;
        overflow-y:auto;
    }

    .notif-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.25);
        display: none;
        z-index: 9990;
    }

    .notif-overlay.active {
        display: block;
    }

    /* Notification wrapper */
.notif-icon-wrap {
    position: absolute;
    right: 150px;          /* desktop default */
    top: 50%;
    transform: translateY(-50%);
    z-index: 1000;
}

/* Bell icon */
.notif-icon {
    font-size: 20px;
    cursor: pointer;
    color: #fff;
}

/* Mobile view fix */
@media (max-width: 768px) {
    .notif-icon-wrap {
        /*right: 55px;      
        top: 18px;
        transform: none;*/
        left: 0px;
        top: 0px;
        transform: none;
        position: relative;
    }

    .notif-icon {
        font-size: 18px;
        color: #fff;
    }
}

     /* ------------------------------------------ */
    /* ✅ NOTIFICATION STYLES ADDED BELOW ONLY     */
    /* ------------------------------------------ */

    .notif-icon-wrap {
        margin-left: auto;
        margin-right: 15px;
        display:flex;
        align-items:center;
    }

    .notif-icon {
        font-size: 20px;
        color: #ff6e26;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        transition: 0.2s;
    }

    .notif-icon:hover {
        background: #ff6e262e;
    }

    .notif-panel {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        height: 100vh;
        background: #fff;
        box-shadow: -4px 0px 12px rgba(0, 0, 0, 0.15);
        border-radius: 12px 0 0 12px;
        transition: right 0.3s ease;
        z-index: 9999;
        padding: 15px;
    }

    .notif-panel.active {
        right: 0;
    }

    .notif-header {
        font-weight: 700;
        font-size: 18px;
        display: flex;
        justify-content: space-between;
    }

    .notif-header i {
        cursor: pointer;
        color: #999;
        font-size: 20px;
    }

 .notif-body {
    margin-top: 20px;
    font-style: italic;
    height: 80vh;
    overflow-y: scroll;

    -ms-overflow-style: none;  /* IE / Edge */
    scrollbar-width: none;     /* Firefox */
}

.notif-body::-webkit-scrollbar {
    display: none; /* Chrome, Safari */
}


    .notif-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.25);
        display: none;
        z-index: 9990;
    }

    .notif-overlay.active {
        display: block;
    }
@media (max-width: 768px) {

    header.header{
        background: #ff6e26;
        color: #fff !important;
    }

    /* All links + icons white */
    header.header a,
    header.header span,
    header.header svg,
    header.header i {
        color: #fff !important;
        fill: #fff !important;
    }

    /* Search bar */
    .navbar .search-ul {
        flex-basis: 100%;
        width: 100%;
        order: 2;
        margin: auto !important;
        padding: 5px;
        background: #ff6e26 !important;
    }

    .navbar .search-bar,
    .navbar .search-form-control {
        width: 100%;
    }

    .navbar .search-form-control {
        color: #000;               /* input text readable */
        background: #fff;          /* white input */
    }
    .navbar .navbar-item .nav-item.search-animated svg{
        /*display: none;*/
        color: #515365 !important;
    }

    .navbar .search-form-control::placeholder {}
}

/*-------css notification ------*/
/* Empty state */
.notif-empty {
    padding: 40px;
    text-align: center;
    color: #9ca3af;
}
.notif-empty i {
    font-size: 24px;
    margin-bottom: 10px;
}

/* Notification item */
.notif-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s ease;
}

.notif-item:hover {
    background: #f1f5f9;
}

/* Unread highlight */
.notif-item.unread {
    background: #fff7ed;
}

/* Read */
.notif-item.read {
    background: #ffffff;
    opacity: 0.85;
}

/* Left content */
.notif-left {
    flex: 1;
}

.notif-title {
    font-weight: 600;
    font-size: 12px;
    color: #111827;
}

.notif-msg {
    font-size: 12px;
    color: #6b7280;
    margin-top: 2px;
}

/* Right badge */
.notif-right {
    /* display: flex; */
    display: grid;
    align-items: center;
}

.notif-badge {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 999px;
    font-weight: 700;
}

.notif-badge.new {
    background: #ff8a3d;
    color: #fff;
}

.notif-badge.read {
    background: #e5e7eb;
    color: #374151;
}

@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255,138,61,.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 8px rgba(255,138,61,0); }
    100% { transform: scale(1); }
}

.notif-badge.new {
    animation: pulse 1.5s infinite;
}


.notif-time {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

.notif-clear {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 12px;
    cursor: pointer;
    margin-right: 10px;
    margin-left: 180px;
}
.notif-clear:hover {
    text-decoration: underline;
}


.notif-ic {
    font-size: 18px;
    margin-right: 10px;
}

.notif-ic.bill { color: #4f46e5; }
.notif-ic.payment { color: #16a34a; }
.notif-ic.warning { color: #dc2626; }


.notif-item {
    position: relative;
    transition: transform .3s ease;
}
.notif-item.swiped {
    transform: translateX(-80px);
}

.notif-wrapper {
    position: relative;
    overflow: hidden;
}

/* main item */
.notif-item {
    background: #fff;
    position: relative;
    z-index: 2;
    transition: transform .25s ease;
}

/* delete layer */
/* Delete layer - hidden by default */
.notif-delete {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 80px;
    background: #e11d48;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
    cursor: pointer;
}



/* swipe open */
.notif-wrapper.swiped .notif-item {
    transform: translateX(-80px);
}

/* reset animation */
.notif-wrapper.reset .notif-item {
    transform: translateX(0);
    transition: transform 0.25s ease;
}

/* Show delete ONLY when swiped */
.notif-wrapper.swiped .notif-delete {
    opacity: 1;              /* 👈 SHOW */
    visibility: visible;
    pointer-events: auto;
}

/* ========== MOBILE (SWIPE ONLY) ========== */
@media (hover: none) and (pointer: coarse) {
    .notif-delete {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: all .2s ease;
    }

    .notif-wrapper.swiped .notif-delete {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
}

/* ========== DESKTOP SMALL ICON ========== */
@media (hover: hover) and (pointer: fine) {
    .notif-delete {
        width: 28px;
        height: 28px;
        top: 20%;
        right: 10px;
        transform: translateY(-50%);
        border-radius: 50%;
        background: #ffffffff;
        font-size: 12px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        border: 1px solid red;
        color: red;
            
    }

    /* show on hover */
    .notif-wrapper:hover .notif-delete {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
}


.undo-toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #1f2937;
    color: #fff;
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    opacity: 0;
    pointer-events: none;
    transition: all .3s ease;
    z-index: 9999;
}

.undo-toast.show {
    opacity: 1;
    pointer-events: auto;
}

.undo-toast button {
    background: transparent;
    border: none;
    color: #60a5fa;
    font-weight: 600;
    cursor: pointer;
}

.notif-tabs {
    display: flex;
    border-bottom: 1px solid #eee;
}

.notif-tab {
    flex: 1;
    padding: 10px;
    background: none;
    border: none;
    font-weight: 600;
    cursor: pointer;
    color: #666;
}

.notif-tab.active {
    color: #e11d48;
    border-bottom: 2px solid #e11d48;
}

.tab-badge {
    background: #e11d48;
    color: #fff;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 12px;
    margin-left: 6px;
    display: none;
}

.mark-all-btn {
    font-size: 12px;
    background: none;
    border: none;
    color: #2563eb;
    cursor: pointer;
    margin-left: 180px;

}





</style>

<!-- Sliding Notification Panel -->
<div id="notifPanel" class="notif-panel">

    <div class="notif-header">
        <span>Notifications</span>
        <i class="fa fa-times" id="closeNotif" style="color:black;"></i>
    </div>

    <div class="notif-tabs">
        <button class="notif-tab active" data-type="unread">
            Unread <span id="unreadBadge" class="tab-badge"></span>
        </button>

        <button class="notif-tab" data-type="read">
            Read <span id="readBadge" class="tab-badge"></span>
        </button>
    </div>

    <!-- 🔥 UNREAD ACTION -->
    <div class="notif-actions unread-only">
        <button id="markAllRead" class="mark-all-btn">Mark all read</button>
    </div>

    <!-- 🔥 READ ACTION (CLEAR ALL) -->
    <div class="notif-actions read-only">
        <button id="clearNotif" class="notif-clear">Clear all</button>
    </div>

    <div class="notif-body">
        <p>No new notifications</p>
    </div>

</div>




<!-- Overlay -->

<div id = "notifOverlay" class = "notif-overlay"></div>


