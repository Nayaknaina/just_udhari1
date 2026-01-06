@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Sell Bill Info',[['title' => 'Sell Bill']] ) ;

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
                    <h3 class="card-title"><x-back-button />  Sell Bill Info</h3>
                    <a href="{{ route('sells.edit',$sell->id) }}" style="float:right;" class="btn btn-sm bg-light text-dark"><li class="fa fa-edit"></li></a>
                    </div>
                </div>
                @if(!empty($sell))
                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="bill_info">
                                <li  class="row">
                                    <b class="col-6">Customer Name</b>
                                    <span class="col-6">{{ $sell->custo_name }}</span>
                                </li>
                                <li  class="row">
                                    <b class="col-6">Customer Mobile</b>
                                    <span class="col-6"><a href="{{ route('sells.info',['custo'=>$sell->customer->id]) }}" class="view_custo detail_info" data-head="Payment Detail">{{ $sell->custo_mobile }}</a></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4 d-md">

                        </div>
                        <div class="col-md-4">
                            <ul class="bill_info">
                                <li class="row">
                                    <b class="col-6">Bill Number</b>
                                    <span class="col-6">{{ $sell->bill_no }}</span></li>
                                <li class="row">
                                    <b class="col-6">Bill Date Mobile</b>
                                    <span class="col-6">{{ date("d-m-Y",strtotime($sell->bill_date)) }}</span></li>
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
                                        <th>COST</th>
                                        <th>MAKING</th>
                                        <th>SUM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $items = ($sell->items->count()>0)?true:false;
                                        $sn = 1;
                                        $cost_sum = 0;
                                        $make_sum = 0;
                                        $total_sum = 0;
                                    @endphp
                                    @if($items)
                                        @foreach($sell->items as $ik=>$item)
                                        <tr>
                                            <td>
                                                {{ $sn++ }}
                                            </td>
                                            <td>
                                                {{ $item->stock->product_name }}
                                            </td>
                                            <td class="text-right">
                                                @if($item->stock->stock_type=="other")
                                                {{ $item->item_weight }} Gms.
                                                @else 
                                                {{ $item->item_quantity }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $item->item_rate }} Rs.
                                            </td>
                                            <td class="text-right">
                                                {{ $item->item_cost }} Rs.
                                                @php $cost_sum+=$item->item_cost @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $item->labour_charge }} Rs.
                                                @php $make_sum+=$item->labour_charge @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $item->total_amount }} Rs.
                                                @php $total_sum+=$item->total_amount @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="text-right"><b>SUM</b></td>
                                            <td class="text-right"><b>{{ $cost_sum }} Rs.</b></td>
                                            <td class="text-right"><b>{{ $make_sum }} Rs.</b></td>
                                            <td class="text-right"><b>{{ $total_sum }} Rs.</b></td>
                                        </tr>
                                    @else 
                                        <tr>
                                            <td colspan="6" class="text-danger text-center">No Items !</td>
                                        </tr>
                                    @endif
                                </tbody>
                                @if($items)
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>SUB TOTAL</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $sell->sub_total }} Rs.</b>
                                        </td>
                                    </tr>
                                    @if($sell->gst_apply==1)
                                    @php 
                                        $gst = json_decode($sell->gst,true);
                                    @endphp
                                    <tr>
                                        <td colspan="6" class="text-right">

                                            <b>GST({{ $gst['val'] }})%</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $gst['amnt'] }}</b>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>DIS%</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ ($sell->discount!=0)?$sell->discount:"NA" }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>TOTAL</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $sell->total }} Rs.</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>PAID</b>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('sells.info',['pay'=>$sell->id]) }}" class="view_pays detail_info" data-head="Payment Detail"><b>{{ $sell->payment }} Rs.</b></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right">
                                            <b>REMAINS</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ $sell->remains }} Rs.</b>
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