<script>
    $(document).ready(function(){
        $(document).on('click','.stock_block_add',function(e){
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
        });
        
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
            calculate();
        });
        
        $(document).on('click','.block_del_btn',function(e){
            e.preventDefault();
            $(this).parent('div').remove();
            block_serialize();
            calculate();
        });
        
        $(document).on('click','.tr_del_btn',function(e){
            e.preventDefault();
            var tr = $(this).parent('td').parent('tr');
            var tbody = tr.parent('tbody');
            if(delete_stock_record(e)){
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

        function calculate(){
            var total_quantity = 0;
            var total_weight = 0;
            var total_fine_weight = 0;
            var total_amount = 0;
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

                        total_quantity+= parseInt(qnt);
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
                        var amount = lbr+amt;
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

            // alert(total_quantity);
            // alert(total_amount);
            $("#totalQuantity").val(total_quantity);
            $("#totalWeight").val(total_weight.toFixed(3));
            $("#totalFineWeight").val(total_fine_weight.toFixed(3));
            $("#totalAmount").val(total_amount.toFixed(0));
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