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

@php $data = new_component_array('breadcrumb',"Girvi Ledger") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- --------top search card ---- -->
            <div class="col-md-12 mb-3">
                <div class="card round curve">
                    <div class="card-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <label>Filter by Date</label>
                                <div class="input-group ">

                                    <input class="form-control  btn-roundhalf  border-dark" type="date">
                                </div>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label>Search</label>
                                <div class="input-group ">

                                    <input class="form-control  btn-roundhalf  border-dark" type="text" placeholder="Search by Name Jewellery type">
                                </div>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label>Sort By</label>
                                <div class="input-group ">
                                    <select class="form-select btn-roundhalf">
                                        <option selected disabled>Select Type:</option>
                                        <option>Recieved Date</option>
                                        <option>Loan Amount </option>
                                        <option>Due Date</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-1" style="align-self: center;">
                                <!-- <label>Export Report</label> -->
                                <button class="btn btn-gradient-danger btn-roundhalf mt-2 _effect--ripple waves-effect waves-light">
                                    <span class="btn-text-inner">Export PDF </span>

                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card round curve">
                    <div class="card-body">
                        <div class="mb-4">
                            <!-- <div class="">
                                <h6 class="fs-15 mb-0 section-title">Current Record :</h6>
                            </div> -->
                            <div class="">
                                <table class="custom-table bg-header-primary">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Contact No.</th>
                                            <th>Jewellery Type</th>
                                            <th>Weight</th>
                                            <th>Purity</th>
                                            <th>Loan Amount</th>
                                            <th>Interest % </th>
                                            <th>Received Date </th>
                                            <th>Due Date </th>
                                            <th>Payment Status </th>
                                            <th>Reminder </th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>9988776655</td>
                                            <td>Silver Chain</td>
                                            <td>50gm</td>
                                            <td>925</td>
                                            <td> ₹ 20,000</td>
                                            <td>8%</td>
                                            <td>16-04-2025</td>
                                            <td>16-04-2025</td>
                                            <td class="text-center"><span class="badge badge-danger">Unpaid</span></td>
                                            <td class="text-center"><a href="#" class="btn-roundhalf text-white badge badge-secondary"> <i class="fa-solid fa-bell"></i> &nbsp;Reminder</a></td>
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

                                            <td>Name</td>
                                            <td>9988776655</td>
                                            <td>Silver Chain</td>
                                            <td>50gm</td>
                                            <td>925</td>
                                            <td> ₹ 20,000</td>
                                            <td>8%</td>
                                            <td>16-04-2025</td>
                                            <td>16-04-2025</td>
                                            <td class="text-center"><span class="badge badge-danger">Unpaid</span></td>
                                            <td class="text-center"><a href="#" class="btn-roundhalf text-white badge badge-secondary"> <i class="fa-solid fa-bell"></i> &nbsp;Reminder</a></td>
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

                                            <td>Name</td>
                                            <td>9988776655</td>
                                            <td>Silver Chain</td>
                                            <td>50gm</td>
                                            <td>925</td>
                                            <td> ₹ 20,000</td>
                                            <td>8%</td>
                                            <td>16-04-2025</td>
                                            <td>16-04-2025</td>
                                            <td class="text-center"><span class="badge badge-success">Paid</span></td>
                                            <td class="text-center"><a href="#" class="btn-roundhalf text-white badge badge-secondary"> <i class="fa-solid fa-bell"></i> &nbsp;Reminder</a></td>
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