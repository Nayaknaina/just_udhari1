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
        <div class="card card-primary">

          <div class="card-header">
            <h3 class="card-title"><x-back-button /> Pay EMI </h3>
          </div>

          <div class="card-body pt-0">

            <div class="row">
              <div class="col-md-4 text-center">
                <img src="{{  url($enrollcusto->info->custo_img) }}" class="img-responsive">
              </div>
              <div class="col-md-8">
                <ul class="custo_ul">
                  <li><strong>NAME</strong><span>{{ $enrollcusto->info->custo_full_name }}</span></li>
                  <li><strong>MOBILE</strong><span>{{ $enrollcusto->info->custo_fone }}</span></li>
                  <li><strong>EMAIL</strong><span>{{ $enrollcusto->info->custo_mail }}</span></li>
                  <li>
                    <hr class="m-0">
                  </li>
                  <li class="form-control py-2 px-2"><strong>STARTs AT</strong><span class="text-primary"><b>{{ date("d-m-Y",strtotime($enrollcusto->schemes->scheme_date)) }}</b></span></li>
                  <li><strong>ASSIGNED ID</strong><span><b>{{ $enrollcusto->assign_id }}</b></span></li>
                  <li><strong>CHOOSED EMI</strong><span><b>{{ $enrollcusto->emi_amnt }}</b> Rs.</span></li>
                </ul>
              </div>
            </div>
            <div class="col-12">
              <ul class="scheme_ul row text-center p-0 mb-0">
                <li class="col-md-4">{{ $enrollcusto->schemes->scheme_head }}</li>
                <li class="col-md-4">{{ $enrollcusto->groups->group_name }}</li>
                <li class="col-md-4">{{ $enrollcusto->customer_name }}</li>
              </ul>
            </div>
            <form id="submitForm" method="POST" action="{{ route("shopscheme.emipay",$enrollcusto->id) }}" class="myForm" enctype="multipart/form-data">

              @csrf
              @method('post')
              @php
              $paid = $enrollcusto->emipaid->max('emi_num')??0;
              @endphp
              <input type="hidden" name="last" id="last" value="{{ $paid }}">
              <div class="table-responsive">
                <table class="table tabel-bordered emi-pay-table">
                  <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>EMI</th>
                      <th>ADD-ON</th>
                      <th>DATE</th>
                      <th>PAY MODE</th>
                      <th>PAY MEDIUM</th>
                      <th>REMARK</th>
                      <th><span class="px-3" style="font-weight:bold;">&#x2637;</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total_bonus = $total_emi_paid  = 0;
                      $start_month = date("Y-m",strtotime($enrollcusto->schemes->scheme_date));
                      $paid = $enrollcusto->emipaid->count('emi_num')??0;
                      $pre_paid = $curr_paid = null;
                      $loop_last = $enrollcusto->schemes->scheme_validity;
                      $start = ($enrollcusto->emipaid->max('emi_num')??0)+1;
                      $prev_emi = 0;
                    @endphp
                    @for($i=1;$i<=$paid;$i++)
                      @php 
                        $curr_emi = $enrollcusto->emipaid[$i-1]->emi_num;
                        $no_bonus = "";
                        $curr_bonus_amount = null;
                        $emi_date = "{$start_month}-15";
                        $month_setter = 1;
                        $emi_calculate = true;
                        if($curr_emi==$prev_emi){
                          $month_setter = 2;
                        }
                        $month_plus = $i-$month_setter; 
                        $date = new DateTime($emi_date);
                        $now_date = $date->modify("+{$month_plus} Month")->format("Y-m-d");
                        $prev_emi = $curr_emi;
                        $total_emi_paid += $enrollcusto->emipaid[$i-1]->emi_amnt;
                      @endphp
                      @if($enrollcusto->emipaid[$i-1]->emi_date <= $now_date && $enrollcusto->emipaid[$i-1]->action_taken=='A')
                        @php 
                          $total_bonus += $curr_bonus_amount= ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='per')?($enrollcusto->emipaid[$i-1]->emi_amnt*$enrollcusto->schemes->interest_rate)/100:$enrollcusto->schemes->interest_amt):0;
                        @endphp
                      @else
                        @php 
                          $no_bonus = 'table-warning';
                          $table_class_arr = ['D'=>'danger','E'=>'info','U'=>'Primary'];
                          if($enrollcusto->emipaid[$i-1]->action_taken !='A'){
                            $no_bonus = "table-{$table_class_arr[$enrollcusto->emipaid[$i-1]->action_taken]}";
                          }
                          
                        @endphp
                      @endif
                      <tr class="{{ $no_bonus }}">
                        <th class="text-center">
                        <a href="#" class="btn btn-sm btn-outline-info"><li class="fa fa-edit"></li></a>{{ $enrollcusto->emipaid[$i-1]->emi_num }}</td>
                        <td class="text-center">{{  $enrollcusto->emipaid[$i-1]->emi_amnt }}</td>
                        <td class="text-center">
                          {{ $curr_bonus_amount??0 }}
                          ({{ ($enrollcusto->emipaid[$i-1]->bonus_type=='T')?'Token':'Bonus'}})
                        </td>
                        <td>
                          {{ @$enrollcusto->emipaid[$i-1]->emi_date }}
                        </td>
                        <td>
                          {{ @$enrollcusto->emipaid[$i-1]->pay_mode }}
                        </td>
                        <td>
                          {{ @$enrollcusto->emipaid[$i-1]->pay_medium }}
                        </td>
                        <td class="text-center">{{ $enrollcusto->emipaid[$i-1]->remark }}</td>
                        <td class="text-center">
                          <a href="#" class="btn btn-sm btn-outline-danger paid_emi_delete_ref" id="paid_emi_{{ $i }}" data-target="#paid_emi_{{ $i }}_del" data-action="click">
                            <li class="fa fa-times"></li>
                          </a>
                          
                          <a href="{{ route('shopscheme.emi.delete',$enrollcusto->emipaid[$i-1]->id) }}" class="btn btn-sm btn-outline-danger paid_emi_delete" id="paid_emi_{{ $i }}_del" style="display:none;">
                          </a>
                         
                        </td>
                      </tr> 
                    @endfor
                    @for($j=$start;$j<=$enrollcusto->schemes->scheme_validity;$j++)
                      <tr class="table-secondary">
                        <td class="text-center">
                          <label class="form-control sn" >
                              <input type="checkbox"  name="emi[{{ $j }}]" value="{{ $j }}" class="emi_sel" id="{{ ($j==$enrollcusto->schemes->scheme_validity)?'checkforbonus':'' }}" > {{ $j }}
                          </label>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <input type="number" class="form-control" id="amnt" name="amnt[{{ $j }}]" value="{{ $enrollcusto->emi_amnt }}" placeholder="EMI Amount" disabled>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <input type="hidden" name="bonus[{{ $j }}]" value="0"  disabled readonly>
                          <input type="hidden" name="type[{{ $j }}]" value="B" disabled readonly >
                          <label >
                          0 <small>(Bonus)</small>
                          </label>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <input type="date" name="date[{{ $j }}]" id="{{ ($i==$enrollcusto->schemes->scheme_validity)?'finaldate':'date' }}" value="{{ date("Y-m-d",strtotime('Now'))}}" class="form-control" disabled readonly>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <select class="form-control" id="mode" name="mode[{{ $j }}]"  disabled readonly>
                              <option value="SYS" selected >System</option>
                              <option value="ECOMM">E-Commerce</option>
                          </select>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <select class="form-control" id="mode" name="medium[{{ $j }}]"  disabled readonly>
                              <option value=""  >Select</option>
                              @php 
                                $medium_arr = ['Cash','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Draw','Vendor'];

                              @endphp
                              @foreach($medium_arr as $mk=>$med)
                                <option value="{{ $med }}">{{ $med }}</option>
                              @endforeach
                          </select>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}">
                          <textarea name="remark[{{ $j }}]" id="remark" class="form-control" placeholder="Remark About Payment" disabled>EMI Paid</textarea>
                        </td>
                        <td class="text-center emi_sel_{{ $j }}" style="">
                              <button class="form-control p-0 m-0 sub_pay_dummy_button" style="font-size:25px;padding-bottom:5px!important;height:auto;" disabled readonly>
                              <span class="pay_ico">&#8512;</span>
                              </button>
                              <a href="{{ url("vendors/shopschemes/emipart/{$enrollcusto->id}/{$j}") }}" class="btn btn-sm btn-outline-primary  py-0 sub_pay_button" style="font-size:25px;display:none;padding-bottom:5px!important;height:auto;" style="">
                                <span class="pay_ico">&#8512;</span>
                              </a>
                              <a href="{{ url("shopscheme/emipart/{$enrollcusto->id}/{$j}") }}" class="btn btn-sm btn-outline-primary px-2 sub_pay_button_ sub_pay_button_option" style="font-size:25px;">
                              &#9902;
                              </a>
                              <a href="{{ url("shopscheme/emipart/{$enrollcusto->id}/{$j}") }}" class="btn btn-sm btn-outline-primary px-2 sub_pay_button_ sub_pay_button_option" style="font-size:25px;">
                              &#9901;
                              </a>
                              <a href="{{ url("shopscheme/emipart/{$enrollcusto->id}/{$j}") }}" class="btn btn-sm btn-outline-primary px-2 sub_pay_button_ sub_pay_button_option" style="font-size:25px;">
                              &#x2633;
                              </a>
                            </td>
                      </tr>
                    @endfor
                  </tbody>
                  
                </table>
              </div>
              @if($enrollcusto->emipaid->max('emi_num') < $enrollcusto->schemes->scheme_validity)
                <div class="row pt-3" style="border-top:1px dashed gray;">
                  <!-- <div class="col-12 text-center my-3 ">
                    <button type="submit" class="btn btn-success editbutton" id="pay_emi_button"  data-target="#submitForm" data-action="submit"> Pay EMI ? </button>
                  </div> -->
                  <div class="col-md-3 text-left">
                    <label class="form-control text-center">
                      BONUS : <span class="pull-right" id="finalbonus">{{ $total_bonus }}</span>
                    </label>
                  </div>
                  <!-- <div class="col-md-6 d-none d-sm-block"></div> -->
                  <div class="col-md-9 text-right">
                    <button type="submit" class="btn btn-success btn-md editbutton" id="pay_emi_button"  data-target="#submitForm" data-action="submit"> Pay EMI ? </button>
                  </div>
                </div>
                @endif
            </form>
            @if($enrollcusto->emipaid->max('emi_num') == $enrollcusto->schemes->scheme_validity)
            <div class="row p-2" style="border-top:1px dashed lightgray;">
              <div class="col-md-4 mt-3  ">
                <div class="col-12  form-control">
                  <div class="input-group">
                    <label class="w-50">TOTAL</label>
                    <div class="input-group-append w-50">
                    <label>: {{ $total_emi_paid }}</label>
                    </div>
                  </div>
                </div>
                <div class="col-12  form-control">
                  <div class="input-group">
                    <label class="w-50">BONUS</label>
                    <div class="input-group-append w-50">
                    <label class="disabled">: {{ ($enrollcusto->emipaid->max('emi_num')==$enrollcusto->schemes->validity+1)?$enrollcusto->emipaid->bonus_amnt:"NA" }}</span></label>
                    </div>
                  </div>
                </div>
              </div>
              @if($enrollcusto->open==1 && ($enrollcusto->emipaid->max('emi_num')==$enrollcusto->schemes->scheme_validity))
              <div class="col-md-8 mt-3" style="border:1px solid lightgray;box-shadow:1px 2px 3px 3px gray;">
                <form action="{{ route('shopscheme.acclose',$enrollcusto->id)}}" method="post" id="bonus_form" class="">
                  <div class="row">
                    @csrf
                    <div class="form-group col-md-5">
                      <label for="bonus_amount">Bonus Amount</label>
                      <input type="number" class="form-control" name="bonus_amount" placeholder="Bonus Amount" value="{{ $total_bonus }}">
                    </div>
                    <div class="form-group  col-md-4">
                      <label for="bonus_date">Payment Date</label>
                      <input type="date" class="form-control" name="bonus_date" placeholder="" value="{{ date('Y-m-d',strtotime('now')) }}">
                    </div>
                    <div class="form-group col-md-3">
                    <label ></label>
                      <button class="btn btn-success editbutton mt-2 form-control" type="submit" name="close" value="scheme">Add & Close</button>
                    </div>
                  </div>
                </form>
              </div>
              @endif
            </div>
            @endif
          </div>
        </div>

      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  <div class="modal" tabindex="-1" role="dialog" id="subpaymodel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body p-2">
          
        </div>
      </div>
    </div>
  </div>
  <style>
    ul.scheme_ul {
      list-style: none;
      border-bottom: 1px solid gray;
      font-size: 120%;
      font-weight: bold;
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

    ul.scheme_ul>li:nth-child(1):after,
    ul.scheme_ul>li:nth-child(2):after {
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

    table.emi-pay-table>thead>tr>th {
      color: white;
      background: #66778c;
    }

    table>tbody>tr.table-select {
      background: #e2f0d1;
      box-shadow: 1px 2px 5px 2px gray;
    }
    .sub_pay_button_option{
      display:none;
    }

  </style>
</section>
@endsection

@section('javascript')

@include("layouts/vendors/js/mpincheckmode96");
<script>
  $(document).ready(function() {
    $('.emi_sel').click(function() {
      var check = 0;
      const val = $(this).val();
      if ($(this).is(":checked")) {
        check = 1;
        $(this).parent('label.sn').addClass('active');
        $(this).parent('label.sn').parent('td').parent('tr').removeClass('table-secondary').addClass('table-select');
        $('.emi_sel_' + val).each(function() {
          $(this).find('input,textarea,select').attr('readonly', false);
          $(this).find('input,textarea,select').attr('disabled', false);
          $(this).find('.sub_pay_dummy_button').hide();
          $(this).find('.sub_pay_button').show();
          $(this).find('input,textarea,select').attr('required', true);
        });
      } else {
        check = 0;
        $(this).parent('label.sn').removeClass('active');
        $(this).parent('label.sn').parent('td').parent('tr').removeClass('table-select').addClass('table-secondary');
        $('.emi_sel_' + val).each(function() {
          $(this).find('input,textarea,select').attr('readonly', true);
          $(this).find('input,textarea,select').attr('disabled', true);
          $(this).find('.sub_pay_dummy_button').show();
          $(this).find('.sub_pay_button').hide();
          $(this).find('input,textarea,select').attr('required', false);
        });
      }
      checkforbonus();
    });

    $('input[type="date"]').change(function() {
      checkforbonus();
    });

    function checkforbonus() {
      var form_data = $("#submitForm").serialize();
      form_data += "&custo={{ $enrollcusto->id }}";
      form_data += "&_token={{ csrf_token() }}";
      $.post("{{ route('shopschemes.getbonus') }}", form_data, function(response) {
        // $("#finalbonus").val("");
        // $("#finalbonus").val(response.bonus);
        $("#finalbonus").empty().append(response.bonus);
      });
    }

    $("#pay_emi_button").click(function(e) {
      e.preventDefault();
      data_element = $(this).data('target');
      data_action = $(this).data('action');
      launchmpinmodal();
    });

    $(".paid_emi_delete_ref").click(function(e){
      e.preventDefault();
      data_element = $(this).data('target');
      data_action = $(this).data('action');
      launchmpinmodal();
    });
    $(".paid_emi_delete").click(function(e){
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

    $('#submitForm').submit(function(e) {
      e.preventDefault(); // Prevent default form submission
      //$("#blockingmodal").modal();
      var formAction = $(this).attr('action');
      var formData = new FormData(this);
      var go = true;
      @if($i == $enrollcusto->schemes->scheme_validity)
      go = confirm("Is the Final Bonus  CORRECT ?");
      @endif
      if (go) {
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

      }
    });

    $("#bonus_form").submit(function(e){
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
    $('.sub_pay_button').click(function(e){
      e.preventDefault();
      modal_body.empty();
      modal_body.load($(this).attr('href'),"",function(){});
      pay_modal.modal();
    });


  });
</script>

@endsection