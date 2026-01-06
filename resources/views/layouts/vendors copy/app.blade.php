<!DOCTYPE html>
<html lang="zxx">

<head>

    @include('layouts.vendors.css')

</head>

<body>

<div class="loader-wrapper">
    <div class="loader"></div>
</div>

<div class="tap-top"><i data-feather="chevrons-up"></i></div>

<div class="page-wrapper" id="pageWrapper">

    @include('layouts.vendors.header')

<div class="page-body-wrapper">

        @include('layouts.vendors.sidebar')

<div class="page-body">

    <div class="container-fluid"></div>

        <div id = "loader" class = "lds-dual-ring hidden overlay"></div>

            @if (!empty(session('subscription_expire')))
                <div class="expiry alert alert-danger" role="alert">
                    {{ session('subscription_expire') }}
                </div>
            @endif

            @yield('content')

</div>

        @include('layouts.vendors.footer')

        @include('layouts.vendors.js')

            @yield('javascript')

    <div id="sidebar-overlay"></div>

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

</div>
</div>

        @if (!empty(session('permission_denied')))

            <script type="text/javascript">

                $(window).on('load', function() {
                    $('#enquiryPermission').modal('show');
                });

            $(document).ready(function() {
                $('#PermissionForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission
                var formAction = $(this).attr('action') ;
                var formData = new FormData(this) ;
                $.ajax({
                    url: formAction,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // $('.btn').prop("disabled", true) ;
                        // $('#loader').removeClass('hidden') ;
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        location.reload() ;
                    },
                    error: function(response) {
                    if (response.status === 422) {
                        var errors = response.responseJSON.errors;
                        $('input').removeClass('is-invalid');
                        $('.btn-outline-danger').prop("disabled", false);
                        $('.btn').prop("disabled", false);
                        $('#loader').addClass('hidden');
                        $.each(errors, function(field, messages) {
                        var $field = $('[name="' + field + '"]');
                        toastr.error(messages[0]) ;
                        $field.addClass('is-invalid') ;
                        });
                    } else {
                        console.log(response.responseText);
                    }
                    }
                    });
                });
            });

            </script>

        @endif

</body>
</html>
