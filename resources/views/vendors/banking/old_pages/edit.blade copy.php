@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Edit Sell Bill',[['title' => 'Sell Bill']] ) ;

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
      /* .item_delete{
        color:red;
      }
      .item_delete.active{
        background:red;
        color:white;
      } */
      .item_delete,.pay_delete{
        border:1px solid lightgray;
        margin:0;
      }
      tr>td.table-disabled,.pay_col_disable{
          opacity:0.3;
          z-index:0;
          pointer-events: none;
        }
        #item_body>tr>td{
            position: relative;
        }
        tr>td.table-disabled:after,.pay_col_disable:after{
            content:"";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent cover */
            z-index: 1; /* Ensure it's on top of the td content */
            pointer-events: none; /* Allow interaction with td content behind the cover */
        }
  </style>
  <section class = "content">
      <div class = "container-fluid">
          <div class = "row justify-content-center">
              <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-primary">
                      <div class="card-header">
                      <h3 class="card-title"><x-back-button />  Change Sell Bill</h3>
                      </div>
                  </div>
                  <div class="card-body bg-white">
                    @if(!empty($sell))
                  <form id = "submitForm" method="POST" action="{{ route('sells.update',$sell->id)}}" class = "myForm" enctype="">
                      @csrf
                      @method('put')
                      <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                          <!---Bill Info Form Element---->
                          <div class="col-md-3">
                              <label for="">Customer</label>
                              <div class="form-group">
                                  <input type="hidden" name="exist" value="yes"  id="is_exist">
                                  <input type="text" name="custo_name" id="custo_name" class="form-control" placeholder="Customer Name" oninput="existingcustomer()"  value="{{ $sell->custo_name }}" readonly>
                                  <ul class="col-12" id="custo_list" style="display:none;"></ul>
                                  <input type="text" name="custo_mobile" id="custo_mobile" class="form-control" placeholder="Customer Mobile"  oninput="digitonly(event,10);" onchange="digitonly(event,10);" value="{{ $sell->custo_mobile }}" readonly>
                                  <div class="input-group">
                                        @php 
                                            $scheme_pay_sum = $sell->payments->where('medium',"Scheme")->sum('amount');
                                        @endphp
                                      <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon1">Balance</span>
                                      </div>
                                      <label id="scheme_balance" class="text-center text-info form-control">{{ ($sell->customer->schemebalance->remains_balance+$scheme_pay_sum)??'NA' }}</label>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="row" id="custo_addr_area" style="border: 1px dotted gray;display:none;">
                                  <label for="" class="col-12">Address</label>
                                  <div class="col-md-6 form-group">
                                      <select class="form-control" name="custo_state" id="custo_state">
                                          <option value="">Select State</option>
                                      </select>
                                      <select class="form-control" name="custo_dist" id="custo_dist">
                                          <option value="">Select Disrtict</option>
                                      </select>
                                      <input type="text" name="custo_area" id="custo_area" class="form-control" placeholder="Enter Area Name">
                                  </div>
                                  <div class="col-md-6 form-group">
                                      <textarea id="custo_addr" name="custo_addr" class="form-control" placeholder="Enter Full Address" rows="4"></textarea>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-3 ">
                              <div class="form-group">
                                  <label for="bill_num">Bill No.</label>
                                  <input type="text" name="bill_num" id="bill_num" class="form-control" placeholder="Bill Number" value="{{ $sell->bill_no }}" readonly>
                                  </ul>
                              </div>
                              <div class="form-group">
                                  <label for="custo_name">Bill Date</label>
                                  <input type="date" name="bill_date" id="bill_date" class="form-control" placeholder="Bill Ddate" value="{{ date('Y-m-d', strtotime($sell->bill_date)) }}" required >
                              </div>
                          </div>
                      </div>
                      <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                          <div class="table-responsive">
                              <table class="table table-bordered table-stripped">
                                  <thead class="bg-dark">
                                      <tr>
                                          <th width="5%">S.N.</th>
                                          <th width="25%">ITEM</th>
                                          <th class="bg-secondary"  width="10%">RATE</th>
                                          <th class="bg-secondary"  width="10%">MAKE</th>
                                          <th class="bg-secondary"  width="10%">AVAIL</th>
                                          <th width="5%" class="text-right">WEIGHT</th>
                                          <th  width="5%" class="text-right">COUNT</th>
                                          <th  width="10%" class="text-right">COST</th>
                                          <th  width="5%" class="text-right">LABOUR</th>
                                          <th  width="10%" class="text-right">TOTAL</th>
                                          <th width="5%" class="bg-danger">&cross;</th>
                                      </tr>
                                  </thead>
                                  <tbody id="item_body">
                                        @php 
                                            $wght_sum = 0;
                                            $count_sum = 0;
                                            $cost_sum = 0;
                                            $make_sum = 0;
                                            $ttl_sum = 0;
                                        @endphp
                                      @foreach($sell->items as $ik=>$item)
                                      <tr id="item_{{ $ik }}" class="item_row">
                                          <td class="text-center">{{ $ik+1  }}</td>
                                          <td>
                                              <div class="col-12 p-0">
                                              <input type="hidden" name="type[]" value="{{ ($item->stock->stock_type!="other")?ucfirst($item->stock->stock_type):'Loose' }}">
                                              <input type="hidden" name="id[]" value="{{ $item->stock_id}}">
                                              <input type="hidden" name="item_id[]" value="{{ $item->id}}">
                                              <input type="text" name="item_name[]" class="form-control w-100 item_info" placeholder="Item Name" onkeyup="getitem($(this))" value="{{ $item->item_name }}" >
                                              </div>
                                          </td>
                                          
                                          <td class="bg-light text-dark">
                                              <input type="text" name="curr_rate" class="form-control item_info" value="{{ $item->item_rate }}" id="" disabled >
                                          </td>
                                          
                                          <td class="bg-light text-dark">
                                              <input type="text" name="curr_make" class="form-control item_info" value="{{ $item->stock->labour_charge/$item->stock->stock_quantity }}" id="" disabled >
                                          </td>
                                          
                                          <td class="bg-light text-dark">
                                              <input type="text" name="curr_avail" class="form-control item_info" value="{{ $item->stock->stock_avail + $item->item_weight+$item->item_quantity }}" id="" disabled >
                                          </td>
                                          @php 
                                            $wght_readonly = ($item->stock->stock_type=='other')?'':'readonly';
                                          @endphp
                                          <td>
                                              <input type="text" name="item_wght[]" class="form-control w-100 item_info text-right {{ $wght_readonly }}" placeholder="Weight" oninput="calculatetotal()" value="{{ $item->item_weight??0 }}" {{ $wght_readonly }}>
                                              @php 
                                                $wght_sum+=$item->item_weight??0
                                              @endphp
                                          </td>
                                          @php 
                                            $count_readonly = ($item->stock->stock_type=='other')?'readonly':'';
                                          @endphp
                                          <td>
                                              <input type="text" name="item_count[]" class="form-control w-100 item_info  text-right {{ $count_readonly }}" placeholder="Quantity"  oninput="calculatetotal()" value="{{ $item->item_quantity??0 }}" {{ $count_readonly }} >
                                              @php 
                                                $count_sum+=$item->item_quantity??0
                                              @endphp
                                          </td>
                                          <td>
                                              <input type="text" name="item_cost[]" class="form-control w-100 item_info  text-right" placeholder="Cost"  oninput="calculatetotal()" {{ ($ik==0)?'required':'' }} value="{{ $item->item_cost }}">
                                              @php 
                                                $cost_sum+=$item->item_cost??0
                                              @endphp
                                          </td>
                                          <td>
                                              <input type="text" name="item_chrg[]" class="form-control w-100 item_info  text-right" placeholder="Charge"  oninput="calculatetotal()" value="{{ $item->labour_charge }}">
                                              @php 
                                                $make_sum+=$item->labour_charge??0
                                              @endphp
                                          </td>
                                          <td>
                                              <input type="text" name="item_total[]" class="form-control w-100 item_info  text-right" placeholder="Total"  readonly {{ ($ik==0)?'required':'' }} value="{{ $item->total_amount }}">
                                              @php 
                                                $ttl_sum+=$item->total_amount??0
                                              @endphp
                                          </td>
                                          <td style="vertical-align:middle;text-align:center">
                                            <label for="delete_item_{{ $item->id }}" class="btn btn-sm btn-default item_delete">
                                                <span class="">&check;</span>
                                            </label>
                                            <input type="checkbox" name="delete_item[]" value="{{ $item->id }}"  id="delete_item_{{ $item->id }}"  class="d-none delete_item">
                                          </td>
                                      </tr>
                                      @endforeach
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
                                          <td colspan="3" >
                                              
                                          </td>
                                          <td style="text-align:center;vertical-align:middle;">
                                              <b style="">SUM</b>
                                          </td>
                                          <td>
                                              <input type="text" name="total_wght" class="form-control item_info readonly text-right" value="{{ $wght_sum }}" id="" readonly required style="font-weight:bold;" >
                                          </td>
                                          <td>
                                              <input type="text" name="total_count" class="form-control item_info readonly text-right" value="{{ $count_sum }}" id="" readonly required style="font-weight:bold;">
                                          </td>
                                          <td>
                                              <input type="text" name="total_cost" class="form-control item_info readonly text-right" value="{{ $cost_sum }}" id="" readonly required style="font-weight:bold;">
                                          </td>
                                          <td>
                                              <input type="text" name="total_make" class="form-control item_info readonly text-right" value="{{ $make_sum }}" id="" readonly required style="font-weight:bold;">
                                          </td>
                                          <td>
                                              <input type="text" name="total_sum" class="form-control item_info readonly text-right" value="{{ $ttl_sum }}" id="" readonly required style="font-weight:bold;">
                                          </td>
                                          <td style="vertical-align:middle;text-align:center">
                                            <label id="delete_item_count" class="text-danger">0</label>
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
                                  <input type="text" name="total_sub" class="form-control readonly text-right" value="{{ $sell->sub_total}}" id="" required style="font-weight:bold;"  readonly>
                              </div>
                          </div>
                          <div class="col-md-3 col-12">
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    @php 
                                        $selected = $sell->gst_type ;
                                        $$selected = "selected";
                                    @endphp
                                      <select name="gst_type" class="form-control item_info readonly text-center" style="font-weight:bold;padding:0;background-color: #f1f2f3 !important;border: 1px solid #bfc9d4" id="gst_type" >
                                          <option value="">GST%</option>
                                          <option value="in" {{ @$in }}>Include</option>
                                          <option value="ex" {{ @$ex }}>Exclude</option>
                                      </select>
                                  </div>
                                  <input type="text" name="total_gst" class="form-control  readonly text-right" value="{{ ($sell->gst_apply==1)?$sell->gst:0; }}" id="total_gst" required style="font-weight:bold;" onfocus="$(this).select()" {{ ($sell->gst_type!="")?"":"readonly" }} oninput="calculatetotal()" >
                              </div>
                          </div>
                          <div class="col-md-3 col-12">
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <label class="input-group-text" id="basic-addon1">Disc%</label>
                                  </div>
                                  <input type="text" name="total_disc" class="form-control readonly text-right" value="{{ $sell->discount }}" id="total_disc" required style="font-weight:bold;" onfocus="$(this).select()" oninput="calculatetotal()" >
                              </div>
                          </div>
                          <div class="col-md-3 col-12">
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                      <label class="input-group-text" id="basic-addon1">Total</label>
                                  </div>
                                  <input type="text" name="total_final" class="form-control readonly text-right" value="{{ $sell->total }}" id="total_final" required style="font-weight:bold;" readonly >
                              </div>
                          </div>
                      </div>
                        @php 
                            $mode_arr = ['on'=>"Online",'off'=>'Offline'];
                            $medium_arr = ['PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Scheme','Cash'];
                            $Scheme = $Cash = "off";
                            $payments  = $sell->payments()->whereIn('action_taken',['A','U'])->get();
                        @endphp
                      @if($payments->count()>0)
                        @foreach($payments as $pi=>$pay) 
                            @if($pi==0)
                                <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;" id="pre_pay_option">
                                    <div class="col-md-3 col-12 form-group">
                                        <div class="form-group">
                                            <label for="mode">Pay Mode</label>
                                            @php 
                                                $pay_sel = $pay->mode;
                                                $$pay_sel = "selected";
                                            @endphp
                                            <select class="form-control" name="mode[]" id="mode" oninput="validalance()" required>
                                                <option value="">Choose</option>
                                                <option value="on" {{ @$on }}>Online</option>
                                                <option value="off" {{  @$off }}>Offline</option>
                                            </select>
                                        </div>
                                    </div>
                                    @php 
                                        $mode_state = (in_array($pay->mode,['on','off']))?true:false;
                                    @endphp
                                    <div class="col-md-3 col-12 form-group">
                                        <label for="medium">Pay Medium</label>
                                        <select class="form-control" name="medium[]" id="medium" oninput="validalance()" {{ ($mode_state)?'':'disabled' }} required>
                                            <option value="" >Choose</option>
                                            @foreach($medium_arr as $key=>$value)
                                                @php 
                                                    $md_lbl = $$value??'on';
                                                @endphp
                                                <option value="{{ $value }}" class="{{ $md_lbl }}" style="display:{{ ($md_lbl==$pay->mode)?"":"none" }}" {{ ($value==$pay->medium)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-12 form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="pre_pay[]" value="{{ $pay->id }}">
                                            <input type="text" class="form-control readonly" name="amount[]" id="amount" placeholder="Enter Amount !" {{ ($pay->medium!="")?"":"readonly" }} oninput="validalance()" onkeyup="validalance()" required value="{{ $pay->amount }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#more_pay_modal" {{ ($sell->remains > 0)?"":"disabled" }} id="bill_more_pay"><li class="fa fa-plus"></li></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <label form="remain">Remains</label>
                                        <input type="text" name="remain" class="form-control" readonly id="remain" required placeholder="Remains Balance" value="{{ $sell->remains }}" >
                                    </div>
                                </div>
                            @else
                                <div class="row mb-2 more_payment_row" style=""> 
                                    <div class="col-md-3 pay_col">
                                        <div class="input-group input-group-sm">  
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="inputGroup-sizing-sm">Mode</label>
                                            </div>
                                            <input type="hidden" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="mode[]" value="{{ $pay->mode }}" readonly >
                                            <label class="form-control" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" readonly>{{ $mode_arr["{$pay->mode}"] }}</lable>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 pay_col">
                                        <div class="input-group input-group-sm">  
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="inputGroup-sizing-sm">Medium</label>
                                            </div>
                                            <input type="hidden" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="medium[]" value="{{ $pay->medium }}" readonly >
                                            <label class="form-control" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" readonly>{{ $pay->medium }}</lable>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 pay_col">
                                        <div class="input-group input-group-sm">  
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" id="inputGroup-sizing-sm">Amount</label>
                                            </div>
                                            <input type="hidden" name="pre_pay[]" value="{{ $pay->id }}">
                                            <input type="hidden" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="amount[]" value="{{ $pay->amount }}" readonly >
                                            <label class="form-control" aria-label="Small"  aria-describedby="inputGroup-sizing-sm" readonly>{{ $pay->amount }}</lable>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-left">
                                    <label for="delete_pay_{{ $pay->id }}" class="btn btn-sm btn-default pay_delete">
                                        <span class="">&cross;</span>
                                    </label>
                                    <input type="checkbox" name="delete_pay[]" value="{{ $pay->id }}"  id="delete_pay_{{ $pay->id }}"  class="d-none delete_pay">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                      @else 
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
                      @endif
                      <div class="row mb-2" style="border-bottom:1px solid #a6a6a6;">
                          <div class="col-12 text-center form-group">
                              <button type="submit" name="make" value="bill" class="btn btn-danger">Update</button>
                          </div>
                      </div>
                  </form>

                  @else 
                    <div class="text-center text-danger">No Sell Bill to Edit !</div>
                  @endif
                  </div>
              </div>
          </div>
      </div><!-- /.container-fluid -->
      
  </section>
  @if(!empty($sell))
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
  @endif
@endsection

@section('javascript')

<script>

  $(document).ready(function() {

    $("#submitForm").trigger('reset');
      
    $('.delete_pay').change(function(){
        var count = $(this).parent('div').parent('div').index('.more_payment_row');
        var rmns = $("#remain").val();
        var amnt = $(document).find('[name="amount[]"]').eq(count+1).val();
      //var new_rmns = +rmns + +amnt;
    //   if(new_rmns > rmns){
    //       $("#remain").val(new_rmns);
    //       $(document).find('.more_payment_row').eq(count).remove();
    //   }
        
        if($(this).is(':checked')){
            $("#remain").val(+rmns + +amnt);
            $('.more_payment_row').eq(count).find('.pay_col').addClass('pay_col_disable').removeClass('pay_col');
            $(this).prev('label').addClass('btn-danger').removeClass('btn-default');
        }else{
            $("#remain").val(+rmns - +amnt);
            $('.more_payment_row').eq(count).find('.pay_col_disable').addClass('pay_col').removeClass('pay_col_disable');
            $(this).prev('label').removeClass('btn-danger').addClass('btn-default');
        }
        // calculatetotal();
        // validalance();
    });

    $('.delete_item').change(function(){
        $(this).prev('label').toggleClass('btn-default btn-outline-danger');
        var num = $(this).closest('tr').index();
        if($(this).is(':checked')){
            $('#item_body > tr').eq(num).find('td').addClass('table-disabled');
            $("#delete_item_count").text(+$("#delete_item_count").text()+ +1);
        }else{
            $('#item_body > tr').eq(num).find('td').removeClass('table-disabled');
            $("#delete_item_count").text(+$("#delete_item_count").text()- +1);
        }
        $(this).closest('td').removeClass('table-disabled');
        calculatetotal();
        validalance();
    });
      $('#submitForm').submit(function(e) {

          $(document).find("#toast-container").remove();
          $("input,select").removeClass('is-invalid');
          e.preventDefault(); // Prevent default form submission

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
          $(this).removeClass('table-disabled');
      });
      $(tr).find('td:last').empty();
      $(tr.find('td>[name="item_wght[]"],td>[name="item_count[]"]')).removeClass('readonly').prop('readonly',false);
      $('tbody').append(tr);

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
              $("#custo_addr_area").hide();
              $("#is_exist").prop('disabled',false);
          }else{
              if($("#custo_name").val()!=""){
                  $("#custo_list").hide();
                  $("#scheme_balance").text('NA');
                  $("#custo_addr_area").show();
                  $("#is_exist").prop('disabled',true);
              }
          }
      });
      
  }

  $(document).on('click','.custo_target',function(){
      var cust_data = $(this).data('target').split('-');
      $("#custo_name").val(cust_data[0]);
      $("#custo_mobile").val(cust_data[1]);
      $("#scheme_balance").text(cust_data[2]);
      $("#custo_list").hide();
  });

  function getitem(input,item_request){
      var ind = input.closest('tr').index();
      
     $.get("{{ route('sells.item') }}","item="+input.val(),function(response){
          $('[name="id[]"]').eq(ind).val("");
          $('[name="type[]"]').eq(ind).val("");
          $('[name="curr_rate"]').eq(ind).val("");
          $('[name="curr_make"]').eq(ind).val("");
          $('[name="curr_avail"]').eq(ind).val("");
          $(document).find('ul#item_list').remove();
          if(response!="" ){
              input.parent('div').append(response);
          }else{
              $('[name="id[]"]').eq(ind).val("");
              $('[name="type[]"]').eq(ind).val("");
              $('[name="item_name[]"]').eq(ind).val("");
              $('[name="curr_rate"]').eq(ind).val("");
              $('[name="curr_make"]').eq(ind).val("");
              $('[name="curr_avail"]').eq(ind).val("");
              $('[name="item_count[]"]').eq(ind).prop('readonly',false);
              $('[name="item_wght[]"]').eq(ind).prop('readonly',false);
              $('[name="item_count[]"]').eq(ind).removeClass('readonly');
              $('[name="item_wght[]"]').eq(ind).removeClass('readonly');
          }
      }); 
  }

  $(document).on('click','.item_target',function(e){
      e.preventDefault();
      var ind = $($(this).attr('href')).parent('div').parent('td').parent('tr').index();
      var item_data = $(this).data('target').split('-');
      $('[name="id[]"]').eq(ind).val(item_data[0]);
      $('[name="type[]"]').eq(ind).val(item_data[1]);
      if(item_data[1]=='Loose'){
          $('[name="item_count[]"]').eq(ind).prop('readonly',true);
          $('[name="item_count[]"]').eq(ind).addClass('readonly');
          $('[name="item_wght[]"]').eq(ind).prop('readonly',false);
          $('[name="item_wght[]"]').eq(ind).removeClass('readonly');
      }else{
          $('[name="item_count[]"]').eq(ind).prop('readonly',false);
          $('[name="item_count[]"]').eq(ind).removeClass('readonly');
          $('[name="item_wght[]"]').eq(ind).prop('readonly',true);
          $('[name="item_wght[]"]').eq(ind).addClass('readonly');
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
              html+=      '<input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="medium[]" value="'+medium+'" readonly style="font-weight:bold;">';
              
              html+= '</div></div>';
              
  
              html+='<div class="col-md-3"><div class="input-group input-group-sm">';
              html+=      '<div class="input-group-prepend">';
              html+=          '<label class="input-group-text" id="inputGroup-sizing-sm">Amount</label>';
              html+=      '</div>';
              html+=      '<input type="hidden" name="pre_pay[]" value=""><input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="amount[]" value="'+amount+'" readonly style="font-weight:bold;">';
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

  /*
  function calculatetotal(){
      var sub_total = 0;
      var sub_cost = 0;
      var sub_make = 0;
      var sub_quant = 0;
      var sub_wght = 0;
      var err = false;
      $(document).find('.toast.toast-error').remove();
      $('tbody>tr').each(function(i,v){
          if($('[name="item_name[]"]').eq(i).val()!=""){
                
              var wght = ($('[name="item_wght[]"]').eq(i).val()!="" )?$('[name="item_wght[]"]').eq(i).val():0;
              var count = ($('[name="item_count[]"]').eq(i).val()!="")?$('[name="item_count[]"]').eq(i).val():0;
              var lbr = ($('[name="item_chrg[]"]').eq(i).val()!="")?$('[name="item_chrg[]"]').eq(i).val():0;
              
              

              var quant = ($('[name="type[]"]').eq(i).val()=='loose')?wght:count;
              
              var match = $('[name="curr_avail"]').eq(i).val();
              var ele = ($('[name="type[]"]').eq(i).val()=='loose')?$('[name="item_wght[]"]').eq(i):$('[name="item_count[]"]').eq(i);
              
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
      var sub_total = 0;
      var sub_cost = 0;
      var sub_make = 0;
      var sub_quant = 0;
      var sub_wght = 0;

      var out_wght = 0;
      var out_count = 0;
      var out_lbr = 0;
      var out_cost = 0;
      var out_total = 0;

      var err = false;
      $(document).find('.toast.toast-error').remove();
      $('tbody>tr').each(function(i,v){
          if($('[name="item_name[]"]').eq(i).val()!=""){
            
              var wght = ($('[name="item_wght[]"]').eq(i).val()!="" )?$('[name="item_wght[]"]').eq(i).val():0;
              
              var count = ($('[name="item_count[]"]').eq(i).val()!="")?$('[name="item_count[]"]').eq(i).val():0;
             
              var lbr = ($('[name="item_chrg[]"]').eq(i).val()!="")?$('[name="item_chrg[]"]').eq(i).val():0;
              
            
            var quant = ($('[name="type[]"]').eq(i).val()=='Loose')?wght:count;
            
            var match = $('[name="curr_avail"]').eq(i).val();
            
            var ele = ($('[name="type[]"]').eq(i).val()=='loose')?$('[name="item_wght[]"]').eq(i):$('[name="item_count[]"]').eq(i);
            // alert("Match :"+match);
            // alert("Quant :"+quant);
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
           
            if(!$('[name="delete_item[]"]').eq(i).is(":checked")){
                sub_cost = +sub_cost + +cost;
                sub_wght =  +sub_wght + +wght;
                sub_quant =  +sub_quant + +count;
                sub_make = +sub_make + +lbrc;
                sub_total +=total;
            }
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
  }

  function validalance(){
      $(document).find('.toast.toast-error').remove();
      $("#amount").removeClass('is-invalid');
      if($("#amount").val()!=""){
          var go_ahead = true;
          var apply = $("#amount").val()??0;
          var able = $("#total_final").val()??0;
          if($("#mode").val()=='off' && $("#medium").val()=='scheme'){
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

