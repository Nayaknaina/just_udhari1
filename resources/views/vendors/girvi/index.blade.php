@extends('layouts.vendors.app')
@section('css')
<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
<style>
    #tab_buttons,#record_tab_buttons{
        list-style:none;
    }
    #tab_buttons>li{
            flex: 1; 
            height:auto;  
    }
    #record_tab_buttons>li>a{
        border:1px solid #ff6e26;
        background:linear-gradient(to bottom,#f0f0f0,#f7f7f7,#fff,#f7f7f7,#f0f0f0);
    }
    #record_tab_buttons>li>a:hover{
        border:1px solid #ff6e26;
        color:#ff6e26;
        font-weight:bold;
    }
    
    .record-tab-btn.active{
        border: 1px solid orange;
        border-top:3px solid #ff6e26!important;
        border-bottom:unset!important;
        background: white!important;
        box-shadow: 1px 2px 3px gray;
        color:#ff6e26;
        font-weight:bold;
    }
    .btn.tab-btn{
        border-radius:10px 10px 0 0;
        border:1px solid lightgray;
        border-bottom:1px solid #ff6e26!important;
    }
    .btn.tab-btn:hover{
        color:#ff6e26;
    }
    .btn.tab-btn:not(.active){
        box-shadow:unset;
    }
    .tab-btn.active{
        background:white;
        margin-bottom:2px;
        border-bottom:unset!important;
        border-top:3px solid #ff6e26;
        border-left:1px solid #ff6e26;
        border-right:1px solid #ff6e26;
        color:#ff6e26;
        font-weight:bold;
    }
    /*.dropdown.sub_drop_over {
        position: absolute;
        top: 0;
        right: 0;
    }
    .dropdown.sub_drop_over>.dropdown-menu {
        width: auto;
        min-width: unset;
    }*/
    .tab-btn {
        padding:5px 5px;
        font-size:90%;
    }
    #new_girvy{
        border-radius:10px;
        border:1px solid #f95600;
        border-bottom:3px solid #f95600;
    }
    .input-group{
        gap:unset;
    }
    #customerlist {
        list-style-type: none;
        padding: 0;
        margin: 0;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        width: 200px;
        display: none;
        position: absolute;
        z-index: 100;
        max-height:50vh;
        height:auto;
        min-width:auto;
        overflow-x:scroll;
        box-shadow: 1px 2px 3px gray
    }
    #customerlist.active{
        display:block;
    }
    #customerlist li {
        padding: 10px;
        cursor: pointer;
        text-wrap:wrap;
    }
    #customerlist li.hover,#customerlist li:hover {
        background-color: #ddd;
    }
</style>
@endsection
@section('content')
@php $data = new_component_array('breadcrumb',"Girvi Details") @endphp
<x-new-bread-crumb :data=$data />
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-1">
                        
                        <form class="row" action="{{ route('girvi.store') }}"role="" id="girvi_form" class="form-inline" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-12 p-0">
                                            <div class="card mb-2" id="new_girvy">
                                                <div class="card-header p-1">
                                                    
                                                    <ul id="tab_buttons" class="d-flex px-0 text-center">
                                                        <li class="p-0">
                                                            <label for="recieved-tab" class="btn tab-btn active w-100 m-0" data-target="#recieved-tab" id="recieved-tab">
                                                                <input type="radio"  name="operation" value="receive" checked style="display:none;"> Girvi Received
                                                            </label>
                                                        </li>
                                                        <li class="p-0">
                                                            <label for="interest-tab" class="btn tab-btn w-100 m-0" data-target="#interest-tab" id="interest-tab">
                                                                <input type="radio"   name="operation" value="interest" style="display:none;"> Pay Interest
                                                            </label>
                                                        </li>
                                                        <li class="p-0">
                                                            <label for="return-tab" class="btn tab-btn w-100 m-0" data-target="#return-tab" id="return-tab" >
                                                                <input type="radio" name="operation" value="return"  style="display:none;"> Girvi Return
                                                            </label>
                                                        </li>
                                                    </ul>
                                                    <div class="search-pill-container shadow-sm mb-2">
                                                        <style>
                                                            .search-pill-container {
                                                                background: #fff;
                                                                border-radius: 50px;
                                                                padding: 4px;
                                                                border: 1px solid #e0e0e0;
                                                                display: flex;
                                                                align-items: center;
                                                                transition: all 0.3s ease;
                                                                position: relative; /* For dropdown positioning */
                                                            }
                                                            .search-pill-container:focus-within {
                                                                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
                                                                border-color: #ff6e26;
                                                            }
                                                            .search-input-custom {
                                                                border: none;
                                                                background: transparent;
                                                                flex-grow: 1;
                                                                padding: 8px 15px;
                                                                font-weight: 600;
                                                                color: #333;
                                                                font-size: 0.9rem;
                                                            }
                                                            .search-input-custom::placeholder {
                                                                color: #aaa;
                                                                font-weight: 500;
                                                            }
                                                            .search-input-custom:focus {
                                                                outline: none;
                                                                box-shadow: none;
                                                            }
                                                            .btn-circle-custom {
                                                                width: 34px;
                                                                height: 34px;
                                                                border-radius: 50%;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                padding: 0;
                                                                transition: transform 0.2s;
                                                                border: none;
                                                            }
                                                            .btn-circle-custom:hover {
                                                                transform: scale(1.1);
                                                            }
                                                            
                                                            /* Custom Lite Scrollbar */
                                                            #customerlist::-webkit-scrollbar {
                                                                width: 5px;
                                                            }
                                                            #customerlist::-webkit-scrollbar-track {
                                                                background: transparent; 
                                                            }
                                                            #customerlist::-webkit-scrollbar-thumb {
                                                                background: #e0e0e0; 
                                                                border-radius: 10px;
                                                            }
                                                            #customerlist::-webkit-scrollbar-thumb:hover {
                                                                background: #bdbdbd; 
                                                            }
                                                            #customerlist {
                                                                scrollbar-width: thin;
                                                                scrollbar-color: #e0e0e0 transparent;
                                                            }
                                                        </style>
                                                        
                                                        <input type="hidden" name="custo" value="" id="custo">
                                                        <input type="hidden" name="type" value="" id="type">
                                                        <input type="hidden" name="girvi" value="" id="girvi">
                                                        
                                                        <input type="text" name="name" id="name" class="search-input-custom" placeholder="Search Name / Mobile / Girvi-ID..." oninput="getcustomer($(this))" autocomplete="off">
                                                        
                                                        <div class="d-flex align-items-center pr-1">
                                                            <a href="javascript:void(null);" class="btn btn-light text-secondary btn-circle-custom mr-1 border" id="custo_ladger" title="Ledger" style="background: #f8f9fa;">
                                                                <i class="fa fa-book"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-gradient-primary btn-circle-custom text-white shadow-sm" data-toggle="modal" data-target="#custo_modal" title="Add New Customer">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        
                                                        <ul id="customerlist" class="w-100" style="top: 100% !important; margin-top: 12px; left: 0; border-radius: 12px; overflow-y: auto; max-height: 220px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: none; z-index: 9999; background: #fff; padding: 0;"></ul>
                                                    </div>
                                                </div>
                                                <div class="card-body tab-content p-1" id="myTabContent">
                                                    <div class="fade-in tab-panel" id="recieved-tab-pane" role="tabpanel" aria-labelledby="recieved-tab" tabindex="0" >
                                                    <!-- {{--@include('vendors.girvi.girviformpart.receive')--}} -->
                                                    @include('vendors.girvi.girviformpart.receive_new')
                                                    {{-- @include('vendors.girvi.girviformpart.receive_OLD') --}}
                                                    </div>
                                                    <div class="fade-in tab-panel" id="interest-tab-pane" role="tabpanel" aria-labelledby="interest-tab" tabindex="0" style="display:none;">
                                                        @include('vendors.girvi.girviformpart.payment_OLD')
                                                       <!-- {{--@include('vendors.girvi.girviformpart.payment')--}} -->
                                                    </div>
                                                    <div class="fade-in tab-panel" id="return-tab-pane" role="tabpanel" aria-labelledby="return-tab" tabindex="0" style="display:none;">
                                                        @include('vendors.girvi.girviformpart.return')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card">
                                        <div class="card-header p-1" >
                                            <ul id="record_tab_buttons" class="d-flex px-0 m-0 text-center" >
                                                <li class="p-0">
                                                    <a data-target="new_girvi" href="javascript:void(null);" class="btn btn-sm record-tab-btn active w-100" id="new_girvi-tab"  type="button" >Current</a>
                                                </li>
                                                <li class="p-0">
                                                    <a data-target="old_girvi" href="javascript:void(null);" class="btn btn-sm record-tab-btn  w-100" id="old_girvi-tab"  type="button" >Older</a>
                                                </li>
                                                <li id="list_button" style="margin-left:auto;">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-list"></i> List <i class="fa fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Current List</a>
                                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Old List</a>
                                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Detail List</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body tab-content p-0" id="myTabContent">
                                            @include('vendors.girvi.girviformpart.record')
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                
            </div>
            <script>
                
            </script>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@include('vendors.commonpages.newcustomerwithcategory')
@include('vendors.commonpages.itemcategory')
@endsection

@section('javascript')

    @include('layouts.common.placeholdertolabel')
    @include('layouts.vendors.js.passwork-popup')
    @include('vendors.girvi.javascripts.common')
    @include('vendors.girvi.javascripts.receive_new')
    @include('vendors.girvi.javascripts.payinterest')
@endsection