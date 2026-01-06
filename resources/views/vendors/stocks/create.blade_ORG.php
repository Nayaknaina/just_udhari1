@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

@endphp

<x-page-component :data=$data />
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><x-back-button /> New Placement </h3>
                </div>
                <form id = "submitForm" method="POST" action="{{ route('purchases.store')}}" class = "myForm" enctype="multipart/form-data">
                    <div class="card-body p-2">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="items">Selected Item/Products</label>
                                    <input type="text" name="item" class="form-control" placeholder="Search Product Name" value="" oninput="changeEntries()">
                                    <div class="w-100 border-secondary table-responsive">
                                        <table class="table table-stripped table-bordered">
                                            <tr class="bg-info">
                                                <th style="width:90%;">NAME</th>
                                                <th class=" text-center">&check;</th>
                                            </tr>
                                        </table>
                                        <tbody id="to_select_items">

                                        </tbody>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-12 text-danger">
                                    <b><u>NOTE</u> : </b>
                                    <small class="help-text">Enter New Name or Choose existing withh "&#x27A5;"</small>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="counter">Counter Name/label</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <a href="{{ route('stock.counters') }}" class="btn btn-outline-secondary m-0 place_resource"  type="button" style="padding:0 5px;">
                                                    <span style="font-size:180%;">&#x27A5;</span>
                                                </a>
                                            </div>
                                            <input type="text" class="form-control" placeholder="New Counter Name" id="counter" class="counter">
                                        </div>
                                        
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="box">Box Name/label</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <a href="{{ route('stock.boxes') }}" class="btn btn-outline-secondary m-0 place_resource" type="button" style="padding:0 5px;">
                                                    <span style="font-size:180%;">&#x27A5;</span>
                                                </a>
                                            </div>
                                            <input type="text" class="form-control" placeholder="New Box Name" id="box" class="box">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="items">Item/Products List</label>
                                        <div class="w-100 border-secondary table-responsive">
                                            <table class="table table-stripped table-bordered">
                                                <tr class="bg-light">
                                                    <th style="width:90%;">NAME</th>
                                                    <th class="text-centere">&check;</th>
                                                </tr>
                                            </table>
                                            <tbody id="selected_items">
                                            </tbody>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" name="do" class="btn btn-danger">Place</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
@section('javascript')
<script>
    $(document).ready(function(e){
        $('.place_resource').click(function(e){
            e.preventDefault();
            var ttrgt = $(this).data('target');
            //$("#place_modal_body").load($(this).attr('href'));
            $("#place_modal_body").empty().load($(this).attr('href'),"",function(){
                //$("#place_resource").modal();
            });
            $("#place_resource").modal();
        });

        $(document).find('.input_apply').click(function(e){
            var ttrgt = $(this).data('target');
            $("#"+ttrgt).val($(this).val());
        });
    });
</script>
@endsection