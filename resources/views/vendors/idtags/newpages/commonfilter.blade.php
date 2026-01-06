<div class="col-md-12 mb-3">
    <div class="card round curve">
        <div class="card-body pb-0 pt-2">
            <div class="row ">
                <div class="col-md-2 col-6 mb-1 ">
                    <label class="mb-1">Code</label>
                    <div class="input-group mb-1" >
                        <select class="form-control text-primary" id="tag" style="font-weight:bold;box-shadow:1px 2px 3px;">
                            <option selected value="">Select</option>
                            <option value="barcode">BarCode</option>
                            <option value="qrcode" selected>QrCode</option>
                            <option value="rfid">RFID</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-1">
                    <label class="mb-1">Category</label>
                    <select class="form-control jewellery_cat" id="metal">
                            <option selected value="">Select</option>
                            <!--<option value="Gold">Gold</option>
                            <option value="Silver">Silver</option>-->
                            @php  $cats = categories(1,true) @endphp
                            @foreach($cats as $ci=>$cats)
                                <option value="{{  $cats->name }}">{{ $cats->name }}</option>
                            @endforeach
                            {{--@php $stones = stonecategory() @endphp 
                            @foreach($stones as $sk=>$stone)
                                <option value="{{  $cats->name }}">{{ $stone->name }}</option>
                            @endforeach--}}
                        </select>
                </div>
                <div class="col-md-3 mb-1">
                    <label class="mb-1">Jewellery/Name</label>
                     <select class="form-control my_select jewellery_cat" id="type">
                        <option selected value="">Enter & Select</option>
                        @php  $cats = categories(3) @endphp
                        @foreach($cats as $ci=>$cats)
                            <option value="{{ $cats->name }}">{{ $cats->name }}</option>
                        @endforeach
                    </select>
                </div>
				<div class="col-md-1 mb-1 ">
                    <label class="mb-1">Karat</label>
                     <select name="karat" id="karat" class="form-control text-control">
						<option value="">All</option>
						<option value="19">18K</option>
						<option value="20">20K</option>
						<option value="22">22K</option>
						<option value="24">24K</option>
					 </select>
                </div>
                {{--<div class="col-md-3 mb-1 p-0">
                    <label class="mb-1">Item Group</label>
                     <select class="form-control select2 item_group" id="group">
                        <option selected value="">Select Type</option>
                        @php  $cats = categories(3) @endphp
                        @foreach($cats as $ci=>$cats)
                            <option value="{{ $cats->name }}">{{ $cats->name }}</option>
                        @endforeach
                    </select>
                </div>--}}
                <div class="col-md-3 col-10 mb-1 ">
                    <label class="mb-1">Search</label>
                    <input class="form-control" type="text" placeholder="Enter Code" name="keyword" id="keyword">
                </div>
                <div class="col-md-1 col-2 mb-1  text-center" style="align-self: center;">
                    <label class="m-1 col-12"></label>
                    <button type="submit" name="" class="btn btn-sm btn-info" id="filter_data">
                        Find
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>