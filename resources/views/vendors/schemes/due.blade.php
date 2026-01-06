@extends('layouts.vendors.app')

@section('content')

@php

//$data = component_array('breadcrumb' , 'Schemes Due',[['title' => 'Schemes Due']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"All Scheme Dues") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 mb-2">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title text-secondary">Scheme Wise</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($scheme_data as $key=>$scheme)
                                    
                                    <div class="col-md-4">
                                        <div class="card mb-2 group_block">
                                        <a href="{{ route("shopscheme.enrollscheme",$scheme['id']) }}" class="overlay_anchor"><li class="fa fa-eye"></li></a>
                                            <div class="card-body row pt-0 pb-0">
                                                <div class="col-12 p-0">
                                                    <div class="title_block">
                                                        <h5>{{ $scheme['head'] }}</h5>
                                                        <hr class="m-0 col-12 sub_hr p-0">
                                                        <h6>{{ $scheme['sub'] }}</h6>
                                                    </div>
                                                </div>
                                                <hr class="m-0 col-12 sub_hr p-0">
                                                <div class="col-12 border-1 p-0 title_block_value">
                                                    <ul class="title_value m-0">
                                                        <li class="text-primary total"><b>TOTAL</b><span>{{ $scheme['payable']??'0'}} Rs.</span></li>
                                                        <li class="text-success received"><b>RECEIVED</b><span>{{ $scheme['received']??'0'}} Rs.</span></li>
														<li class="text-info tokens"><b>TOKEN</b><span>{{ ($scheme['token'])??'0'}} Rs.</span></li>
                                                        <li class="text-danger remains"><b>DUE</b><span>{{ ($scheme['payable']-$scheme['received'])??'0'}} Rs.</span></li>
                                                    </ul>
                                                    <h6 class="bg-danger text-center ml-1">BONUS : -{{ $scheme['bonus']??'0' }} Rs.</h6>
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
                    <div class="card card-default">
                        <div class="card-header">
                             <h3 class="card-title text-secondary">GROUP WISE</h3>
                        </div>
                        @foreach($group_data as $scheme=>$groups)
                            <div class="card-body">
                                <h4 >{{ $scheme }}</h4>

                                <div class="row">
                                    @foreach($groups as $gk=>$group)
                                    <div class="col-md-3">
                                        <div class="card card-default">
                                            <div class="card-header p-2">
                                                <h3 class="card-title col-12 p-0" style="color:inherit;">{{ $group['name'] }} <a href="{{ route("shopscheme.enrollgroup",[$group['id'],'data'=>'all']) }}" class="group_detail_show" style="float:right"><li class="fa fa-eye"></li></a></h3>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="col-12 border-1 p-0 title_block_value">
                                                    <ul class="title_value m-0 pl-4">
                                                        <li class="text-primary total">
                                                            <b>TOTAL</b>
                                                            <span>{{ $group['payable']??'0' }} Rs.</span>
                                                        </li>
                                                        <li class="text-success received">
                                                            <b>RECEIVED</b>
                                                            <span>{{ $group['received']??'0' }} Rs.</span>
                                                        </li>
														<li class="text-info tokens">
                                                            <b>TOKEN</b>
                                                            <span>{{ $group['token']??'0' }} Rs.</span>
                                                        </li>
                                                        <li class="text-danger remains">
                                                            <b>DUE</b>
                                                            <span>{{ ($group['payable']-$group['received'])??'0' }} Rs.</span>
                                                        </li>
                                                        
                                                    </ul>
                                                    <h6 class="border-danger text-center ml-1"><b>BONUS : </b> -{{ $group['bonus']??'0' }} Rs.</h6>
                                                </div>
                                                @if($group['start'])
                                                <hr class="m-0 col-12 sub_hr p-0">
                                                <div class="col-12 border-1 p-0 title_block_value">
                                                    <h6 class="m-2"><b>{{ date('M',strtotime('now'))}}.</b> Month 
													@if($group['fix_date']=='1')
													<a href="{{ route("shopscheme.enrollgroup",$group['id']) }}" class="group_detail_show" style="float:right"><li class="fa fa-eye"></li></a>
													@endif
													</h6>
                                                    <hr class="m-0 col-12 sub_hr p-0">
                                                    <ul class="title_value m-0 pl-4">
                                                        <li class="text-primary total">
                                                            <b>TOTAL</b>
                                                            <span>{{ $group['month_payable']??'0' }} Rs.</span>
                                                        </li>
                                                        <li class="text-success received">
                                                            <b>RECEIVED</b>
                                                            <span>{{ $group['month_received']??'0' }} Rs.</span>
                                                        </li>
                                                        <li class="text-danger remains">
                                                            <b>DUE</b>
                                                            <span>{{ ($group['month_payable']-$group['month_received'])??'0' }} Rs.</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @else 
                                                <span class="badge badge-info w-100 my-2">Scheme Not Started Yet !</span>
                                                @endif
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
        .border-danger{
            border:1px solid red;
            color:red;
            padding:2px;
            margin:2px;
        }
        .title_block{
            margin: 0;
            position: inherit;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
        /* .title_block_value{
            border-left:1px solid lightgray;
            } */
            /* .title_value > .li::marker{
                content:"";
                font-weight:bold;
                padding:2px;
                border:1px solid gray;
                } */
        .title_value{
            list-style:none;
        }
        .title_value > li > span{
            float:right;
            width:50%;
            position: relative;
        }
        .title_value > li > span:before{
            content:": ";
        }
        /* .title_value > .total::marker{
            content:"TOTAL  ";

        }
        .title_value > .received::marker{
            content:"RECEIVE  ";
        }
        .title_value > .remains::marker{
            content:"REMAINS ";
        }
        .title_value > .bonus::marker{
            content:"BONUS ";
            color:red;
        } */
        hr.sub_hr{
            border-top:1px solid lightgray;
        }
        .group_detail_show:hover{
            border:1px solid blue;
            color:blue;
        }
        .group_detail_show{
            border:1px dotted blue;
            padding:0 2px 0 2px;
        }
    </style>
@endsection

  @section('javascript')


  <script>
    $('.group_block').hover(function(){
        const a = $(this).find('a.overlay_anchor');
        //alert($(a).html());
        if($(this).prev('.overlay_anchor').hasClass('anchor_visible')){
            $(this).prev('.overlay_anchor').addClass('anchor_visible');
        }else{
            $(this).prev('.overlay_anchor').removeClass('anchor_visible');

        }
    });
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
