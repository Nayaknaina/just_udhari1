<style>
select.is-invalid{
    border:1px solid red;
    border-radius:15px;
}
</style>
<table id="CsTable"class = "table table_theme table-responsive table-bordered dataTable  stone" >
    <thead >
        <tr>
            <th> SN </th>
            <th>Category</th>
            <th>Product Name </th>
            <th> Caret </th>
            <th> Weight(GM) </th>
            <th> CODE </th>
            <th> BARCODE </th>
            <th style="width:100px;"> QRCODE </th>
            <th style="width:100px;"> RFID </th>
            <th> Price </th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
                <span class = "sn-number ">1</span>
                <button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>
            </td>
            <td>
                <select name="category[]" class="tb_input w-100" id="category_1" placeholder="Select Category" style="background:white;" >
                    <option value="">Select</option>
                    @foreach (stonecategory() as $category )
                    <option value = "{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="tb_input w-120" name="product_name[]" id="product_name_1" placeholder="Product Name"  > 
            </td>
            <td>
                <input type="text" class="tb_input w-120" name="caret[]" id="caret_1" placeholder="Caret"  > 
            </td>
            <td class="text-center"> 
                <input type="number"  class="tb_input  weight stone w-100" name="weight[]" id="weight_1" placeholder="Weight"  min = "0" step = "any" value=""  > 
            </td>
            <td>
                <input type="text" class="tb_input code  stone w-100" name="code[]" id="code_1" placeholder="SN/Code"  > 
            </td>
            <td>
                <input type="text" class="tb_input barcode  stone w-100" name="barcode[]" id="barcode_1" placeholder="BARCODE"  > 
            </td>
            <td>
                <input type="text" class="tb_input qrcode  stone w-100" name="qrcode[]" id="qrcode_1" placeholder="QR Code"  > 
            </td>
            <td>
                <input type="number" class="tb_input rfid  stone w-100" name="rfid[]" id="rfid_1" placeholder="RFID"  > 
            </td>
            <td> 
                <input type="number" class="tb_input amount  stone w-100" name="rate[]" id="rate_1" placeholder="Sell Rate" min = "0" step = "any"   > 
            </td>
        </tr>

    </tbody>
</table>
<!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" ><li class="fa fa-plus-circle"></li> Item </button> -->
<a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>

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