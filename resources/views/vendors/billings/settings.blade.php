@extends('layouts.vendors.app')

@section('content')
@php
$data = component_array('breadcrumb', 'Bill Settings', [['title' => 'Settings']]);
@endphp

<x-page-component :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Bill/Invoice Settings</h3>
                    </div>
                    <div class="card-body">
                        <form id="settingsForm" method="POST" action="{{ route('bill.settings') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">

                                    {{-- Bill Format --}}
                                    <div class="form-group">
                                        <label>Bill Format</label>
                                        <select name="bill_format" id="billFormat" class="form-control">
                                            <option value="format1" {{ ($shop->bill_format ?? 'format1') == 'format1' ? 'selected' : '' }}>Format 1 - Classic</option>
                                            <option value="format2" {{ ($shop->bill_format ?? 'format1') == 'format2' ? 'selected' : '' }}>Format 2 - Professional</option>
                                            <option value="format3"{{ ($shop->bill_format ?? 'format1') == 'format3' ? 'selected' : '' }}>Format 3 - Premium Dark</option>

                                        </select>
                                    </div>

                                    {{-- ⭐ ADDED: BILL FORMAT PREVIEW SECTION --}}
        <style>
        /* Old styling remains same */
        .bill-format-preview {
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 4px;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        /* ACTIVE selection highlight */
        .bill-format-preview.active {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0,123,255,0.6);
        }

        /* IMAGE default */
         .bill-format-preview.active .bill-format-preview img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform .25s ease;
        }

        /* ---- SLIDER SYSTEM ---- */
        .bill-format-slider {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding-bottom: 10px;
            scroll-behavior: smooth;
            scrollbar-width: none; 
        }
        .bill-format-slider::-webkit-scrollbar { display: none; }

        /* side formats smaller */
        .bill-format-preview:not(.active) img {
            transform: scale(0.85);
            opacity: 0.7;
        }

        /* active format bigger */
        .bill-format-preview.active img {
            transform: scale(1.1);
            opacity: 1;
        }

        .bill-format-preview {
            min-width: 140px; 
            scroll-snap-align: center;
        }

.bill-format-slider {
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding: 20px 40px; /* left/right padding added */
    scroll-behavior: smooth;
    scrollbar-width: none;
    justify-content: center;
    position: relative;
}

.bill-format-slider::-webkit-scrollbar { display: none; }

/* preview card */
.bill-format-preview {
    min-width: 160px;
    transition: 0.3s ease;
    scroll-snap-align: center;
    transform: scale(0.85);
    opacity: 0.6;
}

/* ACTIVE — bigger, centered */
.bill-format-preview.active {
    transform: scale(1.15);
    opacity: 1;
    z-index: 2;
}

/* active image full */
.bill-format-preview.active img {
    transform: scale(1);
}

/* non-active image smaller */
.bill-format-preview:not(.active) img {
    transform: scale(0.80);
    opacity: 0.6;
}

/* image */
.bill-format-preview img {
    width: 100%;
    border-radius: 10px;
    transition: 0.3s ease;
}

        
    </style>
    <style>
    .remove-preview-btn{
        position: absolute;
    }
</style>

                                  <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="bill-format-slider">

                                            <div id="preview1" class="bill-format-preview">
                                                <img src="{{ asset('assets/images/billformetepreview/formate_1.png') }}" alt="Format 1 Preview">
                                                <small class="d-block text-center font-weight-bold mt-1">Classic</small>
                                            </div>

                                            <div id="preview2" class="bill-format-preview">
                                                <img src="{{ asset('assets/images/billformetepreview/formate_2.png') }}" alt="Format 2 Preview">
                                                <small class="d-block text-center font-weight-bold mt-1">Professional</small>
                                            </div>

                                            <div id="preview3" class="bill-format-preview">
                                                <img src="{{ asset('assets/images/billformetepreview/formate_3.png') }}" alt="Format 3 Preview">
                                                <small class="d-block text-center font-weight-bold mt-1">Premium Dark</small>
                                            </div>

                                        </div>
                                    </div>
                                

                                </div>

                                    {{-- ⭐ PREVIEW END --}}
                                            <div class="row">

                                                <!-- Shop Logo -->
                                                <div class="col-md-4">
                                                    <div class="form-group te">
                                                        <label>Shop Logo</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="shop_logo" name="shop_logo" accept="image/*">
                                                            <label class="custom-file-label" for="shop_logo">Choose logo file</label>
                                                        </div>

                                                        @if($shop->logo_path)
                                                        <div class="mt-2 position-relative preview-wrap" style="display:inline-block;">
                                                            <img src="{{ asset($shop->logo_path) }}" alt="Shop Logo" style="max-height: 80px; max-width: 200px;" class="img-thumbnail">
                                                            <button type="button" class="remove-preview-btn" data-type="logo" title="Remove logo">✕</button>
                                                            <small class="form-text text-muted d-block">Current logo</small>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Authorized Signature -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Authorized Signature</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="signature" name="signature" accept="image/*">
                                                            <label class="custom-file-label" for="signature">Choose signature file</label>
                                                        </div>

                                                        @if($shop->signature_path)
                                                        <div class="mt-2 position-relative preview-wrap" style="display:inline-block;">
                                                            <img src="{{ asset($shop->signature_path) }}" alt="Signature" style="max-height: 60px; max-width: 150px;" class="img-thumbnail">
                                                            <button type="button" class="remove-preview-btn" data-type="signature" title="Remove signature">✕</button>
                                                            <small class="form-text text-muted d-block">Current signature</small>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Watermark -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Bill Watermark Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="watermark" name="watermark" accept="image/*">
                                                            <label class="custom-file-label" for="watermark">Choose watermark file</label>
                                                        </div>

                                                        @if($shop->watermark_path)
                                                        <div class="mt-2 position-relative preview-wrap" style="display:inline-block;">
                                                            <img src="{{ asset($shop->watermark_path) }}" alt="Watermark" style="max-height: 80px; max-width: 200px; opacity:0.5" class="img-thumbnail">
                                                            <button type="button" class="remove-preview-btn" data-type="watermark" title="Remove Watermark">✕</button>
                                                            <small class="form-text text-muted d-block">Current Watermark</small>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                                                            

                                                                            


                                                                                

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice Terms & Conditions</label>
                                        <textarea name="invoice_terms" class="form-control" rows="8" placeholder="Enter your terms and conditions...">{{ $shop->invoice_terms ?? '1. Goods once sold will not be taken back or exchanged.
                                        2. All disputes are subject to jurisdiction only.
                                        3. Interest @ 18% p.a. will be charged if payment is delayed.
                                        4. Certified that the particulars given above are true and correct.
                                        5. This is a computer generated invoice.' }}</textarea>
                                        <small class="form-text text-muted">Enter each term on a new line</small>
                                    </div>


                                 <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title text-dark">Bill Columns Customization </h4> 
                                                <small class="text-muted">&nbsp;Select which columns to display in your bills</small>
                                              

                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @php
                                                        $availableColumns = [
                                                            'item' => 'Item Name',
                                                            'caret' => 'Caret/Purity', 
                                                            'piece' => 'Piece Count',
                                                            'gross' => 'Gross Weight',
                                                            'less' => 'Less Weight',
                                                            'net' => 'Net Weight',
                                                            'tunch' => 'Tunch',
                                                            'wastage' => 'Wastage',
                                                            'fine' => 'Fine Weight',
                                                            'st_ch' => 'Stone Charge',
                                                            'rate' => 'Rate',
                                                            'labour' => 'Labour',
                                                            'other' => 'Other Charges',
                                                            'disc' => 'Discount',
                                                            'total' => 'Total Amount'
                                                        ];
                                                        
                                                        $selectedColumns = isset($shop->bill_columns) ? json_decode($shop->bill_columns, true) : array_keys($availableColumns);
                                                    @endphp
                                                    
                                                    @foreach($availableColumns as $key => $label)
                                                        <div class="col-md-4 mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" 
                                                                    class="form-check-input bill-column-checkbox" 
                                                                    name="bill_columns[]" 
                                                                    value="{{ $key }}" 
                                                                    id="column_{{ $key }}"
                                                                    {{ in_array($key, $selectedColumns) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="column_{{ $key }}">
                                                                    {{ $label }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                          <small class="text-danger d-block">* You can select a maximum of 8 columns</small>
                                                        <button type="button" id="selectAllColumns" class="btn btn-sm btn-outline-primary">Select All</button>
                                                        <button type="button" id="deselectAllColumns" class="btn btn-sm btn-outline-secondary">Deselect All</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                    <a href="{{ route('billing.view', ['sale', $bill->bill_number ?? '']) }}" class="btn btn-secondary">Back to Bill</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.preview-wrap { position: relative; }
.remove-preview-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: #fff;
    border: none;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    font-weight: 700;
    line-height: 20px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    cursor: pointer;
    padding: 0;
}
.remove-preview-btn:hover { opacity: 0.9; }
</style>
@endsection

@section('javascript')
<script>
    
$(document).ready(function() {

    // ⭐ ADDED: Bill format click + active highlight
// ⭐ UPDATE PREVIEW BASED ON SELECTED OPTION
function updatePreview() {
    let selected = $('#billFormat').val();

    // Remove active from all previews
    $('#preview1, #preview2, #preview3').removeClass('active');

    if (selected == "format1") {
        $('#preview1').addClass('active');
    }
    else if (selected == "format2") {
        $('#preview2').addClass('active');
    }
    else if (selected == "format3") {
        $('#preview3').addClass('active');
    }
}

updatePreview();
$('#billFormat').change(updatePreview);

// Click Handlers
$('#preview1').click(function () {
    $('#billFormat').val('format1').trigger('change');
});

$('#preview2').click(function () {
    $('#billFormat').val('format2').trigger('change');
});

$('#preview3').click(function () {
    $('#billFormat').val('format3').trigger('change');
});


updatePreview();
$('#billFormat').change(updatePreview);

// ⭐ CLICK EVENTS — select on click
$('#preview1').click(function () {
    $('#billFormat').val('format1').trigger('change');
});

$('#preview2').click(function () {
    $('#billFormat').val('format2').trigger('change');
});

$('#preview3').click(function () {
    $('#billFormat').val('format3').trigger('change');
});


    // File input label update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Form submit
    $('#settingsForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    success_sweettoatr(response.msg);
                    setTimeout(() => { location.reload(); }, 1500);
                } else {
                    toastr.error(response.msg || 'Error saving settings');
                }
            },
            error: function(xhr) {
                toastr.error('Error saving settings');
            }
        });
    });

    // REMOVE logo/signature/watermark
// REMOVE logo/signature/watermark — use the correct route names
$('.remove-preview-btn').click(function () {
    let type = $(this).data('type');
    let url = '';

    if (type === "logo") url = "{{ route('bill.settings.remove-logo') }}";
    if (type === "signature") url = "{{ route('bill.settings.remove-signature') }}";
    if (type === "watermark") url = "{{ route('bill.settings.remove-watermark') }}";

    let btn = $(this);

    $.ajax({
        url: url,
        type: "POST",
        data: {_token: "{{ csrf_token() }}"},
        success: function (res) {
            if (res.status) {
                success_sweettoatr(res.msg);
                btn.closest('.preview-wrap').fadeOut(300, function(){ $(this).remove(); });
            } else {
                toastr.error(res.msg || 'Failed to remove');
            }
        },
        error: function () {
            toastr.error("Something went wrong!");
        }
    });
});
// Column selection handlers
$('#selectAllColumns').click(function() {
    $('.bill-column-checkbox').prop('checked', true);
});

$('#deselectAllColumns').click(function() {
    $('.bill-column-checkbox').prop('checked', false);
});

// Ensure at least one column is selected
$('#settingsForm').submit(function(e) {
    const checkedColumns = $('.bill-column-checkbox:checked').length;
    if (checkedColumns === 0) {
        e.preventDefault();
        toastr.error('Please select at least one column to display in the bill.');
        return false;
    }
});


// ⭐ LIMIT: Max 8 columns allowed
$('.bill-column-checkbox').change(function () {
    let selectedCount = $('.bill-column-checkbox:checked').length;

    if (selectedCount > 8) {
        $(this).prop('checked', false); // undo last selection
        toastr.error('You can select maximum 8 columns only.');
        return false;
    }
});



});

// Center scroll function
function centerPreview(previewId) {
    let container = document.querySelector('.bill-format-slider');
    let item = document.getElementById(previewId);
    let containerWidth = container.offsetWidth;
    let itemLeft = item.offsetLeft;
    let itemWidth = item.offsetWidth;

    container.scrollLeft = itemLeft - (containerWidth / 2) + (itemWidth / 2);
}

// Auto-center on load based on selected format
setTimeout(() => {
    if ($('#billFormat').val() === 'format1') centerPreview('preview1');
    if ($('#billFormat').val() === 'format2') centerPreview('preview2');
    if ($('#billFormat').val() === 'format3') centerPreview('preview3');
}, 200);

// When user clicks → center the preview
$('#preview1, #preview2, #preview3').click(function () {
    let id = $(this).attr('id');
    centerPreview(id);
});


</script>
@endsection
