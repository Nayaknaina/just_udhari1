<style>
    .girvi_item{
        position:relative;
        border-radius:10px;
        border:1px double gray;
        box-shadow: 1px 1px 1px 2px lightgray inset;
        background: #f8f8f8;
    }
    .girvi_item>#category_block{
        position: absolute;
        top:-20px;
        left:10px;
    }
    .more_item{
        position: absolute;
        right:0;
        top:-20px;
    }
    .image_browse_label{
        position: relative;
        overflow: hidden;
        cursor:pointer;
        font-size:10px;
        align-content:center;
    }
    .image_browse_label:after{
        position: absolute;
        /* content:"\2750"; */
        content:"@";
        font-size:100%;
        right:0;
        top:0;
    }
</style>
<div class="row col-12 p-0 m-auto" id="item_block">
    <div class="form-inline col-12 px-2 pt-4 pb-2 girvi_item mt-4" id="girvi_item">
        <div class="input-group" id="category_block">
            <select name="category[]" class="form-control w-auto h-32px  category input_field" id="category">
                <option value="">Category?</option>
            </select>
            <div class="input-group-append">
            <a href="javascript:void(null);" class="btn btn-sm btn-primary" style="align-content:center;" data-toggle="modal" data-target="#item_category_modal"><i class="fa fa-plus"></i></a>
            </div>
        </div>
        <a href="javascript:void(null);" class="btn btn-sm btn-dark more_item" type="button" id="more_item">
            &plus; Items <span class="badge badge-light item_count">1</span>
        </a>
        <div class="form-group col-12 p-0 mb-1">
            <textarea class="form-control col-12 detail input_field" id="detail" name="detail[]" placeholder="Item Detail !" rows="1"></textarea>
        </div>
        <div class="input-group position-relative col-md-4 col-sm-6 p-0 mb-1 jewellery">
            <input class="form-control h-32px btn-roundhalf gross input_field" type="text"
                placeholder="Gross Weight." name="gross[]">
                <div class="">
                </div>
            <span class="gm-inside">Gm</span>
        </div>
        <div class="input-group position-relative col-md-4 col-sm-6 p-0 mb-1 jewellery">
            <input class="form-control h-32px btn-roundhalf net input_field" type="text"
                placeholder="Net Weight." name="net[]">
            <span class="gm-inside">Gm</span>
        </div>
        <div class="input-group position-relative  col-md-4 col-sm-6 p-0 mb-1 jewellery ">
            <input class="form-control h-32px btn-roundhalf carat input_field" type="text"
                placeholder="Carat/ %" name="caret[]" oninput="digitonly(event,24)">
            <span class="gm-inside">C/%</span>
        </div>
        <div class="input-group position-relative  col-md-4 col-sm-6 p-0 mb-1">
            <!-- Readonly Text Input -->
            <!--<input id="fileNameDisplay" class="form-control h-32px btn-roundhalf " type="text" placeholder="Item Detail" readonly>-->

            <!-- Hidden File Input -->
             <label class="image_browse_label form-control h-32px btn-roundhalf" name="image[]">Select Image !</label>
            <input type="file" id="uploadFile" class="d-none image input_field" accept=".jpg,.jpeg,.png,.webp" name="image[]" >

            <!-- Upload Button -->
            <!--<button type="button" class="gm-button" onclick="document.getElementById('uploadFile').click();">Upload</button>-->
        </div>
        <div class="input-group position-relative  col-md-4 col-sm-6 p-0 mb-1">
            <input class="form-control h-32px btn-roundhalf rate input_field" type="text" placeholder="Market Rate" name="rate[]">
            <span class="gm-inside">₹/Gm</span>
        </div>
        <div class="input-group position-relative  col-md-4 col-sm-6 p-0 mb-1" >
            <input class="form-control h-32px btn-roundhalf  value input_field" type="text"
                placeholder="Estimated Value" name="value[]">
            <span class="gm-inside">₹</span>
        </div>
    </div>
</div>
<div class="d-flex mb-2 justify-content-between align-items-center" id="payment_detail">
    <h6 class="fs-15 section-title">Payment Details:</h6>
</div>
<div>
    <div class="row col-12 p-0 m-auto">
        <div class="col-md-6 mb-1 p-0">
            <div class="input-group position-relative mb-2">
                <input class="form-control h-32px btn-roundhalf  floatdigit" type="text" placeholder="Valuation" name="valuation" id="valuation" >
                <span class="gm-inside">₹</span>
            </div>
        </div>
        <div class="col-md-6 mb-1 p-0">
            <div class="input-group position-relative mb-2">
                <input class="form-control h-32px btn-roundhalf  floatdigit" type="text" placeholder="Grant" name="grant" id="grant">
                <span class="gm-inside">₹</span>
            </div>
        </div>
        <div class="col-md-4 mb-1 p-0">
            <div class="input-group position-relative date-wrapper">
                <input class="form-control h-32px btn-roundhalf " type="date" name="issue"  id="issue">
                <label for="issue" class="floating-label">Issue Date</label>
            </div>
        </div>
        <div class="col-md-4 mb-1 p-0">
            <div class="input-group position-relative">
                <input class="form-control h-32px btn-roundhalf  text-center" type="text" name="tenure" placeholder="Tenure In Months" id="tenure">
            </div>
        </div>
        <div class="col-md-4 mb-1 p-0">
            <div class="input-group position-relative date-wrapper">
                <input class="form-control h-32px btn-roundhalf " type="date" name="return" readonly  id="return" >
                <label for="return" class="floating-label">Return Date</label>
            </div>
        </div>
        <div class="col-md-3 mb-1 p-0">
            <div class="input-group position-relative mb-3">
                <input class="form-control h-32px btn-roundhalf  floatdigit" type="text" placeholder="Int % " name="interest" id="interest">
                <span class="gm-inside">%</span>
            </div>
        </div>
        <div class="col-md-5 mb-1 p-0">
            <div class="input-group position-relative mb-3">
                <input class="form-control h-32px btn-roundhalf  floatdigit" type="text" placeholder="Final Amount " name="payable" id="payable">
                <span class="gm-inside">₹</span>
            </div>
        </div>
        <div class="col-md-4 mb-1 p-0">
            <div class="input-group mb-3" style="align-items: center;">
                <select class="form-select fs-13px h-32px btn-roundhalf " name="interesttype" id="interesttype">
                    <option value="">Interest Type:</option>
                    <option value="si">Simple</option>
                    <option value="ci">Compound</option>
                </select>
                <span class="custom-select-arrow">▼</span>
            </div>
        </div>
        <div class="col-12 text-center mb-1 p-0  mt-2 pt-2"  style="border-top:1px dashed #f95600;">
            <button type="submit" name="do" value="save" class="btn btn-sm btn-success">SAVE</button>
            <button type="submit" name="do" value="print" class="btn btn-sm btn-info">PRINT</button>
        </div>
    </div>
</div>