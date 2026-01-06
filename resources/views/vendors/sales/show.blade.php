@extends('layouts.vendors.app')

@section('content')

@php

//$data = component_array('breadcrumb' , 'Sell Bill Info',[['title' => 'Sell Bill']] ) ;

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
{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('sells.edit',$sell->id).'" style="float:right;" class="btn btn-sm bg-light border-dark text-dark"><i class="fa fa-edit"></i> Edit</a>','<a href="'.route('sells.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Sell Bills"=>route('sells.index')];
$data = new_component_array('newbreadcrumb',"Sell Bill Info",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
				{{--<div class="card-header">
                    <h3 class="card-title"><x-back-button />  Sell Bill Info</h3>
                    <a href="{{ route('sells.edit',$sell->id) }}" style="float:right;" class="btn btn-sm bg-light text-dark"><li class="fa fa-edit"></li></a>
                    </div>--}}
                </div>
                @if(!empty($sell))
                <div class="card-body bg-white pt-0">
                    <div class="row">
                        <div class="col-12  p-0">
                            @include("vendors.sales.showinvoice")
                        </div>
                        <div class="col-12 text-center">
                            <a href="{{ url("vendors/sells/preview/{$sell->id}") }}" id="print_receipt" class="btn btn-sm btn-secondary">Print Prieview</a>
                        </div>
                    </div>      
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
    $(document).ready(function(){

        $('#print_receipt').click(function(e){
            window.open(this.href,'newWindow','width=800,height=600');
            return false;
        });

    });
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