  @extends('layouts.vendors.app')

  @section('content')

@php 
	$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>'];
	$path = ["Stocks"=>route('stock.new.dashboard')];
	$data = new_component_array('newbreadcrumb',"New Stock",$path) 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
	<style>
    #item_list{
        padding:0px;
        list-style:none;
        border:1px solid gray;
        position: absolute;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        background-color: white;
    }
    #item_list > li{
        padding:2px;
    }
    #item_list > li:hover,#item_list > li.hover{
        background:#efefef;
    }
    td select {
        appearance: none;         /* Standard */
        -webkit-appearance: none; /* Safari/Chrome */
        -moz-appearance: none;    /* Firefox */
        background: none;         /* Optional: Remove background */
        border: 1px solid #ccc;   /* Optional: Add your own border */
        padding-right: 10px;      /* Adjust space for text */
    }
    td .form-control{
        padding:2px 5px!important;
    }
    td .form-control.blank-required{
        border:1px dashed red;
    }
</style>
<style>
    #element_popup{
        position: absolute;
        top:0;
        bottom:0;
        width:100%;
        z-index: 2;
    }
    #element_area{
        right: 0;
        position: absolute;
        background:white;
        box-shadow: 1px -1px 1px 1px lightgray;
    } 
    /* .item_element_container{
        box-shadow:1px 2px 3px inset;
    } */
    .item_element_container table thead tr th{
        color: #040404;
        padding: 2px;
        /* border: 1px solid gray; */
        font-weight: normal;
        border: 1px solid #cacaca;
        font-style: italic;
        font-size:11px;
    }
    .item_element_container table tbody tr td{
        padding:2px;
        font-size:10px;
    }
	.element_table_block{
        position:relative;
    }
    .element_table_block > a{
        position: absolute;
        left:0;
        top:0;
    }
    label.image_for{
        position:relative;
    }
    label.image_for.selected > button{
        position: absolute;
        right:0;
        margin: 0;
    }
</style>
    <style>
    .item{
        width:200px;
    }
    .tag,.rfid,.huid {
        width:50px;
    }
    .gross,.less,.net,.fine{
        width:50px;
    }
    .element_chrg,.labour_chrg{
        width:70px;
    }
    .rate{
        width:90px;
    }
    .other{
        width:70px;
    }
    .discount_value{
        width:50px;
    }
    .ttl{
        width:100px;
    }
	#item_repeat_block.blocked input, #item_repeat_block.blocked label{
        cursor: not-allowed;
        pointer-events: none;
    }
    #item_repeat_block.blocked:after{
        position: absolute;
        content:"";
        left:0;right:0;top:0;bottom:0;
        background:black;
        background: #ffffffd6;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
    <section class = "content">
        <div class = "container-fluid">
            <div class = "row">
                <!-- left column -->
                <div class="col-md-12 p-0">
                    <!-- general form elements -->
                    <div class="card card-primary">

                        <div class="card-body p-1">
                            <form id = "submitForm" method="POST" action="{{ route('stock.new.create')}}" class = "myForm" enctype="multipart/form-data" autocomplete="off">
								<div class="row  p-0">
									<div class="col-md-8 form-inline ">
										<div class="input-group px-1">
											<div class="input-group-text p-1">
												<label for="stock_type" class="m-0">STOCK </label>
											</div>
											<select name="stock_type" class="form-control" id="stock_type" style="" >
												<option value="Gold">Gold</option>
												<option value="Silver">Silver</option>
												<option value="Stone">Stone/Gems</option>
												<option value="Artificial">Artificial</option><option value="Franchise-Jewellery">Franchise Jewellery</option>
											</select>
										</div>
										<div class="input-group px-1">
											<div class="input-group-text p-1">
												<label for="entry_type" class="m-0">ENTRY </label>
											</div>
											<select name="entry_type" id="entry_type" class="form-control">
												<option value="both" >Both</option>
												<option value="loose">loose</option>
												<option value="tag">Tag</option>
											</select>
										</div>
										<div class="input-group px-1" id="item_repeat_block">
											<div class="input-group-text p-1">
												<label for="entry_type" class="m-0">ITEM </label>
											</div>
											<label class="form-control input-group-append w-auto" for="repeat_same"><input type="radio" name="repeat" value="same" id="repeat_same" checked> SAME</label>
											<label class="form-control input-group-append w-auto" for="repeat_many"><input type="radio" name="repeat" value="many" id="repeat_many"> MANY</label>
										</div>
										<div class="m-auto p-1">
											<button type="button" class="btn btn-sm btn-outline-info mx-2 m-auto" data-toggle="modal" data-target="#newmodal">
												Create Item
											</button>
										</div>
									</div>
									<div class="col-md-4 form-inline ">
										<div class="input-group" style="margin-left:auto;">
											<input type="hidden" name="entry_num" value="" id="now_entry_number">
											<label class="form-control w-auto">ENTRY : <span id="curr_entry_num">0</span></label>
											<label class="form-control w-auto">DATE : {{ date("d-M-Y",strtotime('now')) }}</label>
										</div>
									</div>
								</div>
                                @csrf
                                <div class="col-12" id="item_form_area">
                                    
                                </div>
                                <div class="col-12 text-center" id="item_form_loader">
                                    <span><i class="fa fa-spinner fa-spin"></i> Loading Form....</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- <ul id="item_list"style="display:none;">
                    
                </ul> -->
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
        
		
		<style>
            #clear_item_modal{
                position: fixed;
                top: 0;
                right: 0;
                width: 100%;
                bottom: 0;
                left: 0;
                z-index:9999;
                background-color: #00000080;
                display: none;
            }
            #clear_item_modal .clear_item_content{
                position:relative;
                /*margin: auto;*/
                width: fit-content;
                background: white;
                top: 50%;
                left: 50%;
                border:1px dashed #f95600;
                border-radius:15px;
                /*box-shadow:1px 2px 3px lightgray;*/
                transform: translate(-50%, -50%);
            }
        </style>
		<div class="" id="clear_item_modal">
			<div class="clear_item_content text-center p-2">
				<p class="w-100"><b><i>Clear all Listed Items ?</i></b></p>
				<div class="w-100 text-center d-flex flex-wrap">
					<label for="yes" class="form-control w-auto m-auto" id="yesBtn"><input type="radio" name="clear" value="yes" id="yes"> YES</label>
					<label for="no" class="form-control w-auto m-auto" id="noBtn"><input type="radio" name="clear" value="no" id="no"> No</label>
				</div>
				<hr class="mx-0 my-2">
				<div class="w-100 text-center">
					<button type="button" name="option" value="choosed" id="doneBtn" class="btn btn-sm btn-success ">Done</button>
				</div>
				<small class="w-100 text-center text-danger" style="display:none;" id="choice_msg"></small>
			</div>
		</div>
		
		
    </section>
    @include('vendors.stocks.newpage.createitemmodal')
  
@endsection

@section('javascript')
<script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('section.content').append(`<ul id="item_list"style="display:none;"></ul>`);
    });
    
	function showitem(item){
        const input = item;
        const offset = input.offset();
        const inputHeight = input.outerHeight();
        const list = $('#item_list');

        // Temporarily show to get its height
        list.css({ visibility: 'hidden', display: 'block' });
        const listHeight = list.outerHeight();
        list.css({ visibility: '', display: 'none' });

        const windowBottom = $(window).scrollTop() + $(window).height();
        const spaceBelow = windowBottom - (offset.top + inputHeight);

        // Positioning logic
        const topPos = (spaceBelow > listHeight)
        ? offset.top + inputHeight  // show below
        : offset.top - listHeight;  // show above

        list.css({
            position: 'absolute',
            top: topPos,
            left: offset.left,
            display: 'block',
            zIndex: 999
        });
    }
	
    $(document).on('input','.blank-required',function(){
        $(this).removeClass('blank-required');
    });
    $(document).on('input change','.item_input',function(){
        $(this).removeClass('is-invalid');
    });

    $('select.my_select').myselect96(true);

	$(document).on('change','.image',function(){
        const tbody_id = $(this).closest('tbody').attr('id');
        const index = $('tbody#'+tbody_id+' >tr.item_tr').index($(this).closest('tr.item_tr'));
        const item = $(document).find('.item').eq(index).val()??false;
        if(!item){
            $(this).val('');
            $(document).find('.item').eq(index).focus();
            toastr.error("Enter/Select Item First !");
        }else{
            $(document).find("#image_for_"+index).html(`&check;OK<button type='button' class='btn btn-sm btn-outline-danger px-1 py-0' onClick='resetitemimage($(this),${index});'>&times;</button>`).addClass('selected text-success');
        }
    });

    function resetitemimage(input,index){
        $(document).find("input#image_"+index).val('');
        $(document).find("label#image_for_"+index).html('Image').removeClass('selected text-success');
    }

    $(document).on('keydown', function(e) {
		if(e.key === 'Tab' && $(document).find('ul#item_list').css('display')=='block'){
			$('ul#item_list > li.hover > a').trigger('click');
			return false;
        }
        //if((e.shiftKey && e.key === 'ArrowDown') || e.key === 'Tab'){
            if(e.key === 'ArrowDown' || e.key === 'ArrowUp'){
                var input = $(document).find(':focus');
                if(input.hasClass('item_input')){
					if($('ul#item_list').css('display')=='block'){
                        const ul = $('ul#item_list');
                        var li_index = ul.find('li.hover').index();
                        var new_li = false;
                        if(e.key=="ArrowDown" && li_index!=(ul.find('li').length - 1)){
                            new_li = li_index + 1;
                        }else if(e.key=="ArrowUp" && li_index != 0){
                            new_li = li_index - 1;
                        }
                        if(new_li !== false){
                            ul.find('li.stock_item').removeClass('hover');
                            ul.find('li.stock_item').eq(new_li).addClass('hover');
                            scrollItemIntoView(ul,ul.find('li.stock_item').eq(new_li));
                        }
                        return false;
                    }
                    var ahead = true;
                    const tbody_id = input.closest('tbody').attr('id');
                    const tr_ind = $('tbody#'+tbody_id+' >tr.item_tr').index(input.closest('tr.item_tr'));
                if(ahead){
                    var ttl_tr = $('tbody#'+tbody_id+' >tr').length;
                    if(e.key === 'ArrowUp'){
						const item_field = $(`tbody#${tbody_id}>#item_tr_${tr_ind-1}`).find(`td>input.item`);
                        if(item_field.val()==''){
                            item_field.focus();
                        }else{
                            $(document).find(`input[name="${input.attr('name')}"]`).eq(tr_ind-1).focus();
                        }
                        //$(`tbody#${tbody_id}>#item_tr_${tr_ind-1}`).find(`td>input.item`).focus();
                    }else{
                        if(ttl_tr == tr_ind+1 &&  ttl_tr <=49){
                            var tr_new = $('tbody#'+tbody_id+' >tr').eq(0).clone();
                            tr_new.attr('id','item_tr_'+ttl_tr);
                            $.each(tr_new.find('input,select,a'),function(ini,inv){
                                $(this).val("");
                                $(this).attr('id',$(this).attr('id').replace(/\d+$/, tr_ind+1));
                            });
                            $("tbody#"+tbody_id).append(tr_new);
                            if(e.key === 'ArrowDown'){
                                $(`tbody#${tbody_id}>#item_tr_${ttl_tr}`).find(`td>input.item`).focus();
                            }
                        }else{
							const item_field = $('tbody#'+tbody_id+' >tr').eq(tr_ind+1).find('td>input.item');
                            if(item_field.val()==''){
                                item_field.focus();
                            }else{
                                $(document).find(`input[name="${input.attr('name')}"]`).eq(tr_ind+1).focus();
                            }
                            //$('tbody#'+tbody_id+' >tr').eq(tr_ind+1).find('td>input.item').focus();
                        }
						if(e.key === 'ArrowDown'){
                            switch($('#stock_type').val()){
                                case 'Gold':
                                case 'Silver':
                                case 'Stone':
                                    calculatesum(tr_ind);
                                    break;
                                case 'Artificial':
                                    calculateitemsum(tr_ind);
                                    break;
                                default:
                                    toastr.error("Invalid Stock Type !");
                                    $("#stock_type").addClass('is-invalid').focus();
                                    break;
                            }
                        }
                    }
                }
            }
       }
    });

	function scrollItemIntoView($container, $item) {
        let itemTop    = $item.position().top;
        let itemBottom = itemTop + $item.outerHeight();
        let viewTop    = $container.scrollTop();
        let viewBottom = viewTop + $container.innerHeight();

        if (itemTop < 0) {
            // item is above visible area
            $container.scrollTop(viewTop + itemTop);
        } else if (itemBottom > $container.innerHeight()) {
            // item is below visible area
            $container.scrollTop(viewTop + (itemBottom - $container.innerHeight()));
        }
    }

    $(document).on('focus','.item',function(){
        if($("#item_list").css('display')=='block'){
            $("#item_list").hide();
            $("#item_list").empty();
        }
		const entry_mode = $("#entry_type").val()??false;
        if(entry_mode!='loose'){
            const item_repeat = $('[name="repeat"]:checked').val();
            if(item_repeat == 'same'){
                repeatitems($(this));
            }
        }else{
            $(document).find('.tag').val('').prop('readonly',true);
        }
    });
	
	function repeatitems(element){
        const index = $('.item').index(element);
        const base_index = +index - +1;
        let classArr = ['gross', 'net','fine','chrg','other','less'];
        if(base_index >= 0){
            $('tr.item_tr').eq(base_index).find('td').each(function(i,v){
                $(this).find('input').each(function(ini,inv){
                    const input = $(this)??false;
                    if(input && !classArr.some(cls => input.hasClass(cls)) && input.val()!=""){
                        const input_val = input.val();
						const curr_input =  $(document).find(`[name="${input.attr('name')}"]`).eq(index);
                        if(input.hasClass('tag')){
                            // Find the last numeric sequence
                            let match = input_val.match(/(\d+)(?!.*\d)/);
                            if (match) {
                                let numPart = match[1];                      // "0058"
                                let numLen  = numPart.length;                // 4
                                let num     = parseInt(numPart, 10) + 1;     // 59
                                let newNum  = num.toString().padStart(numLen, '0'); // "0059"

                                // Prefix = part before the number
                                let prefix = input_val.slice(0, -numLen);

                                let newCode = prefix + newNum;
                                curr_input.val(newCode);
                            }
                        }else{
							if(input.hasClass('piece')){
                                curr_input.prop('readonly',true);
								curr_input.val(input_val??1);
                            }else{
								curr_input.val(input_val);
							}
                        }
                    }
                });
                $(this).find('select').each(function(ini,inv){
                    const select =$(this)??false;
                    if(select && select.val()!=""){
                        $(document).find(`[name="${select.attr('name')}"]`).eq(index).val(select.val());
                    }
                });
            });
			/*var item_count = 0;
            $(document).find('.item').each(function(i,v){
                if($(v).val()!=""){
                    item_count++;
                }
                $("#list_item").val(item_count);
                $("#list_count").val(item_count);
            });*/
        }
    }

	$(document).on('input','.tag',function(){
        const index = $(this).closest('tr').index();
        const item = $(document).find('.item').eq(index);
        const item_name = item.val()??false;
        if(item_name){
            var quant = $(document).find('.piece ').eq(index);
            if($(this).val()!=""){
                quant.val('1').prop('readonly',true);
            }else{
                quant.val('').prop('readonly',false);
            }
        }else{
            toastr.error("Please Select the Item First !");
            $(this).val('');
            item.focus();
        }
    });

    $(document).ready(function(){
        $("#item_form_loader").show();
        $("#stock_type").trigger('change');
    });
	
    $('#stock_type').change(function(){
		const script_rev = ['.silver','.gold','.stone','.art'];
		$("#now_entry_number").val("");
		$("#item_form_area").empty();
        $("#item_form_loader").show();
        const stock = $(this).val().toLowerCase().replace(/[ -]/g, "");
		$.each(script_rev,function(i,v){
			$(document).off(`${v}`);
		});
        $("#item_form_area").load("{{ route('stock.create.item.form') }}/"+stock,"",function(response){
            $("#item_form_loader").hide();
			const entry_num = $(document).find('#entry_num').val();
            $("#curr_entry_num").html(entry_num);
            $("#now_entry_number").val(entry_num);
        });
    });

    $("#entry_type").change(async function(){
		var clear = false;
		const entry_type = $("#entry_type").val()??false;
		var filledCount = $('input[name="item[]"]').filter(function() {
            return $.trim($(this).val()) !== '';
        }).length;
        if(filledCount>0){
            clear = await askClearForm();
        }
		if(clear){
			$(document).find('.item_input').val("");
			$(document).find('#item_list').empty().hide();
			$(document).find('input.piece').prop('readonly',false);
			clear = false;
		}else if(entry_type && entry_type!='loose'){
			$("#item_repeat_block").removeClass('blocked');
			//const entry_type = $("#entry_type").val()??false;
            if(entry_type=='tag'){
                generatetagforlisteditems();
            }
		}else{
			$(document).find('.tag').val('').prop('readonly',true);
			$("#item_repeat_block").addClass('blocked');
		}
    });

	function generatetagforlisteditems(){
        let uniqueTypes = [...new Set( $('input[name="type[]"]').map(function() {
                         return $(this).val().trim(); 
                    }).get().filter(v => v != ''))] ;
        if(uniqueTypes.length >0){
            let msg_stram = "";
            $.each(uniqueTypes,function(i,v){
                $.get("{{ route('stock.item.tag') }}","item="+v,function(response){
                    if(response.status){
                        let num = response.num;
                        $(`input[name="type[]"][value="${v}"]`).each(function(){
                            const index = $(document).find('.type').index($(this));
                            if( response.prefix && response.length &&  response.num){
                                const numStr = String(num).padStart(response.length, '0');
                                const tag_stram = response.prefix+numStr;
                                $(document).find('.tag').eq(index).val(tag_stram);
                                num++
                            }
                        });
                    }else{
                       msg_stram+=response.msg+"<br>";
                    }
                });
            });
            if(msg_stram!=""){
                toastr.error(msg_stram);
            }
        }
    };

	function askClearForm() {
        return new Promise((resolve) => {
            $("#clear_item_modal").show();
            userChoice = null; // reset
            $("#choice_msg").text("").hide();
            $("#yesBtn").on("click", function(){
                $("#choice_msg").text("").hide();
                userChoice = true; // Yes clicked
            });

            $("#noBtn").on("click", function(){
                $("#choice_msg").text("").hide();
                userChoice = false; // No clicked
            });

            $("#doneBtn").on("click", function(){
                if(userChoice !== null){
                    $("#clear_item_modal").hide();
					$("input[name='clear'][type='radio']").prop('checked',false);
                    resolve(userChoice); // proceed with choice
                } else {
                    $("#choice_msg").text("Please select Yes/No first !").show();
                }
            });
        });
    }

    $("#newitem").find('.form-control').on('input',function(){
        $(this).removeClass('is-invalid');
    });

    $(document).on('hidden.bs.modal','#newmodal',function(){
        $("#newitem").trigger('reset');
        $("#block_back").removeClass('active');
        $("#recent_items_table").addClass('default').empty().append(`<tr><td class="text-info text-center" > Recently Created !</td></tr>`);
        $("#item_group_name").val('');
        $('#item_group_block').addClass('default').empty().append(`<li class='text-info text-center w-100'>Recently Addedd !</li>`);
    });

    $("#newitem").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        $(this).find('.form-control').removeClass('is-invalid');
        $.post(path,formdata,function(response){
            if(response.done){
                success_sweettoatr(response.done);
                $("#recent_items").show();
                let name = $("#item_name").val()??false;
                let group = $('#item_group option:selected').text()??false;
                if(name && group ){
                    let tr = `<tr>
                                <th class="p-1">${name}</th>
                                <td class="p-1">${group}</td>
                                </tr>`;
                    if($("#recent_items_table").hasClass('default')){
                        $("#recent_items_table").removeClass('default').empty().append(tr);
                    }else{
                        $("#recent_items_table").append(tr);
                    }
                }
                $("#newitem").trigger('reset');
            }else if(response.fail){
                toastr.error(response.fail);
            }else if(response.errors){
                $.each(response.errors,function(ei,ev){
                    $("#"+ei).addClass('is-invalid');
                    toastr.error(ev);
                });
            }
        });
    });

    $("#newitemgroup").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = $(this).serialize();
        $(this).find('.form-control').removeClass('is-invalid');
        $.post(path,formdata,function(response){
            if(response.done){
                success_sweettoatr(response.done);
                if(response.item_group){
                    var count = $(document).find('li.items_group_li').length;
                    //alert(count);
                    var li = `<li class="border border-info p-1 m-1 items_group_li "><span class="badge badge-sm badge-info">${count+1}</span> ${response.item_group.item_group_name}</li>`;
                    if($("#item_group_block").hasClass('default')){
                        $("#item_group_block").removeClass('default');
                        $("#item_group_block").empty().append(li);
                    }else{
                        $("#item_group_block").append(li);
                    }
                    if($("#item_group option:selected").hasClass('default')){
                        $("#item_group option:selected.default").text('Select ')
                    }
                    $("#item_group").append(`<option value="${response.item_group.id}">${response.item_group.item_group_name}</option>`);
                }
                if(response.coll){
                    $("select#item_group_col").append(`<option value="${response.coll.id}">${response.coll.name}</option>`);
                    $('#item_group_col').redraw(true);
                }
                if(response.cat){
                    $("select#item_group_cat").append(`<option value="${response.cat.id}">${response.cat.name}</option>`);
                    /*$('#item_group_cat').redraw();*/
                }
                $("#newitemgroup").trigger('reset');
            }else if(response.fail){
                toastr.error(response.fail);
            }else if(response.errors){
                $.each(response.errors,function(ei,ev){
                    $("#"+ei).addClass('is-invalid');
                    toastr.error(ev);
                });
            }
        });
    });
    
    $("#submitForm").submit(function(e){
        e.preventDefault();
        var path = $(this).attr('action');
        var formdata = new FormData(this);
        const button = e.originalEvent.submitter;
        const btn_val = $(button).val();
        const btn_nam = $(button).attr('name');
        formdata.append(btn_nam, btn_val);
        $.ajax({
            url: path, // your PHP endpoint
            type: 'POST',
            data: formdata,
            contentType: false, // important for file upload
            processData: false, // prevent jQuery from processing data
            success: function(response) {
                //$('#response').html(response); // show server response
                if(response.status){
                    success_sweettoatr(response.msg)
                    location.href = response.next;
                }else if(response.errors){
                    var num = 0;
                    $.each(response.errors,function(erri,errv){
                        var err_arr = erri.split('.');
                        const err_input = err_arr[0];
                        const err_index = err_arr[1];
                        $(document).find(`#${err_input}_${err_index}`).addClass('blank-required');
                        toastr.error(errv[0]);
                        if(num==0){
                            $(document).find(`#${err_input}_${err_index}`).focus();
                            num++;
                        }
                    });
                }else{
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                $('#response').html('Upload failed: ' + error);
            }
        });
    });
    
</script>

@endsection

