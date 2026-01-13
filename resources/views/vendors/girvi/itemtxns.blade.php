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

@php $data = new_component_array('breadcrumb',"Girvi Ledger") @endphp
<x-new-bread-crumb :data=$data />
<style>
    .item_info > ul{
        list-style:none;
        /*font-size:80%;*/
    }
    .item_info > ul >li{
        overflow: auto;
    }
    .item_info > ul >li>span{
        float:right;
    }
    #girvi_num{
        text-align: center;
        border:1px dashed #f7c0a5 ;
        border-top:2px solid #f7c0a5 ;
        border-radius:15px;
        margin-bottom: 5px;;
    }
</style>
@php 
    //dd($item->customer)
@endphp
<section class="content">
    {{ $girvi }}
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <div class="col-md-3 mb-3">
                <div class="card round curve">
                    <div class="card-body pb-0 pt-2">
                        <div class="row item_info">
                            <ul class="p-1 col-12">
                                <li><b>NAME</b><span>{{ $item->customer->custo_name }}</span></li>
                                <li><b>MOBILE</b><span>{{ $item->customer->custo_mobile }}</span></li>
                            </ul>
                            <hr class="col-12 m-0 p-1">
                            <ul class="p-1 col-12 text-center">
                                <li id="girvi_num">
                                    GRV_I-{{ $item->receipt }}
                                </li>
                                <li>
                                    <img src="{{ asset($item->image) }}" class="img-responsive img-thumbnail">
                                </li>
                            </ul>
                            <ul class="p-1 col-12 m-0">
                                <li><b>ITEM</b><span>{{ $item->detail }}</span></li>
                                <li><b>TYPE</b><span>{{ $item->category }}</span></li>
                            </ul>
                            @if(in_array(strtolower($item->category),['gold','silver']))
                                @php 
                                    $item_prop = json_decode($item->property,true);
                                @endphp
                            <hr class="col-12 m-0 p-1">
                            <ul class="p-1 col-12 m-0">
                                <li><b>GROSS</b><span>{{ @$item_prop['gross']??'--' }} Gm.</span></li>
                                <li><b>NET</b><span>{{ @$item_prop['net']??'--' }} Gm.</span></li>
                                <li><b>PURE</b><span>{{ @$item_prop['pure']??'--' }} %</span></li>
                                <li><b>FINE</b><span>{{ @$item_prop['fine']??'--' }} Gm</span></li>
                            </ul>
                            @endif
                            <hr class="col-12 m-0 p-1">
                            <ul class="p-1 col-12 m-0">
                                <li><b>INTEREST</b><span>{{ $item->interest_type }}</span></li>
                                <li><b>RATE %</b><span>{{ $item->interest_rate }} %</span></li>
                                <li><hr class="m-0 p-0"></li>
                                <li><b>RATE</b><span>{{ $item->rate }} Rs.</span></li>
                                <li><b>VALUE</b><span>{{ $item->value }} Rs.</span></li>
                                @if($item->flip)
                                <li>
                                    <b>PRINCIPAL</b>
                                    <span>
                                        {{ $item->activeflip->post_p }} Rs.
                                        <hr class="m-0 p-0">
                                        <small class="text-danger"><strike>{{ $item->principal }} Rs.</strike></small>
                                    </span>
                                </li>
                                <li>
                                    <b>INTEREST</b>
                                    <span>
                                        {{ $item->activeflip->post_i }} Rs.
                                        <hr class="m-0 p-0">
                                        <small class="text-danger"><strike>{{ $item->interest }} Rs.</strike></small>
                                    </span>
                                </li>
                                @else 
                                <li>
                                    <b>PRINCIPAL</b>
                                    <span></span>
                                </li>
                                <li>
                                    <b>INTEREST</b>
                                    <span></span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
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
                                <div class="col-md-2 p-0">
                                    <select name="status" class="form-control btn-roundhalf h-32px border-dark" id="status" oninput="changeEntries()">
                                        <option value="">Status ?</option>
                                        <option value="1" class="text-danger">Paid</option>
                                        <option value="0" class="text-success">Unpaid</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-12 mb-2" >
                                    <div class="row"  id="filter_block">
                                        <div class="form-group col-6 p-0 m-0"  id="entries_block">
                                            <select name="entries" class="form-control btn-roundhalf h-32px border-dark" oninput="changeEntries()" id="entries">
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="col-6 p-0">
                                            <a href="#" class="form-control btn btn-sm btn-primary h-32px btn-roundhalf border-dark" style="align-content:center;">Export PDF</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0">
                                    <div class="table-responsive ">
                                        <!--<table class="custom-table  bg-header-primary">-->
                                        <table id="CsTable" class="table table_theme ">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>ITEM</th>
                                                    <th>Weight</th>
                                                    <th>Purity</th>
                                                    <th>Loan Amount</th>
                                                    <th>Interest % </th>
                                                    <th>Received Date </th>
                                                    <th>Return Date</th>
                                                    <th>Payment Status </th>
                                                    <th>Reminder </th>
                                                    <th>Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="txn_data_area">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="txn_paging">
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
        
        $.ajax({
            url: url , // Updated route URL
            type: "GET",
            data: {
                "entries": $("#entries").val(),
                "keyword": $("#keyword").val()??false,
                'status':$("#status").val()??false,
                "date": $("#reportrange").val()??false,
            },
            success: function (data) {
                $("#loader").hide();
                $("#txn_data_area").html(data.html);
                $("#txn_paging").html(data.paging);
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