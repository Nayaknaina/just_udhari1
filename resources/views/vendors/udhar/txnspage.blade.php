@extends('layouts.vendors.app')

@section('css')
@include('layouts.theme.css.datatable')
    <style>
        .bg-danger{
           background-color: #fbd3d7 !important;
        }
        ul.txn_summery_ul{
            list-style:none;
            padding:0;
            font-weight:bold;
            margin:0;
        }
        ul.txn_summery_ul>li{
            border-bottom:1px dashed gray;
        }
        /* table > thead >tr >th{
            text-wrap:nowrap;
        } */
        table > tbody >tr >td{
            /* text-wrap:nowrap; */
            padding:5px!important;
        }
		.note{
            position:relative;
        }
        .note:before{
            content:"\270D";
            content:"\2712";
            /* content:"\2711"; */
            position:absolute;
            top:0;
            font-size: x-large;
            bottom:0;
            padding:0 2px;
            color:white;
            background: #8c3deb;
            border-radius: 15px 0 0 15px;
            font-weight:bold;
        }
        .note>input{
            padding-left:30px;
            color:blue!important;
        }
    </style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Customer Transaction',[['title' => 'Customer Transactions ']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('udhar.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="'.route('udhar.index').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> List</a>','<a href="'.route('udhar.ledger').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"Customer Udhar Txns") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-secondary">
                        <div class="card-header p-1">
                            <ul class="row m-0" style="list-style:none;position:relative;padding:0;">
                                <!--<li class="col-12 col-md-1 text-white p-0">
                                    <x-back-button />
                                </li>-->
                                <li class="col-12 col-md-8 p-0" style="align-content: center;">
                                <h5 class="text-secondary m-0">
                                    {{ $customer_ac->custo_num}}/{{ $customer_ac->custo_name }}({{ $customer_ac->custo_mobile }})
                                </h5>
                                </li>
                                <li class="col-12 col-md-4 text-center p-0">
                                    <div class="input-group">
                                        <button type = "button" class = "form-control float-right  h-auto" id = "daterange-btn" >
                                        <i class="far fa-calendar-alt" style="float:left;"></i>
                                        <span  id="daterange-text" >Start Date - End Date</span>
                                        <i class="fas fa-caret-down" style="float:right;"></i>
                                        </button>
                                        <input type="hidden" class="form-control"  id = "reportrange" value = ""  readonly >
                                    </div>
                                </li>
                                {{--<a href="{{ route('udhar.create') }}" class="btn btn-sm btn-primary m-0" style="position:absolute;top:0;right:0;"><li class="fa fa-plus"></li> New</a>--}}
                            </ul>
                            <!--<h5 class="card-title col-12 p-0 m-0"><x-back-button /> <span class="text-secondary"> {{ $customer_ac->custo->custo_num}}/{{ $customer_ac->custo_name }}({{ $customer_ac->custo_mobile }})</span> 
                            <div class="filter-area">
                            </div>
                            <a href="{{-- route('udhar.create') --}}" class="btn btn-sm btn-primary" style="float:right;"><li class="fa fa-plus"></li> Add New</a></h5>-->
                        </div>
                        <div class="card-body row">
                            <div class="col-12 p-0">
                                <div class="table-responsive">  
                                    <table id="CsTable"  class="table table_theme table-bordered table-stripped" >
                                        <thead>
                                            <tr class="">
                                                <th colspan="3" ></th>
                                                <th colspan="2">AMOUNT (&#8377;)</th>
                                                <th colspan="2">GOLD (gm)</th>
                                                <th colspan="2">SILVER (gm)</th>
                                                <th>BALANCE</th>
                                                <th>CONVERSION</th>
                                            </tr>
                                            <tr class="">
                                                <th >SN.</th>
                                                <th>Date/Time</th>
                                                <th>Source/Remark</th>
                                                <th>Amount Out (-)</th>
                                                <th>Amount In (+)</th>
                                                <th>Gold Out (-)</th>
                                                <th>Gold In (+)</th>
                                                <th>Silver Out (-)</th>
                                                <th>Silver In (+)</th>
                                                <th>Gold/Silver/Amount</th>
                                                <th>Bhav Cut</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_area">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12" id="paging_area">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4 my-2">
										<form action="{{ route('udhar.note.drop') }}" id="note_form">
                                            <div class="w-100 note" >
                                                <input type="hidden" name="ac" value="{{ $customer_ac->id }}" >
                                                <input type="text" name="note" id="leave_note" class="form-control" placeholder="Notes" value="{{ $customer_ac->udhar_note }}">
                                                {{--<a href="#" class="btn btn-secondary form-control"><i class="fa fa-caret-up"></i> Sticky Note <span class="badge badge-info">1</span></a>--}}
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4 my-2">
                                        <ul class="row p-0 text-center m-0" style="list-style:none;">
                                            <li class="col-6">
                                            <a href="{{ route('udhar.txn.print',['size'=>'mini','ac'=>$customer_ac->id]) }}" class="btn btn-outline-info" target="_blank"><i class="fa fa-print"></i> Mini</a>
                                            </li>
                                            <li class="col-6">
                                            <a href="{{ route('udhar.txn.print',['size'=>'a4','ac'=>$customer_ac->id]) }}" class="btn btn-outline-dark" target="_blank"><i class="fa fa-print"></i> A4</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 my-2">
										<button type="button" class="btn btn-outline-danger mb-1 form-control" data-toggle="modal" data-target="#confirmDeleteModal" data-delete-url="{{ route('udhar.destroy',$id) }}">
											Clear All Transactions
										</button>
                                    </div>
                                </div>
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
function renderdatatable() {

    $('#CsTable').DataTable({
        destroy: true,
        fixedHeader: true,
        scrollX: true,
        scrollY: 400,
        "searching": false ,
        "bPaginate": false,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false ,
        dom: 'Bfrtip',
        buttons: [
            'excel'
        ]
    });
};
</script>

<script>
    var route = "{{ route("udhar.txns",[$customer_ac->id]) }}";
      const loading_tr = '<tr><td colspan="11" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
      function getresult(url) {
            $("#loader").show();
            $("#data_area").html(loading_tr);
			$('tfoot').remove();
          $.ajax({
              url: url , // Updated route URL
              type: "GET",
              data: {
                  "entries": $(".entries").val(),
                  "date":$('#reportrange').val()
              },
              success: function (data) {
                $("#loader").hide();
                //$("#data_area").html(data.html);
                $("#data_area").replaceWith(data.html)
                renderdatatable();
                //$("#paging_area").html(data.paging);
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

    
	$("#note_form").trigger('reset');
	$("#leave_note").focusout(function(e){
		e.preventDefault();
		var path = $('#note_form').attr('action');
		var data = $('#note_form').serialize();
		data+="&_token={{ csrf_token() }}";
		$.post(path,data,function(response){
			if(response.success){
				toastr.success(response.success);
			}else{
				if(response.errors){
					toastr.error(response.errors);
				}
			}
		});
	});
	
</script>
<script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
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