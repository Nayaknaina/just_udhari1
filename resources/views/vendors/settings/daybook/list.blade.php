@extends('layouts.vendors.app')

@section('stylesheet')
@endsection 

@section('content')
    @include('layouts.theme.css.datatable')
    @php 
		$anchor = ['<a href="'.route('shop.detail').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-eye"></i> Today</a>','<a href="'.route('shop.daybook.feed').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Feed</a>'];
		$data = new_component_array('newbreadcrumb',"Day-Book") 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="CsTable" class="table table_theme">
                                    <thead id="data_thead">
                                        <!-- First row: main groups -->
                                        <tr class="text-center">
                                            <th rowspan="2">SN.</th>
                                            
                                            <th colspan="2">GOLD</th>
                                            <th colspan="2">SILVER</th>
                                            <th colspan="2">STONE</th>
                                            <th colspan="2">ARTIFICIAL</th>
                                            <th colspan="2">MONEY</th>

                                            <th rowspan="2">DATE</th>
                                            <th rowspan="2">ACTION</th>
                                        </tr>

                                        <!-- Second row: opening/closing sub columns -->
                                        <tr class="text-center">
                                            <th>OPENING</th>
                                            <th>CLOSING</th>

                                            <th>OPENING</th>
                                            <th>CLOSING</th>

                                            <th>OPENING</th>
                                            <th>CLOSING</th>

                                            <th>OPENING</th>
                                            <th>CLOSING</th>

                                            <th>OPENING</th>
                                            <th>CLOSING</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="col-12 text-center" id="data_loader">
                                    <span class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Content...</span>
                                </div>
                            </div>
                            <div id="paging_area">
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
        $(document).ready(function(){

        });
        function getresult(url) {
			$("#allcheck").prop('checked',false);
			$("#paging_area").empty();
			$("#stock_sum > li > span").text('-');
			$('#CsTable').DataTable().destroy();
            $('#CsTable').find('tbody').remove();  // remove old tbody
			//$('#CsTable').find('tfoot').remove();  // 
            $("#data_loader").show();
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $("#entries").val()??null,
                },
                success: function (data) {
                    $("#data_loader").hide();
					$('.data_area').remove();
                    $(data.html).insertAfter('thead#data_thead');
                    $("#paging_area").html(data.paging);
					// $("#stock_sum_gross").text((data.stock_sum.sum_gross).toFixed(3)??'-');
					// $("#stock_sum_net").text((data.stock_sum.sum_net).toFixed(3)??'-');
					// $("#stock_sum_fine").text((data.stock_sum.sum_fine).toFixed(3)??'-');
                    $('#CsTable').DataTable();
                },
                error: function (data) {
                    $("#data_loader").hide();
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