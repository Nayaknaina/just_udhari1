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

@php $data = new_component_array('breadcrumb',"Income & Expense") @endphp
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
                                <label></label>
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
                            <div class="col-md-3 mb-1" style="align-self: center; text-align:end;">
                                <!-- <label>Export Report</label> -->
                                <!-- <a href="{{ route('vendor.rfid.list') }}" class="btn btn-outline-default round mt-2 _effect--ripple waves-effect waves-light">
                                    <span class="btn-text-inner">List <i class="fa-solid fa-list-ul"></i></span>
                                </a> -->


                                <div class="hover-menu-wrapper">
                                    <button class="hover-btn">
                                        List <i class="fa-solid fa-list"></i>
                                    </button>
                                    <div class="hover-menu">
                                        <a href="{{ route('vendor.rfid.list') }}">Current List</a>
                                        <a href="{{ route('vendor.rfid.list') }}">Old List</a>
                                        <a href="{{ route('vendor.rfid.list') }}">Detail List</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <h5 class="mb-2 text-success">Expense (ख़र्चा)</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">To (Source):</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-green" type="text" placeholder="Enter Source (ख़र्चा  Kaha Hua?)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Reason:</label>
                                        <div class="input-group ">
                                            <div class="custom-select-wrap">
                                                <select class="form-select h-32px btn-roundhalf">
                                                    <option selected="" disabled="">Reason (ख़र्च ka कारण ?) </option>
                                                    <option>one </option>
                                                    <option>two </option>
                                                </select>
                                                <span class="custom-select-arrow">▼</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Amount(-) :</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-green" onkeypress="validateKey(event)" maxlength="5" type="text" placeholder="Cash Paid">
                                            <span class="gm-inside">₹</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Payment Amount</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-green " onkeypress="validateKey(event)" maxlength="5" type="text" placeholder="Online Payment ">
                                            <img src="{{asset('main/assets/img/icons/payment-icon.png')}}" class="gm-inside-img" alt="payment">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Material(-): Gm</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark placeholder-gold input-green" onkeypress="validateDecimal(event)" maxlength="10" type="text" placeholder="Gold ">
                                            <span class="gm-inside">in gm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Material(-): Gm</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark placeholder-silver input-green" onkeypress="validateDecimal(event)" maxlength="10" type="text" placeholder="Silver ">
                                            <span class="gm-inside">in gm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-1 text-center">
                                        <button class="btn btn-outline-web">SAVE</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <h5 class="mb-2 text-danger">Income (आय)</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">To (Source):</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-red" type="text" placeholder="Enter  Source (आय Kaha Se Aya?">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Reason:</label>
                                        <div class="input-group ">
                                            <div class="custom-select-wrap">
                                                <select class="form-select h-32px btn-roundhalf ">
                                                    <option selected="" disabled="">Reason (आय ka कारण ?) </option>
                                                    <option>one </option>
                                                    <option>two </option>
                                                </select>
                                                <span class="custom-select-arrow">▼</span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Amount(+) :</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-red" onkeypress="validateKey(event)" maxlength="5" type="text" placeholder="Cash Paid">
                                            <span class="gm-inside">₹</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Payment Amount</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark input-red" onkeypress="validateKey(event)" maxlength="5" type="text" placeholder="Online Payment ">
                                            <img src="{{asset('main/assets/img/icons/payment-icon.png')}}" class="gm-inside-img" alt="payment">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Material(+): Gm</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark placeholder-gold input-red" onkeypress="validateDecimal(event)" maxlength="10" type="text" placeholder="Gold ">
                                            <span class="gm-inside">in gm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <label class="mb-1">Material(+): Gm</label>
                                        <div class="input-group position-relative">
                                            <input class="form-control h-32px btn-roundhalf border-dark placeholder-silver input-red" onkeypress="validateDecimal(event)" maxlength="10" type="text" placeholder="Silver ">
                                            <span class="gm-inside">in gm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-1 text-center">
                                        <button class="btn btn-outline-web">SAVE</button>
                                    </div>

                                </div>
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