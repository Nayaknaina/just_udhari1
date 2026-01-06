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
@php $data = new_component_array('breadcrumb',"Repair ") @endphp
<x-new-bread-crumb :data=$data />
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <div class=" ">

                                    <div class="mb-4">
                                        <div class="simple-pill">
                                            <ul class="d-flex nav-ul nav-container-btn mb-3 px-0 py-2" id="pills-tab" role="tablist" style="justify-content: center;">
                                                <li class="nav-li " role="presentation">
                                                    <button class="btn btn-outline-default round px-4 active" id="pills-buy-tab" data-bs-toggle="pill" data-bs-target="#pills-buy" type="button" role="tab" aria-controls="pills-buy" aria-selected="false" tabindex="-1"> Recieved</button>
                                                </li>
                                                <li class="nav-li" role="presentation">
                                                    <button class="btn btn-outline-default round px-4" id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="true">Return</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade active show" id="pills-buy" role="tabpanel" aria-labelledby="pills-buy-tab" tabindex="0">
                                                    <div class=" ">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-1">
                                                                <div class="mb-2 row">
                                                                    <div class="col-sm-10">
                                                                        <div class="input-group position-relative mb-0">
                                                                            <input class="form-control h-32px btn-roundhalf border-primary" type="text" placeholder="Customer Name/ ID ">
                                                                            <a href="{{route('customers.create')}}" class="add-btn"><span class="add-icon">+</span></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            <h6 class="fs-15 section-title">Item Details:</h6>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-6 ">
                                                                <label class="mb-1 small">Weight</label>
                                                                <div class="input-group position-relative mb-1">
                                                                    <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                                        placeholder="Weight">
                                                                    <span class="gm-inside">Gm</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-1">
                                                                <label class="mb-1 small">Material</label>
                                                                <div class="input-group mb-1">
                                                                    <div class="custom-select-wrap">
                                                                        <select class="form-select fs-13px h-32px btn-roundhalf">
                                                                            <option selected disabled>Select Material :</option>
                                                                            <option>Gold</option>
                                                                            <option>Silver</option>
                                                                        </select>
                                                                        <span class="custom-select-arrow">▼</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-1">
                                                                <label class="mb-1 small">Item Image</label>
                                                                <div class="input-group mb-1">
                                                                    <input id="fileNameDisplay" class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Image Upload" readonly>
                                                                    <input type="file" id="uploadFile" class="d-none" accept=".jpg,.jpeg,.png,.webp">
                                                                    <button type="button" class="gm-button" onclick="document.getElementById('uploadFile').click();">Upload</button>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                const fileInput = document.getElementById('uploadFile');
                                                                const textInput = document.getElementById('fileNameDisplay');

                                                                fileInput.addEventListener('change', function() {
                                                                    if (fileInput.files.length > 0) {
                                                                        textInput.value = fileInput.files[0].name;
                                                                    }
                                                                });
                                                            </script>
                                                            <div class="col-md-6 mb-1">
                                                                <label class="mb-1 small">Estimated Cost </label>
                                                                <div class="input-group mb-1">
                                                                    <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Loan Amount">
                                                                    <span class="gm-inside">₹</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 mb-1">
                                                                <label class="mb-1 small">Repair Note </label>
                                                                <div class="input-group ">
                                                                    <input class="form-control h-32px btn-roundhalf border-dark " type="text" placeholder="Detailed description ">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="action-container">
                                                            <button class="btn btn-outline-web">Print</button>
                                                            <button class="btn btn-outline-web">SAVE</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade " id="pills-sell" role="tabpanel" aria-labelledby="pills-sell-tab" tabindex="0">
                                                    <div class=" ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h6 class="fs-15 section-title">Billing Payment:</h6>
                                                            <!-- <button class="add-btn toggle-loan-btn" type="button">
                                                                <span class="add-icon">+</span>
                                                            </button> -->
                                                        </div>
                                                        <div>
                                                            <div class="row">

                                                                <div class="col-md-6 mb-1">
                                                                    <label class="mb-1 small">GST (3%) </label>
                                                                    <div class="input-group mb-1">
                                                                        <input class="form-control h-32px round border-dark" type="text" placeholder="Interest">
                                                                        <span class="gm-inside">%</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-1">
                                                                    <label class="mb-1 small">Balance Due </label>
                                                                    <div class="input-group mb-1">
                                                                        <input class="form-control h-32px round border-dark" type="text" placeholder="Online ">
                                                                        <span class="gm-inside">₹</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-1">
                                                                    <label class="mb-1 small">Advance Payment </label>
                                                                    <div class="input-group mb-1">
                                                                        <input class="form-control h-32px round border-dark" type="text" placeholder="Cash ">
                                                                        <span class="gm-inside">₹</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-1">
                                                                    <label class="mb-1 small"> </label>
                                                                    <div class="input-group mb-1">
                                                                        <input class="form-control h-32px round border-dark" type="text" placeholder="Online ">
                                                                        <span class="gm-inside">₹</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class=" loan-details-form" style="display: none;">
                                                                <div class="row">



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
                                                        <div class="action-container">
                                                            <button class="btn btn-outline-web">Print</button>
                                                            <button class="btn btn-outline-web">SAVE</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <script>
                                        const tabButtons = document.querySelectorAll('.nav-ul li');

                                        // Function to apply active class
                                        function setActiveTab(index) {
                                            tabButtons.forEach(btn => btn.classList.remove('active-li'));
                                            if (tabButtons[index]) {
                                                tabButtons[index].classList.add('active-li');
                                            }
                                        }

                                        // On click, set active and store in localStorage
                                        tabButtons.forEach((li, index) => {
                                            li.addEventListener('click', () => {
                                                setActiveTab(index);
                                                localStorage.setItem('activeTabIndex', index);
                                            });
                                        });

                                        // On page load, restore active tab
                                        window.addEventListener('DOMContentLoaded', () => {
                                            const savedIndex = localStorage.getItem('activeTabIndex');
                                            if (savedIndex !== null) {
                                                setActiveTab(savedIndex);
                                            } else {
                                                setActiveTab(0); // Default to first tab
                                            }
                                        });
                                    </script>

                                    <!-- =========== -->



                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 mb-3">
                        <div class="card round curve">
                            <div class="card-body">
                                <div class="mb-4">

                                    <div class=" table-responsive-mobile  ">
                                        <table class="fixed-custom-table bg-header-primary-pd1 ">
                                            <thead>
                                                <tr>

                                                    <th>S.No.</th>
                                                    <th>Date &amp; Time</th>
                                                    <th>Customer Name/ ID </th>
                                                    <th>Material</th>
                                                    <th>Item Type </th>
                                                    <th>Total Charge</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="pb-2 scrollable-tbody h-320">
                                                <tr>
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                                    <td>1</td>
                                                    <td>10-01-2024<br>10:30AM</td>
                                                    <td>Piyush</td>

                                                    <td>Gold</td>
                                                    <td>ring</td>
                                                    <td>₹ 52,000</td>
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
                                            <tfoot class="bg-header-primary-pd1">
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
            </div>
        </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection