@extends('ecomm.site')
@section('title', "My Dashboard")
@section('content')

<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section ">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Dashboard</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 col-12 customer_info_block">
			
				<div class="row">
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}wishlist") }}"><span class="fa fa-heart"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}wishlist") }}"><p class="m-0"><b>WishList</b><br><span id="wish"></span></p></a>
                    </li>
                </ul>
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}cart")}}"><span class="fa fa-shopping-cart"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}cart")}}"><p class="m-0"><b>Cart</b><br><span id="cart"></span></p></a>
                    </li>
                </ul>
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}enquiries") }}"><span class="fa fa-paper-plane"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}enquiries") }}"><p class="m-0"><b>Enquiries</b><br><span id="enq"></span></p></a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}schemes") }}"><span class="fas fa-handshake"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}schemes") }}"><p class="m-0"><b>Schemes</b><br><span id="schm"></span></p></a>
                    </li>
                </ul>
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}orders") }}"><span class="fas fa-bookmark"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}orders") }}"><p class="m-0"><b>Orders  </b><br><span id="ordr"></span></p></a>
                    </li>
                </ul>
                <ul class="col-md-4 col-12 dash_info_block">
                    <li class="info_name"><a href="{{ url("{$ecommbaseurl}transactions") }}"><span class="fa fa-briefcase"></span></a></li>
                    <li class="info_value">
                        <a href="{{ url("{$ecommbaseurl}transactions") }}"><p class="m-0"><b>Transactions  </b><br><span id="txns"></span></p></a>
                    </li>
                </ul>
            </div>
			
			
        </div>
    </div>
</div>


@endsection
@section('javascript')
    <script>
        $.get("{{ url("{$ecommbaseurl}dashboard") }}","",function(response){
            $.each(response,function(id,val){
                var new_val = val??0;
                $("#"+id).empty().text(new_val);
            });
        });
    </script>
@endsection