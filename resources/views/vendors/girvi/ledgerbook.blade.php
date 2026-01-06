@extends('layouts.vendors.app')

@section('css')
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
@php 
$anchor = ['<a href="'.route('girvi.create').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> New</a>','<a href="#" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> List</a>'];
$data = new_component_array('newbreadcrumb',"Girvi Ladger") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            {{--<div class="col-md-12 mb-3">
                <div class="card round curve">
                    <div class="card-body pb-0 pt-2">
                        <div class="row">
                            <ul class="col-md-7 info_ul p-0">
                                <li><b>NAME : </b><span id="sel_custo_name">  {{ @$girvicustomer->custo_name??'--' }}</span></li>
                                <li><b>MOBILE : </b><span id="sel_custo_mobile">  {{ @$girvicustomer->custo_mobile??'--' }}</span></li>
                                @php $custo_arr = ['c'=>'CUSTOMER','S'=>'SUPPLIER']; @endphp
                                <li><b>TYPE : </b><span id="sel_custo_type">  {{ @$custo_arr[$girvicustomer->custo_type]??"NA" }}</span></li>
                            </ul>
                            <ul class="col-md-5 info_ul p-0">
                                <li><b>PRINCIPAL : </b><span id="sel_custo_principle" class="text-{{ (@$girvicustomer->balance_principal<0)?'danger':'success' }}">  {{ $girvicustomer->balance_principal??'--' }} ₹</span></li>
                                <li><b>INTEREST : </b><span id="sel_custo_interest" class="text-{{ (@$girvicustomer->balance_interest<0)?'danger':'success' }}">  {{ $girvicustomer->balance_interest??'--' }} ₹</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>--}}
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
                                <div class="col-md-5 p-0 mb-1">
                                    <input type="text" class="form-control btn-roundhalf border-dark h-32px" id="keyword" value="" placeholder="Enter Keyword(Name/Mobile etc.)" oninput="changeEntries()">
                                </div>
                               <!-- <div class="col-md-2 p-0">
                                    <select name="status" class="form-control btn-roundhalf h-32px border-dark" id="status" oninput="changeEntries()">
                                        <option value="">Status ?</option>
                                        <option value="1" class="text-danger">Paid</option>
                                        <option value="0" class="text-success">Unpaid</option>
                                    </select>
                                </div>-->
                                <div class="col-md-2 col-12 offset-md-5 mb-2">
                                    <div class="row"  id="filter_block">
                                        <div class="form-group col-6 p-0 m-0"  id="entries_block">
                                            <select name="entries" class="form-control btn-roundhalf h-32px border-dark" oninput="changeEntries()" id="entries">
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="p-0  col-6">
                                            <a href="#" class="form-control btn btn-sm btn-primary h-32px btn-roundhalf border-dark" style="align-content:center;">Export PDF</a>
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
                                                <tr >
                                                    <th colspan="3" >INFO SECTION</th>
                                                    <th colspan="3">GIRVI</th>
                                                    <th colspan="2">PAID</th>
                                                    <th colspan="3">BALANCE</th>
                                                </tr>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Customer</th>
                                                    <th>Contact</th>
                                                    <th>ITEMS</th>
                                                    <th>PRINCIPAL</th>
                                                    <th>INTEREST</th>
                                                    <th>PRINCIPAL</th>
                                                    <th>INTEREST</th>
                                                    <th>PRINCIPAL</th>
                                                    <th>INTEREST</th>
                                                    <th><i class="fa fa-eye"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="ladger_data_area">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="ladger_paging" class='col-12'>
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
        const loading_tr = '<tr><td colspan="11" class="text-center"><span class="p-1" style="background:lightgray;"><li class="fa fa-spinner fa-spin"></li> Loading Content..</span></td></tr>';
        $("#ladger_data_area").html(loading_tr)
		if ($.fn.DataTable.isDataTable('#CsTable')) {
            $('#CsTable').DataTable().destroy();
        }
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $("#entries").val(),
                "keyword": $("#keyword").val()??false,
                'status':$("#status").val()??false,
            },
            success: function (data) {
                $("#loader").hide();
                $("#ladger_data_area").html(data.html);
				$('#CsTable').DataTable();
                $("#ladger_paging").html(data.paging);
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
            $('#reportrange').trigger('change');
            //changeEntries() ;
        }
    );
    cleardate(changeEntries);
</script>
@endsection