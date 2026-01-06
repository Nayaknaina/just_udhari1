@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Schemes Due',[['title' => 'Schemes Due']] ) ;

@endphp

<x-page-component :data=$data />

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Scheme Dues</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body row">
                                            <div class="col-7 p-0">
                                                <div class="title_block">
                                                    <h5>Laabh Lakshmi Yojnaa</h5>
                                                    <hr class="m-1">
                                                    <h6>Jamaa pr 10% Interest</h6>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body row">
                                            <div class="col-7 p-0">
                                                <div class="title_block">
                                                    <h5>Shubh Lakshmi Yojnaa</h5>
                                                    <hr class="m-1">
                                                    <h6>5000/- me 50000/- kee Jewellery<sup class="text-danger">*</sup></h6>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Group Dues</h3>
                        </div>
                        <div class="card-body">
                            <h4 >Laabh Lakshmi Yojnaa</h4>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','a') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row">
                                            <div class="col-7 p-0">
                                                <div class="title_block pl-2">
                                                    <h5>Group A (LL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                    <a href="{{ route('group.show','b') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row">
                                            <div class="col-7 p-0">
                                                <div class="title_block pl-2">
                                                    <h5>Group B (LL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','c') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row ">
                                            <div class="col-7 p-0">
                                                <div class="title_block  pl-2">
                                                    <h5>Group C (LL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','d') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row ">
                                            <div class="col-7 p-0">
                                                <div class="title_block  pl-2">
                                                    <h5>Group D (LL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 >Shubh Lakshmi Yojnaa</h4>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','e') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row">
                                            <div class="col-7 p-0">
                                                <div class="title_block pl-2">
                                                    <h5>Group A (SL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','f') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row">
                                            <div class="col-7 p-0">
                                                <div class="title_block  pl-2">
                                                    <h5>Group B (SL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','g') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row ">
                                            <div class="col-7 p-0">
                                                <div class="title_block  pl-2">
                                                    <h5>Group C (SL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card group_block">
                                        <a href="{{ route('group.show','h') }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                        <div class="card-body p-2 row ">
                                            <div class="col-7 p-0">
                                                <div class="title_block  pl-2">
                                                    <h5>Group D (SL)</h5>
                                                </div>
                                            </div>
                                            <div class="col-5 border-1 p-0">
                                                <ul class="title_value m-0">
                                                    <li class="text-primary total">675765</li>
                                                    <li class="text-success received">765765</li>
                                                    <li class="text-danger remains">76767</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <style>
        a.overlay_anchor{
            text-align:center;
            color:#b7aeae;
            height:100%;
            width:100%;
            background:#00000094;
            position: absolute;
            font-size:200%;
            z-index:-1;
        }
        a.overlay_anchor.anchor_visible{
            z-index:1!important;
        }
        a.overlay_anchor:after{

        }
        a.overlay_anchor>li{
            padding:10%;
        }
        .title_block{
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .title_value > .total::marker{
            content:"\0023  ";

        }
        .title_value > .received::marker{
            content:"\2713  ";
        }
        .title_value > .remains::marker{
            content:"?  ";
        }
        .title_value >li::marker{
            font-weight:bold;
            border:1px solid gray;
            padding:2px;
        }
        .title_value{
            border-left:1px solid lightgray;
        }
    </style>
@endsection

  @section('javascript')


  <script>
    // $('.group_block').hover(function(){
    //     const a = $(this).find('a.overlay_anchor');
    //     alert($(a).html());
    //     if($(this).prev('.overlay_anchor').hasClass('anchor_visible')){
    //         $(this).prev('.overlay_anchor').addClass('anchor_visible');
    //     }else{
    //         $(this).prev('.overlay_anchor').removeClass('anchor_visible');

    //     }
    // });
    $(".group_block").mouseover(function () {
        const a = $(this).find('a.overlay_anchor');
        a.addClass('anchor_visible');
    });
    $(".group_block").mouseout(function () {
        const a = $(this).find('a.overlay_anchor');
        a.removeClass('anchor_visible');
    });
  </script>



  @endsection
