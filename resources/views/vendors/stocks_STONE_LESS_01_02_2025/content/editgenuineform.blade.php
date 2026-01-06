<div class="row main_bill_block stock_block my-2" id="main_bill_block">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="metals">Metals</label>
            <select name="metals" class="form-control select2" id="metals" placeholder="Select Metal">
                <option value="">Select</option>
                @foreach (categories(1) as $category)
                    <option value="{{ $category->id }}"  {{ ($stock->categories->contains($category->id))?'selected':'' }} >{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="rate">Rate</label>
            <input type="number" class="form-control calculate_item rate" id="rate" name="rate" min="1" step="any" placeholder="Enter Rate" value="{{  $stock->rate  }}" >
        </div>
    </div>


    <div class="col-lg-3">
        <div class="form-group">
            <label for="collections">Collections</label>
            <select name="collections" class="form-control select2" id="collections" placeholder="Select Collection">
                <option value="">Select</option>
                @foreach (categories(2) as $category)
                    <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}
                    >{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" class="form-control select2" id="category" placeholder="Select Category">
                <option value="">Select</option>
                @foreach (categories(3) as $category)
                    <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<hr class="col-12  p-0">
<div class="row text-center">
    @php 
    $property = json_decode($stock->property,true);
    @endphp
    <div class="col-md-4 form-group p-0">
        <label for="name">Product Name</label>
        <input type="hidden" name="stock_type" value="{{ $stock->item_type }}">
        <input type="text" class="form-control text-center" name="name" id="name" placeholder="Product Name" value="{{ $stock->product_name }}">
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="caret">Carat</label>
        <input type="text" class="form-control text-center calculate" name="caret" id="caret" placeholder="Carat" value="{{ $stock->caret }}" >
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="purity">Purity %</label>
        <input type="text" class="form-control text-center calculate" name="purity" id="purity" placeholder="Purity(Grm)" value="{{ $property['purity'] }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="grs_wgt">Gross Weight</label>
        <input type="text" class="form-control text-center calculate" name="grs_wgt" id="grs_wgt" placeholder="Gross Weight(Grm)" value="{{ $property['gross_weight'] }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="net_wgt">Net Weight</label>
        <input type="text" class="form-control text-center calculate" name="net_wgt" id="net_wgt" placeholder="Net Weight(Grm)" value="{{ $stock->available }}" >
    </div>

    <div class="col-md-2 form-group p-0">
        <label for="wstg">Wastage %</label>
        <input type="text" class="form-control text-center calculate" name="wstg" id="wstg" placeholder="Wastage(%)" value="{{ $property['wastage'] }}"  >
    </div>

    <div class="col-md-2 form-group p-0">
        <label for="fine_purity">Fine Purity(%)</label>
        <input type="text" class="form-control text-center" name="fine_purity" id="fine_purity" placeholder="Fine Purity(%)" value="{{ $property['fine_purity'] }}" readonly>
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="fine_wgt">Fine Weight</label>
        <input type="text" class="form-control text-center" name="fine_wgt" id="fine_wgt" placeholder="File Weght (Grm)" value="{{ $property['fine_weight'] }}" readonly>
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="chrg">Charge/Grm</label>
        <input type="text" class="form-control text-center calculate" name="chrg" id="chrg" placeholder="Charge / Grm" value="{{ $stock->labour_charge }}" >
    </div>
    <div class="col-md-3 form-group p-0">
        <label for="amount">Amount</label>
        <input type="text" class="form-control text-center" name="amount" id="amount" placeholder="Amount" value="{{ $stock->amount }}" readonly>
    </div>
</div>

