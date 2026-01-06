@extends('layouts.vendors.app')
@section('css')
<link rel="stylesheet" href = "{{ asset('main/assets/css/figma-design.css')}}">
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
@php $data = new_component_array('breadcrumb',"Girvi Details") @endphp
<x-new-bread-crumb :data=$data />
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <div class="col-md-12">
                <ul class="nav-ul nav-container-btn mb-0  " id="myTab" role="tablist">
                    <div class="col-md-6 mb-1 d-flex px-0">
                        <li class="nav-li">
                            <button class="active btn btn-outline-default round  " id="recieved-tab" data-bs-toggle="tab" data-bs-target="#recieved-tab-pane" type="button" role="tab" aria-controls="recieved-tab-pane" aria-selected="true">Girvi Received</button>

                        </li>
                        <li class="nav-li">
                            <button class="btn btn-outline-default round " id="interest-tab" data-bs-toggle="tab" data-bs-target="#interest-tab-pane" type="button" role="tab" aria-controls="interest-tab-pane" aria-selected="false">Interest Pay</button>

                        </li>
                        <li class="nav-li">
                            <button class="btn btn-outline-default round " id="return-tab" data-bs-toggle="tab" data-bs-target="#return-tab-pane" type="button" role="tab" aria-controls="return-tab-pane" aria-selected="false">Girvi Return</button>

                        </li>
                    </div>
                    <div class="col-md-6 mb-1 px-0 mobile-hidden" style="justify-items: end;     align-self: end;">
                        <div class=" ">
                            <div class="action-buttons">
                                <a href="{{route('girvi.index')}}" class=" btn-outline-primary btn-icon btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-book"></i>
                                </a>
                                <!-- <a class=" btn-outline-secondary btn-icon btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-bars"></i>
                                </a> -->
                                <a class=" btn-outline-primary btn-icon btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-user-gear"></i>
                                </a>
                                <!-- <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle btn btn-outline-secondary  btn-rounded " href="#" role="button" id="elementDrodpown1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        List <i class="fa-solid fa-list"></i>
                                    </a>
                                    <div class="dropdown-menu left dot-menu" aria-labelledby="elementDrodpown1" style="will-change: transform;">
                                        <a class="dropdown-item list-drop" href="">Current List </a>
                                        <a class="dropdown-item list-drop" href="">Old List </a>
                                        <a class="dropdown-item list-drop" href="">Detail List </a>
                                    </div>
                                </div> -->
                                <div class="hover-menu-wrapper">
                                    <button class="hover-btn">
                                        List <i class="fa-solid fa-list"></i>
                                    </button>
                                    <div class="hover-menu">
                                        <a href="{{ route('girvi.list') }}">Current List</a>
                                        <a href="{{ route('girvi.list') }}">Old List</a>
                                        <a href="{{ route('girvi.list') }}">Detail List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- first tab panel start -->
                    <div class="tab-pane fade show active" id="recieved-tab-pane" role="tabpanel" aria-labelledby="recieved-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <div class="card round curve">
                                    <div class="card-body ">
                                        <div class=" ">
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="mb-2 row">
                                                        <label class="col-sm-2 fs-13px pr-1"> Name :</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group position-relative mb-0">
                                                                <input class="form-control h-32px btn-roundhalf border-primary" type="text" placeholder="Enter Name / Mobile No./ Girvi Id">
                                                                <a href="{{route('customers.create')}}" class="add-btn"><span class="add-icon">+</span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <h6 class="fs-15 section-title">Jewellery Collection Details:</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-6 ">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Weight">
                                                        <span class="gm-inside">Gm</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 ">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Carat or Percentage">
                                                        <span class="gm-inside">C / %</span>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-6 mb-1">
                                                    <div class="input-group round border-dark">
                                                        <input type="file" id="inputFile" onchange="updateFileLabel(this)">
                                                        <div class="custom-file-display">
                                                            <span class="file-name" id="fileLabel">Item Detail:</span>
                                                            <button class="btn-choose" type="button" id="button-addon2">Upload</button>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function updateFileLabel(input) {
                                                            const fileName = input.files.length > 0 ? input.files[0].name : "Item Detail:";
                                                            document.getElementById("fileLabel").textContent = fileName;
                                                        }
                                                    </script>
                                                </div> -->
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text" placeholder="Market Rate">
                                                        <span class="gm-inside">Gm</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Estimated Value ">
                                                        <span class="gm-inside">₹</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group" style="align-items: center;">
                                                        <div class="custom-select-wrap">
                                                            <select class="form-select fs-13px h-32px btn-roundhalf">
                                                                <option selected disabled>Select Jewellery :</option>
                                                                <option>Jewellery</option>
                                                            </select>
                                                            <span class="custom-select-arrow">▼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group">
                                                        <!-- Readonly Text Input -->
                                                        <input id="fileNameDisplay" class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Item Detail" readonly>

                                                        <!-- Hidden File Input -->
                                                        <input type="file" id="uploadFile" class="d-none" accept=".jpg,.jpeg,.png,.webp">

                                                        <!-- Upload Button -->
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
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Loan Amount">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Interest">
                                                            <span class="gm-inside">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" loan-details-form" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <div class="input-group" style="align-items: center;">
                                                                <div class="custom-select-wrap">
                                                                    <select class="form-select fs-13px h-32px btn-roundhalf border-dark">
                                                                        <option selected disabled>Interest Type:</option>
                                                                        <option>Jewellery</option>
                                                                    </select>
                                                                    <span class="custom-select-arrow">▼</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <div class="input-group position-relative date-wrapper">
                                                                <input class="form-control h-32px btn-roundhalf border-dark" type="date" required>
                                                                <label for="issueDate" class="floating-label">Issue Date</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <div class="input-group position-relative">
                                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Loan Tenure">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <div class="input-group position-relative date-wrapper">
                                                                <input class="form-control h-32px btn-roundhalf border-dark" type="date">
                                                                <label for="issueDate" class="floating-label">Return Date</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <div class="input-group position-relative">
                                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Total Payable">
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
                                            <div class="action-container">
                                                <button class="btn btn-outline-web">Print</button>
                                                <button class="btn btn-outline-web">SAVE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="card round curve">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <div class="simple-pill">
                                                <ul class="nav  mb-3" id="pills-tab" role="tablist">
                                                    <li class="nav-li " role="presentation">
                                                        <button class="btn btn-sm btn-outline-default active" id="pills-current-tab" data-bs-toggle="pill" data-bs-target="#pills-current" type="button" role="tab" aria-controls="pills-current" aria-selected="false" tabindex="-1">Current Record</button>
                                                    </li>
                                                    <li class="nav-li" role="presentation">
                                                        <button class="btn btn-sm btn-outline-default " id="pills-old-tab" data-bs-toggle="pill" data-bs-target="#pills-old" type="button" role="tab" aria-controls="pills-old" aria-selected="true">Old Record</button>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-current" role="tabpanel" aria-labelledby="pills-current-tab" tabindex="0">
                                                        <div class=" table-responsive-mobile">
                                                            <table class="fixed-custom-table bg-header-primary-pd1">
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
                                                                <tbody class="pb-2 scrollable-tbody h-220">
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
                                                                        <td></td>
                                                                        <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                                                    </tr>
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
                                                                        <td>GRV003-1</td>
                                                                        <td>20-03-2024<br>05:45PM</td>
                                                                        <td>₹ 20,000</td>
                                                                        <td>₹ 1,000</td>
                                                                        <td>₹ 21,000</td>
                                                                        <td></td>
                                                                        <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">

                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
                                                                    </tr>

                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade " id="pills-old" role="tabpanel" aria-labelledby="pills-old-tab" tabindex="0">
                                                        <div class=" table-responsive-mobile">
                                                            <table class="fixed-custom-table bg-header-primary-pd1">
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
                                                                <tbody class="pb-2 scrollable-tbody h-220">
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
                                                                        <td></td>
                                                                        <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                                                    </tr>
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
                                                                        <td>GRV003-1</td>
                                                                        <td>20-03-2024<br>05:45PM</td>
                                                                        <td>₹ 20,000</td>
                                                                        <td>₹ 1,000</td>
                                                                        <td>₹ 21,000</td>
                                                                        <td></td>
                                                                        <td>Pending <i class="fa-solid fa-hourglass-start text-warning"></i></td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">

                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
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
                        </div>
                    </div>
                    <!-- first tab panel end -->
                    <!-- second tab panel start -->
                    <div class="tab-pane fade" id="interest-tab-pane" role="tabpanel" aria-labelledby="interest-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <div class="card round curve">
                                    <div class="card-body ">
                                        <div class=" ">
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="mb-2 row">
                                                        <label class="col-sm-2 fs-13px pr-1"> Name :</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group position-relative mb-0">
                                                                <input class="form-control h-32px btn-roundhalf border-primary" type="text" placeholder="Enter Name / Mobile No./ Girvi Id">
                                                                <a href="{{route('customers.create')}}" class="add-btn"><span class="add-icon">+</span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <h6 class="fs-15 section-title">Interest Payment Portal:</h6>
                                            </div>
                                            <div class="row">
                                                <h6 class="col-sm-4 col-5 fs-13px ">Customer Name </h6>
                                                <h6 class="col-sm-1 col-1 pl-1 pr-1 fs-13px "> : </h6>

                                                <h6 class="col-sm-7 col-6 fs-13px">Name</h6>
                                            </div>
                                            <div class="row">
                                                <h6 class="col-sm-4 col-5 fs-13px ">Girvi ID </h6>
                                                <h6 class="col-sm-1 col-1 pl-1 pr-1 fs-13px "> : </h6>

                                                <h6 class="col-sm-7 col-6 fs-13px">GRV0001-2</h6>
                                            </div>
                                            <div class="">
                                                <h6 class="fs-15 section-title">Loan Details:</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="row">
                                                        <h6 class="col-sm-4 col-5 fs-13px ">Loan Amount </h6>
                                                        <h6 class="col-sm-1 col-1 fs-13px pl-1 pr-1"> : </h6>
                                                        <h6 class="col-sm-7 col-6 fs-13px">50,000 ₹</h6>
                                                    </div>
                                                    <div class="row">
                                                        <h6 class="col-sm-4 col-5 fs-13px ">Due Date </h6>
                                                        <h6 class="col-sm-1 col-1 fs-13px pl-1 pr-1"> : </h6>
                                                        <h6 class="col-sm-7 col-6 fs-13px">15-04-2025</h6>
                                                    </div>
                                                    <div class="row">
                                                        <h6 class="col-sm-4 col-5 fs-13px ">Pending Interest </h6>
                                                        <h6 class="col-sm-1 col-1 fs-13px pl-1 pr-1"> : </h6>
                                                        <h6 class="col-sm-7 col-6 fs-13px">50,000 ₹</h6>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-5 fs-13px">Make Payment </label>
                                                        <label class="col-sm-1 col-1 fs-13px pl-1 pr-1"> : </label>
                                                        <div class="col-sm-7 ">
                                                            <div class="input-group" style="align-items: center;">
                                                                <div class="custom-select-wrap">
                                                                    <select class="form-select fs-13px h-27px btn-roundhalf" fdprocessedid="t0ye9">
                                                                        <option selected="" disabled="">Select Payment Method :</option>
                                                                        <option>Cash</option>
                                                                        <option>Phonepe</option>
                                                                    </select>
                                                                    <span class="custom-select-arrow">▼</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <label class="col-sm-4 col-5 fs-13px">Amount to Pay </label>
                                                        <label class="col-sm-1 col-1 fs-13px pl-1 pr-1"> : </label>
                                                        <div class="col-sm-7 ">
                                                            <div class="input-group position-relative">
                                                                <input class="form-control h-27px  btn-roundhalf  border-dark pe-1" type="text" placeholder="Enter Amount">
                                                                <span class="gm-inside">₹</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-center">
                                                        <button class="btn btn-gradient-danger btn-roundhalf mb-2 me-4 _effect--ripple waves-effect waves-light">
                                                            <span class="btn-text-inner">Pay Interest</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="action-container">
                                                <button class="btn btn-outline-web">Print</button>
                                                <button class="btn btn-outline-web">SAVE</button>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="card round curve">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <div class="simple-pill">
                                                <ul class="nav  mb-3" id="pills-tab" role="tablist">
                                                    <li class="nav-li " role="presentation">
                                                        <button class="btn btn-sm btn-outline-default active" id="pills-interest-current-tab" data-bs-toggle="pill" data-bs-target="#pills-interest-current" type="button" role="tab" aria-controls="pills-interest-current" aria-selected="false" tabindex="-1">Current Record</button>
                                                    </li>
                                                    <li class="nav-li" role="presentation">
                                                        <button class="btn btn-sm btn-outline-default " id="pills-interest-old-tab" data-bs-toggle="pill" data-bs-target="#pills-interest-old" type="button" role="tab" aria-controls="pills-interest-old" aria-selected="true">Old Record</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-interest-current" role="tabpanel" aria-labelledby="pills-interest-current-tab" tabindex="0">
                                                        <div class=" table-responsive-mobile  ">
                                                            <table class="fixed-custom-table bg-header-primary-pd1 ">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Receipt No</th>
                                                                        <th>Date &amp; Time</th>
                                                                        <th>Loan Amount</th>
                                                                        <th>Interest</th>
                                                                        <th>Total Payable</th>
                                                                        <th>Return Date &amp; Time</th>
                                                                        <th>Return Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="pb-2 scrollable-tbody h-220">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">
                                                                        <td class="foottd"></td>
                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade " id="pills-interest-old" role="tabpanel" aria-labelledby="pills-interest-old-tab" tabindex="0">
                                                        <div class="table-responsive-mobile">
                                                            <table class="fixed-custom-table bg-header-primary-pd1 ">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Receipt No</th>
                                                                        <th>Date &amp; Time</th>
                                                                        <th>Loan Amount</th>
                                                                        <th>Interest</th>
                                                                        <th>Total Payable</th>
                                                                        <th>Return Date &amp; Time</th>
                                                                        <th>Return Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="pb-2 scrollable-tbody h-220">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">
                                                                        <td class="foottd"></td>
                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
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
                        </div>
                    </div>
                    <!-- second tab panel end-->
                    <!-- third tab panel start -->
                    <div class="tab-pane fade" id="return-tab-pane" role="tabpanel" aria-labelledby="return-tab" tabindex="0">
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <div class="card round curve">
                                    <div class="card-body ">
                                        <div class=" ">
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="mb-2 row">
                                                        <label class="col-sm-2 fs-13px pr-1"> Name :</label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group position-relative mb-0">
                                                                <input class="form-control h-32px btn-roundhalf border-primary" type="text" placeholder="Enter Name / Mobile No./ Girvi Id">
                                                                <a href="{{route('customers.create')}}" class="add-btn"><span class="add-icon">+</span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h6 class="col-sm-4 col-5 fs-13px ">Customer Name </h6>
                                                <h6 class="col-sm-1 col-1 pl-1 pr-1 fs-13px "> : </h6>

                                                <h6 class="col-sm-7 col-6 fs-13px">Name</h6>

                                            </div>
                                            <div class="row">
                                                <h6 class="col-sm-4 col-5 fs-13px ">Girvi ID </h6>
                                                <h6 class="col-sm-1 col-1 pl-1 pr-1 fs-13px "> : </h6>

                                                <h6 class="col-sm-7 col-6 fs-13px">GRV0001-2</h6>

                                            </div>
                                            <div class="">
                                                <h6 class="fs-15 section-title">Loan Details:</h6>
                                            </div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Loan Amount</th>
                                                        <td>₹ 50,000</td>
                                                        <td rowspan="3">
                                                            <img src="{{asset('main/assets/img/product/girvi/necklace.png')}}" style="height:130px;" class="img-fluid" loading="lazy">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Interest Rate </th>
                                                        <td>₹ 50,000</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Payable </th>
                                                        <td>₹ 50,000</td>
                                                    </tr>
                                                </tbody>



                                            </table>

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
                                            <!-- <div class="action-container">
                                                <button class="btn btn-outline-web">Print</button>
                                                <button class="btn btn-outline-web">SAVE</button>
                                            </div> -->



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="card round curve">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <div class="simple-pill">
                                                <ul class="nav  mb-3" id="pills-tab" role="tablist">
                                                    <li class="nav-li " role="presentation">
                                                        <button class="btn btn-sm btn-outline-default active" id="pills-return-current-tab" data-bs-toggle="pill" data-bs-target="#pills-return-current" type="button" role="tab" aria-controls="pills-return-current" aria-selected="false" tabindex="-1">Current Record</button>
                                                    </li>
                                                    <li class="nav-li" role="presentation">
                                                        <button class="btn btn-sm btn-outline-default " id="pills-return-old-tab" data-bs-toggle="pill" data-bs-target="#pills-return-old" type="button" role="tab" aria-controls="pills-return-old" aria-selected="true">Old Record</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-return-current" role="tabpanel" aria-labelledby="pills-return-current-tab" tabindex="0">
                                                        <div class="table-responsive-mobile">
                                                            <table class="fixed-custom-table bg-header-primary-pd1 ">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Receipt No</th>
                                                                        <th>Date &amp; Time</th>
                                                                        <th>Loan Amount</th>
                                                                        <th>Interest</th>
                                                                        <th>Total Payable</th>
                                                                        <th>Return Date &amp; Time</th>
                                                                        <th>Return Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="pb-2 scrollable-tbody h-280">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">
                                                                        <td class="foottd"></td>
                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>

                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade " id="pills-return-old" role="tabpanel" aria-labelledby="pills-return-old-tab" tabindex="0">
                                                        <div class="table-responsive-mobile">
                                                            <table class="fixed-custom-table bg-header-primary-pd1 ">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>Receipt No</th>
                                                                        <th>Date &amp; Time</th>
                                                                        <th>Loan Amount</th>
                                                                        <th>Interest</th>
                                                                        <th>Total Payable</th>
                                                                        <th>Return Date &amp; Time</th>
                                                                        <th>Return Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="pb-2 scrollable-tbody h-280">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                        <td>
                                                                            <div class="form-check form-check-primary form-check-inline mr-0">
                                                                                <input class="form-check-input mr-0" type="checkbox" value="" id="form-check-primary">

                                                                            </div>
                                                                        </td>
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
                                                                </tbody>
                                                                <tfoot class="bg-header-primary-pd1">
                                                                    <tr class="tfoot-tr">
                                                                        <td class="foottd"></td>
                                                                        <td colspan="2" class="text-center">Total</td>
                                                                        <td>- </td>
                                                                        </td>
                                                                        <td>₹ 1,00,000</td>
                                                                        <td>₹ 4,500</td>
                                                                        <td>₹ 1,04,500</td>
                                                                        <td></td>
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
                        </div>
                    </div>
                    <!-- third tab panel end-->
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
        </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection