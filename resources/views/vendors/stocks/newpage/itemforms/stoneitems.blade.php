
<div class="row">
    <div class="col-12 p-0">
        <div class="table-responsive">
            <table class="table table-bordered table_theme">
                <thead >
                    <tr>
                        <th>ITEM</th>
                        <th>TAG</th>  
                        <th>REMARK</th>
                        <th>COLOUR</th>
                        <th>CLEARITY</th>
                        <!--<th>KARET</th>-->
                        <th>PIECE</th>
                        <th>GROSS</th>
                        <th>LESS</th>
                        <th>NET</th>
                        <!-- <th>TUNCH</th>
                        <th>WASTAGE</th>
                        <th>FINE</th> -->
                        <!-- <th>ST. CH.</th> -->
                        <th>MRP/Pc.</th>
                        <th>LABOUR</th>
                        <!--<th width="50px">ON</th>-->
                        <th>OTHER</th>
                        <th>DISC.</th>
                        <!--<th width="50px">ON</th>-->
                        
                        <th>Crt No.</th>
                        <th>IMAGE</th>
                        <!--<th>RFID</th> --> 
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody class="billing item_tbody" id="item_data_area">
                    @for($i=0;$i<=5;$i++)
                        <tr class="item_tr">
                            <td>
                                <input type="hidden" class="type item_input" name="type[]" id="type_{{ $i }}" value="">
                                <input type="text" class="form-control no-border item item_input" name="item[]" id="item_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border tag item_input" name="tag[]" id="tag_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border remark item_input" name="remark[]" id="remark_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border color item_input" name="color[]" id="color_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border clear item_input" name="clear[]" id="clear_{{ $i }}">
                            </td>
                            <!--<td>
                                <select class="form-control no-border caret item_input px-1 text-center" name="caret[]" id="caret_{{ $i }}">
                                    <option value="">_?</option>
                                    <option value="18">18K</option>
                                    <option value="20">20K</option>
                                    <option value="22">22K</option>
                                    <option value="24">24K</option>
                                </select>
                            </td>-->
                            <td>
                                <input type="text" class="form-control no-border piece item_input" name="piece[]" id="piece_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border gross item_input" name="gross[]" id="gross_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border less item_input" name="less[]" id="less_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border net item_input" name="net[]" id="net_{{ $i }}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control no-border rate item_input" name="rate[]" id="rate_{{ $i }}">
                            </td>
                            <td>
                                <div class="input-group labour_chrg">
                                    <input type="text" class="form-control no-border lbr item_input" name="lbr[]" id="lbr_{{ $i }}">
									<div class="input-group-append">
										<select class="form-control no-border lbrunit item_input px-1 text-center" name="lbrunit[]" id="lbrunit_{{ $i }}">
											<option value="">_?</option>
											<option value="w">Gm</option>
											<option value="p">%</option>
										</select>
									</div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control no-border other item_input" name="other[]" id="other_{{ $i }}">
                            </td>
                            <td>
                                <div class="input-group discount_value">
                                    <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $i }}">
									<div class="input-group-append">
										<select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $i }}">
											<option value="">_?</option>
											<option value="r">Rs.</option>
											<option value="p" selected>%</option>
										</select>
									</div>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control no-border crt item_input" name="crt[]" id="crt_{{ $i }}">
                            </td>
                            <td>
								<label for="image_{{ $i }}" class="form-control mb-0 image_for" style="cursor:pointer;" id="image_for_{{ $i }}"> Image
                                </label>
                                <input type="file" class="form-control no-border image item_input" name="image[]" id="image_{{ $i }}" style="display:none;" accept="image/*">
                            </td>
                            {{--<td>
                                <input type="text" class="form-control no-border rfid item_input" name="rfid[]" id="rfid_{{ $i }}">
                            </td>--}}
                            <td>
                                <input type="text" class="form-control no-border ttl item_input" name="ttl[]" id="ttl_{{ $i }}">
                            </td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input text-center" readonly value="" id="list_item">
                        </td>
                        <td colspan="4"></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_piece">
                        </td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_gross">
                        </td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_net">
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_other">
                        </td>
                        <td colspan="3"></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input" readonly value="" id="list_total">
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 text-center">
        <input type="hidden" name="entry_num" value="{{ @$entry_num }}" id="entry_num">
        <button type="submit" name="stock" value="save" class="btn btn-sm btn-success">Save</button>
        <button type="submit" name="stock" value="print" class="btn btn-sm btn-outline-success">Save & Print</button>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('section.content').append(`<ul id="item_list"style="display:none;"></ul>`);
    });

    var input_index = false;
    $(document).on('input.stone','.item',function(){
        let input = $(this);
        input_index = $(document).find('input.item').index(input);
        if(input.val()!=""){
            $.get("{{ route('stock.find.item') }}?stock=stone&entry="+$('#entry_type').val(),"keyword="+$(this).val(),function(response){
                let lis = '';
                console.log(response.items);
                if(response.items){
                    if(response.items.length > 0 ){
                        const tag = ($('#entry_type').val()=='tag')?true:false
                        $.each(response.items,function(ii,item){
                            const data_arr = {
                                'category':item.itemgroup.cat_name.toLowerCase(),
                                "labour_value":item.labour_value,
                                "labour_unit":item.labour_unit,
                                "tax_value":item.tax_value,
                                "tax_unit":item.tax_unit,
                                "tag_prefix":item.tag_prefix,
                                /*'tag_max':item.inventorystock_max_tag??item.tag_prefix+0,*/
								/*'tag_max':item.inventorystock_max_tag??0,*/
								'tag_max':item.max_tag??0,
                                "tounch":item.tounch,
                                "wastage":item.wastage,
                            };
							const li_class = (ii==0)?'hover':'';
                            const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
                            lis += `<li class="${li_class} stock_item"><a href="javascript:void(null);" data-title="${item.item_name}" data-target="${item.id}" data-desc="${data}" data-parent="item_tr_${input_index}" class="get_item" data-tag="${tag}"><b>${ii+1}: </b>${item.item_name} !</li>`;
                        });
                    }else{
                        lis = `<li><a href="javascript:void(null);" >No Item !</li>`;
                    }
                }
                if(lis!=''){
                    $("#item_list").empty().append(lis);
                    $("#item_list").attr('data-parent','item_tr_'+input_index);
                    showitem(input);
                }else{
                    $("#item_list").data('data-parent','');
                    $("#item_list").empty().hide();
                }
            });
        }else{
            $('.item_tbody > tr').eq(input_index).removeClass().addClass('item_tr');
            $("input.type").eq(input_index).val("");
            $('.item_tbody > tr').eq(input_index).find('input,select:not(.op)').val("");
            $("#item_list").empty().hide();
			
			var item_count = 0;
			$(document).find('.item').each(function(i,v){
				if($(v).val()!=""){
					item_count++;
				}
				$("#list_item").val(item_count);
			});
			
            /*$('.item_tbody > tr').eq(input_index).removeClass();
            $("input.type").eq(input_index).val("");
            $('.item_tbody > tr').eq(input_index).find('input,select:not(.op)').val("");
            $(document).find('.ttl').eq(input_index).trigger('input');
            $(document).find('.rate').eq(input_index).trigger('input');*/
        }
    });


    $(document).on('click.stone','.get_item',function(){
        var title = $(this).data('title');
        var id = $(this).data('target');
        var data = $(this).data('desc');
        const tag = $(this).data('tag');
        var input_index = $(this).data('parent').replace('item_tr_','');
        //alert(data);
        $("#item_list").empty().hide();
        $("input.item").eq(input_index).val(title);
        $("input.type").eq(input_index).val(id);
        $("input.name").eq(input_index).focus();
        $('.item_tbody > tr').eq(input_index).addClass(data.category);
        if(data.labour_value){
            $('input.lbr').eq(input_index).val(data.labour_value);
            $('select.lbrunit').eq(input_index).val(data.labour_unit);
        }
        if(data.tax_value){
            $('input.chrg ').eq(input_index).val(data.tax_value);
        }
        if(data.tounch){
            $('input.pure').eq(input_index).val(data.tounch);
        }
        if(data.wastage){
            $('input.wstg').eq(input_index).val(data.wastage);
        }
        if(tag==true){
			const prefix = data.tag_prefix || false;
			if(prefix){
				var max_tag =  Math.max(...$(document).find(`input[name='type[]'][value='${id}']`).map(function(index,el){ 
					return parseInt($(document).find('input[name="tag[]"]').eq(index).val().replace(/\D/g,''))||false;
				}).get());
				/*max_tag = (max_tag)?data.tag_prefix + max_tag:data.tag_max;
				let nw_tag = parseInt(max_tag.replace(`${data.tag_prefix}`,'')) + 1;
				let fn_tag = data.tag_prefix+(nw_tag.toString().padStart(4,0));*/
				
				max_tag = (max_tag)?max_tag:data.tag_max;
				let nw_tag = parseInt(max_tag) + 1;
				let fn_tag = prefix+(nw_tag.toString().padStart(4,0));
				
				$(document).find('input.tag').eq(input_index).val(fn_tag);
			}else{
				toastr.error('Tag Properties Not Set For this Item !');
			}
			$(document).find('input.piece').eq(input_index).val(1).prop('readonly',true);
        }
		var item_count = 0;
        $(document).find('.item').each(function(i,v){
            if($(v).val()!=""){
                item_count++;
            }
            $("#list_item").val(item_count);
        });
    });

</script>
@include('vendors.stocks.newpage.itemforms.js.stonecalculate')