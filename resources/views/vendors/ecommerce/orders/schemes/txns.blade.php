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

//$data = component_array('breadcrumb' , 'Scheme TXN Detail',[['title' => 'Ecomm-Orders']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('ecomorders.schemes').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Ecomm Scheme Orders"=>route('ecomorders.schemes')];
$data = new_component_array('newbreadcrumb',"Ecomm Scheme Txns",$path) 
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
                        <h5 class="card-title"> <x-back-button /> Transaction Detail </h5>
                        </div>--}}
                        <div class="card-body p-2">
                            <div class="col-12">
                                <div class = "row">
                                    <div class="col-md-7">
                                        <h5>Customer Info</h5>
                                        <hr class="m-1 p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="row p-0" style="list-style:none;">
                                                    <li class="col-md-4 col-12"><b>NAME</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_full_name }}</li>
                                                    <li class="col-md-4 col-12"><b>CONTACT</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_fone }}</li>
                                                    <li class="col-md-4 col-12"><b>E-MAIL</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_mail??'-----' }}</li>
                                                </ul> 
                                            </div>
                                            @php 
                                                $address = $order->customer->custo_address;
                                                $state = $order->customer->state_name;
                                                $district = $order->customer->dist_name;
                                                $area = $order->customer->area_name;
                                                $tehsil = $order->customer->teh_name;
                                                $pincode = $order->customer->pin_code;
                                            @endphp 
                                            <div class="col-md-6">
                                                <ul class="row p-0" style="list-style:none;">
                                                    <li class="col-md-4 col-12"><b>STATE</b></li>
                                                    <li class="col-md-8 col-12 text-right">
                                                        {{ $state }}
                                                    </li>
                                                    <li class="col-md-4 col-12"><b>DISTRICT</b></li>
                                                    <li class="col-md-8 col-12 text-right">
                                                        {{ $district }}
                                                    </li>
                                                    <li class="col-md-4 col-12"><b>AREA</b></li>
                                                    @php 
                                                        $loc = trim($tehsil);
                                                        $loc.=($loc!="")?"/":"";
                                                        $loc.=trim($area);
                                                        $loc.=($loc!="")?"({$pincode})":$pincode;
                                                    @endphp
                                                    <li class="col-md-8 col-12 text-right">
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
                                            <li class="col-md-4 col-12"><b>NUMBER</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ $order->detail_unique }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>DATE</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ $order->created_at }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>PAYMENT</b></li>
                                            @php
                                                $color_arr = ["warning","success","danger"];
                                                $txt_arr = ["Pending","Paid","Unpaid"];
                                            @endphp
                                            <li class="col-md-8 col-12 text-right text-{{ @$color_arr[$order->schemetxn->txn_status] }}">
                                            <b>{{ @$txt_arr[$order->schemetxn->txn_status]??'-----' }}</b>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 py-1" style="box-shadow:1px 2px 3px 3px gray;">
                                <h4 class="text-secondary text-center">Enrollment Detail</h4>
                                <hr class="m-1 p-0">
                                <ul class="row p-0" style="list-style:none;">
                                    <li class="col-md-1 col-12"><b>SCHEME</b></li>
                                    <li class="col-md-2 col-12">{{ $order->enroll->schemes->scheme_head }}</li>
                                    <li class="col-md-1 col-12"><b>GROUP</b></li>
                                    <li class="col-md-2 col-12">{{ $order->group->group_name }}</li>
                                    <li class="col-md-1 col-12"><b>NAME</b></li>
                                    <li class="col-md-2 col-12">{{ $order->enroll->customer_name }}</li>
                                    <li class="col-md-1 col-12"><b>EMI</b></li>
                                    <li class="col-md-2 col-12">{{ $order->enroll->emi_amnt }} Rs</li>
                                </ul>
                                <hr>
                                <h5 class="col-12 text-center text-info">Transaction Info</h5>
                                <hr class="m-1 p-0">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <ul class="row p-0" style="list-style:none;">
                                            <li class="col-md-4 col-12"><b>DATE</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ @$order->schemetxn->created_at??"------" }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>PAY BY</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ @$order->schemetxn->txn_by??'------' }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>TXN NUMBER</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->txn_number??'------' }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>AMOUNT</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->order_amount??'------' }} Rs.
                                            </li>
                                            <li class="col-md-4 col-12"><b>MODE</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->txn_mode??'------' }} 
                                            </li>
                                            <li class="col-md-4 col-12"><b>MEDIUM</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->txn_medium??'------' }} 
                                            </li>
                                        </ul> 
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <h5 class="p-1" style="background: #c6c6c6;color: white;">Gateways Response </h5>
                                        <ul class="row p-0" style="list-style:none;">
                                            <li class="col-md-4 col-12"><b>TXN NUMBER</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->gateway_txn_id??'------' }} 
                                            </li>
                                            <li class="col-md-4 col-12"><b>STATUS</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->gateway_txn_status??'------' }} 
                                            </li>
                                            <li class="col-md-4 col-12"><b>CODE</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                            {{ @$order->schemetxn->txn_res_code??'------' }} 
                                            </li>
                                            <li class="col-md-4 col-12"><b>MSG</b></li>
                                            <li class="col-md-8 col-12  text-right">
                                            {{ @$order->schemetxn->txn_res_msg??'------' }} 
                                            </li>
                                        </ul> 
                                    </div>
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