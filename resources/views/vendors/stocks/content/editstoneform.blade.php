
<div class="row stock_block ">
    <div class="col-md-4 form-group p-0">
        <label for="name">Product Name</label>
        <input type="text" class="form-control text-center" name="name" id="name" placeholder="Product Name" value="{{ $stock->product_name }}">
    </div>
    <div class="col-md-4 form-group p-0">
        <label for="name">Category</label>
        <select name="category" class="form-control">
            <option value="">Select</option>
            @foreach (categories(2) as $category)
                <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}
                >{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="qunt">Caret</label>
        <input type="text" class="form-control text-center " name="caret" id="caret" placeholder="Carat" value="{{ $stock->available }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount">Cost <small style="color:blue;">Amount</small></label>
        <input type="text" class="form-control text-center" name="amount" id="amount" placeholder="Amount" value="{{ $stock->amount }}" >
    </div>
</div>
<hr>
<div class="row">
    <input type="hidden" name="stock_type" value="{{ $stock->item_type }}">
    <div class="col-md-3 form-group p-0">
        <label for="amount" class="hight-light-lebel">CODE<small style="color:blue;">custom</small></label>
        <input type="text" class="form-control text-center  hight-light-field" name="code" id="code" placeholder="BARCODE" value="{{ $stock->product_code }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount" class="hight-light-lebel">BARCODE</label>
        <input type="text" class="form-control text-center  hight-light-field" name="barcode" id="barcode" placeholder="BARCODE" value="{{ $stock->barcode }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount" class="hight-light-lebel">QRCODE</label>
        <input type="text" class="form-control text-center  hight-light-field" name="qrcode" id="qrcode" placeholder="BARCODE" value="{{ $stock->qrcode }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount" class="hight-light-lebel">RFID</label>
        <input type="text" class="form-control text-center hight-light-field" name="rfid" id="rfid" placeholder="RFID" value="{{ $stock->rfid }}" >
    </div>
</div>
<style>
    .hight-light-field{
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
    }
    .hight-light-lebel{
        text-shadow: 1px 2px 1px gray;
    }
</style>
