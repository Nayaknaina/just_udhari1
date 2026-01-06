  @extends('layouts.vendors.app')
  @section('stylesheet')

    <link rel="stylesheet" href="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/ui/trumbowyg.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/colors/ui/trumbowyg.colors.css') }}">

  @endsection
  @section('content')

  @php

  //$data = component_array('breadcrumb' , 'New Sell Bill',[['title' => 'Sell Bill']] ) ;
  $vendr_state =  app('userd')->shopbranch->state;
  @endphp

  {{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('sells.index').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All</a>'];
$path = ["Sell Bills"=>route('sells.index')];
$data = new_component_array('newbreadcrumb',"New Sell Bill",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
  <style>
        ul#custo_list,ul#item_list {
            position: absolute;
            list-style: none; /* Remove default bullet points */
            padding: 0;
            z-index: 1;
            box-shadow: 1px 2px 3px gray;
            justify-content: space-around; /* Space out the items inside the UL */
            height:auto;
            max-height:50vh;
			overflow-y:scroll;
        }
        th{
            vertical-align:middle!important;
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
        .item_info:disabled,.item_info.readonly,.ele_content.readonly{
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
        }
        .ele_count,.ele_caret{
            position:relative;
            text-align:center;
        }
        .ele_caret::after,.ele_count::after{
            position:absolute;
            bottom:0px;
            font-size:10px;
            color:#11489d;
            text-shadow: 1px 2px 5px;
        }
        .ele_caret::after{
            content:"Caret";
        }
        .ele_count::after{
            content:"Count";
        }
        .unit.deactive{
            display:none;
        }
        div.disabled{
            opacity:0.5;
            z-index:0;
            pointer-events: none;
        }
        .pre_info_div{
            width:65%!important;
            background: black;
            color: white;
            position: absolute;
            z-index: 1;
            right: 0;
            top: 80%;
            padding:0 2px;
            display:none;
        }
        .info_block,.info_block:disabled{
            color:white;
            background:transparent!important;
            border:none!important;
            padding:0 1px!important;
            font-weight:bold;
            height:unset!important;
            cursor: auto;
        }
        .pre_info_div > div >.input-group-prepend{
            padding:0 4px 0 0;
            position: relative;
        }
        /* .pre_info_div > div >.input-group-prepend:after{
            content:"-";
            position: absolute;
            right:-5px;
        } */
        span.info_block.label:after{
            content:"-";
            position: absolute;
            right:-5px;
        }
        h5.info_block.curr_cate{
            text-align:center;
            border-bottom:1px dashed lightgray!important;
        }
        span.rate_caret{
            float: right;
            margin-top: -20px;
            font-size: 10px;
            color: blue;
            text-shadow: 1px 2px 3px;
            display:none;
        }
        .gst_sel_block{
            //text-shadow: 1px 1px 1px white;
        }
        #gst_block.deactive{
            opacity: 0.2;
            pointer-events: none;
        }
        .gst_set_label{
            font-weight:normal!important;
        }
        label.gst_set_label.checked{
            font-weight:bold!important;
            text-shadow: 1px 1px 2px gray;
        }
        label.is-invalid{
            border:1px solid red;
        }
        a.pre_info_div_close{
            position:absolute;
            top:0;
            right:0;
            padding:0 5px;
            border:1px solid white;
            color:white;
        }
        a.pre_info_div_close:hover{
            background:white;
            color:red;
            border:1px solid red;
        }
        #new_custo_btn{
            position:absolute;
            top:5%;
            right:1%;
            border-radius:50%;
            margin:0;
        }
        #custo_info_row{
            box-shadow: rgba(17, 17, 26, 0.39) 0px 1px 0px, rgba(17, 17, 26, 0.89) 0px 0px 8px;
            border:1px solid   #ff6c00;
        }
		#rs_unit:after{
            content:"₹";
            position:absolute;
            top:0;
            right:1%;
            height: 100%;
            align-content: center;
            font-size: 150%;
            color: blue;
            z-index: 3;
        }
    </style>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row justify-content-center">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <!--<div class="card-header">
                            <h3 class="card-title"><x-back-button />  Create Sell Bill</h3>
                        </div>-->
                        <div class="card-body bg-white">
                            <form id = "submitForm" method="POST" action="{{ route('sells.store')}}" class = "myForm" enctype="" autocomplete="off">
                                @csrf
                                @method('post')
								<div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                                    @php 
                                        $states = states();
                                    @endphp
                                    <!---Bill Info Form Element---->
                                    <div class="input-group mb-1 col-md-2 p-0">
                                        <div class="input-group-prepend">
                                            <label for="bill_num" class="input-group-text mb-0 px-1">Bill No.</label>
                                        </div>
                                        <input type="text" name="bill_num" id="bill_num" class="form-control text-center px-1" placeholder="Bill Number" value="{{ justbillsequence() }}">
                                    </div>
                                    <div class="input-group mb-1 col-md-2 p-0">
                                        <div class="input-group-prepend">
                                            <label for="bill_num" class="input-group-text mb-0 px-1">Date</label>
                                        </div>
                                        <input type="date" name="bill_date" id="bill_date" class="form-control text-center px-1" placeholder="Bill Ddate" value="{{ date('Y-m-d', strtotime('now')) }}" required>
                                    </div>
                                    <div class="input-group mb-1 col-md-2 p-0">
                                        <div class="input-group-prepend">
                                            <label for="vndr_gst" class="input-group-text mb-0 px-1">GSTIN</label>
                                        </div>
                                        <input type="text" name="vndr_gst" id="vndr_gst" class="form-control text-center px-1" placeholder="GST Number" value="{{ app('userd')->shopbranch->gst_num }}" required="" readonly="">
                                    </div>
                                    <div class="input-group mb-1 col-md-2 p-0">
                                        <div class="input-group-prepend">
                                            <label for="hsn" class="input-group-text mb-0 px-1">HSN Code</label>
                                        </div>
                                        <select name="hsn" id="hsn" class="form-control text-center px-1" required="" onchange="sethsngst();">
                                            {{ justbillhsn(true); }}
                                        </select>
                                    </div>
                                    <div class="form-group mb-1 col-md-4 p-0">
                                        <select name="vndr_state" id="vndr_state" class="form-control text-center px-1" required="">
                                            <option value="">State / Code</option>
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
                                <div id="custo_info_row" class="row mb-2  p-2" >
                                    <label  class="bg-light h-auto px-1 mb-1 col-md-2 p-0" style="box-shadow: 1px 2px 3px black;border:1px dashed blue;">
                                        <label for="gst_yes" class="text-success mb-0 gst_set_label">
                                            <input type="radio" name="gst_apply" value="yes" id="gst_yes" class="" required > GST Bill
                                        </label>
                                        <label for="gst_no" class="text-danger mb-0 gst_set_label">
                                            <input type="radio" name="gst_apply" value="no" id="gst_no" class="" required> Rough Estimate
                                        </label>
                                    </label>
                                    <label for="to" class="col-md-2 p-0">
                                        <div class="input-group">
                                            <select class="form-control text-center" name="to" style="font-weight:bold;color:#044fdd;" required id="to">
                                                <option value="">Category ?</option>
                                                <option value="R" >Retail</option>
                                                <option value="W" >Whole Sell</option>
                                            </select>
                                            <div class="input-group-prepend" style="border-radius:0 15px 15px 0;overflow:hidden;">
                                                <label class="input-group-text h-auto bg-dark" >Bill</label>
                                            </div>
                                        </div>
                                    </label>
                                    <div class="form-group col-md-4 p-0 mb-1">
                                        <input type="hidden" name="exist" value="yes" disabled id="is_exist">
                                        <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="&#9906; Name/Mobile"  required >
                                            <button  id="new_custo_btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#custo_modal">
                                                    <i class="fa fa-plus"></i>
                                            </button>
                                        <ul class="col-12" id="custo_list" style="display:none;"></ul>
                                    </div>
                                    <div class="form-group col-md-2 p-0  mb-1">
                                        <input type="text" name="custo_mobile" id="custo_mobile" class="form-control" placeholder="Mobile"  oninput="digitonly(event,10);" onchange="digitonly(event,10);" required readonly>
                                    </div>
                                    <div class="form-group col-md-2 p-0  mb-1">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text px-1" id="basic-addon1">Balance</span>
                                            </div>
                                            <label id="scheme_balance" class="text-center text-info form-control px-1 text-center" style="border-radius:0 15px 15px 0;overflow:hidden;">NA</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">

                                    <div class="table-responsive">
                                        <table id="CsTable" class="table table_theme table-bordered table-stripped dataTable">
                                            <thead class="border-none">
                                                <tr>
                                                    <th width="5%" rowspan="2">S.N.</th>
                                                    <th width="30%" rowspan="2">ITEM</th>
                                                    <th width="10%" rowspan="2">Quanity/Weight</th>
                                                    <th  width="20%" colspan="2">
                                                        MAKING CHARGE
                                                    </th>
                                                    <th width="15%" rowspan="2">RATE</th>
                                                    <th  width="15%" rowspan="2">TOTAL</th>
                                                    <th width="5%" rowspan="2">&cross;</th>
                                                </tr>
                                                <tr>
                                                    <th width="10%">%</th>
                                                    <th width="10%">Rs/Grm</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i=0;$i<=5;$i++)
                                                <tr id="item_{{ $i }}" class="item_row">
                                                    <td class="text-center">{{ $i+1  }}</td>
                                                    <td>
                                                        <div class="col-12 p-0">
															<input type="hidden" name="code[]" value="">
                                                            <input type="hidden" name="type[]" value="">
                                                            <input type="hidden" name="caret[]" value="">
                                                            <input type="hidden" name="id[]" value="">
                                                            <input type="hidden" name="source[]" value="">
                                                            <input type="text" name="item_name[]" class="form-control w-100 item_info  px-1 " placeholder="Item Name" >
                                                            <!-- <input type="text" name="item_name[]" class="form-control w-100 item_info" placeholder="Item Name" onkeyup="getitem($(this))"> -->
                                                            
                                                            <div class="pre_info_div" >
                                                                <h5 class="info_block curr_cate text-center"></h5>
                                                                <a href="javascript:void(null);" onclick="$(this).closest('div.pre_info_div').hide();" class="pre_info_div_close">x</a>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text info_block label" id="inputGroup-sizing-sm">RATE </span>
                                                                    </div>
                                                                    <input type="text" class="form-control info_block text-right" aria-label="Small" name="curr_rate" disabled>
                                                                    <span class="input-group-text info_block" id="basic-addon2">Rs.</span>
                                                                </div>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text info_block label" id="inputGroup-sizing-sm">CHRG </span>
                                                                    </div>
                                                                    <input type="text" class="form-control info_block  text-right" aria-label="Small"  name="curr_make" disabled>
                                                                    <span class="input-group-text info_block" id="basic-addon2">Rs.</span>
                                                                </div>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text info_block label" id="inputGroup-sizing-sm">AVAIL </span>
                                                                    </div>
                                                                    <input type="text" class="form-control info_block  text-right" aria-label="Small" name="curr_avail" disabled>
                                                                </div>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text info_block label" id="inputGroup-sizing-sm">CARET </span>
                                                                    </div>
                                                                    <input type="text" class="form-control info_block  text-right" aria-label="Small" name="curr_caret" disabled>
                                                                </div>
                                                                <div class="input-group input-group-sm">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text info_block label" id="inputGroup-sizing-sm">FINE </span>
                                                                    </div>
                                                                    <input type="text" class="form-control info_block text-right" aria-label="Small" name="curr_fine" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="position:relative;">
                                                        <input type="text" name="item_quant[]" class="form-control w-100 item_info  text-right  px-1" placeholder="Quantity" oninput="calculatetotal()">
                                                        <span class="unit deactive">Grm</span>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="chrg_perc[]" class="form-control w-100 item_info  text-right  px-1 lbr_chrg_perc" placeholder="Charge %"  oninput="togglecharge($(this),'item_chrg');calculatetotal()" data-target="lbr_chrg_rs"  id="charge_perc_{{  $i }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="item_chrg[]" class="form-control w-100 item_info  text-right  px-1 lbr_chrg_rs" placeholder="Charge Rs."  oninput="togglecharge($(this),'chrg_perc');calculatetotal()" data-target="lbr_chrg_perc" id="charge_rs_{{  $i }}">
                                                    </td>
                                                    <td class="bg-light text-dark">
                                                        <input type="text" name="now_rate[]" class="form-control item_info text-center px-1" value="" id=""  placeholder="Applicable" oninput="lbrconverttoggle($(this));calculatetotal()">
                                                        <span class="rate_caret"></span>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="item_total[]" class="form-control w-100 item_info  text-right  px-1" placeholder="Total"  readonly {{ ($i==0)?'required':'' }}>
                                                    </td>
                                                    <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger item_tr_remove">✗</button>
                                                    </td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                            <tfoot>
                                                <!--<tr id="dummy">
                                                    <td colspan="8">
                                                        <p style="padding:5%"></p>
                                                    </td>
                                                </tr>-->
                                                <tr class="bg-light">
                                                    <td class="text-center" style="vertical-align:middle;">
                                                        <a href="javascript:void(null);" class="btn btn-warning" style="color:maroon;" id="add_rows">
                                                            <li class="fa fa-caret-up w-100">
                                                            <span class="fa fa-plus-circle w-100"></span>
                                                            </li>
                                                        </a>
                                                    </td>
                                                    <td colspan="4" >
                                                        
                                                    </td>
                                                    <td style="text-align:center;vertical-align:middle;">
                                                        <b style="">SUM</b>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_sum" class="form-control item_info readonly  text-right  px-1" value="" id="" readonly required style="font-weight:bold;">
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                                    <div class="col-md-2 col-12 mb-3 p-1">
                                        <input type="text" name="total_sub" class="form-control readonly text-right" value="0" id="" required style="font-weight:bold;"  readonly>
                                    </div>
                                    <div class="col-md-3 col-12  p-1">
                                        <!--<div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="basic-addon1">Disc%</label>
                                            </div>
                                            <input type="text" name="total_disc" class="form-control readonly text-right" value="0" id="total_disc" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()">
                                        </div>-->
										<div class="input-group mb-3" id="rs_unit">
											<div class="input-group-prepend">
												<label class="input-group-text" id="basic-addon1">Disc%</label>
											</div>
											<input type="text" name="total_disc" class="form-control readonly text-right" value="0" id="total_disc" required style="font-weight:bold;width:25%;" onfocus="$(this).select()" oninput="calculatetotal()" placeholder="%" >
											<input type="text" name="total_disc_amnt" class="form-control readonly text-right" value="0" id="total_disc_amnt" required style="font-weight:bold;width:35%;padding-right:20px;" onfocus="$(this).select()" oninput="convertpercentage()" >
										</div>
                                    </div>
									
                                    <div class="col-md-2 col-12  p-1" id="gst_block">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text px-1" id="basic-addon1">GST<span id="hsn_gst"></span>%</label>
                                            </div>
											<input type="hidden" name="gst_set" value="{{ @$gst['val'] }}">
											<input type="text" name="gst_val" class="form-control  readonly text-center  px-1" value="{{ @$gst['amnt'] }}" id="gst_val" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()" readonly>
												{{--<input type="hidden" name="gst_set" value="">
                                            <input type="text" name="gst_val" class="form-control  readonly text-center  px-1" value="0" id="gst_val" required style="font-weight:bold;" onfocus="$(this).select()"  oninput="calculatetotal()" readonly>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text  px-1" id="basic-addon1">RoundOff</label>
                                            </div>
                                            <input type="text" name="round" class="form-control readonly text-center  px-1" value="0" id="round" required="" style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12  p-1">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="basic-addon1">Total</label>
                                            </div>
                                            <input type="hidden" name="inwords" id="inwords" value="">
                                            <input type="text" name="total_final" class="form-control readonly text-right" value="0" id="total_final" required style="font-weight:bold;" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4 pt-3" style="border:1px dashed black;position:relative;" id="payment_block">
                                    <div class="col-md-6 col-12 form-group p-1">
                                        <label for="bank">Bank Name</label>
                                        <input type="text" name="payment[name]" class="form-control readonly " value="" id="bank" style="font-weight:bold;" placeholder="Bank Name">
                                    </div>
                                    <div class="col-md-4 col-12 form-group  p-1">
                                        <label for="check">Check Number</label>
                                        <input type="text" name="payment[check]" class="form-control readonly " value="" id="check" style="font-weight:bold;" placeholder="Check Number">
                                    </div>
                                    <div class="col-md-2 col-12 form-group p-1">
                                        <label for="cash">Cash</label>
                                        <input type="text" name="payment[cash]" class="form-control readonly " value="" id="cash" style="font-weight:bold;" placeholder="Cash Amount" oninput="calculatetotal()">
                                    </div>
                                    <!-- <div class="col-md-2 col-12 form-group p-1">
                                        <label for="remain">Balance</label>
                                        <input type="text" name="remain" class="form-control readonly text-center" value="" id="remain" style="font-weight:bold;" placeholder="Balance Amount">
                                    </div> -->
                                </div>
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;" id="pre_pay_option">
                                    <div class="col-md-3 col-12 form-group">
                                        <div class="form-group">
                                            <label for="mode">Pay Mode</label>
                                            <select class="form-control" name="mode[]" id="mode" oninput="validalance()">
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
                                        <select class="form-control" name="medium[]" id="medium" oninput="validalance()" disabled>
                                            <option value="" >Choose</option>
                                            @foreach($medium_arr as $key=>$value)
                                                <option value="{{ $value }}" class="{{ $$value??'on' }}" style="display:none;">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12 form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control readonly " name="amount[]" id="amount" placeholder="Enter Amount !" readonly oninput="validalance()" onkeyup="validalance()">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#more_pay_modal" disabled id="bill_more_pay"><li class="fa fa-plus"></li></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label form="remain">Remains</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="remain" class="form-control text-center" readonly id="remain" required placeholder="Remains Balance">
                                            <span class="input-group-text">
                                                <b>Rs.</b>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4 pt-3" style="border-bottom:1px solid gray;position:relative;" id="declare_block">
                                    <div class="col-12 form-group p-1">
                                        <label for="declr">Declaration/Desclaimer</label>
                                        <textarea class="form-control" name="declr" id="declr" ><ul><li>Subjected to {{ districts("",app('userd')->shopbranch->district) }} Jurisdiction only</li><li>E & O.E</li></ul></textarea>
                                    </div>
                                </div>
                                <div class="row mb-2" style="">
                                    <div class="col-12 text-center form-group">
                                        <button type="submit" name="make" value="bill" class="btn btn-danger">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    @include('vendors.commonpages.newcustomerdetailed')
  @endsection

  @section('javascript')
  <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/trumbowyg.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/langs/fr.min.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/base64/trumbowyg.base64.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/colors/trumbowyg.colors.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/noembed/trumbowyg.noembed.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/pasteimage/trumbowyg.pasteimage.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/template/trumbowyg.template.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/preformatted/trumbowyg.preformatted.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/ruby/trumbowyg.ruby.js') }}"></script>
    <script src="{{ asset('assets/minimal_editor/trumbowyg-2.28.0/dist/plugins/upload/trumbowyg.upload.js') }}"></script>

<script>
    $('#declr').trumbowyg().on('tbwinit tbwfocus tbwblur tbwchange tbwresize tbwpaste tbwopenfullscreen  tbwclosefullscreen tbwclose', function(e){
                console.log(e.type);
    });
</script>
<script>

    $(document).ready(function() {
        
		 $("#custo_plus_form").submit(function(e){
                e.preventDefault();
                $('.help-block').empty();
                $('.custo').removeClass('invalid');
                const path = $(this).attr('action');
                const fd = new FormData(this) ;
                //var formData = new FormData(this); 
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: fd,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                    $('.btn').prop("disabled", true);
                    $('#loader').removeClass('hidden');
                    },
                    success: function(response) {
                        $('.btn').prop("disabled", false);
                        $('#loader').addClass('hidden');
                        if(response.success){
                            $("#custo_modal").modal('hide');
                            $("#custo_plus_form").trigger('reset');
                            success_sweettoatr(response.success);
                            if(response.data){
                                $("#is_exist").prop('disabled',false);
                                $('[name="custo_name"]').val(response.data.custo_full_name);
                                $('[name="custo_mobile"]').val(response.data.custo_fone);
                                $("#bottom_block").show();
                            }
                        }else{
                            if(typeof response.errors !='string'){
                                $.each(response.errors,function(i,v){
                                    $('[name="'+i+'"]').addClass('is-invalid');
                                    $.each(v,function(ind,val){
                                        toastr.error(val);
                                    });
                                });
                            }else{
                                toastr.error(response.errors);
                            }
                        }
                    },
                    error: function(response) {
                        
                    }
                });
            });
		
        $("#submitForm").trigger('reset');
        
        sethsngst();

        $('[name="gst_apply"]').on('change',function(e){
            var sel = $('[name="gst_apply"]:checked').val();
            $('label.gst_set_label').removeClass('checked');
            $(this).parent('label').parent('label').removeClass('is-invalid');
            switch(sel){
                case 'no':
                    $("#gst_block").addClass('deactive');
                    break;
                case 'yes':
                    $("#gst_block").removeClass('deactive');
                    break;
                default:
                    $("#gst_block").removeClass('deactive');
                    break;
            }
            $(this).parent('label').addClass('checked');
            calculatetotal();
        });
        
        
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
									if (Array.isArray(v)) {
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
										toastr.error(v);
									}
                                    
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
            $(this).find('span').hide();
            if($(this).find('input').attr('name')!="item_total[]"){
                $(this).find('input').removeClass("readonly").prop("readonly",false);
            }
        });
        tr.find('td').find('div.pre_info_div').hide();
        $('tbody').append(tr);

    });

    /*$("#add_rows").click(function(e){
        e.preventDefault();
        const trs = $('tbody>tr');
        var tr = trs.eq(0).clone();
        var num = trs.length;
        tr.attr('id','item_'+num);
        tr.find('td').eq(0).text(num+1);
        $(tr.find('td')).each(function(i,v){
            $(this).find('input').val("");
        });
        $(tr.find('td>[name="item_quant[]"]')).removeClass('readonly').prop('readonly',false);
        $('tbody').append(tr);

    });*/
    
    $(document).on('click','.item_tr_remove',function(){
        const ttl =  $(document).find('tbody>tr.item_row').length;
        var num = $($(document).find('tbody>tr.item_row')).index($(this).closest('tr.item_row'));
        if(ttl>1){
            $(document).find('tbody>tr.element_'+num).remove();
            $(document).find('tbody>tr.item_row').eq(num).remove();
            $(document).find('tbody>tr.item_row').each(function(i,v){
                $(this).find('td').eq(0).empty().text(i+1);
            });
            calculatetotal();
        }
    });
    let typingTimer;
    const typingInterval = 200;
    
    $("#custo_name").keyup(function(){
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
                                        existingcustomer()
                                        }, typingInterval);
    });

    $(document).on('keyup','[name="item_name[]"]',function(e){
        clearTimeout(typingTimer);
        var item_name = $(this);
        typingTimer = setTimeout(() => {
                                        getitem(item_name,e)
                                        }, typingInterval);
    });

    
    $(document).on('focus','[name="item_name[]"]',function(e){
        $(this).select();
        var ind =  $($(document).find('[name="item_name[]"]')).index($(this));
        if(ind > 0){
            var new_ind = ind-1;
            if($('[name="item_name[]"]').eq(new_ind).val()==""){
                $('[name="item_name[]"]').eq(new_ind).focus();
            }
        }
    });

    $(document).on('focus','.item_info',function(){
        var ind =  $($(document).find('tbody>tr.item_row')).index($(this).closest('tr.item_row'));
        $('.pre_info_div').hide();
        if($('[name="item_name[]"]').eq(ind).val()!="") {
            $('.pre_info_div').eq(ind).show();
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
	
	var field_focus = true;
    function getitem(input,event){
		
		var values = $("input[name='code[]']").map(function() {
            return  $(this).val().split('~');
        }).get();
        var filteredValues = values.filter(function(value) {
            return value.trim() !== "";
        });
		if(!filteredValues.includes(input.val().trim())){
			var ind = input.closest('tr.item_row').index();
			
			// alert(response.stock);
			$('[name="code[]"]').eq(ind).val("");
			$('[name="id[]"]').eq(ind).val("");
			$('[name="type[]"]').eq(ind).val("");
			$('[name="source[]"]').eq(ind).val("");
			$('[name="curr_rate"]').eq(ind).val("");
			$('[name="curr_make"]').eq(ind).val("");
			$('[name="curr_avail"]').eq(ind).val("");
			$(document).find('ul#item_list').remove();
			$.get("{{ route('sells.item') }}","item="+input.val(),function(response){
				if(response.data.length > 0){
					var html = "<ul  id='item_list' class='w-100 item_list'>";
					$.each(response.data,function(ind,row){
						var row_data = JSON.stringify(row).replace(/'/g,"&apos;");
						html+='<li class="form-control h-auto">';
						html+='<a href="#item_list" data-object=\''+(row_data)+'\' class="item_target">';
						html+=row.name+'<i>('+(row.type)+')</i><hr class="m-1">';
						html+='<b>('+(row.location)+')</b>';
						html+='</a>';
						html+='</li>';
					});
					html+="</ul>";
					input.parent('div').append(html);
					let tr_ind = $('tbody>tr').index(event.target.closest('tr'));
					
					const tr_count = $('tbody>tr').length;
					if((+tr_count-2)==tr_ind){
						$("#add_rows").trigger('click');
					}
					if(response.data.length==1){
						$($(document).find("ul#item_list > li > a").eq(0)).trigger('click');
						//let tr_ind = $('tbody>tr').index(event.target.closest('tr'));
						$(document).find('tbody>tr').eq(tr_ind+1).find('td').eq(1).find('input[name="item_name[]"]').focus();
						field_focus = false;
					}else{
						field_focus = true;
					}
				}else{
					event.target.select();
					$(document).find('ul#item_list').remove();
					toastr.error("Item Not Found !");
				}
				if(event.key==13){
					event.preventDefault();
				}
			}); 
		}else{
			toastr.error('Already In Bill !');
            event.target.select();
		}
    }
    $(document).on('click','.item_target',function(e){
        e.preventDefault();
        //var ind = $($(this).attr('href')).parent('div').parent('td').parent('tr.item_row').index();
        var ind = $('tr.item_row').index($($(this).attr('href')).parent('div').parent('td').parent('tr.item_row'));
        var item_data = $(this).data('object');
		
        $('[name="code[]"]').eq(ind).val(item_data.code);
        $('.element_'+ind).remove();
        $('[name="id[]"]').eq(ind).val(item_data.stock);
        $('[name="type[]"]').eq(ind).val(item_data.type); 
        $('[name="source[]"]').eq(ind).val(item_data.source);
        $('[name="caret[]"]').eq(ind).val(item_data.caret);
        
        if(item_data.type!='Genuine'){
            $('[name="item_quant[]"]').eq(ind).prop('readonly',false).removeClass('readonly');
            (field_focus)?$('[name="item_quant[]"]').eq(ind).focus():null;
        }else{
            $('[name="item_quant[]"]').eq(ind).prop('readonly',true).addClass('readonly');
            $('[name="item_quant[]"]').eq(ind).val(item_data.avail);
            if(item_data.type!='Artificial'){
                 (field_focus)?$('[name="chrg_perc[]"]').eq(ind).focus():null;
            }else{
                 (field_focus)?$('[name="now_rate[]"]').eq(ind).focus():null;
            }
        }
        var caret_label = "";
        var avail_post = '';
        if(item_data.type!='Artificial'){
            caret_label = item_data.caret+'K ('+(((item_data.caret*100)/24).toFixed(2))+'%)';
            $('.unit').eq(ind).removeClass('deactive').addClass('active');
            $('[name="curr_caret"]').eq(ind).val(caret_label);
            $('[name="curr_fine"]').eq(ind).val(item_data.fine+"grm");
            avail_post = "grm";
        }else{ 
            $('[name="chrg_perc[]"]').eq(ind).addClass('readonly').prop('readonly',true);
            $('[name="item_chrg[]"]').eq(ind).addClass('readonly').prop('readonly',true);
            $('[name="curr_caret"]').eq(ind).val('----');
            $('[name="curr_fine"]').eq(ind).val('----');
            $('.unit').eq(ind).removeClass('active').addClass('deactive');
        }
        
        $('[name="item_name[]"]').eq(ind).val(item_data.name);
        $('[name="curr_rate"]').eq(ind).val(item_data.rate);
        $('[name="curr_make"]').eq(ind).val(item_data.charge);
        $('[name="curr_avail"]').eq(ind).val(item_data.avail+avail_post);
        $(".curr_cate").eq(ind).empty().text(item_data.type);
        $('.pre_info_div').eq(ind).show();
        
        if(item_data.element && item_data.element.length>0){
            var tr = "";
            var elements = item_data.element;
            $.each(elements,function(ei,ele){
                console.log(ele);
                tr+='<tr class="element_'+ind+'">';
                tr+='<td class="text-right">E &#10148;</td>';
                tr+='<td>';
                tr+='<input type="text" name="element['+ind+'][name][]" class="form-control ele_content readonly text-center" readonly value="'+ele.name+'">';
                tr+='</td>';
                tr+='<td colspan="2" class="ele_caret">';
                tr+='<input type="text" name="element['+ind+'][caret][]" class="form-control ele_content readonly text-center" readonly value="'+ele.caret+'">';
                tr+='</td>';
                tr+='<td colspan="2" class="ele_count">';
                    tr+='<input type="text" name="element['+ind+'][quant][]" class="form-control ele_content readonly text-center" readonly value="'+ele.quant+'">';
                tr+='</td>';
                tr+='<td>';
                tr+='<input type="text" name="element['+ind+'][cost][]" class="form-control w-100 ele_content  text-right  px-1 element_total readonly" placeholder="Total" readonly="" required="" value="'+ele.cost+'">';
                tr+='</td>';
                tr+='</tr>';
            });
            $(tr).insertAfter($('tbody').find('tr.item_row').eq(ind));
        }
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

    function sethsngst(){
        var hsn_option = $("#hsn").find('option:selected');
        const gst  = hsn_option.data('target');
        $("[name='gst_set']").val(gst)
        $("#hsn_gst").empty().text('('+gst+')');
    }

    function calculatetotal(disc_amnt_req = true){
        var sum = 0;
        var err = false;
        var gst_apply = true;
        var go_ahead = true;
        if($('[name="to"]').val()==''){
            $('[name="to"]').addClass('is-invalid').focus();
            toastr.error("Please Select The Customer Category !");
            go_ahead = false;
        }
        
        if($('[name="gst_apply"]:checked').length == 0){
            $('[name="gst_apply"]').parent('label').parent('label').addClass('is-invalid').focus();
            toastr.error("Please Select The Bill Category !");
            go_ahead = false;
        }
        if(go_ahead){
            $(document).find('.toast.toast-error').remove();
            $('tbody>tr.item_row').each(function(i,v){
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
    
                    var chrg_perc_field = $('[name="chrg_perc[]').eq(i);
                    var chrg_perc = chrg_perc_field.val()??0;
                    var chrg_rs_field = $('[name="item_chrg[]"]').eq(i);
                    var chrg_rs = chrg_rs_field.eq(i).val()??0;
                    var cost = 0;
                    if($('[name="type[]"]').eq(i).val()!='Artificial'){
                        if(($('[name="to"]').val()!="" && $('[name="to"]').val()=="W")){
                            chrg_rs = 0;
                            var ttl_perc = +((($('[name="caret[]"]').eq(i).val()*100)/24).toFixed(2)) + +chrg_perc;
                            cost = (((item_qunt*ttl_perc)/100)*apply_rate).toFixed(2);
                        }else{
                            if(($('[name="to"]').val()!="" && $('[name="to"]').val()=="R")){
                                chrg_perc = 0;
                                cost = ((+apply_rate * +item_qunt)+ +(item_qunt*chrg_rs)).toFixed(2);
                            }
                        }
                    }else{
                        cost = item_qunt*apply_rate;
                    }
                    $("[name='item_total[]']").eq(i).val(cost);
                    sum += +cost;
                }
            });
            $($(document).find('.element_total')).each(function(elkey,ele){
                sum+= +$(this).val();
            });
            var new_sub = sum.toFixed(2);
            $("[name='total_sum'],[name='total_sub']").val(new_sub);
            var gst_val = $("[name='gst_set']").val();
            var disc = $("[name='total_disc']").val();
			
			var disc_val = (new_sub*disc)/100;
			
            if(disc_amnt_req){
				disc_val = disc_val.toFixed(2);
				disc_val = disc_val.replace(/\.?0+$/, '');
                $('[name="total_disc_amnt"]').val(disc_val);
            }
			
    
            var  disc_total = +new_sub - +disc_val;
            var gst_amnt = 0;
            gst_apply = ($('[name="gst_apply"]:checked').val()=='yes')?true:false;
            if(gst_apply){
				gst_amnt = (disc_total*gst_val)/100;
            }
            total = +disc_total + +gst_amnt;
            var total_fix = total.toFixed(2)
            var round_total = Math.round(total_fix)
            var round_off = round_total-total_fix;
    
            $("[name='gst_val']").val(gst_amnt.toFixed(2));
            $("[name='round']").val(round_off.toFixed(2));
            $("[name='total_final']").val(round_total);
            var in_word = numberintoword(round_total);
            $('[name="inwords"]').val(in_word);
            var paid = 0;
            $('[name="amount[]"]').each(function(i,v){
                if($('[name="mode[]"]').eq(i).val()!="" && $('[name="medium[]"]').eq(i).val()!=""){
                    paid+= +$('[name="amount[]"]').eq(i).val()??0;
                }
            });
            if($('#cash').val()!=""){
                var bank_pay = $('#cash').val();
                paid+= +bank_pay;
            }
            var remain = round_total - paid;
            if(remain<0){
                $('[name="remain"]').val("");
                toastr.error("You are Paying Extra !");
            }else{
                $('[name="remain"]').val(remain);
            }
        }
    }
    
    function togglecharge(self,target){
        var ind = $($(document).find('tbody>tr.item_row')).index(self.closest('tr.item_row'));
        var rate = $('[name="now_rate[]"]').eq(ind).val()??false;
        var lbr = self.val()??0;
        var ele = $('[name="'+target+'[]"]').eq(ind);
        var cnvrt = 0;
        var caret = (target=='item_chrg')?'24':$('[name="caret[]"]').eq(ind).val();
        if(lbr!=0){
            if(rate){
                if(target=='item_chrg'){
                    cnvrt = (lbr*rate)/100;
                }else{
                    if(target=='chrg_perc'){
                        cnvrt = (lbr*100)/rate;
                    }
                }
                ele.val(cnvrt.toFixed(2));
            }
            $('span.rate_caret').eq(ind).empty().text('('+caret+'K)').show();
            ele.prop('readonly',true).addClass('readonly');
        }else{
            $('span.rate_caret').eq(ind).hide();
            ele.val('');
            ele.prop('readonly',false).removeClass('readonly');
        }
    }

    function lbrconverttoggle(self){
       var rate = self.val()??false;
       var ind = $($(document).find('tbody>tr.item_row')).index(self.closest('tr.item_row'));
       var lbr =  0;
       var cnvrt = false;
       var ele = "";
       if(rate){
            if(!$('[name="chrg_perc[]"]').eq(ind).hasClass('readonly')){
                lbr = $('[name="chrg_perc[]"]').eq(ind).val()??0;
                ele = $('[name="item_chrg[]"]').eq(ind);
                cnvrt = (lbr!=0)?((lbr*rate)/100):0;
                ele.val(cnvrt.toFixed(2));
            }
            if(!$('[name="item_chrg[]"]').eq(ind).hasClass('readonly')){
                lbr = $('[name="item_chrg[]"]').eq(ind).val()??0;
                ele = $('[name="chrg_perc[]"]').eq(ind);
                cnvrt = (lbr!=0)?((lbr*100)/rate):0;
                ele.val(cnvrt.toFixed(2));
            }
        }
    }

    function validalance(){
        $(document).find('.toast.toast-error').remove();
        $("#amount").removeClass('is-invalid');
        if($("#amount").val()!=""){
            var go_ahead = true;
            var apply = $("#amount").val()??0;
            var bill_total = $("#total_final").val()??0;
            var bnk_pay = $("#cash").val()??0;
            var able = bill_total-bnk_pay;
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

    function numberintoword(num){
        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        const thousands = ["", "Thousand", "Million", "Billion", "Trillion"];
        if (num === 0) return "Zero";
        let result = "";
        let i = 0;
        while (num > 0) {
            if (num % 1000 !== 0) {
                result = `${convertHundreds(num % 1000)} ${thousands[i]} ${result}`;
            }
            num = Math.floor(num / 1000);
            i++;
        }
        return result.trim();
    }

    function convertHundreds(num) {
        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

        if (num === 0) return "";
        if (num < 20) return ones[num];
        if (num < 100) return `${tens[Math.floor(num / 10)]} ${ones[num % 10]}`;
        return `${ones[Math.floor(num / 100)]} Hundred ${convertHundreds(num % 100)}`.trim();
    }

    function checksum(){
        var paid = 0;
        var cash = $("#cash").val()??0;
        var remains = $("#remain").val();
        var final = $("#total_final").val();
        $.each($(document).find('[name="amount[]"]'),function(){
            paid += +$(this).val();
        });
        paid = +paid + +cash; 
        return ((final-paid)==remains)?true:false;
    }
	
	function convertpercentage(){
        const ttl_sm = $('input[name="total_sum"]').val()??0;
        const ttl_sb = $('input[name="total_sub"]').val()??0;
        var perc = 0;
        if((ttl_sm==ttl_sb) && (ttl_sm!=0 && ttl_sb!=0)){
            const disc_amnt = $("input[name='total_disc_amnt']").val()??0;
            if(disc_amnt!=0){
                perc = ((disc_amnt*100)/ttl_sb)??0;
                perc = perc.toFixed(2);
				perc = perc.replace(/\.?0+$/, '');
            }
            $('input[name="total_disc"]').val(perc);
			
        }else{
            toastr.error("Invalid Bill Sub Total !");
        }
        $('input[name="total_disc"]').val(perc);
        calculatetotal(false);
    }
    </script>

  @endsection

