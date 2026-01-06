@extends('ecomm.site')
@section('meta_title', "Page Not Found -")
@section('content')

@php

    $activemenu = '' ;

@endphp

<div class="container text-center ">
        <div class = "row justify-content-center">
            <div class = "col-lg-6">
                <div class="error-content">
                <img src="{{ url('assets/images/404-error.jpg') }}" alt="Image" class ="img-fluid" >
                <h3>Oops! Page Not Found</h3>
                <a href = "{{ url('/') }}" class="default-btn btn-bg-two">
                Return To Home Page
                </a>
                </div> 
            </div> 
        </div> 
</div> 

@endsection
