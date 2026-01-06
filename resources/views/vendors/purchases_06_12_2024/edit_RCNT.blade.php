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
          <!-- general form elements -->
                <div class="card card-primary">

                    <div class="card-header"><h3 class="card-title"><x-back-button />  Edit </h3></div>

                    <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('purchases.update',$purchase->id)}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('put')
           
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

          <hr>

            <div class="row small_input ">
                <div class="col-12 bg-light mb-2 p-0" style="border:1px solid lightgray;">
                    <h4 class="m-2"> Stock Details - </h4>
                </div>
            </div>
        @php 
            $count = 0;
            $num = 0; 
            $block_num =  $input_num = 0;  
        @endphp
        @foreach($purchase->stocks as $key=>$stock)
           
            @if($num==0)
                @php
                    $count = $stock->where('category_id',$stock->category_id)->count();
                @endphp
            @endif
            @if($num==0)
                <div class="row main_bill_block stock_block my-2" id="{{ ($block_num==0)?'main_bill_block':'' }}">
                    <legend class="block_sn px-3">{{ ++$block_num }}</legend>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="metals">Metals</label>
                            <select name="metals[]" class="form-control select2" id="metals" placeholder="Select Metal">
                                <option value="">Select</option>
                                @foreach (categories(1) as $category)
                                    <option value="{{ $category->id }}"  {{ ($stock->categories->contains($category->id))?'selected':'' }} >{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="rate">Rate</label>
                            <input type="number" class="form-control calculate_item rate" id="rate" name="rate[]" min="1" step="any" placeholder="Enter Rate" value="{{  $stock->rate  }}" >
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="collections">Collections</label>
                            <select name="collections[]" class="form-control select2" id="collections" placeholder="Select Collection">
                                <option value="">Select</option>
                                @foreach (categories(2) as $category)
                                    <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}
                                    >{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category[]" class="form-control select2" id="category" placeholder="Select Category">
                                <option value="">Select</option>
                                @foreach (categories(3) as $category)
                                    <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

    
                    <div class="col-lg-12">
                        <table class = "table table-responsive table-bordered">
                            <thead class = "bg-info">
                                <tr>
                                    <th> SN </th>
                                    <th> purchase Name </th>
                                    <th> Quantity </th>
                                    <th> Carat </th>
                                    <th> Gross Weight </th>
                                    <th> Net Weight </th>
                                    <th> Purity </th>
                                    <th> Watages </th>
                                    <th> Fine Purity </th>
                                    <th> Fine Weight </th>
                                    <th> Labour Charge </th>
                                    <th> Amount </th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" >
            @endif
            @if($num<=$count)
                <tr id="main_item_tr_1" class="main_item_tr">
                    <td class="sn-box">
                        <input type="hidden"class="stock_ids" name="stock_id[{{ $input_num }}][]" value="{{ $stock->id }}" id="stock_id_bndl">
                        <span class = "sn-number ">{{ $num+1 }}</span>
                        <a href="{{ route('purchases.delete',$stock->id) }}" class = "btn btn-danger btn-sm btn-delete tr_del_btn"> X </a>
                    </td>
                    <td>
                        <input type="text" class="tb_input product" name="product_name[{{ $input_num }}][]" id="product_name_{{ $loop->index + 1 }}" placeholder="Product Name" value="{{ $stock->name }}" >
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item quantity" name="quantity[{{ $input_num }}][]" id="quantity_{{ $loop->index + 1 }}" placeholder="Quantity" oninput="calculate(this)" value="{{ $stock->quantity }}"   min = "0" step = "any" readonly style="width:50px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item caret" name="carat[{{ $input_num }}][]" id="carat_{{ $loop->index + 1 }}" placeholder="Carat" oninput="calculate(this)" value="{{ $stock->carat }}"   min = "0" step = "any"  style="width:70px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item gross_weight" name="gross_weight[{{ $input_num }}][]" id="grossWeight_{{ $loop->index + 1 }}" placeholder="Gross Weight" oninput="calculate(this)" value="{{ $stock->gross_weight }}"   min = "0" step = "any"  style="width:80px;" >
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item net_weight" name="net_weight[{{ $input_num }}][]" id="netWeight_{{ $loop->index + 1 }}" placeholder="Net Weight" value="{{ $stock->net_weight }}"   oninput="calculate(this)"  min = "0" step = "any"  style="width:80px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item purity" name="purity[{{ $input_num }}][]" id="purity_{{ $loop->index + 1 }}" placeholder="Purity" oninput="calculate(this)" value="{{ $stock->purity }}"   min = "0" step = "any"  style="width:80px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item waste" name="wastage[{{ $input_num }}][]" id="wastage_{{ $loop->index + 1 }}" placeholder="Wastage" oninput="calculate(this)" value="{{ $stock->wastage }}"   min = "0" step = "any" style="width:80px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input fine_pure" name="fine_purity[{{ $input_num }}][]" id="finePurity_{{ $loop->index + 1 }}" placeholder="Fine Purity" readonly value="{{ $stock->fine_purity }}"   min = "0" step = "any" style="width:80px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input fine_weight" name="fine_weight[{{ $input_num }}][]" id="fineWeight_{{ $loop->index + 1 }}" placeholder="Fine Weight" readonly value="{{ $stock->fine_weight }}"   min = "0" step = "any" style="width:80px;" >
                    </td>
                    <td>
                        <input type="number" class="tb_input calculate_item labour" name="labour_charge[{{ $input_num }}][]" id="labourCharge_{{ $loop->index + 1 }}" placeholder="Labour Charge" oninput="calculate(this)" value="{{ $stock->labour_charge }}"   min = "0" step = "any"  style="width:80px;">
                    </td>
                    <td>
                        <input type="number" class="tb_input amount" name="amount[{{ $input_num }}][]" id="amount_{{ $loop->index + 1 }}" placeholder="Amount" readonly value="{{ $stock->amount }}"   min = "0" step = "any" style="width:80px;">
                    </td>
                </tr>
                @php 
                    $num++ ;
                @endphp
            @endif
            @if($num==$count)
                @php 
                    $num = 0 ;
                    $input_num++;
                @endphp
                </tbody>
            </table>
    
            <a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>
            <!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" > Add More </button> -->
            <a href="#main_bill_block" class="btn btn-sm btn-outline-primary stock_block_add" style="float:right;"  id="block_head"><li  class="fa fa-plus-circle"></li> Block</a>
    
        </div>
        
        </div>
        @endif
    @endforeach
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

