@extends('layouts.vendors.app')

@section('css')

@include('layouts.theme.css.datatable')
    <style>
        /*table>thead>tr>th,table>tfoot>tr>td{
            padding:5px!important;
            vertical-align:middle!important;
        }
        tbody#data_area>tr>td{
            text-align:center;
        }
        table>tbody>tr.foot>td{
            padding:5px 5px!important;
        }*/
    </style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Udhar Record',[['title' => 'Udhar Record ']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.ledger').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"Udhar List") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
					{{--<div class="card-header">
                            <h6 class="card-title col-12 p-0"><x-back-button /> Udhar Record  <a href="{{ route('udhar.create') }}" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-plus"></li> Add New</a></h6>
                        </div>--}}
                        <div class="card-body row pt-2">
                            <div class="col-12 p-0">
                                <div class="row">
                                    <div class="col-12 col-lg-4  form-group m-0">
                                    <input type="text" id = "keyword" class = "form-control" placeholder = "C. name/Number/Mobile (Enter Keyword )" oninput="changeEntries()" >
                                    </div>
									<div class="col-md-2 col-12 form-group m-0">
										<select name="source" id="source" class="form-control" oninput="changeEntries()" >
											<option value="">Source?</option>
											<option value="udhar">Udhar</option>
											<option value="cut">Bhav Cut</option>
											<option value="sell">Sell</option>
										</select>
									</div> 
                                    <div class="col-12 col-lg-4  form-group m-0">
                                        <div class="input-group">
                                            <button type = "button" class = "form-control float-right  h-auto" id = "daterange-btn" >
                                            <i class="far fa-calendar-alt" style="float:left;"></i>
                                            <span  id="daterange-text" >Start Date - End Date</span>
                                            <i class="fas fa-caret-down" style="float:right;"></i>
                                            </button>
                                            <input type="hidden" class="form-control"  id = "reportrange" value = ""  readonly >
                                        </div>
                                    </div>
                                    <div class="col-md-2  form-group m-0">
                                        <div class="input-group">
                                            @include('layouts.theme.datatable.entry')
                                            <div class="input-group-append">
                                                <label class="input-group-text" >Entry</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">  
                                    <table id="CsTable" class="table table_theme table-bordered table-stripped">
                                        <thead>
                                            <tr class="">
                                                <th colspan="3" ></th>
                                                <th colspan="4">Amount</th>
                                                <th colspan="4">Gold</th>
                                                <th colspan="4">Silver</th>
                                                <th colspan="2"></th>
                                            </tr>
                                            <tr class="">
                                                <th >SN.</th>
                                                <th>Date/Time</th>
                                                <th>C.No/C.Name/Source</th>
                                                <th>Old Amnt</th>
                                                <th>Amnt In</th>
                                                <th>Amnt Out</th>
                                                <th>Final Amnt</th>
                                                <th>Old Gold</th>
                                                <th>Gold In</th>
                                                <th>Gold Out</th>
                                                <th>Final Gold</th>
                                                <th>Old Silver</th>
                                                <th>Silver In</th>
                                                <th>Silver Out</th>
                                                <th>Final Silver</th>
                                                <th>Conversion</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_area">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12" id="paging_area">
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('javascript')

@include('layouts.theme.js.datatable')
@include('layouts.vendors.js.passwork-popup')
<script>
    var route = "{{ route('udhar.index') }}";
      const loading_tr = '<tr><td colspan="17" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr)
			$('tfoot').remove();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "keyword":$("#keyword").val(),
                  "date":$("#reportrange").val(),
				  "source":$("#source").val(),
              },
              success: function (data) {
                $("#loader").hide();
                //$("#data_area").html(data.html);
                $(document).find("tbody#data_area").replaceWith(data.html);
                $("#paging_area").html(data.paging);
                //$("#pagination-result").html(data.html);
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
<script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
<!--<script src = "https://onetaperp.com/plugins/daterangepicker/daterangepicker.js"></script>-->

<script src = "{{ asset('main/assets/js/onetaperp_daterangepicker.js')}}"></script>
<script>
  $('#daterange-btn').daterangepicker({},
        function (start, end) {
            $('#reportrange').val(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
            $('#daterange-text').html(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
            changeEntries() ;
		}
	);
	cleardate(changeEntries);
</script>
@endsection

