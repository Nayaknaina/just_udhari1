@extends('ecomm.site')
@section('title', "Myu Cart")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp

<!-- Page Header Start -->
<div class="container-fluid">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 " >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">My Cart</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none  dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 row col-12">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @if(count($list))
                            @foreach($list as $key=>$kart)
                            <tr id="{{ $kart->url }}">
                                <td class="align-middle">
                                <p><img src="{{ asset("ecom/products/{ $kart->product->thumbnail_image }")}}" alt="" style="width: 60px;" style="float:left;"> Colorful  Stylish Shirt</p>
                                </td>
                                <td class="align-middle">Rs. {{ $kart->curr_cost }}/-</td>
                                <td class="align-middle">
                                    {{ $kart->quantity }}
                                </td>
                                <td class="align-middle">Rs. {{ $kart->curr_cost }}/-</td>
                                <td class="align-middle">
                                    <a href="{{ url("{$ecommbaseurl}remove/{$kart->url}") }}" class="btn btn-sm btn-danger remove_from_kart"><i
                                        class="fa fa-times"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        <!-- <tr>
                            <td class="align-middle">
                                 <p><img src="{{ asset("assets/ecomm/products/rings/ring_1.webp")}}" alt="" style="width: 60px;" style="float:left;"> Colorful  Stylish Shirt</p>
                            </td>
                            <td class="align-middle">Rs. 150/-</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center"
                                        value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">Rs. 150/-</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i
                                        class="fa fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td class="align-middle">
                                 <p><img src="{{ asset("assets/ecomm/products/rings/ring_1.webp")}}" alt="" style="width: 60px;" style="float:left;"> Colorful  Stylish Shirt</p>
                            </td>
                            <td class="align-middle base_price">Rs. <span class="unit_price">150</span>/-</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center quantity"
                                        value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle final_price">Rs. <span class="sum_price">150</span>/-</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i
                                        class="fa fa-times"></i></button></td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <!-- <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form> -->
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">$150</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">$10</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">$160</h5>
                        </div>
                        <a href="{{ url("{$ecommbaseurl}checkout") }}"class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection