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

    <form action="">

        <div class="row">

            <div class="col-6 col-lg-2 form-group">
            <label for="">Show entries</label>
            @include('layouts.theme.datatable.entry')
            </div>

            <div class="col-6 col-lg-4  form-group">
            <label for=""> Search Stock </label>
            <input type="text" id = "stocks" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
            </div>

        </div>

    </form>
 <div  class="text-center col-12" id="loader"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></div>
    <div id = "pagination-result"></div>

    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>
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

    </script>

@endsection
