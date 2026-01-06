<script>
    $(document).ready(function(){
        $(document).on('click','.stock_block_add',function(e){
            e.preventDefault();
            var ind = $('.stock_block_add').index($(this));
            var sel = $("#stocktype").val();
            $.get("{{ route("purchases.forms") }}","block="+(ind+1)+"&form="+sel,function(response){
                $("#entry_stock_area").append($(response));
                $("#form_area").append($(response));
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
            //var input_ind = $("tr.main_item_tr").index(tr);
            $.get($(this).attr('href'),"block="+block_ind+"&input="+input_ind,function(response){
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
            $(this).parent('div').remove();
            block_serialize();
            calculate();
        });
        
        $(document).on('click','.tr_del_btn',function(e){
            e.preventDefault();
            var tr = $(this).parent('td').parent('tr');
            var tr_ind = tr.index();
            var tbody = tr.parent('tbody');
            if(delete_stock_record(e)){
                tbody.find('.element_tr_'+tr_ind).remove();
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
                if(rate_value!=0){
                    calculate();
                }else{
                    if(!$(this).hasClass('rate')){
                        $(this).val('');
                        toastr.error("Enter The Rate First!");
                        $('.rate').eq(block_index).select();
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
                        calculate();
                    }
                }
            }
        });

        $(document).on('input','.element_cost,.element_quant,.element_caret,.element_name',function(){
            calculate();
        });

        $(document).on('input','.caret',function(){
            var caret = $(this).val()??0;
            const ind = $('.caret').index($(this));
            if(caret!="" && caret!=0){
                var one = 100/24;
                $(".purity").eq(ind).val(Number((caret*one).toFixed(2)));
            }else{
                $(".purity").eq(ind).val("");
            }
        });

        $(document).on('input','.purity',function(){
            var purity = $(this).val()??0;
            const ind = $('.purity').index($(this));
            if(purity!="" && purity!=0){
                var one = 100/24;
                $(".caret").eq(ind).val(Number((purity/one).toFixed(2)));
            }else{
                $(".caret").eq(ind).val("");
            }
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

        function calculate(){
            var total_quantity = 0;
            var total_weight = 0;
            var total_fine_weight = 0;
            var total_amount = 0;
            var total_payment = 0;
            var focus = "";
            $($(document).find('.rate')).each(function(i,v){
                var rate = $(this).val();
                var table_tr = $('table.genuine').eq(i).find('tbody > tr');
                table_tr.each(function(tr_i,tr_v){
                    if($(tr_v).find('td >.product').val()!=""){

                        var qnt = ($(tr_v).find('td >.quantity').val()>0)?$(tr_v).find('td >.quantity').val():0;
                        var nwgt = ($(tr_v).find('td >.net_weight').val()>0)?$(tr_v).find('td >.net_weight').val():0;
                        
                        var pur = ($(tr_v).find('td >.purity').val()>0)?$(tr_v).find('td >.purity').val():0;
                        var fnw = ($(tr_v).find('td >.fine_weight').val()>0)?$(tr_v).find('td >.fine_weight').val():0;
                        var wst = ($(tr_v).find('td >.waste').val()>0)?$(tr_v).find('td >.waste').val():0;
                        var lbr = ($(tr_v).find('td >.labour').val()>0)?$(tr_v).find('td >.labour').val():0;

                        var amt = ($(tr_v).find('td >.amount').val()>0)?$(tr_v).find('td >.amount').val():0;


                        purity = nmFixed(pur) ;
                        wastage = nmFixed(wst) ;

                        var finePurity = +purity + +wastage ;
                        var fineWeight = nwgt * (finePurity / 100) ;
                        var newLabourCharge = parseFloat((lbr * nwgt).toFixed(3)) ;
                        var wamount = fineWeight * rate ;
                        var amount = +wamount + +newLabourCharge ;

                        $(tr_v).find('td >.fine_pure').val(finePurity.toFixed(2));
                        $(tr_v).find('td >.fine_weight').val(fineWeight.toFixed(3));
                        $(tr_v).find('td > .amount').val(amount.toFixed(0));
                        
                        if($(this).hasClass('element_tr')){
                            $(this).find('div.element_div').each(function(ele_i,ele_v){
                                if($(this).find('.element_name').val()!="" && $(this).find('.element_quant').val()!="" && $(this).find('.element_cost').val()!=""){
                                    amount+= +$(this).find('.element_cost').val();
                                }
                            });
                        }else{
                            total_quantity++;
                        }
                        //total_quantity+= parseInt(qnt);
                        total_weight+= parseFloat(nwgt);
                        total_fine_weight+= parseFloat(fineWeight);
                        total_amount+= parseFloat(amount);
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