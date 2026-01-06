@extends('layouts.vendors.app')

@section('content')

@php

$data = component_array('breadcrumb' , 'Purchase',[['title' => 'Purchase']] ) ;

@endphp
<style>
    td.unit{
        position:relative;
    }
    td.unit::after{
        position:relative;
        content:"Grm";
        font-size:10px;
        font-style:italic;
        color:blue;
        text-shadow:1px 2px 5px;
    }
</style>
<x-page-component :data=$data />
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
            <!-- left column -->
            <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><x-back-button /> New Placement </h3>
                </div>
                <form action="{{ route('stocks.store') }}" method="post" id="counter_place_form" class = "myForm" enctype="multipart/form-data">
                    <div class="card-body p-2">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-12 text-danger">
                                    <b><u>NOTE</u> : </b>
                                    <small class="help-text">Enter New Name or Choose existing withh "&#x27A5;"</small>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="counter">Counter Name/label</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <a href="{{ route('stock.counters') }}" class="btn btn-outline-secondary m-0 place_resource"  type="button" style="padding:0 5px;">
                                                    <span style="font-size:180%;">&#x27A5;</span>
                                                </a>
                                            </div>
                                            <input type="text" class="form-control" placeholder="New Counter Name" id="counter" class="counter" name="counter">
                                        </div>
                                        
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="box">Box Name/label</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <a href="{{ route('stock.boxes') }}" class="btn btn-outline-secondary m-0 place_resource" type="button" style="padding:0 5px;">
                                                    <span style="font-size:180%;">&#x27A5;</span>
                                                </a>
                                            </div>
                                            <input type="text" class="form-control" placeholder="New Box Name" id="box" class="box" name="box">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="items">Item/Products List</label>
                                        <div class="w-100 border-secondary table-responsive">
                                            <table class="table table-stripped table-bordered">
                                                <thead>
                                                    <tr class="bg-info">
                                                        <th style="">S.N.</th>
                                                        <th style="width:10%;">BILL</th>
                                                        <th style="width:50%;">NAME</th>
                                                        <th style="width:10%;">QUANTITY</th>
                                                        <!-- <th style="width:10%;">WEIGHT</th> -->
                                                        <th style="width:10%;">PLACEMENT</th>
                                                        <th class="text-center">&check;</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="items">
                                                    
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                        <div  class="text-center col-12" id="loader"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></div>
                                        <div class="col-12" id="table_data_pagination">
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" name="do" class="btn btn-danger">Place</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="modal" tabindex="-1" id="bill_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header p-2 bg-light">
            <h5 class="modal-title">Purchase Bill</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
        </div>
        <div class="modal-body p-2" id="bill_modal_body">
            
        </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="place_resource" style="background:#00000042">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header p-2 bg-light">
            <h5 class="modal-title">List</h5>
            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><li class="fa fa-times"></li></button>
        </div>
        <div class="modal-body p-2" id="place_modal_body">
        
        </div>
    </div>
  </div>
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function(e){

        $(document).on('focus','.form-control.is-invalid',function(){
            $(this).removeClass('is-invalid');
        });
        
        function getresult(url) {
            $("#loader").show();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "Stock_name": $("#stocks").val(),
              },
              success: function (data) {
                $("#loader").hide();
                $("tbody#items").html(data.html);
                $("#table_data_pagination").html(data.paging);
              },
              error: function (data) {
                $("#loader").hide();
              },
          });
      }

      getresult(url) ;

      $(document).on('click', '.pagination a', function (e) {

              e.preventDefault();
              var pageUrl = $(this).attr('href');
              getresult(pageUrl);

      });

      function changeEntries() {

      getresult(url) ;

      }

      $(document).on('click','.stock_check',function(){
        const ind = $(this).parent('label').parent('td').parent('tr').index();
        var read = ($(this).is(':checked'))?false:true;
        //if($('input[name="item_type['+ind+']"]').val()!='genuine'){
            $('input[name="item_type[]"]').eq(ind).prop('disabled',read);
            $('input[name="quantity[]"]').eq(ind).prop('disabled',read);
            if($('input[name="item_type[]"]').eq(ind).val()!='genuine'){
                $('input[name="quantity[]"]').eq(ind).select();
            }
        //}
      });

        $('.place_resource').click(function(e){
            e.preventDefault();
            var ttrgt = $(this).data('target');
            //$("#place_modal_body").load($(this).attr('href'));
            $("#place_modal_body").empty().load($(this).attr('href'),"",function(){
                //$("#place_resource").modal();
            });
            $("#place_resource").modal();
        });
        
        $(document).on('click','input.input_apply',function(){
            var ttrgt = $(this).data('target');
            $("#"+ttrgt).val($(this).val());
        });
        
        $("#counter_place_form").submit(function(e){
            e.preventDefault();
            if($('input[name="stock[]"]:checked').length>0){
                if(okquantity()){
                    $.post($(this).attr('action'),$(this).serialize(),function(response){
                      if(response.valid){
                        if(response.status){
                          $("#placer_button").removeAttr('data-toggle');
                          $("#placer_button").removeAttr('data-target');
                          $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
                          $("#placement_model").modal('hide');
                          success_sweettoatr(response.msg);
                          $(document).find('.stock_check').each(function(i,v){
                            if($(this).is(':checked')){
                                location.reload();
                              //$(this).replaceWith('<span style="color:green;">&check;</span>');
                            }
                          });
                        }else{
                          toastr.error(response.msg) ;
                        }
                      }else{
                        var pre_msg = "";
                        $.each(response.errors, function(field, messages) {
                            if(field.indexOf('.') !== -1){
                                var new_field = field.split('.');
                                    $('[name="' + new_field[0] + '[]"]').eq(new_field[1]).addClass('is-invalid') ;
                                    $.each(messages,function(ind,msg){
                                        toastr.error(msg);
                                    });
                            }else{
                                if(field=='quantity'){
                                    $('tbody>tr').each(function(trind,trval){
                                        var td_ind = ($('[name="item_type[]"]').eq(trind).val()!='other')?3:4;
                                        var avail_qnt = $(this).find('td').eq(td_ind).text();
                                        var place_qnt = $('[name="quantity[]"]').eq(trind).val();
                                        if(avail_qnt-place_qnt <0){
                                            $('[name="quantity[]"]').eq(trind).addClass('is-invalid');
                                        }
                                    });
                                }else{
                                    $('[name="' + field + '"]').addClass('is-invalid') ;
                                }
                                toastr.error(messages) ;
                            }
                        });
                      }
                    });
                }else{
                    toastr.error("Please Recheck The Quantity !") ;
                }
            }else{
                toastr.error("Please Select the Stock to Place !") ;
            }
        });

        function okquantity(){
            var ok = true;
            var focus = "";
            $('tbody>tr').each(function(trind,trval){
                var avail_qnt = $(this).find('td').eq(3).text();
                var place_qnt = $('[name="quantity[]"]').eq(trind).val();
                if(avail_qnt-place_qnt <0){
                    focus = $('[name="quantity[]"]').eq(trind);
                    if(trind==0){
                        focus.focus();
                    }
                    focus.addClass('is-invalid');
                    ok = false;
                }
            });
            return ok;
        }
    });
</script>
@endsection