<script>
    $(document).ready(function(){

		$(document).on('click','.stock_block_add',function(e){
            e.preventDefault();
            var ind = $($(document).find('.stock_block_add')).length;
            //var ind = $('.stock_block_add').index($(this));
            var sel = $("#stocktype").val();
            $.get("{{ route("purchases.forms") }}","block="+(ind)+"&form="+sel,function(response){
                $("#entry_stock_area").append($(response));
                $("#form_area").append($(response));
                (ind >1)?$(document).find('a.block_del_btn').eq(0).remove():null;
            });
        });
        
        $(document).on('click','.more_item_tr',function(e){
            e.preventDefault();
            var table = $(this).prev('table');
            var tbody = table.find('tbody');
            var item_tr = tbody.find('tr:first').clone();
            item_tr.removeAttr('id');
            if(edit){     
                item_tr.find('td>.stock_ids').val('');
                //item_tr.find('td>label.del_item_btn').remove();
                item_tr.find('td>label.del_item_btn').replaceWith('<button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>');
            }
            tbody.append(item_tr);
            tbody.find("tr:last > td > .td_input:first").focus();
            tbody.find("tr:last").find('td').eq(1).find('input').select();
            //$('.tr_del_btn').show();
            itemtr_serialize(tbody);
            //calculateTotals();
            calculate();
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
            calculate();
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
            calculate();
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
                calculate();
            }
        });
        
        function block_serialize(){
            $('.block_sn').each(function(i,v){
                $(this).text(i+1);
            });
        }
        
        function itemtr_serialize(tbody){
            var count =$(tbody.find("tr.main_item_tr")).length;
            tbody.find("tr.main_item_tr").each(function(i,v){
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


        $(document).on('input','.calculate_item',function(e){
            if(!$(this).hasClass('artifical')){
                var block = $(this).closest('.stock_block');
                var block_index = block.index('.stock_block');
                var rate_value = $('.rate').eq(block_index).val()??0;
                if(!$(this).hasClass('rate') && (rate_value==0 || rate_value=="")){
                    $(this).val('');
                    toastr.error("Enter The Rate First!");
                    $('.rate').eq(block_index).select();
                }else{
                    var go_ahead = true;
                    var tr_index = $('tbody > tr').index($(this).closest('tr'));
					var caller = false;
					if($(this).hasClass('rate')){
						caller = true;
					}
                    if(!$(this).hasClass('product')){
                        /*----The Below will allow only number and decimal place-----*/
                        //this.value = this.value.replace(/[^0-9.]/g, ''); 
                        //this.value = this.value.replace(/(?!^)-|[^0-9.]|(\..*?)\./g, '$1');
                        /*---The Below will Allow only Three Decimal Place ----------//
                        //--this.value = this.value.replace(/(?!^)-|[^0-9.]|(\.\d{3})\./g, '$1');--//

                        /*----Allow Two Deciaml Places------*/
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
                        calculate(caller);
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
                    }else{
                        //calculate();
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
                $(".purity").eq(index).val(purity);
                const ntwt = $('.net_weight').eq(index).val()??0;
                if(ntwt!=0 && purity !=0){
                    const fine_wgt = (ntwt*purity)/100;
                    $('.fine_weight').eq(index).val(Number(fine_wgt.toFixed(3)));
                }
                wastagetolabour(index);
                labourtowastage(index);
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
                const ntwt = $('.net_weight').eq(index).val()??0;
                if(ntwt!=0 && purity !=0){
                    const fine_wgt = (ntwt*purity)/100;
                    $('.fine_weight').eq(index).val(fine_wgt);
                }
                wastagetolabour(index);
                labourtowastage(index);
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
                const self_tbl_index = $('table>tbody').index($('.labour').eq(index).closest('table>tbody'));
                const rate = $('.rate').eq(self_tbl_index).val()??0;
                if(ntwt!="" && ntwt!=0){
                    const caret_val = $('.caret').eq(index).val()??0;
                    if(caret_val!="" && caret_val!=0){
                        const one_caret_rate = rate/24;
                        const caret_rate = one_caret_rate*caret_val;
                        var wstg_wt = (ntwt*wastage)/100;
                        var lbr_per_g = (wstg_wt*caret_rate)/ntwt;
                        $('.labour').eq(index).val(Number((lbr_per_g).toFixed(2)));
                    }else{
                        $('.waste').eq(index).val("");
                        $('.caret').eq(index).focus();
                        toastr.error('Please Enter The Caret Value !');
                    }
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
            if(labour == 0 || labour == "" ){
                $('.waste').eq(index).val("");
                back = false;
            }else{
                const ntwt = $('.net_weight').eq(index).val()??0;
                const self_tbl_index = $('table>tbody').index($('.labour').eq(index).closest('table>tbody'));
                const rate = $('.rate').eq(self_tbl_index).val()??0;
                if(ntwt!="" && ntwt!=0){
                    const caret_val = $('.caret').eq(index).val()??0;
                    if(caret_val!="" && caret_val!=0){
                        const one_caret_rate = rate/24;
                        const caret_rate = one_caret_rate*caret_val;
                        var wstg_wt = (labour*ntwt)/caret_rate;
                        var lbr_perc = (wstg_wt*100)/ntwt;
                        $('.waste').eq(index).val(Number((lbr_perc).toFixed(2)));
                    }else{
                        $('.labour').eq(index).val("");
                        $('.caret').eq(index).focus();
                        toastr.error('Please Enter The Caret Value !');
                    }
                }else{
                    $('.labour').eq(index).val("");
                    $('.net_weight').eq(index).focus();
                    back = false;
                }
            }
            return back;
        }

		
		$(document).on('input','.element_cost,.element_quant,.element_caret,.element_name',function(){
			calculate();
		});

        $("#mode").change(function(){
            $("#medium").val("");
            $("#medium > option").hide();
            $("#medium").prop('disabled',true);
            if($(this).val()!=""){
                $("#medium").prop('disabled',false);
                $("#medium > option."+$(this).val()).show();
            }
            $("#medium").trigger('change');
        });

        $("#medium").change(function(){
            if($(this).val()!=""){
                $("#amount").prop('disabled',false);
            }else{
                $("#amount").val('');
                $("#amount").prop('disabled',true);
            }
        });

        $("#amount").on('input',function(){
            if($(this).val()!=""){
                $("#ok_pay").prop('disabled',false);
            }else{
                $("#ok_pay").prop('disabled',true);
            }
        });


        $("#ok_pay").click(function(){
            if($("#mode").val()!="" && $("#medium").val()!="" && $("#amount").val()!=""){

                var mode = ($("#mode").val()=="on")?'Online':'Offline';
                var medium = $("#medium").val();
                var amount = $("#amount").val();

                var html = '<div class="col-md-3 my-1 p-2" style="border:1px dashed #a6a6a6;">';
                    html += '<div class="input-group input-group-sm">';
                    html += '<div class="input-group-prepend">';
                    html += '<span class="input-group-text px-"><b>MODE</b></span>';
                    html += '</div>';
                    html += '<input type="hidden" name="mode[]" value="'+($("#mode").val())+'">';
                    html += '<span class="form-control text-secondary"><b>Online</b></span>';
                    html += '</div>';

                    html += '<div class="input-group input-group-sm">';
                    html += '<div class="input-group-prepend">';
                    html += '<span class="input-group-text px-"><b>MEDIUM</b></span>';
                    html += '</div>';
                    html += '<input type="hidden" name="medium[]" value="'+medium+'">';
                    html += '<span class="form-control text-secondary"><b>'+medium+'</b></span>';
                    html += '</div>';

                    html += '<div class="input-group input-group-sm">';
                    html += '<div class="input-group-prepend">';
                    html += '<span class="input-group-text px-"><b>AMOUNT</b></span>';
                    html += '</div>';
                    html += '<input type="hidden" name="paid[]" value="'+amount+'">';
                    html += '<span class="form-control text-secondary"><b>'+amount+'</b></span>';
                    html += '</div>';
                    html +='<a href="javascript:void(null);" class="pay_remove">x</a>';
                    html += '</div>';
                    
                $("#payment_block").append(html);
                $("#mode").val('');
                $("#mode").trigger('change');
                calculate();
            }else{
                toastr.error("Please Select Mode > Medium > Amount First !");
            }
        });

        $(document).on('click','.pay_remove',function(e){
            $(this).parent('div').remove();
            calculate();
        });

        $('.pay_del_check').change(function(){
            $(this).parent('label').parent('div').toggleClass('disabled');
            $(this).parent('label').toggleClass('active');
        })

        function calculate(caller = false){
            var total_quantity = 0;
            var total_weight = 0;
            var total_fine_weight = 0;
            var total_amount = 0;
            var total_payment = 0;
            var focus = "";
            var qnt = 0;
            $($(document).find('.rate')).each(function(i,v){
                var rate = $(this).val();
				var table_tr = $('table.genuine').eq(i).find('tbody > tr');
				table_tr.each(function(tr_i,tr_v){
					if($(tr_v).find('td >.product').val()!=""){
						qnt++;
						var nt_wt = ($(tr_v).find('td >.net_weight').val()>0)?$(tr_v).find('td >.net_weight').val():0;
						if(caller){
                            var crt_val = ($(tr_v).find('td >.caret').val()>0)?$(tr_v).find('td >.caret').val():0;

                            const crt_rate = (rate/24)*crt_val;

                            const purity = (100/24)*crt_val;

                            const fine_wght = (nt_wt*purity)/100;

                            $(tr_v).find('td >.fine_weight').val(Number(fine_wght.toFixed(3)));

                            const item_cost = nt_wt*crt_rate;

                            $(tr_v).find('td >.purity').val(Number(purity.toFixed(2)));

                            const wstg = $(tr_v).find('td >.waste').val();

                            const wstg_wt  = (nt_wt*wstg)/100;

                            const ttl_lbr = wstg_wt*crt_rate;

                            const lbr_chrg = Number((ttl_lbr/nt_wt).toFixed(2));

                            $(tr_v).find('td >.labour').val(lbr_chrg);

                            const total = item_cost+ttl_lbr;

                            $(tr_v).find('td >.amount').val(Number(total.toFixed(2)));
                        }
						

						var pur = ($(tr_v).find('td >.purity').val()>0)?$(tr_v).find('td >.purity').val():0;
						var fnw = ($(tr_v).find('td >.fine_weight').val()>0)?$(tr_v).find('td >.fine_weight').val():0;
						var wst = ($(tr_v).find('td >.waste').val()>0)?$(tr_v).find('td >.waste').val():0;
						var lbr = ($(tr_v).find('td >.labour').val()>0)?$(tr_v).find('td >.labour').val():0;

						var amt = ($(tr_v).find('td >.amount').val()>0)?$(tr_v).find('td >.amount').val():0;


						// purity = nmFixed(pur) ;
						// wastage = nmFixed(wst) ;

						// var finePurity = +purity + +wastage ;
						// //var fineWeight = nt_wt * (finePurity / 100) ;
						// var newLabourCharge = parseFloat((lbr * nt_wt).toFixed(3)) ;
						// var wamount = fineWeight * rate ;
						// var amount = +wamount + +newLabourCharge ;

						// $(tr_v).find('td >.fine_pure').val(finePurity.toFixed(2));
						// $(tr_v).find('td >.fine_weight').val(fineWeight.toFixed(3));
						// $(tr_v).find('td > .amount').val(amount.toFixed(0));
						
						if($(this).hasClass('element_tr')){
							$(this).find('div.element_div').each(function(ele_i,ele_v){
								if($(this).find('.element_name').val()!="" && $(this).find('.element_quant').val()!="" && $(this).find('.element_cost').val()!=""){
									amt+= +$(this).find('.element_cost').val();
								}
							});
						}else{
							total_quantity++;
						}
						total_quantity+= parseInt(qnt);
						total_weight+= parseFloat(nt_wt);
						total_fine_weight+= parseFloat(fnw);
						total_amount+= parseFloat(amt);
					}
				});
            });
            
            $($(document).find('.artificial_product')).each(function(ind,vl){
                if($(this).val()!=""){
                    var table_tr = $('table.artificial').eq(ind).find('tbody > tr');
                    //var table_tr = $(this).parent('td').parent('tr').parent('tbody > tr');
                    table_tr.each(function(tr_in,tr_vl){
                        var qnt = ($(tr_vl).find('td >.quantity').val()>0)?$(tr_vl).find('td >.quantity').val():0;
                        var lbr = ($(tr_vl).find('td >.labour').val()>0)?$(tr_vl).find('td >.labour').val():0;
                        var amt = ($(tr_vl).find('td >.amount').val()>0)?$(tr_vl).find('td >.amount').val():0;
                        var amount = +lbr + +amt;
                        if(qnt !=0 && amt != 0){
                            total_quantity+= parseInt(qnt);
                            total_amount+= parseFloat(amount);
                        }
                    });
                }else{
                    // toastr.error("Enter The Product Name First!");
                    // $(this).select();
                }
            });
            $(document).find('[name="paid[]"]').each(function(i,v){
                total_payment+= +($(this).val()??0);
            });
            
            var remains = +total_amount.toFixed(0) - total_payment;
            $("#totalQuantity").val(total_quantity);
            $("#totalWeight").val(total_weight.toFixed(3));
            $("#totalFineWeight").val(total_fine_weight.toFixed(3));
            $("#payamount").val(total_payment);
            $("#totalAmount").val(total_amount.toFixed(0));
            $("#remains").val(remains);
            //alert("QUANTITY "+total_quantity +"\n"+"Weight "+total_weight +"\n"+"Fine "+total_fine_weight +"\n"+"Amount "+total_amount );
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