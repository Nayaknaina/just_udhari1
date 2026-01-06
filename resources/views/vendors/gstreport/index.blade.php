@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')

@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'GST Report',[['title' => 'GST Report']] ) ;

@endphp
<style>
.detail_info{
    border:1px solid lightgray;
    padding:0 2px;
    color:blue;
}
.detail_info:hover{
    color:black;
}
#data_area > tr.selected{
    box-shadow: 5px 2px 5px 5px gray;
}
#data_area > tr.selected > td{
    border-top:1px dashed blue;
    border-bottom:1px dashed blue;
    background: #d5f3ff;
}
.gst_block{
    list-style:none;
    position:relative;
    padding:0;
}
.gst_block > li > b,.gst_block > li > span{
    width:50%;
}
.gst_block > li > span{
    float:right;
    position:relative;
}
.gst_block > li > span:before{
    content:"-";
    position:absolute;
    left:0;
}
.summery{
    float:right;
    display:flex;
}
.sum_info{
    width:50%;
    border:1px solid lightgray;
}
</style>
{{--<x-page-component :data=$data />--}}
@php 
$data = new_component_array('newbreadcrumb',"Bill GST Report") 
@endphp 
<x-new-bread-crumb :data=$data /> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                <!-- general form elements -->
                    <div class="card ">
                        <div class="card-header">
						{{--<a class = "btn btn-outline-primary" href = "{{route('bills.create')}}"><i class="fa fa-plus"></i>  Create New </a>--}}
							<div class="summery text-center col-md-4 col-12 p-0 mt-1">
								<div class="sum_info text-success">
									<b class="col-md-6 col-12 p-0">RECEIVE : </b>
									<span class="col-md-6 col-12 p-0" id="gst_sum_get">0</span>
								</div>
								<div class="sum_info  text-danger">
									<b class="col-md-6 col-12 p-0">GIVEN : </b>
									<span class="col-md-6 col-12 p-0" id="gst_sum_grant">0</span>
								</div>
							</div>
                        </div>

                        <div class="card-body p-1">

                            <div class="col-12">
                                <form action="">
                                    <div class="row">
                                        <div class="col-5 col-lg-2 form-group p-1">
                                            <label for="">Type</label>
                                            <select name="" class="form-control" id="type" onchange="changeEntries()">
                                                <option value="">Select</option>
                                                <option value="get">Get</option>
                                                <option value="grant">Grant</option>
                                            </select>
                                        </div>
                                        <div class="col-7 col-lg-3 form-group p-1">
                                            <label for="">Source</label>
                                            <select name="source" class="form-control" id="source" onchange="changeEntries()">
                                                <option value="">Select</option>
                                                <option value="s">Sell</option>
                                                <option value="p">Purchase</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg-3  form-group p-1">
                                            <label for="">Reference</label>
                                            <input type="text" id = "reference" class="form-control" placeholder = "Reference Number" oninput="changeEntries()" >
                                        </div>
                                        <div class="col-12 col-lg-4  form-group p-1">
                                            <label for=""> Customer Name/Mobile </label>
                                            <input type="text" id = "customer" class = "form-control" placeholder = "Search customer Name/Mobile" oninput="changeEntries()" >
                                        </div>
                                        <div class="col-lg-4 col-12 p-1">
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
                                        <div class="col-6 col-lg-2 form-group p-1">
                                            <label for="">Show Entries</label>
                                            @include('layouts.theme.datatable.entry')
                                        </div>
                                    </div>
                                </form>
                            </div>
							<div class="col-12 p-0">
                                <table id="CsTable" class="table table_theme table-striped table-bordered text-nowrap align-middle dataTable">
                                    <thead class = "">
                                        <tr> <th style="">S.N.</th>
                                        <th style="width:10%">Entry</th>
                                        <th style="width:10%">Source</th>
                                        <th style="width:10%">Base</th>
                                        <th style="width:10%">GST</th>
                                        <th style="width:10%">GST %</th>
                                        <th style="width:10%">Person</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_area">

                                    </tbody>
                                </table>
								<div class="col-12" id="paging">
								</div>
							</div>
                        </div>
                    </div><!-- /.container-fluid -->
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>
    <div class="modal" tabindex="-1" role="dialog" id="info_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="info_head"></h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0" id="info_body">
                </div>
            </div>
        </div>
    </div>

@endsection

  @section('javascript')


  <script>

    var route = "{{ route('sells.index') }}";
    const  load_tr = '<tr><td colspan="8" class="text-center"> <span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span> </td></tr>';
    function getresult(url) {
        $("#data_area").html(load_tr);
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $(".entries").val(),
                "customer": $("#customer").val(),
                "bill": $("#bill").val(),
                "date_rage":$("#reportrange").val()
            },
            success: function (data) {
                $("#data_area").html(data.html) ;
                $("#paging").html(data.paging) ;
            },
            error: function () {},
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
	
	$.get("{{ route('gst.summery') }}","",function(response){
        $("#gst_sum_get").text(response.get??0);
        $("#gst_sum_grant").text(response.grant??0);
    });
	
    $(document).on('click','.detail_info',function(e){
        e.preventDefault();
        const head = $(this).data('head');
        $("#info_head").empty().text(head);
        $('#data_area > tr').removeClass('selected');
        $(this).closest('tr').addClass('selected');
        $('#info_body').empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i> Loading Content !</span></p>');
        $('#info_body').load($(this).attr('href'));
        $("#info_modal").modal('show');
    });

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
          });
		  cleardate(changeEntries);
  </script>

@include('layouts.theme.js.datatable')

@endsection
