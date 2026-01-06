  @extends('layouts.vendors.app')

  @section('content')

    <style>
        ul.stock_prop{
            list-style:none;
            padding:0;
            margin-bottom:2px;
        }
        ul.stock_prop > li >hr{
            border-bottom:1px solid lightgray;
            margin:0;
        }
        .stock_block{
            border:1px solid gray;
        }
        .prop_separate{
            margin:1px;
            border-bottom:1px solid gray;
        }
        a.stock_anchor:hover > div.stock_block,aa.stock_anchor:hover > div.stock_block>h6 {
            box-shadow:1px 2px 3px gray;
            font-size:105%;
            background:white;
            /* padding:5px!important; */
        }
    </style>
@php 
	$anchor = ['<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> New</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>'];
	$data = new_component_array('newbreadcrumb',"Stock Dashboard") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row">
                <!-- left column -->
                <div class="col-md-6 p-0 m-auto">
                    <!-- general form elements -->
                    <div class="card card-primary" style="box-shadow: 1px 2px 3px lightgray;background:#ff480005;">
                        <h6 class="card-title text-dark text-center">Title</h6>
                        <hr style="border-bottom: 1px solid lightgray;" class="m-1 p-0">
                        <div class="card-body">
                            <div class="col-12 p-0">
                                <div class="row">
                                    <a href="{{ route('stock.new.inventory','stock=gold') }}" class="col-md-6 col-12 text-center p-1 stock_anchor" >
                                        <div class="col-12 stock_block p-1">
                                            <h6>GOLD</h6>
                                            <hr class="m-0 prop_separate">
                                            @php 
                                                $g_gross = $gold->gross;
                                                $g_net = $gold->net;
                                                $g_fine = $gold->fine;
                                            @endphp
                                            <ul class="row p-0 stock_prop w-100 m-auto">
                                                <li class="col-4 p-1 m-auto">
                                                    Gross
                                                    <hr>
                                                    {{ $g_gross??0 }}gm
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    Net
                                                    <hr>
                                                    {{ $g_net??0 }}gm
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    Fine
                                                    <hr>
                                                   {{ $g_fine??0 }}gm
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                    <a href="{{ route('stock.new.inventory','stock=silver') }}" class="col-md-6 col-12 text-center p-1 stock_anchor">
                                        <div class="col-12 stock_block p-1">
                                            <h6>SILVER</h6>
                                            <hr class="m-0 prop_separate">
                                            @php 
                                                $s_gross = $silver->gross;
                                                $s_net = $silver->net;
                                                $s_fine = $silver->fine;
                                            @endphp
                                            <ul class="row p-0 stock_prop m-auto w-100">
                                                <li class="col-4 p-1 m-auto">
                                                    Gross
                                                    <hr>
                                                    {{ $s_gross??0 }}gm
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    Net
                                                    <hr>
                                                    {{ $s_net??0 }}gm
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    Fine
                                                    <hr>
                                                    {{ $s_fine??0 }}gm
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                    <a href="{{ route('stock.new.inventory','stock=atificial') }}" class="col-md-6 col-12 text-center p-1 stock_anchor">
                                        <div class="col-12 stock_block p-1">
                                            <h6>ARTIFICIAL</h6>
                                            <hr class="m-0 prop_separate">
                                            <ul class="row p-0 stock_prop m-auto w-100">
                                                <li class="col-4 p-1 m-auto">
                                                    COUNT
                                                    <hr>
                                                    {{ $artificial->count??0 }}
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    COST
                                                    <hr>
                                                    {{ $artificial->rate??0 }} Rs.
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                    <a href="{{ route('stock.new.inventory','stock=stone') }}" class="col-md-6 col-12 text-center p-1 stock_anchor">
                                        <div class="col-12 stock_block p-1">
                                            <h6>STONE</h6>
                                            <hr class="m-0 prop_separate">
                                            <ul class="row p-0 stock_prop m-auto w-100">
                                                <li class="col-4 p-1 m-auto">
                                                    COUNT
                                                    <hr>
                                                    {{ $stone->count??0 }}
                                                </li>
                                                <li  class="col-4 p-1 m-auto">
                                                    WEIGHT
                                                    <hr>
                                                    {{ $stone->net??0 }} gm.
                                                </li>
                                            </ul>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection('content')
        