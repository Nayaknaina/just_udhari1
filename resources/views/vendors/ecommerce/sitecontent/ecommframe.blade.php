<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Simple Responsive Admin</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="{{ asset('assets/ecomm/admin/css/bootstrap.css') }}" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="{{ asset('assets/ecomm/admin/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    
    @stack('style')
 </head>
 <body>
   <style>
      #process_await{
         position:absolute;
         top:0;
         left:0;
         width:100%;
         height:100%;
         background:#000000a1;
         padding-top:100px;
      }
      #page_loading{
         position:fixed;
         top:0;
         left:0;
         width:100vw;
         height:100vh;
         background:#00000040;
         padding-top:45vh;
      }
      #process_await > .content,#page_loading > .content{
         margin:auto;
         width:max-content;
         color:#c15837;
         text-align:center;
         font-size:2rem;
         padding:5px;
         border:1px solid black;
         background:white;
      }
      .required_label:after{
         content:"*";
         color:red;
      }
   </style>
    @yield('css')
    
    @yield('content')
    <div id="page_loading" class="text-center">
         <div class="content">
            <li class="fa fa-spinner fa-spin"></li> Page Loding !
         </div>
    </div>
    <!-- JQUERY SCRIPTS -->
    <script src="{{ asset('assets/ecomm/admin/js/jquery-1.10.2.js') }}"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="{{ asset('assets/ecomm/admin/js/bootstrap.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <!-- CUSTOM SCRIPTS -->

    @stack('script')
    @yield('js')

 </body>
 </html>