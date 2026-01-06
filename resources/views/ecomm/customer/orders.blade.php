@extends('ecomm.site')
@section('title', "My Orders")

@section('content')
@section('stylesheet')
    <style>
        .order_belt{
            text-decoration:none!important;
        }
        .order_belt:hover{
            
            font-weight:bold;
        }
    </style>
@endsection
@php 
    @$$activemenu = 'active';
    //dd($orders);
@endphp
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Orders</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!--<div class="container-fluid">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2" >
        <div class="customer_page_head col-md-4 row text-center px-1" >
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase  my-2" style="margin:auto;">My Orders</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>-->
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">  
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 customer_info_block">
            <div class="row">
                <div class="col-12">
                    @if(count($orders)>0)
                        @foreach($orders as $ordr=>$order)
                            <div class="card">
                                <div class="card-header bg-{{ ($order->pay_status=='1')?'success':'danger' }}">
                                    <a href="#{{ $order->order_unique }}" class="order_belt"><h3 class="card-title text-white p-2 m-0"> {{ $order->order_unique}}<span class="pull-right" style="float:right;">Rs. {{ $order->total}} /-</span></h3></a>
                                    
                                </div>
                                <div class="card-body order_detail_block" id="{{ $order->order_unique }}" style="display:none;">
                                    @if(count($order->orderdetail)>0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">PRODUCT</th>
                                                        <th>QUANTITY</th>
                                                        <th>PRICE</th>
                                                        <th>LABOUR</th>
                                                        <th>SUM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php 
                                                        $final_amount = $unit_amount_sum=$unit_quantity_sum= 0 
                                                    @endphp 
                                                    @foreach($order->orderdetail as $dtli=>$detail)
													@if($detail->product)
                                                    <tr>
                                                        <td class="text-center">
                                                            @if (isset($detail->product) && $detail->product->thumbnail_image!="")
                                                            <img src="{{ asset("ecom/products/{$detail->product->thumbnail_image}")}}" class="img-responsive" style="height:50px;">
                                                            @else
                                                                {{ @$detail->detail_unique }}
                                                            @endif
                                                            <p>
                                                            {{ @$detail->product->name }}</p>
                                                        </td>
                                                        <td>
                                                        @if(@$detail->product->stock->item_type=='genuine')
                                                            {{ $detail->product->stock->quantity }} grms
                                                        @else 
                                                            {{ $detail->quantity }}
                                                        @endif
                                                        </td>
                                                        @php 
                                                        $unit = ($detail->product->stock->item_type=='genuine')?'grms':'';
                                                        @endphp
                                                        <td>
                                                            @php 
                                                                $cost = round($detail->curr_cost*$detail->quantity,2);
                                                            @endphp
                                                            Rs.{{ $cost }} /-
                                                        </td>
                                                        <td>
                                                        @php
                                                        $lbr = 0;
                                                        if($detail->product->stock->item_type=='genuine'){
                                                            $lbr = ($detail->product->strike_rate!="")?$detail->product->strike_rate*$detail->quantity:0;
                                                        }
                                                        @endphp
                                                            Rs.{{ $lbr }}/-
                                                        </td>
                                                        <td>
                                                            @php $sub = $cost+$lbr; @endphp
                                                        Rs.{{ $sub }} /-
                                                        </td>
                                                    </tr>
                                                    @php  $final_amount+= $sub @endphp
													@endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="text-right" colspan="4">
                                                        <h3 class="font-weight-medium m-0">Payable</h3>
                                                        </td>
                                                        <td>
                                                            Rs. {{  @$final_amount }} /-
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center" colspan="4">
                                                        
                                                        </td>
                                                        <td>
                                                        <a href="{{ url("{$ecommbaseurl}checkout/{$order->order_unique}") }}" class="btn btn-primary text-white">Order Now ?</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                    <div class="alert alert-warning text-center">No Order Details !</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                    <div class="alert alert-warning text-center">No Pending Orders !</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('javascript')
<script>
    $('.order_belt').click(function(e){
        e.preventDefault();
        $('.order_detail_block').hide();
        $($(this).attr('href')).show();
    });
</script>
@endsection