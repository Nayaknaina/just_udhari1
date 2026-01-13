
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme-dark.css') }}">
    <title> Just Udhari </title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>

    .navbar-area {

        box-shadow: 1px 1px 2px 1px rgb(0, 0, 0, .2) ;

    }

    @media only screen and (max-width: 991px) {

        .navbar-area {

            height: 72px;

        }

        /* .default-btn {

            padding: 8px 8px;
            font-size: 8px;

        } */

        .growth-area {
            padding: 50px 0;
            /* border-top: 1px solid rgb(0, 0, 0, .1); */
            /* background: aliceblue; */
        }

        .juproduct {

            padding: 30px 0;

        }

        .growth-area img {

            margin-bottom: 30px;

        }
        .section-title h2 {
            font-size: 26px;
            margin-top: 0;
            text-align: center;
        }

        /* .softFeature {
            background: #ee5d19;
            display: flex;
            padding: 5px 10px ;
            border-radius: 10px;
            width: 100%;
            overflow-x: scroll;
            flex-wrap: nowrap;
            align-items: center;
        } */
        .softFeature {
            background: #ee5d19;
            display: -webkit-inline-box;
            padding: 15px 10px;
            border-radius: 10px;
            width: 100%;
            overflow-x: scroll;
            /* justify-content: flex-end; */
            /* align-content: space-around; */
            /* flex-wrap: nowrap; */
            /* align-items: center; */
        }

        .softFeature .nav-link {
            color: #FFF;
            font-weight: 600;
            padding: 5px;
            border-radius: 10px;
            border: 1px solid;
            margin-right: 10px;
        }

        .softFeature .tabinnerSec, .tabInnerLog {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            flex-direction: row;
            justify-content: center;
        }
        .LeftCon h6 {
            margin-bottom: 20px;
            text-align: justify;
        }
        .services-item .content {
            width: 95%;
            padding: 0;
            box-shadow: -5px -2px 6px 1px rgb(0, 0, 0, .2);
        }
        .services-item a img {
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            height: 200px;
            border: 4px solid #FFF;
            object-fit: cover;
        }

        .mobile-sec {
            padding: 30px 0;
        }

        .mobileconPara h2 {
            font-size: 28px;
            line-height: 39px;
            color: #000;
            font-weight: 700;
            margin-bottom: 10px !important;
        }

        .mobileImg, .mobileconPara {
            display: block;
            z-index: 1;
            position: relative;
            margin-bottom: 30px;
        }

        .softHeading {

            margin-top: 20px;
            text-align: center;

        }

        .SoftwareCon .softPara h5 {
            line-height: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .SoftwareCon .softHeading h5 {
            font-size: 30px;
            text-transform: capitalize;
            font-weight: 600;
            font-family: Inter, sans-serif;
            color: #0c6fb9;
            margin-bottom: 20px;
            line-height: 3rem;
            text-shadow: 2px 2px #e3e3e3;
        }

        .mobileconPara h3 {
            font-size: 22px;
            line-height: 29px;
            color: #000;
            font-weight: 700;
            margin-bottom: 20px !important ;
        }
        .mobileconPara .mobileBtn .btn {
            padding: 5px 14px;
            font-size: 16px;
            color: #fff;
            background: #036c8e;
            font-weight: 600;
        }

        .businessFeature p {

            text-align: justify ;

        }

        .prototype_banner_area {
            padding: 30px 0;
            background: #fff9eb;
        }

        .prototype_banner_area .section-title h1 {
            font-size: 19px;
            color: #000;
            font-weight: 600;
            line-height: 2rem;
            line-height: 1.5rem;
            font-family: inter, sans-serif;
            position: relative;
            margin-bottom: 25px;
            text-align: justify;
        }

        .demoImg {
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            overflow: hidden;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
            height: 190px;
        }

    }

    @media only screen and (max-width: 1024px) {

        .mob-login .default-btn {
                    display: inline-block;
                    padding: 8px;
                    color: #FFF;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                    z-index: 1;
                    font-size: 12px;

        }

        .mob-login .btn-bg-two {
            background-color: #ee5d19;
        }

        .mob-login {
            padding: 0 10px;
            height: 30px;
            cursor: pointer;
            z-index: 999;
            position: absolute;
            right: 60px;
            top: 20px;
        }
    }

    </style>

    

    @yield('css')

