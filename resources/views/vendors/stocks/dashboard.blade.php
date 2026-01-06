@extends('layouts.vendors.app')

@section('css')
<style>
  .data_ul{
    list-style:none;
    postion:relative;
  }
  .overlay_link{
    position: absolute;
    right: 0;
    left: 0;
    top: 0;
    bottom: 0;
    background: #00000042;
    padding-top:50%;
  }
  .overlay_link:hover{
    color:white;
  }
  .stock_info >li>h6{
    border-bottom:1px solid gray;
    text-align:center;
  } 
  .data_pane
</style>
@endsection

@section('content')

    @php

        //$data = component_array('breadcrumb' , 'Stocks',[['title' => 'Stocks']] ) ;

    @endphp

    {{--<x-page-component :data=$data />--}}
	@php 
$anchor = ['<a href="'.route('stocks.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>'];
$data = new_component_array('newbreadcrumb',"Stocks") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    @php 

      //dd($metalstocks);

    @endphp
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              {{--<div class="card-header">
			  <h3 class="card-title">Stocks <a href="{{ route('stocks.create') }}" class="btn btn-sm btn-primary"><li class="fa fa-plus"></li> Add New</a></h3>
              </div>--}}
              <div class="card-body row p-3" id="default_block">
                @foreach($metalstocks as $msk=>$metal)
                  @php 
                    $looseStocksum = $metal->stockexist->where('item_type', 'loose')->first()->total_avail ?? 0;
                    $looseStockfine = $metal->stockexist->where('item_type', 'loose')->first()->total_fine ?? 0;
                    $normalStocksum = $metal->stockexist->where('item_type', 'genuine')->first()->total_avail ?? 0;
                    $normalStockfine = $metal->stockexist->where('item_type', 'genuine')->first()->total_fine ?? 0;
                  @endphp
                <div class="col-md-3 m-auto">
                  <div class="card data_pane">
                    <div class="card-header  bg-secondary ">
                        <h3 class="card-title">{{ strtoupper($metal->name) }}</h3>
                    </div>
                    <div class="card-body p-2">
                        <ul class="row p-0 m-0 stock_info" style="list-style:none;position:relative;">
                          <li class="col-12 p-0"><h6 >GENUINE</h6></li>
                          <li class="col-6 p-0"><b>NET</b></li>
                          <li  class="col-6 text-right p-0">{{ $normalStocksum }} Grms</li>
                          <li class="col-6 p-0"><b>FINE</b></li>
                          <li class="col-6 text-right p-0">{{ $normalStockfine }} Grms</li>
                          <li class="col-12 p-0"><hr class="m-1" style="border-top:1px dashed gray;"></li>
                          <li class="col-12 p-0"><h6 >LOOSE</h6></li>
                          <li class="col-6 p-0"><b>NET</b></li>
                          <li  class="col-6 text-right p-0">{{ $looseStocksum }} Grms</li>
                          <li class="col-6 p-0"><b>FINE</b></li>
                          <li class="col-6 text-right p-0">{{ $looseStockfine }} Grms</li>
                        </ul>
                    </div>
                    <a href="{{ route('stocks.index',"stock={$metal->slug}") }}" class="overlay_link text-center" style="display:none;"><i class="fa fa-list"></i></a>
                  </div>
                </div>
                @endforeach


                <div class="col-md-3 m-auto">
                  <div class="card data_pane">
                    <div class="card-header  bg-secondary ">
                        <h3 class="card-title">Artificial</h3>
                    </div>
                    <div class="card-body p-2">
                      <ul class="row p-0 m-0 data_ul" style="list-style:none;position:relative;">
                        <li class="col-5"><b>COUNT</b></li>
                        <li  class="col-7 text-right">{{ $otherstock }}</li>
                      </ul>
                    </div>
                    <a href="{{ route('stocks.index','stock=artificial') }}" class="overlay_link text-center p-4" style="display:none;"><i class="fa fa-list"></i></a>
                  </div>
                </div>

				<div class="col-md-3 m-auto">
                  <div class="card data_pane">
                    <div class="card-header  bg-secondary ">
                        <h3 class="card-title">STONEs</h3>
                    </div>
                    <div class="card-body p-2">
                      <ul class="row p-0 m-0 data_ul" style="list-style:none;position:relative;">
                        <li class="col-5"><b>COUNT</b></li>
                        <li  class="col-7 text-right">{{ $stonestocks->num??0 }}</li>
                        <li class="col-5"><b>WEIGHT</b></li>
                        <li  class="col-7 text-right">{{ $stonestocks->total_avail??'0' }} Gm</li>
                      </ul>
                    </div>
                    <a href="{{ route('stocks.home','stone') }}" class="overlay_link text-center p-4" style="display:none;" id="stone_more_btn"><i class="fa fa-list"></i></a>
                  </div>
                </div>

              </div>
			  <div class="card card-default p-0 col-12" id="stone_block" style="display:none;">
                  <div class="card-header py-1">
                    <h6 class="card-title text-primary">
					<button type="button" class="btn btn-sm btn-outline-primary border" onclick="$('#stone_block').hide();$('#default_block').show();"><i class="fa fa-caret-left"></i></button>
						STONES
					</h6>
                  </div>
                  <div class="card-body row" id="stone_block_content">

                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    </div>

@endsection

  @section('javascript')
    <script>

      var route = "{{ route('stocks.index') }}";
	/*$(document).on('hover','.data_pane',function(){
        const over =  $(this).find('.overlay_link');
		over.toggle();
	});*/
	
	/*$('.data_pane').mouseleave(function(){
        const over =  $(this).find('.overlay_link');
		over.hide();
	});*/
      /*$('.data_pane').hover(function(){
		over.toggle();
        if(over.css('display')=='none'){
          over.show();
        }else{
          over.hide();
        }
      });*/
	  /*$(document).hover('.data_pane',function(){
		  $(this).find('.overlay_link').toggle();
	  });*/
	  /*$('.data_pane').hover(function(){
		  $(this).find('.overlay_link').toggle();
	  });*/
		$(document).on('mouseenter', '.data_pane', function() {
			$(this).find('.overlay_link').show();
		});

		$(document).on('mouseleave', '.data_pane', function() {
			$(this).find('.overlay_link').hide();
		});
	  $('#stone_more_btn').click(function(e){
        e.preventDefault();
        $.get($(this).attr('href'),'',function(response){
        $("#default_block").hide();
        $("#stone_block_content").html(response.html);
        $("#stone_block").show();
        });
      });
    </script>
@endsection
