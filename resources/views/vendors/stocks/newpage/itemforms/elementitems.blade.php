<table class="table table_theme table-bordered mb-0" id="element_data_table">
    <thead>
        <tr>
            <th>Element/Stone</th>
            <th>Caret</th>
            <th>Part</th>
            <th>Colour</th>
            <th>Piece</th>
            <th>Clarity</th>
            <th>Gross</th>
            <th>Less</th>
            <th>Net</th>
            <th>Tunch</th>
            <th>Wastage</th>
            <th>Fine</th>
            <th>Rate</th>
            <th>Cost</th>
        </tr>
    </thead>
    <tbody class="billing item_tbody" id="element_data">
        @for($j=0;$j<=4;$j++)
        <tr>
            <td>
                 <input type="text" class="form-control no-border ele_name item_input" name="ele_name[{{ $num }}][]" id="ele_name_{{ $j }}">
            </td>
            <td>
                <select class="form-control no-border ele_caret item_input" name="ele_caret[{{ $num }}][]" id="ele_caret_{{ $j }}">
                    <option value="">NA</option>
                    <option value="18">18K</option>
                    <option value="20">20K</option>
                    <option value="22">22K</option>
                    <option value="24">24K</option>
                </select>
                {{--<input type="text" class="form-control no-border ele_caret item_input" name="ele_caret[{{ $num }}][]" id="ele_caret_{{ $j }}">--}}
            </td>
            <td>
                <input type="text" class="form-control no-border ele_part item_input" name="ele_part[{{ $num }}][]" id="ele_part_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_color item_input" name="ele_color[{{ $num }}][]" id="ele_color_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_piece item_input" name="ele_piece[{{ $num }}][]" id="ele_piece_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_clear item_input" name="ele_clear[{{ $num }}][]" id="ele_clear_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_gross item_input" name="ele_gross[{{ $num }}][]" id="ele_gross_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_less item_input" name="ele_less[{{ $num }}][]" id="ele_less_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_net item_input" name="ele_net[{{ $num }}][]" id="ele_net_{{ $j }}" readonly>
            </td>
            <td>
                <input type="text" class="form-control no-border ele_tunch item_input" name="ele_tunch[{{ $num }}][]" id="ele_tunch_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_wstg item_input" name="ele_wstg[{{ $num }}][]" id="ele_wstg_{{ $j }}">
            </td>
            <td>
                <input type="text" class="form-control no-border ele_fine item_input" name="ele_fine[{{ $num }}][]" id="ele_fine_{{ $j }}" readonly>
            </td>
            <td>
                <input type="text" class="form-control no-border ele_rate item_input" name="ele_rate[{{ $num }}][]" id="ele_rate_{{ $j }}" >
            </td>
            <td>
                <input type="text" class="form-control no-border ele_cost item_input" name="ele_cost[{{ $num }}][]" id="ele_cost_{{ $j }}">
            </td>
        </tr>
        @endfor
    </tbody>
    <tfoot>
        <tr>
            <td>
                <input type="text" class="form-control no-border list_ele_name item_input text-center" name="list_ele_name{{ $num }}]" id="list_ele_name_{{ $num }}" readonly style="font-weight:bold;">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-dark" data-target="item_{{ $num }}" id="ele_ok_btn"> &check;&nbsp;Done</button>
            </td>
            <td colspan="2"></td>
            <td>
                <input type="text" class="form-control no-border list_ele_piece item_input" name="list_ele_piece[{{ $num }}]" id="list_ele_piece_{{ $num }}" readonly >
            </td>
            <td></td>
            <td>
                <input type="text" class="form-control no-border list_ele_gross item_input" name="list_ele_gross[{{ $num }}]" id="list_ele_gross_{{ $num }}" readonly>
            </td>
            <td></td>
            <td>
                <input type="text" class="form-control no-border list_ele_net item_input" name="list_ele_net[{{ $num }}]" id="list_ele_net_{{ $num }}" readonly>
            </td>
            <td colspan="2"></td>
            <td>
                <input type="text" class="form-control no-border list_ele_fine item_input" name="list_ele_fine[{{ $num }}]" id="list_ele_fine_{{ $num }}" readonly>
            </td>
            <td></td>
            <td>
                <input type="text" class="form-control no-border list_ele_cost item_input" name="list_ele_cost[{{ $num }}]" id="list_ele_cost_{{ $num }}" readonly>
            </td>
        </tr>
    </tfoot>
</table>

<script>
    $(document).find('.ele_name').on('input',function(){
        const index = $(this).closest('tr').index();
        const name = $(this).val()??false;
        if(!name){
            $('tbody#element_data > tr').eq(index).find('input,select').val('');
            calculateelemessum();
        }else{
            const pre_index = +index - 1;
            if(pre_index >= 0){
                const pre_cost = $(document).find('.ele_cost').eq(pre_index).val()??false;
                if(!pre_cost){
                    $(document).find('.ele_cost').eq(pre_index).focus();
                    $(this).val('');
                    toastr.error("Enter the Element Cost !");
                }
            }
        }
    });

    $(document).find('.ele_caret').on('input',function(){
        const index = $(this).closest('tr').index();
        //alert(index);

        const item_ele = $(document).find('.ele_name').eq(index).val()??false;
        //alert(item_ele);
        
        $(document).find('.ele_rate').eq(index).val('');
        $(document).find('.ele_cost').eq(index).val('');
        if(!item_ele){
            $(this).val('');
            $(document).find('.ele_name').eq(index).focus();
            toastr.error("Element name required !");
        }else{
            const caret = $(this).val()??false;
            if(caret){
                const tunch = Math.round((100/24)*caret);
                $(document).find('.ele_tunch').eq(index).val(tunch);
            }else{
                $(document).find('.ele_tunch').eq(index).val('');
            }
            calculateelementfine(index);
        }
    });

    $(document).on('input','.ele_part,.ele_color,.ele_piece,.ele_clear',function(){
        const index = $(this).closest('tr').index();
        const item_ele = $(document).find('.ele_name').eq(index).val()??false;
        if(!item_ele){
            $(this).val('');
            $(document).find('.ele_name').eq(index).focus();
            toastr.error("Element name required !");;
        }
    });

    $(document).on('input','.ele_gross',function(){
        const index = $(this).closest('tr').index();
        const item_ele = $(document).find('.ele_name').eq(index).val()??false;
        if(!item_ele){
            $(this).val('');
            $(document).find('.ele_name').eq(index).focus();
            toastr.error("Element name required !");;
        }else{
            const gross = $(this).val()??fasle;
            if(gross){
                $(document).find('.ele_net').eq(index).val(gross);
            }else{
                $(document).find('.ele_net').eq(index).val('');
            }
            calculateelementfine(index);
        }
    });

    $(document).on('input','.ele_less',function(){
        const index = $(this).closest('tr').index();
        const gross = $(document).find('.ele_gross').eq(index).val()??false;
        if(!gross){
            $(this).val('');
            $(document).find('.ele_gross').eq(index).focus();
            toastr.error("Gross Weight required !");
        }else{
            var  less = $(this).val()??fasle;
            if(less){
                if(+gross < +less){
                    less = less.slice(0,-1);
                    toastr.error("Less can't Greater to Gross !");
                }
                const net = +gross - +less;
                $(document).find('.ele_net').eq(index).val(+net);
            }else{
                $(document).find('.ele_net').eq(index).val(gross);
            }
            calculateelementfine(index);
        }
    });

    $(document).on('input','.ele_tunch',function(){
        const index = $(this).closest('tr').index();
        const net = $(document).find('.ele_net').eq(index).val()??false;
        if(!net){
            $(this).val('');
            $(document).find('.ele_net').eq(index).focus();
            toastr.error("Net Weight required !");
        }else{
            const tunch = $(this).val()??false;
            if(tunch){
                if(tunch>100){
                    $(this).val(tunch.slice(0,-1));
                }
            }
            $(document).find('.ele_caret').eq(index).val('');
        }
        calculateelementfine(index);
    });

    $(document).on('input','.ele_wstg',function(){
        const index = $(this).closest('tr').index();
        const tunch = $(document).find('.ele_tunch').eq(index).val()??false;
        if(!tunch){
            $(this).val('');
            $(document).find('.ele_tunch').eq(index).focus();
            toastr.error("Tunch required !");;
        }else{
            const net = $(document).find('.ele_net').eq(index).val()??false;
            var  wstg = $(this).val()??fasle;
            if(wstg){
                if(wstg > 100){
                    wstg = wstg.slide(0,-1);
                    toastr.error("Wastage can't Greater to 100 !");
                }
                calculateelementfine(index);
            }
        }
    });

    $(document).on('input','.ele_cost,.ele_rate',function(){
        const index = $(this).closest('tr').index();
        const ele_name = $(document).find('.ele_name').eq(index).val()??false;
        if(!ele_name){
            $(this).val('');
            toastr.error("Element Name required !");
            $(document).find('.ele_name').eq(index).focus();
        }
        if($(this).hasClass('ele_rate')){
            const rate = $(this).val()??false;
            if(rate){
                var fine = $(document).find('.ele_fine').eq(index).val()??false;
                if(fine){
                    const cost = (fine * rate).toFixed(2).toString().replace(/\.0+$/, '');
                    $(document).find('.ele_cost').eq(index).val(cost);
                }else{
                    $(this).val('');
                    toastr.error("Fine Weight Required !");
                }
            }else{
                $(document).find('.ele_cost').eq(index).val('');
            }
        }
        calculateelemessum();
    });

    function calculateelementfine(index){
        const net = $(document).find('.ele_net').eq(index).val()??false;
        const tunch = $(document).find('.ele_tunch').eq(index).val()??false;
        var wstg = $(document).find('.ele_wstg').eq(index).val()??false;
        if(net){
            if(wstg && tunch){
                if((+wstg + +tunch) > 100){
                    $(document).find('.ele_wstg').eq(index).val('');
                    toastr.error(`Invalid Wastage that is ${wstg}!`);
                    wstg = 0;
                }
                const diff = 100 - (+tunch + +wstg);
                const fine = ((net - (net * diff)/100).toFixed(3)).toString().replace(/\.0+$/, '');
                $(document).find('.ele_fine').eq(index).val(fine).trigger('input');;
            }else{
                $(document).find('.ele_fine').eq(index).val(net).trigger('input');;
            }
        }
        calculateelemessum();
    }

    function calculateelemessum(){
        var pcs = grs = net = fine = cost = count = 0;
        $(document).find('tbody#element_data > tr').each(function(i,v){
            if($(v).find('.ele_name').val()!=""){
                count++;
                pcs+= +$(v).find('.ele_piece').val()??0;
                grs+= +$(v).find('.ele_gross').val()??0;
                net+= +$(v).find('.ele_net').val()??0;
                fine+= +$(v).find('.ele_fine').val()??0;
                cost+= +$(v).find('.ele_cost').val()??0;
            }
        });
        $(".list_ele_piece").val(pcs);
        $(".list_ele_gross").val(grs);
        $(".list_ele_net").val(net);
        $(".list_ele_fine").val(fine);
        $(".list_ele_cost").val(cost);
        $('.list_ele_name').val(count);
    }

    $("#ele_ok_btn").click(function(){
		const main_head_count = $("#item_data_area").closest("table").find("thead th").length;
        const item_index = $(this).data('target').replace("item_",'');
        const item = $(document).find('.item').eq(item_index).val()??false;
        //alert(item);
        const net = $(document).find('.net').eq(item_index).val()??false;
        //alert(net);
        if(item && net){
            var filledInputs = $(document).find(".ele_name").filter(function() {
                if($(this).val().length){
                    return $(this).val().length;
                }else{
                    $(this).closest('tr').addClass('blank-element');
                    return 0;
                }
                //return $(this).val().length > 0;
            });
            /*var filledCost = $(document).find(".ele_cost").filter(function() {
                return $(this).val().length > 0;
            });*/
            if(filledInputs.length > 0){
                var elements = $("#element_data_table").clone(true);
                elements.attr('id','element_data_table_'+item_index);
                elements.find('tbody').attr('id','element_data_'+item_index);
                elements.removeClass('table_theme');
                elements.find('tfoot').remove();
                elements.find('tr.blank-element').remove();
                elements.find('tbody>tr').each(function(){
                    $(this).find('td').each(function() {
                        var field = false;
                        var text=false;
                        if($(this).find('input').length){
                            field = $(this).find('input');
                        }
                        if($(this).find('select').length){
                            field = $(this).find('select');
                            text = field.find('option:selected').text();
                        }
                        const name = field.attr('name')||'';
                        const value = field.val()||'';
                        text = (!text)?value:text;
                        //const field = $(this).find('input')??$(this).find('select');
                        $(this).addClass('text-center');
                        $(this).html(`<input type="hidden" name="${name}" value="${value}"><span >${text}</span>`);
                    });
                });
                
                if($(document).find('table#element_data_table_'+item_index).length){
                    $(document).find('table#element_data_table_'+item_index).append(elements.find('tbody>tr'));
                }else{
                    var tr = `<tr id="item_element_tr_${item_index}" class="item_element_tr" style="display:none;">
                    <td colspan="${main_head_count}" class="item_element_container p-0">
					<div class="w-100 element_table_block">
                        ${elements.prop('outerHTML')}
                        <a href="javascript:void(null);" onClick="removeelementlist($(this),${item_index});" class="btn btn-sm btn-outline-danger px-1 py-0"><i class="fa fa-times"></i></a>
                    </div>
					</td>
					</tr>`;
                    $(tr).insertAfter("#item_tr_"+item_index);
					$(document).find(`td button#element_show_${item_index}`).show();
                }
                var ttl_srvc_chrg = itemelementchargesum(item_index);
                $(document).find("input#chrg_"+item_index).val(ttl_srvc_chrg);
                // const ele_srvc = $("#list_ele_cost_"+item_index).val();
                // var pre_arvc = $('#list_chrg').val()??0;
                // $("#list_chrg").val(+pre_arvc + +ele_srvc);
                $(document).find("#chrg_"+item_index).trigger('input');
                closeassocelementform();
            }else{
                toastr.error("Add The Element First !");
            }
        }else{
           toastr.error("Not Item found to Assosiate the Element !");
        }
    });
</script>