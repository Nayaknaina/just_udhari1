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
        align-items: stretch;
    }
    ul.stock_path > li{
        width:inherit;
        position: relative;
        padding:0px  20px 0px 25px;
        text-shadow:1px 2px 3px gray;
        font-weight:bold;
        align-content: center;
    }
    ul.stock_path >li:after{
        position: absolute;
        content:"";
        height:100%;
        width:25px;
        bottom:0;
        top:0;
        right:-12px;
        /* transform: rotate(45deg); */
        transform: skew(15deg);
        z-index:1;
        /* border:1px solid white; */
    }
    ul.stock_path >li:last-child{
        background:#ffb893;
    }
    ul.stock_path >li:last-child:after{
        content:unset;
    }
    ul.stock_path >li:nth-child(1){
        background:#f95600;
    }
    ul.stock_path >li:nth-child(1):after{
        background:#f95600;
    }
    ul.stock_path >li:nth-child(2){
        background:#ff813f;
    }
    ul.stock_path >li:nth-child(2):after{
        background:#ff813f;
    }
    /*ul.stock_path >li:last-child{
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
    }*/
    .bg-scan{
        background:#0080000f;
    }
    .bg-miss{
        background:#ff00000a;
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

@php 
$anchor = ['<a href="'.route("idtags.create").'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Generate & Print</a>'];
$data = new_component_array('newbreadcrumb',"ID&Code Scane Stock") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
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
                                        <span><i id="avl_wght">0/0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 pt-3" id="avail_stock_block">
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
                        <div class="card round curve  p-2 bg-scan">
                            <div class="card-header bg-primary-light  p-0 stock_head">
                                <ul class="info_plate p-0 d-flex flex-wrap w-100 m-0" style="list-style:none;"> 
                                    <li class="head_info_title p-1"style="align-content:center;">
                                        <h6 class="text-success">Scanned <span class="badge badge-light scan_count text-dark">0</span></h6>
                                    </li>
                                    <li class="vertical-ribbon-custom">
                                        <b>WEIGHT</b>
                                        <hr class="m-1">
                                        <span><i id="scan_wght">0/0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <div class=" table-responsive">
                                    <table class="table m-0">
                                        <tbody id="scan_stock">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card round curve  p-2 bg-miss">
                            <div class="card-header bg-primary-light  p-0 stock_head">
                                <ul class="info_plate p-0 d-flex flex-wrap w-100 m-0" style="list-style:none;"> 
                                    <li class="head_info_title p-1"style="align-content:center;">
                                        <h6 class="text-danger">Missed <span class="badge badge-light miss_count text-dark">0</span></h6>
                                    </li>
                                    <li class="vertical-ribbon-custom">
                                        <b>WEIGHT</b>
                                        <hr class="m-1">
                                        <span><i id="miss_wght">0/0</i> Gm</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0 pt-3">
                                <div class=" table-responsive">
                                    <table class="table m-0 ">
                                        <tbody id="miss_stock">
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
    var code = $("#tag").val()??false;
    var metal = $("#metal").val()??false;
    var type = $("#type").val()??false;
    var key = $("#keyword").val()??false;
    if(code){
        url_add_on+="code="+code+"&";
        if(metal){
            url_add_on+="metal="+metal+"&";
        }
        if(type){
            url_add_on+="type="+type+"&";
        }
        if(key){
            url_add_on+="keyword="+key+"&";
        }
        if(url_add_on!=""){
            url_add_on = url_add_on.replace(/&$/, '');
            url_add_on = "?"+url_add_on;
        }
    }else{
        toastr.error("Please The Code type !");
        $("#tag").focus();
        return false;
    }
    /*var url_add_on = "";
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
    }*/
    let path = "{{ route('idtags.stock') }}"+url_add_on;
    let item_row = '';
    let num = 0;
    let ttl_gross = 0;
    let ttl_net = 0;
    
    $("#avail_stock").empty().append(loader_tr);
    $.get(path,'req=stock',function(response){
        if(response.path!=""){
            item_row+='<tr><th colspan="3" class="p-0"><ul class="stock_path d-flex m-0 bg-primary">'+response.path+'</ul></th></tr>';
        }
        if(response.data.length > 0){
            var code_value = "";
            $.each(response.data,function(i,v){
                code_value = v[code];
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
                item_row+='<li>WIGHT : <span class="avl_wght">'+gross+"/"+net+'</span>Gm</li>';
                item_row+='<li>CODE : '+code_value+'</li>';
                item_row+='</ul>';
                item_row+='</td>';
                item_row+='</tr>';
                ttl_gross+= +gross;
                ttl_net+= +net;
            });
        }else{
            item_row += '<tr><th  class="text-center"><span class="text-primary"><i class="fa fa-info-circle"></i> No Stock !</span></th></tr>';
        }
        $("#avail_stock").empty().append(item_row);
        $('.avl_count').text(num);
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

</script>
<script>
    
    $(document).ready(function() {
        var scanecode = "";
        var num = 0;
        function decodeAltCodeString(raw) {
            // Strip "NumLock" and split the string on "Alt"
            const parts = raw.replace("NumLock", "").split("Alt");
            
            let result = "";
            for (const part of parts) {
                const code = parseInt(part, 10);
                if (!isNaN(code)) {
                    result += String.fromCharCode(code);
                }
            }
            return result;
        }

        
        function scannedstockbtcode(codevalue){
            var code = $("#tag").val()??false;
            if(code){
                console.log("Here Judge");
                if(codevalue!=""){
                console.log("Here Not Blank");
                    let is_scaned = $('tbody#scan_stock >tr').is('#scan_'+code+'_'+codevalue);
                    if(!is_scaned){
                        let avail_id = 'avail_'+code+'_'+codevalue;
                        let found = $('#avail_stock > tr#'+avail_id).clone();
                        found.attr('id', 'scan_'+code+'_'+codevalue);
                        found.find('span.avl_wght').removeClass('avl_wght').addClass('scan_wght');
                        $("#scan_stock").append(found);
                        let is_miss = $('tbody#scan_stock > tr').is('#scane_'+code+'_'+codevalue);
                            var miss_trs = $('tbody#avail_stock > tr[id]:not(#'+avail_id+')').clone();

                            miss_trs = miss_trs.not(function() {
                                let $row = $(this);
                                let old_miss_id = $row.attr('id');
                                let scan_id = old_miss_id.replace('avail', 'scan');

                                return $('tbody#scan_stock > tr#' + scan_id).length > 0;
                            }).each(function() {
                                // Update ID from 'avail' to 'miss' for the remaining ones
                                let old_id = $(this).attr('id');
                                let new_id = old_id.replace('avail', 'miss');
                                $(this).find('span.avl_wght').removeClass('avl_wght').addClass('miss_wght')
                                $(this).attr('id', new_id);
                            });
                        $("#miss_stock").empty().append(miss_trs);
                        // $("#miss_wght").empty().append(miss_wght);
                        miss_trs.each(function(ind,val){
                            $(val).find('td').eq(0).text(ind+1);
                        })
                        $(".miss_count").empty().append(miss_trs.length);
                        let miss_gross = 0;
                        let miss_net = 0;
                        $('span.miss_wght').each(function(mwi,mwv){
                            let curr_wght = $(mwv).text().split('/');
                            miss_gross+= +curr_wght[0];
                            miss_net+= +curr_wght[1];
                        });
                        $("#miss_wght").empty().append(miss_gross+"/"+miss_net);
                        $('tbody#scan_stock >tr').each(function(ind,val){
                            $(val).find('td').eq(0).text(ind+1);
                        })
                        $(".scan_count").empty().append($('tbody#scan_stock >tr').length);
                        let scane_gross = 0;
                        let scane_net = 0;
                        $('span.scan_wght').each(function(swi,swv){
                            let curr_wght = $(swv).text().split('/');
                            scane_gross+= +curr_wght[0];
                            scane_net+= +curr_wght[1];
                        });
                        $("#scan_wght").empty().append(scane_gross+"/"+scane_net);
                    }else{
                        toastr.error("The Item already Scanned !");
                    }
                }else{
                    //toastr.error("Rescan Please !");
                }
            }else{
                toastr.error("Please Select The Code to Scan !");
            }
        }
        
        /*$(document).on("keydown", function(e) {
            if(e.keyCode!=13){
                scanecode+=e.key;
            }else{
                scanecode = decodeAltCodeString(scanecode);
                scannedstockbtcode(scanecode);
                scanecode="";
            }
        });*/
        let scaneenterPressed = false;
        $(document).on("keydown", function(e) {
            if(e.keyCode!=13){
                scanecode+=e.key;
            }else if(!scaneenterPressed){
                scaneenterPressed = true;
                scanecode = decodeAltCodeString(scanecode);
                scannedstockbtcode(scanecode);
                scanecode="";
                setTimeout(() => {
                    scaneenterPressed = false;
                }, 100);
            }
        });

    });

</script>

@include('layouts.vendors.js.passwork-popup')

@endsection