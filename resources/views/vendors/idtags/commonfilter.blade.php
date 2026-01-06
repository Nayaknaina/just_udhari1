<div class="col-md-12 mb-3">
    <div class="card round curve">
        <div class="card-body pb-0 pt-2">
            <div class="row ">
                <div class="col-md-2 mb-2">
                    <label class="mb-1">Code Type</label>
                    <div class="input-group mb-1" >
                        <select class="form-select h-32px btn-roundhalf text-primary" id="tag" style="font-weight:bold;box-shadow:1px 2px 3px;">
                            <option selected value="">Select</option>
                            <option value="barcode">BarCode</option>
                            <option value="qrcode">QrCode</option>
                            <option value="rfid">RFID</option>
                        </select>
                        <span class="custom-select-arrow">▼</span>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="mb-1">Metal Category</label>
                    <div class="input-group mb-1">
                        <select class="form-select h-32px btn-roundhalf jewellery_cat" id="metal">
                            <option selected value="">Please Select</option>
                            @php  $cats = categories(1) @endphp
                            @foreach($cats as $ci=>$cats)
								@if($cats->name != 'Stone')
									<option value="{{ $cats->id }}">{{ $cats->name }}</option>
								@endif
                            @endforeach
							@php $stones = stonecategory() @endphp 
                            @foreach($stones as $sk=>$stone)
                                <option value="{{ $stone->id }}-stone">{{ $stone->name }}</option>
                            @endforeach
                        </select>
                        <span class="custom-select-arrow">▼</span>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="mb-1">Jewellery Type </label>
                    <div class="input-group mb-1">
                        <select class="form-select h-32px btn-roundhalf select2 jewellery_cat" id="type">
                            <option selected value="">Select Type</option>
                            @php  $cats = categories(3) @endphp
                            @foreach($cats as $ci=>$cats)
                                <option value="{{ $cats->id }}">{{ $cats->name }}</option>
                            @endforeach
                        </select>
                        <span class="custom-select-arrow">▼</span>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="mb-1">Search</label>
                    <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Enter Code" name="keyword" id="keyword">
                </div>
                <div class="col-md-1 mt-2 " style="align-self: center;">
                    <button class="btn btn-gradient-danger btn-roundhalf  h-32px _effect--ripple waves-effect waves-light">
                        Reset
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>