
<h5 class="col-12 text-center" style="text-shadow: 1px 2px 3px gray;">Girvi Payment</h5>
<div class="col-12 p-0">
    <table class="table text-dark pb-2" style="border-bottom:1px solid gray;">
        <thead id="item_table_head">
            <tr class="bg-light">
                <th class="text-dark">Sn</th>
                <th class="text-dark">Item </th>
                <th class="text-dark">Image </th>
                <th class="text-dark">Weight</th>
                <th class="text-dark">Fine</th>
                <th class="text-dark">Valuation</th>
                <th class="text-dark">Interest %</th>
                <th class="text-dark">Principal</th>
                <th class="text-dark">Interest</th>
                <th class="text-dark">&check;</th>
            </tr>
        </thead>
        <tbody id="pay_data_area">
        </tbody>
    </table>
</div>
<div class="col-12 p-0">
    <div class="w-100 mb-4 text-center section_head">
        <b class="m-0">Payment Detail</b>
    </div>
    <div class="row col-12 p-0 m-auto">
        <ul class="col-md-3" style="list-style:none;" id="int_old_balance">
            <li class="form-control w-100 h-auto text-center" id="int_old_principal"><b>PRINCIPAL : <span id="int_old_principal_value">0 ₹</span></b></li>
            <li class="form-control w-100 h-auto text-center" id="int_old_interest"><b>INTEREST : <span id="int_old_interest_value">0 ₹</span></b></li>
        </ul>
        <div class="col-md-3 mb-1 ">
            <div class="input-group position-relative">
                <input class="form-control h-32px btn-roundhalf  floatdigit text-center" type="text" placeholder="Total Principal" name="desire_principal" id="desire_principal" value="" style="font-weight:bold;" readonly >
                <span class="gm-inside">₹</span>
                <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Total principal</label>
            </div>
            <div class="input-group position-relative ">
                <input class="form-control h-32px btn-roundhalf  floatdigit text-center" type="text" name="desire_interest" id="desire_interest" placeholder="Total Interest" value="" style="font-weight:bold;" readonly >
                <span class="gm-inside">₹</span>
                <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Total Interest</label>
            </div>
        </div>
        <div class="col-md-3 mb-1">
            <div class="input-group position-relative">
                <input class="form-control h-32px btn-roundhalf  floatdigit text-center" type="text" placeholder="Pay Principal" name="pay_principal" id="pay_principal" style="font-weight:bold;" value="0">
                <span class="gm-inside">₹</span>
                <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Pay principal</label>
            </div>
            <div class="input-group position-relative">
                <input class="form-control h-32px btn-roundhalf floatdigit text-center" type="text" name="pay_interest" id="pay_interest" placeholder="Pay Interest" style="font-weight:bold;" value="">
                <span class="gm-inside">₹</span>
                <label for="issue" class="floating-label" style="top:-15px;font-size: 11px;color: #333;">Pay Interest</label>
            </div>
        </div>
        <div class="col-md-3 mb-1 ">
            <div class="input-group position-relative">
                <input class="form-control h-32px btn-roundhalf text-center" type="date" name="pay_date"  id="pay_date" value="{{ date('Y-m-d') }}" value="">
                <label for="issue" class="floating-label">Payment Date</label>
            </div>
            <div class="input-group position-relative">
                <select name="int_medium"  id="int_medium" class="form-select h-32px btn-roundhalf text-center">
                    <option value="">Select</option>
                    <option value="on">Online</option>
                    <option value="cash">Cash</option>
                </select>
                <span class="custom-select-arrow">▼</span>
                <label for="int_medium" class="floating-label" style="top:-10px;font-size:11px;color: #333;">Pay Medium</label>
            </div>
        </div>
        <div class="col-12 text-center mb-1 pt-2" style="border-top:1px dashed gray;">
            <input type="text" name="int_remark" value="" class="form-control h-32px btn-roundhalf text-center" placeholder="Remark">
        </div>
    </div>
    <div class="row col-12 p-0 m-auto">
        <div class="col-12 text-center mb-1 p-0  mt-2 pt-2"  style="border-top:1px dashed #f95600;">
            <button type="submit" name="do" value="save" class="btn btn-sm btn-success">PAY</button>
            <button type="submit" name="do" value="print" class="btn btn-sm btn-info">PRINT</button>
        </div>
    </div>
</div>