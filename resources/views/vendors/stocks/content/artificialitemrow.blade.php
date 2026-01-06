<table id="CsTable"class = "table table_theme table-responsive table-bordered artificial dataTable" >
    <thead class = "">
        <tr>
            <th> SN </th>
			<th>Collection</th>
            <th>Category</th>
            <th> Product Name </th>
            <th> Quantity </th>
            <th> Labour Charge </th>
            <th> Total Cost </th>
            <!--<th> BARCODE </th>
            <th> QRCODE </th>-->
            <th> RFID </th>
            <th> Sell Rate </th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
                <span class = "sn-number ">1</span>
                <button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>
            </td>
			<td>
                <select name="collections[artificial][]" class="tb_input artificial_product w-100"  placeholder="Select Collection" style="background:white;"  id="collections_1">
                    <option value="">Select</option>
                    @foreach (categories(2) as $category )
                    <option value = "{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="category[artificial][]" class="tb_input artificial_product w-100" id="" placeholder="Select Category" style="background:white;" id="category_1">
                    <option value="">Select</option>
                    @foreach (categories(3) as $category )
                    <option value = "{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="tb_input artificial_product w-120" name="product_name[artificial][]" id="productName_1" placeholder="Product Name"  > 
            </td>
            <td class="text-center"> 
                <input type="number"  class="tb_input calculate_item quantity artifical w-100" name="quantity[artificial][]" id="quantity_1" placeholder="Quantity"  min = "0" step = "any" value=""  > 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour artifical w-100" name="labour_charge[artificial][]" id="labourCharge_1" placeholder="Labour Charge" min = "0" step = "any" readonly value="0"   >
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical w-100" name="amount[artificial][]" id="amount_1" placeholder="Amount" min = "0" step = "any"   > 
            </td>
			<!--<td>
                <input type="text" class="tb_input barcode  artifical w-100" name="barcode[artificial][]" id="barcode_1" placeholder="BARCODE"  > 
            </td>
			<td>
                <input type="text" class="tb_input barcode  artifical w-100" name="qrcode[artificial][]" id="qrcode_1" placeholder="QRCODE"  > 
            </td>-->
            <td>
                <input type="text" class="tb_input rfid  artifical w-100" name="rfid[artificial][]" id="rfid_1" placeholder="RFID"  > 
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical w-100" name="rate[artificial][]" id="rate_1" placeholder="Rate/Unit" min = "0" step = "any"   > 
            </td>
        </tr>

    </tbody>
</table>
<!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" ><li class="fa fa-plus-circle"></li> Item </button> -->
<a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-sm btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>

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