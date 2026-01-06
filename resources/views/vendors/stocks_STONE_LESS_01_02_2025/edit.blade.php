@extends('layouts.vendors.app')

@section('css')
<style>
    .stock_block{
        box-shadow:1px 2px 3px 5px lightgray;
        border-top:1px dashed gray;
        padding:5px;
    }
    .main_bill_block>div{
        padding:0 2px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
    -moz-appearance: textfield;
    }
</style>
@endsection 
@section('content')


@php

$data = component_array('breadcrumb' , 'Change Stock',[['title' => 'Stock']] ) ;

$stock_ttl_arr = ["artificial"=>"Artificial Jewellery","genuine"=>"Genuine Jewellery","loose"=>"Loose Stock"];
$stock_cat = ($stock->item_type!='artificial')?$stock->category->slug:$stock->item_type;
@endphp

<x-page-component :data=$data />

<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
          <!-- left column -->
            <div class="col-md-12">
          <!-- general form elements -->
                <div class="card card-primary">

                    <div class="card-header"><h3 class="card-title"><x-back-button />  Edit </h3></div>

                    <div class="card-body">

          <form id="submitform" method="POST" action="{{ route('stocks.update',$stock->id)}}" >

            @csrf

            @method('put')   

            <div class="row small_input ">
                <div class="col-12 bg-light mb-2 p-0" style="border:1px solid lightgray;">
                    <h4 class="m-2"> {{ $stock_ttl_arr["{$stock->item_type}"] }}</h4>
                </div>
                <div class="col-12 my-2" id="form_area">
                    @if($stock->item_type=='artificial')

                        @include("vendors.stocks.content.editartificialform")

                    @else 

                        @include("vendors.stocks.content.editgenuineform")
                        
                    @endif
                </div>
            </div>
    
            <div class="row">
                <div class="col-12 text-center my-3 ">
                    <button type = "submit" class="btn btn-danger" name="change" value="stock"> Submit </button>

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
    var return_url = "{{ route('stocks.index',"stock={$stock_cat}") }}";
    @if($stock_cat != 'artificial')
        return_url+="&type={{ $stock->item_type }}"
    @endif
    $("#caret").on('input',function(){
        var caret = $(this).val()??0;
        if(caret!="" && caret!=0){
            var one = 100/24;
            $("#purity").val(Number((caret*one).toFixed(2)));
        }else{
            $("#purity").val("");
        }
        calculate();
    });

    $("#purity").on('input',function(){
        var purity = $(this).val()??0;
        const ind = $('.purity').index($(this));
        if(purity!="" && purity!=0){
            var one = 100/24;
            $("#caret").val(Number((purity/one).toFixed(2)));
        }else{
            $("#caret").val("");
        }
        calculate();
    });

    $('.calculate').on('input',function(){
        calculate();
    });

    function calculate(){
        var ntwght = $("#net_wgt").val()??0;
        var pur = $("#purity").val()??0;
        var wstg = $("#wstg").val()??0;
        var rate = $("#rate").val()??0;
        var lbr = $("#chrg").val()??0;
        var fnwght = 0;
        var fnpure = 0;
        var amount = 0;
        if((ntwght !="" && ntwght !=0) && (pur !="" && pur !=0) && (wstg !="" && wstg !=0)){
            fnpure = +nmFixed(pur) + +nmFixed(wstg);
            fnwght = ntwght*fnpure/100;
            amount = +(rate * fnwght) + parseFloat((lbr*ntwght).toFixed(3));
        }
        $("#fine_purity").val(Number((fnpure).toFixed(2)));
        $("#fine_wgt").val(Number((+fnwght).toFixed(2)));
        $("#amount").val(Number((+amount).toFixed(0)));
    }

    $('#submitform').submit(function(e){
        e.preventDefault();
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.errors){
                $.each(response.errors,function(ele,val){
                    var field = $('[name="'+ele+'"]');
                    field.addClass('is-invalid');
                    toastr.error(val);
                });
            }else{
                if(response.status){
                    location.href = return_url;
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(response.msg)
                }
            }
        });
    });

</script>

@endsection

