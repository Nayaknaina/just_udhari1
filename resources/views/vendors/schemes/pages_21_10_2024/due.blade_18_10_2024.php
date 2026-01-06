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
                <div class="col-md-12 mb-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Scheme Dues</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($scheme_data as $key=>$scheme)
                                    <div class="col-md-4">
                                        <div class="card mb-2">
                                            <div class="card-body row pt-0 pb-0">
                                                <div class="col-7 p-0">
                                                    <div class="title_block">
                                                        <h5>{{ $scheme['head'] }}</h5>
                                                        <!-- <hr class="m-1"> -->
                                                    </div>
                                                </div>
                                                <div class="col-5 border-1 p-0 title_block_value">
                                                    <ul class="title_value m-0">
                                                        <li class="text-primary total">{{ $scheme['payable']}}</li>
                                                        <li class="text-success received">{{ $scheme['received']}}</li>
                                                        <li class="text-danger remains">{{ $scheme['payable']-$scheme['received']}}</li>
                                                    </ul>
                                                    <h6 class="bg-danger text-center ml-1">- {{ $scheme['bonus'] }}</h6>
                                                </div>
                                                <hr class="m-0 col-12 sub_hr p-0">
                                                <div class="col-12 p-1">
                                                <h6>{{ $scheme['sub'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Group Dues</h3>
                        </div>
                        @foreach($group_data as $scheme=>$groups)
                            <div class="card-body">
                                <h4 >{{ $scheme }}</h4>

                                <div class="row">
                                    @foreach($groups as $gk=>$group)
                                    <div class="col-md-3">
                                        <div class="card group_block mb-2">
                                            <a href="{{ route("shopscheme.enrollgroup",$group['id']) }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                            <div class="card-body pt-0 pb-0 row">
                                                <div class="col-7 p-0">
                                                    <div class="title_block pl-2">
                                                        <h5>{{ $group['name'] }}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-5 border-1 p-0 title_block_value">
                                                    <ul class="title_value m-0 pl-4">
                                                        <li class="text-primary total">{{ $group['payable'] }}</li>
                                                        <li class="text-success received">{{ $group['received'] }}</li>
                                                        <li class="text-danger remains">{{ $group['payable']-$group['received'] }}</li>
                                                        
                                                    </ul>
                                                    <h6 class="bg-danger text-center ml-1">- {{ $group['bonus'] }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
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
            position: inherit;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        .title_block_value{
            border-left:1px solid lightgray;
        }
        .title_value > .li::marker{
            content:"";
            font-weight:bold;
            padding:2px;
            border:1px solid gray;
        }
        .title_value > .total::marker{
            content:"\0023  ";

        }
        .title_value > .received::marker{
            content:"\2713  ";
        }
        .title_value > .remains::marker{
            content:"? ";
        }
        .title_value > .bonus::marker{
            content:"- ";
            color:red;
        }
        /* .title_value >li::marker{
            
             border:1px solid gray; 
           
        } */
        /* .title_value{
             border-left:1px solid lightgray; 
        } */
        hr.sub_hr{
            border-top:1px solid lightgray;
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
