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
                        <div class="row ">
                            <div class="col-md-2 mb-2">
                                <label class="mb-1">Scan Category</label>
                                <div class="input-group mb-1">
                                    <select class="form-select h-32px btn-roundhalf">
                                        <option selected disabled>Scan Category</option>
                                        <option>RFID</option>
                                        <option>QrCode</option>
                                        <option>HUID</option>
                                    </select>
                                    <span class="custom-select-arrow">▼</span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="mb-1">Metal Category</label>
                                <div class="input-group mb-1">
                                    <select class="form-select h-32px btn-roundhalf">
                                        <option selected disabled>Please Select</option>
                                        <option>Gold</option>
                                        <option>Silver</option>
                                    </select>
                                    <span class="custom-select-arrow">▼</span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="mb-1">Jewellery Type </label>
                                <div class="input-group mb-1">
                                    <select class="form-select h-32px btn-roundhalf select2">
                                        <option selected disabled>Select Type</option>
                                        <option>Rings</option>
                                        <option>Earrings</option>
                                        <option>Necklace</option>
                                        <option>Payal</option>
                                        <option>Bangles</option>
                                        <option>Bracelet</option>
                                        <option>Nose Pin</option>
                                        <option>Maang Tikka</option>
                                        <option>Toe Rings</option>
                                        <option>Armlet</option>
                                        <option>Chain</option>
                                        <option>Pendant</option>
                                        <option>Anklet</option>
                                        <option>Kamarbandh</option>
                                        <option>Choker</option>
                                        <option>Jhumka</option>
                                        <option>Brooch</option>
                                        <option>Hair Pin</option>
                                        <option>Wedding Set</option>
                                        <option>Cufflinks</option>

                                    </select>
                                    <span class="custom-select-arrow">▼</span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="mb-1">Search</label>
                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Enter RFID Tags">
                            </div>
                            <div class="col-md-1 mt-2 " style="align-self: center;">
                                <button class="btn btn-gradient-danger btn-roundhalf  h-32px _effect--ripple waves-effect waves-light">
                                    Reset
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <style>

                    </style>
                    <div class="col-md-4 mb-3">
                        <div class="card round curve">
                            <div class="card-body p-2">
                                <div class=" mb-3">
                                    <div class="card bg-primary-light position-relative" style="overflow: hidden;">
                                        <div class="vertical-ribbon">Wt: 0/0</div>
                                        <div class="card-content1">
                                            <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                                <h5 class="card-title1 mb-0">Available</h5>
                                                <h5 class="card-title1 mb-0">16</h5>
                                            </div>
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
                            <div class="card-body p-2">
                                <div class=" mb-3">
                                    <div class=" mb-3">
                                        <div class="card bg-primary-light position-relative" style="overflow: hidden;">
                                            <div class="vertical-ribbon">Wt: 0/0</div>
                                            <div class="card-content1">
                                                <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                                    <h5 class="card-title1 mb-0">Scanned</h5>
                                                    <h5 class="card-title1 mb-0">16</h5>
                                                </div>
                                            </div>
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
                            <div class="card-body p-2">
                                <div class=" mb-3">
                                    <div class=" mb-3">
                                        <div class="card bg-primary-light position-relative" style="overflow: hidden;">
                                            <div class="vertical-ribbon">Wt: 0/0</div>
                                            <div class="card-content1">
                                                <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                                    <h5 class="card-title1 mb-0">Missing RFID Tags</h5>
                                                    <h5 class="card-title1 mb-0">16</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title1 text-center text-danger">Missing RFID Tags </h5>
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