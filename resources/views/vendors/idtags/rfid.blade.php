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

@php $data = new_component_array('breadcrumb',"RFID & Barcode") @endphp
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
                                <label>Sort By</label>
                                <div class="input-group">
                                    <select class="form-select btn-roundhalf">
                                        <option selected="" disabled="">Product Category </option>
                                        <option>one </option>
                                        <option>two </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label>Search </label>
                                <div class="input-group ">
                                    <input class="form-control  btn-roundhalf  border-dark" type="text" placeholder="Enter RFID Tags">
                                </div>
                            </div>
                            <div class="col-md-3 mb-1" style="align-self: center;">
                                <!-- <label>Export Report</label> -->
                                <button class="btn btn-gradient-danger btn-roundhalf mt-3 _effect--ripple waves-effect waves-light">
                                    <span class="btn-text-inner">Reset </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card round curve">
                            <div class="card-body ">
                                <div class=" mb-3">
                                    <div class="card bg-primary-light">
                                        <div class="card-padding text-center ">
                                            <h5 class="card-title1 text-center">Available</h5>
                                        </div>
                                        <div class="card-padding">
                                            <h5 class="card-title1 text-center">16 </h5>
                                            <!-- <p class="mb-0">This is some text within a card body.</p> -->
                                        </div>
                                        <div class="card-padding">
                                            <h6 class="card-title1 text-center"> Wt: 0/0</h6>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title1 text-center text-info">Available Stocks</h5>
                                <div class="table-scroll-container">
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">
                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card bg-default mb-2">
                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card bg-default mb-2">
                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card round curve">
                            <div class="card-body">
                                <div class=" mb-3">

                                    <div class="card bg-primary-light">
                                        <div class="card-padding text-center ">
                                            <h5 class="card-title1 text-center">Scanned</h5>
                                        </div>
                                        <div class="card-padding">
                                            <h5 class="card-title1 text-center">16 </h5>
                                            <!-- <p class="mb-0">This is some text within a card body.</p> -->
                                        </div>
                                        <div class="card-padding">
                                            <h6 class="card-title1 text-center"> Wt: 0/0</h6>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title1 text-center text-success">Scanned Stocks</h5>
                                <div class="table-scroll-container">

                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card round curve">
                            <div class="card-body">
                                <div class=" mb-3">

                                    <div class="card bg-primary-light">
                                        <div class="card-padding text-center ">
                                            <h5 class="card-title1 text-center">Unknown RFID Tags</h5>
                                        </div>
                                        <div class="card-padding">
                                            <h5 class="card-title1 text-center">16 </h5>
                                            <!-- <p class="mb-0">This is some text within a card body.</p> -->
                                        </div>
                                        <div class="card-padding">
                                            <h6 class="card-title1 text-center"> Wt: 0/0</h6>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title1 text-center text-danger">Unknown RFID Tags </h5>
                                <div class="table-scroll-container">

                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="card bg-default mb-2">

                                        <div class="table-responsive">
                                            <table class="table border-none pad-5-10 mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>ID : Ring1</td>
                                                        <td>(e)7985454asds</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : Ring1</td>
                                                        <td>Wt : 3.400/1.400 Gm</td>
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
            </div>
        </div>
</section>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('javascript')

@include('layouts.vendors.js.passwork-popup')

@endsection