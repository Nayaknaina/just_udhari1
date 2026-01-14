<div class="card mb-1 mt-3 shadow-sm border-0" id="item_row_{{ $index }}" style="border-radius: 12px; background: #fff; position: relative;">
    
    <!-- Item Number Badge -->
    <div class="position-absolute" style="top: -10px; left: 10px; z-index: 10;">
        <span class="badge badge-primary shadow-sm px-2 py-1 girvi-number-badge" style="font-size: 0.75rem; border-radius: 20px; box-shadow: 0 3px 6px rgba(0,0,0,0.15) !important;">
            ITEM #{{ is_numeric($index) ? ($index + 1) : 'ITEM_NUMBER_PLACEHOLDER' }}
        </span>
    </div>

    @if($index != 0)
        <button type="button" class="btn btn-xs btn-light text-danger position-absolute shadow-sm" style="top: -6px; right: -6px; border-radius: 50%; width: 24px; height: 24px; padding: 0; border:1px solid #ffdede; z-index: 5;" onclick="removeItemRow({{ $index }})" title="Remove Item">
            <i class="fa fa-times"></i>
        </button>
    @endif

    <div class="card-body p-1 pt-3">
        <div class="d-flex align-items-center mb-2">
            <div class="flex-grow-1">
                 <div class="row no-gutters">
                    <!-- Left Column: Category & Rate -->
                    <div class="col-5 pr-1">
                        <!-- Category Select -->
                        <div class="mb-2 position-relative">
                             <style>
                                .custom-compact-select {
                                    appearance: none;
                                    -webkit-appearance: none;
                                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 12 12'%3E%3Cpath fill='%23555' d='M6 8.825L1.175 4 2.238 2.938 6 6.7l3.763-3.762L10.825 4z'/%3E%3C/svg%3E");
                                    background-repeat: no-repeat;
                                    background-position: right 4px center;
                                    background-size: 8px;
                                    padding-right: 15px !important;
                                    padding-left: 8px !important;
                                }
                            </style>
                            <select name="category[{{ $index }}]" class="form-control form-control-sm btn-roundhalf border shadow-sm font-weight-bold text-dark custom-compact-select" onchange="toggleWeights({{ $index }}); calculateItemRow({{ $index }});" required style="width: 100%; height: 30px; font-size: 0.8rem; padding-top: 2px; padding-bottom: 2px;">
                                <option value="" disabled selected hidden>Select Category</option>
                                <option value="Gold">Gold</option>
                                <option value="Silver">Silver</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <!-- Rate Field with Dynamic Label -->
                        <div class="position-relative mt-3">
                            <input type="number" name="rate[{{ $index }}]" id="rate_{{ $index }}" class="form-control form-control-sm btn-roundhalf bg-white border shadow-sm text-center font-weight-bold rate-input" oninput="calculateItemRow({{ $index }})" placeholder="Market Rate" style="height: 30px; font-size: 0.8rem; padding: 2px;">
                            <label for="rate_{{ $index }}" class="rate-floating-label shadow-sm">Rate</label>
                            
                            <!-- Embedded Style for this specific behavior -->
                            <style>
                                .rate-floating-label {
                                    display: none;
                                    position: absolute;
                                    top: -10px;
                                    left: 5px;
                                    font-size: 0.65rem;
                                    background: #fff;
                                    padding: 1px 6px;
                                    color: #ff6e26;
                                    font-weight: bold;
                                    border-radius: 10px;
                                    border: 1px solid #ffdece;
                                    pointer-events: none;
                                    line-height: normal;
                                    z-index: 100;
                                    white-space: nowrap;
                                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                                }
                                .rate-input:not(:placeholder-shown) + .rate-floating-label,
                                .rate-input:focus + .rate-floating-label {
                                    display: block;
                                }
                                .rate-input::placeholder {
                                    font-size: 0.75rem;
                                    color: #999;
                                    font-weight: normal;
                                }
                            </style>
                        </div>
                    </div>

                    <!-- Right Column: Name & Value -->
                    <div class="col-7 pl-1">
                        <!-- Item Name Input -->
                        <div class="mb-2">
                             <input type="text" name="detail[{{ $index }}]" class="form-control form-control-sm btn-roundhalf border shadow-sm pl-2" placeholder="Item Name" required style="height: 30px; font-size: 0.8rem; padding: 2px 8px;">
                        </div>
                        
                        <!-- Value Field -->
                        <div>
                             <input type="text" name="value[{{ $index }}]" id="value_{{ $index }}" class="form-control form-control-sm btn-roundhalf bg-light border-0 text-success font-weight-bold text-right pr-2" oninput="calculateTotals()" placeholder="Value" style="height: 30px; font-size: 0.8rem; padding: 2px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Upload (Square & Larger) - Moved to Right -->
            <div class="ml-3 position-relative text-center" style="width: 70px; height: 70px; flex-shrink: 0;">
                <label class="m-0 d-block w-100 h-100" style="cursor: pointer;">
                    <span class="d-flex align-items-center justify-content-center bg-light text-muted rounded shadow-sm overflow-hidden border" style="width: 100%; height: 100%; border-color: #ddd !important; position: relative;">
                        <!-- Default Icon -->
                        <div id="camera_icon_{{ $index }}" class="text-center">
                             <i class="fa fa-camera fa-2x mb-1 text-primary-50"></i>
                             <small class="d-block font-xs text-muted" style="font-size: 9px; line-height: 1;">ADD PHOTO</small>
                        </div>
                        <!-- Preview Image (Hidden initially) -->
                        <img id="preview_img_{{ $index }}" src="" alt="Item" style="width: 100%; height: 100%; object-fit: contain; display: none;">
                    </span>
                    <input type="file" name="image[{{ $index }}]" accept="image/*" style="display: none;" onchange="previewImage(this, {{ $index }})">
                </label>
            </div>
        </div>

        <!-- Hidden Weights Section (Only for Gold/Silver) -->
        <!-- Hidden Weights Section (Only for Gold/Silver) -->
        <div id="weights_section_{{ $index }}" class="weights-section mt-2" style="display: none;">
            <div class="bg-light rounded p-2 border" style="border-color: #f0f0f0 !important;">
                <div class="row no-gutters">
                     <div class="col-3 px-1">
                        <div class="text-center">
                            <label class="d-block text-muted font-weight-bold mb-1" style="font-size: 0.7rem;">GROSS</label>
                            <input type="number" step="0.01" name="gross[{{ $index }}]" id="gross_{{ $index }}" class="form-control form-control-sm btn-roundhalf border shadow-sm text-center font-weight-bold text-dark" oninput="calculateItemRow({{ $index }})" placeholder="0.00" style="height: 28px; font-size: 0.8rem; padding: 0;">
                        </div>
                    </div>
                    <div class="col-3 px-1">
                        <div class="text-center">
                            <label class="d-block text-muted font-weight-bold mb-1" style="font-size: 0.7rem;">NET</label>
                            <input type="number" step="0.01" name="net[{{ $index }}]" id="net_{{ $index }}" class="form-control form-control-sm btn-roundhalf border shadow-sm text-center font-weight-bold text-dark" oninput="calculateItemRow({{ $index }})"  placeholder="0.00" style="height: 28px; font-size: 0.8rem; padding: 0;">
                        </div>
                    </div>
                    <div class="col-3 px-1">
                        <div class="text-center">
                            <label class="d-block text-muted font-weight-bold mb-1" style="font-size: 0.7rem;">PURITY</label>
                            <input type="number" step="0.01" name="pure[{{ $index }}]" id="pure_{{ $index }}" class="form-control form-control-sm btn-roundhalf border shadow-sm text-center font-weight-bold text-primary" oninput="calculateItemRow({{ $index }})" placeholder="91.6" style="height: 28px; font-size: 0.8rem; padding: 0;">
                        </div>
                    </div>
                     <div class="col-3 px-1">
                        <div class="text-center">
                            <label class="d-block text-muted font-weight-bold mb-1" style="font-size: 0.7rem;">FINE</label>
                            <input type="text" name="fine[{{ $index }}]" id="fine_{{ $index }}" class="form-control form-control-sm btn-roundhalf border-0 shadow-sm text-center font-weight-bold text-success bg-white" readonly placeholder="0.00" style="height: 28px; font-size: 0.8rem; padding: 0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
