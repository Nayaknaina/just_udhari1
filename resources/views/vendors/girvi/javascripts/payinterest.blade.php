 <script>
   $("#int_girvi_summery_show").click(function(e){
        e.preventDefault();
        $("#int_girvi_summery").toggle('slow');
        $(this).toggleClass('on');
   });
   $(document).on('click','.item_select',function(){
     $('.pay_item_row').removeClass('selected');
     var item_principal = 0;
     var item_interest = 0;
     $($(document).find('.item_select')).each(function(tri,trv){
          if($(trv).is(':checked')){
               let self_tr = $(this).closest('tr');
               item_principal+= +self_tr.find('td > span.item_principal').text();
               item_interest+= +self_tr.find('span.item_interest').text();
               self_tr.addClass('selected');
          }
     });
     $("#desire_principal").val(item_principal);
     $("#desire_interest").val(item_interest);
     $("#pay_interest").val(item_interest);
   });

   $(document).on('click','.return_item',function(e){
     e.preventDefault();
     $.get($(this).attr('href'),"",function(response){
          if(response.status){
               success_sweettoatr(response.msg);
               location.reload();
          }else{
               toastr.error(response.msg);
          }
     });
   });
</script>