@extends('ecomm.site')

@section('title', "Schemes")

@section('content')

@php


   if($url=='Subh-Lakshmi-Yojnaa') {

        $schemes =
                [
                        [ 'image' => 'assets/ecomm/images/schemes/1.jpg'] ,
                        [ 'image' => 'assets/ecomm/images/schemes/2.jpg'] ,
                ]  ;

   }else{

        $schemes =  [
                        ['image' => 'assets/ecomm/images/schemes/Mg_page-0001.jpg'] , 
                        ['image' => 'assets/ecomm/images/schemes/Mg_page-0002.jpg'] , 
                        ['image' => 'assets/ecomm/images/schemes/Mg_page-0003.jpg'] , 
                        ['image' => 'assets/ecomm/images/schemes/Mg_page-0004.jpg'] , 
                    ] ; 

   }

   $string_to_remove = "-";

    $cleaned_url = str_replace($string_to_remove, " ", $url);

@endphp

<!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5 breadcrumb-section ">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2 py-2">
        <h1 class="font-weight-semi-bold text-uppercase mb-3"> {{  $cleaned_url }}
             </h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="{{url("$ecommbaseurl") }}" class="breadcrump_link_main">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Scheme</p>
        </div>
    </div>
</div>
<!-- Page Header End -->
<!-- Offer Start -->
<div class="container offer-details pt-5">
    <div class="row px-xl-5">

        <div class ="col-lg-12 text-center pb-5">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Get Scheme Started
            </button>
        </div>

        @foreach ($schemes as $scheme )

            <div class ="col-lg-6">
                <img src="{{ asset($scheme['image']) }}" class="img-fluid" alt="">
            </div>

        @endforeach

    </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> Scheme Enquiry </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form name="sentMessage" id="contactForm" novalidate="novalidate">
                <div class="control-group">
                  <label for="user">Username</label>
                  <input type="text" class="form-control" id="user" placeholder="Username" required="required"
                    data-validation-required-message="Please enter your username" />
                  <p class="help-block text-danger"></p>
                </div>
                <div class="control-group">
                  <label for="pass">Password</label>
                  <input type="password" class="form-control" id="pass" placeholder="Password" required="required"
                    data-validation-required-message="Please enter your password" />
                  <p class="help-block text-danger"></p>
                </div>
                <div class="control-group text-right">
                  <button class="btn btn-primary py-2 px-4" type="submit" id="sendMessageButton">Login</button>
                </div>
              </form>
        </div>
        <div class="modal-footer">
          <a href="{{ url("{$ecommbaseurl}register") }}"> If you don't have a account Register here </a> 
        </div>
      </div>
    </div>
  </div>

@endsection
