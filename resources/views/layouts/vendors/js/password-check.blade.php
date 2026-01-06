
<script>

    $(document).ready(function() {

        $('#passwordForm').on('submit', function(e) {

            e.preventDefault();

            var password = $('#password').val();
            var intendedUrl = $('#intended_url').val();
            var token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('check.password') }}",
                method: 'POST',
                data: {
                    _token: token,
                    password: password,
                    intended_url: intendedUrl
                },
                success: function(response) {
                    if (response.success) {

                        window.location.href = intendedUrl ;

                    } else {
                        // Password is incorrect, show error message
                        $('#passwordError').text(response.message).removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    // Handle any errors that occur during the request
                    $('#passwordError').text('An error occurred. Please try again.').removeClass('d-none');
                }
            });
        });

        $('#deleteForm').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize(); // Serialize the form data

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#confirmDeleteModal').modal('hide') ;
                    success_sweettoatr('Deleted Successfully') ;
                    setTimeout(function() {
                        window.location.reload() ;
                    }, 2000);
                },
                error: function(xhr) {
                    // Handle errors (e.g., incorrect password)
                    var errors = xhr.responseJSON.errors;
                    if (errors && errors.password) {
                        toastr.error(errors.password[0]) ;
                    }
                }
            });
        });

    });

</script>
