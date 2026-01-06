<table class = "table table_theme table-responsive table-bordered artificial dataTable pb-2" >
    <thead class = "">
        <tr>
            <th> SN </th>
			<th>Collection</th>
            <th>Category</th>
            <th> Product Name </th>
            <th> Quantity </th>
            <th> Labour Charge </th>
            <th> Total Cost </th>
            <th> BARCODE </th>
            <th> RFID </th>
            <th> Sell Rate </th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        @if($purchase->stocks->count()>0)
        @foreach($purchase->stocks as $artk=>$artv)
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
            <input type="hidden"class="stock_ids" name="stock_id[artificial][]" value="{{ $artv->id }}" id="stock_id_bndl">
            <span class = "sn-number ">{{ $artk+1 }}</span>
            <a href="{{-- route('purchases.delete',$artv->id) --}}" class = "btn btn-danger btn-sm btn-delete tr_del_btn"> X </a>
            </td>
			<td>
                <select name="collections[]" class="tb_input artificial_product"  placeholder="Select Collection" style="background:white;"  id="collections_1">
                    <option value="">Select</option>
                    @foreach (categories(2) as $category )
                    <option value = "{{ $category->id }}" {{ ($artv->categories->contains($category->id))?'selected':'' }} >{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="category[]" class="tb_input artificial_product" id="" placeholder="Select Category" style="background:white;" id="category_1">
                    <option value="">Select</option>
                    @foreach (categories(3) as $category )
                    <option value = "{{ $category->id }}" {{ ($artv->categories->contains($category->id))?'selected':'' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="tb_input artificial_product" name="product_name[artificial][]" id="productName_1" placeholder="Product Name"  value="{{ $artv->name }}"> 
            </td>
            <td class="text-center"> 
                <input type="number"  class="tb_input calculate_item quantity artifical w-100" name="quantity[artificial][]" id="quantity_1" placeholder="Quantity"  min = "0" step = "any" value="{{ $artv->quantity }}"  > 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour artifical" name="labour_charge[artificial][]" id="labourCharge_1" placeholder="Labour Charge" min = "0" step = "any" readonly value="{{ $artv->labour_charge }}"   >
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical" name="amount[artificial][]" id="amount_1" placeholder="Amount" min = "0" step = "any" value="{{ $artv->amount }}"   > 
            </td>
            <td> 
                <input type="text" class="tb_input artifical" name="barcode[artificial][]" id="amount_1" placeholder="Barcode" value="{{ $artv->barcode }}"   > 
            </td>
            <td> 
                <input type="text" class="tb_input artifical" name="rfid[artificial][]" id="rfid_1" placeholder="RFID"  value="{{ $artv->rfid }}"   > 
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical" name="rate[artificial][]" id="rate_1" placeholder="Rate/Unit" min = "0" step = "any"  value="{{ $artv->rate }}" > 
            </td>
        </tr>
        @endforeach
        @else 
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
                <span class = "sn-number ">1</span>
                <button type = "button" class = "btn btn-danger btn-sm btn-delete tr_del_btn" style="display:none;">X</button>
            </td>
            <td>
                <input type="text" class="tb_input artificial_product" name="product_name[artificial][]" id="productName_1" placeholder="Product Name"  > 
            </td>
            <td class="text-center"> 
                <input type="number"  class="tb_input calculate_item quantity artifical" name="quantity[artificial][]" id="quantity_1" placeholder="Quantity"  min = "0" step = "any" value=""  > 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour artifical" name="labour_charge[artificial][]" id="labourCharge_1" placeholder="Labour Charge" min = "0" step = "any" readonly value="0"   >
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical" name="amount[artificial][]" id="amount_1" placeholder="Amount" min = "0" step = "any"   > 
            </td>
        </tr>
        @endif
    </tbody>
</table>
<!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" ><li class="fa fa-plus-circle"></li> Item </button> -->
<a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>