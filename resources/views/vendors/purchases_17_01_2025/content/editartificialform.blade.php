<table class = "table table-responsive table-bordered artificial" >
    <thead class = "bg-info">
        <tr>
            <th> SN </th>
            <th> Product Name </th>
            <th> Quantity </th>
            <th> Labour Charge </th>
            <th> Total Cost </th>
        </tr>
    </thead>
    <tbody id="tableBody" class="item_tbody">
        @if($purchase->stocks->count()>0)
        @foreach($purchase->stocks as $artk=>$artv)
        <tr id="main_item_tr_1" class="main_item_tr">
            <td class="sn-box">
            <input type="hidden"class="stock_ids" name="stock_id[artificial][]" value="{{ $artv->id }}" id="stock_id_bndl">
            <span class = "sn-number ">{{ $artk+1 }}</span>
            <a href="{{ route('purchases.delete',$artv->id) }}" class = "btn btn-danger btn-sm btn-delete tr_del_btn"> X </a>
            </td>
            <td>
                <input type="text" class="tb_input artificial_product" name="product_name[artificial][]" id="productName_1" placeholder="Product Name"  value="{{ $artv->name }}"> 
            </td>
            <td class="text-center"> 
                <input type="number"  class="tb_input calculate_item quantity artifical" name="quantity[artificial][]" id="quantity_1" placeholder="Quantity"  min = "0" step = "any" value="{{ $artv->quantity }}"  > 
            </td>
            <td> 
                <input type="number" class="tb_input calculate_item labour artifical" name="labour_charge[artificial][]" id="labourCharge_1" placeholder="Labour Charge" min = "0" step = "any" readonly value="{{ $artv->labour_charge }}"   >
            </td>
            <td> 
                <input type="number" class="tb_input amount calculate_item artifical" name="amount[artificial][]" id="amount_1" placeholder="Amount" min = "0" step = "any" value="{{ $artv->amount }}"   > 
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