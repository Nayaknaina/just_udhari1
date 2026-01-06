<script>
    $(document).ready(function(){
        $(document).on('click','.stock_block_add',function(e){
            e.preventDefault();
            var ind = $($(document).find('.stock_block_add')).length;
            //var ind = $('.stock_block_add').index($(this));
            var sel = $("#stocktype").val();
            $.get("{{ route("stocks.forms") }}","block="+(ind)+"&form="+sel,function(response){
                $("#entry_stock_area").append($(response));
                $("#form_area").append($(response));
                (ind >1)?$(document).find('a.block_del_btn').eq(0).remove():null;
            });
        });

       /* $(document).on('click','.stock_block_add',function(e){
            e.preventDefault();
            var block = $("#main_bill_block").clone();
            block.removeAttr("id");
            //block.find('div > div.form-group> span.select2').remove();
            block.find('div > div.form-group >.form-control').removeAttr('id');
            block.find('div > div.form-group >.form-control').val('');
            var all_tr =  block.find('table > tbody >tr');
           //alert($(".stock_block").length);
            all_tr.each(function(i,v){
                if(i!=0){
                    v.remove();
                }else{
                    $(v).find('td').each(function(ind,val){
                        if(edit && ind==0){
                            var field = $(val).find('.stock_ids');
                            field.val('');
                            if(edit){
                                $(val).find('.tr_del_btn').replaceWith('<button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>');;
                            }
                        }
                        if(ind!=0){
                            var field = $(val).find('.tb_input');
                            if(ind!=2){
                                field.val('');
                            }
                            var name = field.attr('name');
                            var newName = name.replace(/\[(\d+)\]/, '['+($(".stock_block").length)+']');
                            field.attr('name',newName);
                        }else{
                            $(val).find('.tr_del_btn').css('display','none');
                        }
                    });
                }
            });
            block.append('<a href="javascript:void(null);" class="custom_remove_btn block_del_btn"><li class="fa fa-times"></li></a>');
            block.insertAfter(".main_bill_block:last");
            block_serialize();
            block.find('div > div.form-group > span.select2').remove();
            block.find('div > div.form-group > select.select2').select2();
        });*/
        
        $(document).on('click','.more_item_tr',function(e){
            e.preventDefault();
            var table = $(this).prev('table');
            var tbody = table.find('tbody');
            var item_tr = tbody.find('tr:first').clone();
            item_tr.removeAttr('id');
            if(edit){
                item_tr.find('td>.stock_ids').val('');
                item_tr.find('td>a.tr_del_btn').replaceWith('<button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>');
            }
            tbody.append(item_tr);
            tbody.find("tr:last > td > .td_input:first").focus();
            tbody.find("tr:last").find('td').eq(1).find('input').select();
            //$('.tr_del_btn').show();
            itemtr_serialize(tbody);
            //calculateTotals();
            //calculate();
        });
        
        $(document).on('click','.assoc_plus',function(e){
            e.preventDefault();
            var block_ind = $("div.stock_block").index($(this).closest('div.stock_block'));
            var tr = $(this).closest('tr');
            var input_ind = $(this).closest("tbody").find("tr.main_item_tr").index(tr);
			var type = $("#stocktype").val();
            //var input_ind = $("tr.main_item_tr").index(tr);
            $.get($(this).attr('href'),"block="+block_ind+"&input="+input_ind+"&type="+type,function(response){
                var html = response.html;
                $(html).insertAfter(tr);
            });
        });

        $(document).on('click','.assoc_del_btn',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
            //calculate();
        })

        $(document).on('click','.block_del_btn',function(e){
            e.preventDefault();
           var curr_ind = $($(document).find('div.stock_block')).index($(this).closest('div.stock_block'));
            $(this).parent('div').remove();
            if(curr_ind!=1){
                const btn = '<a href="javascript:void(null);" class="custom_remove_btn block_del_btn"><li class="fa fa-times"></li></a>';
                $(document).find('div.stock_block').eq(curr_ind-1).append(btn);
            }
            block_serialize();
            //calculate();
        });

        $(document).on('click','.tr_del_btn',function(e){
            e.preventDefault();
            var tbody_ind = $($(document).find('tbody.item_tbody')).index($(this).closest('tbody.item_tbody'));
            var tbody = $(document).find('tbody.item_tbody').eq(tbody_ind);
            tr = $(this).closest('tr');
            var tr_ind = $(tbody.find('tr.main_item_tr')).index(tr);
            if(delete_stock_record(e)){
                $(document).find('tr.element_tr_'+tr_ind).remove();
                tr.remove();
                itemtr_serialize(tbody);
                //calculate();
            }
        });
        
        function block_serialize(){
            $('.block_sn').each(function(i,v){
                $(this).text(i+1);
            });
        }
        
        function itemtr_serialize(tbody){
            var count =$(tbody.find("tr")).length;
            tbody.find("tr").each(function(i,v){
                var del_btn = $(this).find('td.sn-box > .tr_del_btn');
                $(this).find('td.sn-box > .sn-number').text(i+1);
                if(count==1){
                    del_btn.css('display','none');
                }else{
                    del_btn.css('display','unset');
                }
            });
            if(edit){
                var table_count = $(document).find('table').length;
                var count = $(document).find('tr').length-table_count;
                var del_btn = $(document).find('table > tbody > tr > td.sn-box > .tr_del_btn');
                if(count==1){
                    del_btn.css('display','none');
                }else{
                    del_btn.css('display','unset');
                }
            }
        }


		$(document).on('input','.rate',function(e){
            var rate_index = $('.rate').index($(this));
            var trs = $('tbody').eq(rate_index).find('tr');
            var rate = $(this).val();
            $($('tbody').eq(rate_index).find('tr')).each(function(tri,trv){
                $(trv).find('.product,.net_weight,.caret,.purity,.labour').removeClass('is-invalid');
                const prdct = $(trv).find('.product');
                const prdct_val = prdct.val()??false;

                const nt_wt = $(trv).find('.net_weight');
                const nt_wt_val = nt_wt.val()??false;

                const caret = $(trv).find('.caret');
                const caret_val = caret.val()??false;

                const purity = $(trv).find('.purity');
                const purity_val = purity.val()??false;

                // const index = $('tbody>tr').index($(this));
                // wastagetolabour(index);
                // labourtowastage(index);

                const wstg = $(trv).find('.waste');
                var wstg_val = wstg.val()??false;
                const lbr = $(trv).find('.labour');
                var lbr_val = lbr.val()??false; 
                if(wstg_val!=""){
                    const wst_wt = (nt_wt_val*wstg_val)/100;
                    const gm_caret_rate = (rate/24)*caret_val;
                    lbr_val = (wst_wt*gm_caret_rate)/nt_wt_val;
                    lbr.val(lbr_val);
                }
                if(lbr_val!=""){
                    const gm_caret_rate = (rate/24)*caret_val;
                    const wstg_wt = (lbr_val*nt_wt_val)/gm_caret_rate;
                    const lbr_perc = (wstg_wt*100)/nt_wt_val;
                    wstg_val =  (wstg_wt*100)/nt_wt_val;
                    wstg.val(wstg_val);
                }
                if(nt_wt_val && caret_val && purity_val && wstg_val && lbr_val){
                    const caret_rate = (rate/24)*caret_val;
                    const total = (+nt_wt_val + +lbr_val)*caret_rate;
                    $(trv).find('.amount').val(Number(total.toFixed(2)));
                }else{
                    if(prdct && prdct_val!=""){
                        if(!nt_wt_val){
                            nt_wt.addClass('is-invalid');
                        } 
                        if(!caret_val){
                            caret.addClass('is-invalid');
                        }
                        if(!purity_val){
                            purity.addClass('is-invalid');
                        }
                        if(!wstg_val){
                            wstg.addClass('is-invalid');
                        }
                        if(!lbr_val){
                            lbr.addClass('is-invalid');
                        }
                    }
                }
            });
        });

        /*$(document).on('input','.calculate_item',function(e){
            //-------Genuine Product-----------//
            $(this).removeClass('is-invalid');
            if(!$(this).hasClass('artifical')){
                var block = $(this).closest('.stock_block');
                var block_index = block.index('.stock_block');
                var rate_value = $('.rate').eq(block_index).val()??0;
                if(!$(this).hasClass('rate') && (rate_value==0 || rate_value=="")){
                    $(this).val('');
                    toastr.error("Enter The Rate First!");
                    $('.rate').eq(block_index).select();
                }else{
                    var tr_index = $('tbody>tr').index($(this).closest('tr'));
                    var go_ahead = true;
                    if(!$(this).hasClass('product')){
                        this.value = this.value.replace(/[^0-9.]/g, ''); 
                    }
                    if($(this).hasClass('gross_weight')){
                        const index = $('.gross_weight').index($(this));
                        go_ahead = grosscheck(index);
                    }
                    if($(this).hasClass('net_weight')){
                        const index = $('.net_weight').index($(this));
                        go_ahead = netcheck(index);
                    }
                    if($(this).hasClass('caret')){
                        const index = $('.caret').index($(this));
                        go_ahead = carettopurity(index);
                    }
                    if($(this).hasClass('purity')){
                        const index = $('.purity').index($(this));
                        go_ahead = puritytocaret(index);
                    }
                    if($(this).hasClass('waste')){
                        const index = $('.waste').index($(this));
                        go_ahead = wastagetolabour(index);
                    }
                    if($(this).hasClass('labour')){
                        const index = $('.labour').index($(this));
                        go_ahead = labourtowastage(index);
                    }
                    if(go_ahead){
                        const caret = $('.caret').eq(tr_index).val();
                        const caret_rate = (rate_value/24)*caret;
                        const nt_wt = $('.net_weight').eq(tr_index).val();
                        const cost = nt_wt*caret_rate;
                        const lbr = $('.labour').eq(tr_index).val();
                        const ttl_lbr = nt_wt*lbr;
                        const final = +cost  + +ttl_lbr;
                        $('.amount').eq(tr_index).val(final);
                    }
                }
            }else{
                var art_tr = $(this).parent('td').parent('tr');
                var art_product = art_tr.find('td > input.artificial_product');
                if(art_product.val()==""){
                    $(this).val('');
                    art_product.select();
                    toastr.error("Enter The Product Name First!");
                }else{
                    var art_quant = art_tr.find('td > input.quantity');
                    if(art_quant.val()==""){
                        $(this).val('');
                        art_quant.select();
                        toastr.error("Enter The Product Quantity!");
                    }
                }
            }
        });*/

		$(document).on('input','.calculate_item',function(e){
            //-------Genuine Product-----------//
            $(this).removeClass('is-invalid');
            if(!$(this).hasClass('artifical')){
                var block = $(this).closest('.stock_block');
                var block_index = block.index('.stock_block');
                var rate_value = $('.rate').eq(block_index).val()??0;
                if(!$(this).hasClass('rate') && (rate_value==0 || rate_value=="")){
                    $(this).val('');
                    toastr.error("Enter The Rate First!");
                    $('.rate').eq(block_index).select();
                }else{
                    //var tr_index = $(this).closest('tr').index();
                    var tr_index = $('tbody>tr').index($(this).closest('tr'));
                    var go_ahead = true;
                    if(!$(this).hasClass('product')){
                        //this.value = this.value.replace(/[^0-9.]/g, ''); 
						this.value = this.value.replace(/(?!^)-|[^0-9.]|(\.\d{3,})/g, '$1');
                        this.value = this.value.replace(/(\.\d{2})\d+/g, '$1');
                    }
                    if($(this).hasClass('gross_weight')){
                        const index = $('.gross_weight').index($(this));
                        go_ahead = grosscheck(index);
                    }
                    if($(this).hasClass('net_weight')){
                        const index = $('.net_weight').index($(this));
                        go_ahead = netcheck(index);
                    }
                    if($(this).hasClass('caret')){
                        const index = $('.caret').index($(this));
                        go_ahead = carettopurity(index);
                    }
                    if($(this).hasClass('purity')){
                        const index = $('.purity').index($(this));
                        go_ahead = puritytocaret(index);
                    }
                    if($(this).hasClass('waste')){
                        const index = $('.waste').index($(this));
                        go_ahead = wastagetolabour(index);
                    }
                    if($(this).hasClass('labour')){
                        const index = $('.labour').index($(this));
                        go_ahead = labourtowastage(index);
                    }
                    if(go_ahead){
                        const caret = $('.caret').eq(tr_index).val();
                        const caret_rate = (rate_value/24)*caret;
                        const nt_wt = $('.net_weight').eq(tr_index).val();
                        const cost = nt_wt*caret_rate;
                        const lbr = $('.labour').eq(tr_index).val();
                        const ttl_lbr = nt_wt*lbr;
                        const final = +cost  + +ttl_lbr;
                        $('.amount').eq(tr_index).val(Number(final.toFixed(2)));
                    }
                }
            }else{
                var art_tr = $(this).parent('td').parent('tr');
                var art_product = art_tr.find('td > input.artificial_product');
                if(art_product.val()==""){
                    $(this).val('');
                    art_product.select();
                    toastr.error("Enter The Product Name First!");
                }else{
                    var art_quant = art_tr.find('td > input.quantity');
                    if(art_quant.val()==""){
                        $(this).val('');
                        art_quant.select();
                        toastr.error("Enter The Product Quantity!");
                    }
                }
            }
        });

        function grosscheck(index){
            var back = true;
            if($('.product').eq(index).val() == ""){
                back = false;
                toastr.error("Please Enter the <b>Product Name !</b> !");
                $('.gross_weight').eq(index).val("")
                $('.product').eq(index).focus();
            }
            return back;
        }

        function netcheck(index){
            var back = true;
            const ntwt = $('.net_weight').eq(index).val();
            const grwt = $('.gross_weight').eq(index).val()??0;
            if(grwt == 0){
                toastr.error("Please Enter the <b>Gross Weight First</b> !");
                $('.gross_weight').eq(index).focus(); 
                back = false;
            }else{
                if(+ntwt > +grwt){
                    toastr.error("Net Weight <b>Can't Be</b> greater than Gross Weight First !");
                    back = false;
                }else{
                    const purity = $('.purity').eq(index).val()??0;
                    if(purity!=0 && ntwt!=0){
                        const fine_wt = (ntwt*purity)/100;
                        $(".fine_weight").eq(index).val(fine_wt);
                    }
                }
            }
            if(!back){ 
                $('.net_weight').eq(index).val(""); 
            }
            return back;
        } 

        function carettopurity(index){
            var caret = $('.caret').eq(index).val()??0;
            var purity = 0;
            var back = true;
            if(caret!="" && caret!=0){
                var one = 100/24;
                var purity = Number((caret*one).toFixed(2));
				$(".purity").eq(index).removeClass('is-invalid');
                $(".purity").eq(index).val(purity);
                const ntwt = $('.net_weight').eq(index).val()??0;
                if(ntwt!=0 && purity !=0){
                    const fine_wgt = (ntwt*purity)/100;
                    $('.fine_weight').eq(index).val(Number(fine_wgt.toFixed(3)));
                }
				if($('.waste').eq(index).val()!="" && $('').eq(index).val()!=0){
					wastagetolabour(index);
				}else{
					if($('.labour').eq(index).val()!="" && $('').eq(index).val()!=0){
						labourtowastage(index);
					}
				}
				
            }else{
                $(".purity").eq(index).val("");
                back = false;
            }
            return back;
        }

        function puritytocaret(index){
            var purity = $('.purity').eq(index).val()??0;
            var back = true;
            if(purity!="" && purity!=0){
                var one = 100/24;
                $(".caret").eq(index).val(Number((purity/one).toFixed(2)));
				$(".caret").eq(index).removeClass('is-invalid');
                const ntwt = $('.net_weight').eq(index).val()??0;
                if(ntwt!=0 && purity !=0){
                    const fine_wgt = (ntwt*purity)/100;
                    $('.fine_weight').eq(index).val(fine_wgt);
                }
				if($('.waste').eq(index).val()!="" && $('').eq(index).val()!=0){
					wastagetolabour(index);
				}else{
					if($('.labour').eq(index).val()!="" && $('').eq(index).val()!=0){
						labourtowastage(index);
					}
				}
				
            }else{
                $(".caret").eq(index).val("");
                back = false;
            }
            return back;
        }

        function wastagetolabour(index){
            var wastage = $('.waste').eq(index).val()??0;
            var back = true;
            if(wastage == 0 || wastage == ""){
                $('.labour').eq(index).val("");
                back = false;
            }else{
                const ntwt = $('.net_weight').eq(index).val()??0;
                const self_tbl_index = $('.waste').eq(index).closest('table').index();
                const rate = $('input[name="rate['+self_tbl_index+']"]').val()??0;
                if(ntwt!=""){
					const one_caret_rate = rate/24;
                    const caret_val = $('.caret').eq(index).val();
                    const caret_rate = one_caret_rate*caret_val;
                    var wstg_wt = (ntwt*wastage)/100;
                    var lbr_per_g = (wstg_wt*caret_rate)/ntwt;
                    $('.labour').eq(index).val(Number((lbr_per_g).toFixed(2)));
					$('.labour').eq(index).removeClass('is-invalid');
                }else{
                    $('.waste').eq(index).val("");
                    $('.net_weight').eq(index).focus();
                    back = false;
                }
            }
            return back;
        }

        function labourtowastage(index){
            const labour = $('.labour').eq(index).val();
            var back = true;
            if(labour == 0 || labour == ""){
                $('.waste').eq(index).val("");
                back = false;
            }else{
                const ntwt = $('.net_weight').eq(index).val()??0;
                const self_tbl_index = $('.labour').eq(index).closest('table').index();
                const rate = $('input[name="rate['+self_tbl_index+']"]').val()??0;
                if(ntwt!=""){
					const one_caret_rate = rate/24;
                    const caret_val = $('.caret').eq(index).val();
                    const caret_rate = one_caret_rate*caret_val;
                    var wstg_wt = (labour*ntwt)/caret_rate;
                    var lbr_perc = (wstg_wt*100)/ntwt;
                    $('.waste').eq(index).val(Number((lbr_perc).toFixed(2)));
					$('.waste').eq(index).removeClass('is-invalid');
                }else{
                    $('.labour').eq(index).val("");
                    $('.net_weight').eq(index).focus();
                    back = false;
                }
            }
            return back;
        }
    });

    function delete_stock_record(event){
        var out = true;
        if(event.target.tagName=="A"){
            $.get(event.target,"",function(response){
                if(response.success){
                    success_sweettoatr(response.success);
                }else{
                    out = false;
                    toastr.error(response.errors)
                }
            });
        }
        return out;
    }

    
</script>