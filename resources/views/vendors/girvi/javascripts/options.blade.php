<script>
$(document).on('click','.option_item_list_button',function(e){
    e.preventDefault();
    $($(this).attr('href')).toggle();
    $('.option_item_list_button').find('i').toggleClass('fa-caret-up fa-caret-down');
});

$(document).on('click','.girvioperationbtn',function(){
    $("#operation_table").find('tr').removeClass('selected');
    $(this).closest('tr').addClass('selected');
    let modal = $(this).data('target');
    let head = modal+"head";
    let body = modal+"body";
    $(head).html($(this).attr('title'));
    $(body).load($(this).data('href'),"",function(){
        $(document).find('#ir_type').html($('#category').html());
         $(modal).one('hidden.bs.modal', function() {
            $(modal).find('modal-dialog').removeClass('modal-sm');
            $(head).html("Title");
            $(body).html("...!");
        });
    });
});
</script>

