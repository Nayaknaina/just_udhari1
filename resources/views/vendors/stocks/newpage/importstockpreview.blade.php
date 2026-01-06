@extends('layouts.vendors.app')

@section('content')

    @php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Import Stock",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 

    {{--<x-page-component :data=$data />--}}
    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-default">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-6 form-inline mb-1">
							<div class="input-group px-1">
                                <div class="input-group-text p-1">
                                    <label for="stock_type" class="m-0">Stock</label>
                                </div>
                                <label type="text" class="form-control text-center w-auto">{{ ucfirst($stock_type)}}</label>
                            </div>
                            <div class="input-group px-1">
                                <div class="input-group-text p-1">
                                    <label for="stock_type" class="m-0">Entry Num. </label>
                                </div>
                                <label type="text" class="form-control text-center w-auto">{{ $entry_num}}</label>
                            </div>
                        </div>  
                        <div class="col-md-6 form-inline  mb-1">
                            <div class="input-group px-1" style="margin-left:auto;">
                                <div class="input-group-text p-1">
                                    <label for="entries" class="m-0">Record </label>
                                </div>
                                <select name="entries" id="entries" class="form-control">
                                    <option value="10" >10</option>
                                    <option value="20">20</option>
                                    <option value="50" selected>50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>  
                        <div class="col-12">  
                            <div class="table-responsive">
                                <table id="CsTable" class="table table-bordered  table_theme mb-1">
                                    <thead id="data_thead"> 
                                        <tr>
                                            <th>NAME</th>
                                            <th>TAG</th>
                                            <th>HUID</th>
                                            <th>CARET</th>
                                            <th>GROSS</th>
                                            <th>LESS</th>
                                            <th>NET</th>
                                            <th>TUNCH</th>
                                            <th>WASTAGE</th>
                                            <th>FINE</th>
                                            <th>ST.CH.</th>
                                            <th>LBR</th>
                                            <th>OTHER</th>
                                            <th>RATE</th>
                                            <th>DISC</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="col-12 text-center" id="data_loader">
                                    <span><i class="fa fa-spinner fa-spin "></i> Loading Content...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="paging_area"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('javascript')
    <script>
        function getresult(url) {
            $(document).find(".data_area").remove();
            $("#data_loader").show();
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $("#entries").val(),
                },
                success: function (data) {
                    $("#data_loader").hide();
                    
                    $(data.html).insertAfter($("#data_thead"));
                    //$("#data_area").replaceWith(data.html);
                    $("#paging_area").html(data.paging);
                    //$("#pagination-result").html(data.html);
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