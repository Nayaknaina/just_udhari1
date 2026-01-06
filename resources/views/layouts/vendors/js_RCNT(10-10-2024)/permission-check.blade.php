
@if (!empty(session('permission_denied')))

    <script type="text/javascript">

            $(window).on('load', function() {

                $('#enquiryPermission').modal('show') ;

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
