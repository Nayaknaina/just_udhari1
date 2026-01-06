@extends('layouts.vendors.app')

@section('css')

    <style>

        label.radio{

            background:#d7d7d7;

        }

        label.radio.checked{

            background:white;

        }

    </style>

@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Ecom Dashboard',[['title' => 'Ecommerce']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php $data = new_component_array('newbreadcrumb',"Ecomm Dashboard") @endphp 
<x-new-bread-crumb :data=$data />
<section class="content">
<div class="container-fluid">

  <div class="row justify-content-center">
    <div class="col-md-12">
    <div class="card ">
    <div class="card-body">
        <div class="row">

        </div>
  </div>
</div>
</div>
</div>
</div>
</section>

@endsection
