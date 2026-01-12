@extends('layouts.vendors.app')
@section('content')
{{-- @php 
    dd($jewelleries);
@endphp --}}
@include('layouts.theme.css.datatable')
    @php 
		$anchor = ['<a href="'.route('stock.new.dashboard').'" class="btn btn-sm btn-outline-secondary py-1"><i class="fa fa-object-group"></i> Dashboard</a>','<a href="'.route('stock.new.inventory.import').'" class="btn btn-sm btn-outline-dark py-1"><i class="fa fa-download"></i> Import</a>','<a href="'.route('stock.new.create').'" class="btn btn-sm btn-outline-primary py-1"><i class="fa fa-plus"></i> Add</a>'];
		$path = ["Stocks"=>route('stock.new.dashboard')];
		$data = new_component_array('newbreadcrumb',"Group Inventory",$path) 
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
        tr.not_available  td,tr.not_available  td>a{
			color:red!important;
			font-weight:bold!important;
			text-shadow:1px 2px 3px #bcbcbc;
			font-style: italic;
		}
		
		tbody>tr>td >a:hover{
			text-decoration: underline!important;
		}
        .over-text-container{
            position: relative;
        }
        .over-text-container > .form-control{
            padding-right:20px;
        }
        /* .over-text-container > span.over-text{
            position:absolute;
            right:0;
            top:0;
            bottom:0;
        } */

        #CsTable {
            table-layout: fixed;
            width: 100%;
        }

        #option_drop_down {
            position: fixed;
            z-index: 9999;
        }

    </style>

    <section class = "content">
        <div class = "container-fluid p-0">
            <div class="card card-primary">
                <div class="card-body p-1">
                    <div class="container">
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
                                        <option value="Stone" {{ @$stone }}>Stone/Gem</option>
                                        <option value="Artificial" {{ @$artificial }}>Artificial</option>
										<option value="Franchise Jewellery" {{ @$franchise_jewellery }}>Franchise Jewellery</option>
                                    </select>
									<select name="status" id="status" class="form-control text-center w-auto text-success border-success" onchange="changeEntries();" style="font-weight:bold;">
                                        <option value="avail" class="text-sucess" data-target="success" selected>Avail</option>
                                        {{--<option value="unavail" class="text-warning" data-target="warning">Unavail</option>--}}
                                        <option value="sold"  class="text-danger" data-target="danger">Unavail</option>
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
                                                    <option value="{{ $jwlry->coll_name }}" class="item_type_option {{ strtolower($jk) }}" style="display:{{ (strtolower($jk)==strtolower($stock_cat))?'block':'none' }};" {{ ($sub && strtolower($sub)==strtolower($jwlry->coll_name))?'selected':'' }} >{{ $jwlry->coll_name }} </option>
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
										 @php 
                                            if($caret){
                                                $sel_caret = "select_{$caret}";
                                                $$sel_caret = 'selected';
                                            }
                                        @endphp
                                        <option value="" >Caret</option>
                                        <option value="18" {{ @$select_18 }} >18K</option>
                                        <option value="20" {{ @$select_20 }} >20K</option>
                                        <option value="22" {{ @$select_22 }} >22K</option>
                                        <option value="24" {{ @$select_24 }} >24K</option>
                                    </select>
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
                                    <a href="{{ route('stock.new.groupinventory.export',['pdf']) }}" class="btn btn-sm btn-outline-danger m-auto px-2 py-1 export">
                                        <i class="fa fa-upload"></i> Pdf
                                    </a>
                                    <a href="{{ route('stock.new.groupinventory.export',['excel']) }}" class="btn btn-sm btn-outline-success  m-auto px-2 py-1 export">
                                        <i class="fa fa-upload"></i> Excel
                                    </a>

                                    {{--<a href="javascript:void(null);" class="btn btn-sm btn-dark" onclick="$('#option_print').click();"> <i class="fa fa-print" ></i> Tag</a>--}}
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-12 text-center p-0"  id="stock_sum_block">
								
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
                                            <th>CARET</th>
                                            <th>PIECE</th>
                                            <th>GROSS</th>
                                            <th>NET</th>
                                            <th>FINE</th>
                                            <!--<th>Rate</th>-->
                                            <th>TOTAL</th>
                                            <th>
                                                &#8801;
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                                <ul id="option_drop_down" style="">
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
                                            <input type="hidden" name="option_stock" value="{{ @$selected_stock }}">
                                            <button type="submit" name="operation" vaue="delete" class="btn btn-sm btn-outline-danger form-control p-1 h-auto"><i class="fa fa-times"></i> Delete</button>
                                        </form>
                                    <li>
                                        <form id="tag_form" action="#" method="post" class="option_print">
                                            @csrf
                                            <input type="hidden" name="option_stock" value="{{ @$selected_stock }}">
                                            <button type="submit" name="operation" vaue="tagprint" class="btn btn-sm btn-outline-success form-control p-1 h-auto" id="option_print">
                                                <i class="fa fa-print"></i> Tag
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                                <div id="data_loader" class="text-center" style="display:none;">
                                    <span><i class="fa fa-spinner fa-spin"></i> Loading Content...</span>
                                </div>
                            </div>
                            <div class="col-md-12" id="paging_area">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection('content')
@section('javascript')
@include('layouts.theme.js.datatable')
 <script src="{{ asset('assets/custo_myselect_96/my_select_96.js') }}" type="text/javascript"></script>
<script>
    $('select.my_select').myselect96();
</script>                                    
    <script>
		$(document).click(function(e){
			var target = $(e.target);
			if(target.attr('id')!="opt_action"){
				$("#option_drop_down").hide();
			}
		});
		$("select").change(function(){
			const class_name = $(this).find('option:selected').data('target');
		    $(this).removeClass('text-success text-danger text-warning border-success border-danger border-warning').addClass(`text-${class_name} border-${class_name}`);
		});
        function switchitemtype(){
            const stock = $("#stock").val().toLowerCase();
            $('select#item_type').val("");
            $('select#item_type').find('option.item_type_option').hide();
            $('select#item_type').find(`option.item_type_option.${stock}`).show();
        }
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
			}                      // show/hide menu
        }

        url = url.split('?')[0];

        function getresult(url) {
			$("#allcheck").prop('checked',false);
			$("#paging_area").empty();
			$("#stock_sum > li > span").text('-');
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
                    "keyword":$("#keyword").val(),
                },
                success: function (data) {
                    $("#data_loader").hide();
					$('.data_area').remove();
                    $(data.html).insertAfter('thead#data_thead');
                    $("#paging_area").html(data.paging);
					$("#stock_sum_block").empty().html(data.sum_block);
					/*$("#stock_sum_gross").text((data.stock_sum.sum_gross).toFixed(3)??'-');
					$("#stock_sum_net").text((data.stock_sum.sum_net).toFixed(3)??'-');
					$("#stock_sum_fine").text((data.stock_sum.sum_fine).toFixed(3)??'-');*/
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
            const page = $(document).find('.page-item.active > span').text()??false;
            var url_add_on = `entries=${$('#entries').val()}`;
            if(page){
                url_add_on += `&page=${page}`;
            }
            if($("#stock").val()){
                url_add_on+=`&stock=${$("#stock").val()}`;
            }
			if($("#status").val()){
                url_add_on+=`&status=${$("#status").val()}`;
            }
            if($("#stock_type").val()){
                url_add_on+=`&stock_type=${$("#stock_type").val()}`;
            }
            if($("#item_type").val()){
                url_add_on+=`&item_type=${$("#item_type").val()}`;
            }
            if($("#caret").val()){
                url_add_on+=`&caret=${$("#caret").val()}`;
            }
            if($("#keyword").val()){
                url_add_on+=`&keyword=${$("#keyword").val()}`;
            }
            
            const href = $(this).attr('href')+`?${url_add_on}`;
            window.open(href, '_blank');
        });

        $(document).on('change','.item_checked',function(e){
            const ele = $(this);
            if($(this).is(':checked')){
                $('#edit_form,#delete_form,#tag_form').append($(this).clone().css('display','none'));
            }else{
                const ele_id = $(this).attr('id');
                $('#edit_form,#delete_form,#tag_form').find("input#"+ele_id).remove();
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

        $("#delete_form").submit(function(e){
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
        // $(document).find('.option_form').submit(function(e){
        //     if($('input [type="checked"][name="item[]"]:checked').length ==0){
        //         return false;
        //     }
        // });
        $("#option_print").click(function(e){
            e.preventDefault();
            //alert($("#tag_form").find('input[type="checkbox"][name="item[]"]:checked').length);
            if($('#tag_form').find('input[type="checkbox"][name="item[]"]:checked').length > 0){
				printDirect();
            }else{
                toastr.error("Please select the Item First !");
            }
        });
        
        /*$("#option_print").click(function(e){
            e.preventDefault();
            if($("input[type='checkbox']:checked[data-print]").length > 0){
                //alert($("input[type='checkbox']:checked").data('print'))
                printDirect();
            }else{
                toastr.error("Select the Itam First !");
            }
        });*/

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