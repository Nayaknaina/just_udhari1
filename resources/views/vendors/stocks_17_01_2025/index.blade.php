@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        $data = component_array('breadcrumb' , ' Product Stock',[['title' => 'Stocks']] ) ;

    @endphp

    <x-page-component :data=$data />

    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-body">

    <form action="{{ route('stocks.store') }}" method="post" id="counter_place_form">
        @csrf
        <div class="row">
            <div class="col-md-2  form-group">
              <label for="">Show entries</label>
              @include('layouts.theme.datatable.entry')
            </div>
			<div class="col-12 col-lg-2  form-group">
                <label for=""> Bill No. </label>
                <input type="text" id = "bill_no" class = "form-control" placeholder = "Search Bills" oninput="changeEntries()" >
            </div>
            <div class="col-md-6  form-group">
              <label for=""> Search Stock </label>
              <input type="text" id = "stocks" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
            </div>

          </div>
          <div  class="text-center col-12" id="loader"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></div>
          <div id = "pagination-result"></div>

          </div>

          <div class="modal" tabindex="-1" role="dialog" id="placement_model">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Counter Placement</h5>
                  <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="col-12 text-danger p-0">
                      <b><u>NOTE</u> : </b>
                      <small class="help-text">Enter New Name or Choose existing withh "&#x27A5;"</small>
                  </div>
                  <div class="form-group">
                    <label for="">Counter Name/Label</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <a href="{{ route('stock.counters') }}" class="btn btn-outline-secondary m-0 place_resource"  type="button" style="padding:0 5px;">
                              <span style="font-size:180%;">&#x27A5;</span>
                          </a>
                      </div>
                      <input type="text" class="form-control" placeholder="New Counter Name" id="counter" name="counter">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Box Name/Label</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <a href="{{ route('stock.boxes') }}" class="btn btn-outline-secondary m-0 place_resource" type="button" style="padding:0 5px;">
                              <span style="font-size:180%;">&#x27A5;</span>
                          </a>
                      </div>
                      <input type="text" class="form-control" placeholder="New Box Name" id="box" name="box">
                    </div>
                  </div>
                </div>
                <div class="modal-footer text-center">
                  <button type="submit" class="btn btn-secondary" >Place</button>
                </div>
              </div>
            </div>
          </div>


      </form>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>

<!-- <div class="modal" tabindex="-1" id="place_resource" style="background:#00000042">
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
</div> -->

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
@endsection

  @section('javascript')

    @include('layouts.theme.js.datatable')

    <script>

      var route = "{{ route('stocks.index') }}";

      function getresult(url) {
            $("#loader").show();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
				  "bill": $("#bill_no").val(),
                  "Stock_name": $("#stocks").val(),
              },
              success: function (data) {
                $("#loader").hide();
                  $("#pagination-result").html(data.html);
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

      // $(document).on('click','.stock_check',function(){
      //   var is_check = $('.stock_check:checked').length;
      //   if(is_check>0){
      //     $("#placer_button").attr({
      //         'data-toggle': 'modal',
      //         'data-target': '#placement_model'
      //     });
      //     $("#placer_button").removeClass('btn-disabled').addClass('btn-success');
      //   }else{
      //     $("#placer_button").removeAttr('data-toggle');
      //     $("#placer_button").removeAttr('data-target');
      //     $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
      //   }
      // });

      // $('.place_resource').click(function(e){
      //       e.preventDefault();
      //       //$("#place_modal_body").load($(this).attr('href'));
      //       $("#place_modal_body").empty().load($(this).attr('href'),"",function(){
      //           //$("#place_resource").modal();
      //       });
      //       $("#place_resource").modal();
      //   });

        // $(document).on('click','input.input_apply',function(){
        //   var ttrgt = $(this).data('target');
        //     $("#"+ttrgt).val($(this).val());
        // });

        // $("#counter_place_form").submit(function(e){
        //     e.preventDefault();
        //     $.post($(this).attr('action'),$(this).serialize(),function(response){
        //       if(response.valid){
        //         if(response.status){
        //           $("#placer_button").removeAttr('data-toggle');
        //           $("#placer_button").removeAttr('data-target');
        //           $("#placer_button").removeClass('btn-success').addClass('btn-disabled');
        //           $("#placement_model").modal('hide');
        //           success_sweettoatr(response.msg);
        //           $(document).find('.stock_check').each(function(i,v){
        //             if($(this).is(':checked')){
        //               $(this).remove();
        //             }
        //           });
        //         }else{
        //           toastr.error(response.msg) ;
        //         }
        //       }else{
        //         $.each(response.errors, function(field, messages) {
        //           $('[name="' + field + '"]').addClass('is-invalid') ;
        //           toastr.error(messages) ;
        //         });
        //       }
        //     });
        // });
    </script>

@endsection
