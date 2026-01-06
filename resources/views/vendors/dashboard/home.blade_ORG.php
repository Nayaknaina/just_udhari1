@extends('layouts.vendors.app')

@section('content')

@php

  //$data = component_array('breadcrumb' , 'Dashboard' ,[['title' => 'Dashboard']] ) ;

  $data = new_component_array('newbreadcrumb' , 'Dashboard') ;
@endphp

<x-new-bread-crumb :data=$data />
{{--<x-page-component :data=$data />--}}

    <div class="container-fluid dashboard_default">

    </div>

@endsection

