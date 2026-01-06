@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Just Bill Info',[['title' => 'Just Bill']] ) ;

@endphp
<style>
    .detail_info{
        border:1px solid lightgray;
        padding:0 2px;
        color:blue;
    }
    .detail_info:hover{
        color:black;
    }
    ul.bill_info{
        list-style:none;
        padding:0;
    }
    ul.bill_info >li>span:before{
        content:": ";
        font-weight:bold;
    }
</style>
<x-page-component :data=$data />
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title"><x-back-button />  Just Bill Info</h3>
                    <!-- <a href="" style="float:right;" class="btn btn-sm bg-light text-dark"><li class="fa fa-edit"></li></a> -->
                    </div>
                </div>
                @if(!empty($justbill))
                <div class="card-body bg-white">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="bill_info">
                                <li  class="row">
                                    <b class="col-6">Customer Name</b>
                                    <span class="col-6">{{ $justbill->custo_name }}</span>
                                </li>
                                <li  class="row">
                                    <b class="col-6">Customer Mobile</b>
                                    <span class="col-6">{{ $justbill->custo_mobile }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4 d-md">

                        </div>
                        <div class="col-md-4">
                            <ul class="bill_info">
                                <li class="row">
                                    <b class="col-6">Bill Number</b>
                                    <span class="col-6">{{ $justbill->bill_no }}</span></li>
                                <li class="row">
                                    <b class="col-6">Bill Date Mobile</b>
                                    <span class="col-6">{{ date("d-m-Y",strtotime($justbill->bill_date)) }}</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" class="table-responsive">
                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr class="bg-dark">
                                        <th>S.N.</th>
                                        <th>ITEM</th>
                                        <th>Quantity</th>
                                        <th>RATE</th>
                                        <th>MAKING</th>
                                        <th>SUM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $items = ($justbill->items->count()>0)?true:false;
                                        $sn = 1;
                                        $cost_sum = 0;
                                        $make_sum = 0;
                                        $total_sum = 0;
                                    @endphp
                                    @if($items)
                                        @foreach($justbill->items as $ik=>$item)
                                        <tr>
                                            <td>
                                                {{ $sn++ }}
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td class="text-right">
                                                {{ $item->quant }} Gms.
                                            </td>
                                            <td class="text-right">
                                                {{ $item->rate }} Rs.
                                            </td>
                                            <td class="text-right">
                                                {{ $item->charge }} Rs.
                                            </td>
                                            <td class="text-right">
                                                {{ $item->sum }} Rs.
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else 
                                        <tr>
                                            <td colspan="6" class="text-danger text-center">No Items !</td>
                                        </tr>
                                    @endif
                                </tbody>
                                @if($items)
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-right">
                                            <b>SUB TOTAL</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $justbill->sub }} Rs.</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">

                                            <b>GST%</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $justbill->gst }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">
                                            <b>DIS%</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ ($justbill->discount!=0)?$justbill->discount:"NA" }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">
                                            <b>TOTAL</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $justbill->total }} Rs.</b>
                                        </td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>      
                </div>
                <div class="card-footer">
                </div>
                @else 
                    <div class="col-12 text-danger text-center">No Sell Bill !</div>
                @endif
            </div>
        </div>
    </div>
</section>
<div class="modal" tabindex="-1" role="dialog" id="info_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="info_head"></h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0" id="info_body">
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).on('click','.view_custo,.view_pays',function(e){
        e.preventDefault();
        const head = $(this).data('head');
        $("#info_head").empty().text(head);
        $('#info_body').empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></p>');
        $('#info_body').load($(this).attr('href'));
        $("#info_modal").modal('show');
    });
</script>
@endsection