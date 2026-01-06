@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Scheme EMI',[['title' => 'Schemes EMI']] ) ;

@endphp

<x-page-component :data=$data />

<section class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary mb-3">

          <div class="card-header">
            <h3 class="card-title"><x-back-button /> Pay EMI </h3>
          </div>

          <div class="card-body pt-0">
                @php 
                  $choosed_emi = $enrollcusto->emi_amnt;
                  $scheme_start = $enrollcusto->schemes->scheme_date;
                  $scheme_validity = $enrollcusto->schemes->scheme_validity;
                  $deposited = $enrollcusto->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt')??0;
                  $start_month_num = date('m',strtotime($scheme_start));
                  $end_month_num = $start_month_num+$scheme_validity;
                  $month_noun = date("M",strtotime($scheme_start));
                  $month_arr[] = $month_noun;
                  for($i=1;$i<$scheme_validity;$i++){
                    array_push($month_arr,date("M",strtotime("{$month_noun}+$i Month")));
                  }
                  $paid_emi_num_arr = [];
                  foreach($enrollcusto->emipaid as $key=>$array){
                    if(in_array($array['action_taken'],['A','U'])){
                      if(isset($paid_emi_num_arr[$array['emi_num']])){
                        $paid = $paid_emi_num_arr[$array['emi_num']][1]+$array['emi_amnt'];
                        $paid_emi_num_arr[$array['emi_num']] = [$array['action_taken'],$paid];
                      }else{
                        $paid_emi_num_arr[$array['emi_num']] = [$array['action_taken'],$array['emi_amnt']];
                      }
                    }
                  }
                @endphp
            <div class="row">
              <div class="col-md-4 text-center p-5" style="border:1px dashed lightgray; ">
                @if(file_exists(url($enrollcusto->info->custo_img)))
                  <img src="{{  url($enrollcusto->info->custo_img) }}" class="img-responsive"> 
                @else
                  <img src="{{  asset("assets/images/icon/browse.png") }}" class="img-responsive" style="width:inherit;opacity: 0.1;"> 
                @endif
              </div>
              <style>
              #user_img_placeholder{
                position:absolute;
              }
              </style>
              <div class="col-md-8">
                <div class="col-12">
                  <ul class="scheme_ul row text-center p-0 mb-0">

                    <li class="col-md-4">{{ $enrollcusto->schemes->scheme_head }}</li>
                    <li class="col-md-3">{{ $enrollcusto->groups->group_name }}</li>
                    <li class="col-md-4">{{ $enrollcusto->customer_name }}</li>
                    <li class="col-md-1 p-1">
                      @if($enrollcusto->open==0)
                      <button class="btn btn-outline-success form-control text-danger" data-target="#unlock_to_manage" data-action="click" id="unlock_reference"><li class="fa fa-lock"></li></button>
                      <a href="{{ route('shopscheme.emi.unlock',$enrollcusto->id) }}" id="unlock_to_manage" class="d-none"><i class="fa fa-lock"></i></a>
                      @else
                      <span class="form-control disabled" >
                        <i class="fa fa-unlock"></i>
                      </span>
                      @endif
                    </li>
                  </ul>
                </div>
                <ul class="custo_ul">
                  <li><strong>NAME</strong><span>{{ $enrollcusto->info->custo_full_name }}</span></li>
                  <li><strong>MOBILE</strong><span>{{ $enrollcusto->info->custo_fone }}</span></li>
                  <li><strong>EMAIL</strong><span>{{ $enrollcusto->info->custo_mail }}</span></li>
                  <li>
                    <hr class="m-0">
                  </li>
                  <li class="form-control py-2 px-2"><strong>STARTs AT</strong>
                    <span class="text-primary">
                      <b> {{ date("d-m-Y",strtotime($scheme_start)) }}</b>
                    </span>
                  </li>
                </ul>
                <ul class="row p-0 px-3">
                  <li class="col-6 col-md-3  text-center form-control h-auto">
                    <strong>ASSIGNED ID</strong>
                    <hr class="m-0">
                    <span><b>{{ $enrollcusto->assign_id }}</b></span>
                  </li>
                  <li  class="col-6 col-md-3  text-center form-control h-auto">
                    <strong>TOKEN</strong>
                    <hr class="m-0">
                    <span><b>{{ $enrollcusto->token_amt }} Rs.</b></span>
                  </li>
                  <li  class="col-6 col-md-3  text-center form-control h-auto">
                    <strong>EMI</strong>
                    <hr class="m-0">
                    <span><b>{{ $choosed_emi }}</b> Rs.</span></li>
                  <li  class="col-6 col-md-3 text-center form-control h-auto">
                    <strong>DUE DATE</strong>
                    <hr class="m-0">
                    <span><b>{{ $enrollcusto->schemes->emi_date }}</b> of Month</span>
                  </li>
                </ul>
                <div class="row">
                  <div class="col-6">
                    <label class="form-control h-auto text-center text-success bg-white" >DEPOSITE : 
                      <span> {{ $deposited }}</span></label>
                  </div>
                  <div class="col-6">
                  @if($enrollcusto->open==1)
                  <label class="form-control h-auto text-center text-warning bg-white" >BONUS * : <span class="final_bonus">0</span></label>
                  @else 
                  <label class="form-control h-auto text-center text-info bg-white" >BONUS : <span class="">{{ $enrollcusto->emipaid->whereIn('action_taken',['A','U'])->sum('bonus_amnt') }}</span></label>
                  @endif
                  </div>
                </div>
              </div>
            </div>
            <hr style="border-top:1px dashed lightgray;">
            @php
              $total_bonus = $total_emi_paid  = 0;
              $start_month = date("Y-m",strtotime($enrollcusto->schemes->scheme_date));
              $paid = $enrollcusto->emipaid->count('emi_num')??0;
              $pre_paid = $curr_paid = null;
              $prev_emi = 0;
              $bonus_grant = ($enrollcusto->schemes->lucky_draw==1)?false:( ($enrollcusto->schemes->scheme_interest=='Yes')?true:false);
            @endphp
            @if($enrollcusto->open==1)
            <form action="{{ route("shopscheme.emipay",$enrollcusto->id) }}" method="post" id="submitForm" class="myForm">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-3 pt-1">
                      <label for="emi">Month</label>
                      <select name="emi" id="emi" class="form-control">
                        @foreach($month_arr as $num=>$name)
                          @php 
                          $show = (isset($paid_emi_num_arr[$num+1]))?(($paid_emi_num_arr[$num+1][0]=='D' || $paid_emi_num_arr[$num+1][1]<$choosed_emi)?true:false):true;
                          @endphp
                            @if($show)
                            <option value="{{ $num+1 }}">{{ $name }}.</option>
                            @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-5 pt-1">
                      <label for="date">Date</label>
                      <input type="date" name="date" class="form-control" placeholder="" value="{{ date('Y-m-d',strtotime("now")) }}" required>
                    </div>
                    <div class="col-md-4 pt-1">
                      <label for="amnt">EMI</label>
                      <input type="number" id="amnt" name="amnt" class="form-control" placeholder="EMI Amount" value="{{ $enrollcusto->emi_amnt }}" required>
                    </div>
                    <div class="col-md-6 pt-1">
                      <label for="mode">Pay Mode</label>
                      <select class="form-control" id="mode" name="mode"  required>
                          <option value="" >SELECT</option>
                          <option value="SYS" >System</option>
                          <option value="ECOMM">E-Commerce</option>
                      </select>
                    </div>
                    <div class="col-md-6 pt-1">
                      <label for="medium">Pay Mediums</label>
                      <select class="form-control" id="medium" name="medium" required>
                          <option value="">SELECT</option>
                          @php 
                            $medium_arr = ['Cash','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Draw','Vendor'];
                          @endphp
                          @foreach($medium_arr as $mk=>$med)
                            @php 
                            $draw = ($med=='Draw')?(($enrollcusto->schemes->lucky_draw==1)?true:false):true;
                            @endphp
                            @if($draw)
                            <option value="{{ $med }}">{{ $med }}</option>
                            @endif
                          @endforeach
                      </select>
                    </div>
                    <div class="col-12 m-2" id="draw_month" style="display:none">
                      <label form="all_month form-control " style="color:blue;">
                        <input type="checkbox" name="drawmonth" id="all_month" value="all" > Emi Pay for all Remaining Month !
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 p-0">
                  <div class="col-md-12 pt-1">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control" required placeholder="Payment Related Remarks" required rows="4" >Emi Paid</textarea>
                  </div>
                </div>
                <div class="col-md-12  text-center mt-3 pt-3" style="border-top:1px dotted lightgray;">
                    <button type="submit" name="pay" value="emi" id="pay_emi_button" class="btn btn-md btn-success mt-1 pl-5 pr-5" data-target="#submitForm" data-action="submit">Pay ?</button>
                  </div>
                </div>
              </div>
            </form>
            @endif
              <ul style="" class="" id="color_indecator">
                <li>&nbsp;Addedd</li>
                <li style="{{ (!$bonus_grant)?'display:none;':'' }}">&nbsp;No Bonus</li>
                <li>&nbsp;Edited</li>
                <li>&nbsp;Updated</li>
                <li>&nbsp;Deleted</li>
              </ul>
              <style>
                #color_indecator > li{
                  display:inline;
                }
                #color_indecator > li:before{
                  content:" ";
                  padding-left:8px;
                  padding-right:8px;
                }
                #color_indecator > li:nth-child(1):before{
                  color:white;
                  background:white;
                  border:1px solid lightgray;
                }
                #color_indecator > li:nth-child(2):before{
                  color:#ffbd203d !important;
                  background:#ffbd203d !important;
                  border:1px solid orange;
                }
                #color_indecator > li:nth-child(3):before{
                  color:#bee2f054 !important;
                  background:#bee2f054 !important;
                  border:1px solid #bee2f0;
                }
                #color_indecator > li:nth-child(4):before{
                  color:#c8c8ff7a !important;
                  background:#c8c8ff7a !important;
                  border:1px solid #c8c8ff;
                }
                #color_indecator > li:nth-child(5):before{
                  color:#ff95951c !important;
                  background:#ff95951c !important;
                  border:1px solid #ff9595;
                }
              </style>
              <div class="table-responsive">
                <table class="table tabel-bordered emi-pay-table">
                  <thead>
                    <tr>
                      <th>ENTRY</th>
                      <th>MONTH</th>
                      <th>EMI</th>
                      @if($bonus_grant)
                      <th>BONUS</th>
                      @endif
                      <th>DATE</th>
                      <th>PAY MODE</th>
                      <th>PAY MEDIUM</th>
                      <th>REMARK</th>
                      <th>UPDATE</th>
                      @if($enrollcusto->open==1)
                      <th><span class="px-3" style="font-weight:bold;">&#x2637;</span></th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($enrollcusto->emipaid as $emik=>$emi)
                      @php 
                        $no_bonus = "";
                        $curr_bonus_amount = null;
                        $emi_date = "{$start_month}-15";
                        $month_plus = $emi->emi_num-1; 
                        $date = new DateTime($emi_date);
                        $now_date = $date->modify("+{$month_plus} Month")->format("Y-m-d");
                        $total_emi_paid += $emi->emi_amnt;
                      @endphp
                      @if($bonus_grant)
                        @if($emi->emi_date <= $now_date && in_array($emi->action_taken,['A','U']))
                          @php 
                            $total_bonus += $curr_bonus_amount= ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='per')?($emi->emi_amnt*$enrollcusto->schemes->interest_rate)/100:$enrollcusto->schemes->interest_amt):0;
                          @endphp
                        @else
                          @php
                            $no_bonus = '-my-warning';
                          @endphp
                        @endif
                      @endif
                      @php 
                        $table_class_arr = ['D'=>'-my-danger','E'=>'-my-info','U'=>'-my-primary'];
                        if($emi->action_taken !='A' ){
                          $no_bonus = "{$table_class_arr[$emi->action_taken]}";
                        }
                      @endphp
                      @if($emi->emi_num !=0)
                      <tr class="table{{ $no_bonus }} {{ ($bonus_grant && $curr_bonus_amount==0)?'no-bonus':'' }}" id="parent_{{ $emi->id  }}">

                        <td style="color:unset;font-weight:unset;">
                          {{ date("d-m-Y H:i:a",strtotime($emi->created_at)) }}
                        </td>
                        <td class="text-center">
                          {{ date("M",strtotime($now_date)) }}
                        </td>
                        <td class="text-center">
                          @if(!empty($emi->action_on))
                          <a href="#parent_{{ $emi->action_on }}" class="uadated_value">{{  $emi->emi_amnt }}</a>
                          @else 
                          {{  $emi->emi_amnt }}
                          @endif
                        </td>
                        @if($bonus_grant)
                        <td class="text-center">
                          {{ $curr_bonus_amount??0 }}
                          ({{ ($emi->bonus_type=='T')?'Token':'Bonus'}})
                        </td>
                        @endif
                        <td>
                          {{ date("d-m-Y",strtotime($emi->emi_date)) }}
                        </td>
                        <td>
                          {{ @$emi->pay_mode }}
                        </td>
                        <td>
                          {{ @$emi->pay_medium }}
                        </td>
                        <td class="text-center">{{ $emi->remark }}</td>
                        <td style="color:unset;font-weight:unset;">
                          {{ date("d-m-Y H:i:a",strtotime($emi->updated_at)) }}
                        </td>
                        @if($enrollcusto->open==1)
                        <td class="text-center">
                        @if($emi->action_taken!='D')
                          @if($emi->action_taken!='E')
                            <a href="{{ route('shopscheme.emi.edit',$emi->id) }}" class="btn btn-sm btn-outline-info edit_emi m-1"><li class="fa fa-edit"></li></a>
                          @endif
                            <a href="#" class="btn btn-sm btn-outline-danger paid_emi_delete_ref m-1" id="paid_emi_{{ $emik }}" data-target="#paid_emi_{{ $emik }}_del" data-action="click">
                              <li class="fa fa-times"></li>
                            </a>
                            <a href="{{ route('shopscheme.emi.delete',$emi->id) }}" class="btn btn-sm btn-outline-danger paid_emi_delete" id="paid_emi_{{ $emik }}_del" style="display:none;">
                            </a>
                        @endif
                        </td>
                        @endif
                      </tr> 
                      @endif
                    @endforeach
                    
                  </tbody>
                </table>
              </div>
              <hr>
              @php 
                $validity = $enrollcusto->schemes->scheme_validity;
                $desire_emi = $validity*$enrollcusto->emi_amnt;
                $paid_emi = $enrollcusto->emipaid->whereIn('action_taken',['A','U'])->sum('emi_amnt');
              @endphp
              @if($enrollcusto->open==1 && $desire_emi==$paid_emi && $enrollcusto->emipaid->max('emi_num')==$validity)
              <form action="{{ route('shopscheme.emi.bonus',$enrollcusto->id) }}" method="post" id="final_bonus_form">
                @csrf
              <div class="col-md-4 m-auto">
                <div class="form-group mb-3">
                  <input type="text" class="form-control text-center text-primary" placeholder="Bonus Remark" value="" name="bonus_remark" style="font-weight:bold;"  id="bonus_remark" required >
                </div>
              </div>
              <div class="col-md-4 m-auto">
                <div class="input-group mb-3">
                  <input type="text" class="form-control text-center text-primary" placeholder="Final Bonus" value="{{ $total_bonus }}" name="bonus_amount" style="font-weight:bold;" requied >
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" data-target="#final_bonus_form" data-action="submit" id="add_bonus_button">Add & Close</button>
                  </div>
                </div>
              </div>
              </form>
              @endif
          </div>
        </div>

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  <div class="modal" tabindex="-1" role="dialog" id="subpaymodel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-body p-2">
          
        </div>
      </div>
    </div>
  </div>
  <style>
    .uadated_value{
      border-bottom:1px dotted blue;
      font-size:120%;
    }
    tr.clicked{
      border:2px dotted blue;
    }
    tr.highlight{
      border:2px solid dotted blue;
      box-shadow: 1px 2px 3px 5px #0058ff;
    }
    table.emi-pay-table>thead>tr>th {
      color: white;
      background: #66778c;
    }
    table.emi-pay-table>tbody>tr.table-my-warning{
      background-color:#ffbd203d !important;
    }
    table.emi-pay-table>tbody>tr.table-my-warning{
      background-color:#ffbd203d !important;
    }
    table.emi-pay-table>tbody>tr.table-my-info{
      background-color:#bee2f054 !important;;
    }
    table.emi-pay-table>tbody>tr.table-my-primary{
      background-color:#c8c8ff7a !important;;
    }
    table.emi-pay-table>tbody>tr.table-my-danger{
      background-color:#ff95951c !important;
    }
    table.emi-pay-table>tbody>tr.no-bonus>td,table.emi-pay-table>tbody>tr.no-bonus>th{
      color:orange;
      font-weight:bold;
    }
    ul.scheme_ul {
      list-style: none;
      border-bottom: 1px solid gray;
      /* font-size: 120%;
      font-weight: bold; */
      overflow: hidden;
    }

    ul.custo_ul {
      list-style: none;
      overflow: hidden;
      padding: 0;
    }

    ul.custo_ul>li {
      padding-top: 10px;
    }

    ul.custo_ul>li>span {
      float: right;
    }

    ul.scheme_ul>li {
      padding: 10px;
    }

    ul.scheme_ul>li:nth-child(1) {
      background: #c6c6c6;
    }

    ul.scheme_ul>li:nth-child(2) {
      background: lightgray;
    }

    ul.scheme_ul>li:nth-child(3) {
      background: #eaeaea;
    }

    ul.scheme_ul>li:nth-child(4) {
      background: #eaeaea;
    }

    ul.scheme_ul>li:after {
      content: " ";
      display: block;
      width: 0;
      height: 0;
      border-top: 50px solid transparent;
      border-bottom: 50px solid transparent;
      position: absolute;
      top: 50%;
      margin-top: -50px;
      left: 100%;
      z-index: 1;
    }

    ul.scheme_ul>li:nth-child(1):after {
      border-left: 30px solid #c6c6c6;
    }

    ul.scheme_ul>li:nth-child(2):after {
      border-left: 30px solid lightgray;
    }

    label.form-control {
      background: lightgray;
    }

    label.form-control.sn {
      width: max-content;
    }

    label.form-control.sn.active {
      background: #a6eea66e;
      color: #2507b5;
    }

    
  </style>
</section>
@endsection

@section('javascript')

@include("layouts/vendors/js/mpincheckmode96");
<script>
  $(document).ready(function() {
    $('.final_bonus').empty().append("{{ $total_bonus }}");
    

    $('input[type="date"]').change(function() {
      checkforbonus();
    });

    $('#medium').change(function(){
      if($(this).val()=="Draw"){
        $("#all_month").attr('checked',true);
        $("#emi").attr('disabled',true);
        $("#draw_month").show();
      }else{
        $("#all_month").attr('checked',false);
        $("#emi").attr('disabled',false);
        $("#draw_month").hide();
      }
    });
    $("#all_month").click(function(){
      if($(this).is(':checked')){
        $("#emi").attr('disabled',true);
      }else{
        $("#emi").attr('disabled',false);
      }
    });

    function checkforbonus() {
      var form_data = $("#submitForm").serialize();
      form_data += "&custo={{ $enrollcusto->id }}";
      form_data += "&_token={{ csrf_token() }}";
      $.post("{{ route('shopschemes.getbonus') }}", form_data, function(response) {
        // $("#finalbonus").val("");
        // $("#finalbonus").val(response.bonus);
        $(".final_bonus").empty().append(response.bonus);
      });
    }

    $("#pay_emi_button,#add_bonus_button,.paid_emi_delete_ref,#unlock_reference").click(function(e) {
      e.preventDefault();
      data_element = $(this).data('target');
      data_action = $(this).data('action');
      launchmpinmodal();
    });

    $(".paid_emi_delete,#unlock_to_manage").click(function(e){
      e.preventDefault();
      $.get($(this).attr('href'),"",function(response){
          //var res = JSON.parse(response);
          if(response.status){
            success_sweettoatr(response.msg);
            window.open("{{route("shopscheme.emipay",$enrollcusto->id) }}", '_self');
          }else{
            toastr.error(res.msg);
          }
      });
    });

    // $("#unlock_to_manage").click(function(e){
    //   e.preventDefault();
    //   $.get($(this).attr('href'),"",function(response){
    //       //var res = JSON.parse(response);
    //       if(response.status){
    //         success_sweettoatr(response.msg);
    //         window.open("{{route("shopscheme.emipay",$enrollcusto->id) }}", '_self');
    //       }else{
    //         toastr.error(res.msg);
    //       }
    //   });
    // });

    $('#submitForm').submit(function(e) {
      e.preventDefault(); // Prevent default form submission
      //$("#blockingmodal").modal();
      var formAction = $(this).attr('action');
      var formData = new FormData(this);
        // Send AJAX request
        $.ajax({
          url: formAction,
          type: 'POST',
          data: formData,
          dataType: 'json',
          contentType: false,
          processData: false,
          beforeSend: function() {
            // $('.btn').prop("disabled", true);
            // $('#loader').removeClass('hidden');
          },
          success: function(response) {
            // Handle successful update
            success_sweettoatr(response.success);
            window.open("{{route("shopscheme.emipay",$enrollcusto->id) }}", '_self');
          },
          error: function(response) {
            if (response.status === 422) {
              var errors = response.responseJSON.errors;
              $('input').removeClass('is-invalid');
              $('.btn-outline-danger').prop("disabled", false);
              $('.btn').prop("disabled", false);
              $('#loader').addClass('hidden');
              $.each(errors, function(field, messages) {
                var $field = $('[name="' + field + '"]');
                toastr.error(messages[0]);
                $field.addClass('is-invalid');
              });
            } else {
              if (response.status === 425) {
                toastr.error(response.responseJSON.errors);
              } else {
                console.log(response.responseText);
              }
            }
          }
        });
    });

    $("#final_bonus_form").submit(function(e){
      e.preventDefault();
      $.post($(this).attr('action'),$(this).serialize(),function(response){
          if(response.errors){
               // var errors = response.responseJSON.errors;
                $.each(response.errors, function(field, messages) {
                var $field = $('[name="' + field + '"]');
                toastr.error(messages[0]);
                $field.addClass('is-invalid');
              });
            }else{
                if(response.status){
                    success_sweettoatr(response.msg);
                    window.open("{{route("shopscheme.emipay",$enrollcusto->id) }}", '_self');
                }else{
                    toastr.error(response.msg);
                }
            }
      });
    });

    const pay_modal = $('#subpaymodel');
    const modal_head = $('#subpaymodel > .modal-dialog > .modal-content > .modal-header');
    const modal_body = $('#subpaymodel > .modal-dialog > .modal-content > .modal-body');
    $('.edit_emi').click(function(e){
      e.preventDefault();
      modal_body.empty();
      modal_body.load($(this).attr('href'),"",function(){});
      pay_modal.modal();
    });

    $('.uadated_value').click(function(e){
      e.preventDefault();
      $('tr').removeClass('highlight clicked');
      const element = $($(this).attr('href'));
      const self = $(this).parent('td').parent('tr');
      element.addClass('highlight');
      self.addClass('clicked');
      $($(this).attr('href')).get(0).scrollIntoView({behavior: 'smooth'});
      //$('body').scrollTo($(this).attr('href'));
      setTimeout(function(){ element.removeClass('highlight');self.removeClass('clicked'); }, 5000);
    })

  });
</script>

@endsection