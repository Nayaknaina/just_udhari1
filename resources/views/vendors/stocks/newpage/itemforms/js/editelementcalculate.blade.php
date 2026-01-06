<script>
   $(document).on('input','.ele_name',function(){
      const tr = checkelementname($(this));
   });

    $(document).on('input','.ele_caret',function(){
        const tr = checkelementname($(this));
        //alert(index);

        const item_ele = tr.find('.ele_name').val()??false;
        //alert(item_ele);
         const caret = tr.find('.ele_caret').val()??false;
         if(caret){
               const tunch = Math.round((100/24)*caret);
               tr.find('.ele_tunch').val(tunch);
         }else{
               tr.find('.ele_tunch').val('');
         }
         calculateelementfine(tr);
    });

      $(document).on('input','.ele_gross',function(){
         const tr = checkelementname($(this));
         const gross = $(this).val()??false;
         const less = tr.find('.ele_less').val()??0;
         if(gross){
               if(gross =='' || gross==0){
                  tr.find('.ele_less,.ele_net,.ele_fine').val('');
                  return false;
               }else{
                  if(+less > +gross){
                     toastr.error("Less Can't be Grester to Gross !");
                     return false;
                  }else{
                     const net = gross - less;

                     if(+net < 0 || +net > gross){
                        toastr.error("Invalid Gross or Less !");
                        return false;
                     }else{
                        tr.find('.ele_net').val(net);
                     }
                  }
               }
         }else{
               tr.find('.ele_net').val('');
         }
         calculateelementfine(tr);
      })

    $(document).on('input','.ele_less',function(){
        const tr = checkelementname($(this));
        const gross = tr.find('.ele_gross').val()??false;
        if(!gross){
            $(this).val('');
            tr.find('.ele_gross').focus();
            toastr.error("Gross Weight required !");
        }else{
            var  less = $(this).val()??fasle;
            if(less){
                if(+gross < +less){
                    less = less.slice(0,-1);
                    toastr.error("Less can't Greater to Gross !");
                }
                const net = +gross - +less;
                tr.find('.ele_net').val(+net);
            }else{
                tr.find('.ele_net').val(gross);
            }
            calculateelementfine(tr);
        }
    });

    $(document).on('input','.ele_tunch',function(){
        const tr = checkelementname($(this));
        const net = tr.find('.ele_net').val()??false;
        if(!net){
            $(this).val('');
            tr.find('.ele_net').focus();
            toastr.error("Net Weight required !");
        }else{
            const tunch = $(this).val()??false;
            if(tunch){
                if(tunch>100){
                    $(this).val(tunch.slice(0,-1));
                }
            }
            tr.find('.ele_caret').val('');
        }
        calculateelementfine(tr);
    });

    $(document).on('input','.ele_wstg',function(){
        const tr = checkelementname($(this));
        const tunch = tr.find('.ele_tunch').val()??false;
        if(!tunch){
            $(this).val('');
            $tr.find('.ele_tunch').focus();
            toastr.error("Tunch required !");;
        }else{
            const net = tr.find('.ele_net').val()??false;
            var  wstg = $(this).val()??fasle;
            if(wstg){
                if(wstg > 100){
                    wstg = wstg.slide(0,-1);
                    toastr.error("Wastage can't Greater to 100 !");
                }
                calculateelementfine(tr);
            }
        }
    });

    $(document).on('input','.ele_cost,.ele_rate',function(){
        const tr = checkelementname($(this));
        const ele_name = tr.find('.ele_name').val()??false;
        if(!ele_name){
            $(this).val('');
            toastr.error("Element Name required !");
            tr.find('.ele_name').focus();
        }
        if($(this).hasClass('ele_rate')){
            const rate = $(this).val()??false;
            if(rate){
                var fine = tr.find('.ele_fine').val()??false;
                if(fine){
                    const cost = (fine * rate).toFixed(2).toString().replace(/\.0+$/, '');
                    tr.find('.ele_cost').val(cost);
                }else{
                    $(this).val('');
                    toastr.error("Fine Weight Required !");
                }
            }else{
                tr.find('.ele_cost').val('');
            }
        }
    });

    function calculateelementfine(tr){
        const net = tr.find('.ele_net').val()??false;
        const tunch = tr.find('.ele_tunch').val()??false;
        var wstg = tr.find('.ele_wstg').val()??false;
        if(net){
            if(wstg && tunch){
                if((+wstg + +tunch) > 100){
                    tr.find('.ele_wstg').val('');
                    toastr.error(`Invalid Wastage that is ${wstg}!`);
                    wstg = 0;
                }
                const diff = 100 - (+tunch + +wstg);
                const fine = ((net - (net * diff)/100).toFixed(3)).toString().replace(/\.0+$/, '');
                tr.find('.ele_fine').val(fine).trigger('input');;
            }else{
                tr.find('.ele_fine').val(net).trigger('input');;
            }
        }
    }

   $('.add_assos_element').click(function(){
      const ele_body = $("#"+$(this).data('target'));
      const seq = $(this).data('target').replace("ele_area_", "");
      const ttl_tr_count = $(document).find(`tr.item_ele_${seq}`).length;
      const tr = `<tr class="item_ele item_ele_${seq}">
                     <td>
                         ${ttl_tr_count +1 }
                     </td>
                     <td>
                        <input type="text" name="ele_name[${seq}][]" class="form-control no-border ele_name" value="" required>
                     </td>
                     <td>
                        <select name="ele_caret[${seq}][]" class="form-control no-border ele_caret">
                           <option value="">__?</option>
                           <option value="18">18K</option>
                           <option value="20">20K</option>
                           <option value="22">22K</option>
                           <option value="24">24K</option>
                        </select>
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_part" name="ele_part[${seq}][]" value="">
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_colour" name="ele_colour[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_piece" name="ele_piece[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_clear" name="ele_clear[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_gross" name="ele_gross[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_less" name="ele_less[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_net" name="ele_net[${seq}][]" value="" readonly>     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_tunch" name="ele_tunch[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_wstg" name="ele_wstg[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_fine" name="ele_fine[${seq}][]" value="" readonly>     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_rate" name="ele_rate[${seq}][]" value="">     
                     </td>
                     <td>
                        <input type="text" class="form-control no-border ele_cost" name="ele_cost[${seq}][]" value="">     
                     </td>
                     <td class="text-danger text-center">
                        <button class="btn btn-outline-danger remove_ele_row p-0 m-0" style="line-height:1;padding:0 2px;" data-index="${seq}">&#10005;</button
                     </td>
                  </tr>`;
      ele_body.append(tr);
      $(`#ele_tr_${seq}`).show();
   });

   $('.ele_del_check').change(function(){
      $(this).closest('label').toggleClass('checked');
      $(this).closest('tr.item_ele').toggleClass('to-delete');
   });

   $(document).on('click','.remove_ele_row',function(){
      const seq = $(this).data('index');
      $(this).closest('tr.item_ele').remove();
      const ttl_tr_count = $(document).find(`tr.item_ele_${seq}`).length;
      if(ttl_tr_count != 0){
         $(`tr.item_ele_${seq}`).each(function(i,v){
            $(this).find('td:first-child').html(i+1);
         });
      }else{
         $(`#ele_tr_${seq}`).hide();
      }
   });

   function checkelementname(element){
      const tr_nw = $(element).closest('tr');
      const name_field = tr_nw.find('.ele_name');
      const val = $(name_field).val()??'';
      if(val==''){
         tr_nw.find('td').each(function(i,v){
            $(this).find('input,select').val('');
         });
         toastr.error("Please Fill The Element Name !");
         name_field.focus();
      }else{
         return tr_nw;
      }
   }
</script>
