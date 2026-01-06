@extends('layouts.vendors.app')
@section('css')

 @include('layouts.theme.css.datatable')

    <style>
      .bill_show{
          color:blue;
          border:1px solid lightgray;
          padding:1px 2px;
      }
      .bill_show:hover{
          color:black;            
      }
      td.grms{
          position:relative;
      }
      td.grms:before{
          position:absolute;
          content:'G';
          right:15px;
          color:gray;
      }
      td.active.grms:before{
          color:blue;
      }
      input.false{
          font-weight:bold;
          color:blue;
      }
      .placer_label{
        position: relative;
      }
      .placer_label > .inline_check_input{
        position:absolute;
        right:0;
        top:0;
      }
      span.unit{
        position: absolute;
        right:0;
        bottom:0;
        font-weight:normal;
      }
      span.unit.active{
        color:blue;
        text-shadow: 1px 2px 3px blue;
      }
  </style>
@endsection

@section('content')

@php
//$data = component_array('newbreadcrumb' , 'Stocks List',[['title' => 'Stocks']] ) ;
$data = new_component_array('newbreadcrumb' , 'Ecomm Not Listed') ;

@endphp
	<x-new-bread-crumb :data=$data />
    {{--<x-page-component :data=$data />--}}

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
          <div class="card ">
            <div class="card-body p-1">

              <div class="row">
				
                <div class="col-md-6  form-group">
                  <label for=""> Search Stock </label>
                  <input type="text" id = "stocks" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
                </div>
                <div class="col-12 col-lg-2  form-group">
                    <label for=""> Stock Type </label>
                    <select name="type" class="form-control" id="type" onchange="changeEntries()">
                      <option value="">Select</option>
                      <option value="gold">Gold</option>
                      <option value="silver">Silver</option>
					  <option value="artificial">Artificial</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2  form-group">
                    <label for=""> Bill No. </label>
                    <input type="text" id = "bill_no" class = "form-control" placeholder = "Search Bills" oninput="changeEntries()" >
                </div>
                <div class="col-md-2  form-group">
                  <label for="">Show entries</label>
                  @include('layouts.theme.datatable.entry')
                </div>
              </div>
              
              <table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable ">
                <thead class = "">
                  <tr> 
                    <th>S.N.</th>
                    <th>Bill</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Rate</th>
                    <th>Weight/Quantity</th>
                    <th>Purity</th>
                    <th>Amount</th>
                    <th>Ecomm</th>
                  </tr>
                </thead>
                <tbody id="data_area" > </tbody>
              </table>
              <div class="col-12" id="paging_area"> </div>
            </div>
          </div>

        </div>
      </div><!-- /.container-fluid -->
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
@endsection

@section('javascript')
@include('layouts.theme.js.datatable')

    <script>

      var route = "{{ route('stocks.index') }}";
      const loading_tr = '<tr><td colspan="9" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr);
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "bill": $("#bill_no").val(),
                  "type":$("#type").val(),
                  "stock_name": $("#stocks").val(),
              },
              success: function (data) {
                $("#loader").hide();
                $("#data_area").html(data.html);
                $("#paging_area").html(data.paging);
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
      
      $(document).on('click','.bill_show',function(e){
          e.preventDefault();
          $("#bill_modal_body").empty().load($(this).attr('href'));
          $("#bill_modal").modal();
      });
    
    </script>




@include('layouts.vendors.js.passwork-popup')

@endsection

