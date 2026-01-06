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
<div class="container-fluid  bg-secondary mb-5 breadcrumb-section p-0">
    <div class="d-flex flex-column align-items-center justify-content-center  px-2">
        <div class="customer_page_head col-md-4 row text-center px-1">
            <div class="head_left col-md-6 border_div"></div>
            <h1 class="font-weight-semi-bold text-uppercase my-2 " style="margin:auto;">My Schemes</h1>
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
                            <th>ENROLL</th>
                            <th>SCHEME</th>
                            <th class="text-center">VALIDITY</th>
                            <th class="text-center">PAYABLE</th>
                            <th class="text-center">PAID</th>
                            <th class="text-center">BONUS</th>
                            <th class="text-center">
                                <li class="fa fa-list"></li>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($enrollsgroups->count()>0)
                        @foreach($enrollsgroups as $ei=>$enroll)

                        @php  
                            $schemes = $enroll->schemes; 
                            $payable = $schemes->scheme_validity*$enroll->emi_amnt;
                            $paid = $enroll->schemetxn_sum->whereIn('action_taken',['A','U'])->sum('emi_amnt');
                            $bonus = $enroll->schemetxn_sum->whereIn('action_taken',['A','U'])->sum('bonus_amnt');
                            $url = url("{$ecommbaseurl}txnsdetail/{$enroll->id}");
                            $payurl = url("{$ecommbaseurl}emipay");
                        @endphp
                        <tr>
                            <td>{{ $ei+1 }}</td>
                            <td>
                                <b>NAME : </b>{{ $enroll->customer_name }}
                                <hr class="m-1 p-0">
                                <b>ID : </b>{{ $enroll->assign_id }}
                                <hr class="m-1 p-0">
                                <b>GROUP : </b>{{ @$enroll->groups->group_name }}
                            </td>
                            <td>
                                <b>{{ @$schemes->scheme_head }}</b>
                                <hr class="m-1 p-0">
                                {{ @$schemes->scheme_sub_head }}
                            </td>
                            <td class="text-center">
                                @php 
                                    $start_date = ($schemes->scheme_date_fix=='1')?$schemes->scheme_date:$enroll->entry_at;
                                    $datestart = date('d-m-Y',strtotime($start_date));
                                    $dateend = date("d-m-Y",strtotime("{$start_date}+{$schemes->scheme_validity} Month"))
                                @endphp
                                <ul style="padding:0;list-style:none;">
                                    <li>{{ $schemes->scheme_validity }} Month</li>
                                    <li><hr class="m-1 p-0"></li>
                                    <li><b>START : </b>  {{ $datestart }}</li>
                                    <li><hr class="m-1 p-0"></li>
                                    <li><b>END : </b> {{ $dateend }}</li>
                                </ul>
                            </td>
                            <td class="text-warning">
                                {{ $payable }} Rs
                            </td>
                            <td  class="text-success text-center">
                                {{ $paid }} Rs.
                            </td>
                            <td class="text-info">
                                {{ $bonus }} Rs.
                            </td>
                            @php
                            $target_id = str_replace(" ","_",$schemes->scheme_head);
                            @endphp
                            <td class="text-center">
                            <ul style="list-style:none;padding:0;">
								<li class="my-1 w-100">
										<a href="{{$url}}" class='scheme_txn_btn btn btn-sm btn-outline-primary' style="width: max-content;">
											<i class="fa fa-outdent"> Txns</i>
										</a>
								</li>
							{{--<li class="mx-1">
                                    <form action="{{ $payurl }}" method="post" >
                                        @csrf
                                        <input type="hidden" name="enroll" value="{{ $enroll->id }}">
                                        <button type="submit" name="pay" value="gateway" type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-rupee">?</i>
                                        </button>
                                    </form>
                                </li>--}}
								<li class="my-1">
                                    @if($gateways->count()==1)
                                    <form action="{{ url("{$ecommbaseurl}payemi") }}" method="post" class="emi_pay_form">
										@csrf
                                        <input type="hidden" name="enroll"   value="{{ $enroll->id }}">
                                        <input type="hidden" name="amnt"   value="{{ $enroll->emi_amnt }}">
                                        <input type="hidden" name="pay"   value="{{ $enroll->emi_amnt }}">
                                        @foreach($gateways as $gk=>$gtwy)
                                            <input type="hidden" name="gateway" value="{{ $gtwy->id }}">
                                        @endforeach
                                        <button type="submit" name="do" value="pay" type="submit" class="btn btn-sm btn-outline-success">
                                        ₹ Pay
                                        </button>
                                    </form>
                                    @else 
                                    <form action="{{ $payurl }}" method="post" >
                                        @csrf
                                        <input type="hidden" name="enroll" value="{{ $enroll->id }}">
                                        <button type="submit" name="pay" value="gateway" type="submit" class="btn btn-sm btn-outline-success">
                                        ₹ Pay
                                        </button>
                                    </form>
                                    @endif
                                </li>
                            </ul>
                            </td>
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

<div id="pay_loader">
    <p class="text-center">
        <span><i class="fa fa-spinner fa-spin"></i> Processing..</span><br><br>
        <span id="aware">Please Do not Press<b><br> Cancel or Back Button</b><br>While Processing !</span>
    </p>
</div>

<style>
    tr.table-separated>td,
    tr.table-separated>th {
        border-bottom: 2px solid lightgray !important;
    }
	
	
	

    #pay_loader{
        position: fixed;
        bottom:0;
        width:100%;
        height:100%;
        background:#000000c9;
        overflow:hidden!important;
        z-index:9999;
        display:none;
    }
    #pay_loader>p{
        color:#fdd199;
        margin-top:10%;
        font-size:25px;
    }
    
    #pay_loader>p>span#aware{
        /* background:white; */
        color:white;
        /* text-shadow:2px 1px 5px gray; */
        font-size:initial;
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
const txn_modal = $('#modal');
const openModalButton = $('#openModal');
const closeModalButton = $('#closeModal');

// Open modal
$(document).on('click','.scheme_txn_btn',function(e){
    e.preventDefault();
    $("#scheme_txn_detail").empty().append('<p class="text-center"><span style="color:orange;" ><i class="fa fa-spinner fa-spin"> </i> Loding TXNs..</span></p>')
    txn_modal.addClass('show');
    $("#scheme_txn_detail").load($(this).attr('href'));
});

// Close modal
$('#close_txn_model').click(function(){
    txn_modal.removeClass('show');
});

// Close modal when clicking outside of the modal content
$(document).click(function(e){
    if (e.target === modal) {
        txn_modal.removeClass('show');
    }
});

$(".emi_pay_form").submit(function(e){
    if($('input[name="gateway"]').val()!=""){
        $("#pay_loader").show();
    }else{
        alert("Please Select The Payment Gateway First !");
        return false;
    }
});
</script>
@endsection