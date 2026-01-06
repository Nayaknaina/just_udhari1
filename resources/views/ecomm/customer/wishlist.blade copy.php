@extends('ecomm.site')
@section('title', "Myu Whishlist")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">My Wishlist</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
    <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 row col-12">
            <div class="col-lg-12 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0 table-stripped">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                            <th>Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <tr>
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
                            <td  class="align-middle">
                                <a href="" class="btn btn-sm btn-primary text-white">
                                    <i class="fa fa-cart-plus"></i>
                                </a>
                            </td>
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
                            <td  class="align-middle">
                                <a href="" class="btn btn-sm btn-primary text-white">
                                    <i class="fa fa-cart-plus"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-secondary">
                        <tr>
                        <td></td>
                        <td class="total_base_price">Rs. <span <span class="total_unit_price">1000</span>/-</td>
                        <td class="total_quantity">100</td>
                        <td class="total_final_price">Rs. <span class="total_sum_price">2000</span>/-</td>
                        <td></td>
                        <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection