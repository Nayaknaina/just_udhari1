

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
    #item_list > li:hover{
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
</style>
<div class="row">
    <div class="col-12 p-0">
        <div class="table-responsive">
            <table class="table table-bordered table_theme">
                <thead >
                    <tr>
                        <!--<th>OP</th>-->
                        <th>ITEM</th>
                        <th>TAG</th>  
                        <th>REMARK</th>  
                        <th>PIECE</th>
                        <th>MRP/Pc.</th>
                        <th>OTHER</th>
                        <th>DISC.</th>
                        <!--<th width="50px">ON</th>-->
                        <th>IMAGE</th>
                        <th>RFID</th>  
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody class="billing item_tbody" id="item_data_area">
                    @for($i=0;$i<=5;$i++)
                        <tr class="item_tr" id="item_tr_{{ $i }}">
                            <td>
                                <input type="hidden" class="type item_input" name="type[]" id="type_{{ $i }}" value="">
                                <input type="text" class="form-control no-border item item_input " name="item[]" id="item_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border tag item_input w-100" name="tag[]" id="tag_{{ $i }}">
                            </td>
                            <td class="remark_td">
                                <input type="text" class="form-control no-border remark item_input w-100" name="remark[]" id="remark_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border piece item_input w-100" name="piece[]" id="piece_{{ $i }}" oninput="decimalonly(event,2)">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border rate item_input w-100" name="rate[]" id="rate_{{ $i }}" oninput="decimalonly(event,2)">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border other item_input w-100" name="other[]" id="other_{{ $i }}">
                            </td>
                            <td style="width:60px;">
                                <div class="input-group">
                                    <input type="text" class="form-control no-border disc item_input" name="disc[]" id="disc_{{ $i }}">
                                    <select class="form-control no-border discunit item_input px-1 text-center" name="discunit[]" id="discunit_{{ $i }}">
                                        <option value="">_?</option>
                                        <option value="r">Rs.</option>
                                        <option value="p" selected>%</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <label for="image_{{ $i }}" class="form-control mb-0 image_for" style="cursor:pointer;" id="image_for_{{ $i }}"> 
                                    Image
                                </label>
                                <input type="file" class="form-control no-border image item_input" name="image[]" id="image_{{ $i }}" style="display:none;" accept="image/*">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border rfid item_input" name="rfid[]" id="rfid_{{ $i }}">
                            </td>
                            <td>
                                <input type="text" class="form-control no-border ttl item_input w-100" name="ttl[]" id="ttl_{{ $i }}">
                            </td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input text-center" readonly value="" id="list_item">
                        </td>
                        <td colspan="2"></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input text-center" readonly value="" id="list_piece">
                        </td>
                        <td ></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input text-center" readonly value="" id="list_other">
                        </td>
                        <td colspan="3"></td>
                        <td>
                            <input type="text" class="form-control no-border no-hover item_input text-center" readonly value="" id="list_total">
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

    /*$(document).on('keydown.art', function(e) {
       if(e.shiftKey && e.key === 'ArrowDown'){
           var input = $(document).find(':focus');
            if(input.hasClass('item_input')){
                const tr_ind = $('tbody.item_tbody >tr').index(input.closest('tr'));
                $('tbody.item_tbody >tr').eq(tr_ind+1).find('.item').focus();
                //alert($('tbody.item_tbody >tr').length);
                var ttl_tr = $('tbody.item_tbody >tr').length;
                if(ttl_tr == tr_ind+1){
                    var tr_new = $('tbody.item_tbody >tr').eq(0).clone();
                    //tr_new.find('td').eq(0).text(ttl_tr+1);
                    $.each(tr_new.find('input'),function(ini,inv){
                        $(this).val("");
                        $(this).attr('id',$(this).attr('id').replace(/\d+$/, tr_ind+1));
                    });
                    $("tbody.item_tbody").append(tr_new).find('input.item').focus();
                }
            }
       }
    });*/

    $(document).on('focus.art','.item',function(){
        if($("#item_list").css('display')=='block'){
            $("#item_list").hide();
            $("#item_list").empty();
        }
    });

    //var input_index = false;
    $(document).on('input.art','.item',function(){
        let input = $(this);
        const tbody_id = input.closest('tbody').attr('id');
        const input_index = $('tbody#'+tbody_id+' >tr.item_tr').index(input.closest('tr.item_tr'))??false;
        
        if(input.val()!=""){
            $("#item_list").empty().append(`<li><span class="fa fa-spinner fa-spin"></span>Loading Content..</li>`);showitem($(this));
            $.get("{{ route('stock.find.item') }}?stock=artificial&entry="+$('#entry_type').val(),"keyword="+$(this).val(),function(response){
                let lis = '';
                if(response.items){
                    if(response.items.length > 0 ){
                        const tag = ($('#entry_type').val()=='tag')?true:false
                        $.each(response.items,function(ii,item){
                            const data_arr = {
                                'category':item.itemgroup.cat_name.toLowerCase(),
                                "labour_value":item.labour_value,
                                "huid":item.hsn_code,
                                "labour_unit":item.labour_unit,
                                "tax_value":item.tax_value,
                                "tag_prefix":item.tag_prefix,
                                /*'tag_max':item.inventorystock_max_tag??0,*/
								'tag_max':item.max_tag??0,
                                "tax_unit":item.tax_unit,
                                'karet':item.karet,
                                "tounch":item.tounch,
                                "wastage":item.wastage,
                                "rate":response.rate
                            };
							const li_class = (ii==0)?'hover':'';
                            const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
                            lis += `<li class="${li_class} stock_item"><a href="javascript:void(null);" data-title="${item.item_name}" data-target="${item.id}" data-desc="${data}"  data-parent="item_tr_${input_index}"class="get_item" data-tag="${tag}"><b>${ii+1}: </b>${item.item_name} !</li>`;
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
            //itemsummery();
			var item_count = 0;
			$(document).find('.item').each(function(i,v){
				if($(v).val()!=""){
					item_count++;
				}
				$("#list_item").val(item_count);
			});
            calculatestocksum();
        }
    });

    $(document).on('click.art','.get_item',function(){
        var title = $(this).data('title');
        var id = $(this).data('target');
        var data = $(this).data('desc');
        const tag = $(this).data('tag');
        var input_index = $(this).data('parent').replace('item_tr_','');
        $("#item_list").empty().hide();
        $("input.item").eq(input_index).val(title);
        $("input.type").eq(input_index).val(id);
        $("input.name").eq(input_index).focus();
        $('.item_tbody > tr').eq(input_index).addClass(data.category);
        if(data.labour_value){
            $('input.lbr').eq(input_index).val(data.labour_value);
            $('select.lbrunit').eq(input_index).val(data.labour_unit);
        }
        if(data.huid){
            $('input.huid').eq(input_index).val(data.huid);
        }
        if(data.tax_value){
            $('input.other').eq(input_index).val(data.tax_value);
        }
        if(data.tounch){
            $('input.tunch').eq(input_index).val(data.tounch);
        }
        if(data.wastage){
            $('input.wstg').eq(input_index).val(data.wastage);
        }
        if(data.karet){
            $('select.caret').eq(input_index).val(data.karet);
        }
        if(data.rate){
            $('input.rate').eq(input_index).val(data.rate);
        }
        if(tag==true){
            const prefix = data.tag_prefix??false;
            if(prefix){
                var max_tag =  Math.max(...$(document).find(`input[name='type[]'][value='${id}']`).map(function(index,el){ 
                    //return parseInt($(document).find('input[name="tag[]"]').eq(index).val().replace(/\D/g,''))||false;
                    return parseInt($(document).find('input[name="tag[]"]').eq(index).val().replace(prefix,''))||false;
                }).get());
                max_tag = (max_tag)?max_tag:data.tag_max;
                let nw_tag = parseInt(max_tag) + 1;
                let fn_tag = prefix+(nw_tag.toString().padStart(4,0));
                $(document).find('input.tag').eq(input_index).val(fn_tag);
            }else{
                toastr.error("Tag Properties Not Set For this Item !");
            }
			$(document).find('input.piece').eq(input_index).val(1).prop('readonly',true);
        }
        //itemsummery();
		var item_count = 0;
        $(document).find('.item').each(function(i,v){
            if($(v).val()!=""){
                item_count++;
            }
            $("#list_item").val(item_count);
        });
    });


</script>
@include('vendors.stocks.newpage.itemforms.js.artificialcalculate')
