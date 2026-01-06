<tr class="py-0 element_tr element_tr_{{ $input_num  }}">
    <td class="text-right">E&#10148;</td>
    <td colspan="10" class="py-0 element_param">
        <div class="form-inline element_div" >
            <input type="hidden" name="assoc_id[{{ $block_num-1 }}][{{ $input_num }}][]" value="{{ $element->id }}">
            <input type="text" name="assoc[{{ $block_num-1 }}][{{ $input_num }}][metal][]" value="{{ $element->name }}" class="form-control col-md-5 p-2 tb_input element_name" placeholder="Element Name">
            <input type="text" name="assoc[{{ $block_num-1 }}][{{ $input_num }}][caret][]" value="{{ $element->caret }}" class="form-control  col-md-2 p-2 tb_input element_caret" placeholder="Caret">
            <input type="text" name="assoc[{{ $block_num-1 }}][{{ $input_num }}][quant][]" value="{{ $element->quant }}" class="form-control  col-md-2 p-2 tb_input element_quant" placeholder="Quantity">
            
            <input type="text" name="assoc[{{ $block_num-1 }}][{{ $input_num }}][cost][]" value="{{ $element->cost }}" class="form-control  col-md-3 p-2 tb_input element_cost" placeholder="Total Cost (Rs)" >
        </div>
    </td>
    <td class="text-center py-0">
        <label class="del_ele btn btn-sm btn-outline-danger btn-delete" for="ele_{{ $element->id }}">
            <input type="checkbox" class="del_ele_check" name="del_ele[]" value="{{ $element->id }}" style="display:none;" id="ele_{{ $element->id }}" >
            &cross;
        </label>
        <!-- <a href="javascript:void(null);"  class="btn btn-sm btn-outline-danger assoc_del_btn">x</a> -->
    </td>
</tr>