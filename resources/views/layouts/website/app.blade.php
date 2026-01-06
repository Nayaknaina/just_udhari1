<!DOCTYPE html>
<html lang="zxx">

<head>

    @include('layouts.website.css')

</head>

<body>

    @include('layouts.website.header')

        @yield('content')

    @include('layouts.website.footer')
    
    @include('layouts.website.js')

        @yield('javascript')

</body>

</html>