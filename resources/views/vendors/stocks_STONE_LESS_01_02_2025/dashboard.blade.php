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
</style>
@endsection

@section('content')

    @php

        $data = component_array('breadcrumb' , 'Stocks',[['title' => 'Stocks']] ) ;

    @endphp

    <x-page-component :data=$data />
    @php 

      //dd($metalstocks);

    @endphp
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card">
              <div class="card-body row p-3">
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

      $('.data_pane').hover(function(){
        const over =  $(this).find('.overlay_link');
        if( over.css('display')=='none'){
          over.show();
        }else{
          over.hide();
        }
      });
    </script>
@endsection
