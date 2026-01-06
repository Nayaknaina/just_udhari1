@extends('ecomm.site')
@section('title', "Myu Cart")
@section('content')
@php 
    @$$activemenu = 'active';
@endphp

<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 " >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase my-2" style="margin:auto;">My Cart</h1>
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
        <div class="col-md-9 customer_info_block">
            <div class="row">
                <div class="col-lg-8  mb-5">
                    <div class="table-responsive">
                        <table class="table table-bordered table-stripped text-center mb-0 bg-white">
                            <thead class="bg-secondary text-dark">
                                <tr>
									<th>Product</th>
                                    <th>Title</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Labour</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @php 
                                $labour_total = $new_amount_total =  $total_amount_total= $total_cost = $quantity = 0;
                                @endphp
                                @if(count($list))
                                    @foreach($list as $key=>$kart)
                                    <tr id="{{ $kart->url }}" class="{{ $kart->product->stock->item_type }}">
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
												
												//$labr_chrg = $stock->labour_charge*$kart->quantity;
                                                $total_cost = $quant_price+$labr_chrg;
                                                $unit = "grms";
                                            }else{
                                                $quantity++;
												$per_grm_rate = $kart->product->rate;
                                                $quant_price = $kart->product->rate*$kart->quantity;
                                                $total_cost = $quant_price;
                                                $labr_chrg = 0;
												$unit = 'pcs';
                                            }
                                            $new_amount_total  +=$quant_price;
                                            $labour_total +=$labr_chrg;
                                            $total_amount_total  +=$total_cost;
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
                                            <a href="{{ url("{$ecommbaseurl}removecart/{$kart->id}") }}" class="btn btn-sm btn-outline-danger remove_from_kart">
                                            <i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr><td class="align-middle text-danger align-center" colspan="7">Cart is Empty !</td></tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-secondary">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="total_labour align-middle">--</td>
                                    <td class="total_base_price">Rs. <span id="total_unit_price">{{ $new_amount_total }}</span>/-</td>
                                    <td class="total_quantity">Rs. <span id="total_sum_labour">{{ $labour_total }}</span>/-</td>
                                    <td class="total_final_price">Rs. <span id="total_sum_price">{{ $total_amount_total }}</span>/-</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-secondary mb-5"> 
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0 text-white">Cart Summary</h4>
                        </div>
                        <form role="" action="{{ url("{$ecommbaseurl}createorder") }}" method="post" id="checkour_form">
                            @csrf
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="font-weight-medium" >Quanity</h6>
                                    <input type="hidden" name="quantity" value="{{ $quantity }}">
                                    <h6 class="font-weight-medium" id="final_quantity">{{ $quantity }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pt-1">
                                    <h6 class="font-weight-medium" >Subtotal</h6>
                                    <input type="hidden" name="total" value="{{ $total_amount_total }}">
                                    <h6 class="font-weight-medium">RS. <span id="final_subtotal">{{ $total_amount_total }}</span>/-</h6>
                                </div>
                            </div>
                            <div class="card-footer border-secondary bg-transparent">
                                <div class="d-flex justify-content-between mt-2">
                                    <h5 class="font-weight-bold">Total</h5>
                                    <h5 class="font-weight-bold">Rs. <span id="final_amount">{{ $total_amount_total }}</span>/-</h5>
                                    <input type="hidden" name="from" value="cart">
                                </div>
                                @if($total_amount_total>0)
                                <button type="submit" name="checkout" value="yes" class="btn btn-block btn-primary my-3 py-3 text-white">Checkout ?</button>
                                @else
                                <button type="button" class="btn btn-block btn-primary my-3 py-3 text-white" onclick="alert('Your Cart is Empty !');">Checkout ?</button>
                                @endif
                                <!-- <a href="{{ url("{$ecommbaseurl}checkout") }}"class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection
@section('javascript')
<script>
    $('#checkour_form').submit(function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var formdata = $(this).serialize();
        $.post(url,formdata,function(response){
            var res = JSON.parse(response);
            if(res.status){
                window.location.href= "{{ url("{$ecommbaseurl}") }}/"+res.next;
            }else{
                alert(res.msg);
            }
        });
    });
</script>
@endsection
