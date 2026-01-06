<tr class="py-0 element_tr element_tr_{{ $input_arr['input'] }}">
    <td class="text-right">E&#10148;</td>
    <td colspan="{{ ($input_arr['input']=='loose')?9:11 }}" class="py-0 element_param">  
        <div class="form-inline element_div" >
            <input type="text" name="assoc[{{ $input_arr['block'] }}][{{ $input_arr['input'] }}][metal][]" value="" class="form-control col-md-5 p-2 tb_input element_name" placeholder="Element Name">
            <input type="text" name="assoc[{{ $input_arr['block'] }}][{{ $input_arr['input'] }}][caret][]" value="" class="form-control  col-md-2 p-2 tb_input element_caret" placeholder="Caret">
            <input type="text" name="assoc[{{ $input_arr['block'] }}][{{ $input_arr['input'] }}][quant][]" value="" class="form-control  col-md-2 p-2 tb_input element_quant" placeholder="Quantity">
            
            <input type="text" name="assoc[{{ $input_arr['block'] }}][{{ $input_arr['input'] }}][cost][]" value="" class="form-control  col-md-3 p-2 tb_input element_cost" placeholder="Total Cost (Rs)" >
        </div>
    </td>
    <td class="text-center py-0">
        <a href="javascript:void(null);"  class="btn btn-sm btn-outline-danger assoc_del_btn btn-delete">x</a>
    </td>
</tr>