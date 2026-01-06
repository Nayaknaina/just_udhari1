@extends('layouts.vendors.app')

@section('css')
<style>
    .dropdown.sub_drop_over {
        position: absolute;
        top: 0;
        right: 0;
    }

    .dropdown.sub_drop_over>.dropdown-menu {
        width: auto;
        min-width: unset;
    }
</style>
@endsection

@section('content')

@php $data = new_component_array('breadcrumb',"Girvi Recieved") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <style>
                /* Navigation styles */
                .nav-container {
                    display: flex;
                    gap: 1rem;
                    padding: 1rem 1.5rem;
                    padding-top: 1rem;
                    justify-content: space-between;
                }

                .nav-container-btn {
                    gap: 1rem;
                    padding: 1rem 1.5rem;
                    padding-top: 1rem;
                    text-align: center;
                }

                .nav-button {
                    background-color: #e5e7eb;
                    color: black;
                    font-weight: bold;
                    padding: 0.5rem 1.5rem;
                    border-radius: 9999px;
                    border: 2px solid #9ca3af;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                    cursor: pointer;
                }

                .old-record-btn {
                    border: 1px solid black;
                    border-radius: 0.25rem;
                    padding: 0.25rem 1rem;
                    background-color: white;
                    cursor: pointer;
                }

                .action-buttons {
                    display: flex;
                    gap: 0.5rem;
                }

                .action-btn {
                    background-color: #6A5ACD;
                    color: white;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: none;
                    cursor: pointer;
                }

                .menu-icon {
                    width: 20px;
                    height: 20px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }

                .menu-line {
                    width: 16px;
                    height: 2px;
                    background-color: white;
                    margin-bottom: 2px;
                }

                .user-icon {
                    position: relative;
                    width: 20px;
                    height: 20px;
                }

                .user-dot {
                    position: absolute;
                    width: 12px;
                    height: 12px;
                    background-color: white;
                    border-radius: 9999px;
                }

                .user-dot-1 {
                    top: 0;
                    left: 0;
                }

                .user-dot-2 {
                    top: 0;
                    right: 0;
                }

                .user-dot-3 {
                    bottom: 0;
                    left: 4px;
                }



                /* Form styles */
                .input-group {
                    display: flex;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                }

                .input-container {
                    position: relative;
                    flex: 1;
                }

                .rounded-input {
                    border-radius: 0.25rem;
                }

                .add-btn {
                    background: linear-gradient(145deg, #000000, #1a1a1a);
                    /* polished black effect */
                    border-radius: 9999px;
                    width: 32px;
                    height: 32px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 2px solid #FB923C;
                    /* orange border */
                    cursor: pointer;
                    box-shadow: 0 2px 4px rgba(255, 255, 255, 0.1);
                    /* subtle highlight */
                }

                .add-btn:hover {
                    background: #FB923C;
                    border: 2px solid black;

                }


                .add-icon {
                    color: white;
                    font-size: 1.25rem;
                    font-weight: bold;
                }

                .section-title {
                    font-weight: bold;
                    margin-bottom: 0.5rem;
                }

                .fs-15 {
                    font-size: 15px !important;
                }

                .dropdown-container {
                    display: flex;
                }

                .dropdown-input {
                    border-radius: 9999px 0 0 9999px;
                    border-right: none;
                }

                .dropdown-btn {
                    background-color: #FB923C;
                    border-radius: 0 9999px 9999px 0;
                    padding: 0 0.75rem;
                    display: flex;
                    align-items: center;
                    border: none;
                    cursor: pointer;
                }

                .dropdown-icon {
                    color: white;
                }

                .input-addon {
                    background-color: white;
                    border: 1px solid #d1d5db;
                    border-radius: 0 9999px 9999px 0;
                    padding: 0 0.75rem;
                    display: flex;
                    align-items: center;
                    border-left: none;
                }

                .input-addon-left {
                    border-radius: 9999px 0 0 9999px;
                    border-right: none;
                }

                .input-addon-right {
                    border-radius: 0 9999px 9999px 0;
                    border-left: none;
                }

                .attach-btn {
                    background-color: #e5e7eb;
                    border: 1px solid #d1d5db;
                    border-radius: 0 9999px 9999px 0;
                    padding: 0.25rem 0.5rem;
                    font-size: 0.75rem;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                }

                .grid-2 {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 1rem;
                }

                .calendar-icon {
                    width: 20px;
                    height: 20px;
                }

                .action-container {
                    display: flex;
                    justify-content: center;
                    gap: 1rem;
                    /* margin-top: 1.5rem; */
                }

                .action-button {
                    background-color: #d1d5db;
                    color: black;
                    font-weight: bold;
                    padding: 0.5rem 2rem;
                    border-radius: 0.375rem;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                    border: none;
                    cursor: pointer;
                }

                .icon-btn-add {
                    background-color: #d1d5db;
                    color: black;
                    font-weight: bold;
                    padding: 0.5rem 2rem;
                    border-radius: 0.375rem;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                    border: none;
                    cursor: pointer;
                }

                /* Search styles */
                .search-container {
                    position: relative;
                }

                .search-icon {
                    position: absolute;
                    right: 0.75rem;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #9ca3af;
                }

                /* Customer info styles */
                .customer-info {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: .1rem 1rem;
                    /* margin-bottom: 1rem; */
                }

                .info-field {
                    display: flex;
                    align-items: center;
                }

                .info-label {
                    font-weight: bold;
                    margin-right: 0.5rem;
                }

                .col-span-2 {
                    grid-column: span 2;
                }


                .status-released {
                    display: flex;
                    align-items: center;
                }

                .check-icon {
                    margin-left: 0.5rem;
                    background-color: #16a34a;
                    border-radius: 0.25rem;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                }

                .fa-xs {
                    font-size: .65em;
                    line-height: .08333em;
                    vertical-align: .125em;
                }



                .small-text {
                    font-size: 0.75rem;
                }

                .form-select-sm {
                    min-height: calc(1.5em + 0.5rem + calc(var(--bs-border-width) * 2));
                    padding: 0.25rem 0.5rem;
                    font-size: 0.7rem;
                    /* border-radius: var(--bs-border-radius-sm); */
                }

                .form-control-sm {
                    min-height: calc(1.5em + 0.5rem + calc(var(--bs-border-width) * 2));
                    padding: 0.25rem 0.5rem;
                    font-size: 0.7rem;
                }

                .gm-inside {
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #555;
                    font-size: 0.9rem;
                    pointer-events: none;
                    font-weight: bold;
                }

                .btn-roundhalf {
                    border-radius: 8px !important;
                }

                .form-record {
                    height: 40px;
                }

                .form-record::placeholder {
                    font-size: 1.1rem;
                    /* Change this value as needed */
                }



                .curve {
                    border-bottom: 5px solid #FF9933;
                }

                .round {
                    border-radius: 20px !important;
                }

                .btn-outline-default {
                    background-image: linear-gradient(to top, #D8D9DB 0%, #fff 80%, #FDFDFD 100%);
                    border: 1px solid #8F9092 !important;
                    /* background: #B3B3B34D; */
                    /* box-shadow: 0px 4px 4px 0px #00000040; */

                    box-shadow: 0px 4px 4px 0px #00000040;


                    transition: all 0.3s ease;
                    font-family: "Source Sans Pro", sans-serif;
                    font-size: 14px !important;
                    font-weight: 600;
                    color: #606060;
                    text-shadow: 0 1px #fff;
                    padding: 5px 30px !important;
                    border-radius: 30px;
                    display: inline-block;
                    transform: translateY(0);
                }

                .btn-outline-default:hover,
                .btn-outline-default.active {
                    border-top: 2px solid #FF9933 !important;
                    color: red;
                    transform: translateY(-4px);
                }

                .btn-icon {

                    color: white;
                    border-radius: 50%;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: none;
                    cursor: pointer;
                }

                .input-group {
                    display: flex;
                    gap: 0.5rem;
                    margin-bottom: 1rem;
                }

                .input-group {
                    position: relative;
                    display: flex;
                    flex-wrap: wrap;
                    align-items: stretch;
                    width: 100%;
                }

                .custom-select-wrap {
                    position: relative;
                    width: 100%;
                }

                .form-select {
                    --bs-form-select-bg-img: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e);
                    display: block;
                    width: 100%;
                    padding: 0px 10px;
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    color: #212529;
                    background-color: #fff;
                    background-repeat: no-repeat;
                    background-position: right 0.75rem center;
                    background-size: 16px 12px;
                    border: 1px solid;
                    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                    appearance: none;
                }

                .form-control-sm {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.7rem;
                    width: 100%;
                    border: 1px solid;
                }



                .custom-select-wrap {
                    position: relative;
                    width: 100%;
                }

                .custom-select {
                    appearance: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    padding-right: 2.5rem;
                    /* Space for arrow */
                    background-color: #fff;
                    border: 1px solid #000;
                    border-radius: 9999px;
                    padding: 0.375rem 1rem;
                    font-size: 14px;
                    cursor: pointer;
                }

                .custom-select-arrow {
                    position: absolute;
                    top: 48%;
                    right: 8px;
                    transform: translateY(-50%);
                    background-color: #FF9933;
                    /* yellow-400 */
                    color: #000;
                    padding: 1px 5px;
                    border-radius: 0.375rem;
                    pointer-events: none;
                    font-size: 12px;
                }

                .h-27px {
                    height: 27px !important;
                }

                .fs-13px {
                    font-size: 13px !important;
                }

                .date-placeholder:invalid::before {
                    content: attr(placeholder);
                    color: #999;
                    position: absolute;
                    left: 10px;
                    top: 5px;
                }

                .date-placeholder:valid::before {
                    content: '';
                }

                .date-wrapper {
                    position: relative;
                }

                .floating-label {
                    position: absolute;
                    left: 12px;
                    top: 4px;
                    color: #999;
                    font-size: 13px;
                    pointer-events: none;
                    transition: 0.2s ease all;
                    background: white;
                    padding: 0 4px;
                }

                .date-wrapper input:focus+.floating-label,
                .date-wrapper input:not(:placeholder-shown)+.floating-label,
                .date-wrapper input:valid+.floating-label {
                    top: -10px;
                    left: 8px;
                    font-size: 11px;
                    color: #333;
                }
            </style>

            <div class="col-md-5">
                <div class="card round curve">
                    <div class="card-body ">
                        <div class="simple-tab">
                            <ul class=" nav-container-btn pt-0 " id="myTab" role="tablist">
                                <button class="btn btn-outline-default round mb-2 active" id="recieved-tab" data-bs-toggle="tab" data-bs-target="#recieved-tab-pane" type="button" role="tab" aria-controls="recieved-tab-pane" aria-selected="true">G. Received</button>
                                <button class="btn btn-outline-default round mb-2" id="return-tab" data-bs-toggle="tab" data-bs-target="#return-tab-pane" type="button" role="tab" aria-controls="return-tab-pane" aria-selected="false">G. Return</button>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <!-- first tab panel start -->
                                <div class="tab-pane fade show active" id="recieved-tab-pane" role="tabpanel" aria-labelledby="recieved-tab" tabindex="0">
                                    <div class=" ">
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <div class=" input-group" style="align-items: anchor-center;"><input
                                                        class="form-control h-27px round border-success" type="text" placeholder="Customer Name"><button
                                                        class="add-btn"><span class="add-icon">+</span></button></div>
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <div class=" input-group" style="align-items: anchor-center;"><input
                                                        class="form-control h-27px round border-success" type="text" placeholder="Mobile No-/ Girvi Id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6 class="fs-15 section-title">jewellery Collins Details:</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">


                                                <div class="input-group" style="align-items: center;">
                                                    <div class="custom-select-wrap">
                                                        <select class="form-select fs-13px h-27px round">
                                                            <option selected disabled>Jewellery Type:</option>
                                                            <option>Jewellery</option>
                                                        </select>

                                                        <span class="custom-select-arrow">▼</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-1 pr-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control-sm round border-dark " type="text"
                                                                placeholder="Weight">
                                                            <span class="gm-inside">Gm</span>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6 mb-1 pl-1">
                                                        <div class="input-group" style="align-items: anchor-center;">
                                                            <select class=" form-select-sm round border-dark">
                                                                <option selected disabled>Carat:</option>
                                                                <option>22</option>
                                                                <option>24</option>
                                                            </select>
                                                            <span class="">%</span>

                                                        </div>

                                                    </div>


                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <style>
                                                    #inputFile {
                                                        opacity: 0;
                                                        position: absolute;
                                                        width: 100%;
                                                        height: 100%;
                                                        z-index: 2;
                                                        cursor: pointer;
                                                    }

                                                    .custom-file-display {
                                                        display: flex;
                                                        align-items: center;
                                                        width: 100%;
                                                        border: 1px solid black;
                                                        border-radius: 0.375rem;
                                                        padding: 0.1rem 0.3rem;
                                                        background-color: #f8f9fa;
                                                        z-index: 1;
                                                    }

                                                    .file-name {
                                                        flex-grow: 1;
                                                        color: #444;
                                                        font-style: italic;
                                                    }

                                                    .btn-choose {
                                                        background-color: #f59e0b;
                                                        border: 1px solid #f59e0b;
                                                        color: white;
                                                        border-radius: 0.375rem;
                                                        cursor: pointer;
                                                        transition: background-color 0.3s ease, color 0.3s ease;
                                                        font-size: 13px;
                                                        margin-left: 10px;
                                                        /* width: 38%; */
                                                    }

                                                    .btn-choose:hover {
                                                        background-color: #f59e0b;
                                                        border-color: #f59e0b;
                                                        color: white;
                                                    }
                                                </style>

                                                <div class="input-group round border-dark">
                                                    <input type="file" id="inputFile" onchange="updateFileLabel(this)">
                                                    <div class="custom-file-display">
                                                        <span class="file-name" id="fileLabel">Item Detail:</span>
                                                        <button class="btn-choose" type="button" id="button-addon2">Attach Item Photo</button>
                                                    </div>
                                                </div>

                                                <script>
                                                    function updateFileLabel(input) {
                                                        const fileName = input.files.length > 0 ? input.files[0].name : "Item Detail:";
                                                        document.getElementById("fileLabel").textContent = fileName;
                                                    }
                                                </script>





                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <div class="input-group position-relative">
                                                    <input class="form-control h-27px  round border-dark " type="text" placeholder="Market Rate">
                                                    <span class="gm-inside">Gm</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <div class="input-group position-relative">
                                                    <input class="form-control h-27px  round border-dark " type="text"
                                                        placeholder="Estimated Value ">
                                                    <span class="gm-inside">₹</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2 justify-content-between align-items-center">
                                            <h6 class="fs-15 section-title">Loan Details:</h6>
                                            <button class="add-btn toggle-loan-btn" type="button">
                                                <span class="add-icon">+</span>
                                            </button>
                                        </div>

                                        <div>
                                            <div class="row">
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-27px round border-dark" type="text" placeholder="Loan Amount">
                                                        <span class="gm-inside">₹</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-27px round border-dark" type="text" placeholder="Interest">
                                                        <span class="gm-inside">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <style>

                                            </style>
                                            <div class=" loan-details-form" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group" style="align-items: center;">
                                                            <div class="custom-select-wrap">
                                                                <select class="form-select fs-13px h-27px round border-dark">
                                                                    <option selected disabled>Interest Type:</option>
                                                                    <option>Jewellery</option>
                                                                </select>
                                                                <span class="custom-select-arrow">▼</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="position-relative date-wrapper">
                                                            <input class="form-control h-27px round border-dark" type="date" required>
                                                            <label for="issueDate" class="floating-label">Issue Date</label>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-27px round border-dark" type="text" placeholder="Loan Tenure">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="position-relative date-wrapper">
                                                            <input class="form-control h-27px round border-dark" type="date">
                                                            <label for="issueDate" class="floating-label">Return Date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-27px round border-dark" type="text" placeholder="Total Payable">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            $(document).ready(function() {
                                                $(".toggle-loan-btn").click(function() {
                                                    $(".loan-details-form").slideToggle("fast");

                                                    // Toggle icon text +/-
                                                    var icon = $(this).find(".add-icon");
                                                    icon.text(icon.text() === "+" ? "-" : "+");
                                                });
                                            });
                                        </script>


                                        <style>
                                            .btn {
                                                display: inline-block;
                                                font-weight: 400;
                                                line-height: 1.5;
                                                color: #212529;
                                                text-align: center;
                                                text-decoration: none;
                                                vertical-align: middle;
                                                cursor: pointer;
                                                user-select: none;
                                                background-color: transparent;
                                                border: 1px solid transparent;
                                                padding: 0.375rem 0.70rem;
                                                font-size: 1rem;
                                                border-radius: 0.375rem;
                                                transition: color 0.15s ease-in-out,
                                                    background-color 0.15s ease-in-out,
                                                    border-color 0.15s ease-in-out,
                                                    box-shadow 0.15s ease-in-out;
                                            }

                                            .btn-outline-web {
                                                background: linear-gradient(180deg, rgba(0, 0, 0, 0.25) 0%, rgba(51, 51, 51, 0.25) 50%, rgba(102, 102, 102, 0.25) 68.5%);
                                                color: #000000 !important;
                                                /* fallback */
                                                box-shadow: 0px 4px 4px 0px #00000040;
                                                color: #ffffff;
                                                padding: 8px 20px;
                                                border: 1px solid rgba(255, 255, 255, 0.2);
                                                border-radius: 5px;
                                                font-weight: 600;
                                                transition: all 0.3s ease-in-out;
                                                text-transform: uppercase;

                                                /* box-shadow: 0px 4px 4px 0px #00000040; */

                                                box-shadow: 0px 4px 4px 0px #000000;

                                            }

                                            /* Hover Effect */
                                            .btn-outline-web:hover {
                                                background: linear-gradient(180deg, #FC932B 0%, #C97522 50%, #965819 71.6%);

                                                box-shadow: 0px 6px 8px 0px rgba(0, 0, 0, 0.5);
                                                color: white !important;
                                                transform: translateY(-2px);
                                            }


                                            .btn-outline-web:focus {
                                                box-shadow: 0 0 0 0.25rem rgba(255, 153, 51, 0.5);
                                                ;
                                            }

                                            .btn-outline-web:disabled,
                                            .btn-outline-web.disabled {
                                                color: #ff9933;
                                                background-color: transparent;
                                                border-color: #FF9933;
                                                opacity: 0.65;
                                            }

                                            .btn-gradient-success {
                                                /* background: linear-gradient(to right, #a8e063, #56ab2f); */
                                                background: linear-gradient(90deg, #18BB64 0%, #118849 40%, #0B552E 80%);

                                                /* Light to dark green */
                                                color: white;
                                                border: none;
                                                padding: 5px 10px;
                                                border: 2px solid #0B552E;
                                                font-weight: 600;
                                                font-size: 15px;
                                                transition: background 0.4s ease;
                                            }

                                            .btn-gradient-success:hover {
                                                /* background: linear-gradient(to right, #9e9e9e, #e0e0e0); */
                                                background: linear-gradient(90deg, rgba(0, 0, 0, 0.25) 0%, rgba(64, 64, 64, 0.25) 29.72%, rgba(51, 51, 51, 0.25) 48.1%, rgba(67, 67, 67, 0.25) 67.66%, rgba(77, 77, 77, 0.25) 79.6%, rgba(101, 101, 101, 0.25) 83.5%, rgba(102, 102, 102, 0.25) 100%);
                                                border: 2px solid #434343;
                                                /* Grey to light grey */
                                                color: #000;
                                                /* Optional: change text color on hover */
                                            }
                                        </style>
                                        <div class="action-container">
                                            <button class="btn btn-outline-web">Print</button>
                                            <button class="btn btn-outline-web">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- first tab panel end -->
                                <!-- second tab panel start -->
                                <div class="tab-pane fade" id="return-tab-pane" role="tabpanel" aria-labelledby="return-tab" tabindex="0">
                                    <div class=" ">
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <div class=" input-group" style="align-items: anchor-center;">
                                                    <input class="form-control h-27px round border-success" type="text" placeholder="Customer Name">
                                                    <span class="gm-inside"><i class="fa-solid fa-magnifying-glass"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <div class=" input-group" style="align-items: anchor-center;">
                                                    <input class="form-control h-27px round border-success" type="text" placeholder="Mobile No-/ Girvi Id">
                                                    <span class="gm-inside"><i class="fa-solid fa-magnifying-glass"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <h6 class="fs-15 section-title">Loan Details:</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="row">
                                                            <label class="col-sm-5 fs-13px pr-1">Loan Amount :</label>
                                                            <div class="col-sm-7 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text"
                                                                        placeholder="Enter ID">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-5 fs-13px pr-1">Interest Due :</label>
                                                            <div class="col-sm-7 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark " type="text"
                                                                        placeholder="Enter ID">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-5 fs-13px pr-1"><b>Total Payable :</b></label>
                                                            <div class="col-sm-7 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark " type="text"
                                                                        placeholder="Enter ID">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                            </div>
                                            <div class="col-md-6 mb-1" style="    align-content: center;">
                                                <div class="input-group round border-dark">
                                                    <input type="file" id="inputFile" onchange="updateFileLabel(this)">
                                                    <div class="custom-file-display">
                                                        <span class="file-name" id="fileLabel">Item Detail:</span>
                                                        <button class="btn-choose" type="button" id="button-addon2">Attach Item Photo</button>
                                                    </div>
                                                </div>


                                            </div>




                                        </div>

                                        <div class="">
                                            <h6 class="fs-15 section-title">
                                                <img src="{{asset('main/assets/img/icons/money-bag.png')}}" loading="lazy">Payment Settlement :
                                            </h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-1">
                                                <div class="row">
                                                    <div class="col-md-12 mb-1">
                                                        <div class="row">
                                                            <label class="col-sm-6 fs-13px pr-1">Online Payment :</label>
                                                            <div class="col-sm-6 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text"
                                                                        placeholder="Enter Amount">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-6 fs-13px pr-1">Cash Payment :</label>
                                                            <div class="col-sm-6 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark " type="text"
                                                                        placeholder="Enter Amount">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <label class="col-sm-6 fs-13px pr-1"><b>Remaining Amount :</b></label>
                                                            <div class="col-sm-6 pr-1 pl-1">
                                                                <div class="input-group position-relative">
                                                                    <input class="form-control h-27px  btn-roundhalf border-dark " type="text"
                                                                        placeholder="Enter Amount">
                                                                    <span class="gm-inside">₹</span>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-gradient-success btn-roundhalf mb-2 me-4 _effect--ripple waves-effect waves-light">
                                                    <span class="btn-text-inner">Confirm Payment & Release Jewellery</span>
                                                    &nbsp; <i class="fa fa-lock-open"></i>
                                                </button>
                                            </div>



                                        </div>
                                        <div class="action-container">
                                            <button class="btn btn-outline-web">Print</button>
                                            <button class="btn btn-outline-web">SAVE</button>
                                        </div>



                                    </div>
                                </div>
                                <!-- second tab panel end-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card round curve">
                    <div class="card-body">
                        <div class="nav-container mb-2 pt-0">
                            <button class="btn btn-outline-dark mb-2 me-4 _effect--ripple waves-effect waves-light">Old Record</button>
                            <div class="action-buttons">
                                <button class="btn btn-outline-secondary btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-book"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-icon mb-2 me-4 btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-user-gear"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class=" input-group" style="align-items: anchor-center;">
                                        <input class="form-control h-27px  btn-roundhalf border-dark form-record" type="text"
                                            placeholder="Customer Name">
                                        <span class="gm-inside"><i class="fa-solid fa-magnifying-glass"></i></span>

                                    </div>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <div class=" input-group" style="align-items: anchor-center;">
                                        <input class="form-control h-27px  btn-roundhalf border-dark form-record" type="text"
                                            placeholder="Mobile No-/ Girvi Id">
                                    </div>
                                </div>

                            </div>

                            <div class="customer-info">
                                <div class="mb-2 row">
                                    <label class="col-sm-5 fs-13px pr-1">Customer Name :</label>
                                    <div class="col-sm-7">
                                        <div class="input-group position-relative">
                                            <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text"
                                                placeholder="Enter Name">

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-5 fs-13px pr-1">Girvi ID :</label>
                                    <div class="col-sm-7">
                                        <div class="input-group position-relative">
                                            <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text"
                                                placeholder="Enter ID">

                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-sm-5 fs-13px pr-1">Mobile Number :</label>
                                    <div class="col-sm-7">
                                        <div class="input-group position-relative">
                                            <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text"
                                                placeholder="Enter Number">

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <style>
                                .custom-table {
                                    width: 100%;
                                    border-collapse: separate;
                                    border-spacing: 0;
                                    border-radius: 10px;
                                    margin-top: 1rem;
                                }

                                .custom-table th,
                                .custom-table td {
                                    padding: 3px 6px;
                                    border: 1px solid #d1d5db;
                                    border-radius: 10px;
                                    font-size: 13px;
                                }

                                .custom-table th {
                                    background-color: #FF9933;
                                    color: white;
                                    font-size: 12px;
                                    font-weight: bold;
                                    border-top-left-radius: 10px;
                                    border-top-right-radius: 10px;
                                }

                                .custom-table tr:nth-child(odd) {
                                    background-color: #f3f4f6;
                                }

                                .custom-table tr:nth-child(even) {
                                    background-color: #fff;
                                }

                                .custom-table tfoot tr {
                                    background-color: white !important;
                                    font-weight: bold;
                                    /* color: ; */
                                }

                                .custom-table tfoot tr .foottd {
                                    border: none;
                                }

                                .custom-table tbody tr:last-child td {
                                    border-bottom: none;
                                }

                                .custom-table .status-released {
                                    color: green;
                                }

                                .custom-table .check-icon {
                                    display: inline-block;
                                    background-color: green;
                                    color: white;
                                    padding: 3px 6px;
                                    border-radius: 50%;
                                }

                                .custom-table .small-text {
                                    font-size: 0.9rem;
                                    color: #555;
                                }

                                .custom-table .empty-row td {
                                    border: none;
                                }

                                .table-container {
                                    overflow-x: auto;
                                }
                            </style>
                            <div class="table-responsive table-container">

                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>Receipt No</th>
                                            <th>Date &amp; Time</th>
                                            <th>Loan Amount</th>
                                            <th>Interest</th>
                                            <th>Total Payable</th>
                                            <th>Return Date &amp; Time</th>
                                            <th>Return Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>GRV001-1</td>
                                            <td>10-01-2024<br>10:30AM</td>
                                            <td>₹ 50,000</td>
                                            <td>₹ 2,000</td>
                                            <td>₹ 52,000</td>
                                            <td>05-06-2024<br>02:30PM</td>
                                            <td>
                                                <div class="status-released">
                                                    Released <i class="fa-solid fa-lg fa-square-check"></i>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>GRV002-1</td>
                                            <td>15-02-2024<br>02:15PM</td>
                                            <td>₹ 30,000</td>
                                            <td>₹ 1,500</td>
                                            <td>₹ 31,500</td>
                                            <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>GRV003-1</td>
                                            <td>20-03-2024<br>05:45PM</td>
                                            <td>₹ 20,000</td>
                                            <td>₹ 1,000</td>
                                            <td>₹ 21,000</td>
                                            <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Total</td>
                                            <td class="foottd text-center">-</td>
                                            <td>₹ 1,00,000</td>
                                            <td>₹ 4,500</td>
                                            <td>₹ 1,04,500</td>
                                            <td class="foottd"></td>
                                            <td class="foottd"></td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection