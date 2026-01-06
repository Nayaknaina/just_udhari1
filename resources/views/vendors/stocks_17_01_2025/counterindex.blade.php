@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

    @php

        $data = component_array('breadcrumb' , 'Counters Stock',[['title' => 'Counters']] ) ;

    @endphp

    <x-page-component :data=$data />

    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="card ">
    <div class="card-header">
        <a class = "btn btn-outline-primary" href = "{{route('stocks.create')}}"><i class="fa fa-plus"></i> New Placement</a>
    </div>
    <div class="card-body">

    <form action="">

        <div class="row">

            <div class="col-6 col-lg-2 form-group">
            <label for="">Show entries</label>
            @include('layouts.theme.datatable.entry')
            </div>
			<div class="col-12 col-md-2 form-group">
                <label for="">Counter/Box</label>
                <input type="text" name="place" id="place" placeholder="Enter Label" class="form-control" oninput="changeEntries()" >
            </div>
            <div class="col-12 col-md-2 form-group">
                <label for="">Bill No.</label>
                <input type="text" name="bill" id="bill" placeholder="Enter Bill No." class="form-control" oninput="changeEntries()" >
            </div>
            <div class="col-6 col-lg-6  form-group">
            <label for=""> Search Stock </label>
            <input type="text" id = "stocks" class = "vin_no form-control" placeholder = "Search ...."  oninput="changeEntries()" >
            </div>

        </div>

    </form>
    <div  class="text-center col-12" id="loader"></div>
    <!-- <div class = "table-responsive"> -->
        <table id="CsTable" class="table table-striped table-bordered text-nowrap align-middle dataTable">
            <thead>
                <tr class = "bg-info">
                    <th>S.N.</th>
                    <th>COUNTER</th>
                    <th>BOX</th>
                    <th>ITEM</th>
                    <th>COUNT</th>
                    <th>WEIGHT</th>
                    <th>BILL</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="data_area">
                
            </tbody>
        </table>
    <!-- </div> -->

    </div>
    <div class="col-12" id="table_data_pagination" >
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
    <style>
        .bill_show{
            color:blue;
            border:1px solid lightgray;
            padding:1px 2px;
        }
        .bill_show:hover{
            color:black;            
        }
    </style>
@endsection


@section('javascript')

    @include('layouts.theme.js.datatable')

    <script>

      var route = "{{ route('stocks.index') }}";
      const  load_tr = '<tr><td colspan="8" class="text-center"> <span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span> </td></tr>';
      function getresult(url) {
            $("#data_area").html(load_tr);
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "counter": $("#counter").val(),
                  "box": $("#box").val(),
                  "item": $("#item").val(),
              },
              success: function (data) {
                $("#data_area").html(data.html);
                $("#table_data_pagination").html(data.paging);
              },
              error: function (data) {
                $("#data_area").html(data.html);
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
