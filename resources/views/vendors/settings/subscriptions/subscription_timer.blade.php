@extends('layouts.vendors.app')

@section('content')

@php

  //$data = component_array('breadcrumb' , 'Subscriptions' ,[['title' => 'Subscriptions']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Subscriptions") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <div class="container-fluid dashboard_default">

            @include('layouts.vendors.content.coundown')

    </div>

@endsection

