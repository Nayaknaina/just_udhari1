  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

  @endphp

  <x-page-component :data=$data />
  <style>
    a#supplr_plus{
        font-size:15px;
        border:1px dotted blue;
        padding:0 0.3rem 0 0.3rem;
        float:right;
        color:blue;
    }
    a#supplr_plus:hover{
        border:1px solid blue;
        background:lightblue;
        color:white;
    }
    .ele_block{
        position: relative;
    }
    .over_button{
        position: absolute;
        font-size:15px;
        padding:0 0.3rem 0 0.3rem;
        line-height:1;
        right:-5px;
        top:-5px;
        background:white;
    }
    .over_button:over{
        color:white;
    }
    a.ele_plus{
        border:1px dotted blue;
        color:blue;
    }
    a.ele_plus:after{
        content:"+";
    }
    a.ele_minus{
        border:1px dotted red;
        color:red;
    }
    a.ele_minus:after{
        content:"x";
    }
    a.ele_plus:hover{
        background:lightblue;
    }
    a.ele_minus:hover{
        background:pink;
    }
    .pay_remove{
        position:absolute;
        top:0;right:0;
        border:1px solid red;
        padding:0 5px;
        color:red;
        background:white;
    }
    .pay_remove:hover{
        background:red;
        color:white;
    }
</style>

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
                    <a href="javascript:void(null)" data-toggle="modal" data-target="#newsupplier" id="supplr_plus"><li class="fa fa-plus"></li></a>
                    <select name = "supplier" class = "form-control select2" id="supplier">
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
                    <ul class="row text-center" style="list-style:none;padding:0;margin:0;">
                        <!-- <li class="col-md-3 p-0"><h4 class="m-2"> Stock Details - </h4></li> -->
                        <li class="col-md-3 p-0"><h4 class="m-0 h-100">
                        <select id="stocktype" class="form-control m-0 h-100" name="stocktype" >
                            <option value="">Stock Type ?</option>
                            <option value="artificial">Artificial Jewellery</option>
                            <option value="genuine">Genuine Jewellery</option>
                            <option value="loose">Loose Stock</option>
                        </select></h4>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <div id="entry_stock_area" class="col-12 px-2 m-0">

            </div>
            <div class = "row my-2 py-2" style="border-top:1px solid #e3e3e3;border-bottom:1px solid #a6a6a6;">
                <div class = "col-lg-2 p-1">
                    <div class = "form-group">
                        <label> Total Quantity </label>
                        <input class = "form-control" name="totalquantity" id="totalQuantity" placeholder="Total Quantity" readonly>
                    </div>
                </div>

                <div class = "col-lg-2 p-1">
                    <div class = "form-group">
                        <label> Total Weight </label>
                        <input class = "form-control" name="totalweight" id="totalWeight" placeholder="Total Weight" readonly>
                    </div>
                </div>

                <div class = "col-lg-2 p-1">
                    <div class = "form-group">
                        <label> Total Fine Weight </label>
                        <input class = "form-control" name="totalfineweight" id="totalFineWeight" placeholder="Total Fine Weight" readonly>
                    </div>
                </div>

                <div class = "col-lg-3 p-1">
                    <div class = "form-group">
                        <label> Total Amount </label>
                        <input class = "form-control" name="totalamount" id="totalAmount" placeholder="Payable" readonly>
                    </div>
                </div>

                <div class = "col-lg-3 p-1">
                    <div class = "form-group">
                        <label> Remains </label>
                        <input class = "form-control " name="remains" id="remains" placeholder="Remains Amount" readonly>
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
                        <input type="text" name="payamount" id="payamount"class="form-control text-center" readonly="" required="" placeholder="Total Paid" readonly value="0">
                        <span class="input-group-text">
                            <b>Rs.</b>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mb-2" id="payment_block">
            </div>
            <div class="row" >
                <div class="col-12 text-center pt-3" style="border-top:1px solid #a6a6a6;">
                    <button type = "submit" class="btn btn-danger"> Submit </button>
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                </div>
            </div>
            </form>

            </div>
            </div>
            </div>
            
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    
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
        #stocktype{
            /* font-weight: bold; */
            text-shadow: 1px 2px 3px gray;
            /* padding: 2px; */
            margin: 0;
            background: transparent;
            border: none;
            border: 1px solid lightgray;
            font-size: inherit;
            text-align:center;
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
        </style>
  </section>
  <div class="modal" tabindex="-1" role="dialog" id="newsupplier">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Supplier</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('suppliers.store') }}" role="form" method="post" id="new_splr_form">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="supplier_name" d="supplier_name" class="form-control new_splr" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control new_splr" placeholder="Mobile Number" oninput="digitonly(event,10)">
                    </div>
                    <div class="form-group">
                        <input type="text" name="gst_no" id="gst_no" class="form-control new_splr" placeholder="GST Number">
                    </div>
                    <div class="form-group">
                        <select name="state" class="form-control new_splr" id="state">
                            <option value="">Select State</option>
                            @foreach (states() as $state )
                            <option value = "{{ $state->code }}"> {{ $state->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="district" class="form-control new_splr" id="district">
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control new_splr" name="address" id="address" placecholder="Full Address"></textarea>  
                    </div>
                    <hr class="m-2">
                    <div class="form-group text-center">
                        <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                        <button type="submit" name="save" id="save_splr_btn" value="splr" class="btn btn-sm btn-success">Save ?</button>  
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    
    <div class="modal fade" id="assoc_ele" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h6 class="modal-title" id="exampleModalLabel">Associated Element 
                        
                    </h6>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2" id="assoc_body">
                    
                </div>
            </div>
        </div>
    </div>
  @endsection

  @section('javascript')
<script>
const edit = false;
</script>

@include('vendors.purchases.content.js_dynamicaction')

<script>
    $(document).ready(function() {

        $('.new_splr').blur(function(){
            $(this).removeClass('is-invalid')
        });
        $('#state').change(function() {
            var state = $(this).val();
            if (state) {
                $.ajax({
                    url: '/get-districts',
                    type: 'GET',
                    data: { state: state },
                    success: function(response) {
                        $('#district').empty();
                        $('#district').append('<option value="">Select District</option>');
                        $.each(response, function(key, district) {
                            $('#district').append('<option value="' + district.code + '">' + district.name + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#district').empty();
                $('#district').append('<option value="">Select District</option>');
            }
        });

        $("#new_splr_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
            var path = $(this).attr('action');
            $.post(path, formdata, function(data) {
                success_sweettoatr(data.success);
                const supp = data.data;
                const option = '<option value="'+supp.id+'" >'+supp.supplier_name+'</option>';
                $("#supplier").append(option);
                $("#supplier").val(supp.id);
                $("#newsupplier").modal('hide');
            })
            .fail(function(response) {
                $('input').removeClass('is-invalid');
                $('#save_splr_btn').prop("disabled", false);
                $('#save_splr_btn').prop("disabled", false);
                var errors = response.responseJSON.errors ;
                if (response.status === 422) {
                    $.each(errors, function(field, messages) {
                    var $field = $('[name="' + field + '"]');
                    toastr.error(messages[0]) ;
                    $field.addClass('is-invalid') ;
                    });
                } else {
                    toastr.error(errors) ;
                }
            });
        });

        $('#newsupplier').on('hidden.bs.modal', function () {
            $("#new_splr_form").trigger('reset');
        });

        $("#stocktype").change(function(){
            var sel = $(this).val();
            if(sel!=""){
                $("#entry_stock_area").empty().load("{{ route("purchases.forms") }}","form="+sel);
            }else{
                $("#entry_stock_area").empty();
            }
        });

        $('#submitForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formAction = $(this).attr('action');
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

    function digitonly(event,num){
        let inputValue = event.target.value;

            // Allow only digits using regex
            inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

            // Ensure that the input has exactly 10 digits
            if (inputValue.length > num) {
                inputValue = inputValue.slice(0, 10);  // Trim to 10 digits
            }

            // Update the input field with the valid input
            event.target.value = inputValue;
    }

    function triggerselect(){
        $(document).find('select.select2').each(function(){
            $(this).select2();
        });
    }


    // $(document).on('click','.assoc_plus',function(e){
    //     e.preventDefault();
    //     $("#assoc_body").load($(this).attr('href'));
    //     $("#assoc_ele").modal('show');
    // });
    // $(document).on('click','.ele_plus',function(){
    //     var block = $("#element_block_default").clone();
    //     block.attr('id','');
    //     block.find('input').val("");
    //     block.find("a.ele_plus").removeClass('ele_plus').addClass('ele_minus');
    //     block.insertAfter("#element_block_default");
    // });
    // $(document).on('click','.ele_minus',function(){
    //     $(this).parent('.row').remove();
    // });
  </script>
  @endsection

