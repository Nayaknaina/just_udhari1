<script>

    $(document).on('input','.piece',function(){
        const index = itemcheck($(this));
        const piece_val =$(this).val()??false;
        if(!piece_val || piece_val==0){
            toastr.error("Invalid Piece Count !");
        }
        itemsum(index);
    });

    $(document).on('input','.gross',function(){
        const index = itemcheck($(this));
        const gross_val =$(this).val()??false;
        if(!gross_val || gross_val==0){
            toastr.error("Invalid Gross !");
        }
        weightchange(index);
        itemsum(index);
    });

    
    $(document).on('change','.caret',function(){
        const index = itemcheck($(this));
        const caret_val =$(this).val()||false;
        var tunch = "";
        if(caret_val){
            tunch = Math.round(100/24 * caret_val);
        }
        $(document).find('.tunch').eq(index).val(tunch);
        $(document).find('.gross').eq(index).trigger('input');
    });

    $(document).on('input','.less',function(){
        const index = itemcheck($(this));
        const less_value = $(this).val()??false;
        const gross_value = $(document).find('.gross').eq(index).val()??false;
        if(less_value && +less_value > +gross_value){
            toastr.error("Less Can't be Greater than Gross !");
            $(this).val(($(this).val()).slice(0,-1));
        }else{
            $(document).find('.gross').eq(index).trigger('input');
        }
    });


    $(document).on('input','.wstg',function(){
        const index = itemcheck($(this));
        const wstg_value = $(this).val()??false;
        const tunch_value = $(document).find('.tunch').eq(index).val()??false;
        var diff = 0;
        if(tunch_value){
            diff = 100-tunch_value;
        }
        if(wstg_value && +wstg_value > +diff){
            toastr.error(`Wastage Can't be Greater than ${diff} !`);
            $(this).val(($(this).val()).slice(0,-1));
        }else{
            $(document).find('.gross').eq(index).trigger('input');
        }
    });

    $(document).on('input','.tunch',function(){
        const index = itemcheck($(this));
        const tunch_val = $(this).val()??0;
        if(+tunch_val > 100){
            toastr.error("Tunch Can't be Greater than 100 !");
            $(this).val(($(this).val()).slice(0,-1));
        }else{
            if(+tunch_val !=0){
                //alert((24 * (+tunch_val))/100);
                const caret = Math.round((24 * (+tunch_val))/100);
                if($.inArray(caret,[18,20,22,24])  !== -1){
                    $(document).find('.caret').eq(index).val(caret);
                }else{
                    $(document).find('.caret').eq(index).val('');
                    toastr.error("Invalid Tunch !");
                }
            }
            $(document).find('.gross').eq(index).trigger('input');
        }
    });

    $(document).on('input','.lbr',function(){
        const index = itemcheck($(this));
        const lbr = $(this);
        const lbr_val = lbr.val()??false;
        const lbr_unit = $(document).find('.lbrunit').eq(index).val()??false;
        itemsum(index);
    });

    $(document).on('blur','.lbr',function(){
        const index = itemcheck($(this));
        const lbr = $(this);
        const lbr_val = lbr.val()??false;
        const lbr_unit = $(document).find('.lbrunit').eq(index).val()??false;
        if(lbr_val && !lbr_unit){
            toastr.error('Labour Unit with Charge required !');
            $(document).find('.lbrunit').eq(index).focus();
        }
    });

    $(document).on('change','.lbrunit',function(){
        const index = itemcheck($(this));
        itemsum(index);
    });

    $(document).on('input','.disc',function(){
        const index = itemcheck($(this));
        const disc = $(this);
        const disc_val = disc.val()??false;
        const disc_unit = $(document).find('.discunit').eq(index).val()??false;
        itemsum(index);
    });

    $(document).on('blur','.disc',function(){
        const index = itemcheck($(this));
        const disc = $(this);
        const disc_val = disc.val()??false;
        const disc_unit = $(document).find('.discunit').eq(index).val()??false;
        if(disc_val && !disc_unit){
            toastr.error('Labour Unit with Charge required !');
            $(document).find('.discunit').eq(index).focus();
        }
    });

    $(document).on('change','.discunit',function(){
        const index = itemcheck($(this));
        itemsum(index);
    });

    $(document).on('input','.ttl,.chrg,.other,.rate',function(){
        const index = itemcheck($(this));
        itemsum(index);
    })

    function itemcheck(element){
        const index = $('tbody#sale_form > tr.item_tr').index(element.closest('tr.item_tr'));
        const item = $(document).find('.item').eq(index)
        const item_name = item.val()??false;
        if(item_name){
            return index;
        }else{
            element.val("");
            toastr.error("Enter/Select the Item Name First !");
            item.addClass('is-invalid').focus();
            return;
        }
    }

    function weightchange(index){
        const gross = $(document).find('.gross').eq(index).val()||false;
        if(gross && gross!=0){
            const less = $(document).find('.less').eq(index).val()??0;
            $(document).find('.net').eq(index).val((gross-less).toFixed(3));

            const net = $(document).find('.net').eq(index).val()??0;
            const wstg = $(document).find('.wstg').eq(index).val()??0;
            var tunch = $(document).find('.tunch').eq(index).val()??0;
            var caret = $(document).find('.caret').eq(index).val()??false;
            if(caret && tunch==0){
                tunch = (100/24)*caret;
            }
            var fine = net;
            if(tunch!=0 && wstg!=0){
                const pure = Math.round(+tunch + +wstg);
                fine = (+net * pure)/100;
            }
            //const fine = (+net - minus_wght).toFixed(3);
            $(document).find('.fine').eq(index).val(fine);
        }else{
            $(document).find('.gross').eq(index).val('');
            $(document).find('.net').eq(index).val('');
            $(document).find('.fine').eq(index).val('');
        }
        itemsum(index);
    }

    function itemsum(index){
        const net = $(document).find('.net').eq(index).val()??0;
        const rate = $(document).find('.rate').eq(index).val()??0;
		const  item_type = $(document).find('.stock').eq(index).val()??false;
        var item_cost = 0;
        if(item_type && ($.inArray(item_type.toLowerCase(),['stone','artificial','franchise-jewellery'])  !== -1)){
            item_cost = rate * (($(document).find('.piece').eq(index).val())??0);
        }else{
            item_cost = (net * rate);
        }
        //const item_cost = net * rate;

        const chrg = $(document).find('.chrg').eq(index).val()??0;
        const other = $(document).find('.other').eq(index).val()??0;

        const disc = $(document).find('.disc').eq(index).val()??0;
        const disc_unit = $(document).find('.discunit').eq(index).val()??false;
        var disc_amnt = 0;
        if(disc_unit && disc){
            if(disc_unit == 'p'){
                disc_amnt = (item_cost * disc)/100;
            }else{
                disc_amnt = disc;
            }
        }
        const lbr = $(document).find('.lbr').eq(index).val()??0;
        const lbr_unit = $(document).find('.lbrunit').eq(index).val()??false;
        var lbr_amnt = 0;
        if(lbr_unit && lbr){
            if(lbr_unit == 'p'){
                lbr_amnt = (item_cost * lbr)/100;
            }else{
                lbr_amnt = lbr * net;
            }
        }
        const item_sub = ((+item_cost - +disc_amnt) + +chrg + +other + +lbr_amnt).toFixed(2);
        $(document).find('.ttl').eq(index).val(item_sub??'');
        billsum();
    }

    function billsum(){
        var item_count = piece_sum = gross_sum = net_sum = fine_sum = chrg_sum = other_sum = total_sum =0
        $(document).find('tr:not(.deleted) td .ttl').each(function(i,v){
            if($(document).find('.item').eq(i).val()!=""){
                item_count++;
                const piece = $(document).find('.piece').eq(i).val()??0;
                const gross = $(document).find('.gross').eq(i).val()??0;
                const net = $(document).find('.net').eq(i).val()??0;
                const fine = $(document).find('.fine').eq(i).val()??0;
                const chrg = $(document).find('.chrg').eq(i).val()??0;
                const other = $(document).find('.other').eq(i).val()??0;
                const ttl = $(v).val()??0;
                piece_sum+= +piece;
                gross_sum+= +gross;
                net_sum+= +net;
                fine_sum+= +fine;
                chrg_sum+= +chrg;
                other_sum+= +other;
                total_sum+= +ttl;
            }
        });
        $("#list_item").val(item_count);
        $("#list_piece").val(piece_sum);
        $("#list_gross").val(gross_sum.toFixed(3));
        $("#list_net").val(net_sum.toFixed(3));
        $("#list_fine").val(fine_sum.toFixed(3));
        $("#list_chrg").val(chrg_sum.toFixed(2));
        $("#list_other").val(other_sum.toFixed(2));
        $("#list_total,#sub").val(total_sum.toFixed(2));
        //$("#sub").
        billtotal();
    }

    $('#discount ,#gst ,#sub').on('input',function(){
        billtotal();
    });
     $('#discount_unit').on('change',function(){
        billtotal();
    });

    function billtotal(){
        const sub_total = $('#sub').val()??0;
        var bill_disc = $("#discount").val()??0;
        const bill_disc_unit = $("#discount_unit").val()??false;
        const bill_gst = $("#gst").val()??0;
        const rounder_sub = Math.round(sub_total);
        const roundoff = +rounder_sub - +sub_total;
        const payment = $("#payment").val()??0;
        if(bill_disc_unit){
            if(bill_disc_unit=='p'){
                bill_disc = (sub_total * bill_disc)/100;
            }
        }
        const bill_total = (+sub_total - +bill_disc).toFixed(2);
        const bill_gst_amnt = ((+bill_total * +bill_gst)/100).toFixed(2);
        const bill_grand = (+bill_total + +bill_gst_amnt);
        const bill_total_rounder = Math.round(bill_grand);
        const round_off = (bill_total_rounder - bill_grand).toFixed(2);

        $("#round").val(round_off??0);
        $("#total").val(bill_grand??0);
        $("#final").val(bill_total_rounder??0);
        //const bill_disc = $(document).
        const balance = bill_total_rounder - payment;
        $("#balance").val(balance);
    }

</script>