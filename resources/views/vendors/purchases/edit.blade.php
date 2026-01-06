@extends('layouts.vendors.app')

@section('content')


@include("layouts.theme.css.datatable")
@php

//$data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

$stock_ttl_arr = ["artificial"=>"Artificial Jewellery","genuine"=>"Genuine Jewellery","loose"=>"Loose Stock"];
@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('purchases.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Purchases"=>route('purchases.index')];
$data = new_component_array('newbreadcrumb',"Edit Purchase",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
          <!-- left column -->
            <div class="col-md-12">
          <!-- general form elements -->
                <div class="card card-primary">

                    <!--<div class="card-header"><h3 class="card-title"><x-back-button />  Edit </h3></div>-->

                    <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('purchases.update',$purchase->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')
            <input type="hidden" name="stocktype" value="{{ $purchase->stock_type }}" >
            <div class="row">

                <div class="col-lg-3">
                    <label for=""> Supplier </label>
                    <select name = "supplier" class = "form-control select2">
                        <option value="">Select</option>
                        @foreach (supplier() as $supplier )
                        <option value = "{{ $supplier->id }}" @if($purchase->supplier_id==$supplier->id ) selected @endif >{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill No </label>
                    <input type="text" name="bill_no" class="form-control form-group" placeholder="Enter Bill No " value = "{{ $purchase->bill_no }}">
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill Date </label>
                    <input type="date" name="bill_date" class="form-control form-group" placeholder="Enter Bill Date" value = "{{ $purchase->bill_date }}">
                </div>

                <div class="col-lg-3">
                    <label for=""> Batch No </label>
                    <input type="text" name = "batch_no" class="form-control form-group" placeholder="Enter Batch No " value = "{{ $purchase->batch_no }}">
                </div>

            </div>

            <div class="row small_input ">
                <div class="col-12 bg-light mb-2 p-0" style="border:1px solid lightgray;">
                    <h4 class="m-2"> {{ $stock_ttl_arr["{$purchase->stock_type}"] }}</h4>
                </div>
                <div class="col-12 my-2" id="form_area">
                    @if($purchase->stock_type=='artificial')

                        @include("vendors.purchases.content.editartificialform")

                    @else 
						
                        @include("vendors.purchases.content.editgenuineform")
                        
                    @endif
                </div>
            </div>
       
    <style>
        .block_sn{
            font-weight:bold;
            color:teal;
            background: #80808024;
        }
        .block_del_btn{
            position:absolute;
            right:5px;
        }
        .row_del_btn{
            position:absolute;
            right:5px;
            top:0;
        }
        .custom_remove_btn{
            border: 1px solid red;
            padding: 0px 6px;
            text-align: center;
            color: red;
            border-radius:5px;
            font-weight:bold;
            background:white;
        }
        .custom_remove_btn:hover{
            color: white;
            background:#dc3545;
        }
        .custom_add_btn{
            border: 1px solid blue;
            padding: 0px 6px;
            text-align: center;
            color: blue;
            border-radius:5px;
            font-weight:bold;
            background:white;
        }
        .custom_add_btn:hover{
            color: white;
            background:blue;
        }
        .stock_block{
            box-shadow:1px 2px 3px 5px lightgray;
        }
    </style>
    <style>
        .tb_input,.tb_input[readonly]{
            border:unset;
            border-bottom:1px dashed gray;
            text-align:center;
        }
        .tb_input[readonly]{
            font-weight:bold;
        }
        .btn-delete{
            border:1px solid red;
            color:red;
            padding:0 5px;
        }
        .main_bill_block{
            border-top:1px dashed gray;
            padding:5px;
        }
        .main_bill_block>div{
            padding:0 2px;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
        -moz-appearance: textfield;
        }
    </style>
    <style>
        tr.disabled{

        }
        tr.disabled>td{
            position:relative;
        }
        tr.disabled.element_tr > td:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.81); /* Optional: to dim the content */
            /*backdrop-filter: blur(5px); /* Apply blur */
            z-index: 1; /* Ensure it's on top of content */
        }
        tr.parent_disabled.element_tr > td::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.81); /* Optional: to dim the content */
            /*backdrop-filter: blur(5px); /* Apply blur */
            z-index: 1; /* Ensure it's on top of content */
        }

        tr.disabled.item_tr > td:not(:first-child)::after,div.disabled::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.81); /* Optional: to dim the content */
            /*backdrop-filter: blur(5px); /* Apply blur */
            z-index: 1; /* Ensure it's on top of content */
        }
        label.ele_del_btn_invis{
            display:none;
        }
        .pay_remove,.pay_del{
            position:absolute;
            top:0;right:0;
            border:1px solid red;
            padding:0 5px;
            color:red;
            background:white;
        }
        .pay_remove:hover,.pay_del:hover,.pay_del.active{
            background:red;
            color:white;
        }
    </style>                     

            <div class="row my-2 py-2" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #a6a6a6;">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Total Quantity</label>
                        <input class="form-control" name="totalquantity" id="totalQuantity" placeholder="Total Quantity" readonly value="{{ $purchase->total_quantity }}">
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Total Weight</label>
                        <input class="form-control" name="totalweight" id="totalWeight" placeholder="Total Weight" readonly value="{{ $purchase->total_weight }}">
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Total Fine Weight</label>
                        <input class="form-control" name="totalfineweight" id="totalFineWeight" placeholder="Total Fine Weight" readonly value="{{ $purchase->total_fine_weight }}">
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Total Amount</label>
                        <input class="form-control" name="totalamount" id="totalAmount" placeholder="Total Amount" readonly value="{{ $purchase->total_amount }}">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label>Remains</label>
                        <input class="form-control" name="remains" id="remains" placeholder="Remains" value="{{ $purchase->remain }}" readonly>
                    </div>
                </div>
            </div>
            <div class="row"  id="pre_pay_option">
                <div class="col-md-3 col-12 form-group mb-2">
                    <label for="mode">Pay Mode</label>
                    <select class="form-control pay_control" name="mode" id="mode" >
                        <option value="">Choose</option>
                        <option value="on">Online</option>
                        <option value="off">Offline</option>
                    </select>
                </div>
                @php 
                    $medium_arr = ['PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Cash'];
                    $Cash = "off";
                @endphp
                <div class="col-md-3 col-12 form-group mb-2">
                    <label for="medium">Pay Medium</label>
                    <select class="form-control pay_control" name="medium" id="medium" disabled>
                        <option value="">Choose</option>
                        @foreach($medium_arr as $key=>$value)
                            <option value="{{ $value }}" class="{{ $$value??'on' }}" style="display:none;">{{ $value }}
                            </option>
                        @endforeach                         
                    </select>
                </div>
                <div class="col-md-3 col-12 form-group  mb-2">
                    <label for="amount">Amount</label>
                    <div class="input-group">
                        <input type="text" class="form-control readonly amount" name="amount" id="amount" placeholder="Enter Amount !" disabled="" >
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button"  data-toggle="modal" disabled="" id="ok_pay">OK ?</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12 form-group mb-2">
                    <label form="remain">Paid</label>
                    <div class="input-group">
                        <input type="text" name="payamount" id="payamount"class="form-control text-center" readonly="" required="" placeholder="Total Paid" readonly value="{{ $purchase->pay_amount }}">
                        <span class="input-group-text">
                            <b>Rs.</b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mb-2" id="payment_block">
                @if($purchase->payments->count()>0)
                @foreach($purchase->payments as $pk=>$pay)
                <div class="col-md-3 my-1 p-2 " style="border:1px solid #a6a6a6;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-"><b>MODE</b></span>
                        </div>
                        @php 
                            $mode = ($pay->mode=='on')?'Online':'Offline';
                        @endphp
                        <input type="hidden" name="mode[]" value="on">
                        <span class="form-control text-secondary"><b>{{ $mode }}</b></span>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-"><b>MEDIUM</b></span>
                        </div>
                        <input type="hidden" name="medium[]" value="{{ $pay->medium }}">
                        <span class="form-control text-secondary"><b>{{ $pay->medium }}</b></span>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text px-"><b>AMOUNT</b></span>
                        </div>
                        <input type="hidden" name="paid[]" value="{{ $pay->amount }}">
                        <span class="form-control text-secondary"><b>{{ $pay->amount }}</b></span>
                    </div>
                    <input type="hidden" name="pre_paid[]" value="{{ $pay->id }}">
                    {{--<label class="pay_del" for="pay_{{ $pay->id }}" style="cursor:pointer;font-weight:normal;z-index:2;">x
                        <input type="checkbox" name="pay_del[]" value="{{ $pay->id }}" style="display:none;" id="pay_{{ $pay->id }}" class="pay_del_check">
                    </label>--}}
                </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-12 text-center pt-3 "style="border-top:1px solid #a6a6a6;">

                    <button type = "submit" class="btn btn-danger"> Submit </button>
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{ time().rand(9999, 100000) }}" >
                    <input type = "hidden" id = "deletedStocks" name = "deleted_stocks" value = "">

                </div>
            </div>

        </form>

          </div>
          </div>

          </div>
          <!-- /.row -->
      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
</section>

@endsection

@section('javascript')
<script>
const edit = true;
</script>
@include('vendors.purchases.content.js_dynamicaction')
<script>

  $(document).ready(function() {
    $('#submitForm').submit(function(e) {
    e.preventDefault(); // Prevent default form submission

    var formAction = $(this).attr('action') ;
    var formData = new FormData(this) ;

    // Send AJAX request

        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {
            $('.btn').prop("disabled", true);
            $('#loader').removeClass('hidden');
            },
            success: function(response) {
            // Handle successful update
            success_sweettoatr(response.success);
            window.open("{{route('purchases.index')}}", '_self');
            },
            error: function(response) {
                $('.btn-outline-danger').prop("disabled", false);
                $('.btn').prop("disabled", false);
                $('#loader').addClass('hidden');
                var errors = response.responseJSON.errors;
            if (response.status === 422) {
                $('input').removeClass('is-invalid');

                $.each(errors, function(field, messages) {
                    // Extract field name and index (e.g., field = "gross_weight.0")
                    var matches = field.match(/^([a-zA-Z_]+)\.(\d+)$/);

                    if (matches) {

                        var fieldName = matches[1] + '[]';
                        var index = matches[2];
                        var $field = $('[name="' + fieldName + '"]').eq(index);

                    } else {

                        var $field = $('[name="' + field + '"]');

                    }

                    $field.addClass('is-invalid') ;
                    toastr.error(messages[0]) ;
                    // $field.after('<div class="invalid-feedback">' + messages[0] + '</div>') ;

                }) ;
            } else { toastr.error(errors) ; }
            }
        });
    });

    $(document).on('change','.del_ele_check, .del_item_check',function(e){
        var label = $(this).closest('label'); // Get the closest label to the checkbox
        var tr = $(this).closest('tr');
        var checked = false;
        if ($(this).is(':checked')) {
            tr.addClass('disabled');
            checked = true;
            label.removeClass('btn-outline-danger').addClass('btn-danger');
        } else {
            tr.removeClass('disabled');
            label.removeClass('btn-danger').addClass('btn-outline-danger');
        }
        var nextmaintr = tr.nextAll('tr.main_item_tr').first();
        if(label.hasClass('del_item_btn')){
            tr.nextAll('tr').each(function() {
                if($(this).hasClass('element_tr')){
                    if(checked){
                        $(this).addClass('disabled');
                        $(this).find('td>label.del_ele>.del_ele_check').prop('checked',true);
                        $(this).find('td>label.del_ele ').addClass('ele_del_btn_invis');
                        $(this).find('td>label.del_ele').removeClass('btn-outine-danger').addClass('btn-danger');
                    }else{
                        $(this).removeClass('disabled');
                        $(this).find('td>label.del_ele>.del_ele_check').prop('checked',false);
                        $(this).find('td>label.del_ele').removeClass('ele_del_btn_invis');
                        $(this).find('td>label.del_ele').removeClass('btn-danger').addClass('btn-outine-danger');
                    }
                }else{
                    return false;
                }
                //$(this).find('td>label.del_ele').css('display','none');
            });
        }
    });
   
  });
    function triggerselect(){
        $(document).find('select.select2').each(function(){
            $(this).select2();
        });
    }
</script>

@endsection

