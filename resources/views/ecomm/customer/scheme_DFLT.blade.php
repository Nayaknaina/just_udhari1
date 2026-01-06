@extends('ecomm.site')
@section('title', "My Dashboard")
@section('content')
@php
@$$activemenu = 'active';
//dd($enrollschemes);
@endphp
<style>
body {
    font-family: Arial, sans-serif;
}

#modal {
    display: none; /* Hidden by default */
    /* position: fixed; */
    position: absolute;
    top: 0;
    right: 0!important;
    left:unset;
    width: 100%;
    /* height: 100%; */
    height:unset!important;
    background-color: white;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease!important;
    transform: translateX(100%); /* Start off screen */
}

#modal.show {
    display: block;
    transform: translateX(0)!important; /* Slide in */
}
.model-body{
    height:auto;
    max-height:85vh;
    overflow:auto;
}
.modal-content {
    padding: 20px;
}
.close {
    cursor: pointer;
    float: right;
    font-size: 24px;
}
</style>
<!-- Page Header Start -->
<div class="container-fluid bg-secondary breadcrumb-section">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2">
        <div class="customer_page_head col-md-4 row text-center px-1">
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase mb-3 " style="margin:auto;">My Schemes</h1>
            <div class="head_right col-md-6 border_div"></div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-md-3 bt-primary d-lg-block d-none dashboard_lg_control">
            @include('ecomm.customer.sidebar')
        </div>
        <div class="col-md-9 col-12 customer_info_block">
			@if($errors->any())
			<div class="alert alert-danger text-center">{{$errors->first()}}</div>
			@endif
            <div class="table-responsive">
                <table class="table table-bordered table-default">
                    <thead>
                        <tr class="bg-secondary">
                            <th>SN.</th>
                            <th>SCHEME</th>
                            <th class="text-center">VALIDITY</th>
                            <th class="text-center">START</th>
                            <th class="text-center">END</th>
                            <th class="text-center">PAID</th>
                            <th class="text-center">BONUS</th>
                            <th class="text-center">
                                <li class="fa fa-list"></li>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($enrollschemes->toArray()))
                        @foreach($enrollschemes as $ei=>$enroll)
                        @php
                            //dd($enroll);
                            $schemes = $enroll['schemes'];
                        @endphp
                        <tr>
                            <td>{{ $ei+1 }}</td>
                            <td>
                                <b>{{ @$schemes->scheme_head }}</b>
                                <hr class="m-1 p-0">
                                {{ $schemes->scheme_sub_head }}
                            </td>
                            <td>
                                {{ $schemes->scheme_validity }} Month
                            </td>
                            <td>
                                @if($schemes->scheme_date_fix=='1')
                                {{date('d-m-Y',strtotime($schemes->scheme_date)) }}
                                @else
                                <i class="text-warning">Enrolled</i>
                                @endif
                            </td>
                            <td>
                                @if($schemes->scheme_date_fix=='1')
                                {{ date('d-m-Y',strtotime("{$schemes->scheme_date}+{$schemes->scheme_validity} Month")) }}
                                @else
                                <i class="text-warning">Enrolled</i>
                                @endif

                            </td>
                            <td  class="text-success">
                                {{ $enroll['total'][$schemes->id]['emi'] }} Rs.
                            </td>
                            <td class="text-info">
                                {{ $enroll['total'][$schemes->id]['bonus'] }} Rs.
                            </td>
                            @php
                            $target_id = str_replace(" ","_",$schemes->scheme_head);
                            @endphp
                            <td>
                                <a href="{{ url("{$ecommbaseurl}schemedetail/{$schemes->id}") }}" data-target="{{ $target_id }}" class="show_group btn btn-sm btn-outline-info">
                                    <li class="fa fa-angle-down"></li>
                                </a>
                            </td>
                        </tr>
                        <tr id="{{ $target_id }}" style="display:none;" class="table-separated">

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center text-danger" colspan="8">No Schemes !</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="modal" class="modal">
                <div class="modal-content p-0">
                    <div class="model-header p-2" style="border-bottom:1px dashed lightgray;">
                        <!-- <span id="closeModal" class="close">&times;</span> -->
                        <h4>Payment Detail <small><button class="close text-danger" id="close_txn_model" style="font-size:inherit;">&cross;</button></small></h4>
                    </div>
                    <div class="model-body p-2" id="scheme_txn_detail">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    tr.table-separated>td,
    tr.table-separated>th {
        border-bottom: 2px solid lightgray !important;
    }
</style>
@endsection
@section('javascript')
<script>
    $('.show_group').click(function(e) {
        e.preventDefault();
        const path = $(this).attr('href');
        const target = $(this).data('target');
        $("#" + target).empty().append('<td class="text-center text-danger" colspan="8" style="background:#00000045;"><span style="color:white;"><li class="fa fa-spinner fa-spin"> </li> Loding Content..</span></td>');
        var get_data = ($("#" + target).css('display') == 'none') ? true : false;
        $("#" + target).toggle();
        if (get_data) {
            $("#" + target).load(path);
        }
    });
</script>
<script>
const modal = $('#modal');
const openModalButton = $('#openModal');
const closeModalButton = $('#closeModal');

// Open modal
$(document).on('click','.scheme_txn_btn',function(e){
    e.preventDefault();
    $("#scheme_txn_detail").empty().append('<p class="text-center"><span style="color:orange;" ><i class="fa fa-spinner fa-spin"> </i> Loding TXNs..</span></p>')
    modal.addClass('show');
    $("#scheme_txn_detail").load($(this).attr('href'));
});

// Close modal
$('#close_txn_model').click(function(){
    modal.removeClass('show');
});

// Close modal when clicking outside of the modal content
$(document).click(function(e){
    if (e.target === modal) {
        modal.removeClass('show');
    }
});
</script>
@endsection
