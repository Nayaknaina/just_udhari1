@extends('layouts.vendors.app')

@section('css')
<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
@include('layouts.theme.css.datatable')
<style>
    :root {
        --primary-color: #ffd30e; /* Gold */
        --primary-dark: #bfa300;
        --secondary-color: #1e293b; /* Dark Slate */
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --light-bg: #f8fafc;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --radius: 1rem;
    }

    body {
        background-color: #e0e0e4;
        font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Glassmorphic Card */
    .card {
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
    }

    /* Search Box Wrapper */
    .customer-input-group {
        background: #fff;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 5px;
        transition: all 0.3s ease;
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
        height: 30px;
        font-size: 1rem;
        box-shadow: none;
        padding-left: 0;
        color: #1e293b;
    }

    /* Table Styling */
    .table_theme thead th {
        background-color: #1e293b;
        color: #ffd30e;
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .table_theme tbody td {
        vertical-align: middle;
        padding: 1rem;
        border-color: #f1f5f9;
        font-weight: 500;
        color: #334155;
    }

    .table-responsive {
        border-radius: var(--radius);
        overflow: hidden;
    }

    /* Entries Wrapper */
    .entries-wrapper {
        background: #fff;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        padding: 4px 15px;
        display: inline-flex; /* Changed from flex */
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        transition: all 0.2s;
        width: auto; /* Ensure auto width */
    }

    .entries-wrapper:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .entries-wrapper select {
        border: none;
        background: transparent;
        font-weight: 600;
        color: #1e293b;
        outline: none;
        cursor: pointer;
        padding-right: 20px; /* Space for chevron */
    }

    .entries-wrapper label {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Override include Select styles if needed */
    .entries-wrapper select.entries {
        border: none !important;
        padding: 0 !important;
        height: auto !important;
        box-shadow: none !important;
        margin-right: 8px;
    }

    ul.pagination{
        margin-bottom:5px;
        font-size:90%;
        justify-content: center;
    }
    .page-link {
        border-radius: 50% !important;
        margin: 0 2px;
        border: none;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e293b;
        font-weight: 600;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: black;
    }
</style>
@endsection

@section('content')

@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-sm btn-outline-primary shadow-sm" style="border-radius: 50px;"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.index').'" class="btn btn-sm btn-outline-secondary shadow-sm" style="border-radius: 50px;"><i class="fa fa-list"></i> List</a>'];
$data = new_component_array('newbreadcrumb',"Udhar Ledger") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0">
                        <div class="card-body pt-4">
                                <div class="row align-items-center mb-4">
                                    <div class="col-12 col-lg-5 form-group mb-3 mb-lg-0">
                                        <div class="input-group customer-input-group position-relative align-items-center">
                                            <div class="input-group-prepend">
                                                <i class="fa fa-search"></i>
                                            </div>
                                            <input type="text" id="keyword" class="form-control" placeholder="Search by Name/Mobile..." oninput="changeEntries()">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12 form-group offset-lg-4 mb-0 text-right">
                                        <div class="entries-wrapper">
                                            @include('layouts.theme.datatable.entry')
                                            <label class="ml-2">Entries</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive ">  
                                    <table id="CsTable" class="table table_theme  table-stripped" >
                                        <thead>
                                            <tr class="">
                                                <th >SN.<i class="fa fa-envelope"></i></th>
                                                <!--<th>DAte/Time</th>-->
                                                <th>Name/No.</th>
                                                <th>Contact No.</th>
                                                <th>AMOUNT</th>
                                                <th>GOLD</th>
                                                <th>SILVER</th>
                                                <th><i class="fa fa-eye"></i></th>
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
<script>
    var route = "{{ route('udhar.ledger') }}";
      const loading_tr = '<tr><td colspan="8" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr);
			//$('tfoot').remove();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "keyword":$("#keyword").val(),
              },
              success: function (data) {
                $("#loader").hide();
                /*$("#data_area").html(data.html);*/
                $(document).find("#data_area").replaceWith(data.html);
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
	  
	  $(document).on('change','.sms_send_check',function(){
        if($('.sms_send_check:checked').length >0){
            $('#sms_send_btn').removeClass('btn-outline-info').addClass('btn-info');
        }else{
            $('#sms_send_btn').removeClass('btn-info').addClass('btn-outline-info');
        }
      });
	  
	  $(document).on('click','#sms_send_btn',function(e){
        e.preventDefault();
        if($('.sms_send_check:checked').length >0){
            const path = $(this).attr('href');
            var custos = $('.sms_send_check:checked').serialize();
            var csrf = '{{ csrf_token() }}';
            custos+= '&_token='+encodeURIComponent(csrf);
            $.post(path,custos,function(response){
				if(response.success){
					success_sweettoatr(response.success);
					$('.sms_send_check').prop('checked',false);
					$("#sms_send_btn").removeClass('btn-info').addClass('btn-outline-info');
				}else{
					toastr.error(response.errors);
				}
            });
        }else{
            toastr.error("Select The Customer First !");
        }
      });
	  
</script>
@endsection