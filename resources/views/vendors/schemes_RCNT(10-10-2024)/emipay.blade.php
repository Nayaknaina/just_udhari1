@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Scheme EMI',[['title' => 'Schemes EMI']] ) ;
  
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
                <li><hr  class="m-0"></li>
                <li><strong>ASSIGNED ID</strong><span><b>{{ $enrollcusto->assign_id }}</b></span></li>
                <li><strong>CHOOSED EMI</strong><span><b>{{ $enrollcusto->emi_amnt }}</b></span></li>
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
          <form id = "submitForm" method="POST" action="{{ route("shopscheme.emipay",$enrollcusto->id) }}" class = "myForm" enctype="multipart/form-data">

          @csrf
          @method('post')
          @php 
            $paid = count($enrollcusto->emipaid);
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
                </tr>
              </thead>
              <tbody>
                @php
                  $total_bonus = $total_emi_paid = 0;
                  $start_month = date("Y-m",strtotime($enrollcusto->schemes->scheme_date));
                @endphp
                
                @for($i=1;$i<=$enrollcusto->schemes->scheme_validity;$i++)
                  @php 
                    $no_bonus = "";
                    $curr_bonus_amount = null;
                    $emi_date = "{$start_month}-15";
                    $month_plus = $i-1; 
                    $date = new DateTime($emi_date);
                    $now_date = $date->modify("+{$month_plus} Month")->format("Y-m-d");
                    $no
                  @endphp
                  @if($i<=$paid)
                    @php 
                      $total_emi_paid += $enrollcusto->emi_amnt;
                    @endphp
                    @if($enrollcusto->emipaid[$i-1]->emi_date <= $now_date)
                        @php 
                          $total_bonus += $curr_bonus_amount= ($enrollcusto->schemes->scheme_interest=='Yes')?(($enrollcusto->schemes->interest_type=='per')?($enrollcusto->emi_amnt*$enrollcusto->schemes->interest_rate)/100:$enrollcusto->schemes->interest_amt):0;
                        @endphp
                      @else
                        @php 
                          $no_bonus = 'table-warning';
                        @endphp
                    @endif
                    <tr class="{{ $no_bonus }}">
                      <td class="text-center">{{ $i }}</td>
                      <td class="text-center">{{ $enrollcusto->emipaid[$i-1]->emi_amnt }}</td>
                      <td class="text-center">
                        @if($enrollcusto->schemes->scheme_validity==$paid)
                         {{ $curr_bonus_amount }}
                        @else 
                          {{ $enrollcusto->emipaid[$i-1]->bonus_amnt }}
                        @endif
                         ( {{ ($enrollcusto->emipaid[$i-1]->bonus_type=='T')?'Token':'Bonus'}} )
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
                    </tr>
                    
                    @if(($i== $paid) && ($paid == $enrollcusto->schemes->scheme_validity))
                      <tr class="table-success">
                        <td colspan="7" class="p-0">  
                          <table class="table-bordered table m-0">
                            <tbody>
                              <tr>
                                <td>TOTAL : <b >{{ $total_emi_paid }}</b></td>
                                <td>BONUS : <b >{{ $total_bonus }}</b></td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    @endif
                  @else
                    <tr class="table-secondary">
                        <td class="text-center">
                          <label class="form-control sn" >
                              <input type="checkbox"  name="emi[{{ $i }}]" value="{{ $i }}" class="emi_sel" id="{{ ($i==$enrollcusto->schemes->scheme_validity)?'checkforbonus':'' }}" > {{ $i }}
                          </label>
                        </td>
                        <td class="text-center emi_sel_{{ $i }}">
                          <input type="number" class="form-control" id="amnt" name="amnt[{{ $i }}]" value="{{ $enrollcusto->emi_amnt }}" placeholder="EMI Amount" disabled></td>
                        <td class="text-center emi_sel_{{ $i }}">
                          @if($i==$enrollcusto->schemes->scheme_validity)
                          <input type="number" class="form-control" id="finalbonus" name="bonus[{{ $i }}]" value="{{ $total_bonus }}" placeholder="Bonus Amount" disabled>
                          <input type="hidden" name="type[{{ $i }}]" value="B"  disabled>
                          @else
                              <input type="hidden" name="bonus[{{ $i }}]" value="0"  disabled readonly>
                              <input type="hidden" name="type[{{ $i }}]" value="B" disabled readonly >
                              <label class="form-control">
                              0 <small>( Bonus )</small>
                              </label>
                          </label>
                          @endif
                        </td>
                        <td class="text-center emi_sel_{{ $i }}">
                        <input type="date" name="date[{{ $i }}]" id="{{ ($i==$enrollcusto->schemes->scheme_validity)?'finaldate':'date' }}" value="{{ date("Y-m-d",strtotime('Now'))}}" class="form-control" disabled readonly>
                        </td>
                        <td class="text-center emi_sel_{{ $i }}">
                          <select class="form-control" id="mode" name="mode[{{ $i }}]"  disabled readonly>
                              <option value="SYS" selected >System</option>
                              <option value="ECOMM">E-Commerce</option>
                          </select>
                        </td>
                        <td class="text-center emi_sel_{{ $i }}">
                          <select class="form-control" id="mode" name="medium[{{ $i }}]"  disabled readonly>
                              <option value=""  >Select</option>
                              @php 
                                $medium_arr = ['Cash','PayTm','GPay','PhonPay','BharatPay','BHIM','WhatsAppPay','Draw','Vendor'];

                              @endphp
                              @foreach($medium_arr as $mk=>$med)
                                <option value="{{ $med }}">{{ $med }}</option>
                              @endforeach
                          </select>
                        </td>
                        <td class="text-center emi_sel_{{ $i }}">
                          <textarea name="remark[{{ $i }}]" id="remark" class="form-control" placeholder="Remark About Payment" disabled>EMI Paid</textarea>
                        </td>
                      </tr>
                  @endif
                @endfor
              </tbody>
            </table>
        </div>
          @if(count($enrollcusto->emipaid) < $enrollcusto->schemes->scheme_validity)
            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger editbutton" id="pay_emi_button"> Pay EMI ? </button>
                </div>
            </div>
          @endif

          </form>

          </div>
          </div>

          </div>
          <!-- /.row -->
      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
  <style>
    ul.scheme_ul{
      list-style:none;
      border-bottom:1px solid gray;
      font-size:120%;
      font-weight:bold;
      overflow:hidden;
    }
    ul.custo_ul{
      list-style:none;
      overflow:hidden;
      padding:0;
    }
    ul.custo_ul > li{
      padding-top:10px;
    }
    ul.custo_ul > li >span{
      float:right;
    }
    ul.scheme_ul > li{
      padding:10px;
    }
    ul.scheme_ul > li:nth-child(1) {
      background:#c6c6c6;
    }
    ul.scheme_ul > li:nth-child(2) {
      background:lightgray;
    }
    ul.scheme_ul > li:nth-child(3) {
      background:#eaeaea;
    }
    ul.scheme_ul > li:nth-child(1):after,ul.scheme_ul > li:nth-child(2):after{
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
    ul.scheme_ul > li:nth-child(1):after{
      border-left: 30px solid #c6c6c6;
    }
    ul.scheme_ul > li:nth-child(2):after{
      border-left: 30px solid lightgray;
    }
    label.form-control{
      background:lightgray;
    } 
    label.form-control.sn{
      width:max-content;
    } 
    label.form-control.sn.active{
      background:#a6eea66e;
      color: #2507b5;
    }
    table.emi-pay-table > thead >tr>th{
      color:white;
      background:#66778c;
    } 
    table>tbody>tr.table-select{
      background:#e2f0d1;
      box-shadow:1px 2px 3px 5px gray;
    }
  </style>
</section>
@endsection

@section('javascript')
<script>

  $(document).ready(function() {
    
    $('.emi_sel').click(function(){
      var check = 0;
      const val = $(this).val();
      if($(this).is(":checked")){
        check = 1; 
        $(this).parent('label.sn').addClass('active');
        $(this).parent('label.sn').parent('td').parent('tr').removeClass('table-secondary').addClass('table-select');
        $('.emi_sel_'+val).each(function(){
          $(this).find('input,textarea,select').attr('readonly',false);
          $(this).find('input,textarea,select').attr('disabled',false);
          $(this).find('input,textarea,select').attr('required',true);
        });
      }else{
        check = 0; 
        $(this).parent('label.sn').removeClass('active');
        $(this).parent('label.sn').parent('td').parent('tr').removeClass('table-select').addClass('table-secondary');
        $('.emi_sel_'+val).each(function(){
          $(this).find('input,textarea,select').attr('readonly',true);
          $(this).find('input,textarea,select').attr('disabled',true);
          $(this).find('input,textarea,select').attr('required',false);
        });
      }
      checkforbonus();
    });

    $('input[type="date"]').change(function(){
      checkforbonus();
    })
    function checkforbonus(){
      var form_data = $("#submitForm").serialize();
      form_data+="&custo={{ $enrollcusto->id }}";
      form_data+="&_token={{ csrf_token() }}";
      $.post("{{ route('shopschemes.getbonus') }}",form_data,function(response){
          $("#finalbonus").val("");
          $("#finalbonus").val(response.bonus);
      });
    }
	
	$("#pay_emi_button").click(function(e){
      e.preventDefault();
      $("#mpinerror").addClass('d-none');
      $('input[type="password"]').val("");
      $("#blockingmodal").modal();
    });
    
    $('#mpincheckform').on('submit', function(e) {
        e.preventDefault();
        var formdata = $(this).serialize();
        var action = "{{ route('shopschemes.mpincheck') }}";
        $.post(action,formdata,function(response){
          var res = JSON.parse(response);
          if(res[0]){
            $("#blockingmodal").modal('hide');
            $('#submitForm').trigger('submit');
          }else{
            $("#mpinerror").empty().append("Invalid MPIN !");
            $("#mpinerror").removeClass('d-none');
            toastr.error("Invalid MPIN !") ;
          }
        })
    });
	
    $('#submitForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        var formAction = $(this).attr('action') ;
        var formData = new FormData(this) ;
        var go = true;
        @if($i==$enrollcusto->schemes->scheme_validity)
          go = confirm("Is the Final Bonus  CORRECT ?");
        @endif
        if(go){
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
                  toastr.error(messages[0]) ;
                  $field.addClass('is-invalid') ;
                  });
              } else {
                if(response.status === 425){
                  toastr.error(response.responseJSON.errors) ;
                }else{
                  console.log(response.responseText);
                }
              }
              }
          });

        }
    });
      
    
  });

</script>

@endsection

