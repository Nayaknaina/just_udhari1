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

@php $data = new_component_array('breadcrumb'," List") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <!-- <div class="col-md-12 pt-2 ">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <label></label>
                        <div class="input-group ">
                            <div class="custom-select-wrap">
                                <select class="form-select h-32px btn-roundhalf">
                                    <option value="" selected disabled hidden>Daily Profit</option>
                                    <option value="1" disabled>One</option>
                                    <option value="2" disabled>Two</option>
                                </select>
                                <span class="custom-select-arrow">▼</span>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label> </label>
                        <div class="input-group ">
                            <div class="custom-select-wrap">
                                <select class="form-select h-32px btn-roundhalf">
                                    <option value="" selected disabled hidden>Gold Balance</option>
                                    <option value="1" disabled>One</option>
                                    <option value="2" disabled>Two</option>
                                </select>
                                <span class="custom-select-arrow">▼</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-1">
                        <label> </label>
                        <div class="input-group ">
                            <div class="custom-select-wrap">
                                <select class="form-select h-32px btn-roundhalf">
                                    <option value="" selected disabled hidden>Monthly Revenue</option>
                                    <option value="1" disabled>One</option>
                                    <option value="2" disabled>Two</option>
                                </select>
                                <span class="custom-select-arrow">▼</span>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 mb-1 pt-2" style="align-self: center;">

                        <div class="btn-group  mb-2 me-4" role="group">
                            <button id="outlineDropdown7" type="button" class="btn btn-outline-dark dropdown-toggle _effect--ripple waves-effect waves-light" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter <i class="fa-solid fa-filter"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="outlineDropdown7">
                                <a href="javascript:void(0);" class="dropdown-item">Name</a>
                                <a href="javascript:void(0);" class="dropdown-item">Date </a>
                            </div>
                        </div>
                    </div>


                </div>
            </div> -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <div class="mb-1 p-0 d-flex flex-wrap align-items-center justify-content-between">
                                    <div class="d-flex gap-2">
                                        <div class="col-md-6 py-0 px-1">
                                            <div class="input-group position-relative">
                                                <input class="form-control h-27px btn-roundhalf border-dark" type="text" placeholder="Select date range">
                                                <span class="gm-inside"><i class="fa-solid fa-calendar-days"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 py-0 px-1">
                                            <div class="input-group position-relative">
                                                <input class="form-control h-27px btn-roundhalf border-dark" type="text" placeholder="Search Result ">
                                                <span class="gm-inside"><i class="fa-solid fa-magnifying-glass"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <!-- <button class="btn btn-outline-dark mb-2 _effect--ripple waves-effect waves-light">Old Records</button> -->
                                        <div class="btn-group  mb-2 me-4" role="group">
                                            <button id="outlineDropdown7" type="button" class="btn  btn-outline-dark dropdown-toggle _effect--ripple waves-effect waves-light" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Filter <i class="fa-solid fa-filter"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="outlineDropdown7">
                                                <a href="javascript:void(0);" class="dropdown-item">Name</a>
                                                <a href="javascript:void(0);" class="dropdown-item">Date </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" table-responsive-mobile">
                                    <table class="fixed-custom-table bg-header-primary-pd1">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Date </th>
                                                <th>Time </th>
                                                <th>Customer Name/No.</th>

                                                <th>From/To </th>
                                                <th>Reason </th>
                                                <th>Gold in(+) </th>
                                                <th>Gold in(-) </th>
                                                <th>Rupees in(+) </th>
                                                <th>Rupees Out(-) </th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tbody class="pb-2 scrollable-tbody h-270">
                                            <tr>
                                                <td>1</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>22-04-2025</td>
                                                <td>02:50 PM</td>
                                                <td>Allen Bradley</td>
                                                <td>Account / Wallet</td>
                                                <td>Sell</td>
                                                <td>30.60 gm</td>
                                                <td>6 gm</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">₹ 20,000</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
                                                                <circle cx="12" cy="12" r="1"></circle>
                                                                <circle cx="12" cy="5" r="1"></circle>
                                                                <circle cx="12" cy="19" r="1"></circle>
                                                            </svg>
                                                        </a>
                                                        <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                                            <a class="dropdown-item" href="javascript:void(0);">View <i class="fa-solid text-success fa-eye"></i></a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="2" class="text-center">Total</td>
                                                <td>
                                                    <div class="td-input-wrapper">
                                                        <span class="td-value" id="gmValue">00.00</span>
                                                        <span class="td-unit">gm</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="td-input-wrapper">
                                                        <span class="td-value" id="gmValue">00.00</span>
                                                        <span class="td-unit">gm</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="td-input-wrapper">
                                                        <span class="td-value" id="gmValue">00.00</span>
                                                        <span class="td-unit">₹</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="td-input-wrapper">
                                                        <span class="td-value" id="gmValue">00.00</span>
                                                        <span class="td-unit">₹</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <!-- <a href="#"><i class="fa-solid fa-2xl fa-floppy-disk"></i></a> -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="border-success text-center"> Profit</td>
                                                <td colspan="2" class="text-center"></td>
                                                <td colspan="2">
                                                    <div class="td-input-wrapper ">
                                                        <span class="td-value text-success" id="gmValue">00.00</span>
                                                        <span class="td-unit text-success">gm</span>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="td-input-wrapper ">
                                                        <span class="td-value text-success" id="gmValue">00.00</span>
                                                        <span class="td-unit text-success">₹</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href=""><i class="fa-solid fa-xl fa-print"></i></a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection