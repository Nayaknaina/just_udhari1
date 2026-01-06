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
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Wishlist</h1>
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
        <div class="col-md-9 customer_info_block">
            <div class="table-responsive mb-5">
                <table class="table table-bordered text-center mb-0 table-stripped bg-white">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
							<th>Image</th>
                            <th>Quantity</th>
                            <th>Rate</th>
							<th>Labour</th>
                            <th>Total</th>
                            <th>Remove</th>
                            <th>Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @php 
							$labour_total = $new_amount_total =  $total_amount_total= $total_cost = $quantity = 0;
						@endphp
                        @if(count($list))
                            @foreach($list as $key=>$kart)
                            <tr id="{{ $kart->url }}"  class="{{ $kart->product->stock->item_type }}">
                                <td class="align-middle">
                                <p><img src="{{ url("ecom/products/{$kart->product->thumbnail_image}") }}" alt="{{ $kart->product->name }}" class="img-responsive img-thumbnail"></p>
                                </td>
								<td>{{ $kart->product->name }}</td>
								@php 
									$stock = $kart->product->stock;
									$qnt = $kart->quantity;
									$labr_chrg = $stock->labour_charge*$kart->quantity;
									$unit = "";
									if($stock->item_type=='genuine'){
										$quantity++;
										$cat_name = strtolower($stock->category->name);
										$per_grm_rate=   todayrate($stock->category->name,$stock->caret);
										$quant_price = round($kart->quantity*$per_grm_rate,2);
										
										$labr_chrg = $stock->labour_charge*$kart->quantity;
										$labr_chrg_total = $labr_chrg*$kart->quantity;
										$total_cost = $quant_price+$labr_chrg;
										$unit = "grms";
									}else{
										$quantity++;
										$per_grm_rate = $kart->product->rate;
										$quant_price = $kart->product->rate*$kart->quantity;
										$total_cost = $quant_price;
										$labr_chrg_total = $stock->labour_charge*$kart->quantity;
										$unit = 'pcs';
									}
									$new_amount_total  +=$quant_price;
									$labour_total +=$labr_chrg;
									$total_amount_total  +=$total_cost;
								@endphp
                                @php
									/*
                                    $curr_amount = todayrate($kart->curr_cost;
                                    $new_amount = $kart->product->rate;
                                    $quantity = $kart->quantity;
                                    $total_cost = $new_amount*$quantity;
                                    $new_amount_total  +=$new_amount;
                                    $quantity_total  +=$quantity;
                                    $total_amount_total  +=$total_cost;*/
                                @endphp
                                <td class="align-middle ">	
									<span class="unit_qnt">{{ @$qnt}}</span><span>{{ @$unit }}</span>
									@if($stock->item_type=='genuine')
									<hr class="m-1 p-0">
									<small><b>{{ $stock->caret }} K</b></small>
									@endif
								</td>
								<td class="align-middle">
									<span class="unit_price">{{ $quant_price }}</span>/-
									<hr class="m-1 p-0">
									<small><b>{{ $qnt.@$unit." x ".$per_grm_rate }} Rs</b></small>
								</td>
								<td class="align-middle">
									<span class="unit_labour">{{ ($labr_chrg!=0)?$labr_chrg:'0' }} </span>/-
									<hr class="m-1 p-0">
									@php 
										$curr_lbr = (!empty($stock->labour_charge))?$stock->labour_charge:0
									@endphp
									<small><b>{{ $qnt.@$unit." x ". $curr_lbr}} Rs</b></small>
								</td>
								<td class="align-middle">Rs. <span class="sub_price">{{ $total_cost }}</span>/-</td>
                                <td class="align-middle">
                                    <a href="{{ url("{$ecommbaseurl}removewish/{$kart->id }") }}" class="btn btn-sm btn-outline-danger remove_from_wishlist">
                                    <i class="fa fa-times"></i></button>
                                </td>
                                <td class="align-middle">
                                <a href="{{ url("{$ecommbaseurl}movetokart/{$kart->id }") }}" class="btn btn-sm btn-primary text-white move_to_cart">
                                    <i class="fa fa-cart-plus"></i>
                                </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr><td class="align-middle text-danger align-center" colspan="8">Nothing in WishList !</td></tr>
                        @endif
                    </tbody>
                    <tfoot class="bg-secondary">
                        <tr>
                        <td></td>
                        <td></td>
                        <td class="total_base_price">Rs.  <span id="total_unit_price">{{ $new_amount_total }}</span>/-</td>
                        <td class="total_quantity text-center">--</td>
                        <td class="total_quantity text-center">--</td>
                        <td class="total_final_price">Rs. <span id="total_sum_price">{{ $total_amount_total }}</span>/-</td>
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
