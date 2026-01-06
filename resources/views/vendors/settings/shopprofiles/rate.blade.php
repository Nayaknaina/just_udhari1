@extends('layouts.vendors.app')

@section('content')

@php

    $data = component_array('breadcrumb' , "Current Rate",[['title' => "Current Rate"]] ) ;
    //dd($shopbranch);
@endphp

<x-page-component :data=$data />
<style>
    ul.rate_info{
        list-style:none;
    }
    .rate_info > li >span.rs:after{
        content:"/-Rs";
    }
    .rate_info > li >span.rs{
        padding-right:10px;
        font-size:90%;
    }
    .rate_info > li{
        margin:1px;
        border:1px solid lightgray;
        border-radius:5px;
        padding:5px;
    }
    tr.text-success > td{
        color:green!important;
        font-weight:bold;
        text-align:center;
    }
</style>
<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"> 
                        <x-back-button /> Current Rate 
                    </h3>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-default">
                                <form class="" id="rateform" name="rateform" method="post" action="{{ route('currentrates') }}" >
                                    @csrf
                                    <div class="card-body row">
                                        <div class="form-group col-md-5 p-1">
                                            <label for="gold_rate">Gold <small class="text-info"><b>(24K/10Gm)</b></b></small></label>
                                            <input type="text" class="form-control" name="gold_rate" id="gold_rate" placeholder="24K Rate">
                                        </div>
                                        <div class="form-group col-md-5 p-1">
                                            <label for="silver_rate">Silver <small class="text-info"><b>(1Kg)</b></small></label>
                                            <input type="text" class="form-control" name="silver_rate" id="silver_rate" value="" placeholder="1kg Rate">
                                        </div>
                                        <div class="form-group text-center col-md-2 p-1">
                                            <label class="col-12">&nbsp;</label>
                                            <button type="submit" name="save" value="rate" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-12">
                                    <div class="row" >
                                        <div class="col-md-8 col-12">
                                            <h6>GOLD <small class="text-info"><b>/Gm</b></small></h6>
                                            <ul class="rate_info gold d-flex flex-wrap p-0 text-center">
                                                <li class="m-auto">
                                                    <b>18K</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="gold_18" class="rs">0.00</span>
                                                </li>
                                                <li class="m-auto">
                                                    <b>20K</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="gold_20" class="rs">0.00</span>
                                                </li>
                                                <li class="m-auto">
                                                    <b>22K</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="gold_22" class="rs">0.00</span>
                                                </li>
                                                <li class="m-auto">
                                                    <b>24K</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="gold_24" class="rs">0.00</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <h6>SILVER</h6>
                                            <ul class="rate_info d-flex flex-wrap p-0 text-center">
                                                <li class="m-auto">
                                                    <b>1Gm</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="silver_1" class="rs">0.00</span>
                                                </li>
                                                <li class="m-auto " >
                                                    <b>10Gm</b>
                                                    <hr class="m-0 p-0 my-1">
                                                    <span id="silver_10" class="rs">0.00</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card card-default">
                                <div class="card-header p-2">
                                    <h3 class="card-title text-dark p-0"> All Rates</h3>
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive">
                                        <table class="table_theme table-bordered table">
                                            <thead id="rate_list">
                                                <tr>
                                                    <th >SN</th>
													<th>DATE</th>
                                                    <th>GOLD<small class="text-dark"><b><i>(24K/1Gm)</i></b></small></th>
                                                    <th>SILVER<small class="text-dark"><b><i>(1KG)</i></b></small></th>
                                                    <th><i class="fa fa-times"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($rates->count() > 0)
                                                    @foreach($rates as $rk=>$rate)
                                                        <tr class={{ ($rate->active==0)?'table-danger':'text-success' }}>
                                                            <td class="text-center">{{ $rk+1 }}</td>
															<td>{{ date('d-m-Y',strtotime($rate->updated_at)) }}</td>
                                                            <td>{{ $rate->gold_rate }}</td>
                                                            <td>{{ $rate->silver_rate }}</td>
                                                            <td class="text-center">
                                                                @if(!$rate->active)
                                                                <a href="{{ route('currentrate.delete',['delete',$rate->id]) }}" class="delete_rate btn btn-sm btn-danger py-0 px-1"><i class="fa fa-times"></i></a>
                                                                @else 
                                                                <span><i class="fa fa-times"></i></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else 
                                                <tr><td colspan="5" class="text-center text-danger">No Rates Yet !</td></tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .form-control.invalid{
                border:1px dotted red;
            }
            .alert-block{
                display:none;
            }
        </style>
          <!-- left column -->

      </div><!-- /.container-fluid -->
  </div><!-- /.container-fluid -->
</section>

@endsection

@section('javascript')


<script>

  $(document).ready(function() {

    $("#gold_rate").on('input',function(e){
        var gold_rate = $(this).val()??false;
        if(gold_rate){
            //const one_karet_rate = gold_rate/24;
			const one_karet_rate = (gold_rate/10)/24;
            const caret_arr = [18,20,22,24];
            $.each(caret_arr,function(i,v){
                $(`#gold_${v}`).text((one_karet_rate * v).toFixed(2));
            })
        }
    });
    $("#silver_rate").on('input',function(e){
        var silver_rate = $(this).val()??false;
        if(silver_rate){
            const one_g = silver_rate/1000;
            $('#silver_1').text(one_g);
            const ten_g = one_g * 10;
             $('#silver_10').text(ten_g.toFixed(2));
        }
    });
      $('#rateform').submit(function(e) {
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
                  $('.btn').prop("disabled", true) ;
                  $('#loader').removeClass('hidden') ;
              },
              success: function(response) {
                if(response.status){
                  // Handle successful update
                  success_sweettoatr(response.msg);
                  location.reload();
                }else{
                    toastr.error(response.msg);
                }
              },
              error: function(response) {
                  $('input').removeClass('is-invalid');
                  $('.btn-outline-danger').prop("disabled", false);
                  $('.btn').prop("disabled", false);
                  $('#loader').addClass('hidden');

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
              }
          });
      });

      $('.form-control').focus(function(){
        $(this).removeClass('invalid');
        $(this).next('small').empty().hide();
      });

		$(document).on('click','.delete_rate',function(e){
			e.preventDefault();
			var q = confirm("Sure To Delete Rate ?")
			if(q){
				$.get($(this).attr('href'),'',function(response){
					if(response.status){
						success_sweettoatr(response.msg);
						location.reload();
					}else{
						toastr.error(response.msg);
					}
				});
			}
		});

      $('#password_form').submit(function(e){
        e.preventDefault();
        $('.form-control').removeClass('invalid');
        $('.alert-block').empty().hide();
        $.post($(this).attr('href'),$(this).serialize(),function(response){
            if(response.errors){
                $.each(response.errors,function (i,v){
                    $("#"+i).addClass('invalid');
                    $("#"+i+"_error").text(v).show(); 
                });
            }else{
                if(response.msg){
                    toastr.error(response.msg)
                }else{
                    $("#password_form").trigger('reset');
                    success_sweettoatr(response.success);
                }
            }
        });
      });

      $("#mpin_change").click(function(){
        $("#mpin_modal_body").load($(this).attr('href'));
      });
      
  });

</script>

@endsection

