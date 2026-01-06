@php 
    $stocks = $purchase->stocks->sortBy('category_id');
    $pre_block = false;
    $num = $count = $block_num = $input_num = 0;
@endphp

<input type="hidden" name="stocktype" id="stocktype" value="{{ ($purchase->stock_type!="other")?$purchase->stock_type:"loose" }}">
@foreach($stocks as $sk=>$stock)
    @if(!$pre_block)
    @php 
        $count = $stocks->where('category_id',$stock->category_id)->count() ;
        $pre_cat = true ;
    @endphp
    <div class="row main_bill_block stock_block my-2" id="{{ ($block_num==0)?'main_bill_block':'' }}">
                    <legend class="block_sn px-3">{{ ++$block_num }}</legend>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="metals">Metals</label>
                            <select name="metals[]" class="form-control select2" id="metals" placeholder="Select Metal">
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
                            <input type="number" class="form-control calculate_item rate" id="rate" name="rate[]" min="1" step="any" placeholder="Enter Rate" value="{{  $stock->rate  }}" >
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="collections">Collections</label>
                            <select name="collections[]" class="form-control select2" id="collections" placeholder="Select Collection">
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
                            <select name="category[]" class="form-control select2" id="category" placeholder="Select Category">
                                <option value="">Select</option>
                                @foreach (categories(3) as $category)
                                    <option value="{{ $category->id }}" {{ ($stock->categories->contains($category->id))?'selected':'' }}  }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

    
                    <div class="col-lg-12">
                        <table class = "table table_theme table-responsive table-bordered genuine dataTable">
                            <thead class = "">
                                <tr>
                                    <th> SN </th>
                                    <th> purchase Name </th>
									@if($purchase->stock_type=='genuine')
                                    <th> HUID </th>
									@endif
                                    <th> Carat </th>
                                    <th> Gross Weight </th>
                                    <th> Net Weight </th>
                                    <th> Purity </th>
                                    <th> Watages </th>
                                    <!--<th> Fine Purity </th>-->
                                    <th> Fine Weight </th>
                                    <th> Labour Charge </th>
									@if($purchase->stock_type=='genuine')
                                    <th> BARCODE </th>
                                    <th> RFID </th>
									@endif
                                    <th> Amount </th>
                                    <th> Stone </th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" >
    @endif
    
    <tr id="main_item_tr_1" class="main_item_tr item_tr">
        <td class="sn-box">
            <input type="hidden"class="stock_ids" name="stock_id[{{ $block_num-1 }}][]" value="{{ $stock->id }}" id="stock_id_bndl">
            <span class = "sn-number ">{{ $num+1 }}</span>
            <label for="item_{{ $stock->id }}" class="btn btn-outline-danger btn-sm btn-delete del_item_btn">X
                <input type="checkbox" class="del_item_check" name="del_item[]"  value="{{ $stock->id }}" style="display:none;" id="item_{{ $stock->id }}">
            </label>
        </td>
        <td>
            <input type="text" class="tb_input product" name="product_name[{{ $block_num-1 }}][]" id="product_name_{{ $loop->index + 1 }}" placeholder="Product Name" value="{{ $stock->name }}" >
        </td>
		@if($purchase->stock_type=='genuine')
		<td>
			<input type="text" class="tb_input huid" name=" huid[{{ $block_num-1 }}][]" id="huid_{{ $loop->index + 1 }}" placeholder="HUID"  style="width:70px;" placeholder="HUID" value="{{ $stock->huid }}"> 
		</td>
		@endif
        <td>
            <input type="number" class="tb_input calculate_item caret" name="carat[{{ $block_num-1 }}][]" id="carat_{{ $loop->index + 1 }}" placeholder="Carat" oninput="calculate(this)" value="{{ $stock->carat }}"   min = "0" step = "any"  style="width:70px;">
        </td>
        <td>
            <input type="number" class="tb_input calculate_item gross_weight" name="gross_weight[{{ $block_num-1 }}][]" id="grossWeight_{{ $loop->index + 1 }}" placeholder="Gross Weight" oninput="calculate(this)" value="{{ $stock->gross_weight }}"   min = "0" step = "any"  style="width:80px;" >
        </td>
        <td>
            <input type="number" class="tb_input calculate_item net_weight" name="net_weight[{{ $block_num-1 }}][]" id="netWeight_{{ $loop->index + 1 }}" placeholder="Net Weight" value="{{ $stock->net_weight }}"   oninput="calculate(this)"  min = "0" step = "any"  style="width:80px;">
        </td>
        <td>
            <input type="number" class="tb_input calculate_item purity" name="purity[{{ $block_num-1 }}][]" id="purity_{{ $loop->index + 1 }}" placeholder="Purity" oninput="calculate(this)" value="{{ $stock->purity }}"   min = "0" step = "any"  style="width:80px;">
        </td>
        <td>
            <input type="number" class="tb_input calculate_item waste" name="wastage[{{ $block_num-1 }}][]" id="wastage_{{ $loop->index + 1 }}" placeholder="Wastage" oninput="calculate(this)" value="{{ $stock->wastage }}"   min = "0" step = "any" style="width:80px;">
        </td>
        {{--<td>
            <input type="number" class="tb_input fine_pure" name="fine_purity[{{ $block_num-1 }}][]" id="finePurity_{{ $loop->index + 1 }}" placeholder="Fine Purity" readonly value="{{ $stock->fine_purity }}"   min = "0" step = "any" style="width:80px;">
        </td>--}}
        <td>
            <input type="number" class="tb_input fine_weight" name="fine_weight[{{ $block_num-1 }}][]" id="fineWeight_{{ $loop->index + 1 }}" placeholder="Fine Weight" readonly value="{{ $stock->fine_weight }}"   min = "0" step = "any" style="width:80px;" >
        </td>
        <td>
            <input type="number" class="tb_input calculate_item labour" name="labour_charge[{{ $block_num-1 }}][]" id="labourCharge_{{ $loop->index + 1 }}" placeholder="Labour Charge" oninput="calculate(this)" value="{{ $stock->labour_charge }}"   min = "0" step = "any"  style="width:80px;">
        </td>
		@if($purchase->stock_type=='genuine')
		<td>
			<input type="text" class="tb_input barcode" name="barcode[{{ $block_num-1 }}][]" id="barcode_{{ $loop->index + 1 }}" placeholder="BARCODE"  style="width:100px;" value="{{ $stock->barcode }}"> 
		</td>
		<td>
			<input type="text" class="tb_input rfid" name=" rfid[{{ $block_num-1 }}][]" id="rfid_{{ $loop->index + 1 }}" placeholder="RFID"  style="width:100px;" value="{{ $stock->rfid }}"> 
		</td>
		@endif
        <td>
            <input type="number" class="tb_input amount" name="amount[{{ $block_num-1 }}][]" id="amount_{{ $loop->index + 1 }}" placeholder="Amount" readonly value="{{ $stock->amount }}"   min = "0" step = "any" style="width:80px;">
        </td>
        <td class="text-center">
            <a href="{{ route('purchases.associated') }}" class="btn btn-sm btn-info assoc_plus">&plus;</a>
        </td>
    </tr>
    @if($stock->elements->count()>0) 
        @php $elements = $stock->elements @endphp
        @foreach($elements as $elek=>$element)
            @include('vendors.purchases.content.associatetredit')
        @endforeach
    @endif
    @php 
        $num++;
        $input_num++;
        $pre_block = ($num==$count)?false:$pre_cat;
    @endphp
    @if(!$pre_block)
    </tbody>
            </table>
    
            <a href="#main_item_tr"  id = "more_item_tr" class = "btn btn-primary more_item_tr" ><li class="fa fa-plus-circle"></li> Item </a>
            <!-- <button type = "button" id = "addMoreBtn" class = "btn btn-primary" > Add More </button> -->
            <a href="#main_bill_block" class="btn btn-sm btn-outline-primary stock_block_add" style="float:right;"  id="block_head"><li  class="fa fa-plus-circle"></li> Block</a>
    
        </div>
        
        </div>
        @php $num=0 @endphp
    @endif

@endforeach