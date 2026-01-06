<!DOCTYPE html>
<html lang="zxx">

<head>

    @include('layouts.vendors.css')

</head>

<body class="dashboard-analytics mini_bar-open">

    <!-- BEGIN LOADER -->
    {{-- <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div> --}}
    <!--  END LOADER -->

    @include('layouts.vendors.header')

    @include('vendors.partials.password_modal')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id = "container">

        {{-- <div class="overlay"></div>
        <div class="search-overlay"></div> --}}

        @include('layouts.vendors.sidebar')

        <div id="content" class="main-content">
            <div class="layout-px-spacing">

            @if ($errors->has('password'))
                <div class="alert alert-danger">
                    {{ $errors->first('password') }}
                </div>
            @endif

            @if (!empty(session('subscription_expire')))

                <div class="expiry alert alert-danger" role="alert">
                    {{ session('subscription_expire') }}
                </div>

            @endif

            @yield('content')

        </div>

        @include('layouts.vendors.js')

            @yield('javascript')

    <div id="sidebar-overlay"></div>

    @include('layouts.vendors.content.permission-model')

</div>
</div>

@include('layouts.vendors.footer')

@include('layouts.vendors.js.permission-check')

@include('layouts.vendors.js.password-check')

</body>
</html>
