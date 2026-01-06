@php 
    $edit = $edit??false;
@endphp
<script>

$(document).on('input.silver','.gross',function(){
    const index = itemcheck($(this));
    const gross = $(this).val()??0;
    const less = $(document).find('.less').eq(index).val()??0;
    //if(gross){
        $(document).find('.net').eq(index).val((+gross - +less).toFixed(3));
    //}
    calculatesum(index);
});

$(document).on('input.silver','.less',function(){
    const index = itemcheck($(this));
    const less = $(this).val()??0;
    const gross = $(document).find('.gross').eq(index);
    const gross_val = gross.val()??false;
    if(gross_val){
        $(document).find('.net').eq(index).val((+gross_val - +less).toFixed(3));
    }else{
        toastr.error('Please Enter the Gross !');
        $(this).val("");
        gross.addClass('is-invalid').focus();
    }
    calculatesum(index);
});

$(document).on('input.silver','.tunch',function(){
    const index = itemcheck($(this));
    const tunch = $(document).find('.tunch').eq(index).val()??false;
    if(tunch){
        if(tunch > 100){
            toastr.error("Tunch can't be Greater than 100 !");
            $(document).find('.tunch').eq(index).focus();
			$(document).find('.tunch').eq(index).val($(document).find('.tunch').eq(index).val().toString().slice(0, -1));
        }
    }
    calculatesum(index);
});

$(document).on('blur.silver','.tunch',function(){
    const index = itemcheck($(this));
    const tunch = $(document).find('.tunch').eq(index).val()??false;
    if(tunch){
        if(tunch > 100){
            toastr.error("Tunch can't be Greater than 100 !");
            $(document).find('.tunch').eq(index).focus();
			$(document).find('.tunch').eq(index).val($(document).find('.tunch').eq(index).val().toString().slice(0, -1));
        }
    }
    calculatesum(index);
});

/*$(document).on('blur.silver','.tunch',function(){
    const index = itemcheck($(this));
    const tunch = $(document).find('.tunch').eq(index).val()??false;
    const caret = $(document).find('.caret').eq(index).val()??false;
    if(!tunch){
        if(!caret){
            toastr.error("Caret required to Create the Tunch !");
        }else{
            $(document).find('.tunch').eq(index).val((100/24 * caret));
        }
    }else{
        if(caret){
            const nw_t =  Math.round((100/24 * caret));
            if(nw_t != tunch){
                toastr.error("Wrong Tunch value !");
                $(document).find('.tunch').eq(index).addClass('is-invalid').focus();
            }
        }else{
            const nw_k = Math.round((tunch * 24) /100);
            if($.inArray(parseInt(nw_k), [18, 20, 22, 24])!== -1){
                $(document).find('.caret').eq(index).val(nw_k);
            }else{
                $(document).find('.caret').eq(index).val("");
                toastr.error("Wrong Tunch value !");
                $(document).find('.tunch').eq(index).addClass('is-invalid').focus();
            }
        }
    }
    calculatesum(index);
});

$(document).on('input.silver','.tunch',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index);
    const net_value = net.val()??false;
    const caret = $(document).find('.caret').eq(index).val()??false;
    if(net_value){
        const tunch = $(document).find('.tunch').eq(index);
        const tunch_val = tunch.val()??0;
        const nw_k = Math.round((tunch_val * 24) /100);
        if($.inArray(parseInt(nw_k), [18, 20, 22, 24])!== -1){
            $(document).find('.caret').eq(index).val(nw_k);
            $(this).removeClass('is-invalid').focus();

        }else{
            $(document).find('.caret').eq(index).val("");
            $(this).addClass('is-invalid').focus();

        }
    }else{
        if(caret){
            const nw_t =  Math.round((100/24 * caret));
            $(document).find('.tunch').eq(index).val(nw_t);
        }
        toastr.error("Net missing Re-Enter te Gross !");
        $(document).find('.gross').eq(index).addClass('is-invalid').focus();
        return false;
    }
    calculatesum(index);
});*/

$(document).on('input.silver','.wstg',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index);
    const net_val = net.val()??false;
    if(net_val){
        const caret = $(document).find('.caret').eq(index).val()??false;
        const tunch = $(document).find('.tunch').eq(index).val()??false;
        if(caret);
    }else{
        toastr.error("Net missing, Re-Enter the Gross !");
        $(document).find('.gross').eq(index).addClass("is-invalid").focus();
        $(this).val("");
    }
    calculatesum(index);
});

$(document).on('input.silver','.chrg',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index).val()??false;
    if(!net){
        toastr.error("Net Missing Re-Enter the Gross !");
        $(this).val("");
        $(document).find('.gross').eq(index).focus();
    }
    calculatesum(index);
});

$(document).on('input.silver','.rate',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index).val()??false;
    if(!net){
        toastr.error("Net Missing Re-Enter the Gross !");
        $(this).val("");
        $(document).find('.gross').eq(index).focus();
    }
    calculatesum(index);
});

$(document).on('input.silver','.lbr',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index).val()??false;
    if(!net){
        toastr.error("Net Missing Re-Enter the Gross !");
        $(this).val("");
        $(document).find('.gross').eq(index).focus();
    }
    calculatesum(index);
});

$(document).on('change.silver','.lbrunit',function(){
    const index = itemcheck($(this));
    const lbr = $(document).find('.lbr').eq(index).val()??false;
    if($(this).val()!=""){
        if(!lbr){
            toastr.error("Please Enter The Labour Charge !");
            $(this).val("");
            $(document).find('.lbr').eq(index).focus();
        }
    }
    calculatesum(index);
});

$(document).on('input.silver','.disc',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index).val()??false;
    if(!net){
        toastr.error("Net Missing Re-Enter the Gross !");
        $(this).val("");
        $(document).find('.gross').eq(index).focus();
    }
    calculatesum(index);
});

$(document).on('change.silver','.discunit',function(){
    const index = itemcheck($(this));
    const disc = $(document).find('.disc').eq(index).val()??false;
    if($(this).val()!=""){
        if(!disc){
            toastr.error("Please Enter The Discount Value !");
            $(this).val("");
            $(document).find('.disc').eq(index).focus();
        }
    }
    calculatesum(index);
});

$(document).on('input.silver','.other',function(){
    const index = itemcheck($(this));
    const net = $(document).find('.net').eq(index).val()??false;
    if(!net){
        toastr.error("Net Missing Re-Enter the Gross !");
        $(this).val("");
        $(document).find('.gross').eq(index).focus();
    }
    calculatesum(index);
});

function itemcheck(element){
    const index = element.closest('tr').index();
	@if(!$edit)
    const item = $(document).find('.item').eq(index)
    const item_name = item.val()??false;
    if(item_name){
        return index;
    }else{
        element.val("");
        toastr.error("Please Select the Item Name First !");
        item.addClass('is-invalid').focus();
        return;
    }
	@else 
		return index;
	@endif
}

$(document).on('input.silver','.ttl',function(){
    calculatesum();
});



function calculatetunchfine(index){
    const net = $(document).find('.net').eq(index).val()??false;
    const caret = $(document).find('.caret').eq(index).val()??false;
    const wastage = $(document).find('.wstg').eq(index).val()??0;
    var tounch = (!caret)?($(document).find('.tunch').eq(index).val()??0):0;
    if(caret){
        tounch = 100/24 * caret;
        $(document).find('.tunch').eq(index).val(Math.round(tounch));
    }
	var fine = net??0;
    if(tounch && net && wastage){
		const wstg_val = Math.round(+tounch + +wastage);
        fine = ((+net * +wastage_val)/100).toFixed(3);
	}
	$(document).find('.fine').eq(index).val(fine);
}

function calculatesum(index){
    calculatetunchfine(index);
    const net = $(document).find('.net').eq(index).val()??0;
    const rate = $(document).find('.rate').eq(index).val()??0;

    const chrg = $(document).find('.chrg').eq(index).val()??0;
    const other = $(document).find('.other').eq(index).val()??0;

    const lbr = $(document).find('.lbr').eq(index).val()??0;
    const lbr_unit = $(document).find('.lbrunit').eq(index).val()??0;

    const disc = $(document).find('.disc').eq(index).val()??0;
    const disc_unit = $(document).find('.discunit').eq(index).val()??0;

    const item_sum = ((net * rate) + +chrg);
    
    var labour_value = discount_value = 0;
    if(lbr!=0 && lbr_unit!=0){
        if(lbr_unit == 'p'){
            labour_value = (item_sum * lbr)/100;
        }else{
            labour_value = (net * lbr)/100 * rate;
        }
    }else{
        //toastr.error("Check Manually For Labour & Unit !");
        //return false;
    }

    if(disc!=0 && disc_unit!=0){
        if(disc_unit=="r"){
            discount_value = disc;
        }else{
            discount_value = (item_sum * disc)/100;
        }
    }else{
        //toastr.error("Check Manually For Discount & Unit !");
        //return false;
    }
    
    const item_sub = (+item_sum  -  +discount_value) + +labour_value + +other;
    $(document).find('.ttl').eq(index).val(item_sub.toFixed(2));
    var item_count = stock_total = piece_total = gross_total = net_total = fine_total = chrg_total = other_total = 0;
    $(document).find('.ttl').each(function(i,v){
        const ttl_sum = $(v).val()||0;
        stock_total += +ttl_sum;
		if($(v).val()!=""){
			if($(document).find('.item').eq(i).val()!=""){
                item_count++;
            }
            //item_count++;
        }
    });
    $(document).find('.piece').each(function(i,v){
        const count_sum = $(v).val()||0;
        piece_total += +count_sum;
    });
    $(document).find('.gross').each(function(i,v){
        const gross_sum = $(v).val()||0;
        gross_total += +gross_sum;
    });
    $(document).find('.net').each(function(i,v){
        const net_sum = $(v).val()||0;
        net_total += +net_sum;
    });
    $(document).find('.fine').each(function(i,v){
        const fine_sum = $(v).val()||0;
        fine_total += +fine_sum;
    });
    $(document).find('.chrg').each(function(i,v){
        const chrg_sum = $(v).val()||0;
        chrg_total += +chrg_sum;
    });
    $(document).find('.other').each(function(i,v){
        const other_sum = $(v).val()||0;
        other_total += +other_sum;
    });
    $(document).find('#list_count').val(item_count||0);
    $(document).find("#list_piece").val(piece_total||0);
    $(document).find("#list_gross").val((gross_total||0).toFixed(3));
    $(document).find("#list_net").val((net_total||0).toFixed(3));
    $(document).find("#list_fine").val((fine_total||0).toFixed(3));
    $(document).find("#list_chrg").val((chrg_total||0).toFixed(2));
    $(document).find("#list_other").val((other_total||0).toFixed(2));
    $(document).find("#list_total").val((stock_total||0).toFixed(2));
}

</script>
