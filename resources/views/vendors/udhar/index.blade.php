@extends('layouts.vendors.app')

@section('css')

@include('layouts.theme.css.datatable')
    <style>
        :root {
            --primary-color: #ffd30e; /* Gold */
            --primary-dark: #e6be00;
            --primary-light: #fffbeb;
            --secondary-color: #1e293b; /* Dark Slate */
            --accent-color: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.6);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        /* Glassmorphic Card Container */
        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Top Border */
        .glass-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
        }

        /* Filter Inputs (Pills) */
        .form-control-pill {
            border-radius: 50px !important;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1.2rem;
            padding-right: 2.5rem; /* Space for icon */
            font-size: 0.9rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
            height: 45px !important; /* Fixed Height */
            display: flex;
            align-items: center;
        }

        .form-control-pill:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        /* Search Icon Positioning */
        .search-icon {
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            font-size: 0.9rem;
            pointer-events: none;
        }

        /* Date Range Picker Pill */
        .daterange-pill {
            border-radius: 50px !important;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: auto; /* Changed from 100% */
            min-width: 200px; /* Ensure it's not too small */
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.2s;
            height: 45px !important; /* Fixed Height */
        }
        
        .daterange-pill:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Select Pill */
        select.form-control-pill {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
            appearance: none;
            padding-right: 2rem;
        }

        /* Entries Wrapper */
        .entries-wrapper {
            background: #fff;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            padding: 0 15px;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            width: auto;
            height: 45px !important; /* Fixed Height */
        }

    /* Search Box Wrapper */
        .customer-input-group {
            background: #fff;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            padding: 5px;
            transition: all 0.3s ease;
            height: 45px; /* Match height of other inputs */
            display: flex;
            align-items: center;
        }
        
        .customer-input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 211, 14, 0.15);
        }

        .input-group-prepend {
            display: flex;
            align-items: center;
            padding-left: 15px;
            padding-right: 10px;
            color: var(--secondary-color);
        }
        
        .customer-input-group .form-control {
            border: none;
            background: transparent;
            height: 100%; /* Fill parent */
            font-size: 1rem;
            box-shadow: none;
            padding-left: 0;
            color: #1e293b;
        }

        /* Table Responsive Wrapper */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden; /* Rounded corners for table */
        }
    </style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Udhar Record',[['title' => 'Udhar Record ']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-header-light shadow-sm"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.ledger').'" class="btn btn-header-light shadow-sm"><i class="fa fa-list"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"Udhar List") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- Glassmorphic Container -->
                    <div class="glass-container">
                        <div class="card-body row pt-0 p-0">
                            <div class="col-12 p-0">
                                <div class="row align-items-center mb-4">
                                    <!-- Search Keyword -->
                                    <div class="col-auto form-group m-0 mb-2 mb-md-0">
                                        <div class="input-group customer-input-group position-relative align-items-center" style="width: 300px;">
                                            <div class="input-group-prepend">
                                                <i class="fa fa-search"></i>
                                            </div>
                                            <input type="text" id="keyword" class="form-control" placeholder="Search Customer..." oninput="changeEntries()">
                                        </div>
                                    </div>
                                    
                                    <!-- Source Select -->
                                    <div class="col-auto form-group m-0 mb-2 mb-md-0">
                                        <select name="source" id="source" class="form-control form-control-pill" oninput="changeEntries()" style="width: auto; min-width: 120px;">
                                            <option value="">Source</option>
                                            <option value="udhar">Udhar</option>
                                            <option value="cut">Bhav Cut</option>
                                            <option value="sell">Sell</option>
                                        </select>
                                    </div> 
                                    
                                    <!-- Date Range -->
                                    <div class="col-auto form-group m-0 mb-2 mb-md-0">
                                        <div class="input-group">
                                            <button type="button" class="daterange-pill" id="daterange-btn">
                                                <span><i class="far fa-calendar-alt text-muted mr-2"></i> <span id="daterange-text">Select Date Range</span></span>
                                                <i class="fas fa-chevron-down text-muted ml-2" style="font-size: 0.8rem;"></i>
                                            </button>
                                            <input type="hidden" class="form-control"  id="reportrange" value="" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Entries Dropdown -->
                                    <div class="col-auto form-group m-0 ml-auto">
                                        <div class="entries-wrapper">
                                            @include('layouts.theme.datatable.entry')
                                            <label class="ml-2 mb-0 font-weight-bold text-muted" style="font-size: 0.85rem;">Entries</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive shadow-sm">  
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
            $("#CsTable").DataTable().destroy();
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
                $("#CsTable").DataTable();
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

