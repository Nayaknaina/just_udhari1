@extends('layouts.vendors.app')

@section('css')
<style>
    :root {
        --primary-color: #ffd30e; /* Gold */
        --primary-dark: #bfa300;
        --secondary-color: #1e293b; /* Dark Slate */
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --light-bg: #f8fafc;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --radius: 1rem;
    }

    body {
        background-color: #e0e0e4;
        font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* General Card Styling */
    .card {
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--hover-shadow);
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 700;
        padding: 1.25rem 1.5rem;
        letter-spacing: 0.5px;
    }

    .form-control {
        border-radius: 0.75rem;
        border: 2px solid #e2e8f0;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 211, 14, 0.2);
    }

    /* Customer Select Section - Glassmorphism & Compact */
    /* Customer Select Section - Glassmorphism & Compact */
    .customer-section {
        background: rgba(255, 255, 255, 0.9); /* More sleek/premium white */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        margin-bottom: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 1);
        border-bottom: 3px solid rgba(255, 77, 0, 0.35); /* Premium Orange Border */
        /* Added negative Y offset for top shadow */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 -5px 15px -5px rgba(0, 0, 0, 0.05); 
        position: relative; 
        z-index: 50;
    }

    /* Distinct Search Box Wrapper */
    .customer-input-group {
        background: #fff; /* Solid white background */
        border-radius: 50px; /* Pill shape for the input itself */
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); /* Subtle shadow inner */
        padding: 5px; /* Padding for the inner elements */
        transition: all 0.3s ease;
    }
    
    .customer-input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 211, 14, 0.15);
        transform: translateY(-1px);
    }

    /* Search Icon Container */
    .input-group-prepend {
        display: flex;
        align-items: center;
        padding-left: 15px;
        padding-right: 10px; /* Added spacing */
        color: var(--secondary-color);
        height: 100%;
    }
    
    .customer-input-group .form-control {
        border: none;
        background: transparent;
        height: 45px;
        line-height: 45px;
        font-size: 1rem;
        font-weight: 500;
        box-shadow: none;
        padding-left: 0; /* padding handled by prepend container margin/padding */
        color: #1e293b;
        display: flex; 
        align-items: center;
    }
    
    .customer-input-group .btn {
        height: 45px;
        padding: 0 1.5rem;
        background: #1e293b; 
        color: #ffd30e;
        border-radius: 50px !important;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        box-shadow: none;
        transition: all 0.2s;
    }
    
    .customer-input-group .btn:hover {
        background: #0f172a;
        transform: scale(1.02);
    }
    
    .section-label {
        font-size: 0.75rem; 
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        display: block; /* Bring back label for clarity */
    }

    /* Date Filter Compact */
    .date-compact {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        height: auto;
        width: auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .date-compact:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(255, 211, 14, 0.2);
    }

    /* Header Action Buttons (List/Ledger) */
    .btn-header-light {
        background: white;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        border-radius: 50px !important;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-header-light:hover {
        background: #f1f5f9; /* Light Gray on hover */
        color: #0f172a; /* Darker text */
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    /* Customer Dropdown */
    #customerlist {
        list-style-type: none;
        padding: 0.5rem 0;
        margin: 0;
        background-color: white;
        border: none;
        border-radius: 0.75rem;
        width: 100%;
        display: none;
        position: absolute;
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        top: 100%;
        margin-top: 10px;
    }

    #customerlist.active {
        display: block;
    }

    #customerlist li {
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 500;
        transition: background 0.15s;
    }

    #customerlist li:last-child {
        border-bottom: none;
    }

    #customerlist li:hover, #customerlist li.hover {
        background-color: #fefce8; /* Light yellow bg */
        color: var(--primary-dark);
        font-weight: 600;
    }
    
    /* Transaction Blocks */
    .transaction-card {
        background: white;
    }

    .transaction-card .card-body {
        padding: 0.75rem; /* Reduced padding */
    }

    .transaction-card .card-header {
        padding: 0.75rem 1rem; /* Compact Header */
    }
    
    .block-cash { border-top: 4px solid var(--success-color); }
    .block-gold { border-top: 4px solid var(--warning-color); }
    .block-silver { border-top: 4px solid #94a3b8; }

    /* Cash Block */
    .block-cash .card-header { color: var(--success-color); }
    .block-cash .u-label { color: var(--success-color); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;}
    
    /* Gold Block */
    .block-gold .card-header { color: var(--warning-color); }
    .block-gold .u-label { color: var(--warning-color); font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;}

    /* Silver Block */
    .block-silver .card-header { color: #475569; }
    .block-silver .u-label { color: #64748b; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;}

    /* Inputs within blocks */
    .udhar_block .form-group {
        margin-bottom: 0.75rem; /* Reduced margin */
    }

    /* Premium Input Wrapper */
    .input-wrapper {
        position: relative;
        background-color: #f8fafc; /* Subtle gray bg */
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .input-wrapper:focus-within {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 211, 14, 0.15);
        transform: translateY(-1px);
    }

    /* Input inside wrapper */
    .input-wrapper .form-control {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        padding-right: 45px; /* Space for unit */
        height: 30px;
        font-weight: 600;
        color: #1e293b;
    }

    .unit-badge {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: #e2e8f0;
        color: #64748b;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 700;
        pointer-events: none;
        letter-spacing: 0.5px;
    }

    .form-control[readonly] {
        background-color: transparent; /* Parent wrapper handles bg */
        color: #64748b;
    }
    
    .text-danger { color: #ef4444 !important; }
    .text-success { color: #10b981 !important; }
    
    /* Specific Border/Text Colors for focused/active states */
    .input-wrapper:focus-within:has(.text-danger) {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }
    
    .input-wrapper:focus-within:has(.text-success) {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }
    
    .border-danger { border-color: transparent !important; } /* Override default form control border */
    .border-success { border-color: transparent !important; }

    /* Bhav Cut Section */
    .bhav-cut-wrapper {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border-top: 4px solid #ff4d00;
        display: none;
        margin-top: 1.5rem;
    }

    #bhav_cut_table th, #bhav_cut_table td {
        vertical-align: middle;
        padding: 0.75rem;
        border-color: #e2e8f0;
        color: #1e293b !important; /* Force black text */
    }

    #bhav_cut_table input {
        text-align: center;
        font-weight: 600;
        color: #1e293b;
    }

    .toggle_button {
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s;
    }


    
    /* Responsive tweaks */
    @media (max-width: 768px) {
        .customer-section {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')

@php 
$anchor = ['<a href="'.route('udhar.index').'" class="btn btn-sm btn-light text-primary fw-bold shadow-sm"><i class="fa fa-list"></i> List</a>','<a href="'.route('udhar.ledger').'" class="btn btn-sm btn-light text-primary fw-bold shadow-sm"><i class="fa fa-book"></i> Ledger</a>'];
$data = new_component_array('newbreadcrumb',"New Udhar") 
@endphp 
<x-new-bread-crumb :data=$data :anchor=$anchor/> 
    
    <section class="content pb-5">
        <div class="container-fluid">
            
            <form id="udhar_form" action="{{ route('udhar.store') }}" role="form" autocomplete="off">
                @csrf
                
                <!-- Customer Selection Hero -->
                <div class="row block_at_bhav_cut">
                    <div class="col-12">
                        <div class="customer-section">
                            <div class="row align-items-end">
                                <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                                    <!-- <label class="section-label">Select Customer</label> -->
                                    <div class="input-group customer-input-group position-relative align-items-center">
                                        <div class="input-group-prepend">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <input type="text" name="name" id="name" class="form-control myselect" placeholder="Search by Name or Mobile..." oninput="getcustomer($(this))">
                                        <input type="hidden" name="custo_id" value="">
                                        <input type="hidden" name="udhar_id" value="">
                                        <div class="input-group-append">
                                            <button type="button" class="btn" data-toggle="modal" data-target="#custo_modal">
                                                New <i class="fa fa-plus-circle ml-2"></i>
                                            </button>
                                        </div>
                                        <ul id="customerlist"></ul>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 text-lg-right text-left d-flex align-items-center justify-content-lg-end justify-content-start mt-2 mt-lg-0">
                                    <a href="{{ route('udhar.index') }}" class="btn btn-header-light mr-2 d-flex align-items-center justify-content-center shadow-sm">
                                        <i class="fa fa-list mr-2" style="color: #64748b;"></i> List
                                    </a>
                                    <a href="{{ route('udhar.ledger') }}" class="btn btn-header-light d-flex align-items-center justify-content-center shadow-sm">
                                        <i class="fa fa-book mr-2" style="color: #64748b;"></i> Ledger
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Blocks -->
                <div class="row block_at_bhav_cut">
                    
                    <!-- Cash Block -->
                    <div class="col-lg-5 col-md-12 mb-4">
                        <div class="card transaction-card block-cash h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="h5 m-0"><i class="fa fa-money-bill-wave mr-2"></i> Cash Transaction</span>
                                <img src="{{ asset('main/assets/cstm_imgs/game-icons_take-my-money.svg') }}" alt="Money" width="24" height="24" style="opacity: 0.6">
                            </div>
                            <div class="card-body udhar_block bg-white">
                                <div class="row">
                                    <!-- Old -->
                                    <div class="col-12 form-group">
                                        <label class="u-label">Previous Balance</label>
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control cash_block" name="cash_old" readonly>
                                            <span class="unit-badge">₹</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Udhar/Out -->
                                    <div class="col-12 form-group">
                                        <label class="u-label text-danger">Udhar / Given (-)</label>
                                        <div class="row">
                                            <div class="col-6 pr-1">
                                                <div class="input-wrapper">
                                                    <input type="text" class="form-control cash_block border-danger text-danger udhar_input_field" name="cash_out_off" data-target="cash" placeholder="Cash">
                                                    <span class="unit-badge">₹</span>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-1">
                                                <div class="input-wrapper">
                                                    <input type="text" class="form-control cash_block border-danger text-danger udhar_input_field" name="cash_out_on" data-target="cash" placeholder="Online">
                                                    <span class="unit-badge">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jama/In -->
                                    <div class="col-12 form-group">
                                        <label class="u-label text-success">Jama / Received (+)</label>
                                        <div class="row">
                                            <div class="col-6 pr-1">
                                                <div class="input-wrapper">
                                                    <input type="text" class="form-control cash_block border-success text-success udhar_input_field" name="cash_in_off" data-target="cash" placeholder="Cash">
                                                    <span class="unit-badge">₹</span>
                                                </div>
                                            </div>
                                            <div class="col-6 pl-1">
                                                <div class="input-wrapper">
                                                    <input type="text" class="form-control cash_block border-success text-success udhar_input_field" name="cash_in_on" data-target="cash" placeholder="Online">
                                                    <span class="unit-badge">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Final -->
                                    <div class="col-12 form-group mb-0">
                                        <label class="u-label">Net Closing Balance</label>
                                        <div class="input-wrapper">
                                            <input type="text" class="form-control input-lg cash_block" name="cash_final" readonly style="font-size: 1.1em;">
                                            <span class="unit-badge">₹</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metal Blocks -->
                    <div class="col-lg-7 col-md-12">
                        <div class="row">
                            <!-- Gold -->
                            <div class="col-md-6 col-12 mb-4">
                                <div class="card transaction-card block-gold h-100">
                                    <div class="card-header">
                                        <span class="h5 m-0"><i class="fa fa-ring mr-2"></i> Gold</span>
                                    </div>
                                    <div class="card-body udhar_block bg-white">
                                        <div class="form-group">
                                            <label class="u-label">Old Balance</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control gold_block" name="gold_old" readonly>
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="u-label text-danger">Given (-)</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control gold_block border-danger text-danger udhar_input_field" name="gold_out_off" data-target="gold">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="u-label text-success">Received (+)</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control gold_block border-success text-success udhar_input_field" name="gold_in_off" data-target="gold">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="u-label">Net Balance</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control gold_block" name="gold_final" readonly style="font-size: 1.1em;">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Silver -->
                            <div class="col-md-6 col-12 mb-4">
                                <div class="card transaction-card block-silver h-100">
                                    <div class="card-header">
                                        <span class="h5 m-0"><i class="fa fa-coins mr-2"></i> Silver</span>
                                    </div>
                                    <div class="card-body udhar_block bg-white">
                                        <div class="form-group">
                                            <label class="u-label">Old Balance</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control silver_block" name="silver_old" readonly>
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="u-label text-danger">Given (-)</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control silver_block border-danger text-danger udhar_input_field" name="silver_out_off" data-target="silver">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="u-label text-success">Received (+)</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control silver_block border-success text-success udhar_input_field" name="silver_in_off" data-target="silver">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="u-label">Net Balance</label>
                                            <div class="input-wrapper">
                                                <input type="text" class="form-control silver_block" name="silver_final" readonly style="font-size: 1.1em;">
                                                <span class="unit-badge">gm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              

                <!-- Footer / Bhav Cut Section -->
                <!-- Footer / Bhav Cut Section -->
                <!-- Footer / Bhav Cut Section -->
                <!-- Footer / Bhav Cut Section -->
                <div class="row justify-content-center">
                    <div class="col-12 w-100 p-0">
                        <!-- Top Row: Bhav Cut Toggle (Right Aligned) -->
                        <!--<div class="text-right mb-2">
                             <label class="btn btn-secondary toggle_button shadow-sm" for="chav_cut_check" id="bhaw_cut_button">
                                <span>Bhav Cut</span> <i class="fa fa-chevron-down ml-2"></i>
                                <input type="checkbox" name="bhav_cut" value="yes" id="chav_cut_check" style="display:none;" onchange="$('#bhav_cut').slideToggle(); $(this).closest('label').toggleClass('btn-secondary btn-outline-secondary');">
                            </label>
                        </div>-->
                        <!-- Top Row: Bhav Cut Toggle (Right Aligned) -->
                        <div class="text-right mb-2">
                             <!-- Note: Using absolute position as per user previous edit -->
                             <!-- Added ID for text and icon spans to target them easily -->
                             <label class="btn btn-secondary toggle_button shadow-sm mt-2" for="chav_cut_check" id="bhaw_cut_button" style="position:absolute; right:0; transition: all 0.3s ease; z-index: 100;">
                                <span id="bhav_text">Bhav Cut</span> 
                                <i id="bhav_icon" class="fa fa-chevron-down ml-2"></i>
                                <input type="checkbox" name="bhav_cut" value="yes" id="chav_cut_check" style="display:none;" 
                                    onchange="
                                        $('#bhav_cut').slideToggle(); 
                                        var btn = $(this).closest('label');
                                        btn.toggleClass('btn-secondary btn-outline-secondary');
                                        if(this.checked){
                                            $('#bhav_text').text('');
                                            $('#bhav_icon').removeClass('fa-chevron-down ml-2').addClass('fa fa-minus');
                                            btn.css({'padding': '0.2rem 0.6rem', 'font-size': '0.9rem', 'z-index': '1000'}); // Make smaller and higher z-index
                                        } else {
                                            $('#bhav_text').text('Bhav Cut');
                                            $('#bhav_icon').removeClass('fa-minus').addClass('fa fa-chevron-down ml-2');
                                             btn.css({'padding': '', 'font-size': '', 'z-index': '100'}); // Reset styles
                                        }
                                    ">
                            </label>
                        </div>
                        <!-- Hidden Bhav Cut Table -->
                        <div id="bhav_cut" class="bhav-cut-wrapper text-left my-0 mx-auto mb-2" style="max-width: 100%;">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="bhav_cut_table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="15%">Action</th>
                                                <th width="10%"></th>
                                                <th width="25%" class="text-center">CASH</th>
                                                <th width="20%"></th>
                                                <th width="25%">
                                                    <select name="conver_into" class="form-control border-0 bg-transparent font-weight-bold text-center p-0" style="color: #1e293b;">
                                                        <option value="">SELECT METAL...</option>
                                                        <option value="gold">GOLD</option>
                                                        <option value="silver">SILVER</option>
                                                    </select>
                                                </th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">Final Balance</td>
                                                <td></td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_final_udhar text-center" name="bhav_final_udhar" id="bhav_final_udhar" readonly>
                                                        <span class="unit-badge">₹</span>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_final_cnvrt text-center" id="bhav_final_cnvrt" name="bhav_final_cnvrt" readonly>
                                                        <span class="unit-badge">gm</span>
                                                    </div>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Exchange</td>
                                                <td class="text-right">
                                                    <label class="btn btn-sm btn-outline-warning bhav_cnvrt_btn rounded-circle" id="bhav_cnvrt_left" for="direction_amount" style="width: 35px; height: 35px; padding: 0; line-height: 35px;">
                                                        <i class="fa fa-arrow-left"></i>
                                                        <input type="radio" id="direction_amount" name="direction" value="amount" style="display:none;">
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_cnvrt_from text-center convert_source" name="bhav_cnvrt_from" id="bhav_cnvrt_from" placeholder="Amount" style="background: transparent;">
                                                        <span class="unit-badge">₹</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control bhav_cnvrt_rate text-center" name="bhav_cnvrt_rate" id="bhav_cnvrt_rate" placeholder="Rate">
                                                        <div class="input-group-append">
                                                            <select name="cnvrt_unit" class="form-control small" style="width: 70px; padding: 0 5px;"> 
                                                                <option value="" class="metal">Units</option>
                                                                <option value="1" class="gold metal gold_1">/1gm</option>
                                                                <option value="10" class="gold metal gold_10">/10gm</option>
                                                                <option value="1" class="silver metal silver_1">/1kg</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_cnvrt_to text-center convert_source" name="bhav_cnvrt_to" id="bhav_cnvrt_to" placeholder="Weight" style="background: transparent;">
                                                        <span class="unit-badge">gm</span>
                                                    </div>
                                                </td>
                                                <td class="text-left">
                                                    <label class="btn btn-sm btn-outline-warning bhav_cnvrt_btn rounded-circle" id="bhav_cnvrt_right" for="direction_metal" style="width: 35px; height: 35px; padding: 0; line-height: 35px;">
                                                        <input type="radio" id="direction_metal" name="direction" value="metal" style="display:none;" >
                                                        <i class="fa fa-arrow-right"></i>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-muted">Remaining</td>
                                                <td></td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_udhar_money text-center" name="bhav_udhar_money" id="bhav_udhar_money" readonly style="background: transparent;">
                                                        <span class="unit-badge">₹</span>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <div class="input-wrapper">
                                                        <input type="text" class="form-control bhav_udhar_metal text-center" name="bhav_udhar_metal" id="bhav_udhar_metal" readonly style="background: transparent;">
                                                        <span class="unit-badge">gm</span>
                                                    </div>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>

                        <!-- Bottom Row: Remark & Action Buttons (Inline & Compact) -->
                        <div class="d-flex flex-wrap justify-content-center align-items-center mt-2">
                            <div class="form-group mb-0 mx-2" style="min-width: 350px;">
                                <input type="text" class="form-control text-center" id="remark" name="remark" placeholder="Add a note/remark for this transaction..." style="border-style: dashed; background: transparent;">
                            </div>
                            <div class="mx-2">
                                <button type="submit" name="action" value="print" class="btn btn-outline-secondary btn-lg m-1 px-4 shadow-sm" style="border-radius: 50px;">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <button type="submit" name="action" value="done" class="btn btn-primary btn-lg m-1 px-5 shadow-lg" style="border-radius: 50px;">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            
        </div>
    </section>
    @php $from = 4 @endphp
    @include('vendors.commonpages.newcustomerwithcategory')

@endsection
@section('javascript')
    @include('layouts.common.placeholdertolabel')
    <script>
        //const price = {'gold':1000,'silver':800};
        const price = @json($rate);
        function getcustomer(self){
            const title = self.val();
            if(title!=""){
                $.get("{{ route('global.customers.search') }}","keyword="+title,function(response){
                    var li = '';
                    if(response){
                        /*$(response).each(function(i,v){
                            var name = v.name??"NA";
                            var num = v.num??'NA';
                            var mob = v.mobile??'NA';
                            var id=v.id??0;
                            var stream = num+"/"+name+" - "+mob;
                            li+='<li><a href="{{ url('vendors/customer/udhardata') }}/'+id+'" data-target="'+stream+'" class="select_customer">'+stream+'</a></li>';
                        });*/
                    }
                    $("#customerlist").empty().append(response);
                    $("#customerlist").addClass('active');
                    positionmenu('#customerlist','#name');
                });
            }else{
                $('[name="custo_id"]').val('');
                $('[name="udhar_id"]').val('');
                $("#udhar_form").trigger('reset');
                $("#customerlist").removeClass('active');
                //$("#bottom_block").hide();
            }
        }
		
		/*$("#name").focusout(function(){
			setTimeout(function() {
			if (!$(document).find(':focus').hasClass('select_customer')) {
					$("#customerlist").hide();
				}
			}, 5); 
        });*/
		
        /*$("#name").focusin(function(){
            const customer_list = $("#customerlist");
            //alert(customer_list.find('li').length);
            if(customer_list.find('li').length > 0){
                customer_list.show();
            }
        });*/
		
        function positionmenu(container,input){
            const $menu = $(container);
            const $input = $(input);
            const input_height = $input.outerHeight();//Use To Specify Position From Top/Bottom
            const menu_height = $menu.outerHeight();
            const page_height = $(document).height();
            const from_top = $input.offset().top; 
            const from_bottom = $(document).height()-(from_top+input_height);
            
            switch(menu_height){
                case (menu_height/4<from_bottom):
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
                case (menu_height/4 < from_top):
                    $menu.css({
                        bottom: input_height + 'px',
                    });
                    break;
                default :
                    $menu.css({
                        top: input_height + 'px',
                    });
                    break;
            }
        }

        $(document).on('click','.select_customer',function(e){
            e.preventDefault();
            $("#customerlist").removeClass('active');
            $("#name").empty().val($(this).data('target'));
            var trgt_path = $(this).attr('href');
            var match_path = "{{ url('vendors/customer/udhardata') }}";
            if(trgt_path.includes(match_path)){
                $.get($(this).attr('href'),"",function(response){
                    $("#custo_txn_jump").attr('href',"javascript:void(null);");
                    const custo_id = response.custo_id??0;
                    const udhar_id = response.id??0;
                    $('[name="custo_id"]').empty().val(custo_id);
                    $('[name="udhar_id"]').empty().val(udhar_id);
                    if(udhar_id!=0){
                        $("#custo_txn_jump").attr('href',"{{ url('vendors/udhar/transactions') }}/"+udhar_id);
                    }else{
                        $("#custo_txn_jump").attr('onclick',"toastr.error('No Udhar Account Yet !')" );
                    }
                    var amount = response.custo_amount??0;
                    if(amount !=0){
                        amount = (response.custo_amount_status==1)?'+'+amount:'-'+amount;
                        var color = (response.custo_amount_status==1)?'green':'red';
                        $('[name="cash_old"],[name="cash_final"]').css('color',color);
                    }
                    $("#bhav_final_udhar").val(amount);
                    $('[name="cash_old"],[name="cash_final"]').empty().val(amount);
                    
                    var gold = response.custo_gold??0;
                    if(gold!=0){
                        gold = (response.custo_gold_status==1)?'+'+gold:'-'+gold;
                        var color = (response.custo_gold_status==1)?'green':'red';
                        $('[name="gold_old"],[name="gold_final"]').css('color',color);
                    }
                    $('[name="gold_old"],[name="gold_final"]').empty().val(gold??0);
                    var silver = response.custo_silver??0;
                    if(silver!=0){
                        silver = (response.custo_silver_status==1)?'+'+silver:'-'+silver;
                        var color = (response.custo_silver_status==1)?'green':'red';
                        $('[name="silver_old"],[name="silver_final"]').css('color',color);
                    }
                    $('[name="silver_old"],[name="silver_final"]').empty().val(silver??0);
                    //$("#bottom_block").show();
                    calculation();
                });
            }else{
                $("#udhar_form").trigger('reset');
                $("#custo_txn_jump").attr('href');
            }
        });

        $(document).on('input','.udhar_input_field',function(e){
            if($("#name").val()!="" || $("[name='custo_id']").val()!=""){
                const target = $(this).data('target');
                const old_value = $('[name="'+target+'_old"]').val(); 
                if(old_value!=""){
                     const curr_val = $(this).val()??0;
                     var op_arr = $(this).attr('name').replace(target+"_","").split("_");
                     const op = op_arr[0];
                     const mode = op_arr[1];
                     var final_value = 0;
                     if(curr_val!=""){
                        const opps_trgt = (op=='in')?'out':'in';
                        const input = target+'_'+opps_trgt+'_';
                        $('[name="'+input+mode+'"]').val("");
                        if(target == 'cash'){
                            var mode_alt = (mode=='off')?'on':'off';
                            $('[name="'+input+mode_alt+'"]').val("");
                            var alt_input = target+'_'+op+'_';
                            $('[name="'+alt_input+mode_alt+'"]').val("");
                        }

                        if(op=='in'){
                            final_value = +old_value+ +curr_val;
                        }else{
                            final_value = +old_value- +curr_val;
                        }
                        //final_value = +old_value+ +curr_val;
                        /*if(op=='in'){
                             $('[name="'+target+'_out_'+mode+'"]').val("");
                             final_value = +old_value+ +curr_val;
                             if(target == 'cash'){
                                var mode_alt = (mode=='off')?'on':'off';
                                $('[name="'+target+'_out_'+mode_alt+'"]').val("");
                             }
                         }else{
                             $('[name="'+target+'_in_'+mode+'"]').val("");
                             final_value = +old_value- +curr_val;
                             if(target == 'cash'){
                                
                            }
                         }*/
                     }else{
                             final_value = old_value;
                         $('[name="'+target+'_final"]').val(+ + old_value);
                     }
                     var color = (final_value>0)?'green':'red';
                     final_value = (final_value>0)?"+"+final_value:final_value;
                     $('[name="'+target+'_final"]').val(final_value);
                     $('[name="'+target+'_final"]').css('color',color);
                     if((curr_val!="" && curr_val!=0) && final_value!=0 ){
                         var color = (final_value>0)?'green':'red';
                         $('[name="'+target+'_final"]').val(final_value);
                         $('[name="'+target+'_final"]').css('color',color);
                    } 
                }
                calculation();
            }else{
                //e.preventDefault();
                toastr.error("Please Select the customer first !");
                $("#name").focus();
                $(this).val("");
            }
        });
        
        /*$(document).on("input",".convert_source",function(e){
            const id = $(this).attr('id');
            const target_field = {"bhav_cnvrt_from":["bhav_final_udhar","bhav_udhar_money"],"bhav_cnvrt_to":["bhav_final_cnvrt","bhav_udhar_metal"]};
            const reset_field = {"bhav_cnvrt_from":"bhav_cnvrt_to","bhav_cnvrt_to":"bhav_cnvrt_from"};
            if($("[name='conver_into']").val()!=""){
                const main_value = $("#"+target_field[id][0]).val();
                const self_value = $(this).val()??0;
                const test_main_value = Math.abs(main_value);
                const test_self_value = Math.abs(self_value);
                if(test_self_value<=test_main_value){
                    var  total = (main_value>0)?+ main_value - +self_value:+ main_value + +self_value;
                    //$("#"+reset_field[$(this).attr('id')][1]).val("");
                    total = (total>0)?"+"+total:total;
                    $("#"+target_field[$(this).attr('id')][1]).val(total);
                }else{
                    $(this).focus();
                    toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                    return false;
                }
                $("#"+reset_field[id]).val("");
            }else{
                $(this).val("");
                toastr.error("In what you want to Convert !");
                $("[name='conver_into']").focus();
            }
        });*/

        var cut_pre_val = "";
        $(document).on("input",".convert_source",function(e){
            $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
            $('[type="radio"]').prop('checked',false);
            if($("[name='conver_into']").val()!=""){
                const id = $(this).attr('id');
                const target_field = {"bhav_cnvrt_from":["bhav_final_udhar","bhav_udhar_money"],"bhav_cnvrt_to":["bhav_final_cnvrt","bhav_udhar_metal"]};
                const reset_field = {"bhav_cnvrt_from":"bhav_cnvrt_to","bhav_cnvrt_to":"bhav_cnvrt_from"};
                const main_value = $("#"+target_field[id][0]).val();
                var self_value = $(this).val();
                if(self_value=="-" || self_value == "+"){
                    $(this).val("");
                    $("#"+target_field[id][1]).val("");
                    $(".bhav_cnvrt_btn").removeClass('btn-warning').addClass("btn-outline-warning");
                    $("#"+reset_field[id]).val("");
                    $("#"+target_field[reset_field[id]][1]).val("");
                    $('.row.block_at_bhav_cut').removeClass('active');
                }else{
                    const test_main_value = Math.abs(main_value);
                    var test_self_value = Math.abs(self_value);
                    
                    if(test_self_value > 0 || self_value!=""){
    
                        if(test_self_value <= test_main_value){
                            $('.row.block_at_bhav_cut').addClass('active');
                            var show_test = (main_value>0)?"+"+test_self_value:"-"+test_self_value;
                            $(this).val(show_test);
                            const total = test_main_value-test_self_value;
                            //alert(total);
                            var test_total = (main_value>0)?'+'+total:"-"+total;
                            var color = (main_value>0)?'green':'red';
                            $("#"+target_field[id][1]).val(test_total);
                            $("#"+target_field[id][1]).css('color',color);
                            cut_pre_val = self_value;
                        }else{
                            $(this).focus();
                            toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                            $(this).val(cut_pre_val);
                        }
                    }else{
                        $(this).val("");
                        $(this).css("color","");
                        $('.row.block_at_bhav_cut').removeClass('active');
                    }
                }
            }else{
                $(this).val("");
                toastr.error("In what you want to Convert !");
                $("[name='conver_into']").focus();
            }
            
        });


        

        function loadbhavcut(){
            if($('#chav_cut_check').is(':checked')){
                var cut_val = false;
                $('.convert_source').each(function(i,v){
                    if($(this).val()!=""){
                        cut_val=true;
                    }
                });
                if(cut_val){
                    $(".row.block_at_bhav_cut").addClass('active');
                }
                const source = $('[name="cash_final"]');
                const color = source.css('color');
                $('[name="bhav_final_udhar"]').css('color',color);
                $('[name="bhav_final_udhar"]').empty().val(source.val());
                loadconvertmetal();
            }else{
                $(".row.block_at_bhav_cut").removeClass('active');
            }
        }

        function loadconvertmetal(){
            if($('[name="conver_into"]').val()!=""){
                $('.metal').hide();
                const sel_metal = $('[name="conver_into"]').val();
                $(".metal").hide();
                $(".metal."+sel_metal).show();
                $('[name="cnvrt_unit"] option.'+sel_metal+'_1').prop('selected',true);
                const source = $('[name="'+sel_metal+'_final"]');
                const color = source.css('color');
                $('[name="bhav_final_cnvrt"]').empty().val(source.val());
                $('[name="bhav_final_cnvrt"]').css('color',color);
                $('[name="bhav_cnvrt_rate"]').empty().val(price[sel_metal]);
            }else{
                $('#bhav_cnvrt_rate').val("");
                $('[name="cnvrt_unit"]').val("");
                $('.metal.gold,.metal.silver').hide();
            }
        }

        function calculation(){
            loadbhavcut();
        }
        $(document).on('change','#chav_cut_check',function(e){
            loadbhavcut();
        })

        $(document).on('change','[name="conver_into"]',function(e){
            loadconvertmetal();
        });
		
        $(document).on('click','.bhav_cnvrt_btn',function(e){
            //e.preventDefault();
            if($('[name="conver_into"]').val()!=""){
                if($('#bhav_cnvrt_rate').val()!="" && $('[name="cnvrt_unit"]').val() !=""){
                    const direction = $(this).attr('id');

                    const rate_value = $('#bhav_cnvrt_rate').val()
                    const unit = $('[name="cnvrt_unit"]').val();
                    const cnvrt_into = $('[name="conver_into"]').val();
                    
                    const unit_div = (cnvrt_into=='silver')?1000:unit;
                    
                    const rate = rate_value/unit_div;
                    var ahead = true;

                    var initial =   "bhav_final_";
                    var value   =   "bhav_cnvrt_";
                    var final =   "bhav_udhar_";
                    
                    const data_arr = {
                        'bhav_cnvrt_right': {
                            'source': ['udhar', 'from', 'money'],
                            'target': ['cnvrt', 'to', 'metal']
                        },
                        'bhav_cnvrt_left': {
                            'source': ['cnvrt', 'to', 'metal'],
                            'target': ['udhar', 'from', 'money']
                        }
                    };                 


                    const src_final = $('[name="'+initial+data_arr[direction]['source'][0]+'"]').val();
                    const src_value_field = $('[name="'+value+data_arr[direction]['source'][1]+'"]');
                    const src_value = src_value_field.val();
                    
                    const src_final_field = $('[name="'+value+data_arr[direction]['source'][1]+'"]');

                    const src_test_final = Math.abs(src_final)??0;
                    if(src_value > src_test_final){
                        ahead = false;
                        src_value_field.focus();
                        toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                    }
                    if(ahead){
                        const src_test_value = Math.abs(src_value)??0;
                        var src_remains = +src_test_final - +src_test_value;
                        src_remains = (src_final>0)?"+"+src_remains:"-"+src_remains;
                        const src_final_color = (src_final>0)?"green":"red";
                        $('[name="'+final+data_arr[direction]['source'][2]+'"]').val(src_remains).css('color',src_final_color);
                        var converted = (data_arr[direction]['source'][0]=="udhar")?src_test_value/rate:src_test_value*rate;
						
						
						//alert(direction);
                        //alert(converted);
						converted = (direction=='bhav_cnvrt_right')?converted.toFixed(3):converted;
                        converted = ((src_value>0)?'+':'-')+converted;
						
                        const trgt_final = $('[name="'+initial+data_arr[direction]['target'][0]+'"]').val();
                        $('[name="'+value+data_arr[direction]['target'][1]+'"]').val(converted);
                        
                        const trgt_val = $('[name="'+value+data_arr[direction]['target'][1]+'"]').val();
                        
                        var trgt_remains = +trgt_final + +trgt_val
                        trgt_remains = (trgt_remains>0)?'+'+trgt_remains:trgt_remains;
                        const trgt_color = (trgt_remains<0)?'red':'green';
                        $('[name="'+final+data_arr[direction]['target'][2]+'"]').val(trgt_remains).css('color',trgt_color);
                        
                        $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
                        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
                    }
                }else{
                    toastr.error("Please Provide The rate & Unit !");
                }
            }else{
                $('[name="conver_into"]').addClass('is-invalid');
                $('[name="conver_into"]').focus();
                toastr.error("In What You Want to Convert !");
            }
        });
        /*$(document).on('click','.bhav_cnvrt_btn',function(e){
            e.preventDefault();
            if($('[name="conver_into"]').val()!=""){
                if($('#bhav_cnvrt_rate').val()!="" && $('[name="cnvrt_unit"]').val() !=""){

                    const into  = $(this).attr('id');
    
                    const rate_value = $('#bhav_cnvrt_rate').val()
                    const unit = $('[name="cnvrt_unit"]').val();

                    const rate = rate_value/unit;


                    const desire_final = {'bhav_cnvrt_right':'bhav_final_udhar','bhav_cnvrt_left':'bhav_final_cnvrt'};
                    const desire_input = {'bhav_cnvrt_right':'bhav_cnvrt_from','bhav_cnvrt_left':'bhav_cnvrt_to'};
                    const target_input = {'bhav_cnvrt_right':'bhav_cnvrt_to','bhav_cnvrt_left':'bhav_cnvrt_from'};

                    const target_final = {'bhav_cnvrt_right':'bhav_final_cnvrt','bhav_cnvrt_left':'bhav_final_udhar'}
                    const target_remains = {'bhav_cnvrt_right':'bhav_udhar_metal','bhav_cnvrt_left':'bhav_udhar_money'}
                    var from = 0;
                    var to = 0;
                    var ahead = true;
                    
                    const input_final_val =  Math.abs($('[name="'+desire_final[into]+'"]').val());
                    
                    const input_source_val = Math.abs($('[name="'+desire_input[into]+'"]').val());
    
                    if($('[name="'+desire_input[into]+'"]').val()=="" || $('[name="'+desire_input[into]+'"]').val()==0){
                        $('[name="'+desire_input[into]+'"]').addClass('is-invalid');
                        $('[name="'+desire_input[into]+'"]').focus();
                        ahead = false;
                        toastr.error("Conversion Source Required !");
                    }else{
                        if(input_source_val > input_final_val){
                            ahead = false;
                            $('[name="'+desire_input[into]+'"]').focus();
                            toastr.error("Conversion Source Values can't be greater that the final digital Value; !");
                        }
                    }
                    if(ahead){
                        $('.bhav_cnvrt_btn').removeClass('btn-warning').addClass('btn-outline-warning');
                        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
                        $('[name="bhav_cnvrt_from"],[name="bhav_cnvrt_to"]').removeClass('is-invalid');
                        
                        var converted = (into=='bhav_cnvrt_right')?input_source_val/rate:input_source_val*rate;
                        converted = converted??false;
                        alert(converted);
                        if(converted){
                            converted = ($('[name="'+desire_input[into]+'"]').val()>0)?"+"+converted:"-"+converted;
                            
                            $('[name="'+target_input[into]+'"]').val(converted);
                            
                            const initial = $('[name="'+target_final[into]+'"]').val();
                            alert(initial);
                            const new_cnvrt = Math.abs(converted);

                            var remains = +initial + +converted;
                            
                            remains = (remains>0)?"+"+remains:remains;
                            const color_rmns = (remains>0)?"green":"red";
                            $('[name="'+target_remains[into]+'"]').val(remains);
                            $('[name="'+target_remains[into]+'"]').css('color',color_rmns);
                            
                        }else{
                            toastr.error("Conversion Error !");
                        }
                    }
                }else{
                    toastr.error("Please Provide The rate & Unit !");
                }
            }else{
                $('[name="conver_into"]').addClass('is-invalid');
                $('[name="conver_into"]').focus();
                toastr.error("In What You Want to Convert !");
            }
        });*/

        /*$(document).ready(function() {
            $("#custo_plus_form").submit(function(e){
                e.preventDefault();
                $('.help-block').empty();
                $('.custo').removeClass('invalid');
                const path = $(this).attr('action');
                const fd = new FormData(this) ;
                //var formData = new FormData(this); 
                $.ajax({
                    url: path,
                    type: 'POST',
                    data: fd,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                    $('.btn').prop("disabled", true);
                    $('#loader').removeClass('hidden');
                    },
                    success: function(response) {
                        $('.btn').prop("disabled", false);
                        $('#loader').addClass('hidden');
                        if(response.success){
                            $("#custo_modal").modal('hide');
                            $("#custo_plus_form").trigger('reset');
                            success_sweettoatr(response.success);
                            if(response.data){
                                const data_stram = response.data.custo_num+"/"+response.data.custo_full_name+"-"+response.data.custo_fone;
                                alert(data_stram);
                                $('[name="custo_id"]').val(response.data.id);
                                $('[name="cash_old"] ,[name="gold_old"] ,[name="silver_old"] ,[name="cash_final"],[name="gold_final"],[name="silver_final"]').val(0);
                                $('#name').val(data_stram);
                                $("#bottom_block").show();
                            }
                        }else{
                            if(typeof response.errors !='string'){
                                $.each(response.errors,function(i,v){
                                    $('[name="'+i+'"]').addClass('is-invalid');
                                    $.each(v,function(ind,val){
                                        toastr.error(val);
                                    });
                                });
                            }else{
                                toastr.error(response.errors);
                            }
                        }
                    },
                    error: function(response) {
                        
                    }
                });
            });

            $(document).on('mouseenter','#customerlist li',function(){
                $(this).addClass('hover');
            });
            $(document).on('mouseleave','#customerlist li',function(){
                $(this).removeClass('hover');
            });

            $(document).on('keydown',function(e){
                const input_element = $(':focus');
                const list_vis = $("#customerlist").css('display');
                if(input_element.prop('name')=="name" && list_vis=='block'){
                    if(event.key=='ArrowUp' || event.key=='ArrowDown'){
                        var li_index = $("#customerlist li.hover").index();
                        var li_count =  $("#customerlist li").length-1;
                        $("#customerlist li").removeClass('hover');
                        if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                                $("#customerlist li").eq(li_index-1).addClass('hover');
                            }

                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                $("#customerlist li").eq(li_index+1).addClass('hover');
                            }
                        }
                    }else{
                        if(event.key=='Tab'){
                            $("#customerlist li.hover>a").trigger('click');
                        }
                    }
                }
            });
        });*/

		
		 $(document).on('keydown',function(e){
                const input_element = $(':focus');
                const list_vis = $("#customerlist").css('display');
                if(input_element.prop('name')=="name" && list_vis=='block'){
                    const $list = $("#customerlist");
                    if(event.key=='ArrowUp' || event.key=='ArrowDown'){
                        var li_index = $("#customerlist li.hover").index();
                        var li_count =  $("#customerlist li").length-1;
                        $("#customerlist li").removeClass('hover');
                        if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                               li_index--;
                            }
                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                li_index++;
                            }else{
                                li_index = 0;
                            }
                        }
                        //const $hovered = $items.eq(li_index).addClass('hover');
                        const $hovered = $("#customerlist li").eq(li_index).addClass('hover');

                        // ✅ Auto-scroll logic
                        const ulScrollTop = $list.scrollTop();
                        const ulHeight = $list.outerHeight();
                        const liOffsetTop = $hovered.position().top + ulScrollTop;
                        const liHeight = $hovered.outerHeight();

                        if (liOffsetTop + liHeight > ulScrollTop + ulHeight) {
                            // scroll down to bring item into view
                            $list.scrollTop(liOffsetTop + liHeight - ulHeight);
                        } else if (liOffsetTop < ulScrollTop) {
                            // scroll up to bring item into view
                            $list.scrollTop(liOffsetTop);
                        }
                        /*if(event.key=='ArrowUp'){
                            if(li_index!=-1){
                                $("#customerlist li").eq(li_index-1).addClass('hover');
                            }

                        }
                        if(event.key=='ArrowDown'){
                            if(li_index!=li_count){
                                $("#customerlist li").eq(li_index+1).addClass('hover');
                            }
                        }*/
                    }else{
                        if(event.key=='Tab'){
                            $("#customerlist li.hover>a").trigger('click');
                        }
                    }
                }
            });
		
		$(document).on('customerformsubmit',function(e){
                let data  = e.originalEvent.detail;
                $("#custo_plus_form").find("button[type='submit']").prop('disabled',false);
                $("#process_wait").hide();
                if(data.errors){
                    errors = data.errors;
                    $.each(errors,function(i,v){
                        let err = '';
                        $.each(v,function(ei,ev){
                            if(err!=''){
                                err+='\n';
                            }
                            err+=ev;
                        });
                        $("#"+i).addClass('is-invalid');
                        $("#"+i+"_error").html(err);
                        toastr.error(err)
                    });
                }else if(data.error){
                    toastr.error(data.error);
                }else{
                    let custo = data.custo;
                    // $("#custo_modal").modal('hide');
                    // $("#custo_plus_form").trigger('reset');
                    // success_sweettoatr(response.success);
                    const data_stram = custo.num+"/"+custo.name+"-"+custo.contact;
                    $('[name="custo_id"]').val(custo.id);
                    $('[name="cash_old"] ,[name="gold_old"] ,[name="silver_old"] ,[name="cash_final"],[name="gold_final"],[name="silver_final"]').val(0);
                    $('#name').val(data_stram);
                    //$("#bottom_block").show();

                    /*$("#custo_id").val(custo.id);
                    $("#custo_type").val(data.type);
                    let name = custo.custo_full_name;
                    let mob = custo.custo_fone;
                    $('#custo').val(name+' -( '+mob+' )');
                    var enrol_count = $("#times").val()??1;
                    for(var i=1;i<=enrol_count;i++){
                        $('input[name="name[]"]').val(name);
                    }*/
                    success_sweettoatr("Customer succesfully Added !");
                    $("#custo_modal").modal('hide');
                    resetcustoform(true);
                }
            });

		var submit_btn = "";
        $('button[type="submit"]').click(function(){
            submit_btn = $(this).val();
        });

        $("#udhar_form").submit(function(e){
            e.preventDefault();
            var formdata = $(this).serialize();
			formdata+= '&action='+submit_btn;
            var path = $(this).attr('action');
            $("#loader").show();
            $.post(path,formdata,function(response){
				submit_btn = "";
                if(response.errors){
                    if(typeof response.errors !='string'){
                        $.each(response.errors,function(i,v){
                            $('[name="'+i+'"]').addClass('is-invalid');
                            $.each(v,function(ind,val){
                                toastr.error(val);
                            });
                        });
                    }else{
                        toastr.error(response.errors);
                    }
                }else if(response.success){
					//alert(response);
                    success_sweettoatr(response.success);
					if(response.url){
                        window.open(response.url,'_blank');
                    }
                    location.href="{{ route('udhar.index') }}";
                }
                $("#loader").hide();
                is_submit = false;
            });
        })
    </script>
@endsection
