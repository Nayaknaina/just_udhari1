@extends('layouts.vendors.app')

@section('css')

    @include('layouts.theme.css.datatable')
    <style>
    .detail_show{
      border:1px solid lightgray;
      color:blue;
      padding:2px;
    }
    .detail_show:hover{
      color:black;
      border:1px solid blue;
    }
    </style>
@endsection

@section('content')

@php

//$data = component_array('breadcrumb' , 'Order TXN Detail',[['title' => 'Ecomm-Orders']] ) ;

@endphp

{{--<x-page-component :data=$data />--}}
@php 
$anchor = ['<a href="'.route('ecomorders.products').'" class="btn btn-sm btn-outline-info"><i class="fa fa-list"></i> All Orders</a>'];
$path = ["Ecomm Orders"=>route('ecomorders.products')];
$data = new_component_array('newbreadcrumb',"Ecomm Order Txns",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <!-- left column -->
                <div class="col-md-12">
                <!-- general form elements -->
                    <div class="card card-primary">
                        <!--<div class="card-header">
                        <h5 class="card-title"><x-back-button />Transaction Detail </h5>
                        </div>-->
                        <div class="card-body p-2">
                            <div class="col-12">
                                <div class = "row">
                                    <div class="col-md-7">
                                        <h5>Shiping Detail</h5>
                                        <hr class="m-1 p-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="row p-0" style="list-style:none;">
                                                    <li class="col-md-4 col-12"><b>NAME</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_full_name }}</li>
                                                    <li class="col-md-4 col-12"><b>CONTACT</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_fone }}</li>
                                                    <li class="col-md-4 col-12"><b>E-MAIL</b></li>
                                                    <li class="col-md-8 col-12 text-right">{{ $order->customer->custo_mail??'-----' }}</li>
                                                </ul> 
                                            </div>
                                            @php 
                                                $address = $state = $district = $area = $tehsil = $pincode = null;
                                                if($order->ship_id==1){
                                                    $address = $order->customer->shiping->custo_address;
                                                    $state = $order->customer->shiping->state_name;
                                                    $district = $order->customer->shiping->dist_name;
                                                    $area = $order->customer->shiping->area_name;
                                                    $tehsil = $order->customer->shiping->teh_name;
                                                    $pincode = $order->customer->shiping->pin_code;
                                                }else{
                                                    $address = $order->customer->custo_address;
                                                    $state = $order->customer->state_name;
                                                    $district = $order->customer->dist_name;
                                                    $area = $order->customer->area_name;
                                                    $tehsil = $order->customer->teh_name;
                                                    $pincode = $order->customer->pin_code;
                                                }
                                            @endphp 
                                            <div class="col-md-6">
                                                <ul class="row p-0" style="list-style:none;">
                                                    <li class="col-md-4 col-12"><b>STATE</b></li>
                                                    <li class="col-md-8 col-12 text-right">
                                                        {{ $state }}
                                                    </li>
                                                    <li class="col-md-4 col-12"><b>DISTRICT</b></li>
                                                    <li class="col-md-8 col-12 text-right">
                                                        {{ $district }}
                                                    </li>
                                                    <li class="col-md-4 col-12"><b>AREA</b></li>
                                                    @php 
                                                        $loc = trim($tehsil);
                                                        $loc.=($loc!="")?"/":"";
                                                        $loc.=trim($area);
                                                        $loc.=($loc!="")?"({$pincode})":$pincode;
                                                    @endphp
                                                    <li class="col-md-8 col-12 text-right">
                                                        {{ $loc }}
                                                    </li>
                                                </ul> 
                                            </div>
                                            <div class="col-12">
                                                <b>ADDRESS</b><address>{{ $address }}</address>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <h5>Order</h5>
                                        <hr class="m-1 p-0">
                                        <ul class="row p-0" style="list-style:none;">
                                            <li class="col-md-4 col-12"><b>NUMBER</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ $order->order_unique }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>DATE</b></li>
                                            <li class="col-md-8 col-12 text-right">
                                                {{ $order->created_at }}
                                            </li>
                                            <li class="col-md-4 col-12"><b>PAYMENT</b></li>
                                            @php
                                                $color_arr = ["warning","success","danger"];
                                                $txt_arr = ["Pending","Paid","Unpaid"];
                                            @endphp
                                            <li class="col-md-8 col-12 text-right text-{{ $color_arr[$order->pay_status] }}">
                                            <b>{{ $txt_arr[$order->pay_status] }}</b>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-1 p-0" style="border-top:1px dashed gray;">
                            <div class="col-12">
                                <form action=""> 
                                    <div class="row">
                                        <div class="col-12 col-md-4 p-0">
                                            <label>Day Wise</label>
                                            <div class="input-group">
                                                <button type = "button" class = "form-control float-right" id = "daterange-btn">
                                                <i class="far fa-calendar-alt" style="float:left;"></i>
                                                <span  id="daterange-text">Start Date - End Date</span>
                                                <i class="fas fa-caret-down" style="float:right;"></i>
                                                </button>
                                                <input type="hidden" class="form-control"  id = "reportrange" value = ""  readonly >
                                            </div>
                                        </div>                                        
                                        <div class="col-12 col-md-3 p-0  form-group">
                                            <label for="">Txn Number </label>
                                            <input type="text" id = "txn" class = "vin_no form-control" placeholder = "Search Transaction"  oninput="changeEntries()" name="txn">
                                        </div>
                                        <div class="col-12 col-md-2 p-0">
                                        <label for="">Status </label>
                                        <select id="status" name="status" class="form-control" onchange="changeEntries()" >
                                            <option value="">Select</option>
                                            <option value="1">Paid</option>
                                            <option value="0">Unpaid</option>
                                        </select>
                                        </div>
                                        <div class="col-12 col-md-2  form-group p-0">
                                            <label for="">Medium </label>
                                            <input type="text" id = "medium" class = "vin_no form-control" placeholder = "Enter Medium"  oninput="changeEntries()" name="medium">
                                        </div>
                                        <div class="col-12 col-md-1 form-group p-0">
                                        <label for="">Entries</label>
                                        @include('layouts.theme.datatable.entry')
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 p-0">
                                <div class="table-responsive">
                                    @php 
                                        $total = 0;
                                    @endphp
                                    @if($order->txns->count()>0)
                                        <table class="table table_theme table-bordered table-stripped dataTable custom_scroll">
                                            <thead>
                                                <tr class="">
                                                    <th>SN</th>
                                                    <th>Entry</th>
                                                    <th>By</th>
                                                    <th>Order Num.</th>
                                                    <th>TXN Num.</th>
                                                    <th>Amount</th>
                                                    <th>Mode</th>
                                                    <th>Medium</th>
                                                    <th>Response</th>
                                                    <th>Status</th>
                                                    <!-- <th>Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody id="txn_body">
                                            
                                            </tbody>
                                        </table>
                                    @else 
                                            <div class="alert alert-warning">No Order Detail</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12" id="paginate">
                                </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
    </section>

    </div>
  
@endsection

  @section('javascript')
  <script>
  const get_route = "{{ route("ecomorders.productordertxns",$order->id) }}";

function getresult(url) {
  const load_tr = '<tr><td class="text-center" colspan="9"><span style="background: lightgray;font-size:15px;"><li class="fa fa-spinner fa-spin"></li>Loading Content..</span></td></tr>'
  console.log($("#name").val()) ;
  $("#data_area").empty().append(load_tr);
$.ajax({
    url: url , // Updated route URL
    type: "GET",
    data: {
        "entries": $(".entries").val(),
        'date':$("#reportrange").val(),
        "status": $("#status").val(),
        "txn": $("#txn").val(),
        "medium":$("#medium").val()
       
    },
    success: function (data) {
        $("#txn_body").html(data.html);
        $("#paginate").html(data.paginate);
    },
    error: function () {},
});
}

getresult(get_route);

$(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var pageUrl = $(this).attr('href');
        getresult(pageUrl);

    });

function changeEntries() {

  getresult(get_route);

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
          });
          cleardate(changeEntries);
  </script>
  @endsection