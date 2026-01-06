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
    #item_body >tr>td{
        border-top:none;
        border-bottom:none;
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
                        <div class="col-12  p-0">
                            @include("vendors.justbills.showinvoice")
                        </div>
                        <div class="col-12 text-center">
                            <a href="{{ url("vendors/bills/preview/{$justbill->id}") }}" id="print_receipt" class="btn btn-sm btn-secondary">Print Prieview</a>
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

@endsection
@section('javascript')
    <script>
    $(document).ready(function(){

        $('#print_receipt').click(function(e){
            window.open(this.href,'newWindow','width=800,height=600');
            return false;
        });

    });
    </script>
@endsection
