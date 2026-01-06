@extends('layouts.vendors.app')

@section('css')
    <link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
    <style>
        
        fieldset {
            border: 1px dashed #f95600;
            padding: 10px;
            border-radius:15px;
            color:#f95600;
            line-height:normal;
        }
        fieldset >legend{
            font-size:90%;
            font-weight:bold;
            margin:0;
        }
        .form-group > label{
            font-size:70%;
        }
        .form-group > .form-control{
            text-align:center;
        }
        .item_tag{
            /*display:inline-flex;
            background:white;
            border:1px solid gray;
            border-radius:10px;*/
            width:50mm;
            page-break-after: always;
            page-break-inside: avoid;
        }
        .machine{
            min-width:50mm;
            width:min-content;
        }
        .code_image{
            width:40mm;
            align-content:center;  
            padding:5px; 
            text-align:center;
        }
        .item_tag.default >.code_image > img{
            height:inherit;
            width:100%;
        }
        .item_tag.default >.detail{
            width:60mm;
        }
        .edited{
            border:1px dashed black;
        }
        /*.detail > .info_block{
            list-style: none;
            padding:0 2px;
            margin:0;
            flex:1;
            font-size:70%;
        }*/
        .detail >.info_block>li>span{
            float:right;
        }
    </style>
@endsection

@section('content')

@php $data = new_component_array('breadcrumb',"RFID & Barcode") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 bg-white" id="size_block">
                    <form action="{{ route('idtags.store') }}" role="form" id="size_setup_form">
                        <fieldset>
                            <legend class="w-auto">SIZE TITLE/LABEL</legend>
                            <div class="row">
                                @csrf
                                <input type="hidden" name="edit" value="" id="edit">
                                <div class="form-group mb-1 col-12">
                                    <input type="text" name="name" class="border-dark form-control h-32px btn-roundhalf" value=""  id="name">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="w-auto">MACHINE</legend>
                            <div class="row p-2">
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="m_left" class="mb-1">Left Space <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="m_left" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="m_left">
                                </div>
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="m_right" class="mb-1">Right Space <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="m_right" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="m_right">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="w-auto">TAG DIMENSION</legend>
                            <div class="row p-2">
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="t_left" class="mb-1">Left Space <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="t_left" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="t_left">
                                    
                                </div>
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="t_right" class="mb-1">Right Space <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="t_right" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="t_right">
                                </div>
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="t_height" class="mb-1">Height <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="t_height" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="t_height">
                                </div>
                                <div class="form-group mb-1 col-6 mm p-1">
                                    <label for="t_width" class="mb-1">Width <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="t_width" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="t_width">
                                </div>
                                 <div class="form-group mb-1 col-12 mm p-1">
                                    <div class="input-group h-auto btn-roundhalf form-control p-1">
                                            <label for="v_space" class="m-0" style="align-content:center;">V-Space <small class="text-info"><b>MM</b></small></label>
                                            <input type="number" class="form-control text-center h-32px btn-roundhalf" name="v_space" id="v_space" min="0" value="">
                                    </div>
                                 </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="w-auto">CODE (Image)</legend>
                            <div class="row p-2">
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_width" class="mb-1">WIDTH<small class="text-info"><b>MM</b></small></label>
                                    <input type="number" name="code_width" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="code_width">
                                </div>
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_height" class="mb-1">SPACE <small class="text-info"><b>PX</b></small></label>
                                    <input type="number" name="code_pad" class="border-dark form-control h-32px btn-roundhalf" value="" min="0" id="code_pad">
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="w-auto">INFO BLOCK</legend>
                            <div class="row p-2">
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_height" class="mb-1">WIDTH <small class="text-info"><b>MM</b></small></label>
                                     <input type="number" name="info_width" id="info_width" class="border-dark form-control h-32px btn-roundhalf" min="0">
                                </div>
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_height" class="mb-1">FONT SIZE</label>
                                    <input type="number" class="form-control form-control h-32px btn-roundhalf" name="font" value="" id="font">
                                </div>
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_width" class="mb-1">NAME & WEIGHT</label>
                                    <label for="one_left" class="border-dark form-control h-32px btn-roundhalf">
                                        <input type="checkbox" class="pos_check" name="info_pos" value="one" id="one_left" onclick="$('#two_left').prop('checked',false)"> LEFT
                                    </label>
                                    <label for="one_size" class="mb-1">WIDTH <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" min="0" class="form-control border-dark h-32px btn-roundhalf" name="one_size" id="one_size" min="0">
                                </div>
                                <div class="form-group mb-1 col-6 p-1">
                                    <label for="code_height" class="mb-1">ID & CODE </label>
                                    <label for="two_left" class="border-dark form-control h-32px btn-roundhalf">
                                        <input type="checkbox" class="pos_check" name="info_pos" value="two" id="two_left" onclick="$('#one_left').prop('checked',false)"> LEFT
                                    </label>
                                    <label for="two_size" class="mb-1">WIDTH <small class="text-info"><b>MM</b></small></label>
                                    <input type="number" min="0" class="form-control border-dark h-32px btn-roundhalf" name="two_size" id="two_size" min="0">
                                </div>
                            </div>
                        </fieldset>
                        <div class="col-12 form-group text-center mt-2 ">
                            <button type="button" name="reset" value="form" class="btn btn-sm btn-info m-auto" onClick="resetform();">Reset</button>
                            <button type="button" name="apply" value="size" class="btn btn-sm btn-primary m-auto" onClick="setsize();">Apply</button>
                            <button type="submit" name="set" value="size" class="btn btn-sm btn-outline-success m-auto">Save</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-{{ ($sizes->count()>0)?7:9 }} mt-2">
                    <div class="machine m-auto" style="background:gray;">
                        <div class="item_tag   default" id="item_tag" style="display:inline-flex;background:white;border:1px solid gray; border-radius:10px;">
                            <div class="code_image">
                                <img id="qr_img_LJ195816" src="{{ asset('assets/images/dummy/qr_dummy.png') }}" alt="Qwer Tre" class="m-auto">
                            </div>
                            <div class="detail">
                                <ul class="item_info_one info_block" style="list-style: none;padding:0 2px;margin:0;font-size:70%;align-content:center;">
                                    <li class="name">NAME : <span style="float:right;">product name</span></li>
                                    <li class="wght">WEIGHT : <span style="float:right;">100/99 Gm</span></li>
                                </ul>
                                <ul class="item_info_two info_block" style="list-style: none;padding:0 2px;margin:0;font-size:70%;align-content:center;">
                                    <li class="id">ID : <span style="float:right;">545345345</span></li>
                                    <li class="code">CODE : <span style="float:right;">JS00000</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="item_tag   default" id="item_tag" style="display:inline-flex;background:white;border:1px solid gray; border-radius:10px;">
                            <div class="code_image">
                                <img id="qr_img_LJ195816" src="{{ asset('assets/images/dummy/qr_dummy.png') }}" alt="Qwer Tre" class="m-auto">
                            </div>
                            <div class="detail">
                                <ul class="item_info_one info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                                    <li class="name">NAME : <span style="float:right;">product name</span></li>
                                    <li class="wght">WEIGHT : <span style="float:right;">100/99 Gm</span></li>
                                </ul>
                                <ul class="item_info_two info_block" style="list-style: none;padding:0 2px;margin:0;font-size:10px;align-content:center;">
                                    <li class="id">ID : <span style="float:right;">545345345</span></li>
                                    <li class="code">CODE : <span style="float:right;">JS00000</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="m-auto pt-4" style="width:fit-content;">
                        <button type="button" class="btn btn-sm btn-dark" onclick="testprint();">
                            <i class="fa fa-print"></i> Test Print</i>
                        </button>
                        <button id="printBtn">SAMPLE
                        </button>
                    </div>
                </div>
                @if($sizes->count()>0)
                <div class="col-md-2 p-0">
                    <ul>
                        @foreach($sizes as $sk=>$size)
                            @php 
                                $size_id = strtolower(str_replace(" ","_",$size->name));
                            @endphp 
                            <li class="btn-group w-100 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-info w-100 size_btn m-0 " data-id="{{ $size->id }}"  data-name="{{ $size->name }}" data-machine='{{ $size->machine }}' data-tag='{{ $size->tag }}' data-code='{{ $size->image }}' data-info='{{ $size->info }}' data-one='{{ $size->one }}' data-two='{{ $size->two }}' id="{{ $size_id }}">
                                    {{ $size->name }}
                                </button>
                                <button type="button" class="btn btn-secondary dropdown-toggle py-0 px-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-caret-down"></i></button>
                                <div class="dropdown-menu border-dark" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item text-secondary size_edit"   href="#{{ $size_id }}"><i class="fa fa-edit"></i> Edit</a>
                                    <a class="dropdown-item text-danger size_delete" href="#{{ $size_id }}" ><i class="fa fa-times"></i> Delete</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')
<script src="{{ asset('main/printer/BrowserPrint-3.0.216.min.js') }}"></script>
    <script>
        const items = [
            {"name":'Name','weight':'100/99gm'},
            {"name":'Name','weight':'100/99gm'},
            {"name":'Name','weight':'100/99gm'}
        ];
    var selected_device;

    BrowserPrint.getDefaultDevice("printer", function(device) {
        selected_device = device;
    }, function(error) {
        alert("Error getting printer: " + error);
    });

    
    function generateZPL(tag){
        return  `^XA
        ^FO50,50^A0N,30,30^FDProduct: ${tag.name}^FS
        ^FO50,100^BCN,100,Y,N,N^FD${tag.weight}^FS
        ^XZ`;
    }

    function printLabel() {
        items.forEach((tag, i) => {
            const zpl = generateZPL(tag);
            setTimeout(() => {
                //printer.send(zpl);
                selected_device.send(zpl, undefined, function(error) {
                    alert("Print error: " + error);
                });
            }, i * 1000);
        });
    }

    $('#printBtn').click(function(){
        printLabel();
    });

    /*$('#printBtn').click(function() {
        var zpl = "^XA^FO50,50^ADN,36,20^FDBYE Zebra^FS^XZ";
        //printLabel(zpl);
        selected_device.send(zpl, undefined, function(error) {
            alert("Print error: " + error);
        });
    });*/
</script>
<script>
    // var parent_font = parseFloat($('.detail').eq(0).css('font-size'));
    // var info_font = parseFloat($('.info_block').eq(0).css('font-size'));
    // var pre_font_num = (info_font/parent_font)*100;
    var pre_font = $('.info_block').eq(0).css('font-size');
    var pre_font_num = pre_font.replace('px','');
    $('.pos_check').prop('checked',false);
    $('.form-control').focus(function(){
        $(this).removeClass('is-invalid');
    });

    function resetform(){
        $(".item_tag").css({'margin-left':'','margin-right':'','padding-left':'','padding-right':'','width':'','height':''});
        $('.code_image').css({'width':'','height':'','padding':''});
        $('.code_image > img').css({'width':'','height':''});
        $('.detail').css({'width':'','display':''});
        $(".item_info_one").css('order','');
        $(".item_info_two").css('order','');
        $(".item_tag").addClass('default');
        $('.pos_check').prop('checked',false);
        $("#size_setup_form").trigger('reset');
    }

    function setsize(){
        var input_ok = true;
        var fault_field = false;
        if($("#m_left").val()==""){
            input_ok = false;
            fault_field = "#m_left";
        }
        if(input_ok){
            if($("#m_right").val()==""){
                input_ok = false;
                fault_field = "#m_right";
            }
        }
        if(input_ok){
            if($("#t_left").val()==""){
                input_ok = false;
                fault_field = "#t_left";
            }
        }
        if(input_ok){
            if($("#t_right").val()==""){
                input_ok = false;
                fault_field = "#t_right";
            }
        }
        if(input_ok){
            if($("#t_width").val()==""){
                input_ok = false;
                fault_field = "#t_width";
            }
        }
        if(input_ok){
            if($("#t_height").val()==""){
                input_ok = false;
                fault_field = "#t_height";
            }
        }
        if(input_ok){
            if($("#code_width").val()==""){
                input_ok = false;
                fault_field = "#code_width";
            }
        }
        if(input_ok){
            if($("#code_pad").val()==""){
                input_ok = false;
                fault_field = "#code_pad";
            }
        }
        
        if(input_ok){
            var m_left = $("#m_left").val();
            var m_right = $("#m_right").val();
            var t_left = $("#t_left").val();
            var t_right = $("#t_right").val();
            var t_width = $("#t_width").val();
            var t_height = $("#t_height").val();
            var img_width = $("#code_width").val();
            var img_pad = $("#code_pad").val();
            var info_width = $("#info_width").val()??false;
            var detail_width = (info_width)?info_width:(t_width - img_width);
            var info_pos = $('[name="info_pos"]:checked').val()??false;
            $(".item_tag").css({'margin-left':m_left+'mm','margin-right':m_right+'mm','padding-left':t_left+'mm','padding-right':t_right+'mm','width':t_width+'mm','height':t_height+'mm'});
            $('.code_image').css({'width':img_width+'mm','height':100+'%','padding':img_pad+'px'});
            $('.code_image > img').css({'height':100+'%','width':'auto'});
            $('.detail').css('width',detail_width+"mm");
            if(info_pos){
                $('.detail').css({'display':'inline-flex'});
                let one_pos = (info_pos=='one')?'1':'2';
                let two_pos = (info_pos=='one')?'2':'1';
                $(".item_info_one").css('order',one_pos);
                $(".item_info_two").css('order',two_pos);
            }else{
                $('.detail').css({'display':''});
                $(".item_info_one").css('order','');
                $(".item_info_two").css('order','');
            }
            var font = $("#font").val()??false;
            if(font){
                var now_font = +pre_font_num + +font;
                $('.info_block').css('font-size',now_font+'px');
            }
            var one_width = $("#one_size").val()??false;
            var two_width = $("#two_size").val()??false;
            if(one_width){
                $(".item_info_one").css('width',one_width+"mm");
            }
            if(two_width){
                $(".item_info_two").css('width',two_width+"mm");
            }
            var v_space = $("#v_space").val()??false;
            if(v_space){
                $(".item_tag").css('margin-bottom',v_space+"mm");
            }
            $(".item_tag").removeClass('default');
        }else{
            $(fault_field).addClass('is-invalid');
            toastr.error('Enter the Required Input !');
        }
    }


    function testprint(){
        // Select the div you want to print
        var $content = $('.machine');
        
        // Get the full outer HTML
        var html = $content[0].outerHTML;
        
        // Open a new print window
        var printWindow = window.open('', '', 'width=600,height=400');

        // Write the content with CSS
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Tag</title>
                <link rel="stylesheet" href=""> <!-- Replace with real CSS path -->
                <style>
                    @media print {
                        @page {
                            size: auto;
                            margin: 0;
                            height:auto;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            background: white;
                        }
                    }
                    .detail >ul{
                        align-content:center;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                    }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                ${html}
            </body>
            </html>
        `);

        printWindow.document.close();
    }


    function testprint__(){
         window.print();
        // var content = $('.machine').html();
        // var printWindow = window.open('');
        // printWindow.document.write('<html><head><title>Print</title>');
        // printWindow.document.write('<style>@media print { body { font-family: sans-serif; } }</style>');
        // printWindow.document.write('</head><body >');
        // printWindow.document.write(content);
        // printWindow.document.write('</body></html>');
        // printWindow.document.close();
        // printWindow.focus();
        // printWindow.print();
        // printWindow.close();
    }
    var edit_ele = '';
    $('.size_edit').click(function(e){
        e.preventDefault();
        $("#size_block").addClass('edited');
        edit_ele = $(this).attr('href');
        const name = $(edit_ele).data('name');
        const machine = $(edit_ele).data('machine');
        const tag = $(edit_ele).data('tag');
        const code = $(edit_ele).data('code');
        const info = $(edit_ele).data('info');
        const one = $(edit_ele).data('one');
        const two = $(edit_ele).data('two');
        const id = $(edit_ele).data('id');
        $("#edit").val(id);
        $("#name").val(name);
        $("#m_left").val(machine.l);
        $("#m_right").val(machine.r);
        $("#t_left").val(tag.l);
        $("#t_right").val(tag.r);
        $("#t_width").val(tag.w);
        $("#t_height").val(tag.h);
        $("#v_space").val(tag.v);
        $("#code_width").val(code.w);
        $("#code_pad").val(code.s);
        $("#info_width").val(info.w);
        $("#font").val(info.f);
        $("#"+one.p+"_left").prop('checked',true);
        $("#one_size").val(one.w);
        $("#two_size").val(two.w);
        $("#size_block").find('[type="submit"]').text('Update');
    });

    $("#size_setup_form").submit(function(e){
        e.preventDefault();
        if($("#name").val()!=""){
            var data = $(this).serialize();
            var path = $(this).attr('action');
            $.post(path,data,function(response){
                if(response.success){
                    $("#size_setup_form").trigger('reset');
                    success_sweettoatr(response.success);
                    if(response.data && edit_ele!=""){
                        const data = response.data;
                        $(edit_ele).data('name',data.name);
                        $(edit_ele).data('machine',data.machine);
                        $(edit_ele).data('tag',data.tag);
                        $(edit_ele).data('code',data.image);
                        $(edit_ele).data('info',data.info);
                        $(edit_ele).data('one',data.one);
                        $(edit_ele).data('two',data.two);
                        $(edit_ele).data('id',data.id);
                        $(edit_ele).html(data.name);
                    }
                }else if(response.errors){
                    var field = '';
                    var num = 0;
                    $.each(response.errors,function(i,v){
                        console.log(i + v);
                        if(num==0){
                            field = '#'+i;
                        }
                        num++;
                        toastr.error(v);
                    });
                    $(field).focus();
                }else {
                    toastr.error(response.error);
                }
                $('[type="submit"]').prop('disabled',false);
                $("#process_wait").hide();
            });
        }else{
            $("#process_wait").hide();
            $('#name').focus();
            $('[type="submit"]').prop('disabled',false);
            toastr.error("Please Provide Label/Title of Size !")
        }
    }); 

      var size_title = '';  
    $('.size_btn').click(function(e){
        // var parent_font = parseFloat($('.detail').eq(0).css('font-size'));
        // var info_font = parseFloat($('.item_info_one').eq(0).css('font-size'));
        // var pre_font_num = (info_font/parent_font)*100;
        size_title = $(this).data('name');
        // var pre_font = $('.info_block').eq(0).css('font-size');
        // var pre_font_num = pre_font.replace('px','');

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
</script>

@include('layouts.vendors.js.passwork-popup')

@endsection