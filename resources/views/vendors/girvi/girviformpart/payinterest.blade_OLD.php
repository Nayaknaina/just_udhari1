<style>
    #int_girvi_id{
        position: relative;
    }
    #int_girvi_id:before{
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        border-top: 1px solid gray;
        z-index: 0;
    }
    #int_girvi_id > span{
        position: relative;
        display: inline-block;
        padding: 2px 10px;
        background-color: white; 
        z-index: 1;
        border: 1px solid lightgray;
        box-shadow: 1px 2px 3px gray inset;
        border-radius: 10px;
        text-shadow: 1px 1px 2px gray;
    }
    #int_girvi_separater{
        font-size:120%;
        list-style:none;
        display:inline-flex;
        border-top:1px solid #f95600;
    }
    #int_girvi_separater >li{
        position: relative;
    }
    #int_girvi_separater > li:first-child,#int_girvi_separater > li:last-child{
       border:1px solid #f95600;
       border-radius: 0 0 10px 10px;
    }
    .girvi_detail{
        list-style:none;
        /* display:inline-flex; */
    }
    .girvi_detail > li{
        border:1px solid lightgray;
        border-radius:10px;
    }
    .shadow_li{
        box-shadow:1px -2px 3px gray;
    }
    table > thead > tr >th{
        padding:1px!important;
        border:unset!important;
    }
    #int_girvi_summery_show:after{
        content:"\225A";
        border:1px solid #f95600;
        line-height:normal;
        padding:0 5px;
        border-radius:50%;
        color:white;
        font-weight:bold;
        background-color: #f95600;
        /* background-image: linear-gradient(to bottom,lightgray,white,lightgray); */
    }
    #int_girvi_summery_show.on:after{
        content: "\2259";
    }
    #int_girvi_summery_show.on:after,#int_girvi_summery_show:hover:after{
        background:white;
        color:#f95600;
    }
</style>
<div class="alert alert-outline-warning text-center text-warning" id="girvi_int_pay_default"><b>Select The Girvi Record !</b></div>
<div class="row col-12 p-0 m-0" id="girvi_int_pay_block" style="display:none;">
    <div class="col-12">
        <div class="row">
            <h6 id="int_girvi_id" class="col-12 text-center"><span>GIRVI-ID</span></h6>
        </div>
    </div>
    <ul class="col-12 text-primary p-0" id="int_girvi_separater">
        <li class="w-100 text-center"><b>Girvi Detail </b></li>
        <li class="w-100 text-center text-primary" style="align-content:end;">
            <a href="javascript:void(null);"  id="int_girvi_summery_show"></a>
        </li>
        <li class="w-100 text-center"><b id="int_item_count">0</b><span>-Item</span></li>
    </ul>
    <div class="col-12">
        <ul class="girvi_detail p-0 row mb-0" style="display:none;" id="int_girvi_summery">
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Principal</b>
                    <hr class="m-1">
                    <span id="int_girvi_amount">0 ₹</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Tanure</b>
                    <hr class="m-1">
                    <span id="int_girvi_duration">0 Month</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Return</b>
                    <hr class="m-1">
                    <span id="int_girvi_return">dd-mm-yyyy</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Int. Type</b>
                    <hr class="m-1">
                    <span id="int_girvi_int_type">SI/CI</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Int. %</b>
                    <hr class="m-1">
                    <span  id="int_girvi_int_perc">0 %</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1">
                <div class="w-100 text-center">
                    <b>Int. Amount</b>
                    <hr class="m-1">
                    <span  id="int_girvi_int_amnt">0 ₹</span>
                </div>
            </li>
        </ul>
        <ul class="girvi_detail p-0 row">
            <li class="w-100 text-center col-4 mb-1 border-warning text-warning shadow_li" >
                <div class="w-100 text-center">
                    <b>Payable</b>
                    <hr class="m-1">
                    <span  id="int_girvi_payable">0 ₹</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1 border-succcess text-success shadow_li">
                <div class="w-100 text-center">
                    <b>Paid</b>
                    <hr class="m-1">
                    <span id="int_girvi_paid">0 ₹</span>
                </div>
            </li>
            <li class="w-100 text-center col-4 mb-1 border-danger text-danger shadow_li">
                <div class="w-100 text-center">
                    <b>Remains</b>
                    <hr class="m-1">
                    <span  id="int_girvi_remains">0 ₹</span>
                </div>
            </li>
        </ul>
    </div>
    <div class="col-12 p-0">
        <div class="table-responsive">
            <table class="table table_theme">
                <thead>
                    <tr>
                        <th class="p-1">SN</th>
                        <th class="p-1">NAME</th>
                        <th class="p-1">VALUE</th>
                    </tr>
                </thead>
                <tbody id="int_pay_dataarea">
                    <tr>
                        <td colspan="3" class="text-center text-danger">No Items !</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group col-md-4 col-12 p-0">
        <label for="pay_amount" class="mb-0">Old </label>
        <div class="input-group">
            <input type="text" class="form-control h-32px btn-roundhalf text-center text-danger" id="old_amount" name="old_amount" value="" readonly>
            <span class="gm-inside">₹</span>
        </div>
    </div>
    <div class="form-group col-md-4 col-12 p-0">
        <label for="int_amount"  class="mb-0">Interest</label>
        <div class="input-group">
            <input type="text" class="form-control h-32px btn-roundhalf text-center" id="int_amount" name="int_amount" value="" readonly>
            <span class="gm-inside">₹</span>
        </div>
    </div>
    <div class="form-group col-md-4 col-12 p-0">
        <label for="pay_mode"  class="mb-0">Mode</label>
        <div class="input-group">
            <select name="pay_mode" class="form-select fs-13px h-32px btn-roundhalf text-center" id="pay_mode">
                <option value="">Select</option>
                <option value="online">Online</option>
                <option value="offline" selected >Cash</option>
            </select>
            <span class="custom-select-arrow">▼</span>
        </div>
    </div>
    
    <div class="form-group col-md-8 col-12 p-0">
        <div class="input-group mb-1">
            <label for="int_amount"  class="mb-0 input-prepend">Amount</label>
            <input type="text" class="form-control h-32px  btn-roundhalf  text-center" id="pay_amount" name="pay_amount" value="">
            <span class="gm-inside">₹</span>
        </div>
    </div>
    <div class="form-group col-md-4 text-center mb-1">
        <button type="submit" name="pay" value="interest" class="btn btn-sm btn-success">Pay</button>
    </div>
</div>

