<div class="row main_bill_block stock_block my-2" id="main_bill_block" >
{{--<legend class="block_sn px-3">{{ $sn+1 }}</legend>--}}
    <div class="col-lg-3">
        <div class="form-group">
            <label for="metals">Metals</label>
            <select name="metals[]" class="form-control metals select2" id="metals" placeholder="Select Metal">
                <option value="">Select</option>
                @foreach (categories(1) as $category )
                <option value = "{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="col-lg-3">
        <div class="form-group">
            <label for="rate">Rate</label>
            <input type="number" class="form-control rate calculate_item" id = "rate" name = "rate[]" min="1" step="any" placeholder="Enter Rate">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="collections">Collections</label>
            <select name="collections[]" class="form-control select2 collections" id="collections" placeholder="Select Collection">
                <option value="">Select</option>
                @foreach (categories(2) as $category )
                <option value = "{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category[]" class="form-control select2 category" id="category" placeholder="Select Category">
                <option value="">Select</option>
                @foreach (categories(3) as $category )
                <option value = "{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-12">
        
    @include('vendors.purchases.content.itemrow',compact('form_name'))
        
    </div>
    @if($sn>0)
		<ul class="block_del_btn p-0 m-0">
			<li>{{ $sn+1 }}</li>
			<li>
				<a href="javascript:void(null);" class="custom_remove_btn">
					<i class="fa fa-times"></i>
				</a>
			</li>
		</ul>
    <!--<a href="javascript:void(null);" class="custom_remove_btn block_del_btn"><li class="fa fa-times"></li></a>-->           
    @endif
</div>