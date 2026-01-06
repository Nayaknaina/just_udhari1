@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')
    <style>
    .detail_show{
      border:1px solid lightgray;
      color:blue;
      padding:2px;
    }
    .detail_show:hover{
      color:black;
      border:1px solid blue;
    }
    </style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Order Detail',[['title' => 'Ecomm-Orders']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('ecomorders.productordertxns',['id'=>$order->id]).'" class="btn btn-sm btn-outline-info"><i class="fa fa-exchange"></i> Order Txn</a>'];
$path = ["Ecomm Orders"=>route('ecomorders.products'),"Txns"=>route('ecomorders.productordertxns',['id'=>$order->id])];
$data = new_component_array('newbreadcrumb',"Ecomm Order Detail",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <!-- left column -->
                <div class="col-md-12">
                <!-- general form elements -->
                    <div class="card card-primary">
					{{--<div class="card-header">
                        <h5 class="card-title"><x-back-button /> Order Detail </h5>
                        </div>--}}
                        <div class="card-body p-2">
                            <div class = "row">
                                <div class="col-md-7">
                                    <h5>Shiping Detail</h5>
                                    <hr class="m-1 p-0">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="row p-0" style="list-style:none;">
                                                <li class="col-md-4 col-12"><b>NAME</b></li>
                                                <li class="col-md-8 col-12">{{ $order->customer->custo_full_name }}</li>
                                                <li class="col-md-4 col-12"><b>CONTACT</b></li>
                                                <li class="col-md-8 col-12">{{ $order->customer->custo_fone }}</li>
                                                <li class="col-md-4 col-12"><b>E-MAIL</b></li>
                                                <li class="col-md-8 col-12">{{ $order->customer->custo_mail??'-----' }}</li>
                                            </ul> 
                                        </div>
                                        @php 
                                            $address = $state = $district = $area = $tehsil = $pincode = null;
                                            if($order->ship_id==1){
                                                $address = $order->customer->shiping->custo_address;
                                                $state = $order->customer->shiping->state_name;
                                                $district = $order->customer->shiping->dist_name;
                                                $area = $order->customer->shiping->area_name;
                                                $tehsil = $order->customer->shiping->teh_name;
                                                $pincode = $order->customer->shiping->pin_code;
                                            }else{
                                                $address = $order->customer->custo_address;
                                                $state = $order->customer->state_name;
                                                $district = $order->customer->dist_name;
                                                $area = $order->customer->area_name;
                                                $tehsil = $order->customer->teh_name;
                                                $pincode = $order->customer->pin_code;
                                            }
                                        @endphp 
                                        <div class="col-md-6">
                                            <ul class="row p-0" style="list-style:none;">
                                                <li class="col-md-4 col-12"><b>STATE</b></li>
                                                <li class="col-md-8 col-12">
                                                    {{ $state }}
                                                </li>
                                                <li class="col-md-4 col-12"><b>DISTRICT</b></li>
                                                <li class="col-md-8 col-12">
                                                    {{ $district }}
                                                </li>
                                                <li class="col-md-4 col-12"><b>AREA</b></li>
                                                @php 
                                                    $loc = trim($tehsil);
                                                    $loc.=($loc!="")?"/":"";
                                                    $loc.=trim($area);
                                                    $loc.=($loc!="")?"({$pincode})":$pincode;
                                                @endphp
                                                <li class="col-md-8 col-12">
                                                    {{ $loc }}
                                                </li>
                                            </ul> 
                                        </div>
                                        <div class="col-12">
                                            <b>ADDRESS</b><address>{{ $address }}</address>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Order</h5>
                                    <hr class="m-1 p-0">
                                    <ul class="row p-0" style="list-style:none;">
                                        <li class="col-md-4 col-12"><b>ID</b></li>
                                        <li class="col-md-8 col-12">
                                            {{ $order->order_unique }}
                                        </li>
                                        <li class="col-md-4 col-12"><b>DATE</b></li>
                                        <li class="col-md-8 col-12">
                                            {{ $order->created_at }}
                                        </li>
                                        <li class="col-md-4 col-12"><b>PAYMENT</b></li>
                                        @php
                                            $color_arr = ["warning","success","danger"];
                                            $txt_arr = ["Pending","Paid","Unpaid"];
                                        @endphp
                                        <li class="col-md-8 col-12 text-{{ $color_arr[$order->pay_status] }}">
                                        <b>{{ $txt_arr[$order->pay_status] }}</b>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    @php 
                                        $total = 0;
                                    @endphp
                                    @if($order->orderdetail->count()>0)
                                          
                                        <table class="table table-bordered table-stripped">
                                            <thead>
                                                <tr class="bg-dark">
                                                    <th>SN</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Cost</th>
                                                    <th>Labour</th>
                                                    <th>Sum</th>
                                                </tr>
                                            </thead>
											
                                            <tbody>
											@if($order->orderdetail->count()>0)
											@php 
                                                $url = app('userd')->shopbranch->domain_name;
                                            @endphp
                                            @foreach($order->orderdetail as $odk=>$dtl)
											
											@if(!empty($dtl->product))
                                            <tr>
                                                <td>{{ $odk+1 }}</td>
                                                <td class="text-center">
                                                    <a href="www.{{$url}}/products/{{ @$dtl->product->url}}" target="_blank">
                                                        <img src="{{ asset("ecom/products/{$dtl->product->thumbnail_image}")}}" class="img-responsive img-thumbnail" style="height:150px;width:auto;">
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ url("{$url}/product/{$dtl->product->url}") }}" target="_blank" class="btn btn-outline-secondary">
                                                    {{ $dtl->product->name }}
                                                    </a>
                                                </td>
                                               @php 
                                                $cost = round($dtl->curr_cost*$dtl->quantity,2);
                                               @endphp
                                               <td class="text-center">{{ $dtl->quantity }} {{ ($dtl->product->stock->item_type=='genuine')?'grms':'' }}</td>
                                                <td class="text-right">{{ $cost }} Rs.</td>
                                                @php 
                                                    $labour = ($dtl->product->stock->item_type=='genuine')?$dtl->product->strike_rate*$dtl->quantity:false;
                                                    $sum =$cost+$labour;
                                                @endphp 
                                                <td class="text-center">
                                                {{ ($dtl->product->stock->item_type=='genuine')?$labour.'Rs':'0' }}
                                                </td>
                                                <td>
                                                    {{ $sum  }}
                                                </td>
                                                @php 
                                                $total+= $sum;
                                                @endphp
                                            </tr>
											@else 
                                            <tr><td colspan="7"  class="text-center text-danger">No Associated Product !</td></tr>
                                            @endif
                                            @endforeach
											
											@else 
												<tr><td colspan="7"  class="text-center text-danger">No Items Found !</td></tr>
											@endif
                                            </tbody>
                                            <tfoot>
                                                <tr><th colspan="6" class="text-right">TOTAL</th>
                                                <td class="text-right">{{$total}} Rs.</td></tr>
                                            </tfoot>
                                        </table>
                                    @else 
                                            <div class="alert alert-warning">No Order Detail</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>

    </div>
  
@endsection

  @section('javascript')





  @endsection