<script>
    $(document).ready(function(){
        $('section.content').append(`<ul id="item_list"style="display:none;"></ul>`);
    });

    $(document).on('keydown', function(e) {
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
        // Example: Detect Ctrl + S
        /*if (e.ctrlKey && e.key.toLowerCase() === 's') {
            e.preventDefault(); // prevent browser save
            console.log('Ctrl + S pressed!');
        }

        // Example: Detect Shift + A
        if (e.shiftKey && e.key.toLowerCase() === 'a') {
            console.log('Shift + A pressed!');
        }*/
    });

    $(document).on('focus','.item',function(){
        if($("#item_list").css('display')=='block'){
            $("#item_list").hide();
            $("#item_list").empty();
        }
    });

    var input_index = false;
    $(document).on('input','.item',function(){
        let input = $(this);
        input_index = $(document).find('input.item').index(input);
        if(input.val()!=""){
            $.get("{{ route('stock.find.item') }}","keyword="+$(this).val(),function(response){
                let lis = '';
                console.log(response.items);
                if(response.items){
                    if(response.items.length > 0 ){
                        $.each(response.items,function(ii,item){
                            const data_arr = {
                                'category':item.itemgroup.cat_name.toLowerCase(),
                                "labour_value":item.labour_value,
                                "labour_unit":item.labour_unit,
                                "tax_value":item.tax_value,
                                "tax_unit":item.tax_unit,
                                "tounch":item.tounch,
                                "wastage":item.wastage,
                            };
                            const data = JSON.stringify(data_arr).replace(/"/g, '&quot;');
                            lis += `<li><a href="javascript:void(null);" data-title="${item.item_name}" data-target="${item.id}" data-desc="${data}" class="get_item"><b>${ii+1}: </b>${item.item_name} !</li>`;
                        });
                    }else{
                        lis = `<li><a href="javascript:void(null);" >No Item !</li>`;
                    }
                }
                if(lis!=''){
                    $("#item_list").empty().append(lis);
                    showitem(input);
                }else{
                    $("#item_list").empty().hide();
                }
            });
        }else{
            $('.item_tbody > tr').eq(input_index).removeClass();
            $("input.type").eq(input_index).val("");
            $('.item_tbody > tr').eq(input_index).find('input,select:not(.op)').val("");
            $(document).find('.ttl').eq(input_index).trigger('input');
            $(document).find('.rate').eq(input_index).trigger('input');
        }
    });

    function showitem(item){
        const input = item;
        const offset = input.offset();
        const inputHeight = input.outerHeight();
        const list = $('#item_list');

        // Temporarily show to get its height
        list.css({ visibility: 'hidden', display: 'block' });
        const listHeight = list.outerHeight();
        list.css({ visibility: '', display: 'none' });

        const windowBottom = $(window).scrollTop() + $(window).height();
        const spaceBelow = windowBottom - (offset.top + inputHeight);

        // Positioning logic
        const topPos = (spaceBelow > listHeight)
        ? offset.top + inputHeight  // show below
        : offset.top - listHeight;  // show above

        list.css({
            position: 'absolute',
            top: topPos,
            left: offset.left,
            display: 'block',
            zIndex: 999
        });
    }

    $(document).on('click','.get_item',function(){
        var title = $(this).data('title');
        var id = $(this).data('target');
        var data = $(this).data('desc');
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
        input_index = false;
    });

    $(document).on('change','.caret',function(){
        const index = $(this).closest('tr').index();
        const item = $(document).find('.item').eq(index).val()??false;
        if(item){
            const k = $(this).val()??false;
            if(k){
                let one_tunch = 100/24;
                const nw_tunch = Math.round(one_tunch*k);
                $(document).find('.tunch').eq(index).val(nw_tunch);
            }else{
                $(document).find('.tunch').eq(index).val('');
            }
            calculatefine(index);
        }else{
            $(this).val("");
            toastr.error("Item Missing !");
            $(document).find('.item').eq(index).focus();
        }
    });

    $(document).on('input','.gross',function(){
        const index = $(this).closest('tr').index();
        const caret = $(document).find('.caret').eq(index).val()??false;
        if(caret){
            const grs = $(this).val()??false;
            if(grs){
                $(document).find('.net').eq(index).val(grs);
            }else{
                 $(document).find('.net').eq(index).val('');
            }
            calculatefine(index);
        }else{
            $(this).val("");
            toastr.error("Caret Missing !");
            //caret.trigger('change');
            $(document).find('.caret').eq(index).focus().trigger('change');
        }
    });

    $(document).on('input','.less ',function(){
        const less = $(this).val()??false;
        const index = $(this).closest('tr').index();
        const grs = $(document).find('.gross').eq(index).val()??false;
        if(grs){
            if(less){
                $(document).find('.net').eq(index).val(+grs - +less);
            }else{
               $(document).find('.net').eq(index).val(grs); 
            }
        }else{
            $(document).find('.net').eq(index).val('');
            toastr.error("Gross Missing !");
            $(document).find('.gross').eq(index).focus().trigger('input');
        }
        calculatefine(index);
    });

    $(document).on('input','.net ',function(){
        const index = $(this).closest('tr').index();
        const grs = $(document).find('.gross').eq(index).val()??false;
        if(grs){
            if(grs < $(this).val()){
                let net = $(this).val().slice(0,-1);
                $(this).val(net);
                toastr.error("Net Can't be Greater Than Gross !");
            }
        }else{
            $(this).val("");
            toastr.error('Gross Missing !');
            $(document).find('.gross').eq(index).focus().trigger('input');
        }
    });

    $(document).on('input','.tunch',function(){
        const index = $(this).closest('tr').index();
        const caret = $(document).find('.caret').eq(index).val()??false;
        if(!caret){
            $(this).val("");
            toastr.error("Caret Missing !");
            $(document).find('.caret').eq(index).focus().trigger('change');
        }
    });

    $(document).on('input','.fine',function(){
        const index = $(this).closest('tr').index();
        const net = $(document).find('.net').eq(index).val()??false;
        if(net){
            if(+net < +$(this).val()){
                let fine = $(this).val().slice(0,-1);
                $(this).val(fine);
                toastr.error("Fine Can't be Greater Than Net !");
            }
        }else{
            $(this).val("");
            toastr.error('Net Missing !');
            $(document).find('.net').eq(index).focus().trigger('input');
        }
    });

    $(document).on('input','.wstg',function(){
        const index = $(this).closest('tr').index();
        const wstg = $(this).val()??false;
        const net = $('.net').eq(index).val()??false;
        if(net){
            calculatefine(index);
        }else{
            $(document).find('.net').eq(index).trigget('input');
        }
    });

    $(document).on('input','.chrg,.rate,.lbr,.wstg',function(){
        const index = $(this).closest('tr').index();
        const fine = $(document).find('.fine').eq(index).val()??false;
        if(!fine){
            $(this).val("");
            toastr.error('Fine Missing !');
            $(document).find('.fine').eq(index).focus().trigger('input');
        }else{
            var go = true;
            const rate = $(document).find('.rate').eq(index).val()??0;
            const lbr_val = $(document).find('.lbr').eq(index).val()??0; 
            const lbr_unt = $(document).find('.lbrunit').eq(index).val()??false;
            const chrg = $(document).find('.chrg').eq(index).val()??0;
            const other = $(document).find('.other ').eq(index).val()??0;
            const disc_val = $(document).find('.disc').eq(index).val()??0;
            const disc_unt = $(document).find('.discunit').eq(index).val()??0;
            if(lbr_val!=0){
                if(!lbr_unt){
                    $(".lbrunit").eq().addClass('is-invalid').focus();
                    toastr.error("Please Select the Labout Charge Unit !");
                    go = false;
                }
            } 
            if(disc_val!=0){
                if(!disc_unt){
                    $(".discunit").eq(index).addClass('is-invalid').focus();
                    toastr.error("Please Select the Discount Unit !");
                    go = false;
                }
            }
            if(go){
                var itm_intr_ttl = (+rate * +fine) ;
                var lbr_rs = (lbr_unt=='rs')?lbr_val:(+itm_intr_ttl * +lbr_val)/100;
                var dis_rs = (disc_unt=='rs')?disc_val:(+itm_intr_ttl * +disc_val)/100;
                var item_total = +itm_intr_ttl + +lbr_rs  + +dis_rs + +chrg + +other;
                $(document).find('.ttl ').eq(index).val((item_total.toFixed(2)).toString().replace(/\.0+$/, ''));
                $(document).find('.ttl').eq(index).trigger('input');
            }
        }
    });

    $(document).on('input','.lbr',function(){
        const index = $(this).closest('tr').index();
        const fine = $(document).find('.rate').eq(index).val()??false;
        if(!fine){
            $(this).val("");
            toastr.error('Fine Missing !');
            $(document).find('.fine').eq(index).focus().trigger('input');
        }
    });

    function calculatefine(index){
        const net = $(document).find('.net').eq(index).val()??false;
        const tunch = $(document).find('.tunch').eq(index).val()??false;
        const wstg = $(document).find('.wstg').eq(index).val()??false;
        if(net){
            if(wstg && tunch){
                const diff = 100 - (+tunch + +wstg);
                const fine = (net - (net * diff)/100).toFixed(3);
                $(document).find('.fine').eq(index).val(fine).trigger('input');;
            }else{
                $(document).find('.fine').eq(index).val(net).trigger('input');;
            }
        }
    }

</script>