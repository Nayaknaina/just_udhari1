<div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
    <img class="animation__shake" src = "{{ asset('assets/images/logo.png') }}" alt="JUST UDHARI" height="60" width="60" style="display: none;">
</div>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('vendors/') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">

    <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
    Super Admin <i class="far fa-user"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

    <a href="#" class="dropdown-item">
    <i class="fas fa-user mr-2"></i> Profile</a>
    <div class="dropdown-divider"></div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href = "{{ route('logout') }}"
    onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
    <i class="fas fa-power-off mr-2"></i> Logout
    </a>
    </div>
    </li>

    </ul>
    </nav>

