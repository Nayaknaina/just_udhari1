@extends('layouts.vendors.app')

@section('css')

@include('layouts.theme.css.datatable')
<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
<style>
    .dropdown.sub_drop_over {
        position: absolute;
        top: 0;
        right: 0;
    }
    .dropdown.sub_drop_over>.dropdown-menu {
        width: auto;
        min-width: unset;
    }
</style>


@include('layouts.theme.css.datatable')
@endsection

@section('content')

@php $data = new_component_array('breadcrumb',"Girvi Ledger") @endphp
<x-new-bread-crumb :data=$data />
<style>
.info_ul{
    list-style:none;
    display: flex;
}
.info_ul > li{
    margin:auto;
    width:auto;
}
.info_ul > li >span{
    float:right;
}
#filter_block  #entries_block:after{
    content:'Entries';
    position:absolute;
    top:0;
    bottom:0;
    right:5px;
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
.select2.select2-container{
    width:100%!important;
}
td >ul{
    padding:0;
    margin:0;
    list-style:none;
    text-align:center;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card round curve">
                    <div class="card-body pt-1">
                        <div class="mb-4">
                            <!-- <div class="">
                                <h6 class="fs-15 mb-0 section-title">Current Record :</h6>
                            </div> -->
                            <div class="row">
                                <!--<div class="col-md-3 p-0 mb-1">
                                    <select class="form-control select2  h-32px border-dark" id="customer" oninput="changeEntries()">
                                        <option value="">Select Customer</option>
                                    </select>
                                </div>-->
                                <div class="col-md-4 p-0 mb-1">
                                    <input type="text" class="form-control btn-roundhalf border-dark h-32px" id="keyword" value="" placeholder="Enter Keyword" oninput="changeEntries()">
                                </div>
                                <div class="col-md-3 p-0 mb-1">
                                    <div class="input-group m-0">
                                        <button type="button" class="form-control float-right  h-32px border-dark" id="daterange-btn">
                                        <i class="far fa-calendar-alt" style="float:left;"></i>
                                        <span id="daterange-text">Start Date - End Date</span>
                                        <i class="fas fa-caret-down" style="float:right;"></i>
                                        </button>
                                        <input type="hidden" class="form-control" id="reportrange" value="" readonly="" onchange="changeEntries()" oninput="changeEntries()">
                                    </div>
                                </div>
                                <!--<div class="col-md-1 p-0">
                                    <select name="mode" class="form-control btn-roundhalf h-32px border-dark" id="mode" oninput="changeEntries()">
                                        <option value="">Mode ?</option>
                                        <option value="on" >Online</option>
                                        <option value="off" >Cash</option>
                                    </select>
                                </div>
                                <div class="col-md-2 p-0">
                                    <select name="operation" class="form-control btn-roundhalf h-32px border-dark" id="operation" oninput="changeEntries()">
                                        <option value="">Operation ?</option>
                                        <option value="GG" >Girvi Grant</option>
                                        <option value="GI" >Girvi Interest</option>
                                    </select>
                                </div>
                                <div class="col-md-1 p-0">
                                    <select name="status" class="form-control btn-roundhalf h-32px border-dark" id="holder" oninput="changeEntries()">
                                        <option value="">Holder ?</option>
                                        <option value="B">Bank</option>
                                        <option value="S" >Shop</option>
                                    </select>
                                </div>-->
                                <div class="col-md-2 col-12 mb-2 text-center offset-md-3" >
                                    <div class="row"  id="filter_block">
                                        <div class="form-group col-6 p-0 m-0"  id="entries_block">
                                            <select name="entries" class="form-control btn-roundhalf h-32px border-dark" oninput="changeEntries()" id="entries">
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="col-6 p-0">
                                            <div class="dropdown">
                                                <button class="btn  btn btn-sm btn-primary dropdown-toggle h-32px btn-roundhalf border-dark" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Export <i class="fa fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">PDF</a>
                                                    <a class="dropdown-item" href="#">CSV</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0">
                                    <div class="table-responsive ">
                                        <!--<table class="custom-table  bg-header-primary">-->
                                        <table id="CsTable" class="table table_theme">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>DATE</th>
                                                    <th>Customer</th>
                                                    <th>Contact</th>
                                                    <th>Principal</th>
                                                    <th>Interest</th>
                                                    <!--<th>Operation</th>
                                                    <th>Holder</th>-->
                                                    <th>PAY</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list_data_area">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="list_paging" class="col-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.theme.js.datatable')
@include('layouts.vendors.js.passwork-popup')

<script>
    
    function getresult(url) {
        $("#loader").show();
        const loading_tr = '<tr><td colspan="8" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
        $("#list_data_area").html(loading_tr)
        if ($.fn.DataTable.isDataTable('#CsTable')) {
            $('#CsTable').DataTable().destroy();
        }
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $("#entries").val(),
                "keyword": $("#keyword").val()??false,
                "date": $("#reportrange").val()??false,
            },
            success: function (data) {
                $("#list_data_area").html(data.html);
                $('#list_paging').html(data.paging);
                //renderdatatable();
                $("#CsTable").DataTable();
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
            $('#reportrange').trigger('change');
            //changeEntries() ;
        }
    );
    cleardate(changeEntries);
</script>
@endsection