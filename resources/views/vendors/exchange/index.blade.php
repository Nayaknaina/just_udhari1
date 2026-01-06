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

@php $data = new_component_array('breadcrumb',"Jewellery Exchange") @endphp
<x-new-bread-crumb :data=$data />
<section class="content">
    <div class="container-fluid">

        <!-- --------top search card ---- -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card round curve">
                    <div class="card-body py-2">
                        <div class="mb-4">
                            <div class="simple-pill">
                                <ul class="d-flex nav-ul nav-container-btn mb-3 px-0 py-2" id="pills-tab" role="tablist" style="justify-content: center;">
                                    <li class="nav-li " role="presentation">
                                        <button class="btn btn-outline-default round px-4 active" id="pills-buy-tab" data-bs-toggle="pill" data-bs-target="#pills-buy" type="button" role="tab" aria-controls="pills-buy" aria-selected="false" tabindex="-1"> Buy</button>
                                    </li>
                                    <li class="nav-li" role="presentation">
                                        <button class="btn btn-outline-default round px-4 " id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="true"> Sell</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade active show" id="pills-buy" role="tabpanel" aria-labelledby="pills-buy-tab" tabindex="0">
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
                                                <div class="col-md-6 col-6 ">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Weight(gm)">
                                                        <span class="gm-inside">gm</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 ">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Tounch(%)">
                                                        <span class="gm-inside">%</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group ">
                                                        <div class="custom-select-wrap">
                                                            <select class="form-select h-32px btn-roundhalf " fdprocessedid="ndtoz">
                                                                <option selected="" disabled="">Sample </option>
                                                                <option>Rava </option>
                                                                <option>Dhali </option>
                                                                <option>Depa</option>
                                                                <option>Zevar</option>
                                                                <option>Others</option>
                                                            </select>
                                                            <span class="custom-select-arrow">▼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="add-btn toggle-loan-btn" type="button">
                                                    <span class="add-icon">+</span>
                                                </button>
                                            </div>
                                            <div class=" row loan-details-form mb-2" style="display: none;">
                                                <div class="col-md-12">
                                                    <table class="custom-table bg-header-primary">
                                                        <thead>
                                                            <tr>
                                                                <th>Weight</th>
                                                                <th>Tounch</th>
                                                                <th>Sample</th>
                                                                <th>Fine</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>10.000</td>
                                                                <td>1.00</td>
                                                                <td>Rava</td>
                                                                <td>0.100</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10.000</td>
                                                                <td>1.00</td>
                                                                <td>Rava</td>
                                                                <td>0.100</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Fine">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="(दिया) GIVEN GOLD">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="(बाकि) DIFF GOLD">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Rate [P/S]">
                                                            <span class="gm-inside">₹/10gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Cash [G/R]">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Charges">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-3 col-5 fs-13px">Final Cash : </label>
                                                    <div class="col-sm-9 ">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px  btn-roundhalf  border-dark pe-1" type="text" placeholder="Enter Amount">
                                                            <span class="gm-inside">₹</span>

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

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="pills-sell" role="tabpanel" aria-labelledby="pills-sell-tab" tabindex="0">
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
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group ">
                                                        <div class="custom-select-wrap">
                                                            <select class="form-select h-32px btn-roundhalf select2" fdprocessedid="ndtoz">
                                                                <option selected="" disabled="">Select Sample </option>
                                                            </select>
                                                            <span class="custom-select-arrow">▼</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 ">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark " type="text"
                                                            placeholder="Weight(gm)">
                                                        <span class="gm-inside">gm</span>
                                                    </div>
                                                </div>


                                            </div>
                                            <div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Fine">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="(दिया) GIVEN GOLD">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="(बाकि) DIFF GOLD">
                                                            <span class="gm-inside">gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Rate [P/S]">
                                                            <span class="gm-inside">₹/10gm</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Cash [G/R]">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-1">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Charges">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-3 col-5 fs-13px">Final Cash : </label>
                                                    <div class="col-sm-9 ">
                                                        <div class="input-group position-relative">
                                                            <input class="form-control h-32px  btn-roundhalf  border-dark pe-1" type="text" placeholder="Enter Amount">
                                                            <span class="gm-inside">₹</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="action-container">
                                                <button class="btn btn-outline-web">Print</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card round curve">
                    <div class="card-body ">
                        <div class="mb-3 d-flex" style="justify-content: space-between;">
                            <div class="action-buttons">
                                <a href="http://localhost:8000/vendors/ecommerce/vendor/girvi/ledger" class="btn btn-outline-primary btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-book"></i>
                                </a>
                                {{-- <a href="{{ route('vendor.exchange.list') }}" class="btn btn-outline-secondary  btn-rounded _effect--ripple waves-effect waves-light">
                                    <i class="fa-solid fa-bars"></i>
                                </a> --}}

                                <a href="{{ route('vendor.outsource.exchange.reciept') }}" class="btn btn-outline-primary  btn-rounded">
                                    Sample Reciept
                                </a>
                            </div>
                            <div>
                                <div class="hover-menu-wrapper">
                                    <button class="  hover-btn">
                                        List <i class="fa-solid fa-list"></i>
                                    </button>
                                    <div class="hover-menu">
                                        <a href="{{ route('vendor.outsource.exchange.list') }}">Current List</a>
                                        <a href="{{ route('vendor.outsource.exchange.list') }}">Old List</a>
                                        <a href="{{ route('vendor.outsource.exchange.list') }}">Detail List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <!-- <label class="mb-1">To (Source):</label> -->
                                <div class="input-group position-relative">
                                    <input class="form-control h-32px btn-roundhalf border-dark input-red" type="text" placeholder="(बेचना) SALE RATE">
                                </div>
                            </div>
                            <div class="col-md-6 mb-1">
                                <!-- <label class="mb-1">To (Source):</label> -->
                                <div class="input-group position-relative">
                                    <input class="form-control h-32px btn-roundhalf border-dark input-red" type="text" placeholder="(खरीदना) PURCHASE RATE">
                                </div>
                            </div>
                        </div>
                        <h6 class="fs-15 section-title text-center">Udhari Section :</h6>
                        <div class="row">
                            <div class="col-md-4 mb-1">

                            </div>
                            <div class="col-md-4 mb-1">
                                <p><b>GOLD</b></p>
                            </div>
                            <div class="col-md-4 mb-1">
                                <p><b>CASH</b></p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">(पुराना) Old :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">gm</span>

                                </div>
                            </div>
                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">Kch se Jama(+) :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">

                                    <input id="demo6" type="text" value="" name="demo6" class="form-control h-27px  btn-roundhalf border-dark">
                                    <span class="gm-inside">gm</span>
                                    <span class="input-group-btn input-group-append">
                                        <button class="btn btn-sm round btn-danger bootstrap-touchspin-up" type="button"><i class="fa-solid fa-plus"></i></button>
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">Kch se Udhar(-) :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">
                                    <input id="demo6" type="text" value="" name="demo6" class="form-control h-27px  btn-roundhalf border-dark">
                                    <span class="gm-inside">gm</span>
                                    <span class="input-group-btn input-group-append">
                                        <button class="btn btn-sm round btn-danger bootstrap-touchspin-up" type="button"><i class="fa-solid fa-minus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">Jama (+) :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">gm</span>

                                </div>
                            </div>
                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">Udhar (-) :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">gm</span>

                                </div>
                            </div>
                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-27px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-4 fs-10px pr-1">Final :</label>
                            <div class="col-md-4 pr-1 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-32px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">gm</span>

                                </div>
                            </div>
                            <div class="col-md-4 pr-3 pl-1">
                                <div class="input-group position-relative">
                                    <input class="form-control h-32px  btn-roundhalf border-dark pe-1" type="text" placeholder="Enter Amount">
                                    <span class="gm-inside">₹</span>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 text-center">
                                <button class="btn btn-outline-web">PRINT</button>
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

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection