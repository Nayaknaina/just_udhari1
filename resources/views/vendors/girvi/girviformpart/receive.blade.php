<!--<h5 class="col-12 text-center" style="text-shadow: 1px 2px 3px gray;">New Girvi</h5>-->
<style>
ul#new_girvi_page_tab {
    display: flex;
    justify-content: space-between;
    list-style: none;
    padding: 0;
    margin: 0;
    align-items: end;
}
ul#new_girvi_page_tab > li{
    border:1px solid #f7c0a5;
    padding:2px 5px;
    border-radius:10px 10px 0px 0px;
    text-align:center;    
}
ul#new_girvi_page_tab > li:hover{
    border-top:3px solid  #f7c0a5!important;
}
ul#new_girvi_page_tab > li > a{
    font-size:90%;
}
ul#new_girvi_page_tab > li > a > b{
    font-size: 150%;
}
ul#new_girvi_page_tab > li.active{
    border-top:3px solid  #f7c0a5!important;
    background-image: linear-gradient(to bottom,silver,lightgray,white,lightgray,silver);
}
ul#new_girvi_page_tab li:first-child{
    margin-right: auto;
    box-shadow:2px 3px -3px lightgray;
}
ul#new_girvi_page_tab li:not(:first-child).active{
    background-image: linear-gradient(to bottom,silver,lightgray,white,lightgray,silver);
}
ul#new_girvi_page_tab li:not(:first-child).active > a{
    color:#fd5f00!important;
}
</style>
<ul class="col-12" id="new_girvi_page_tab">
    <li class="active">
        <a href="#new_girvi_section" class="new_girvi_page_tab_switch">
            <b style="text-shadow: 1px 2px 3px gray;">New Girvi</b>
        </a>
    </li>
    <li>
        <a href="#custo_record_section" class="new_girvi_page_tab_switch " data-target="old_girvi">
            Old Girvi
        </a>
    </li>
    <li class="">
        <a href="#custo_record_section" class="new_girvi_page_tab_switch" data-target="new_girvi">
            Current Girvi
        </a>
    </li>
</ul>
<div class="w-100 new_girvi_page_tab" id="new_girvi_section">
    <div class="col-12 p-0 pb-2 mb-2" style="overflow:x-scroll;border-bottom:1px solid lightgray;">
        <table class="table text-dark m-0" >
            <thead id="item_table_head">
                <tr class="bg-light">
                    <th class="text-dark">SN</th>
                    <th class="text-dark">Category <a href="javascript:void(null);" class="btn btn-sm btn-outline-dark" style="align-content:center;" data-toggle="modal" data-target="#item_category_modal"><i class="fa fa-plus"></i></a></th>
                    <th class="text-dark">Detail</th>
                    <th class="text-dark">Image</th>
                    <th class="text-dark">Gross</th>
                    <th class="text-dark">Net</th>
                    <th class="text-dark">Pur %</th>
                    <th class="text-dark">Fine</th>
                    <th class="text-dark">Rate</th>
                    <th class="text-dark">Valuation</th>
                    <th class="text-dark">&plus;</th>
                </tr>
            </thead>
            <tbody id="item_table_body">
                <tr id="main_item_row" class="item_row">
                    <td width="" class="item_count">1</td>
                    <td  width="10%;">
                        <div class="input-group">
                            <select name="category[]" class="form-control w-auto h-32px  category input_field btn-roundhalf" id="category">
                                <option value="">Category?</option>
                            </select>
                        </div>
                    </td>
                    <td width="15%;">
                        <div class="input-group" >
                            <textarea class="form-control col-12 detail input_field btn-roundhalf" id="detail" name="detail[]" placeholder="Item Detail !" rows="1"></textarea>
                        </div>
                    </td>
                    <td width="15%;">
                        <div class="input-group position-relative">
                            <label class="image_browse_label form-control h-32px btn-roundhalf image" name="image[]">Select Image !</label>
                            <input type="file" id="uploadFile" class="d-none image input_field" accept=".jpg,.jpeg,.png,.webp" name="image[]" >
                        </div> 
                    </td>
                    <td width="10%;">
                        <div class="input-group position-relative">
                            <input class="form-control h-32px btn-roundhalf gross input_field jewellery" type="text"
                                placeholder="Gross Weight." name="gross[]">
                                <div class="">
                                </div>
                            <span class="gm-inside">Gm</span>
                        </div>
                    </td>
                    <td width="10%;">
                        <div class="input-group position-relative">
                            <input class="form-control h-32px btn-roundhalf net input_field jewellery" type="text"
                                placeholder="Net Weight." name="net[]">
                            <span class="gm-inside">Gm</span>
                        </div>
                    </td>
                    <td width="6%;">
                        <div class="input-group position-relative">
                            <input class="form-control h-32px btn-roundhalf pure input_field jewellery" type="text"
                                placeholder="Purity" name="pure[]">
                            <span class="gm-inside">%</span>
                        </div>
                    </td>
                    <td width="10%;">
                        <div class="input-group position-relative">
                            <input class="form-control h-32px btn-roundhalf fine input_field" type="text" placeholder="Fine" name="fine[]" readonly>
                            <span class="gm-inside">Gm</span>
                        </div>
                    </td>
                    <td width="10%;">
                        <div class="input-group position-relative">
                            <input class="form-control h-32px btn-roundhalf rate input_field" type="text" placeholder="Market Rate" name="rate[]">
                            <span class="gm-inside jewellery_gm">₹/Gm</span>
                            <span class="gm-inside jewellery_rs" style="display:none;">₹</span>
                        </div>
                    </td>
                    <td width="10%;">
                        <div class="input-group position-relative" >
                            <input class="form-control h-32px btn-roundhalf  value input_field" type="text"
                                placeholder="Estimated Value" name="value[]" readonly>
                            <span class="gm-inside" style="color:blue;">₹</span>
                        </div>
                    </td>
                    <td class="text-center" width="">
                        <a href="javascript:void(null);" class="btn btn-sm btn-info more_item m-0" type="button" id="more_item">
                        &plus; 
                    </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 p-0">
        <div class="w-100 mb-4 text-center section_head">
            <b class="m-0">Payment Detail</b>
        </div>
        <div class="row col-12 p-0 m-auto">
            <div class="col-md-3 mb-1 p-0">
                <div class="input-group position-relative mb-2">
                    <input class="form-control h-32px btn-roundhalf  floatdigit text-center" type="text" placeholder="Valuation" name="valuation" id="valuation" readonly style="font-weight:bold;">
                    <span class="gm-inside">₹</span>
                    <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Valuation</label>
                </div>
            </div>
            <div class="col-md-3 mb-1 p-0">
                <div class="input-group position-relative mb-2 date-wrapper">
                    <input class="form-control h-32px btn-roundhalf  floatdigit text-center" type="text"name="grant" id="grant" placeholder="Isuue Payment" style="font-weight:bold;">
                    <span class="gm-inside">₹</span>
                    <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Issue Payment</label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group position-relative date-wrapper">
                    <input class="form-control h-32px btn-roundhalf text-center" type="date" name="issue"  id="issue" value="{{ date('Y-m-d',strtotime('now')) }}">
                    <label for="issue" class="floating-label">Issue Date</label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group position-relative date-wrapper">
                    <label for="tenure" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Tanure(Month)</label>
                    <input class="form-control h-32px btn-roundhalf floatdigit text-center" type="text" name="tenure" placeholder="Tanure In Months" id="tenure">
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group position-relative date-wrapper">
                    <input class="form-control h-32px btn-roundhalf text-center" type="date" name="return" readonly  id="return" >
                    <label for="return" class="floating-label">Return Date</label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group mb-3" >
                    <select class="form-select fs-13px h-32px btn-roundhalf " name="interesttype" id="interesttype">
                        <option value="">Select</option>
                        <option value="si">Simple</option>
                        <option value="ci">Compound</option>
                    </select>
                    <span class="custom-select-arrow">▼</span>
                    <label for="interest" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Interest Type </label>
                </div>
            </div>
            <div class="col-md-1 mb-1 p-0">
                <div class="input-group position-relative mb-3">
                    <input class="form-control h-32px btn-roundhalf  floatdigit" type="text" placeholder="Int % " name="interest" id="interest">
                    <span class="gm-inside">%</span>
                    <label for="interest" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Rate(%) </label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group position-relative mb-3">
                    <input class="form-control h-32px btn-roundhalf  floatdigit text-success text-center" type="text" placeholder="Final Amount " name="principal_val" id="principal_val" readonly >
                    <span class="gm-inside">₹</span>
                    <label for="principal_val" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Principal ₹ </label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group position-relative mb-3">
                    <input class="form-control h-32px btn-roundhalf  floatdigit  text-success text-center" type="text" placeholder="Final Amount " name="interest_val" id="interest_val" readonly>
                    <span class="gm-inside">₹</span>
                    <label for="interest_val" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Interest ₹ </label>
                </div>
            </div>
            <div class="col-md-3 mb-1 p-0">
                <div class="input-group position-relative mb-3">
                    <input class="form-control h-32px btn-roundhalf  floatdigit  text-success text-center" type="text" placeholder="Final Amount " name="payable" id="payable" readonly style="font-weight:bold;">
                    <span class="gm-inside">₹</span>
                    <label for="interest" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Payable ₹ </label>
                </div>
            </div>
            <div class="col-md-2 mb-1 p-0">
                <div class="input-group mb-3">
                    <select name="medium" class="form-select fs-13px h-32px btn-roundhalf  text-center" id="medium" >
                        <option value="">Select</option>
                        <option value="on">Online</option>
                        <option value="cash">Cash</option>
                    </select>
                    <span class="custom-select-arrow">▼</span>
                    <label for="medium" class="floating-label" style="top:-15px;font-size:11px;color: #333;">Pay Medium </label>
                </div>
            </div>
            <div class="col-12 text-center mb-1 p-0  mt-2 pt-2"  style="border-top:1px dashed #f95600;">
                <button type="submit" name="do" value="save" class="btn btn-sm btn-success">SAVE</button>
                <button type="submit" name="do" value="print" class="btn btn-sm btn-info">PRINT</button>
            </div>
        </div>
    </div>
</div>
<div class="w-100 new_girvi_page_tab" id="custo_record_section" style="display:none;">
    @include('vendors.girvi.girviformpart.record')
</div>