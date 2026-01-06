<table class = "table table-responsive table-bordered genuine" id="h">
    <thead class = "bg-info">
        <tr>
            <th> SN </th>
            <th> Product Name </th>
            @if(isset($form_name) && $form_name!='loose')
            <th> Quantity </th>
            @endif
            <th> Carat </th>
            <th> Gross Weight </th>
            <th> Net Weight </th>
            <th> Purity </th>
            <th> Wastage </th>
            <th> Fine Purity </th>
            <th> Fine Weight </th>
            <th> Labour Charge </th>
            <th> Amount </th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
                <span class = "sn-number ">1</span>
                <button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>
            </td>
            <td>
                <input type="text" class="tb_input product" name="product_name[0][]" id="productName_1" placeholder="Product Name"> 
            </td>
            @if(isset($form_name) && $form_name!='loose')
            <td class="text-center"> 
                <input type="number"  class="tb_input calculate_item quantity" name="quantity[0][]" id="quantity_1" placeholder="Quantity" min = "0" step = "any" value="1" readonly style="width:50px;"> 
            </td>
            @endif
            <td> 
                <input type="number" class="tb_input calculate_item caret" name="carat[0][]" id="carat_1" placeholder="Carat" min = "0" step = "any" style="width:70px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item gross_weight" name="gross_weight[0][]" id="grossWeight_1 floatInput" placeholder="Gross Weight "   min = "0" step = "0.001" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item net_weight" name="net_weight[0][]" id="netWeight_1" placeholder="Net Weight"   min = "0" step = "any"  style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item purity" name="purity[0][]" id="purity_1" placeholder="Purity" min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item waste" name="wastage[0][]" id="wastage_1" placeholder="Wastage"  min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input fine_pure" name="fine_purity[0][]" id="finePurity_1" placeholder="Fine Purity"  min = "0" step = "any" readonly style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input fine_weight" name="fine_weight[0][]" id="fineWeight_1" placeholder="Fine Weight"  min = "0" step = "any" readonly style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour" name="labour_charge[0][]" id="labourCharge_1" placeholder="Labour Charge"  min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input amount" name="amount[0][]" id="amount_1" placeholder="Amount" min = "0" step = "any" readonly style="width:80px;"> 
            </td>
        </tr>

    </tbody>
</table>
<!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" ><li class="fa fa-plus-circle"></li> Item </button> -->
<a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>
<a href="#main_bill_block" class="btn btn-sm btn-outline-primary stock_block_add" style="float:right;"  id="block_head"><li  class="fa fa-plus-circle"></li> Block</a>
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
-moz-appearance: textfield;
}
</style>