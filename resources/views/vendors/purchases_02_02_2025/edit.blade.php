@extends('layouts.vendors.app')

@section('content')


@php

$data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

$stock_ttl_arr = ["artificial"=>"Artificial Jewellery","genuine"=>"Genuine Jewellery","loose"=>"Loose Stock"]
@endphp

<x-page-component :data=$data />

<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
          <!-- left column -->
            <div class="col-md-12">
          <!-- general form elements -->
                <div class="card card-primary">

                    <div class="card-header"><h3 class="card-title"><x-back-button />  Edit </h3></div>

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
                        <label>Pay Amount</label>
                        <input class="form-control" name="payamount" id="payAmount" placeholder="Pay Amount" value="{{ $purchase->pay_amount }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center my-3 ">

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

   
  });

</script>

@endsection

