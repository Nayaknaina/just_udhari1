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

@php $data = new_component_array('breadcrumb',"Add Employee ") @endphp
<x-new-bread-crumb :data=$data />

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="edit-profile">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card round curve">
                                <div class="card-body">
                                    <form>
                                        <div class=" mb-2">
                                            <div class="profile-title d-flex">
                                                <div class="d-flex align-items-center py-2" style="position: relative; width: max-content;">
                                                    <img id="profileImage" class="img-70 rounded-circle" alt="Profile" src="/main/assets/img/profile-30.png" style="object-fit: cover;width:90px;height:90px;">

                                                    <label for="fileInput" style="position: absolute; bottom: 0; right: 0; background: white; border-radius: 50%; padding: 5px; cursor: pointer;">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </label>

                                                    <input type="file" id="fileInput" accept="image/*" style="display: none;">
                                                </div>
                                                <div class="flex-grow-1 pl-3" style="align-self: center;">
                                                    <h3 class="mb-1">Name Name</h3>
                                                    <p>Karigar</p>
                                                </div>
                                                <script>
                                                    document.getElementById('fileInput').addEventListener('change', function(event) {
                                                        const file = event.target.files[0];
                                                        if (file) {
                                                            const reader = new FileReader();
                                                            reader.onload = function(e) {
                                                                document.getElementById('profileImage').src = e.target.result;
                                                            }
                                                            reader.readAsDataURL(file);
                                                        }
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="form-label">Bio</h6>
                                            <textarea class="form-control" rows="5">I am a hardworking and passionate person, always ready to learn and grow.</textarea>
                                        </div>
                                        <div class="form-footer">
                                            <button class="btn btn-primary btn-block">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <form class="card round curve">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-6">
                                                <label class="form-label">Full Name</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Enter Employee Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email address</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="email" placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Designation</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Enter Designation">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mobile Number</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Enter Mobile No.">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Address</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Home Address">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">PinCode</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="number" placeholder="PinCode">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">City</label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="City">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">State </label>
                                                <input class="form-control h-32px btn-roundhalf border-dark" type="number" placeholder="State">
                                            </div>
                                        </div>
                                        <!-- Resume Upload -->
                                        <div class="col-md-4 mb-3">
                                            <label class="mb-1">Resume</label>
                                            <div class="input-group mb-1">
                                                <input id="resumeDisplay" class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Resume file" readonly>
                                                <input type="file" id="resume" class="d-none" accept=".doc,.docx,.pdf">
                                                <button type="button" class="gm-button" onclick="document.getElementById('resume').click();">Upload</button>
                                            </div>
                                        </div>

                                        <!-- Aadhaar Upload -->
                                        <div class="col-md-4 mb-3">
                                            <label class="mb-1">Aadhaar Img</label>
                                            <div class="input-group mb-1">
                                                <input id="aadhaarDisplay" class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Aadhaar image" readonly>
                                                <input type="file" id="aadhaarImg" class="d-none" accept=".jpg,.jpeg,.png,.webp">
                                                <button type="button" class="gm-button" onclick="document.getElementById('aadhaarImg').click();">Upload</button>
                                            </div>
                                        </div>

                                        <!-- Bank Passbook Upload -->
                                        <div class="col-md-4 mb-3">
                                            <label class="mb-1">Bank Passbook Img</label>
                                            <div class="input-group mb-1">
                                                <input id="bankDisplay" class="form-control h-32px btn-roundhalf border-dark" type="text" placeholder="Bank passbook image" readonly>
                                                <input type="file" id="bankBook" class="d-none" accept=".jpg,.jpeg,.png,.webp">
                                                <button type="button" class="gm-button" onclick="document.getElementById('bankBook').click();">Upload</button>
                                            </div>
                                        </div>

                                        <!-- JavaScript -->
                                        <script>
                                            document.getElementById('resume').addEventListener('change', function() {
                                                const file = this.files[0];
                                                if (file) {
                                                    document.getElementById('resumeDisplay').value = file.name;
                                                }
                                            });

                                            document.getElementById('aadhaarImg').addEventListener('change', function() {
                                                const file = this.files[0];
                                                if (file) {
                                                    document.getElementById('aadhaarDisplay').value = file.name;
                                                }
                                            });

                                            document.getElementById('bankBook').addEventListener('change', function() {
                                                const file = this.files[0];
                                                if (file) {
                                                    document.getElementById('bankDisplay').value = file.name;
                                                }
                                            });
                                        </script>

                                        <div class=" col-md-12">
                                            <button class="btn btn-primary" type="submit">Update Profile</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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