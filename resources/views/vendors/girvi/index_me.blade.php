@extends('layouts.vendors.app')
@section('css')
<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
<style>
    td>div>input.value{
        font-weight:bold;
        color:blue;
        text-align:center;
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
    select.is-invalid{
        border:1px solid red!important;
    }
</style>
<style>
    #tab_buttons{
        list-style:none;
        display:inline-flex;
    }
    #tab_buttons>li{
        flex:1;
        height:auto;  
    }
    .tab-btn {
        padding:5px 5px;
        font-size:90%;
    }
    .btn.tab-btn{
        border-radius:10px 10px 0 0;
        border:1px solid lightgray;
        border-bottom:1px solid #f95600!important;
    }
    .btn.tab-btn:hover{
        color:#f95600;
    }
    .btn.tab-btn:not(.active){
        box-shadow:unset;
    }
    .tab-btn.active{
        background:white;
        margin-bottom:2px;
        border-bottom:unset!important;
        border-top:3px solid #f95600;
        border-left:1px solid #f95600;
        border-right:1px solid #f95600;
        color:#f95600;
        font-weight:bold;
    }
    #item_table_head>tr>th{
        padding:2px;
        vertical-align: middle;
        text-transform: capitalize;
        border:1px solid lightgray;
        background:#e9e9e9;
    }
    #item_table_body>tr>td{
        padding:0;
        /* font-size:80%; */
    }
    #item_table_body>tr>td > div{
        margin:0;
    }
    #item_table_body>tr>td > div >input,
    #item_table_body>tr>td > div >label,
    #item_table_body>tr>td > div >select,
    #item_table_body>tr>td > div >textarea{
        padding:2px 5px;
        font-size:90%;
        align-content:center;
        line-height:1;
        min-height:30px;
    }
    .gm-inside{
        font-size: 90%;
        right:1px;
    }
    .section_head{
        position: relative;
        z-index: 0; 
    }
    .section_head:before{
        content:"";
        width:100%;
        z-index:0;
        position: absolute;
        left:0;
        border-top:1px solid lightgray;
        top:50%;
    }
    .section_head>b{
        border:1px solid lightgray;
        padding:2px 5px;
        border-radius:20px;
        position: relative;
        color:#f95600;
        background-image: linear-gradient(to bottom,lightgray,#ebebeb,white,#ebebeb,lightgray);
        z-index:1;
    }
</style>
<style>
    #pay_data_area > tr >td,#option_data_area > tr >td{
        font-size:90%;
    }
    .child_table,.child_table>thead>tr{
        box-shadow:1px 2px 3px gray inset;
        border:1px solid gray;
    }
    .child_table>thead>tr>th{
        padding:2px;
    }
    tbody.child_table_tbody >tr >td{
        font-size:85%;
        padding:2px;
    }
    .child_table_tr > td{
        padding:0;
    }
    tr.selected{
        background-color:#fff6ed;
    }
    tr.selected > td{
        border-top:1px dashed #fd5f00;
        border-bottom:1px dashed #fd5f00;
    }
    tr.success{
        background-color:#00800012;
    }
    #int_old_balance{
        position: relative;
    }
    #int_old_balance:before{
        content: "OLD";
        position: absolute;
        top: -15px;
        left: 5px;
        color: blue;
        font-weight: bold;
        background: white;
        border: 1px solid blue;
        padding: 0 5px;
        border-radius: 10px;
    }
</style>
@endsection
@section('content')
@php $data = new_component_array('breadcrumb',"Girvi Details") @endphp
<x-new-bread-crumb :data=$data />
    <section class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <form role="form" action="{{ route('girvi.store') }}" class="form-inline" id="girvi_form" autocomplete="off">
                        <div class="card card-light w-100" style="border-top:1px dashed #ff2300;border-left:1px solid #f7c0a5;border-right:1px solid #f7c0a5;border-bottom:2px solid #f7c0a5;">
                            <div class="card-header  py-0" >
                                <div class="row">
                                    <div class="col-md-6  pt-2">
                                        @csrf
                                        <div class="form-group input-group p-0 mb-1">
                                            <input type="hidden" name="custo" value="" id="custo">
                                            <input type="hidden" name="type" value="" id="type">
                                            <input type="hidden" name="girvi" value="" id="girvi">
                                            <input type="text" name="name" id="name" class="form-control myselect placeholdertolabel h-32px" placeholder="Name/Mobile/Girvi-Id" oninput="getcustomer($(this))" style="border-radius: 15px 0 0 15px;">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-primary m-0 h-32px" data-toggle="modal" data-target="#custo_modal" style="line-height:normal;border-radius:0 15px 15px 0;">
                                                <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                            <ul id="customerlist" class="w-auto"></ul>
                                        </div>
                                    </div>
                                    <div class="dropdown col-md-2 pt-2 text-center">
                                        
                                        <a href="javascript:void(null);" class="btn btn-outline-primary" style="line-height:normal;" id="custo_ladger">
                                            <i class="fa fa-book"></i>
                                        </a>

                                        <button class="btn btn-outline-primary dropdown-toggle m-0" type="button"  style="line-height:normal;border-radius:15px;"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-list"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Current List</a>
                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Old List</a>
                                            <a class="dropdown-item" href="{{ route('girvi.list') }}">Detail List</a>
                                        </div>
                                    </div>
                                    <div class="text-center p-0 pt-2 col-md-4" >
                                        <ul id="tab_buttons" class="px-0 m-0 w-100">
                                            <li class="p-0 w-auto">
                                                <label for="recieved-tab-radio" class="btn tab-btn active w-100 m-0" data-target="#recieved-tab" id="recieved-tab">
                                                    <input type="radio"  name="operation" value="receive" checked style="display:none;" id="recieved-tab-radio"> Girvi Receive
                                                </label>
                                            </li>
                                            <li class="p-0">
                                                <label for="interest-tab-radio" class="btn tab-btn w-100 m-0" data-target="#interest-tab" id="interest-tab">
                                                    <input type="radio"   name="operation" value="interest" style="display:none;" id="interest-tab-radio"> Pay/Return
                                                </label>
                                            </li>
                                            <li class="p-0">
                                                <label for="return-tab-radio" class="btn tab-btn w-100 m-0" data-target="#return-tab" id="return-tab" >
                                                    <input type="radio" name="operation" value="return"  style="display:none;" id="return-tab-radio"> Options
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 px-2" id="girvi_tab_container" >
                                <div class="fade-in tab-panel" id="recieved-tab-pane" role="tabpanel" aria-labelledby="recieved-tab" tabindex="0" >
                                    @include('vendors.girvi.girviformpart.receive')
                                </div>
                                <div class="fade-in tab-panel" id="interest-tab-pane" role="tabpanel" aria-labelledby="interest-tab" tabindex="0" style="display:none;">
                                    @include('vendors.girvi.girviformpart.payment')
                                </div>
                                <div class="fade-in tab-panel" id="return-tab-pane" role="tabpanel" aria-labelledby="return-tab" tabindex="0" style="display:none;">
                                    @include('vendors.girvi.girviformpart.options')
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="girvioperationmodal" tabindex="-1" role="dialog" aria-labelledby="girvioperationmodal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header p-1" style="background:#f5e5dc !important">
                <h5 class="modal-title text-primary" id="girvioperationmodalhead">Modal title</h5>
                <button type="button" class="close text-dan ger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="girvioperationmodalbody">
                ...
            </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@include('vendors.commonpages.newcustomerwithcategory')
@include('vendors.commonpages.itemcategory')
@endsection

@section('javascript')

    @include('layouts.common.placeholdertolabel')
    @include('layouts.vendors.js.passwork-popup')
    @include('vendors.girvi.javascripts.common')
    @include('vendors.girvi.javascripts.receive')
    @include('vendors.girvi.javascripts.options')
    @include('vendors.girvi.javascripts.payinterest')
@endsection