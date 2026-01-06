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
    .item_dtl{
        list-style: none;
        text-align: initial;
        font-size:80%;
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
            
        <ul class="w-auto m-auto stock_path d-inline-flex bg-primary" id="path_area">
        </ul>
        <div class="col-md-12 text-center mt-2">
            <div class="row text-center" id="code_area">
                <div class="col-md-2">
                    
                    <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                        <img src="..." class="img-fluid rounded-start" alt="...">
                        </div>
                        <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
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
                    item+='<div class="col-md-2 col-6 m-auto" id="'+code+'_'+code_value+'">';
                    item+='<div class="card">';
                    item+='<img id="'+qrId+'"src="" class="qr-img p-2" alt="'+v.product_name+'">';
                    item+='<div class="card-body p-2" style="border-top:1px dashed gray;">';
                    item+='<ul class="item_dtl p-0 m-0">';
                    item+='<li>ID : '+v.product_code+'</li>';
                    item+='<li>NAME : '+v.product_name+'</li>';
                    item+='<li>Wgt : '+gross+"/"+net+'Gm</li>';
                    item+='<li>CODE :'+code_value+'</li>';
                    item+='</ul>';
                    item+='</div>';
                    item+='</div>';
                    item+='</div>';
                    ttl_gross+= +gross;
                    ttl_net+= +net;
                });
                if(item!=""){
                    item+='<div class="col-12 text-center pt-2 mt-2" style="border-top:1px dashed gray;"><a href="{{ route("idtags.preview") }}" class="btn btn-sm btn-outline-dark" id="print_preview"><i class="fa fa-print"></i> Preview</a></div>'
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
    data();
});
$(document).on('change','.jewellery_cat',function(){
    data();
});
$(document).on('input','#keyword',function(){
    data();
});

</script>
<script type="text/javascript" src="{{ asset('main/services/qr_js/qrcode.js') }}"></script>
<script>
    $(document).on('click','#generate_code',function(e){
        e.preventDefault();
        $("#tag").removeClass('is-invalid');
        let codetype = $("#tag").val()??false;
        if(codetype){
            $.get($(this).attr('href')+"?code="+codetype,"",function(response){

            });
        }else{
            $("#tag").addClass('is-invalid');
            $("#tag").focus();
            toastr.error("Please select The Code Type !");
        }
    });
</script>
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
    function generatebarcode(data){
        /*$.each(data,function(i,v){
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
        });*/
    }
</script>
<script>
$(document).on('click','#print_preview',function(e){
    e.preventDefault();
    const contentToCopy = $('#code_area').clone();
    const newWin = window.open('', "_blank", "width=800,height=600");
    newWin.document.write(`
    <html>
    <head>
        <title>Cloned Content</title>
        <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        </style>
    </head>
    <body></body>
    </html>
    `);
    newWin.document.close(); // Close the document for writing

    // Wait a bit and then inject the content
    newWin.onload = () => {
    $(newWin.document.body).append(contentToCopy);
    };
});
</script>
@include('layouts.vendors.js.passwork-popup')

@endsection