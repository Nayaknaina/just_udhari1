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
    ul.stock_path >li:nth-child(3){
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
	.tag_count{
		display: block;
		background: #e4e4e4;
		color: black;
	}
</style>
@endsection

@section('content')

  @php 
	$anchor = ['<a href="'.route('stock.idtags.scane').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-search"></i> Scane/Match</a>'];
	$data = new_component_array('newbreadcrumb',"Stock Scane/Match") 
  @endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
        @include('vendors.idtags.newpages.commonfilter')
        <div class="col-12">   
            <ul class="w-auto stock_path d-inline-flex bg-primary flex-wrap mb-2" id="path_area">
            </ul>
            <ul id="size" style="margin-left:auto;display:inline-flex;flex-wrap: wrap;float:right;" class="text-center p-0 mb-1">
                <li class="text-info" id="printer_con" style="display:none;"><i><span class="fa fa-spinner fa-spin"></span> Connecting Printer ! </i> </li>
                <li class="text-danger" id="printer_no" style="display:none;"><i><span class="fa fa-times"></span> Printer Not Found ! </i> </li>
                <li class="text-success"  id="printer_yes" style="display:none;"><i><span class="fa fa-check"></span> Printer Connected ! </i> </li>
                <li id="reconnectprinter" style="display:none;">
                    <button type="button" class="btn btn-outline-info btn-sm" id="printer_reconnect"><i class="fa fa-refresh"></i> Reconnect</button>
                </li>
            </ul>
        </div>
        <div class="col-md-12 text-center mt-2">
            <div class="text-center" id="code_area" style="background:lightgray;overflow: scroll;max-height:100vh;height:auto;">
                
            </div>
        </div>
</div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')
<script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
    $('select.my_select').myselect96(false);
</script>
<script>
const loader = '<span class="text-primary w-100 mt-2"><i class="fa fa-spinner fa-spin"></i> Generating !</span>';
var pre_font='';
var pre_font_num='';

$("#filter_data").click(function(e){
    e.preventDefault();
    data();
});

function data(){
    var url_add_on = "";
    var metal = $("#metal").val()??false;
    var code = $("#tag").val()??false;
    var type = $(document).find("input[type='hidden']#type").val()??false;
	if(!code){
        toastr.error("Select The Code Type First !");
        $("#tag").addClass('is-invalid').focus();
        return false;
    }
    if(!metal){
        toastr.error("Select The Stock Type First !");
        $("#metal").addClass('is-invalid').focus();
        return false;
    }
    if(!type){
        toastr.error("Select The Jewellery Type First !");
        $(document).find('#type_input').addClass('is-invalid').focus();
        return false;
    }
    if(code){
        url_add_on+="code="+code+"&";
    }
    if(metal){
        url_add_on+="metal="+metal+"&";
    }
    if(type){
        url_add_on+="type="+type+"&";
    }
	 var karet = $("#karat").val()??false;
	if(karet){
		url_add_on+="karet="+code+"&";
	}
    var key = $("#keyword").val()??false;
    if(key){
        url_add_on+="keyword="+key+"&";
    }
    if(url_add_on!=""){
        url_add_on = url_add_on.replace(/&$/, '');
        url_add_on = "?"+url_add_on;
    }
    //let path = "{{ route('idtags.stock') }}"+url_add_on;
    let item = '';
    let num = 0;
    let ttl_gross = 0;
    let ttl_net = 0;
    
    $("#code_area").empty().append(loader);
    
    //----New Code----------------------------//
    if(code){
        $.get(url+url_add_on,'',function(response){
            if(response.path!=""){
                $("#path_area").empty().append(response.path);
            }
            $("#code_area").html(response.html); 
			const count = $(document).find('#tag_record').val();
			$('li.tag_count').html(count);	
			//alert(count);			
        });
    }else{
        item = '<span class="text-danger w-100 mt-5"><b><i class="fa fa-question-circle"></i> Select the code type & Category first !</b></span>'
        $("#code_area").empty().append(item); 
        $("#tag").focus();
    }
}



// $(document).on('change','#tag',function(){
//     data();
// });
// $(document).on('change','.jewellery_cat',function(){
//     data();
// });
// $(document).on('input','#keyword',function(){
//     data();
// });

</script>


    
</script>

@include('layouts.vendors.js.passwork-popup')

@endsection