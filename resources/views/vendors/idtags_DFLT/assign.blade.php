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
<style>
    .head_info_title > h6{
        text-align:center;
        background: wheat;
        padding: 5px;
        border-radius:5px;
        font-weight:bold;
    }
    .info_plate{
        height:50px;
    }
    .vertical-ribbon-custom{
        /* position: absolute; */
        top: 0;
        right: 0;
        width: 120px;
        /* height: 45px; */
        background: #e63946;
        color: white;
        text-align: center;
        /* writing-mode: vertical-rl; */
        transform: rotate(0deg);
        font-size: 14px;
        font-weight: bold;
        line-height: 20px;
        padding: 5px 0;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        z-index: 2;
        margin-left: auto;
    }
    table{
        border-collapse: separate;
        width:100%;
        border-spacing: 0 5px!important;
    }
    tbody > tr > td{
        padding:2px 0;
        border-top:1px solid lightgray;
        border-bottom:1px solid lightgray;

    }
    tr > td >ul{
        list-style:none;
        padding:0;
        align-content: center;
        margin:0;
    }
    tr > td >ul > li{
        margin:2px 0;
    }
    tr > td:nth-child(1){
        width:10%;
        border-top-left-radius: 10px; 
        border-bottom-left-radius: 10px; 
        border-left:1px solid lightgray;
    }
    tr > td:nth-child(2){
        width:40%;
        border-top:1px solid lightgray;
        border-bottom:1px solid lightgray;
    }
    tr > td:nth-child(3){
        width:50%;
        border-top:1px solid lightgray;
        border-bottom:1px solid lightgray;
        border-right:1px solid lightgray;
        border-top-right-radius: 10px; 
        border-bottom-right-radius: 10px; 
    }
    ul.stock_path{
        list-style:none;
        padding:0;
        width:100%;
        overflow:hidden;
    }
    ul.stock_path > li{
        margin:auto;
        width:inherit;
        position: relative;
        padding-left: 10px;
        text-shadow:1px 2px 3px white;
    }
    ul.stock_path >li:after{
        position: absolute;
        content:"";
        height:auto;
        width:20px;
        bottom:0;
        top:0;
        right:-5px;
        transform: rotate(45deg);
        z-index:1;
    }
    ul.stock_path >li:last-child{
        background:#e6e6e6;
    }
    ul.stock_path >li:last-child:after{
        content:unset;
    }
    ul.stock_path >li:nth-child(1){
        background:#afafaf;
    }
    ul.stock_path >li:nth-child(1):after{
        background:#afafaf;
    }
    ul.stock_path >li:nth-child(2){
        background:lightgray;
    }
    ul.stock_path >li:nth-child(2):after{
        background:lightgray;
    }
    @media only screen and (max-width: 400px){
        .vertical-ribbon-custom,.head_info_title{
            margin:auto;
        }
        .head_info_title > h6{
            width:100%;
            font-size:150%;
            margin-bottom:0;
        }
        .info_plate{
            height:unset;
        }
    }
</style>
@endsection

@section('content')

@php $data = new_component_array('breadcrumb',"RFID & Barcode") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
             @include('vendors.idtags.commonfilter')
            
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card round curve  p-2">
                            <div class="card-header bg-primary-light  p-0 stock_head">
                                <ul class="info_plate p-0 d-flex flex-wrap w-100 m-0" style="list-style:none;"> 
                                    <li class="head_info_title p-1"style="align-content:center;">
                                        <h6 class="text-info">Availbale <span class="badge badge-light avl_count text-dark">0</span></h6>
                                    </li>
                                    <li class="vertical-ribbon-custom">
                                        <b>WEIGHT</b>
                                        <hr class="m-1">
                                        <span><i id="avl_wght">0.0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <div class=" table-responsive">
                                    <table class="table m-0">
                                        <tbody id="avail_stock">
                                            <tr>
                                                <th class="text-center" >
                                                    <span class="text-primary">
                                                        <i class="fa fa-question-circle"></i> Select Code to List Stock !
                                                    </span>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card round curve  p-2">
                            <div class="card-header bg-primary-light  p-0 stock_head">
                                <ul class="info_plate p-0 d-flex flex-wrap w-100 m-0" style="list-style:none;"> 
                                    <li class="head_info_title p-1"style="align-content:center;">
                                        <h6 class="text-success">Scanned <span class="badge badge-light scan_count text-dark">0</span></h6>
                                    </li>
                                    <li class="vertical-ribbon-custom">
                                        <b>WEIGHT</b>
                                        <hr class="m-1">
                                        <span><i id="scan_wght">0.0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <div class=" table-responsive">
                                    <table class="table m-0">
                                        <tbody id="scan_stock">
                                            <tr>
                                                <td class="sn">0</td>
                                                <td>
                                                    <ul>
                                                        <li>ID : LI22K</li>
                                                        <li>NAME : Ring</li>
                                                    </ul>
                                                </td>
                                                    <td>
                                                    <ul>
                                                        <li>CODE : 9856564343</li>
                                                        <li>WIGHT : 10.90Gm</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card round curve  p-2">
                            <div class="card-header bg-primary-light  p-0 stock_head">
                                <ul class="info_plate p-0 d-flex flex-wrap w-100 m-0" style="list-style:none;"> 
                                    <li class="head_info_title p-1"style="align-content:center;">
                                        <h6 class="text-danger">Missed <span class="badge badge-light miss_count text-dark">0</span></h6>
                                    </li>
                                    <li class="vertical-ribbon-custom">
                                        <b>WEIGHT</b>
                                        <hr class="m-1">
                                        <span><i id="miss_wght">0.0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 py-3">
                                <div class=" table-responsive">
                                    <table class="table m-0">
                                        <tbody id="miss_stock">
                                            <tr>
                                                <td class="sn">0</td>
                                                <td>
                                                    <ul>
                                                        <li>ID : LI22K</li>
                                                        <li>NAME : Ring</li>
                                                    </ul>
                                                </td>
                                                    <td>
                                                    <ul>
                                                        <li>CODE : 9856564343</li>
                                                        <li>WIGHT : 10.90Gm</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
<script>
    const loader_tr = '<tr><th class="text-center"><span class="text-primary"><i class="fa fa-spinner fa-spin"></i> Loading Stock !</span></th></tr>';
    function data(){
        var url_add_on = "";
        var metal = $("#metal").val()??false;
        if(metal){
            url_add_on+="metal="+metal+"&";
        }
        var type = $("#type").val()??false;
        if(type){
            url_add_on+="type="+type+"&";
        }
        var code = $("#tag").val()??false;
        if(code){
            url_add_on+="code="+code+"&";
        }
        var key = $("#keyword").val()??false;
        if(key){
            url_add_on+="keyword="+key+"&";
        }
        if(url_add_on!=""){
            url_add_on = url_add_on.replace(/&$/, '');
            url_add_on = "?"+url_add_on;
        }
        let path = "{{ route('idtags.stock') }}"+url_add_on;
        let item_row = '';
        let num = 0;
        let ttl_gross = 0;
        let ttl_net = 0;
        
        $("#avail_stock").empty().append(loader_tr);
        $.get(path,'',function(response){
            if(response.path!=""){
                item_row+='<tr><th colspan="3" class="p-0"><ul class="stock_path d-flex m-0">'+response.path+'</ul></th></tr>';
            }
            if(response.data.length > 0){
                $.each(response.data,function(i,v){
                    const code_value = v[code];
                    let prop = JSON.parse(v.property);
                    let gross = (prop)?prop.gross_weight:"";
                    let net = (prop)?prop.net_weight:"";
                    num = i + 1;
                    item_row+= '<tr id="avail_'+code+'_'+code_value+'">';
                    item_row+='<td class="sn">'+ num +'</td>';
                    item_row+='<td>';
                    item_row+='<ul>';
                    item_row+='<li>NAME : '+v.product_name+'</li>';
                    item_row+='<li>ID : '+v.product_code+'</li>';
                    item_row+='</ul>';
                    item_row+='</td>';
                    item_row+='<td>';
                    item_row+='<ul>';
                    item_row+='<li>WIGHT : '+gross+"/"+net+'Gm</li>';
                    item_row+='<li>CODE : '+code_value+'</li>';
                    item_row+='</ul>';
                    item_row+='</td>';
                    item_row+='</tr>';
                    ttl_gross+= +gross;
                    ttl_net+= +net;
                });
                //$("#avail_stock").empty().append(item_row);
                $(".avl_count").empty().append(num);
                $('#avl_wght').empty().append(ttl_gross+"/"+ttl_net);
            }else{
                item_row += '<tr><th  class="text-center"><span class="text-primary"><i class="fa fa-info-circle"></i> No Stock !</span></th></tr>';
            }
            $("#avail_stock").empty().append(item_row);
        });
    }
    $(document).on('change','#tag',function(){
        data();
    });
    $(document).on('change','.jewellery_cat',function(){
        data();
    });
    $(document).on('input','#keyword',function(){
        data();
    });

    
    $(document).ready(function() {
        $("#scannedinput").focus();

        $("document").on("keypress", function(e) {
            if (e.which === 13) { // Enter key
                var code = $("#tag").val()??false;
                if(code){
                    var codevalue = $(this).val()??false;
                    if(codevalue!=""){
                        let item_row = "";
                        let url_add_on = "";
                        let ttl_gross = 0;
                        let ttl_net = 0;
                        let num = 0;
                        let match_id = "";
                        url_add_on+="code="+code+"&value="+codevalue;
                        $("#scan_stock").empty().append(loader_tr);
                        $.get("{{ route('idtags.scane') }}?"+url_add_on,"",function(response){
                            if(response.data.length > 0){
                                const code_value = v[code];
                                match_id = code_value;
                                let prop = JSON.parse(v.property);
                                let gross = (prop)?prop.gross_weight:"";
                                let net = (prop)?prop.net_weight:"";
                                num++;
                                item_row+= '<tr id="scane_'+code+'_'+code_value+'">';
                                item_row+='<td class="sn">'+ num +'</td>';
                                item_row+='<td>';
                                item_row+='<ul>';
                                item_row+='<li>NAME : '+v.product_name+'</li>';
                                item_row+='<li>ID : '+v.product_code+'</li>';
                                item_row+='</ul>';
                                item_row+='</td>';
                                item_row+='<td>';
                                item_row+='<ul>';
                                item_row+='<li>WIGHT : '+gross+"/"+net+'Gm</li>';
                                item_row+='<li>CODE : '+code_value+'</li>';
                                item_row+='</ul>';
                                item_row+='</td>';
                                item_row+='</tr>';
                                ttl_gross+= +gross;
                                ttl_net+= +net;
                                $(".scan_count").empty().append(num);
                                $('#scan_wght').empty().append(ttl_gross+"/"+ttl_net);
                            }
                            let avail_wght = $("#avl_wght").val();
                            let wght_arr = avail_wght.split("/");
                            let miss_gross = wght_arr[0] - +gross;
                            let miss_net = wght_arr[1] - +net;
                            let miss_wght = miss_gross+"/"+miss_net;
                            var miss_tr = $('tbody#avail_stock > tr[id]:not(#avail_'+code+'_'+match_id+')').clone();
                            $("#miss_stock").empty().append(miss_tr);
                            $("#miss_wght").empty().append(miss_wght);
                            $(".miss_count").empty().append(miss_tr.length);
                        });
                    }else{
                        toastr.error("Rescane Please !");
                    }
                }else{
                    toastr.error("Please Select The Code to Scane !");
                }
            }
        });

        // Keep input focused
        // $(document).on('click', function() {
        //     $("#barcodeInput").focus();
        // });
    });

</script>

@include('layouts.vendors.js.passwork-popup')

@endsection