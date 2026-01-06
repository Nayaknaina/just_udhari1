<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<title> JUST UDHARI </title>
<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png')}}"/>
<link href="{{ asset('main/assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('main/assets/js/loader.js')}}"></script>

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
<link href="{{ asset('main/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('main/assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="{{ asset('main/plugins/apex/apexcharts.css')}}" rel="stylesheet" type="text/css">
<link href="{{ asset('main/assets/css/dashboard/dash_1.css')}}" rel="stylesheet" type="text/css" class="dashboard-analytics" />
<link rel="stylesheet" type="text/css" href="{{ asset('main/assets/css/elements/alert.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('main/assets/css/elements/breadcrumb.css')}}">
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

<link href="{{ asset('main/assets/css/style2.css')}}" rel="stylesheet" type="text/css" />

<link href="{{ asset('main/plugins/animate/animate.css')}}" rel="stylesheet" type="text/css" />
<script src="{{ asset('main/plugins/sweetalerts/promise-polyfill.js')}}"></script>
<link href="{{ asset('main/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('main/plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('main/assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href = "{{ asset('theme/plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href = "{{ asset('theme/plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href = "{{ asset('theme/plugins/select2/css/select2.css')}}">

<style>

    .menu span {

        color: #FFF;
        white-space: normal;
        font-size: 9px;
        text-align: center;
        line-height: 12px;
        padding: 5px;
        margin: auto;
        display: block;
        width: 80%;
        overflow: hidden;
        font-weight: 600;

    }

    .list-group-item {

        padding: 10px ;

    }

    .select2-container--default .select2-selection--single {

        display: block;
        width: 100% !important;
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        /* border: 1px solid #ced4da; */
        border: 1px solid #a2a4a7;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;

    }

    .select2 {

        width: 100% !important;

    }

    input[disabled], select[disabled], textarea[disabled], input[readonly], select[readonly], textarea[readonly] {

        cursor: not-allowed;
        background-color: #f1f2f3 !important;
        color: #555656;
        border: 1px solid #767676;

    }

    input.is-invalid {

        border: 1px solid #dc3545;
        border-radius : 10px ;

    }

    .sn-box {

        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        flex-wrap: nowrap;
        align-content: center;
        align-items: center;

    }

    .sn-number { 

        margin-right: 10px;

    }

    .product-card {

        margin-bottom: 10px ;

    }

    .product-card .card-title{

        color : #000 ;
        text-align: center ;
        width: 100% ;

    }

    .product-card .view-btn{

        position: absolute;
        left: 10px;
        top: 10px;

    }

    .product-card .edit-btn{

        position: absolute ;
        right: 10px ;
        top: 10px ;

    }

    .product-card .img-fluid {

        max-width: 100%;
        height: 180px;
        object-fit: contain;

    }


</style>

@yield('css')

