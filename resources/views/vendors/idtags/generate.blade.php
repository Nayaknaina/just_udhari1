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
</style>
@endsection

@section('content')

@php 
$anchor = ['<a href="'.route("idtags.index").'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-home"></i> Scane & Match</a>'];
$data = new_component_array('newbreadcrumb',"ID&Code Generate/Print") 
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
                @if($sizes->count() > 0)
                @foreach($sizes as $si=>$size)
                <li class="m-auto">
                    <button type="button" data-name="{{ $size->name }}" data-machine='{{ $size->machine }}' data-tag='{{ $size->tag }}' data-code='{{ $size->image }}' data-info='{{ $size->info }}' data-one='{{ $size->one }}' data-two='{{ $size->two }}' class="btn btn-sm btn-outline-info size_btn m-0">
                        {{ ucfirst($size->name) }}
                    </button>
                </li>
                @endforeach
                @else 
                <!--<li class="m-auto">
                    <label class="alert alert-outline-warning">Size Not Specified !</label>
                </li>-->
                @endif
				<li id="reconnectprinter" style="display:none;">
                    <button type="button" class="btn btn-outline-info btn-sm" id="printer_reconnect"><i class="fa fa-refresh"></i> Reconnect</button>
                </li>
            </ul>
        </div>
        <div class="col-md-12 text-center mt-2">
            <div class="text-center" id="code_area">
                
            </div>
            <button id="printBtn">Test Print</button>
        </div>
</div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')
<script>
const loader = '<span class="text-primary w-100 mt-2"><i class="fa fa-spinner fa-spin"></i> Generating !</span>';
var pre_font='';
var pre_font_num='';
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
    
    //----New Code----------------------------//
    if(code){
        $.get(path,'',function(response){
            if(response.path!=""){
                $("#path_area").empty().append(response.path);
            }
            
            $("#code_area").html(response.html);
            pre_font = $('#code_area').find('.info_block').eq(0).css('font-size');
            if(pre_font){
                pre_font_num = pre_font.replace('px','');
            }
            
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
  var size_title = '';  
$('.size_btn').click(function(e){
    // var parent_font = parseFloat($('.detail').eq(0).css('font-size'));
    // var info_font = parseFloat($('.item_info_one').eq(0).css('font-size'));
    // var pre_font_num = (info_font/parent_font)*100;
    size_title = $(this).data('name');
    //pre_font = $('.info_block').eq(0).css('font-size');
    //pre_font_num = pre_font.replace('px','');
    let prev_pdf = $('#pdf_preview').attr('href');
    prev_pdf+='&size='+size_title;
     $('#pdf_preview').attr('href',prev_pdf);
    var machine = $(this).data('machine');
    var ml = (machine.l && machine.l !="")?machine.l:false;
    if(ml){
        $('.machine').css('margin-left',machine.l+'mm');
    }
    var mr = (machine.r && machine.r !="")?machine.r:false;
    if(mr){
        $('.machine').css('margin-right',machine.r+'mm');
    }

    var tag = $(this).data('tag');
    var tl = (tag.l && tag.l!=0)?tag.l:false;
    if(tl){
        $('.item_tag').css('padding-left',tl+'mm');
    }
    var tr = (tag.r && tag.r!="")?tag.r:false;
    if(tr){
        $('.item_tag').css('padding-right',tr+'mm');
    }
    var tw = (tag.w && tag.w!="")?tag.w:false;
    if(tw){
        $('.item_tag').css('width',tw+'mm');
    }
    var th = (tag.h && tag.h!="")?tag.h:false;
    if(th){
        $('.item_tag').css('height',th+'mm');
    }
    var tv = (tag.v && tag.v!="")?tag.v:false;
    if(tv){
        $('.item_tag').css('margin-bottom',tv+'mm');
    }
    
    var code = $(this).data('code');
    var cw = (code.w && code.w!='')?code.w:false;
    if(cw){
        $('.code_image').css({'width':cw+'mm','height':100+'%'});
        $('.code_image > img').css({'height':100+'%','width':'auto'});
    }
    var cs = (code.s && code.s!='')?code.s:false;
    if(cs){
        $('.code_image').css({'padding':cs+'px'});
    }

    var info = $(this).data('info');
    var inw = (info.w && info.w!="")?info.w:false;
    if(inw){
        $('.detail').css({'width':inw+"mm"});
    }
    var inf = (info.f && info.f!="")?info.f:false;
    if(inf){
        var now_font = +pre_font_num + +inf;
        $('.info_block').css({'font-size':now_font+'px'});
    }

    var one = $(this).data('one');
    var ow = (one.w && one.w!="")?one.w:false;
    if(ow){
        $(".item_info_one").css('width',ow+"mm");
    }
    var op = (one.p && one.p!="" && one.p!=0)?one.p:false;
    if(op=='one'){
        var one_order =  1
        var two_order = 2;
        $('.detail').css({'display':'inline-flex'});
        $(".item_info_one").css('order',one_order);
        $(".item_info_two").css('order',two_order);
    }

    var two = $(this).data('two');
    var tw = (two.w && two.w!="")?two.w:false;
    if(tw){
        $(".item_info_two").css('width',tw+"mm");
    }
    var tp = (two.p && two.p!="")?two.p:false;
    if(tp=='two'){
        var one_order =  2
        var two_order = 1;
        $('.detail').css({'display':'inline-flex'});
        $(".item_info_one").css('order',one_order);
        $(".item_info_two").css('order',two_order);
        
    }
});


$(document).on('click','#print_btn',function(e){
    //e.preventDefault();
    if(size_title!=""){
        var path = $(this).data('url')+"&size="+size_title+"&print=true";
        var printWindow  = window.open(path,'','width=800,height=600');
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
        };
    }else{
        toastr.error("Select the Size First !");
    }
    /*$.get(path,"size="+size_title,function(){

    });*/
});
</script>
<script src="{{ asset('main/printer/BrowserPrint-3.0.216.min.js') }}"></script>
<script>
    var selected_device;

    
    /*BrowserPrint.getLocalDevices(function(devices) {
        // Filter for printers
        const printers = devices.filter(device => device.deviceType === 'printer');

        if (printers.length > 0) {
            // Set the first printer as default (or let the user choose)
            selected_device = printers[0];
            console.log("Default printer set to: " + selected_device.name);
        } else {
            alert("No printers found");
        }
    }, function(error) {
        alert("Error finding printers: " + error);
    }, 'printer'); // Only look for printers*/

	function connecttoprinter(again=false){
        BrowserPrint.getLocalDevices(function(devices) {
            // Filter for printers
            const printers = devices.filter(device => device.deviceType === 'printer');
    
            if (printers.length > 0) {
                // Set the first printer as default (or let the user choose)
                selected_device = printers[0];
                if(again){
                    setTimeout(function() {
                        toastr.success('Reconnected To Printer : '+selected_device.name);
                         $("#reconnectprinter").hide()
                        $('#printer_reconnect').find('i').removeClass('fa-spin');
                    }, 5000);
                }
                console.log("Default printer set to: " + selected_device.name);
            } else {
                //alert("No printers found");
                toastr.error("No printers found !");
            }
        }, function(error) {
            toastr.error("Error finding printers: " + error);
            //alert("Error finding printers: " + error);
        }, 'printer'); // Only look for printers
    }

	connecttoprinter();
	
    function printLabel(zpl) {
        /*selected_device.send(zpl, undefined, function(error) {
            alert("Print error: " + error);
        });*/
		if(selected_device){
            selected_device.send(zpl, undefined, function(error) {
				toastr.error("Print error: " + error);
				toastr.info("Check if the Printer Online !<br>Remove the USB & Reconnect !");
				$("#reconnectprinter").show();
            });
        }else{
            toastr.error("No Connection to Printer !");
            $("#reconnectprinter").show();
        }
    }

	$(document).on('click','#printer_reconnect',function(){
        connecttoprinter(true);
        $(this).find('i').addClass('fa-spin');
    });

    $('#printBtn').click(function() {
        var zpl = `^XA
                    ^FO100,30^A0N,30,30^FDName   : product name^FS
                    ^FO100,70^A0N,30,30^FDWeight : 11/10Grm^FS
                    ^FO300,30^A0N,30,30^FDID     : ytrytr655656^FS
                    ^FO300,70^A0N,30,30^FDCode   : ADSA4545^FS
                    ^XZ`;
        printLabel(zpl);
    });
    
</script>

@include('vendors.idtags.js.zpltagprint')
@include('layouts.vendors.js.passwork-popup')

@endsection