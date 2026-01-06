<script>
    $(document).ready(function(){
        $(document).on('keyup','.rate', function() {
            
            var newRate = parseFloat($(this).val()) || 0 ;

            $('tr').each(function() {

                var row = $(this) ;
                var quantity = parseFloat(row.find('input[id^="quantity_"]').val()) || 0 ;
                var netWeight = parseFloat(row.find('input[id^="netWeight_"]').val()) || 0 ;
                var purity = parseFloat(row.find('input[id^="purity_"]').val()) || 0 ;
                var wastage = parseFloat(row.find('input[id^="wastage_"]').val()) || 0 ;
                var labourCharge = parseFloat(row.find('input[id^="labourCharge_"]').val()) || 0 ;

                purity = nmFixed(purity) ;
                wastage = nmFixed(wastage) ;

                var finePurity = +purity + +wastage ;
                var fineWeight = netWeight * (finePurity / 100) ;
                var newLabourCharge = parseFloat((labourCharge * netWeight).toFixed(3)) ;
                var wamount = fineWeight * newRate ;
                var amount = +wamount + +newLabourCharge ;

                row.find('input[id^="fineWeight_"]').val(fineWeight.toFixed(3)) ;
                row.find('input[id^="finePurity_"]').val(finePurity.toFixed(2)) ;
                row.find('input[id^="amount_"]').val(amount.toFixed(0)) ;

            });

            calculateTotals() ;

        });
    })
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
        block.find('div > div.form-group > span.select2').remove();
        block.find('div > div.form-group > select.select2').select2();
        block_serialize();
    });

    $(document).on('click','.more_item_tr',function(e){
        e.preventDefault();
        var table = $(this).prev('table');
        var tbody = table.find('tbody');
        var item_tr = tbody.find('tr:first').clone();
        item_tr.removeAttr('id');
        tbody.append(item_tr);
        tbody.find("tr:last > td > .td_input:first").focus();
        tbody.find("tr:last").find('td').eq(1).find('input').select();
        //$('.tr_del_btn').show();
        itemtr_serialize(tbody);
        calculateTotals();
    });

    $(document).on('click','.block_del_btn',function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        block_serialize();
    });

    $(document).on('click','.tr_del_btn',function(e){
        e.preventDefault();
        var tr = $(this).parent('td').parent('tr');
        var tbody = tr.parent('tbody');
        if(delete_stock_record(e)){
            tr.remove();
            itemtr_serialize(tbody);
        }
    });
    
    function block_serialize(){
        $('.block_sn').each(function(i,v){
            $(this).text(i+1);
        });
    }

    function itemtr_serialize(tbody){
        var count = $(tbody.find("tr")).length;
        tbody.find("tr").each(function(i,v){
            var del_btn = $(this).find('td.sn-box > .tr_del_btn')
            $(this).find('td.sn-box > .sn-number').text(i+1);
            if(count==1){
                del_btn.css('display','none');
            }else{
                del_btn.css('display','unset');
            }
        });
    }

    
    function calculate(element) {

        id = $(element).attr('id') ;
        var row = $(element).closest('tr') ;

        var quantity = parseFloat(row.find('input[id^="quantity_"]').val()) || 0;
        var netWeight = parseFloat(row.find('input[id^="netWeight"]').val()) || 0;
        var purity = parseFloat(row.find('input[id^="purity_"]').val()) || 0;
        var wastage = parseFloat(row.find('input[id^="wastage_"]').val()) || 0;
        var labourCharge = parseFloat(row.find('input[id^="labourCharge_"]').val()) || 0;
        var rate = parseFloat($('#rate').val()) || 0 ;

        if (isNaN(rate) || $('#rate').val().trim() === "") {
            rate = 0 ;
        }

        purity = nmFixed(purity) ;
        wastage = nmFixed(wastage) ;

        var finePurity = +purity + +wastage ;

        var fineWeight = netWeight * (finePurity / 100) ;
        var new_labourCharge = parseFloat((labourCharge * netWeight).toFixed(3)) ;
        var wamount = fineWeight * rate ;
        var amount =  +wamount + +new_labourCharge ;

        row.find('input[id^="fineWeight_"]').val(fineWeight.toFixed(3)) ;
        row.find('input[id^="finePurity_"]').val(finePurity.toFixed(2)) ;
        row.find('input[id^="amount_"]').val(amount.toFixed(0)) ;

        calculateTotals() ;

    }

    
    function calculateTotals() {

        var totalQuantity = 0;
        var totalNetWeight = 0;
        var totalFineWeight = 0;
        var totalAmount = 0;

        $('#tableBody tr').each(function() {

            var quantity = parseFloat($(this).find('input[id^="quantity_"]').val()) || 0;
            var netWeight = parseFloat($(this).find('input[id^="netWeight_"]').val()) || 0;
            var fineWeight = parseFloat($(this).find('input[id^="fineWeight_"]').val()) || 0;
            var amount = parseFloat($(this).find('input[id^="amount_"]').val()) || 0;

            totalQuantity += quantity;
            totalNetWeight += netWeight;
            totalFineWeight += fineWeight;
            totalAmount += amount;

        });

        $('#totalQuantity').val(totalQuantity.toFixed(0)) ;
        $('#totalWeight').val(totalNetWeight.toFixed(3)) ;
        $('#totalFineWeight').val(totalFineWeight.toFixed(3)) ;
        $('#totalAmount').val(totalAmount.toFixed(0)) ;

    }
    function delete_stock_record(event){
        var out = true;
        if(event.target.tagName=="A"){
            $.get(event.target,"",function(response){
                
            });
        }
        return out;
    }
</script>