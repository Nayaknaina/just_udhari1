/*$('select.my_select').each(function(i,v){
    var $select = $(v);
    var $hiddenInput = $("<input type='hidden'>");
    var $textInput = $("<input type='text'>");
    var $listul = `<ul id="${$(this).attr('id')+'_list'}" style="display:none;position:absolute;margin: 0;top:100%;background:white;z-index:3;width:100%;padding:0;border:1px solid lightgray;list-style:none;box-shadow:1px 2px 3px gray;"></ul>`;
    $.each($select[0].attributes, function(index, attr) {
        $hiddenInput.attr(attr.name, attr.value);
        $textInput.attr(attr.name, attr.value+"_input");
    });
    $textInput.attr('placeholder',$(v).find('option:selected').text());
    $select.prop('disabled',true).hide();
    $select.parent().append($hiddenInput);
    $select.parent().append($textInput);
    $select.parent().append($listul);
    $textInput.on('input',myselectoptions);
});*/
let create = true;
$.fn.myselect96 = function (create) {
    return this.each(function(i,v){
        var $select = $(v);
        var $hiddenInput = $("<input type='hidden'>");
        var $textInput = $("<input type='text'>");
        var $listul = `<ul id="${$(this).attr('id')+'_list'}" style="display:none;position:absolute;margin: 0;top:100%;background:white;z-index:3;width:100%;padding:0;border:1px solid lightgray;list-style:none;box-shadow:1px 2px 3px gray;"></ul>`;
        $.each($select[0].attributes, function(index, attr) {
            $hiddenInput.attr(attr.name, attr.value);
            $textInput.attr(attr.name, attr.value+"_input");
        });
        $textInput.attr('placeholder',$(v).find('option:selected').text());
        $select.prop('disabled',true).hide();
        $select.after($hiddenInput, $textInput, $listul);
        //$([$hiddenInput,$textInput,$listul]).insertAfter($select);
        // $select.parent().append($hiddenInput);
        // $select.parent().append($textInput);
        // $select.parent().append($listul);
        $textInput.on('input', function() {
            myselectoptions.call(this,create);
        });
        //$textInput.on('input',myselectoptions(create));
    });
}


function myselectoptions(create){
    var $triggeredInput = $(this);
    var keyword =  $triggeredInput.val()??'';
    const select_input_id = $triggeredInput.attr('id').replace(/_input$/, '');
    if(keyword){
        var li = '';
        $('select#'+select_input_id+'> option').each(function(i,option){
            const val = $(option).val();
            if(val!=""){
                const text = $(option).text().toLowerCase();
                const source = ($(option).data('source'))?String($(option).data('source')):'';
                const target = (($(option).data('source')))?$(option).data('target'):false;
                if (text.startsWith(keyword.toLowerCase()) || (source && source.startsWith(keyword))) {
                    li+=`<li><a href="javascript:void(null);" data-target="${val}" data-label="${text}" `;
                    if(target){
                        li+=`data-target-value="${source}" data-target-field="${target}" `;
                    }
                    const source_data = (source!='')?`- ${source}`:'';
                    li+=`class="my_select_option" onmouseover="this.style.fontWeight='bold'; this.style.background='#f2f2f2';" onmouseout="this.style.background='unset'; this.style.fontWeight='unset';" style="padding:2px;display:block;">${text}${source_data}</li>`;
                }
            }
        });
        if(create){
            if(li==''){
                const target = $('select#'+select_input_id+'> option:first').data('target');
                li+=`<li><a href="javascript:void(null);" data-target="${keyword}" data-label="${keyword}"  data-target-value="" data-target-field="${target}" class="my_select_option" onmouseover="this.style.fontWeight='bold'; this.style.background='#f2f2f2';" onmouseout="this.style.background='unset'; this.style.fontWeight='unset';" style="padding:2px;display:block;">Create -${keyword}</li>`;
            }
        }else{
            if(li==''){
                li+=`<li><a href="javascript:void(null);"  data-target-value="" data-target-field="" class="my_select_option" onmouseover="this.style.fontWeight='bold'; this.style.background='#f2f2f2';" onmouseout="this.style.background='unset'; this.style.fontWeight='unset';" style="padding:2px;display:block;">No Record !</li>`;
            }
        }
        $(document).find("#"+select_input_id+"_list").empty().append(li).show();
    }else{
        $(document).find(`input#${select_input_id}`).val();
        $(document).find(`input#${select_input_id}_input`).val();
        $(document).find("#"+select_input_id+"_list").empty().hide();
    }
}
$(document).on('click','.my_select_option',function(){
    const $listparentul = $(this).closest('ul');
    const $targetid =  $listparentul.attr('id').replace(/_list$/, '');
    const target_val = $(this).data('target');
    $(document).find(`input#${$targetid}`).val(target_val);
    $(document).find(`select#${$targetid}`).val(target_val);
    const target_label = $(this).data('label');
    $(document).find(`input#${$targetid}_input`).val(target_label);
    $listparentul.hide();
    const value = $(this).data('target-value')??'';
    const target = $(this).data('target-field')??false;
    if(target){
        $(target).val(value);
        if(value==""){
            $(target).prop('readonly',false);
        }
    }
});

$.fn.redraw = function (create) {
    return this.each(function () {
        const $select = $(this);
        const id = $select.attr('id');
        $(document).find(`input[type="hidden"]#${id},input[type="text"]#${id}_input`).remove();
        // $(document).find('input[type="hidden"].my_select').remove();
        // $(document).find('input[type="text"].my_select_input').remove();
        $(`select#${id}`).show().prop('disabled',false);
        $(`select#${id}`).myselect96(create);
    });
};