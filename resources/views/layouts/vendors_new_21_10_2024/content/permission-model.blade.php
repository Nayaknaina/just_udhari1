
    <div class="modal fade" id="enquiryPermission" tabindex="-1" role="dialog" aria-labelledby="enquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('permission-enquiry') }}" method="POST" id = "PermissionForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="enquiryModalLabel">Permission Enquiry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You do not have permission to access this module. Would you like to send an enquiry to request access?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="sendEnquiryBtn">Send Enquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
2