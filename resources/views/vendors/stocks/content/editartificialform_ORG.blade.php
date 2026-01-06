
<div class="row ">
    <input type="hidden" name="stock_type" value="{{ $stock->item_type }}">
    <div class="col-md-2 form-group p-0">
        <label for="name">Collection</label>
        <select name="collections" class="form-control">
            <option value="">Select</option>
            @foreach (categories(2) as $category)
                <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}
                >{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="name">Category</label>
        <select name="category" class="form-control">
            <option value="">Select</option>
            @foreach (categories(3) as $category)
                <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}
                >{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="name">Product Name</label>
        <input type="text" class="form-control text-center" name="name" id="name" placeholder="Product Name" value="{{ $stock->product_name }}">
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="qunt">Quantity</label>
        <input type="text" class="form-control text-center " name="qunt" id="qunt" placeholder="Carat" value="{{ $stock->available }}" >
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="amount">Cost <small style="color:blue;">Total</small></label>
        <input type="text" class="form-control text-center" name="amount" id="amount" placeholder="Amount" value="{{ $stock->amount }}" >
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="rate">Sell Price <small style="color:blue;">/Unit</small></label>
        <input type="text" class="form-control text-center" name="rate" id="rate" placeholder="Sell Price" value="{{ $stock->rate }}" >
    </div>
</div>

