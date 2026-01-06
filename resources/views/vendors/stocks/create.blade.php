@extends('layouts.vendors.app')
@section('css')
@include('layouts.theme.css.datatable')
<style>
		ul.block_del_btn{
            list-style:none;
            display:inline-flex;
        }
        .block_del_btn{
            position:absolute;
            right:0px;
            text-shadow: 1px 2px 4px gray;
        }
        ul.block_del_btn>li:first-child{
            padding:0 5px;
            color:#f95600;
            font-weight:bold;
            font-size:20px;
            line-height:normal;
        }
      .block_sn{
          font-weight:bold;
          color:teal;
          background: #80808024;
      }
      /*.block_del_btn{
          position:absolute;
          right:5px;
      }*/
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
@endsection
@section('content')

@php

//$data = component_array('breadcrumb' , 'New Stock',[['title' => 'New Stock']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('stocks.home').'" class="btn btn-sm btn-outline-info"><i class="fa fa-object-group"></i> Stocks</a>'];
$path = ["Stocks"=>route('stocks.home')];
$data = new_component_array('newbreadcrumb',"New Stock",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
  <div class = "container-fluid">
      <div class = "row justify-content-center">
          <!-- left column -->
          <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

          <!--<div class="card-header">
          <h3 class="card-title"><x-back-button />  Create </h3>
          </div>-->

          <div class="card-body">

          <form id = "submitForm" method="POST" action="{{ route('stocks.store')}}" class = "myForm" enctype="multipart/form-data">

          @csrf

          @method('post')

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
						  <option value="stone">Stone</option>
                      </select></h4>
                      </li>
                      
                  </ul>
              </div>
          </div>
          <div id="entry_stock_area" class="col-12 px-2 m-0">

          </div>
          
          <div class="row" id="save_stock">
              <div class="col-12 text-center ">
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
  
</section>

@endsection

@section('javascript')
<script>
const edit = false;
</script>

@include('vendors.stocks.content.js_dynamicaction')

<script>
  $(document).ready(function() {

      $('.new_splr').blur(function(){
          $(this).removeClass('is-invalid')
      });
      
      $("#stocktype").change(function(){
          var sel = $(this).val();
          if(sel!=""){
              $("#entry_stock_area").empty().load("{{ route("stocks.forms") }}","form="+sel);
          }else{
              $("#entry_stock_area").empty();
          }
          
      });

		$("#stocktype").val('genuine');
		$("#stocktype").trigger('change');
		
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
              window.open("{{route('stocks.home')}}", '_self');
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
  
  
  function triggerselect(){
          $(document).find('select.select2').each(function(){
              $(this).select2();
          });
      }
</script>
@endsection

