@extends('layouts.vendors.app')

	@section('css')

		@include('layouts.theme.css.datatable')

		<style>
			#filter_block input,#filter_block select{
				height:32px;
			}
			#daterange-btn{
				height:32px!important;
			}
			#filter_block  #entries_block:after{
				content:'Entries';
				position:absolute;
				top:0;
				bottom:0;
				right:2px;
				font-weight:bold;
				align-content: center;
			}
			#filter_block  #entries_block select {
				appearance: none; /* Removes default arrow in modern browsers */
				-webkit-appearance: none; /* For Safari/Chrome */
				-moz-appearance: none; /* For Firefox */
				background: none; /* Optional: remove background if needed */
				line-height: initial;
			}
			tr.tr-deleted{
				opacity:0.3;
				pointer-events: none;
			}
		</style>
	@endsection

  @section('content')

@php 
	$data = new_component_array('newbreadcrumb',"Scheme Messages") 
@endphp 
<x-new-bread-crumb :data=$data /> 

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-light">
                        <div class="card-header py-1">
                            <div class="row" id="filter_block">
                                <div class="form-group mb-0 col-md-4 p-0">
                                    <input type="text" name="keyword" class="form-control h-32px" value="" placeholder="Find Keyword/Name" oninput="changeEntries()" id="keyword">
                                </div>
                                <div class="form-group mb-0 col-md-2 p-0">
                                    <input type="text" name="contact" class="form-control h-32px" value="" placeholder="Find Mobile" oninput="changeEntries()" id="contact">
                                </div>
                                <div class="form-group mb-0 col-md-1 p-0">
                                    <select name="status" class="form-control h-32px" oninput="changeEntries()" id="status">
                                        <option value="">Status ?</option>
                                        <option value='0'>Failed</option>
                                        <option value='1'>Success</option>
                                    </select>
                                </div>
                                <div class="form-group  mb-0 col-md-4 p-0">
                                    <div class="input-group">
                                        <button type = "button" class = "form-control float-right  h-auto" id = "daterange-btn" >
                                        <i class="far fa-calendar-alt" style="float:left;"></i>
                                        <span  id="daterange-text" >Start Date - End Date</span>
                                        <i class="fas fa-caret-down" style="float:right;"></i>
                                        </button>
                                        <input type="hidden" class="form-control"  id = "reportrange" value = ""  readonly onchange="changeEntries()" oninput="changeEntries()" >
                                    </div>
                                </div>
                                <div class="form-group  mb-0 col-md-1 p-0" id="entries_block">
                                    <select name="entries" class="form-control" oninput="changeEntries()" id="entries">
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table id="CsTable" class="table table_theme table-striped table-bordered text-wrap">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Section</th>
                                            <th>API</th>
                                            <th>Customer</th>
                                            <th>Message</th>
                                            <th>Status</th>
											<th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_area">
                                    </tbody>
                                </table>
								<div class="col-12 text-center" id="data_loader" style="display:none;">
									<i><span class="fa fa-spinner fa-spin"></span> Loading Content...</i>
								</div>
                            </div>
							<div class="col-12">
								<div id="paging_area">
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  @endsection

  @section('javascript')
	
	@include('layouts.theme.js.datatable')
    <script>
		function getresult(url) {
			$("#loader").show();
			$('#CsTable').DataTable().destroy();
			
			$("#data_loader").show();
			/*const loading_tr = '<tr><td colspan="8" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
			$("#data_area").html(loading_tr)*/
			$.ajax({
				url: url , // Updated route URL
				type: "GET",
				data: {
					"entries": $("#entries").val(),
					"keyword": $("#keyword").val()??false,
					"contact": $("#contact").val()??false,
					"status": $("#status").val()??false,
					"date": $("#reportrange").val()??false,
				},
				success: function (data) {
					$("#loader").hide();
					$("#data_loader").hide();
					$("#data_area").html(data.html);
					$('#CsTable').DataTable()
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
        $(document).ready(function(){
           
			
			/*$(document).on('click','.resend_message',function(e){
                e.preventDefault();
                $.get($(this).attr('href'),'',function(output){
                    const data =  output.response;
                    var msg_res = JSON.parse(data);
                    if(msg_res.return==true){
                        success_sweettoatr("Message Succesfully Resent !");
						//self_tr.addClass('tr-deleted');
                        location.reload();
                    }else{
                        toastr.error("Message Resending Failed !");
                    }
                });
            });
			
			$(document).on('click','.delete_message',function(e){
                e.preventDefault();
                const self_tr = $(this).closest('tr');
                $.get($(this).attr('href'),'',function(response){
                    if(response.success){
                        success_sweettoatr(response.success);
                        self_tr.addClass('tr-deleted');
                        //location.reload();
                    }else{
                        toastr.error(response.error)
                    }
                });
            });*/
			
			$(document).on('mpinVerified', function(e, response,triggerelement) {
                console.log(triggerelement);
                if(response.response){
                    const data =  response.response;
                    var msg_res = JSON.parse(data);
                    if(msg_res.return==true){
                        success_sweettoatr("Message Succesfully Resent !");
                        location.reload();
                    }else{
                        toastr.error("Message Resending Failed !");
                    }
                }else if(response.success){
                        success_sweettoatr(response.success);
                        triggerelement.closest('tr').addClass('tr-deleted');
                        //location.reload();
                    }else{
                        toastr.error(response.error)
                    }

             });
			
        });
    </script>
	 <script src = "https://onetaperp.com/plugins/moment/moment.min.js"></script>
    <!--<script src = "https://onetaperp.com/plugins/daterangepicker/daterangepicker.js"></script>-->
    <script src = "{{ asset('main/assets/js/onetaperp_daterangepicker.js')}}"></script>
    <script>
    $('#daterange-btn').daterangepicker({maxDate: moment(),},
            function (start, end) {
                $('#reportrange').val(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
                $('#daterange-text').html(start.format('YYYY/MM/D') + ' - ' + end.format('YYYY/MM/D'));
                $('#reportrange').trigger('change');
                //changeEntries() ;
            }
        );
        cleardate(changeEntries);
    </script>
	
  @endsection