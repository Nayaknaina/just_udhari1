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
    <div class="col-md-{{ ($stock->item_type=='genuine')?3:4 }} form-group p-0">
        <label for="name">Product Name</label>
        <input type="hidden" name="stock_type" value="{{ $stock->item_type }}">
        <input type="text" class="form-control text-center" name="name" id="name" placeholder="Product Name" value="{{ $stock->product_name }}">
    </div>
	@if($stock->item_type=='genuine')
	 <div class="col-md-1 form-group p-0">
        <label for="amount">HUID</label>
        <input type="text" class="form-control text-center" name="huid" id="huid" placeholder="HUID" value="{{ $stock->huid }}" >
    </div>
	@endif
    <div class="col-md-1 form-group p-0">
        <label for="caret">Carat</label>
        <input type="text" class="form-control text-center calculate" name="caret" id="caret" placeholder="Carat" value="{{ $stock->caret }}" >
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="purity">Purity %</label>
        <input type="text" class="form-control text-center calculate" name="purity" id="purity" placeholder="Purity(Grm)" value="{{ $property['purity'] }}" >
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="grs_wgt">Gross Weight</label>
        <input type="text" class="form-control text-center calculate" name="grs_wgt" id="grs_wgt" placeholder="Gross Weight(Grm)" value="{{ $property['gross_weight'] }}" >
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="net_wgt">Net Weight</label>
        <input type="text" class="form-control text-center calculate" name="net_wgt" id="net_wgt" placeholder="Net Weight(Grm)" value="{{ $stock->available }}" >
    </div>

    <div class="col-md-2 form-group p-0">
        <label for="fine_wgt">Fine Weight</label>
        <input type="text" class="form-control text-center" name="fine_wgt" id="fine_wgt" placeholder="File Weght (Grm)" value="{{ $property['fine_weight'] }}" readonly>
    </div>
    <div class="col-md-1 form-group p-0">
        <label for="wstg">Wastage %</label>
        <input type="text" class="form-control text-center calculate" name="wstg" id="wstg" placeholder="Wastage(%)" value="{{ $property['wastage'] }}"  >
    </div>
	
    <!--<div class="col-md-2 form-group p-0">
        <label for="fine_purity">Fine Purity(%)</label>
        <input type="text" class="form-control text-center" name="fine_purity" id="fine_purity" placeholder="Fine Purity(%)" value="{{ $property['fine_purity'] }}" readonly>
    </div>-->
    <div class="col-md-2 form-group p-0">
        <label for="chrg">Charge/Grm</label>
        <input type="text" class="form-control text-center calculate" name="chrg" id="chrg" placeholder="Charge / Grm" value="{{ $stock->labour_charge }}" >
    </div>
	@if($stock->item_type=='genuine')
	<div class="col-md-2 form-group p-0">
        <label for="amount" class="hight-light-lebel">BARCODE</label>
        <input type="text" class="form-control text-center  hight-light-field" name="barcode" id="barcode" placeholder="BARCODE" value="{{ $stock->barcode }}" >
    </div>
	<div class="col-md-2 form-group p-0">
        <label for="amount" class="hight-light-lebel">QRCODE</label>
        <input type="text" class="form-control text-center  hight-light-field" name="qrcode" id="qrcode" placeholder="QRCODE" value="{{ $stock->qrcode }}" >
    </div>
    <div class="col-md-2 form-group p-0">
        <label for="amount" class="hight-light-lebel">RFID</label>
        <input type="text" class="form-control text-center hight-light-field" name="rfid" id="rfid" placeholder="RFID" value="{{ $stock->rfid }}" >
    </div>
	@endif
    <div class="col-md-3 form-group p-0"> 
        <label for="amount">Amount</label>
        <input type="text" class="form-control text-center" name="amount" id="amount" placeholder="Amount" value="{{ $stock->amount }}" readonly>
    </div>
</div>
<h4 class="bg-dark" id="element_header">Elements/Stones <a href="javascript:void(null);" id="ele_plus" >&plus;</a></h3>
@if(!empty($stock->elements) && $stock->elements->count()>0)

    @foreach($stock->elements as $key=>$ele)
    <div class="row bg-light" style="border-top:1px dashed gray;">
        <input type="hidden" name="ele_ids[]" value="{{ $ele->id }}">
        <div class="col-md-5 form-group p-0">
            <label for="caret">Element/Stone</label>
            <input type="text" class="form-control text-center calculate" name="ele_name[]" id="ele_name" placeholder="Element/Stone Name" value="{{ $ele->name }}">
        </div>
        <div class="col-md-2 form-group p-0">
            <label for="purity">Caret</label>
            <input type="text" class="form-control text-center calculate" name="ele_caret[]" id="ele_caret" placeholder="Caret" value="{{ $ele->caret }}">
        </div>
        <div class="col-md-2 form-group p-0">
            <label for="grs_wgt">Quantity</label>
            <input type="text" class="form-control text-center calculate" name="ele_quant[]" id="ele_quant" placeholder="Quantity (Count)" value="{{ $ele->quant }}" >
        </div>
        <div class="col-md-3 form-group p-0">
            <label for="net_wgt">Cost</label>
            <input type="text" class="form-control text-center calculate" name="ele_cost[]" id="ele_cost" placeholder="Cost of All" value="{{ $ele->cost }}">
        </div>
        <label for="ele_{{ $ele->id }}" class="del_sel btn btn-outline-danger px-1 py-0">&cross;
            <input type="checkbox" name="ele_del[]" class="del_ele" value="{{ $ele->id }}" id="ele_{{ $ele->id }}" style="display:none;">
        </label>
    </div>
    @endforeach
@endif
<style>
    #ele_plus{
        font-size: initial;
        vertical-align: middle;
        padding:0 5px;
        border:1px dashed lightblue;
        color:lightblue;
    }
    #ele_plus:hover{
        color:blue;
        background:lightblue;
        border:1px dashed blue;
    }
    .del_sel,.ele_del{
        position:absolute;
        right:0;
        z-index:1;
    }
    div.row.disabled{
        position: relative;
    }
    div.row.disabled:after{
        content:"";
        position: absolute;
        right:0;
        left:0;
        bottom:0;
        top:0;
        background:#000000a3;
    }
	.hight-light-field{
        box-shadow: rgba(0, 0, 0, 0.15) 0px 5px 15px 0px;
		box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }
    .hight-light-lebel{
        text-shadow: 1px 2px 1px gray;
    }
</style>

