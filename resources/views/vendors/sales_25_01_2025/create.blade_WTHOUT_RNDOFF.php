  @extends('layouts.vendors.app')

  @section('content')

  @php

  $data = component_array('breadcrumb' , 'New Sell Bill',[['title' => 'Sell Bill']] ) ;
  $vendr_state =  app('userd')->shopbranch->state;
  @endphp

  <x-page-component :data=$data />

  <style>
        ul#custo_list,ul#item_list {
            position: absolute;
            list-style: none; /* Remove default bullet points */
            padding: 0;
            z-index: 1;
            box-shadow: 1px 2px 3px gray;
            justify-content: space-around; /* Space out the items inside the UL */
        }
        table>tbody>tr>td,table>tfoot>tr>td{
            padding:0!important;
        }
        .item_info{
            border:none;
            border-bottom:1px dashed gray;
        }
        .item_info:focus{
            border-bottom:1px solid gray;
            box-shadow: -1px -2px 5px 3px gray;
            background:#f9f9f9
        }
        .item_info:disabled,.item_info.readonly{
            border:none;
            /* border-top:none;
            border-bottom:none; */
        }
        .unit{
            position:absolute;
            bottom:0px;
            right:0;
            font-size:10px;
            color:blue;
            text-shadow: 1px 2px 5px;
            display:none;
        }
        div.disabled{
            opacity:0.5;
            z-index:0;
            pointer-events: none;
        }
    </style>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title"><x-back-button />  Create Sell Bill</h3>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                    <form id = "submitForm" method="POST" action="{{ route('sells.store')}}" class = "myForm" enctype="">
                        @csrf
                        @method('post')
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            @php 
                                $states = states();
                            @endphp
                            <!---Bill Info Form Element---->
                            <div class="col-md-8">
                                <label for="col-12">Customer</label>
                                <div class="row">
                                    <div class="form-group col-md-5 p-0 mb-1">
                                        <input type="hidden" name="exist" value="yes" disabled id="is_exist">
                                        <!-- <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="Name" oninput="existingcustomer()" required > -->
                                        <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="Name"  required >
                                        <ul class="col-12" id="custo_list" style="display:none;"></ul>
                                    </div>
                                    <div class="form-group col-md-3 p-0  mb-1">
                                        <input type="text" name="custo_mobile" id="custo_mobile" class="form-control" placeholder="Mobile"  oninput="digitonly(event,10);" onchange="digitonly(event,10);" required>
                                    </div>
                                    <div class="form-group col-md-4 p-0  mb-1">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Balance</span>
                                            </div>
                                            <label id="scheme_balance" class="text-center text-info form-control">NA</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-5 p-0  mb-1">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" >GSTIN</span>
                                            </div>
                                            <input type="text" name="custo_gst" id="custo_gst" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-7 p-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">State/Code</span>
                                            </div>
                                            <select name="custo_state_code" id="custo_loc" class="form-control" required >
                                                @if($states->count()>0)
                                                    @foreach($states as $si=>$state)
                                                        <option value="{{ $state->code }}" {{ ($state->code==$vendr_state)?'selected':''; }} >{{ $state->name." / ".$state->code }}</option>
                                                    @endforeach
                                                @else 
                                                    <option value="">No State/Code</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 disabled" id="custo_addr_area" style="border: 1px dotted gray;">
                                        <div class="row">
                                            <div class="col-md-6 p-1">
                                                <div class="form-group mb-1">
                                                    <select class="form-control custo_addr" name="custo_state" id="custo_state">
                                                    @if($states->count()>0)
                                                        <option value="">Select State</option>
                                                        @foreach($states as $si=>$state)
                                                            <option value="{{ $state->code }}">{{ $state->name." / ".$state->code }}</option>
                                                        @endforeach
                                                    @else 
                                                        <option value="">No State/Code</option>
                                                    @endif
                                                    </select>
                                                </div>
                                                <div class="form-group mb-1">
                                                    <select class="form-control custo_addr" name="custo_dist" id="custo_dist">
                                                        <option value="">Select Disrtict</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-1">
                                                    <input type="text" name="custo_area" id="custo_area" class="form-control custo_addr" placeholder="Enter Area Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 p-1">
                                                <textarea id="custo_addr" name="custo_addr" class="form-control custo_addr" placeholder="Enter Full Address" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <label for="bill_num" class="input-group-text mb-0">Bill No.</label>
                                    </div>
                                    <input type="text" name="bill_num" id="bill_num" class="form-control" placeholder="Bill Number" value="{{ justbillsequence() }}">
                                </div>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <label for="bill_num" class="input-group-text mb-0">Date</label>
                                    </div>
                                    <input type="date" name="bill_date" id="bill_date" class="form-control" placeholder="Bill Ddate" value="{{ date('Y-m-d', strtotime('now')) }}" required>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <label for="vndr_gst" class="input-group-text mb-0">GSTIN</label>
                                    </div>
                                    <input type="text" name="vndr_gst" id="vndr_gst" class="form-control" placeholder="GST Number" value="{{ app('userd')->shopbranch->gst_num }}" required="" readonly="">
                                </div>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <label for="hsn" class="input-group-text mb-0">HSN Code</label>
                                    </div>
                                    <select name="hsn" id="hsn" class="form-control" required="" onchange="sethsngst();">
                                        {{ justbillhsn(true); }}
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="bill_num">State / Code</label>
                                    <select name="vndr_state" id="vndr_state" class="form-control" required="">
                                        @if($states->count()>0)
                                            @foreach($states as $si=>$state)
                                                <option value="{{ $state->code }}" {{ ($state->code==$vendr_state)?'selected':''; }}>{{ $state->name." / ".$state->code }}</option>
                                            @endforeach
                                        @else 
                                            <option value="">No State/Code</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-stripped">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th width="5%">S.N.</th>
                                            <th width="35%">ITEM</th>
                                            <th class="bg-secondary"  width="10%">RATE</th>
                                            <th class="bg-secondary"  width="10%">MAKE</th>
                                            <th class="bg-secondary"  width="10%">AVAIL</th>
                                            <th width="10%">Quanity/Weight</th>
                                            <th width="10%">RATE</th>
                                            <th  width="10%">LABOUR</th>
                                            <th  width="10%">TOTAL</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i=0;$i<=5;$i++)
                                        <tr id="item_{{ $i }}" class="item_row">
                                            <td class="text-center">{{ $i+1  }}</td>
                                            <td>
                                                <div class="col-12 p-0">
                                                    <input type="hidden" name="type[]" value="">
                                                    <input type="hidden" name="id[]" value="">
                                                    <input type="hidden" name="source[]" value="">
                                                    <input type="text" name="item_name[]" class="form-control w-100 item_info  px-1 " placeholder="Item Name" >
                                                    <!-- <input type="text" name="item_name[]" class="form-control w-100 item_info" placeholder="Item Name" onkeyup="getitem($(this))"> -->
                                                </div>

                                            </td>
                                            
                                            <td class="bg-light text-dark">
                                                <input type="text" name="curr_rate" class="form-control item_info v" value="" id="" disabled >
                                            </td>
                                            
                                            <td class="bg-light text-dark">
                                                <input type="text" name="curr_make" class="form-control item_info  px-1" value="" id="" disabled>
                                            </td>
                                            
                                            <td class="bg-light text-dark">
                                                <input type="text" name="curr_avail" class="form-control item_info  px-1" value="" id="" disabled>
                                            </td>
                                            <td style="position:relative;">
                                                <input type="text" name="item_quant[]" class="form-control w-100 item_info  text-right  px-1" placeholder="Quantity" oninput="calculatetotal()">
                                                <span class="unit">Grm</span>
                                            </td>
                                            <td class="bg-light text-dark" >
                                                <input type="text" name="now_rate[]" class="form-control item_info text-center px-1" value="" id=""  placeholder="Applicable" oninput="calculatetotal()">
                                            </td>
                                            <td>
                                                <input type="text" name="item_chrg[]" class="form-control w-100 item_info  text-right  px-1" placeholder="Charge"  oninput="calculatetotal()">
                                            </td>
                                            <td>
                                                <input type="text" name="item_total[]" class="form-control w-100 item_info  text-right  px-1" placeholder="Total"  readonly {{ ($i==0)?'required':'' }}>
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td class="text-center" style="vertical-align:middle;">
                                                <a href="javascript:void(null);" class="btn btn-warning" style="color:maroon;" id="add_rows">
                                                    <li class="fa fa-caret-up w-100">
                                                    <span class="fa fa-plus-circle w-100"></span>
                                                    </li>
                                                </a>
                                            </td>
                                            <td colspan="6" >
                                                
                                            </td>
                                            <td style="text-align:center;vertical-align:middle;">
                                                <b style="">SUM</b>
                                            </td>
                                            <td>
                                                <input type="text" name="total_sum" class="form-control item_info readonly  text-right  px-1" value="" id="" readonly required style="font-weight:bold;">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Sub Total</label>
                                    </div>
                                    <input type="text" name="total_sub" class="form-control readonly text-right" value="0" id="" required style="font-weight:bold;"  readonly>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text px-1" id="basic-addon1">GST<span id="hsn_gst"></span>%</label>
                                    </div>
                                    <input type="hidden" name="gst_set" value="">
                                    <input type="text" name="gst_val" class="form-control  readonly text-center  px-1" value="0" id="gst_val" required style="font-weight:bold;" onfocus="$(this).select()"  oninput="calculatetotal()" readonly>
                                    <!-- <div class="input-group-prepend">
                                        <select name="gst_type" class="form-control item_info readonly text-center" style="font-weight:bold;padding:0;background-color: #f1f2f3 !important;border: 1px solid #bfc9d4" id="gst_type" >
                                            <option value="">GST%</option>
                                            <option value="in">Include</option>
                                            <option value="ex">Exclude</option>
                                        </select>
                                    </div>
                                    <input type="text" name="total_gst" class="form-control  readonly text-right" value="0" id="total_gst" required style="font-weight:bold;" onfocus="$(this).select()" readonly oninput="calculatetotal()"> -->
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Disc%</label>
                                    </div>
                                    <input type="text" name="total_disc" class="form-control readonly text-right" value="0" id="total_disc" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" id="basic-addon1">Total</label>
                                    </div>
                                    <input type="text" name="total_final" class="form-control readonly text-right" value="0" id="total_final" required style="font-weight:bold;" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;" id="pre_pay_option">
                            <div class="col-md-3 col-12 form-group">
                                <div class="form-group">
                                    <label for="mode">Pay Mode</label>
                                    <select class="form-control" name="mode[]" id="mode" oninput="validalance()" required>
                                        <option value="">Choose</option>
                                        <option value="on">Online</option>
                                        <option value="off">Offline</option>
                                    </select>
                                </div>
                            </div>
                            @php 
                                $medium_arr = ['PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Scheme','Cash'];
                                $Scheme = $Cash = "off";

                            @endphp
                            <div class="col-md-3 col-12 form-group">
                                <label for="medium">Pay Medium</label>
                                <select class="form-control" name="medium[]" id="medium" oninput="validalance()" disabled required>
                                    <option value="" >Choose</option>
                                    @foreach($medium_arr as $key=>$value)
                                        <option value="{{ $value }}" class="{{ $$value??'on' }}" style="display:none;">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-12 form-group">
                                <label for="amount">Amount</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control readonly " name="amount[]" id="amount" placeholder="Enter Amount !" readonly oninput="validalance()" onkeyup="validalance()" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#more_pay_modal" disabled id="bill_more_pay"><li class="fa fa-plus"></li></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <label form="remain">Remains</label>
                                <input type="text" name="remain" class="form-control" readonly id="remain" required placeholder="Remains Balance">
                            </div>
                        </div>
                        <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                            <div class="col-12 text-center form-group">
                                <button type="submit" name="make" value="bill" class="btn btn-danger">Create</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        
    </section>
    <div class="modal fade" id="more_pay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">More Payments</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" id="more_pay_remains_label" for="more_pay_remains">Remains</label>
                        </div>
                        <input type="text" name="more_pay_remains" class="form-control readonly text-right"  id="more_pay_remains" required style="font-weight:bold;" readonly value="">
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-control more_pay_input" name="more_pay_mode" id="more_pay_mode"  required>
                            <option value="">Mode</option>
                            <option value="on">Online</option>
                            <option value="off">Offline</option>
                        </select>
                        <select class="form-control more_pay_input" name="more_pay_medium" id="more_pay_medium"  disabled required>
                            <option value="" >Medium</option>
                            @foreach($medium_arr as $key=>$value)
                                <option value="{{ $value }}" class="{{ (isset($$value))?$$value:'on' }}" style="display:none;">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" id="more_pay_amount_label" for="more_pay_amount">Rs.</label>
                        </div>
                        <input type="text" name="more_pay_amount" class="form-control readonly text-right more_pay_input"  id="more_pay_amount" required style="font-weight:bold;" onfocus="$(this).select()" readonly>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-info" id="more_pay_ok">&check; OK</button>
                        </div>
                    </div>
                    <hr>
                    <div id="more_pay_form_field" class="more_pay_ok_field col-12 pb-2 mb-2" style="display:none;border-bottom:1px solid gray;">
                    </div>
                    <div class="more_pay_ok_field text-center" id="more_pay_done" style="display:none;">
                        <button type="button" class="btn btn-success">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  @endsection

  @section('javascript')

<script>

    $(document).ready(function() {
        
        $("#submitForm").trigger('reset');
        
        sethsngst();
        //$('#more_pay_modal').modal('show');
        $('#submitForm').submit(function(e) {
            $(document).find("#toast-container").remove();
            $("input,select").removeClass('is-invalid');
            e.preventDefault(); // Prevent default form submission
            if(checksum()){
                var formAction = $(this).attr('action') ;
                var formData = $(this).serialize() ;
    
                $.post(formAction,formData,function(response){
                    if(!response.valid){
                        if(response.errors){
                            var num = 0;
                            var focus = "";
                            $.each(response.errors,function(i,v){
                                if(i.indexOf(".") > 0){
                                    var ele_arr = i.split('.');
                                    if(num == 0){
                                        focus = $('[name="'+ele_arr[0]+'[]"]').eq(ele_arr[1]);
                                    }
                                    $('[name="'+ele_arr[0]+'[]"]').eq(ele_arr[1]).addClass("is-invalid");
                                    var pre_msg = "";
                                    $.each(v,function(ci,cv){
                                        if(pre_msg!=""){
                                            if(pre_msg!=cv){
                                                toastr.error(cv);
                                            }
                                        }else{
                                            pre_msg = cv;
                                            toastr.error(cv);
                                        }
                                    });
                                }else{
                                    if(num == 0){
                                        focus = $('[name="'+i+'"]');
                                    }
                                    $('[name="'+i+'"]').addClass("is-invalid");
                                    toastr.error(v[0]);
                                }
                                num++;
                                focus.focus();
                            });                    
                        }else{
                            toastr.error(response.msg);
                        }
                    }else{
                        if(response.status){
                            window.location.href = response.next;
                            success_sweettoatr(response.msg);
                        }else{
                            toastr.error(response.msg);
                        }
                    }
                });
            }else{
                toastr.error("Recheck the Payments !");
            }
            
        });
    });
    $("#add_rows").click(function(e){
        e.preventDefault();
        const trs = $('tbody>tr');
        var tr = trs.eq(0).clone();
        var num = trs.length;
        tr.attr('id','item_'+num);
        tr.find('td').eq(0).text(num+1);
        $(tr.find('td')).each(function(i,v){
            $(this).find('input').val("");
        });
        $(tr.find('td>[name="item_wght[]"],td>[name="item_count[]"]')).removeClass('readonly').prop('readonly',false);
        $('tbody').append(tr);

    });
    
    let typingTimer;
    const typingInterval = 200;
    
    $("#custo_name").keyup(function(){
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
                                        existingcustomer()
                                        }, typingInterval);
    });

    $(document).on('keyup','[name="item_name[]"]',function(){
        clearTimeout(typingTimer);
        var item_name = $(this);
        typingTimer = setTimeout(() => {
                                        getitem(item_name)
                                        }, typingInterval);
    });

    $(document).on('focus','[name="item_name[]"]',function(e){
        $(this).select();
        var ind = $(this).closest('tr').index();
        //alert("Cuur "+ind);
        if(ind > 0){
            var new_ind = ind-1;
            if($('[name="item_name[]"]').eq(new_ind).val()==""){
                $('[name="item_name[]"]').eq(new_ind).focus();
            }
        }
    });

    function existingcustomer(){
        $("#custo_list").load("{{ route('sells.customer') }}","name="+$("#custo_name").val(),function(response){
            if(response!="" ){
                $("#custo_list").show();
                $("#custo_addr_area").addClass('disabled');
                $(".custo_addr").attr('disabled',true);
                $("#is_exist").prop('disabled',false);
            }else{
                if($("#custo_name").val()!=""){
                    $("#custo_list").hide();
                    $("#scheme_balance").text('NA');
                    $("#custo_addr_area").removeClass('disabled');
                    $(".custo_addr").attr('disabled',false);
                    $("#is_exist").prop('disabled',true);
                }
            }
        }); 
    }

    $('[name="custo_state"]').change(function(){
        var state_id = $(this).val();
        if(state_id!=""){
            $.get("{{ url('get-districts') }}",'state='+state_id,function(response){
                var option = '<option value="">Select District</option>';
                if(response.length>0){
                    $.each(response,function(i,v){
                        option += '<option value="'+v.code+'">'+v.name+'</option>';
                    });
                }else{
                    option = '<option value="">No District Data !</option>';
                }
                $('[name="custo_dist"]').empty().append(option);
            });
        }
    });

    $(document).on('click','.custo_target',function(){
        var cust_data = $(this).data('target').split('-');
        $("#custo_name").val(cust_data[0]);
        $("#custo_mobile").val(cust_data[1]);
        $("#scheme_balance").text(cust_data[2]);
        $("#custo_list").hide();
    });

    function getitem(input,item_request){
        var ind = input.closest('tr').index();
        var item_container =  input.next('.item_list');
       $.get("{{ route('sells.item') }}","item="+input.val(),function(response){
            $('[name="id[]"]').eq(ind).val("");
            $('[name="type[]"]').eq(ind).val("");
            $('[name="source[]"]').eq(ind).val("");
            $('[name="curr_rate"]').eq(ind).val("");
            $('[name="curr_make"]').eq(ind).val("");
            $('[name="curr_avail"]').eq(ind).val("");
            $(document).find('ul#item_list').remove();
            if(response!="" ){
                //item_container.append(response);
                input.parent('div').append(response);
            }else{
                // $('[name="id[]"]').eq(ind).val("");
                // $('[name="type[]"]').eq(ind).val("");
                // $('[name="item_name[]"]').eq(ind).val("");
                // $('[name="curr_rate"]').eq(ind).val("");
                // $('[name="curr_make"]').eq(ind).val("");
                // $('[name="curr_avail"]').eq(ind).val("");
                // $('[name="item_count[]"]').eq(ind).prop('readonly',false);
                // $('[name="item_wght[]"]').eq(ind).prop('readonly',false);
                // $('[name="item_count[]"]').eq(ind).removeClass('readonly');
                // $('[name="item_wght[]"]').eq(ind).removeClass('readonly');
            }
        }); 
    }

    $(document).on('click','.item_target',function(e){
        e.preventDefault();
        var ind = $($(this).attr('href')).parent('div').parent('td').parent('tr').index();
        var item_data = $(this).data('target').split('-');
        var item_source  = $(this).data('source');
        $('[name="id[]"]').eq(ind).val(item_data[0]);
        $('[name="type[]"]').eq(ind).val(item_data[1]);
        $('[name="source[]"]').eq(ind).val(item_source);
        if(item_data[1]!='Genuine'){
            $('[name="item_quant[]"]').eq(ind).prop('readonly',false);
            $('[name="item_quant[]"]').eq(ind).focus();
            
        }else{
            $('[name="item_quant[]"]').eq(ind).prop('readonly',true);
            $('[name="item_quant[]"]').eq(ind).val(item_data[5]);
            $('[name="now_rate[]"]').eq(ind).focus();
        }
        $('[name="item_name[]"]').eq(ind).val(item_data[2]);
        $('[name="curr_rate"]').eq(ind).val(item_data[3]);
        $('[name="curr_make"]').eq(ind).val(item_data[4]);
        $('[name="curr_avail"]').eq(ind).val(item_data[5]);
        $($(this).attr('href')).remove();
    });

    $("#mode").change(function(){
        $("#medium").val("");
        var mode_val = $(this).val();
        if(mode_val!=""){
            $("#medium").prop('disabled',false);
            var alt_mode = (mode_val=='off')?'on':'off';
            $('.'+mode_val).show();
            $('.'+alt_mode).hide();
        }else{
            $("#medium").prop('disabled',true);
            $("#amount").prop('disabled',true);
            $("#amount").val('');
            $("#remain").val('');
            $(".on, .off").hide();
        }
    });
    $("#more_pay_mode").change(function(){
        $("#more_pay_medium").val("");
        var mode_val = $(this).val();
        if(mode_val!=""){
            $("#more_pay_medium").prop('disabled',false);
            var alt_mode = (mode_val=='off')?'on':'off';
            $('.'+mode_val).show();
            $('.'+alt_mode).hide();
        }else{
            $("#more_pay_medium").prop('disabled',true);
            $("#more_pay_amount").prop('disabled',true);
            $("#more_pay_amount").val('');
            $(".on, .off").hide();
        }
    });
    $("#medium").change(function(){
        var apply = ($(this).val()=="")?true:false;
        $("#amount").prop('readonly',apply);
        if(apply){
            $("#amount").addClass('readonly');
            $("#amount").val('');
            $("#remain").val('');
        }else{
            $("#amount").removeClass('readonly');
        }
    });
    $("#more_pay_medium").change(function(){
        var apply = ($(this).val()=="")?true:false;
        $("#more_pay_amount").prop('readonly',apply);
        if(apply){
            $("#more_pay_amount").addClass('readonly');
            $("#more_pay_amount").val('');
        }else{
            $("#more_pay_amount").removeClass('readonly');
        }
    });
    $("#more_pay_ok").click(function(){
        var mode = $("#more_pay_mode").val();
        var medium = $("#more_pay_medium").val();
        $("#more_pay_amount").removeClass('is-invalid');
        if(mode =="" && medium==""){
            toastr.error("Recheck the <b>Mode / Medium</b> !");
        }else{
            var rs = $("#more_pay_amount").val();
            var total = rs;
            var pre_rs_count = $(document).find('[name="more_amount[]"]').length;
            if(pre_rs_count > 0){
                $(document).find('[name="more_amount[]"]').each(function(){
                    total= +total + +$(this).val();
                });
            }
            var rm = $("#more_pay_remains").val();
            if(rm > 0){
                if(rs!="" && rs!=0){
                    if(rm-total < 0 ){
                        toastr.error("You are <b>Paying Extra</b> !");
                        $("#more_pay_amount").addClass('is-invalid');
                    }else{
                        var input = '<div class="row more_added_pay" >';
                        input += '<input type="hidden" name="more_mode[]" value="'+mode+'" class="w-100 form-control col-5 " readonly style="font-weight:bold;">';
                        input += '<input type="text" name="more_medium[]" value="'+medium+'" class="w-100 form-control col-5 " readonly style="font-weight:bold;">';
                        input += '<input type="text" name="more_amount[]" value="'+rs+'"  class="w-100 form-control col-5 " readonly>';
                        input +='<button class="w-100 form-control col-2 remove_more_add_row text-danger border-danger" type="button" >&cross;</button>';
                        input += '</div>';
                        
                        $('#more_pay_form_field').append(input);
                        $('.more_pay_ok_field').show();

                        $('.more_pay_input').val('');
                    }
                }else{
                    toastr.error("Enter the <b>Valid Amount</b> !");
                }
            }else{
                toastr.error("No Balance <b>Remains to Pay</b> !");
            }
        }
    });
    $(document).on('click','.remove_more_add_row',function(e){
        $(this).parent('div.row').remove();
        var opt_count = $(document).find('[name="more_amount[]"]').length;
        if(opt_count==0){
            $('.more_pay_ok_field').hide();
        }
    });
    $("#more_pay_done").click(function(e){
        var html="";
        var total = "";
        var remain = $("#remain").val();
        var opt_count = $(document).find('[name="more_amount[]"]').length;
        if(opt_count > 0){
            $(document).find(".more_added_pay").each(function(i,v){
                var mode = $(this).find('[name="more_mode[]"]').val();
                var mode_arr = {'on':'Online','off':'Offline'};
                var medium = $(this).find('[name="more_medium[]"]').val();
                var amount = $(this).find('[name="more_amount[]"]').val();
                total = +total + +amount;
                html = '<div class="row mb-2 more_payment_row" style="">';
    
                html+='<div class="col-md-3"><div class="input-group input-group-sm">';
                html+=      '<div class="input-group-prepend">';
                html+=          '<label class="input-group-text" id="inputGroup-sizing-sm">Mode</label>';
                html+=      '</div>';
                html+=      '<input type="hidden" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="mode[]" value="'+mode+'" readonly >';
                html+=      '<label class="form-control" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" readonly>'+mode_arr[mode]+'</lable>';
                html+= '</div></div>';
    
                html+='<div class="col-md-3"><div class="input-group input-group-sm">';
                html+=      '<div class="input-group-prepend">';
                html+=          '<label class="input-group-text" id="inputGroup-sizing-sm">Medium</label>';
                html+=      '</div>';
                html+=      '<input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="medium[]" value="'+medium+'" readonly >';
                
                html+= '</div></div>';
                
    
                html+='<div class="col-md-3"><div class="input-group input-group-sm">';
                html+=      '<div class="input-group-prepend">';
                html+=          '<label class="input-group-text" id="inputGroup-sizing-sm">Amount</label>';
                html+=      '</div>';
                html+=      '<input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="amount[]" value="'+amount+'" readonly >';
                html+= '</div></div>';
    
                html+='<div class="col-md-3 text-left"><button type="button" class="btn btn-outline-danger remove_pay_option">&cross;</button></div>';
    
                html+='</div>';
                $("#pre_pay_option").after(html);
                $("#more_pay_modal").modal('hide');
            });
            $("#remain").val(remain-total);
            if(remain-total == 0){
                $("#bill_more_pay").prop('disabled',true);
            }
        }else{
            toastr.error("Provide The Payment First !");
        }

    });
    $('#more_pay_modal').on('shown.bs.modal', function (e) {
        $("#more_pay_remains").val($("#remain").val());
    });
    $('#more_pay_modal').on('hidden.bs.modal', function () {
        $(".more_pay_ok_field").hide();
        $("#more_pay_form_field").empty();
    });
    $(document).on("click",".remove_pay_option",function(){
        var count = $(this).parent('div').parent('div').index('.more_payment_row');
        var rmns = $("#remain").val();
        var amnt = $(document).find('[name="amount[]"]').eq(count+1).val();
        var new_rmns = +rmns + +amnt;
        if(new_rmns > rmns){
            $("#remain").val(new_rmns);
            $(document).find('.more_payment_row').eq(count).remove();
        }
    });

    $("#gst_type").change(function(e){
        if($(this).val()!=''){ 
            $('#total_gst').prop('readonly',false); 
            $('#total_gst').select();
        }else{
            $('#total_gst').prop('readonly',true);
            $('#total_gst').val(0);
        }
    });

    function sethsngst(){
        var hsn_option = $("#hsn").find('option:selected');
        const gst  = hsn_option.data('target');
        $("[name='gst_set']").val(gst)
        $("#hsn_gst").empty().text('('+gst+')');
    }
    /*function calculatetotal(){
        var sub_total = 0;
        var sub_cost = 0;
        var sub_make = 0;
        var sub_quant = 0;
        var sub_wght = 0;
        var err = false;
        $(document).find('.toast.toast-error').remove();
        $('tbody>tr').each(function(i,v){
            if($('[name="item_name[]"]').eq(i).val()!=""){

                var wght = ($('[name="item_wght[]"]').eq(i).val()!="")?$('[name="item_wght[]"]').eq(i).val():0;
                
                var count = ($('[name="item_count[]"]').eq(i).val()!="")?$('[name="item_count[]"]').eq(i).val():0;
                var lbr = ($('[name="item_chrg[]"]').eq(i).val()!="")?$('[name="item_chrg[]"]').eq(i).val():0;
                
                

                var quant = ($('[name="type[]"]').eq(i).val()=='Loose')?wght:count;
                
                
                var match = $('[name="curr_avail"]').eq(i).val();
                
                var ele = ($('[name="type[]"]').eq(i).val()=='Loose')?$('[name="item_wght[]"]').eq(i):$('[name="item_count[]"]').eq(i);
                
                var total = 0;
                var cost = 0;
                var lbrc = 0;
                
                if((match-quant)<0){
                    wght = count = lbr = 0;
                    ele.addClass('is-invalid');
                    $('[name="item_cost[]"]').eq(i).val("");
                    $('[name="item_chrg[]"]').eq(i).val("");
                    $('[name="item_total[]"]').eq(i).val("");

                    $("[name='total_wght']").val("");
                    $("[name='total_count']").val("");
                    $("[name='total_cost']").val("");
                    $("[name='total_make']").val("");
                    $("[name='total_sum']").val("");

                    err = "Recheck The Quntity !";
                }else{
                    ele.removeClass('is-invalid');

                    cost = quant*$('[name="curr_rate"]').eq(i).val();
                    lbrc = quant*$('[name="curr_make"]').eq(i).val();
                    total = cost+lbrc;
                    
                    $('[name="item_cost[]"]').eq(i).val(cost);
                    $('[name="item_chrg[]"]').eq(i).val(lbrc);
                    $('[name="item_total[]"]').eq(i).val(total);
                }
                
                sub_cost = +sub_cost + +cost;
                sub_wght =  +sub_wght + +wght;
                sub_quant =  +sub_quant + +count;
                sub_make = +sub_make + +lbrc;
                sub_total +=total;
            }
        });
        $("[name='total_wght']").val(sub_wght);
        $("[name='total_count']").val(sub_quant);
        $("[name='total_cost']").val(sub_cost);
        $("[name='total_make']").val(sub_make);
        $("[name='total_sum']").val(sub_total);
        $("[name='total_sub']").val(sub_total);
        var final_total = 0;
        var gst_type = $("#gst_type").val();
        var gst_val = (gst_type=="")?0:(($("#total_gst").val()!="")?$("#total_gst").val():0);
        
        final_total+= sub_total+(sub_total*gst_val)/100;
        
        var dis_val = ($("#total_disc").val()!="")?$("#total_disc").val():0;
        
        final_total = final_total-(final_total*dis_val)/100;
        
        $("[name='total_final']").val(parseFloat(final_total).toFixed(3));
        (err)?toastr.error(err):null;
    }*/
    function calculatetotal(){
        var sum = 0;
        var err = false;
        $(document).find('.toast.toast-error').remove();
        $('tbody>tr').each(function(i,v){
            if($('[name="item_name[]"]').eq(i).val()!=""){
                var apply_rate = 0;
                if($('[name="now_rate[]"]').eq(i).val()){
                    apply_rate = $('[name="now_rate[]"]').eq(i).val();
                }else{
                    if($('[name="curr_rate"]').eq(i).val()){
                        apply_rate = $('[name="curr_rate"]').eq(i).val();
                    }
                }
                var item_qunt = $('[name="item_quant[]"]').eq(i).val()??0;
                var item_chrg = $('[name="item_chrg[]"]').eq(i).val()??0;
                var cost = (+apply_rate * +item_qunt)+ +item_chrg;
                $("[name='item_total[]']").eq(i).val(cost);
                sum += +cost;
            }
        });
        $("[name='total_sum'],[name='total_sub']").val(sum);
        var gst_val = $("[name='gst_set']").val();
        var gst = (sum*gst_val)/100;
        $("[name='gst_val']").val(gst);
        var dis_val = $("[name='total_disc']").val();
        var with_gst = +sum + +gst;
        var with_gst_dic =  +with_gst - +((with_gst*dis_val)/100);
        $("[name='total_final']").val(with_gst_dic);
        var paid = 0;
        $('[name="amount[]"]').each(function(i,v){
            if($('[name="mode[]"]').eq(i).val()!="" && $('[name="medium[]"]').eq(i).val()!=""){
                paid+= +$('[name="amount[]"]').eq(i).val()??0;
            }
        });
        var remain = with_gst_dic - paid;
        if(remain<0){
            $('[name="remain"]').val("");
            toastr.error("You are Paying Extra !");
        }else{
            $('[name="remain"]').val(remain);
        }
    }
    function validalance(){
        $(document).find('.toast.toast-error').remove();
        $("#amount").removeClass('is-invalid');
        if($("#amount").val()!=""){
            var go_ahead = true;
            var apply = $("#amount").val()??0;
            var able = $("#total_final").val()??0;
            if($("#mode").val()=='off' && $("#medium").val()=='Scheme'){
                var avail = $("#scheme_balance").text();
                go_ahead = (+apply > +avail)?false:true;
                if(!go_ahead){
                    $("#amount").addClass('is-invalid');
                    toastr.error("No Enough Balance at SCHEME !");
                    $("#remain").val(able);
                }
            }
            if(go_ahead){
                if(able-apply < 0){
                    $("#amount").addClass('is-invalid');
                    toastr.error("You are Paying EXTRA !");
                    $("#remain").val("");
                }else{
                    $("#remain").val(able- apply);
                    var more_opt = (able-apply > 0 )?false:true;
                    $("#bill_more_pay").prop('disabled',more_opt);
                }
            }
        }
    }

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

    function checksum(){
        var paid = 0
        var remains = $("#remain").val();
        var final = $("#total_final").val();
        $.each($(document).find('[name="amount[]"]'),function(){
            paid += +$(this).val();
        });
        return ((final-paid)==remains)?true:false;
    }
    </script>

  @endsection

