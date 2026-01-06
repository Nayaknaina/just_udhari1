  @extends('layouts.vendors.app')

  @section('content')

  
   @php 
	$anchor = ['<a href="'.route('billing.all',['sale']).'" class="btn btn-sm btn-outline-info py-1"><i class="fa fa-list"></i> All</a>','<a href="'.route('bill.settings').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-gear"></i> Setting</a>'];
	$data = new_component_array('newbreadcrumb',"Sell Bill") 
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
        ul#customer_list>li.hover{
            background:#d3d3d33d;
            font-weight:bold;
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

            <form id = "submitForm" method="POST" action="{{ route('billing',['sale'])}}" class = "myForm" enctype="multipart/form-data" autocomplete="off">

            @csrf

            @method('post')

            <div class="row mb-2 " >
                <div class="col-md-10 col-12">
                    <div class="row">
                        <div class="form-group col-md-4 mb-2 col-6">
                            <label for="suplier">Bill Type </label>
                            <div class="input-group" id="bill_type_select">
                                <label class="form-control h-auto"  style="white-space:nowrap;">
                                    <input type="radio" name="bill_type" class="bill_type" id="bill_type_est" value="e" data-target=""> Rough
                                </label>
                                <label class="form-control  h-auto" style="white-space:nowrap;">
                                    <input type="radio" name="bill_type" class="bill_type" id="bill_type_inv" value="g" checked  data-target="{{ @$perc; }}"> Gst
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-2 col-6">
                            <label for="custo_type">Party Type</label>
                            <select name="custo_type" id="custo_type"  class="form-control" placeholder="Find Party Name">
                                <option value="">Select</option>
                                <option value="s">Supplier</option>
                                <option value="c" selected>Customer</option>
                                <option value="w">Whole Seller</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <label for="customer">Party Name</label>
                            <div class="input-group" id="bill_type_select">
                                <select name="customer" id="customer"  class="form-control my_select" placeholder="Filnd Party Name">
                                    <option value="" data-target="#mobile" data-dource="">Find Name/Mobile</option>
                                    @foreach ($custos as $custo )
                                    <option value = "{{ $custo->id }}" data-source="{{  $custo->mobile }}" data-target="#mobile">{{ $custo->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-outline-primary m-0" data-target="#custo_modal" data-toggle="modal">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label for="mobile">Party Mobile</label>
                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Party Mobile" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-12 d-flex flex-wrap mt-2">
                    <div class="flot-label-input extra-sm col-4 col-md-12 p-0 mt-1">
                        <label for="">Bill No.</label>
                        <input type="text" class="form-control ju-form-control h-auto m-0 text-center" name="bill_no" id="bill_no" value="{{ @$bill_num }}">
                    </div>
                    <div class="flot-label-input extra-sm col-4 col-md-12 p-0 mt-1">
                        <label for="">Bill Date</label>
                        <input type="date" class="form-control ju-form-control h-auto m-0 text-center px-0" name="bill_date" id="bill_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="flot-label-input extra-sm col-4 col-md-12 p-0 mt-1">
                        <label for="">Due Date</label>
                        <input type="date" class="form-control ju-form-control h-auto m-0 text-center px-0" name="due_date" id="due_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
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
                    @include("vendors.billings.sale.saleform")
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
                        <div class="col-md-5 p-2 " id="option_pages" style="border:1px dashed #f95600;background: #fff9f6;box-shadow: -1px 2px 2px 2px lightgray;height:fit-content;">
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
                                <tbody id="all_pays" class="text-center">
                                    <tr id="no_payment">
                                        <td colspan="6" class="text-center text-danger" >No Payment Yet !</td>
                                    </tr>
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
                                    <input type="text" class="form-control no-hover no-border text-right" id="sub" name="sub" readonly>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th >DISCOUNT</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-hover no-border text-right" id="discount" name="discount">
                                    <select class="p-0 text-right" style="font-size: unset;" id="discount_unit" name="discount_unit">
                                        <option value="p">%</option>
                                        <option value="r">Rs</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>GST</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right" id="gst" name="gst" value="{{ @$perc }}">
                                    <span>&nbsp;%</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>TOTAL</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right text-warning" id="total" name="total" style="font-weight:bold;" readonly value="">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>ROUND</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right" id="round" name="round" value="" readonly>
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>FINAl</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-right text-info" id="final" name="final" style="font-weight:bold;" readonly value="">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th>PAYMENT</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-secondary text-right" id="payment" name="payment" readonly style="font-weight:bold;">
                                    <span>Rs</span>
                                </td>
                            </tr>
                            <tr class="no-hover">
                                <th >BALANCE</th>
                                <td class="input-group">
                                    <input type="text" class="form-control no-border no-hover text-danger text-right" id="balance" name="balance" readonly style="font-weight:bold;">
                                    <span>Rs</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
			
			<ul id="item_list" style="display:none;" data-parent=""></ul>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    
  </section>
    @include('vendors.commonpages.newcustomerwithcategory')
  @endsection

  @section('javascript')

 <script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
	
	var scaned =  false;
    $('select.my_select').myselect96();
    
	 $(".bill_type").change(function(){
       const val = ($(this).is(':checked'))?$(this).val():false;
       if(val){
            $("#gst").val($(this).data('target'));
       }else{
            $("#gst").val();
       }
    });
	
    $(document).on('input change','.form-control',function(){
        $(this).removeClass('is-invalid');
    });

    $(document).on('keydown', function(e) {
        //if((e.shiftKey && e.key === 'ArrowDown') || e.key === 'Tab'){
			//alert(e.key);
		
		var input = $(document).find(':focus');
		if(input.hasClass('my_select_input')){
            const class_name = input.attr('id').replace('_input','_list');
            const main_ul = $(document).find(`#${class_name}`)
                const visibility = main_ul.css('display')??'none';
                if(visibility=='block'){
                    var item_index = main_ul.find('li.hover').index();
                    main_ul.find('li').removeClass('hover');
                    const ttl_li = main_ul.find('li').length - 1;
                    if(e.key === 'ArrowUp'){
                        if(item_index==0){
                            item_index = ttl_li;
                        }else{
                            item_index-= 1;
                        }
                    }
                    if(e.key === 'ArrowDown'){
                        if(item_index==ttl_li){
                            item_index = 0;
                        }else{
                            item_index+= 1;
                        }
                    }
                    $(main_ul.find('li')).eq(item_index).addClass('hover');
                    if(e.key === 'Tab'){
                        main_ul.find('li.hover>a').trigger('click');
                    }
                }
        }else{
			if(e.key === 'Tab' && $(document).find('ul#item_list').css('display')=='block'){
				$('ul#item_list > li.hover > a').trigger('click');
				return false;
			}
			if(e.key === 'ArrowDown' || e.key === 'ArrowUp'){
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
							if(ttl_tr == tr_ind+1 && ttl_tr < 20){
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
			}else if(e.key === 'Enter' ){
				
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

    var count = 0;
	
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
                fetchRecords(input,true);
				$(document).on("shownList", "#item_list", function () {
					if($("#item_list > li").length == 1){
						$("#item_list").find('li:first > a').click();
						var e = jQuery.Event("keydown");
						e.which = 40;           // Arrow Down
						e.key = "ArrowDown";    // optional, modern browsers
						$(document).trigger(e);
					}
				});
            }, 100);
        } else {
            // normal typing case → debounce (e.g. 300ms after last key)
            typingTimer = setTimeout(function () {
                fetchRecords(input,false);
            }, 200);
        }
    });

    function fetchRecords(input,scane) {   
        const tbody_id = input.closest('tbody').attr('id');
        const input_index = $('tbody#'+tbody_id+' >tr.item_tr').index(input.closest('tr.item_tr'))??false;
		const url_addon = (scane)?'&scane=yes':'';
		var input_val = input.val().trim();
		input_val = input_val??false;
		if(input_val){
			$.get("{{ route('billing.find.stock') }}", "keyword="+input_val+url_addon, function (response) {
				let lis = '';
				if(response.stocks){
					if(response.stocks.length > 0 ){  
						var rec_count = 0;
						const stock_label_arr = {gold:'GL',silver:'SL',stone:'ST',artificial:'AR',franchise_jewellery:'FJ'};

						$.each(response.stocks,function(si,stock){
							if($(document).find(`input[name='id[]'][value='${stock.id}']`).length==0){
								const item_stock_type_label = (stock.stock_type).toLowerCase().replace("-","_");
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
								let prop_ul = `<ul class="d-flex flex-wrap p-0 item_prop text-center" style="list-style:none;">`;
								if(stock.tag){
                                    prop_ul+=`<li class="m-auto px-1"><b><u>${data_arr['tag']}</b></u></li>`;
                                }
								 if(item_stock_type_label=='artificial'){
                                    prop_ul+=`<li class="m-auto px-1"><b>Count-</b>${data_arr['count']}</li>`;
                                }else{
                                    if(item_stock_type_label=='franchise_jewellery'){
                                       prop_ul+=`<li>|</li><li class="m-auto px-1"><b>Count-</b>${data_arr['count']}</li>`; 
                                    }
									prop_ul+=`<li>|</li>
											<li class="m-auto px-1"><b>G-</b>${data_arr['gross']}gm</li>
											<li>|</li>
											<li class="m-auto px-1"><b>N-</b>${data_arr['net']}gm</li>
											<li>|</li>`;
											if(item_stock_type_label=='gold'){
												prop_ul+=`<li class="m-auto px-1"><b>K-</b>${data_arr['caret']}K</li>`;
											}
								}
								
								prop_ul+=`</ul>`;
								const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
								const class_name = (rec_count==0)?'hover':'';
								lis += `<li class="${class_name} bill_item"><a href="javascript:void(null);" data-title="${stock.name}" data-target="${stock.id}" data-desc="${data}"  data-parent="item_tr_${input_index}"class="get_item"><b class="${item_stock_type_label}_mark stock_mark">${stock_label_arr[item_stock_type_label]}</b> : ${stock.name} !<br>${prop_ul}</li>`;
								rec_count++;
							}else{
								//toastr.error('Already in Bill !');
								//$('.item').eq(input_index).select();
							}
						});
					}else{
						lis = `<li ><a href="javascript:void(null);" data-parent="item_tr_${input_index}" data-val="false" data-feed="blank" class="get_item">No Item !</li>`;
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
			$("tbody#sale_form > tr").eq(input_index).find('input,select').removeClass('blocked');
            itemsum(input_index);
            /*$('.item_tbody > tr').eq(input_index).removeClass().addClass('item_tr');
            $("input.type").eq(input_index).val("");
            $('.item_tbody > tr').eq(input_index).find('input,select:not(.op)').val("");
            $("#item_list").hide().empty();
            itemsummery();*/
            // $(document).find('.ttl').eq(input_index).trigger('input');
            // $(document).find('.rate').eq(input_index).trigger('input');
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
		//alert($(this).data('feed'));
		if($(this).data('feed') && $(this).data('feed')=='blank'){
			//alert("Here");
			const item_index = $(this).data('parent').replace('item_tr_','');
			$(document).find('input.item').eq(item_index).select();
			//scaned = false;
		}else{
			var title = $(this).data('title');
			var id = $(this).data('target');
			var data = $(this).data('desc');
			$("#item_list").empty().hide();
			title += (data.tag)?' ('+data.tag+')':'';
			$("input.item").eq(input_index).val(title);
			$("input.id").eq(input_index).val(id);
			$("input.name").eq(input_index).focus();
			
			var block_item =  $('select.caret').eq(input_index).add($('input.gross').eq(input_index)).add($('input.less').eq(input_index)).add($('input.net').eq(input_index)).add($('input.tunch').eq(input_index)).add($('input.wstg').eq(input_index)).add($('input.fine').eq(input_index));

            if(data.stock=='Franchise-Jewellery' || data.stock=='Artificial' ){
                block_item.addClass('blocked');
            }
			if(data.tag){
			   block_item.add($('input.piece').eq(input_index)).addClass('blocked');
			}else{
				block_item.add($('input.piece').eq(input_index)).removeClass('blocked');
			}
			$(document).find('select.caret').eq(input_index).addClass('blocked');
			if(data.caret){
				$('select.caret').eq(input_index).val(data.caret);
			}
			
			if(data.count){
				$('input.piece').eq(input_index).val(data.count);
                if(data.count==1){
                    block_item.add($('input.piece').eq(input_index)).addClass('blocked');
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
			itemsum(input_index);
			//$("input.item").eq(+input_index + 1).focus();
			var e = jQuery.Event("keydown");
			e.which = 40;           // Arrow Down
			e.key = "ArrowDown";    // optional, modern browsers
			$(document).trigger(e);
		}
    });

	$('select[readonly]').on('focus mousedown', function(e) {
		e.preventDefault();
		this.blur(); // Prevent dropdown from opening
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
                        option+= `<option value="${custo.id}" data-source="${custo.mobile}" data-target="#mobile">${custo.name}</option>`;
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
        if(!custo){
            toastr.error("Custome Not Entered/Selected !");
            return false;
        }
        const bill_final = $("#final").val()??false;
        if(!bill_final){
            toastr.error("No Payment Needed !");
            return false;
        }
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
			$(document).find('input#customer').val(custo.id);
            $("input#mobile").val(custo.contact);
            $("#bottom_block").show();
            success_sweettoatr("Customer succesfully Added !");
            $("#custo_modal").modal('hide');
            resetcustoform(true);
        }
    });

    $("#submitForm").submit(function(e){
        e.preventDefault();
        $(document).find('.form-control').removeClass('is-invalid');
        $.post($(this).attr('action'),$(this).serialize(),function(response){
            if(response.status){
                success_sweettoatr(response.msg);
                location.href = response.next;
            }else if(response.error){
				toastr.error(response.error);
				if(response.field){
                    var field = response.field.split("#");
                    $(document).find(`.${field[0]}`).eq(field[1]).addClass('is-invalid');
                }
            }else if(response.errors){
                $.each(response.errors,function(i,v){
                    $(`[name="${i}"]`).addClass('is-invalid')
                    toastr.error(v);
                });
            }
        });
    });
</script>
@include('vendors.billings.sale.billcalculation')
@include('vendors.billings.commonpages.js.payselectjs')
@endsection

