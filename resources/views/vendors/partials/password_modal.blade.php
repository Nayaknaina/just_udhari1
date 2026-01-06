<!-- resources/views/auth/password_confirm.blade.php -->

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Enter MPIN Password</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p> Please enter your mpin to edit data.</p>
                <form id = "passwordForm">
                    @csrf
                    <input type="hidden" name="intended_url" id="intended_url">
                    <div class="form-group">
                        <label for="password">MPIN</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your MPIN" required>
                    </div>
                    <div class="alert alert-danger d-none" id="passwordError"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id = "" >Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

   <!-- Modal -->
   <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? Please enter your mpin to confirm.</p>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="blockingmodal" tabindex="-1" role="dialog" aria-labelledby="blockingmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="passwordModalLabel">Enter MPIN Password</h5> -->
                <h5 class="modal-title" id="passwordModalLabel">MPIN ?</h5><button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <p> Enter MPIN to Continue. </p> -->
                <form id = "mpincheckform">
                    @csrf
                    <!-- <input type="hidden" name="intended_url" id="intended_url"> -->
                    <div class="form-group">
                        <label for="password">Enter MPIN to Continue.</label>
                        <div class="input-group form-group">
                            <input type="password" class="form-control text-center" id="password" name="password" placeholder="Enter Your MPIN" required>
                            <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id = "" >Submit</button>
                            </div>
                        </div>
                        <!-- <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your MPIN" required> -->
                    </div>
                    <div class="alert alert-outline-danger alert-sm d-none text-danger" id="mpinerror"></div>
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id = "" >Submit</button> -->
                </form>
            </div>
        </div>
    </div>
</div>
