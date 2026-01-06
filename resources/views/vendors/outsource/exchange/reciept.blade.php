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

@php $data = new_component_array('breadcrumb',"Sample Reciept") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 mb-3">
                <div class="card round curve">
                    <div class="card-body py-2">
                        <div class="mb-4">
                            <div class="simple-pill">
                                <ul class="d-flex nav-ul nav-container-btn mb-3 px-0 py-2" id="pills-tab" role="tablist" style="justify-content: center;">
                                    <li class="nav-li " role="presentation">
                                        <button class="btn btn-outline-default round px-4 active" id="pills-buy-tab" data-bs-toggle="pill" data-bs-target="#pills-buy" type="button" role="tab" aria-controls="pills-buy" aria-selected="false" tabindex="-1"> Tounch Form</button>
                                    </li>
                                    <li class="nav-li" role="presentation">
                                        <button class="btn btn-outline-default round px-4 " id="pills-sell-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="true"> Recieved Sample</button>
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
                                                                <input class="form-control h-32px round border-success" type="text" placeholder="Enter Name / Mobile No./ Girvi Id">
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
                                                            placeholder=":Purity(%)">
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
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Powder">
                                                        <span class="gm-inside">gm</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-1">
                                                    <div class="input-group position-relative">
                                                        <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Charges">
                                                        <span class="gm-inside">₹</span>
                                                    </div>
                                                </div>

                                            </div>

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
                                                                <input class="form-control h-32px round border-success" type="text" placeholder="Enter Name / Mobile No./ Girvi Id">
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
            <div class="col-md-7 mb-3">
                <div class="card round curve">
                    <div class="card-body ">
                        <div class=" table-responsive-mobile">
                            <table class="fixed-custom-table bg-header-primary-pd">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Customer Name/No.</th>
                                        <th>Weight </th>
                                        <th>Sample </th>
                                        <th>Purity % </th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody class="pb-2 scrollable-tbody h-270">
                                    <tr>
                                        <td>1</td>
                                        <td>22-04-2025</td>
                                        <td>02:50 PM</td>
                                        <td>Allen Bradley</td>
                                        <td>Account / Wallet</td>

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



                                        <td><b>Total Sample :-</b></td>
                                        <td>
                                            <div class="td-input-wrapper">
                                                <span class="td-value" id="gmValue">0/00.00</span>
                                                <span class="td-unit">gm</span>
                                            </div>
                                        </td>
                                        <td><b>Total Exchange </b></td>
                                        <td>
                                            <div class="td-input-wrapper">
                                                <span class="td-value" id="gmValue">0/00.00</span>
                                                <span class="td-unit">gm</span>
                                            </div>
                                        </td>


                                        <td><b>Total Return </b></td>
                                        <td>
                                            <div class="td-input-wrapper">
                                                <span class="td-value" id="gmValue">0/00.00</span>
                                                <span class="td-unit">gm</span>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2"><a href="#"><b>GST Print</b> <i class="fa-solid fa-2xl fa-print"></i></a></td>
                                        <td class="text-center">
                                            <a href="#"><i class="fa-solid fa-2xl fa-print"></i></a>
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
</section>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection