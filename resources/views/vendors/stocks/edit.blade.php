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

//$data = component_array('breadcrumb' , 'Change Stock',[['title' => 'Stock']] ) ;

$stock_ttl_arr = ["artificial"=>"Artificial Jewellery","genuine"=>"Genuine Jewellery","loose"=>"Loose Stock",'stone'=>'Edit Stone'];
//$stock_cat = ($stock->item_type!='artificial')?$stock->category->slug:$stock->item_type;

$stock_cat = ($stock->item_type!='artificial')?(($stock->item_type!='stone')?$stock->category->slug:$stock->item_type):$stock->item_type;
@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('stocks.home').'" class="btn btn-sm btn-outline-info"><i class="fa fa-object-group"></i> Stocks</a>'];
$path = ["Stocks"=>route('stocks.home')];
$data = new_component_array('newbreadcrumb',"Edit Stock",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class = "content">
    <div class = "container-fluid">
        <div class = "row justify-content-center">
          <!-- left column -->
            <div class="col-md-12">
          <!-- general form elements -->
                <div class="card card-primary">

                    <!--<div class="card-header"><h3 class="card-title"><x-back-button />  Edit </h3></div>-->

                    <div class="card-body">

          <form id="submitform" method="POST" action="{{ route('stocks.update',$stock->id)}}" >

            @csrf

            @method('put')   

            <div class="row small_input "  style="border-bottom:1px solid lightgray;">
                <div class="col-12 bg-light mb-2 p-0" style="border:1px solid lightgray;">
                    <h4 class="m-2"> {{ $stock_ttl_arr["{$stock->item_type}"] }}</h4>
                </div>
                <div class="col-12 my-2" id="form_area">
                    @if($stock->item_type=='artificial')

                        @include("vendors.stocks.content.editartificialform")
						
					@elseif($stock->item_type=='stone') 

                        @include("vendors.stocks.content.editstoneform")
						
                    @else 

                        @include("vendors.stocks.content.editgenuineform")
                        
                    @endif
                </div>
            </div>
            
            <div class="row" >
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
	
    @if($stock_cat!='stone')
    @if($stock_cat != 'artificial')
        return_url+="&type={{ $stock->item_type }}"
    @endif
    @else 
        return_url+="&type={{ $stock->category_id }}"
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
<script>
    $(document).on("change",".del_ele",function(){
        $(this).parent('label').toggleClass('btn-outline-danger btn-danger');
        $(this).closest('div.row').toggleClass('disabled');
    });
    $("#ele_plus").click(function(){
        var html = '<div class="row bg-light element_block" style="border-top:1px dashed gray;">';
        html+'<input type="hidden" name="ele_ids[]" value="">';
        html+='<div class="col-md-5 form-group p-0">';
        html+='<label for="caret">Element/Stone</label>';
        html+='<input type="text" class="form-control text-center calculate" name="ele_name[]" id="ele_name" placeholder="Element/Stone Name" value="">';
        html+='</div>';
        html+='<div class="col-md-2 form-group p-0">';
        html+='<label for="purity">Caret</label>';
        html+='<input type="text" class="form-control text-center calculate" name="ele_caret[]" id="ele_caret" placeholder="Caret" value="">';
        html+='</div>';
        html+='<div class="col-md-2 form-group p-0">';
        html+='<label for="grs_wgt">Quantity</label>';
        html+='<input type="text" class="form-control text-center calculate" name="ele_quant[]" id="ele_quant" placeholder="Quantity (Count)" value="" >';
        html+='</div>';
        html+='<div class="col-md-3 form-group p-0">';
        html+='<label for="net_wgt">Cost</label>';
        html+='<input type="text" class="form-control text-center calculate" name="ele_cost[]" id="ele_cost" placeholder="Cost of All" value="">';
        html+='</div>';
        html+='<button type="button" class="ele_del btn btn-outline-danger px-1 py-0">&cross;';
        html+='</label>';
        html+='</div>';
        $(html).insertAfter("#element_header");
    });
    $(document).on('click','.ele_del',function(){
        $(this).closest('div.row').remove();
    })
</script>
@endsection

