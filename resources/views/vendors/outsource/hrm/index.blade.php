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

@php $data = new_component_array('breadcrumb',"Employee List") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <div class="mb-3 p-0  flex-wrap align-items-center justify-content-between">
                                    <div class="d-flex gap-2">
                                        <div class="col-md-4 py-0 px-1">
                                            <div class="input-group position-relative mb-0">
                                                <input class="form-control h-32px btn-roundhalf border-primary" type="text" placeholder="Enter Employee Name /Mobile No. ">
                                                <a href="{{route('vendor.hrm.add')}}" class="add-btn"><span class="add-icon">+</span></a>
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-1 px-0 mobile-hidden" style="justify-items: end;     align-self: end;">
                                            <div class=" ">
                                                <div class="action-buttons">

                                                    <div class="hover-menu-wrapper">
                                                        <button class="hover-btn">
                                                            List <i class="fa-solid fa-list"></i>
                                                        </button>
                                                        <div class="hover-menu">
                                                            <a href="{{ route('vendor.hrm.attendance') }}">Attendance List</a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="custom-table bg-header-primary-pd">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Image</th>
                                            <th>Employee Name </th>
                                            <th>Mobile No. </th>
                                            <th>Attendance Date</th>
                                            <th>Time IN</th>
                                            <th>Time OUT</th>
                                            <th>Position</th>
                                            <th>Status </th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody class="pb-2">
                                        <tr>
                                            <td>1</td>
                                            <td><img src="{{asset('main/assets/img/profile-30.png')}}" style="height: 50px;"></td>
                                            <td>Name</td>
                                            <td>9988774455</td>
                                            <td>29-04-2025</td>
                                            <td>01:00 PM</td>
                                            <td>10:00 PM</td>
                                            <td>karigar</td>
                                            <td class="text-center"><span class="badge badge-info mb-2 me-4">Active</span></td>
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
                                                        <a class="dropdown-item" href="{{route('vendor.hrm.view')}}">View <i class="fa-solid text-success fa-eye"></i></a>
                                                        <a class="dropdown-item" href="{{route('vendor.hrm.view')}}">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td><img src="{{asset('main/assets/img/profile-5.jpg')}}" style="height: 50px;"></td>
                                            <td>Name</td>
                                            <td>9988774455</td>
                                            <td>29-04-2025</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>Allen Bradley</td>
                                            <td class="text-center"><span class="badge badge-danger mb-2 me-4">On Leave</span></td>
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
                                                        <a class="dropdown-item" href="{{route('vendor.hrm.view')}}">View <i class="fa-solid text-success fa-eye"></i></a>
                                                        <a class="dropdown-item" href="{{route('vendor.hrm.view')}}">Edit <i class="fa-solid text-warning fa-pen-to-square"></i> </a>
                                                        <a class="dropdown-item" href="">Delete <i class="fa-solid text-danger fa-trash-can"></i></a>
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
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection