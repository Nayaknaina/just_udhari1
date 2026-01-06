@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')
    <style>
    .detail_show{
      border:1px solid lightgray;
      color:blue;
      padding:2px;
    }
    .detail_show:hover{
      color:black;
      border:1px solid blue;
    }
    </style>
@endsection

@section('content')

@php

$data = component_array('breadcrumb' , 'E-Comm Order List',[['title' => 'Ecomm-Orders']] ) ;

@endphp

<x-page-component :data=$data />
 @php 
$data = new_component_array('newbreadcrumb',"Ecomm Scheme Orders") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <!--<div class="card card-primary">
    <div class="card-header">
    <h5 class="card-title">Scheme Ordered</h5>
    </div>-->

    <div class="card-body p-2">

    <div class="col-12">
    <form action=""> 
      <div class="row">
        <div class="col-12 col-md-4  form-group p-0">
          <label for="">Customer </label>
          <input type="text" id = "name" class = "vin_no form-control" placeholder = "Search Name/Mobile"  oninput="changeEntries()" >
        </div>
        <div class="col-12 col-md-2  form-group p-0">
          <label for="">Amount </label>
          <input type="text" id = "amount" name="amount" class = "form-control" placeholder = "Enter Amount"  oninput="changeEntries()" >
        </div>
        <div class="col-12 col-md-1 p-0">
          <label for="">Status </label>
          <select id="status" name="status" class="form-control"  onchange="changeEntries()">
          <option value="">Select</option>
          <option value="1">Paid</option>
          <option value="0">Unpaid</option>
          </select>
        </div>
        <div class="col-12 col-md-4 p-0">
        <label>Day Wise</label>
          <div class="input-group">
            <button type = "button" class = "form-control float-right  h-auto" id = "daterange-btn" >
              <i class="far fa-calendar-alt" style="float:left;"></i>
              <span  id="daterange-text" >Start Date - End Date</span>
              <i class="fas fa-caret-down" style="float:right;"></i>
            </button>
            <input type="hidden" class="form-control"  id = "reportrange" value = ""  readonly >
          </div>
        </div>

        <div class="col-12 col-md-1 form-group p-0">
          <label for="">Entries</label>
          @include('layouts.theme.datatable.entry')
        </div>
      </div>
    </form>
    </div>

    <div class = "col-12 p-0">
      <table id="CsTable" class="table table_theme table-bordered table-hover">
        <thead class = "">
            <tr> <th style="">S.N.</th>
            <th style="width:10%">Entry</th> 
            <th style="width:10%">Scheme</th> 
            <th style="width:10%">Customer</th> 
            <th style="width:10%">Amount</th>
            <th style="width:10%">Status</th>
            <th style="width:10%">TXNs</th>
            <!-- <th style="border:1px solid;width:10%">Mark</th> -->
            </tr>
        </thead>
        <tbody id="data_area">
        </tbody>
      </table>
    </div>
    <div class="col-12" id = "paginate">
	</div>
    </div>
    </div>

    </div>
    </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    </section>

    </div>
  
@endsection

  @section('javascript')


  <script>

    const get_route = "{{ route('ecomorders.schemes') }}";

    function getresult(url) {
      const load_tr = '<tr><td class="text-center" colspan="9"><span style="background: lightgray;font-size:15px;"><li class="fa fa-spinner fa-spin"></li>Loading Content..</span></td></tr>'
      console.log($("#name").val()) ;
      $("#data_area").empty().append(load_tr);
    $.ajax({
        url: url , // Updated route URL
        type: "GET",
        data: {
            "entries": $(".entries").val(),
            "date":$("#reportrange").val(),
            "status":$("#status").val(),
            "amount":$("#amount").val(),
            "custo": $("#name").val(),
           
        },
        success: function (data) {
            $("#data_area").html(data.html);
            $("#paginate").html(data.paginate);
        },
        error: function () {},
    });
    }
    
    getresult(get_route);

    $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);
    
        });

    function changeEntries() {

      getresult(get_route);

    }

  </script>
@include('layouts.theme.js.datatable')
  <script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
<!--<script src = "https://onetaperp.com/plugins/daterangepicker/daterangepicker.js"></script>-->
<script src = "{{ asset('main/assets/js/onetaperp_daterangepicker.js')}}"></script>
  <script>
  $('#daterange-btn').daterangepicker({},
          function (start, end) {
              $('#reportrange').val(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
              $('#daterange-text').html(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
              changeEntries() ;
          });
		  cleardate(changeEntries);
  </script>


  @endsection