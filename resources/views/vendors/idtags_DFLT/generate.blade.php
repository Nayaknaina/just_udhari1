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
        border-radius:10px;
    }
    #size{
        list-style:none;
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
    #code_area{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }
    .item_tag{
        display: flex;
        padding:0 2px;
        box-sizing: border-box;
        margin-bottom:5px;
        border:1px solid black;
        border-radius:5px;
        font-size:80%;
        background-color: white;
        margin: 0 auto 5px auto;
    }
    .item_tag > .code_image{
        width:30%;
        padding:0 2px;
        border-right:1px dashed black;
        align-content: center;
    }
    .code_image > img{
        width:100%;
        height:inherit;
    }
    .item_tag > .detail{
        width:70%;
        align-content: center;
        text-align: initial;
    }
    .item_tag > .detail >ul{
        list-style:none;
        padding-left:2px;
        margin:0;
        align-content:center;
    }
    .size_btn.selected{
        position:relative;
        padding-left:20px!important;
    }
    .size_btn.selected:before{
        content:"\2714";
        position:absolute;
        left:0px;
        padding:0 5px;
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
$anchor = ['<a href="'.route('idtags.index').'" class="btn btn-sm btn-outline-primary"><i class="fa fa-minus-square"></i> Scane</a>'];
$data = new_component_array('newbreadcrumb',"Product ID Scane")   
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
        @include('vendors.idtags.commonfilter')
        <div class="col-12">   
            <ul class="w-auto stock_path d-inline-flex bg-primary flex-wrap mb-2" id="path_area">
            </ul>
            <ul id="size" style="margin-left:auto;display:inline-flex;flex-wrap: wrap;float:right;" class="text-center p-0 mb-1">
                <li class="m-auto">
                    <label for="sm_size" class="size_btn btn btn-sm btn-outline-info m-0" id="sm">
                        <input type="radio" name="size" onchange="small();" style="display:none;" id="sm_size" value="small">Small
                    </label>
                </li>
                <li class="m-auto">
                    <label for="md_size" class="size_btn btn btn-sm btn-outline-info m-0" id="md">
                        <input type="radio" name="size" onchange="medium();" style="display:none;"  id="md_size" value="medium">Medium
                    </label>
                </li>
                <li class="m-auto">
                    <label for="fld_size" class="size_btn btn btn-sm btn-outline-info m-0" id="fld">
                        <input type="radio" name="size" onchange="folded();" style="display:none;"  id="fld_size" value="folded">Folded
                    </label>
                </li>
                <li class="m-auto">
                    <label for="str_size" class="size_btn btn btn-sm btn-outline-info m-0" id="str">
                        <input type="radio" name="size" onchange="string();" style="display:none;"  id="str_size" value="string"> String
                    </label>
                </li>
                <li class="m-auto">
                    <label for="dflt_size" class="size_btn btn btn-sm btn-secondary m-0" id="dflt">
                        <input type="radio" name="size" onchange="reset();" style="display:none;"  id="dflt_size" checked value="default"> DEFAULT
                    </label>
                </li>
            </ul>
        </div>
        <div class="col-md-12 text-center mt-2">
            <div class="text-center" id="code_area">
                
            </div>
        </div>
</div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')
<script type="text/javascript" src="{{ asset('main/services/qr_js/qrcode.js') }}"></script>
<script>
    function generateqrcode(data,code){
        $.each(data,function(i,v){
            const code_value = v[code].replace(' ', '_');
            //code_value = code_value;
            const qrId = 'qr_img_' + code_value;
            // 1. Create an invisible container to generate QR code
            const tempContainer = document.createElement("div");

            // 2. Generate QR code (it creates a canvas in the temp container)
            new QRCode(tempContainer, {
                text: code_value,
                // width: 100%,
                // height: 100%,
                correctLevel: QRCode.CorrectLevel.H
            });

            // 3. Wait a tiny bit for canvas to render
            setTimeout(function() {
                const canvas = tempContainer.querySelector("canvas");
                if (canvas) {
                    const base64Image = canvas.toDataURL("image/png");
                    $("#" + qrId).attr("src", base64Image);
                }
            }, 50);
        });
    }
</script>
<script>
const loader = '<span class="text-primary w-100 mt-2"><i class="fa fa-spinner fa-spin"></i> Generating !</span>';
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
    let item = '';
    let num = 0;
    let ttl_gross = 0;
    let ttl_net = 0;
    
    $("#code_area").empty().append(loader);
    if(code){
        $.get(path,'',function(response){
            if(response.path!=""){
                $("#path_area").empty().append(response.path);
            }
            if(response.data.length > 0){
                $.each(response.data,function(i,v){
    
                    const code_value = v[code].replace(' ', '_');
                    //code_value = code_value;
                    const qrId = 'qr_img_' + code_value;
                    let prop = JSON.parse(v.property);
                    let gross = (prop)?prop.gross_weight:"";
                    let net = (prop)?prop.net_weight:"";
                    num = i + 1;
                    item+='<div class="col-md-3 col-12 item_tag" id="'+code+'_'+code_value+'">';
                    item+=`<div class="code_image">
                                <img id="${qrId}" src="" alt="${v.product_name}" id="${code}_${v[code]}" class="m-auto">
                            </div>
                            <div class="detail">
                                <ul>
                                    <li class="name">NAME : <span>${v.product_name}</span></li>
                                    <li class="wght">WEIGHT : <span>${gross+"/"+net} Gm</span></li>
                                    <li class="id">ID : <span>${v.product_code}</span></li>
                                    <li class="code">CODE : <span>${code_value}</span></li>
                                </ul>
                            </div>`;
                    item+='</div>';
                    ttl_gross+= +gross;
                    ttl_net+= +net;
                });
                if(item!=""){
                    item+='<div class="col-12 text-center pt-2 mt-2" style="border-top:1px dashed gray;" id="preview_block"><a href="{{ route("idtags.preview") }}" class="btn btn-sm btn-outline-dark" id="print_preview"><i class="fa fa-print"></i> Preview</a></div>';
                }
                $("#code_area").empty().append(item);
               switch(code){
                    case 'qrcode':
                        generateqrcode(response.data,code);
                        break;
                    case 'barcode':
                        generatebarcode(response.data,code);
                        break;
                    default:
                        generateqrcode(response.data,code);
                        break; 
               }
                //$("#avail_stock").empty().append(item_row);
                //$(".avl_count").empty().append(num);
                //$('#avl_wght').empty().append(ttl_gross+"/"+ttl_net);
            }else{
                item = '<span class="text-primary w-100 mt-2"><i class="fa fa-info-circle"></i> No Stock !</span>';
            }
            $("#code_area").empty().append(item); 
        });
    }else{
        item = '<span class="text-danger w-100 mt-5"><b><i class="fa fa-question-circle"></i> Select the code type first !</b></span>'
        $("#code_area").empty().append(item); 
        $("#tag").focus();
    }
}
$(document).on('change','#tag',function(){
    $('.size_btn:not(#dflt)').removeClass('btn-info selected').addClass('btn-outline-info');
    $("#dflt").removeClass('btn-outline-secondary selected').addClass('btn-secondary');
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
$(document).on('click','#print_preview',function(e){
    e.preventDefault();
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
    var size = $("input[name='size']:checked").val()??false;
    if(size){
        url_add_on+="size="+size+"&";
    }
    if(url_add_on!=""){
        url_add_on = url_add_on.replace(/&$/, '');
        url_add_on = "?"+url_add_on;
    }
    //let path = "{{ route('idtags.preview') }}"+url_add_on;
    window.open("{{ route('idtags.preview') }}"+url_add_on, "_blank");
});
    $('.size_btn').click(function(e){
        $('.size_btn:not(#dflt)').removeClass('btn-info selected').addClass('btn-outline-info');
        //$('.size_btn').removeClass('btn-info selected').addClass('btn-outline-info');
        let self_id = $(this).attr('id');
        if(self_id=='dflt'){
            $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
        }else{
            $("#dflt").removeClass('btn-secondary').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-info').addClass('btn-info selected');
        }
    })
    let tag_width = 'initial';
    let tag_height = 'initial';
    let font_size = 'initial;'
    function small(){
        if($("#sm_size").is(':checked')){
            tag_height = 10;
            tag_width = 25;
            font_size = '40%';
            //$("#print_preview").addClass('disabled');
            setdimention();
        }
    }
    function medium(){
        if($("#md_size").is(':checked')){
            tag_height = 15;
            tag_width = 35;
            font_size = '60%';
            //$("#print_preview").addClass('disabled');
            setdimention();
        }
    }
    function folded(){
        if($("#fld_size").is(':checked')){
            tag_height = 20;
            tag_width = 50;
             font_size = '85%';
            //$("#print_preview").addClass('disabled');
            setdimention();
        }
    }
    function string(){
        if($("#str_size").is(':checked')){
            tag_height = 20;
            tag_width = 60;
             font_size = '95%';
            //$("#print_preview").addClass('disabled');
            setdimention();
        }
    }

    function reset(){
         if($("#dflt_size").is(':checked')){
            $('.item_tag').addClass('col-md-3 col-12');
            $('.item_tag').css({width:'inherit',height:'inherit'});
            $('.item_tag > .detail').css('font-size','inherit');
         }
    }
    function setdimention(){
        $('.item_tag').removeClass('col-md-3 col-12');
        $('.item_tag').css({width:tag_width+'mm',height:tag_height+'mm'});
        $('.item_tag > .detail').css('font-size',font_size);
    }
</script>
@include('layouts.vendors.js.passwork-popup')

@endsection