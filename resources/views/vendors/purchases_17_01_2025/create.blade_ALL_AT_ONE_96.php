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

            <div class="card-header">
            <h3 class="card-title"><x-back-button />  Create </h3>
            </div>

            <div class="card-body">

            <form id = "submitForm" method="POST" action="{{ route('purchases.store')}}" class = "myForm" enctype="multipart/form-data">

            @csrf

            @method('post')

            <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">

                <div class="col-lg-3">
                    <label for=""> Supplier </label>
                    <select name = "supplier" class = "form-control select2">
                        <option value="">Select</option>
                        @foreach (supplier() as $supplier )
                        <option value = "{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill No </label>
                    <input type="text" name="bill_no" class="form-control form-group" placeholder="Enter Bill No "  >
                </div>

                <div class="col-lg-3">
                    <label for=""> Bill Date </label>
                    <input type="date" name="bill_date" class="form-control form-group" placeholder="Enter Bill Date" max = "{{ date('Y-m-d') }}">
                </div>

                <div class="col-lg-3">
                    <label for=""> Batch No </label>
                    <input type="text" name = "batch_no" class="form-control form-group" placeholder="Enter Batch No ">
                </div>

            </div>

            <div class="row small_input">
                <div class="col-12 bg-light mb-2 p-0" style="border:1px solid lightgray;">
                       <h4 class="m-2"> Stock Details - 
                        <a href="#main_bill_block" class="btn btn-sm btn-outline-primary"  id="block_head"><li  class="fa fa-plus"></li> More</a></h4>
                </div>
            </div>
            
            <div class="row main_bill_block" id="main_bill_block" >
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="metals">Metals</label>
                        <select name="metals" class="form-control" id="metals" placeholder="Select Metal">
                            <option value="">Select</option>
                            @foreach (categories(1) as $category )
                            <option value = "{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="rate">Rate</label>
                        <input type="number" class="form-control" id = "rate" name = "rate" min="1" step="any" placeholder="Enter Rate">
                    </div>
                </div>
    
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="collections">Collections</label>
                        <select name="collections" class="form-control select2" id="collections" placeholder="Select Collection">
                            <option value="">Select</option>
                            @foreach (categories(2) as $category )
                            <option value = "{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select name="category" class="form-control select2" id="category" placeholder="Select Category">
                            <option value="">Select</option>
                            @foreach (categories(3) as $category )
                            <option value = "{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <table class = "table table-responsive table-bordered">
                        <thead class = "bg-info">
                            <tr>
                                <th> SN </th>
                                <th> Product Name </th>
                                <th> Quantity </th>
                                <th> Carat </th>
                                <th> Gross Weight </th>
                                <th> Net Weight </th>
                                <th> Purity </th>
                                <th> Wastage </th>
                                <th> Fine Purity </th>
                                <th> Fine Weight </th>
                                <th> Labour Charge </th>
                                <th> Amount </th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" >
                        <tr>
                            <td class="sn-box">
                                <span class = "sn-number ">1</span>
                                <button type = "button" class = "btn btn-danger btn-sm btn-delete">X</button>
                            </td>
                            <td>
                                <input type="text" class="tb_input" name="product_name[]" id="productName_1" placeholder="Product Name"> 
                            </td>
                            <td> 
                                <input type="number"  class="tb_input" name="quantity[]" id="quantity_1" placeholder="Quantity" oninput="calculate(this)" min = "0" step = "any" value="1" readonly> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="carat[]" id="carat_1" placeholder="Carat" min = "0" step = "any"> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="gross_weight[]" id="grossWeight_1 floatInput " placeholder="Gross Weight " oninput="calculate(this)"  min = "0" step = "0.001"> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="net_weight[]" id="netWeight_1" placeholder="Net Weight" oninput="calculate(this)"  min = "0" step = "any"  > 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="purity[]" id="purity_1" placeholder="Purity" oninput="calculate(this)" min = "0" step = "any"> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="wastage[]" id="wastage_1" placeholder="Wastage" oninput="calculate(this)" min = "0" step = "any"> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="fine_purity[]" id="finePurity_1" placeholder="Fine Purity"  min = "0" step = "any" readonly> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="fine_weight[]" id="fineWeight_1" placeholder="Fine Weight"  min = "0" step = "any" readonly> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="labour_charge[]" id="labourCharge_1" placeholder="Labour Charge" oninput="calculate(this)" min = "0" step = "any"> 
                            </td>
                            <td> 
                                <input type="number" class="tb_input" name="amount[]" id="amount_1" placeholder="Amount" min = "0" step = "any" readonly> 
                            </td>
                        </tr>

                        </tbody>
                    </table>

                    <button type = "button" id = "addMoreBtn" class = "btn btn-primary" > Add More </button>
                    
                <a href="javascript:void(null);" class="btn btn-sm btn-danger row_del_btn">x</a>
                </div>
                
                <a href="javascript:void(null);" class="btn btn-sm btn-danger block_del_btn">x</a>
            </div>
            <div class = "row my-2 py-2" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #a6a6a6;">
                <div class = "col-lg-2">
                    <div class = "form-group">
                        <label> Total Quantity </label>
                        <input class = "form-control" name="totalquantity" id="totalQuantity" placeholder="Total Quantity" readonly>
                    </div>
                </div>

                <div class = "col-lg-3">
                    <div class = "form-group">
                        <label> Total Weight </label>
                        <input class = "form-control" name="totalweight" id="totalWeight" placeholder="Total Weight" readonly>
                    </div>
                </div>

                <div class = "col-lg-3">
                    <div class = "form-group">
                        <label> Total Fine Weight </label>
                        <input class = "form-control" name="totalfineweight" id="totalFineWeight" placeholder="Total Fine Weight" readonly>
                    </div>
                </div>

                <div class = "col-lg-2">
                    <div class = "form-group">
                        <label> Total Amount </label>
                        <input class = "form-control" name="totalamount" id="totalAmount" placeholder="Total Amount" readonly>
                    </div>
                </div>

                <div class = "col-lg-2">
                    <div class = "form-group">
                        <label> Pay Amount </label>
                        <input class = "form-control" name="payamount" id="payAmount" placeholder="Pay Amount" >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center ">
                    <button type = "submit" class="btn btn-danger"> Submit </button>
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                </div>
            </div>
            </form>

            </div>
            </div>
            </div>
            
            <style>
                .block_del_btn{
                    position: absolute;
                    right:0;
                }
                .row_del_btn{
                    position: absolute;
                    top:0;
                    right:0; 
                }
                /* #block_head{
                    float:right;
                } */
            </style>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    
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
        </style>
  </section>

  @endsection

  @section('javascript')

  @include('vendors.purchases.content.js_dynamicaction')

<script>

    $(document).ready(function() {
        $('#submitForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formAction = $(this).attr('action') ;
            var formData = new FormData(this) ;

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
                var errors = response.responseJSON.errors ;
                if (response.status === 422) {

                    $('input').removeClass('is-invalid'); // Remove any previous validation classes
                    $('.invalid-feedback').remove(); // Remove any previous validation messages

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
                } else {
                    toastr.error(errors) ;
                }
                }
            });
        });
    });

  </script>

  @endsection

