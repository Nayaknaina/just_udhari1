@extends('layouts.vendors.app')
@section('content')
@include('layouts.theme.css.datatable')

	@php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Stock Inventory",$path) 
	@endphp 
	<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    <style>
        /* span.sctock_option{
            position: absolute;
            content:""
        }
        span.sctock_option.checked{

        } */
        #option_drop_down{
            display:none;
            margin:0;
            list-style:none;
            position:absolute;
            background:white;
            border:1px solid lightgray;
            border-radius:5px;
            box-shadow:1px 2px 3px lightgray;
            padding:2px 0px;
            z-index: 900;
        }
        #option_drop_down>li{
            padding:2px 5px;
        }
        
        tr.deleted_tr,tr.deleted_tr{
            border:1px solid red !important;
            background:unset!important;
            background:#ffc0cb3b !important;
            opacity: 0.3;
        }
        tr.deleted_tr > td{
            border-top:1px solid red !important;
            background: pink !important;
        }
		tr.item_sold >td{
            background-image: linear-gradient(#9cccff,white,#9cccff);
        }
		tr.item_sold > td{
			color:red!important;
		}
		.over-text-container{
            position: relative;
        }
        .over-text-container > .form-control{
            padding-right:20px;
        }
        /*.over-text-container > span.over-text{
            position:absolute;
            right:0;
            top:0;
            bottom:0;
        }*/
		tr.text-center > tr{
			text-align:center;
		}
		
		#item_image_viewer{
            position:absolute;
            top:0;
            left:0;
            bottom:0;
            right:0;
            background:#00000012;

            display: flex;           /* enables centering */
            justify-content: center; /* horizontal center */
            align-items: center;     /* vertical center */
            z-index: 9999;           /* ensu*/
        }
        #item_image_viewer_content{
            position:relative;
        }
        #item_image_viewer_close{
            position: absolute;
            right:0;
        }
        /*#item_image_viewer .row {
            background: #fff;        /* optional white box background */
            border-radius: 10px;    
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }*/
    </style>

    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="container-fluid">
                        <div class="row">
                            @php 
                                $selected_stock = "";
                                if(isset($stock_cat)){
                                    $selected_stock = str_replace(['-'," "],"_",strtolower($stock_cat));
                                    $$selected_stock = 'selected';
                                }
                            @endphp
                            <div class="form-inline col-md-10 col-12 p-0">
                                <div class="input-group mx-auto mb-1 w-auto">
                                    <label for="stock" class="input-group-text p-1">Stock</label>
                                    <select name="stock" id="stock" class="form-control w-auto" onchange="switchitemtype();changeEntries();">
                                        <option value="Gold" {{ @$gold }}>Gold</option>
                                        <option value="Silver" {{ @$silver }}>Silver</option>
                                        <option value="Stone" {{ @$stone }}>Stone/Gems</option>
										<option value="Artificial" {{ @$artificial }}>Artificial</option>
										<option value="Franchise Jewellery" {{ @$franchise_jewellery }}>Franchise Jewellery</option>
                                    </select>
									<select id="status" name="status" class="form-control w-auto text-success text-center border-success" onchange="changeEntries();" style="font-weight:bold;">
                                        <!--<option value="" class="text-info" data-target="info">All</option>-->
                                        <option value="avail" class="text-success" selected data-target="success">Avail</option>
                                        <option value="sold" class="text-danger" data-target="danger">Sold</option>
                                    </select>
                                </div>
                                @php 
                                $selected_stock_type = "";
                                    if(isset($sub)){
                                        $selected_stock_type = strtolower($sub);
                                        $$selected_stock_type = 'selected';
                                    }
                                @endphp
                                <div class="input-group mx-auto mb-1  w-auto">
                                    <label for="stock_type" class="input-group-text p-1">Type</label>
                                    <select name="stock_type" id="stock_type" class="form-control w-auto" onchange="changeEntries();">
                                        <option value="" >Both</option>
                                        <option value="Tag" {{ @$tag }}>Tag</option>
                                        <option value="Loose" {{ @$loose }}>loose</option>
                                    </select>
                                </div>
                                <div class="input-group mx-auto mb-1  w-auto">
                                    <select name="item_type" id="item_type" class="form-control w-auto" onchange="changeEntries();">
                                        @if($jewelleries->count() > 0)
                                            @foreach($jewelleries as $jk=>$jstock)
                                                @if($jstock->count() > 0)
                                                    <option value="" class="item_type_option {{ strtolower($jk) }}" style="display:{{ (strtolower($jk)==strtolower($stock_cat))?'block':'none' }};">Item Type </option>
                                                    @foreach($jstock as $jsk=>$jwlry)
                                                    <option value="{{ $jwlry->coll_name }}" class="item_type_option {{ strtolower($jk) }}" style="display:{{ (strtolower($jk)==strtolower($stock_cat))?'block':'none' }};">{{ $jwlry->coll_name }} </option>
                                                    @endforeach
                                                @else 
                                                    <option value="">No Data</option>
                                                @endif
                                            @endforeach
                                        @else 
                                            <option value="">No Data</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group mx-auto mb-1  w-auto">
                                    <select name="caret" id="caret" class="form-control w-auto text-center" onchange="changeEntries();">
                                        <option value="" >K?</option>
                                        <option value="18" >18K</option>
                                        <option value="20" >20K</option>
                                        <option value="22" >22K</option>
                                        <option value="24" >24K</option>
                                    </select>
                                </div>
								<div class="input-group mx-auto mb-1 w-auto">
                                    <label for="stock_type" class="input-group-text p-1">Weight</label>
                                    <input type="text" class="form-control px-1" name="sgm" id="sgm" value="" style="width:50px;border-radius:0 0 0 0!important;" placeholder="Gm" oninput="changeEntries();" >
									<div class="input-group-append">
                                    <input type="text" class="form-control px-1" name="egm" id="egm" value="" style="width:50px;border-radius:0 5px 5px 0!important;" placeholder="Gm" oninput="changeEntries();">
									</div>
                                </div>
                                <div class="form-group mx-auto mb-1  w-auto">
                                    <input type="text" class="form-control w-auto keyword" name="keyword" placeholder="Keyword/Item Name" id="keyword" oninput="changeEntries();">
                                </div>
                                
                                <div class="input-group mx-auto mb-1  w-auto" >
                                    <label for="entries" class="input-group-text p-1">Entry</label>
                                    <select name="entries" id="entries" class="form-control w-auto" onchange="changeEntries();">
                                        <option value="10" >10</option>
                                        <option value="50" selected >50</option>
                                        <option value="100" >100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-inline col-md-2 col-12 p-0">
                                <div class="input-group mx-auto mb-1">
                                    <a href="{{ route('stock.new.inventory.export',['pdf']) }}" class="btn btn-sm btn-outline-danger m-auto px-2 py-1 export">
                                        <i class="fa fa-upload"></i> Pdf
                                    </a>
                                    <a href="{{ route('stock.new.inventory.export',['excel']) }}" class="btn btn-sm btn-outline-success  m-auto px-2 py-1 export">
                                        <i class="fa fa-upload"></i> Excel
                                    </a>
									
									<a href="javascript:void(null);" class="btn btn-sm btn-dark"  onclick="$('#option_print').click();"> <i class="fa fa-print"></i> Tag</a>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-12 text-center p-0" id="stock_sum_block">
							</div>
							<!--<div class="col-12 text-center p-0">
								<ul id="stock_sum" class="p-0 mb-1">
									<li>
										<b>GROSS</b>
										 <span class="gm" id="stock_sum_gross">-</span>
									</li>
									<li>
										<b>NET</b>
										 <span class="gm" id="stock_sum_net">-</span>
									</li>
									<li>
										<b>FINE</b>
										 <span class="gm" id="stock_sum_fine">-</span>
									</li>
								</ul>
							</div>-->
						</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="CsTable" class="table_theme table table-bordered">
                                    <thead id="data_thead">
                                        <tr>
                                            <th>SN</th>
                                            <th>ITEM</th>
                                            <th>TAG</th>
                                            <th>HUID</th>
                                            <th>PIECE</th>
                                            <th>CARET</th>
                                            <th>GROSS</th>
                                            <th>LESS</th>
                                            <th>NET</th>  
                                            <th>TUNCH</th>
                                            <th>WSTG.</th>
                                            <th>FINE</th>
                                            <th>ST.CH.</th>
                                            <th>Lbr.</th>
                                            <th>Other</th>
                                            <th>Rate</th>
                                            <th>Disc.</th>
                                            <th>TOTAL</th>
                                            <th>
                                                <input type="checkbox" name="allcheck" value="yes" id="allcheck">
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
								
                                <div id="data_loader" class="text-center" style="display:none;">
                                    <span><i class="fa fa-spinner fa-spin"></i> Loading Content...</span>
                                </div>
                                <ul id="option_drop_down" style="">
                                    {{--<li><a href="{{ route('stock.new.edit') }}" class="text-info select_edit select_option" id="select_edit" data-redirect="true" data-mpin-check='true'><i class="fa fa-edit"></i> Edit</a></li>
                                    <li><a href="{{ route('stock.new.delete') }}" class="text-danger select_delete select_option" id="select_delete" data-redirect="false" data-mpin-check='true'><i class="fa fa-times"></i> Delete</a></li>
                                    <li ><a href="#" class="text-success"><i class="fa fa-print"></i> Tag</a></li>--}}
                                    <li>
                                        <form id="edit_form" action="{{ route('stock.new.edit') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="option_stock" value="{{ @$selected_stock }}" class="option_form">
                                            <button type="submit" name="operation" vaue="edit" class="btn btn-sm btn-outline-info form-control p-1 h-auto"><i class="fa fa-edit"></i> Edit</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form id="delete_form" action="{{ route('stock.new.delete') }}" method="post" class="option_form">
                                            @csrf
                                            <input type="hidden" name="option_stock"  value="{{ @$selected_stock }}">
                                            <button type="submit" name="operation" vaue="delete" class="btn btn-sm btn-outline-danger form-control p-1 h-auto"><i class="fa fa-times"></i> Delete</button>
                                        </form>
                                    <li>
									<li>
                                        <form id="tag_form" action="#" method="post" class="option_form">
                                            @csrf
                                            <input type="hidden" name="option_stock" value="{{ @$selected_stock }}">
                                            <button type="submit" name="operation" vaue="tagprint" class="btn btn-sm btn-outline-success form-control p-1 h-auto" id="option_print">
                                                <i class="fa fa-print"></i> Tag
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12" id="paging_area">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<div id="item_image_viewer" style="display:none;">
        <div class="col-12 col-md-4" id="item_image_viewer_content">
            <button type="button" class="btn btn-sm btn-danger p-0 px-1" id="item_image_viewer_close" onclick="$('#item_image_viewer').hide();">&cross;</button>
            <h6 class="item_image_title"></h6>
            <div class="w-100 text-center" >
                <img src="" class="img-thumbnail img-responsive" style="width:100%;height:inherit;" id="item_image_path">
            </div>
        </div>
    </div>
@endsection('content')
@section('javascript')
@include('layouts.theme.js.datatable')
    <script>
		$(document).on('click','.item_image',function(e){
            e.preventDefault();
            $("#item_image_title").text($(this).data(''));
            $("#item_image_path").attr('src','');
            const img_path = $(this).data('img')??false;
            if(img_path){
                $("#item_image_title").text($(this).data('title'));
                $("#item_image_path").attr('src',img_path);
                $("#item_image_viewer").show();
            }else{
                toastr.error("No Image Saved !");
            }
        });
		
		$(document).click(function(e){
			var target = $(e.target);
			if(target.attr('id')!="opt_action"){
				$("#option_drop_down").hide();
			}
		});
		$("select").change(function(){
			const class_name = $(this).find('option:selected').data('target');
		    $(this).removeClass('text-success text-danger text-info border-success border-danger border-info').addClass(`text-${class_name} border-${class_name}`);
		});
		
		function switchitemtype(){
            const stock = $("#stock").val().toLowerCase();
            $('select#item_type').val("");
            $('select#item_type').find('option.item_type_option').hide();
            $('select#item_type').find(`option.${stock}`).show();
        }
		$("#stock").change(function(){
			$("input[type='hidden'][name='option_stock']").val($(this).val());
		});
        $(document).ready(function(){
            $("#allcheck").prop('checked',false);
        })
		
        function launchaction(element) {
			if($(document).find('tr>td>.item_checked:checked').length > 0){
				const $button = $(element);
				const $menu   = $('#option_drop_down');
				const menuHeight = $menu.outerHeight();
				const menuWidth  = $menu.outerWidth();

				// position relative to #content
				const pos = $button.position();

				// Leave space for button width
				let c_left = pos.left - $button.outerWidth();
				let c_top  = pos.top + $button.outerHeight(); // below button

				// Adjust if dropdown goes outside right edge
				const $content = $('#content');
				if (c_left + menuWidth > $content.innerWidth() + $content.scrollLeft()) {
					c_left = pos.left + $button.outerWidth() - menuWidth;
				}

				// Adjust if dropdown goes outside bottom edge
				if (c_top + menuHeight > $content.innerHeight() + $content.scrollTop()) {
					c_top = pos.top - menuHeight;
				}
				const target = element.dataset.target; 
				$(target).css({
					top:  c_top + "px",
					left: c_left + "px",
				}).toggle();
			}else{
				toastr.error("Please Select The Items First!");
			}
            
        }

				

        url = url.split('?')[0];
        function getresult(url) {
			$("#allcheck").prop('checked',false);
			$("#stock_sum > li > span").text('-');
			$("#paging_area").empty();
            $('#CsTable').DataTable().destroy();
            $('#CsTable').find('tbody').remove();  // remove old tbody
			$('#CsTable').find('tfoot').remove();  // 
            $("#data_loader").show();
            $.ajax({
                url: url , // Updated route URL
                type: "GET",
                data: {
                    "entries": $("#entries").val(),
                    "stock": $("#stock").val(),
					"status":$('#status').val(),
                    "stock_type":$("#stock_type").val(),
                    "item_type":$("#item_type").val(),
                    "caret":$("#caret").val(),
					'start_wght':$("#sgm").val(),
					'end_wght':$("#egm").val(),
                    "keyword":$("#keyword").val(),
                },
                success: function (data) {
                    $("#data_loader").hide();
					$(document).find('.data_area').remove();
                    $(data.html).insertAfter('thead#data_thead');
                    $("#paging_area").html(data.paging);
					$("#stock_sum_block").empty().html(data.sum_block);
					$("#stock_sum_gross").text((data.stock_sum.sum_gross).toFixed(3)??'-');
					$("#stock_sum_net").text((data.stock_sum.sum_net).toFixed(3)??'-');
					$("#stock_sum_fine").text((data.stock_sum.sum_fine).toFixed(3)??'-');
                    $('#CsTable').DataTable();
                },
                error: function (data) {
                    $("#data_loader").hide();
                },
            });
        }

        getresult(url) ;

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var pageUrl = $(this).attr('href');
            getresult(pageUrl);

        });

        function changeEntries() {

            getresult(url) ;

        }
		
		
		$('.export').click(function(e){
            e.preventDefault();
			if($(document).find('tr>td>.item_checked:checked').length == 0){
				const page = $(document).find('.page-item.active > span').text()??false;
				var url_add_on = `entries=${$('#entries').val()}`;
				if(page){
					url_add_on += `&page=${page}`;
				}
				if($("#stock").val()){
					url_add_on +=`&stock=${$("#stock").val()}`;
				}
				if($('#status').val()){
					url_add_on+=`&status=${$('#status').val()}`
				}
				if($("#stock_type").val()){
					url_add_on +=`&stock_type=${$("#stock_type").val()}`;
				}
				if($("#item_type").val()){
					url_add_on +=`&item_type=${$("#item_type").val()}`;
				}
				if($("#caret").val()){
					url_add_on +=`&caret=${$("#caret").val()}`;
				}
				if($('#sgm').val()!="" && $('#egm').val()!=""){
					url_add_on +=`&start_wght=${$('#sgm').val()}&end_wght=${$('#egm').val()}`;
				}
				if($("#keyword").val()){
					url_add_on +=`&keyword=${$("#keyword").val()}`;
				}
				const href = $(this).attr('href')+`?${url_add_on}`;
				//alert(href);
				window.open(href, '_blank');
			}else{
				toastr.error("No Record to Export !");
			}
        });
		
        /*$('.export').click(function(e){
            e.preventDefault();
            const href = $(this).attr('href')+`?stock=${$("#stock").val()}&stock_type=${$("#stock_type").val()}`;
            window.open(href, '_blank');
        });*/

        $(document).on('change','.item_checked',function(e){
            const ele = $(this);
            const sn = ele.attr('id').replace('item_','');
            if($(this).is(':checked')){
                $('#edit_form,#delete_form,#tag_form').find(`input[type="checkbox"]#item_${sn}`).remove();
                $('#edit_form,#delete_form,#tag_form').append($(this).clone().css('display','none'));
                $(`#dropdown_${sn}`).addClass('d-none');
                $(this).removeClass('d-none');
            }else{
                const ele_id = $(this).attr('id');
                $('#edit_form,#delete_form,#tag_form').find("input#"+ele_id).remove();
                $(`#dropdown_${sn}`).removeClass('d-none');
                $(this).addClass('d-none');
            }
			if($(document).find('tr>td>.item_checked:checked').length > 0 ){
				$('.export').addClass('disabled');
			}else{
				$('.export').removeClass('disabled');
			}
        });

        $('#allcheck').change(function(){
			if($(this).is(':checked')){
               $(document).find('.item_checked').prop('checked',true);
            }else{
                $(document).find('.item_checked').prop('checked',false);
            }
			$('.export').toggleClass('disabled');
            $(document).find('.item_checked').trigger('change');
            /*if($(this).is(':checked')){
               $(document).find('.item_checked').prop('checked',true);
			   $(document).find('.item_checked').removeClass('d-none');
			   $(document).find('.dropdown').addClass('d-none');
            }else{
                $(document).find('.item_checked').prop('checked',false);
				$(document).find('.item_checked').addClass('d-none');
				$(document).find('.dropdown').removeClass('d-none');
            }*/
        });

        $(document).on('click','.stock_item_delete',function(e){
            e.preventDefault();
            const tr = $(this).closest('tr');
            $.get($(this).attr('href'),"",function(response){
                if(response.status){
                    tr.addClass('deleted_tr');
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(response.msg);
                }
            });
        });

        /*$("#delete_form").submit(function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(),function(response){
                if(response.status){
                    $.each(response.deleted,function(i,v){
                        $('#delete_form > input[type="checkbox"][name="item[]"]').remove();
                        $(`tr#tr_${v}`).removeClass('even odd').addClass('deleted_tr');
                        //$('document').find(`tbody >tr#tr_${v}`).addClass('deleted_tr');
                    });
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(response.msg);
                }
            });
        });*/
		
		$(document).on('submit','#delete_form',function(e){
            e.preventDefault();
            $.post($(this).attr('action'),$(this).serialize(),function(response){
                if(response.status){
                    $.each(response.done,function(i,v){
                        $('#delete_form > input[type="checkbox"][name="item[]"]').remove();
                        $(`tr#tr_${v}`).removeClass('even odd').addClass('deleted_tr');
                    });
					$.each(response.skip,function(i,v){
                        //$('#delete_form > input[type="checkbox"][name="item[]"]').remove();
                        //$(`tr#tr_${v}`).removeClass('even odd').addClass('deleted_tr');
                    });
                    success_sweettoatr(response.msg);
                }else{
                    toastr.error(response.msg);
                }
            });
        });
		
		$("#option_print").click(function(e){
            e.preventDefault();
            //alert($("#tag_form").find('input[type="checkbox"][name="item[]"]:checked').length);
            if($('#tag_form').find('input[type="checkbox"][name="item[]"]:checked').length > 0){
				printDirect();
            }else{
                toastr.error("Please select the Item First !");
            }
        });
		
        // $(document).find('.option_form').submit(function(e){
        //     if($('input [type="checked"][name="item[]"]:checked').length ==0){
        //         return false;
        //     }
        // });

        $(document).ready(function(){
             $(document).on('mpinVerified', function(e, response,triggerelement) {
                console.log(triggerelement);
                if(response.response){
                    const data =  output.response;
                    var msg_res = JSON.parse(data);
                    if(msg_res.return==true){
                        success_sweettoatr("Message Succesfully Resent !");
                        location.reload();
                    }else{
                        toastr.error("Message Resending Failed !");
                    }
                }else if(response.success){
                        success_sweettoatr(response.success);
                        triggerelement.closest('tr').addClass('tr-deleted');
                        //location.reload();
                    }else{
                        toastr.error(response.error)
                    }

             });
        });
    </script>
	
	<script src="https://cdn.jsdelivr.net/npm/qz-tray/qz-tray.js"></script>
    <script>
        $(function () {
            window.printDirect = function () {
                if (!qz.websocket.isActive()) {
                    qz.websocket.connect().then(doPrint).catch(() => alert("⚠️ QZ Tray not running"));
                } else {
                    doPrint();
                }
                function doPrint() {
                    qz.printers.find("ZDesigner ZD230-203dpi ZPL")
                        .then(printer => {
                            let config = qz.configs.create(printer);

                            let zplCommands = $("#tag_form").find('input[type="checkbox"][name="item[]"]:checked').map(function () {
                                return { type: 'raw', format: 'plain', data: $(this).data("print") };
                            }).get();
                            if (zplCommands.length === 0) {
                                alert("Please select at least one label to print.");
                                return;
                            }

                            return qz.print(config, zplCommands);
                        })
                        .then(() => alert("✅ Labels sent to printer!"))
                        .catch(err => alert("❌ Print failed: " + err));
                }
            };
        });
    </script>
    <script>
        /*$(document).ready(function(){
            printDirect();
        });*/
    </script>
    
@endsection