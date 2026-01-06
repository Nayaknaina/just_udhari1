<table class = "table table-responsive table-bordered genuine" id="h">
    <thead class = "bg-info">
        <tr>
            <th> SN </th>
            <th> Product Name </th>
            <th> Carat </th>
            <th> Purity(%)</th>
            <th> Gross Weight </th>
            <th> Net Weight </th>
            <th> Wastage(%) </th>
            <th> Fine Purity(%)</th>
            <th> Fine Weight</th>
            <th> Charge/Grm </th>
            <th> Amount </th>
            <th><span style="border: 1px solid white;padding: 0 3px;">Stone</span></th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
                <span class = "sn-number ">1</span>
                <button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>
            </td>
            <td>
                <input type="text" class="tb_input product" name="product_name[{{ $sn  }}][]" id="productName_1" placeholder="Product Name"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item caret" name="carat[{{ $sn  }}][]" id="carat_1" placeholder="Carat" min = "0" step = "any" style="width:70px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item purity" name="purity[{{ $sn  }}][]" id="purity_1" placeholder="Purity" min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item gross_weight" name="gross_weight[{{ $sn  }}][]" id="grossWeight_1 floatInput" placeholder="Gross Weight "   min = "0" step = "0.001" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item net_weight" name="net_weight[{{ $sn  }}][]" id="netWeight_1" placeholder="Net Weight"   min = "0" step = "any"  style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item waste" name="wastage[{{ $sn  }}][]" id="wastage_1" placeholder="Wastage"  min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input fine_pure" name="fine_purity[{{ $sn  }}][]" id="finePurity_1" placeholder="Fine Purity"  min = "0" step = "any" readonly style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input fine_weight" name="fine_weight[{{ $sn  }}][]" id="fineWeight_1" placeholder="Fine Weight"  min = "0" step = "any" readonly style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour" name="labour_charge[{{ $sn  }}][]" id="labourCharge_1" placeholder="Labour Charge"  min = "0" step = "any" style="width:80px;"> 
            </td>
            <td> 
                <input type="number" class="tb_input amount" name="amount[{{ $sn  }}][]" id="amount_1" placeholder="Amount" min = "0" step = "any" readonly style="width:80px;"> 
            </td>
            <td class="text-center">
                <a href="{{ route('stocks.associated') }}" class="btn btn-sm btn-info assoc_plus">&plus;</a>
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