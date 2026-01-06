
<div class="row ">
    <div class="col-md-4 form-group p-0">
        <label for="name">Product Name</label>
        <input type="hidden" name="stock_type" value="{{ $stock->item_type }}">
        <input type="text" class="form-control text-center" name="name" id="name" placeholder="Product Name" value="{{ $stock->product_name }}">
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="qunt">Quantity</label>
        <input type="text" class="form-control text-center " name="qunt" id="qunt" placeholder="Carat" value="{{ $stock->available }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount">Cost</label>
        <input type="text" class="form-control text-center" name="amount" id="amount" placeholder="Amount" value="{{ $stock->amount }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="rate">Sell Price/Unit</label>
        <input type="text" class="form-control text-center" name="rate" id="rate" placeholder="Sell Price" value="{{ $stock->rate }}" >
    </div>
</div>

