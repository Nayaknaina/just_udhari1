  @extends('layouts.vendors.app')

  @section('content')

  
@php 
	$anchor = ['<a href="'.route('billing',['sale']).'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> New</a>','<a href="'.route('billing.all',['sale']).'" class="btn btn-sm btn-outline-info py-1"><i class="fa fa-list"></i> All</a>'];
	$data = new_component_array('newbreadcrumb',"Edit ".ucfirst($type)." Bill") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
        .form-control.no-border.is-invalid{
            border:1px solid red!important;
        }
        select.blocked,
        input.blocked{
            pointer-events: none;
            background-color: #f5f5f5; 
            color: #666;
        }
		
		b.stock_mark{
            padding:1px;
            border-radius:50%;
            background:white;
        }
        b.gold_mark{
            color:orange;
            border:1px solid orange;
        }
        b.silver_mark{
            color:lightgray;
            border:1px solid lightgray;
        }
        b.stone_mark{
            border:1px solid gray;
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

            <form id = "submitForm" method="POST" action="{{ route('billing.update',['sale',$bill_data->id])}}" class = "myForm" enctype="multipart/form-data" autocomplete="off">

            @csrf

            @method('post')
            @php 
                $party = $bill_data->partydetail;
                $bill_items = $bill_data->billitems;
                $bill_type = $bill_data->bill_prop;
                
                $$bill_type = 'checked';
            @endphp
            <div class="row mb-2 " >
                <div class="col-md-10 col-6">
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <label for="suplier">Bill Type </label>
                            <div class="input-group  mb-0" id="bill_type_select">
                                <label class="form-control">
                                    <input type="radio" name="bill_type" class="" id="bill_type_est" value="e"  {{ @$e }}> Rough
                                </label>
                                <label class="form-control ">
                                    <input type="radio" name="bill_type" class="" id="bill_type_inv" value="g" {{ @$g }}  > Gst
                                </label>
                            </div>
                            <input type="hidden" name="bill_id" value="{{ $bill_data->id }}">
                        </div>
                        @php 
                            $party_arr = [
                                'c'=>['type'=>"Customer",'name'=>'custo_full_name','contact'=>'custo_fone'],
                                's'=>['type'=>"Supplier",'name'=>'supplier_name','contact'=>'mobile_no'],
                                'w'=>['type'=>"Whole Seller",'name'=>'','contact'=>''],
                                ];
                        @endphp
                        <div class="form-group col-md-4 mb-2">
                            <label for="custo_type">Party Type</label>
                            <label class="form-control  mb-0">{{ @$party_arr["{$bill_data->party_type}"]['type'] }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <label for="customer">Party Name</label>
                            @php $party_name = $party_arr["{$bill_data->party_type}"]['name']  @endphp
                            <label class="form-control mb-0">{{ @$party->$party_name }}</label>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label for="mobile">Party Mobile</label>
                            @php $party_contact = $party_arr["{$bill_data->party_type}"]['contact']  @endphp
                            <label class="form-control mb-0">{{ @$party->$party_contact }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="flot-label-input extra-sm">
                        <label for="">Bill No.</label>
                        <input type="text" class="form-control ju-form-control" name="bill_no" id="bill_no" value="{{ @$bill_data->bill_number }}">
                    </div>
                    <div class="flot-label-input extra-sm ">
                        <label for="">Bill Date</label>
                        <input type="date" class="form-control ju-form-control" name="bill_date" id="bill_date" value="{{ @$bill_data->bill_date }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="flot-label-input extra-sm">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control ju-form-control" name="due_date" id="due_date" value="{{ @$bill_data->due_date }}" max="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <style>
                .extra-sm .form-control{
                        height:10%;
                }
            </style>
            <div class="row">
                <div class="col-12 ">
                    @include("vendors.billings.sale.editsaleform")
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 text-center">
                    <div class="card card-default border-dark">
                        <div class="card-header p-1 mb-1" style="border-bottom:1px dashed gray;">
                            <h2 class="card-title text-dark w-100">Pay Options</h2>
                        </div>
                        <div class="card-body p-1">
                            <a href="{{ route('billing.operation.option',['amount']) }}" class="btn btn-sm btn-outline-dark form-control h-auto option_btn mb-1 pay_option_btn" id="pay_option_amount">Amount</a>
                            <a href="{{ route('billing.operation.option',['scheme']) }}" class="btn btn-sm btn-outline-dark form-control h-auto option_btn mb-1 pay_option_btn" id="pay_option_scheme">Scheme</a>
                            <a href="{{ route('billing.operation.option',['metal']) }}" class="btn btn-sm btn-outline-dark form-control h-auto option_btn mb-1 pay_option_btn" id="pay_option_metal">Metal</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-7" >
                    <div class="row">
                        <div class="col-md-5 p-2 edit" id="option_pages" style="border:1px dashed #f95600;background: #fff9f6;box-shadow: -1px 2px 2px 2px lightgray;height:fit-content;">
                            <span class="text-center text-info m-auto"><= Select Pay Option </span>
                        </div>
                        <div class="col-md-7 p-0" id="pay_info">
                            <table class="table table_theme">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>MODE</th>
                                        <th>MEDIUM</th>
                                        <th>PAYMENT</th>
                                        <th>REMARK</th>
                                        <th>&cross;</th>
                                    </tr>
                                </thead>
                                <tbody id="all_pays" class="text-center edit">
                                    @php 
                                        $bill_pays = $bill_data->payments;
                                    @endphp
                                    @if($bill_pays->count()>0)
                                        @foreach($bill_pays as $pi=>$pay) 
                                            <tr class="source_{{ $pay->pay_source }}">
                                                <td>{{ $pi+1 }}</td>
                                                <td>{{ $pay->pay_source }}</td>
                                                <td>{{ $pay->pay_method }}</td>
                                                <td>{{ $pay->pay_value }}</td>
                                                <td>{{ $pay->pay_remark }}</td>
                                                <td>
                                                    <span class="text-success py-0 px-1" style="border:1px solid green;">&check;</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else 
                                        <tr id="no_payment">
                                            <td colspan="6" class="text-center text-danger" >No Payment Yet !</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <table class="table table-bordered table_theme">
                        <tbody class="billing" >
                            <tr class="no-hover">
                                <th >SUB</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-hover no-border text-right" id="sub" name="sub" readonly value="{{ $bill_data->sub }}">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th >DISCOUNT</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-hover no-border text-right" id="discount" name="discount" value="{{ $bill_data->discount }}">
                                    <select class="p-0 text-right" style="font-size: unset;" id="discount_unit" name="discount_unit">
                                        @php 
                                            $disc_unit = $bill_data->discount_unit;
                                            if($disc_unit){
                                                $disc_unit  = "du{$disc_unit}";
                                                $$disc_unit = 'selected';
                                            }
                                        @endphp
                                        <option value="p" {{ @$dup }} >%</option>
                                        <option value="r" {{ @$dur }} >Rs</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>GST</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right" id="gst" name="gst" value="{{ $bill_data->gst }}">
                                    <span>&nbsp;%</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>TOTAL</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right text-warning" id="total" name="total" style="font-weight:bold;" readonly  value="{{ $bill_data->total }}">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>ROUND</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right" id="round" name="round"value="{{ $bill_data->round }}" readonly>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>FINAl</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right text-info" id="final" name="final" style="font-weight:bold;" readonly value="{{ $bill_data->final }}">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>PAYMENT</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-secondary text-right" id="payment" name="payment" readonly style="font-weight:bold;" value="{{ $bill_data->payment }}">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th >BALANCE</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-danger text-right" id="balance" name="balance" readonly style="font-weight:bold;" value="{{ $bill_data->balance }}">
                                    <span>Rs</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <ul id="item_list"style="display:none;" data-parent=""></ul>
            <div class="row" >
                <div class="col-12 text-center pt-3">
                    <button type = "submit" class="btn btn-danger"> Submit </button>
                    <input class="form-control" type = "hidden" name="unique_id" value = "{{time().rand(9999, 100000) }}" >
                </div>
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
    
  </section>
    @include('vendors.commonpages.newcustomerwithcategory');
  @endsection

  @section('javascript')
<script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
	var pressed_key_code = pressed_key_name = false;
	var scaned =  false;
	
	$('input[name="delete_item[]"]').change(function(e){
        const index = $('input[name="delete_item[]"]').index($(this));
        const label = $(this).closest('label');
        let pre_item_count = +($("#list_item").val()??0);
        let pre_item_quant = +($("#list_piece").val()??0);
        let pre_item_gross = +($("#list_gross").val()??0);
        let pre_item_net = +($("#list_net").val()??0);
        let pre_item_fine = +($("#list_fine").val()??0);
        let pre_item_chrg = +($("#list_chrg").val()??0);
        let pre_item_other = +($("#list_other").val()??0);
        let pre_item_ttl = +($("#list_total").val()??0);

        const row_quant = $(document).find('.piece').eq(index).val()??0
        const row_gross = $(document).find('.gross').eq(index).val()??0
        const row_net = $(document).find('.net').eq(index).val()??0
        const row_fine = $(document).find('.fine').eq(index).val()??0
        const row_chrg = $(document).find('.chrg').eq(index).val()??0
        const row_other = $(document).find('.other').eq(index).val()??0
        const row_ttl = $(document).find('.ttl').eq(index).val()??0
        if($(this).is(':checked')){
            pre_item_count--;
            pre_item_quant-= +row_quant;
            pre_item_gross-= +row_gross;
            pre_item_net-= +row_net;
            pre_item_fine-= +row_fine;
            pre_item_chrg-= +row_chrg;
            pre_item_other-= +row_other;
            pre_item_ttl-= +row_ttl;
            label.addClass('checked');
            $('#sale_form >tr').eq(index).addClass('deleted');
        }else{
            pre_item_count++;
            pre_item_quant+= +row_quant;
            pre_item_gross+= +row_gross;
            pre_item_net+= +row_net;
            pre_item_fine+= +row_fine;
            pre_item_chrg+= +row_chrg;
            pre_item_other+= +row_other;
            pre_item_ttl+= +row_ttl;
            label.removeClass('checked');
            $('#sale_form >tr').eq(index).removeClass('deleted');
        }
        $("#list_item").val(pre_item_count??0);
        $("#list_piece").val(pre_item_quant);
        $("#list_gross").val(pre_item_gross.toFixed(3));
        $("#list_net").val(pre_item_net.toFixed(3));
        $("#list_fine").val(pre_item_fine.toFixed(3));
        $("#list_chrg").val(pre_item_chrg.toFixed(2));
        $("#list_other").val(pre_item_other.toFixed(2));
        $("#list_total").val(pre_item_ttl.toFixed(2));
        $('#sub').val((pre_item_ttl??0).toFixed(2));
        billtotal();
    });
	
    $('select.my_select').myselect96();
    
    $(document).on('input change','.form-control',function(){
        $(this).removeClass('is-invalid');
    });

    $(document).on('keydown', function(e) {
        //if((e.shiftKey && e.key === 'ArrowDown') || e.key === 'Tab'){
			//alert(e.key);
        if(e.key === 'Tab' && $(document).find('ul#item_list').css('display')=='block'){
			$('ul#item_list > li.hover > a').trigger('click');
			return false;
        }
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
                        ul.find('li.bill_item').removeClass('hover');
                        ul.find('li.bill_item').eq(new_li).addClass('hover');
						scrollItemIntoView(ul,ul.find('li.bill_item').eq(new_li));
                    }
                    return false;
                }
                var ahead = true;
                const tbody_id = input.closest('tbody').attr('id');
                const tr_ind = $('tbody#'+tbody_id+' >tr').index(input.closest('tr'));
				$('#item_list').data('parent','tem_tr_'+tr_ind);
                /*if(e.key === 'Tab'){
                    const td_index = $('tbody#'+tbody_id+' >tr.item_tr').eq(tr_ind).find('td').index(input.closest('td'));
                    const td_count = $('tbody#'+tbody_id+' >tr.item_tr').eq(tr_ind).find('td').length - 1;
                    
                    ahead = (td_index == td_count)?true:false;
                }*/
                if(ahead){
                    var ttl_tr = $('tbody#'+tbody_id+' >tr').length;
                    if(e.key === 'ArrowUp'){
                        $(`tbody#${tbody_id}>#item_tr_${tr_ind-1}`).find(`td>input.item`).focus();
                    }else{
                        if(ttl_tr == tr_ind+1){
                            var tr_new = $('tbody#'+tbody_id+' >tr').eq(0).clone();
                            tr_new.attr('id','item_tr_'+ttl_tr);
                            $.each(tr_new.find('input,select,a'),function(ini,inv){
                                if($(this).prop('tagName')=='SELECT'){
                                    $(this).val($(this).find('option:selected').val());
                                }else{
                                    $(this).val("");
                                }
                                $(this).attr('id',$(this).attr('id').replace(/\d+$/, tr_ind+1));
                            });
                            $("tbody#"+tbody_id).append(tr_new);
                            if(e.key === 'ArrowDown'){
                                $(`tbody#${tbody_id}>#item_tr_${ttl_tr}`).find(`td>input.item`).focus();
                            }
                        }else{
                            $('tbody#'+tbody_id+' >tr').eq(tr_ind+1).find('td>input.item').focus();
                        }
                    }
                }
            }
        }else if(e.key === 'Enter' || e.key==='NumLock'){
			scaned = true;
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
        const input_index = $('.item').index($(this));
        $("#item_list").data('parent',"item_tr_"+input_index);
        //alert($("#item_list").data('parent'));
        if($("#item_list").css('display')=='block'){
            $("#item_list").hide();
            $("#item_list").empty();
        }
    });
    var count = 0;
    let typingTimer;
    let lastTime = 0;
    let isScanner = false;
    $(document).on('input','.item',function(e){
        let input = $(this);
        const now = Date.now();
        const timeDiff = now - lastTime;
        lastTime = now;

        // Agar characters bahut fast aaye → scanner ho sakta hai
        if (timeDiff < 30) {
            isScanner = true;
        } else {
            isScanner = false;
        }

        clearTimeout(typingTimer);

        if (isScanner) {
            typingTimer = setTimeout(function () {
                fetchRecords(input, "scanner");
            }, 100);
            $(document).on("shownList", "#item_list", function () {
                if($("#item_list > li").length == 1){
                    $("#item_list").find('li:first > a').click();
                }
            });
        } else {
            // normal typing case → debounce (e.g. 300ms after last key)
            typingTimer = setTimeout(function () {
                fetchRecords(input, "scanner");
            }, 100);
        }
        //fetchRecords($(this),input_val);
    });

    function fetchRecords(input) {
        var input_val = input.val().trim();
        const tbody_id = input.closest('tbody').attr('id');
        const input_index = $('tbody#'+tbody_id+' >tr.item_tr').index(input.closest('tr.item_tr'))??false;
        if(input_val){
            $.get("{{ route('billing.find.stock') }}", "keyword="+input_val, function (response) {
                //console.log("Response for " + source + ":", res);
                // yaha aap apna UI update ya aur action likh sakte ho
                let lis = '';
                    //alert(ids);
                if(response.stocks){
                    if(response.stocks.length > 0 ){     
						const stock_label_arr = {gold:'GL',silver:'SL',stone:'ST',artificial:'AR'};
                        $.each(response.stocks,function(si,stock){
                            if($(document).find(`input[name='id[]'][value='${stock.id}']`).length==0){
								const item_stock_type_label = (stock.stock_type).toLowerCase();
                                const data_arr = {
                                    "caret":stock.caret,
                                    "tag":stock.tag,
                                    "count":stock.avail_count,
                                    "gross":stock.avail_gross,
                                    "less":stock.less,
                                    "net":stock.avail_net,
                                    "tunch":stock.tunch,
                                    "wastage":stock.wastage,
                                    "fine":stock.avail_fine,
                                    "ele_charge":stock.element_charge,
                                    "ele_is":stock.have_element,
                                    "rate":stock.current_rate||stock.rate,
                                    "labour_value":stock.labour,
                                    "labour_unit":stock.labour_unit,
                                    "other":stock.charge,
                                    "disc":stock.discount,
                                    "disc_unit":stock.discount_unit,
                                    'stock':stock.stock_type
                                };
                                let prop_ul = `<ul class="d-flex flex-wrap p-0 item_prop text-center" style="list-style:none;">
                                                <li class="m-auto px-1"><b><u>${data_arr['tag']}</b></u></li>
                                                <li>|</li>
                                                <li class="m-auto px-1"><b>G-</b>${data_arr['gross']}gm</li>
                                                <li>|</li>
                                                <li class="m-auto px-1"><b>N-</b>${data_arr['net']}gm</li>
                                                <li>|</li>`;
                                                if(stock.stock_type=='gold'){
                                                    prop_ul+=`<li class="m-auto px-1"><b>K-</b>${data_arr['caret']}K</li>`;
                                                }
                                                prop_ul+=`</ul>`;
                                const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
                                const class_name = (si==0)?'hover':'';
                                lis += `<li class="${class_name} bill_item"><a href="javascript:void(null);" data-title="${stock.name}" data-target="${stock.id}" data-desc="${data}"  data-parent="item_tr_${input_index}"class="get_item"><b class="${item_stock_type_label}_mark stock_mark">${stock_label_arr[item_stock_type_label]}</b> : ${stock.name} !<br>${prop_ul}</li>`;
                            }
                        });
                    }else{
                        //$("#item_list").data('parent','');
                        lis = `<li ><a href="javascript:void(null);" data-parent="item_tr_${input_index}" data-val="false">No Item !</li>`;
                    }
                }
                count++;
                $("#item_list").data('parent','item_tr_'+input_index);
                if(lis!=''){
                    $("#item_list").html(lis);
                    showitem(input);
                }
            });
        }else{
            $("tbody#sale_form > tr").eq(input_index).find('input,select').val('');
            itemsum(input_index);
        }
    }

    function showitem(item){
        const input = item;
        const offset = input.offset();
        const inputHeight = input.outerHeight();
        const list = $('#item_list');

        // Temporarily show to get its height
        //list.css({ visibility: 'hidden', display: 'block' });
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
		list.show();
		list.trigger("shownList");
    }

    $(document).on('click','.get_item',function(){
        var input_index = $(this).data('parent').replace('item_tr_','');
		if($(this).data('val') && $(this).data('val')==false){
			 $("input.item").eq(input_index).select();
			 return false;
		}
        var title = $(this).data('title');
        var id = $(this).data('target');
        var data = $(this).data('desc');
        $("#item_list").empty().hide();
		title += (data.tag)?' ('+data.tag+')':'';
        $("input.item").eq(input_index).val(title);
        $("input.id").eq(input_index).val(id);
        $("input.name").eq(input_index).focus();
        var block_item =  $('select.caret').eq(input_index).add($('input.piece').eq(input_index)).add($('input.gross').eq(input_index)).add($('input.less').eq(input_index)).add($('input.net').eq(input_index)).add($('input.tunch').eq(input_index)).add($('input.wstg').eq(input_index)).add($('input.fine').eq(input_index));
        
        if(data.tag){
           block_item.addClass('blocked');
        }else{
            block_item.removeClass('blocked');
        }
        if(data.caret){
            $('select.caret').eq(input_index).val(data.caret);
        }
        if(data.count){
            if(data.tag){
             $('input.piece').eq(input_index).val(data.count??1);
            }
        }
        if(data.gross){
            $('input.gross').eq(input_index).val(data.gross);
        }
        
        if(data.less){
            $('input.less').eq(input_index).val(data.less);
        }
        if(data.net){
            $('input.net').eq(input_index).val(data.net);
        }
        if(data.tunch){
            $('input.tunch').eq(input_index).val(data.tunch);
        }else{
            if(data.caret){
                $('input.tunch').eq(input_index).val(Math.round(100/24 * data.caret));
            }
        }
        if(data.wastage){
            $('input.wstg').eq(input_index).val(data.wastage);
        }
        if(data.fine){
            $('input.fine').eq(input_index).val(data.fine);
        }
        if(data.ele_charge){
            $('input.chrg').eq(input_index).val(data.ele_charge);
        }
		if(data.rate){
			$('input.rate').eq(input_index).val(data.rate);
		}
        if(data.labour_value){
            $('input.lbr').eq(input_index).val(data.labour_value);
            $('select.lbrunit').eq(input_index).val(data.labour_unit);
        }
        if(data.other){
            $('input.other').eq(input_index).val(data.other);
        }
        if(data.disc){
            $('input.disc').eq(input_index).val(data.disc);
        }
        if(data.disc_unit){
            $('select.discunit').eq(input_index).val(data.disc_unit);
        }
        if(data.stock){
            $('input.stock').eq(input_index).val(data.stock);
        }
        //itemtotal();
        itemsum(input_index);
		//$("input.item").eq(input_index).focus();
        var e = jQuery.Event("keydown");
        e.which = 40;           // Arrow Down
        e.key = "ArrowDown";    // optional, modern browsers
        $(document).trigger(e);
    });

    $("#custo_type").on('change',function(){
        const custo_type = $(this).val();
        if(custo_type!=""){
            $("#mobile").val('').prop('readonly',true);
            $.get("{{ route('global.customers.search') }}","type="+custo_type,function(response){
                var option = '';
                if(response){
                    option = '<option value="" data-source="" data-target="#mobile">Find Name/Mobile</option>';
                    $.each(response,function(custoi,custo){
                        option+= `<option value="${custo.id}" data-source="${custo.mobile}" data-target="#mobile" >${custo.name}</option>`;
                    });
                }else{
                    option = '<option value="">No Party Found !</option>';
                }
                $("#customer").empty().append(option);
                $("#customer").redraw();
            });
        }
    })

    $("a.option_btn").click(function(e){
        e.preventDefault();
        const custo = $("#customer").val()??false;
        /*if(!custo){
            toastr.error("Custome Not Entered/Selected !");
            return false;
        }
        const bill_final = $("#final").val()??false;
        if(!bill_final){
            toastr.error("No Payment Needed !");
            return false;
        }*/
        $("#option_pages").empty().append('<p class="text-center"><span><i class="fa fa-spinner fa-spin"></i>Loading...</span></p>').show();
        $.get($(this).attr('href')+`/${custo}`,"",function(response){
            $("#option_pages").empty().append(response);
        });
    });

    function digitonly(event,num){
        let inputValue = event.target.value;

            // Allow only digits using regex
            inputValue = inputValue.replace(/[^0-9]/g, '');  // Remove anything that's not a digit

            // Ensure that the input has exactly 10 digits
            if (inputValue.length > num) {
                inputValue = inputValue.slice(0, 10);  // Trim to 10 digits
            }

            // Update the input field with the valid input
            event.target.value = inputValue;
    }

	$(document).on('customerformsubmit',function(e){
        let data  = e.originalEvent.detail;
        $("#custo_plus_form").find("button[type='submit']").prop('disabled',false);
        $("#process_wait").hide();
        if(data.errors){
            errors = data.errors;
            $.each(errors,function(i,v){
                let err = '';
                $.each(v,function(ei,ev){
                    if(err!=''){
                        err+='\n';
                    }
                    err+=ev;
                });
                $("#"+i).addClass('is-invalid');
                $("#"+i+"_error").html(err);
                toastr.error(err)
            });
        }else if(data.error){
            toastr.error(data.error);
        }else{
            let custo = data.custo;
            const data_stram = custo.num+"/"+custo.name+"-"+custo.contact;
            $("select#customer").append(`<option value="${custo.id}" data-source="${custo.contact}" data-target="#mobile" selected>${custo.name}</option>`);
            //$("select#customer").val(custo.id);
            $('select#customer.my_select').redraw();
            $(document).find('input#customer_input').val(custo.name);
            $("input#mobile").val(custo.contact);
            $("input#customer").val(custo.id);
            $("#bottom_block").show();
            success_sweettoatr("Customer succesfully Added !");
            $("#custo_modal").modal('hide');
            resetcustoform(true);
        }
    });

    $("#submitForm").submit(function(e){
        e.preventDefault();
		@if(auth()->user()->shop_id== 35 && auth()->user()->branch_id == 37)
			$(document).find('.form-control').removeClass('is-invalid');
			$.post($(this).attr('action'),$(this).serialize(),function(response){
				if(response.status){
					success_sweettoatr(response.msg);
					location.href = response.next;
				}else if(response.errors){
					$.each(response.errors,function(i,v){
						$(`[name="${i}"]`).addClass('is-invalid')
						toastr.error(v);
					});
				}else if(response.msg){
					toastr.error(response.msg);
					if(response.field){
						const fields = response.field.split('#');
						$(document).find(`.${fields[0]}`).eq(fields[1]).addClass('is-invalid');
					}
				}
			});
		@else 
			alert("Edit Only !");
			return false;
		@endif
    });
	//------OK CODE BELOW-------------------------------------//
	/*$("#submitForm").submit(function(e){
        e.preventDefault();
        $(document).find('.form-control').removeClass('is-invalid');
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.status){
                success_sweettoatr(response.msg);
                //location.href = response.next;
            }else if(response.errors){
                $.each(response.errors,function(i,v){
                    $(`[name="${i}"]`).addClass('is-invalid')
                    toastr.error(v);
                });
            }else if(response.msg){
                toastr.error(response.msg);
                if(response.field){
                    const fields = response.field.split('#');
                    $(document).find(`.${fields[0]}`).eq(fields[1]).addClass('is-invalid');
                }
            }
        });
    });*/
</script>
@include('vendors.billings.sale.billcalculation')
@include('vendors.billings.commonpages.js.payselectjs')
<script>
    /*function itemtotal(){

    }

    function billtotal(){

    }*/
</script>
@endsection


