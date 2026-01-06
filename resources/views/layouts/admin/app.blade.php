<!DOCTYPE html>
<html lang="zxx">

<head>

    @include('layouts.admin.css')

</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">

    <div class="wrapper" >

        @include('layouts.admin.header')

        @include('layouts.admin.sidebar')

        <div id = "loader" class = "lds-dual-ring hidden overlay"></div>

        <div class="content-wrapper">

            @yield('content')

        </div>

        @include('layouts.admin.footer')

        @include('layouts.admin.js')

            @yield('javascript')

        <div id="sidebar-overlay"></div>

    </div>

</body>

</html>
