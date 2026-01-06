@extends('layouts.website.app')

@section('content')

<style>

        .otp_box{

            display: flex;
            flex-wrap: nowrap;
            flex-direction: row;
            justify-content: flex-end;

        }

       #countdown {
            font-size: 14px;
            font-weight: 500;
            color: #343a40;
        }

</style>

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> Mobile No Verification </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> Verification </li>
    </ul>
    </div>
    </div>
    <div class="inner-shape">
    </div>
</div>

<div class="user-area pt-100 pb-70">
    <div class="container">
    <div class="row align-items-center">
    <div class="col-lg-6">
    <div class="user-img">
    <img src = "{{ asset('assets/images/user-img.jpg') }}" alt="Images">
    </div>
    </div>
    <div class="col-lg-6">
    <div class="user-form">
    <div class="contact-form text-c enter">
    <h2 class="text-center"> Verify Your OTP </h2>

        <form id = "VerifyForm" enctype = "multipart/form-data" > 

            @csrf

            <div class="row">

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="text" class="form-control" name = "mobile_no" id = "mobile_no" placeholder="User I.D." value="{{ session('otp_mobile') }}" readonly >
                </div>
            </div>

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="text" class="form-control" name = "otp" placeholder = "Enter OTP ( *OTP sent to the above number ) ">
                <span class = "otp_box"><div id="countdown" class="mt-2"></div>
                    <button type = "button" class=" btn otp_btn" id = "resendOtpBtn"> Resend Otp </button></span>
                </div>
            </div>

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="password" class="form-control" name = "password" placeholder = "Set New Password">
                </div>
            </div>

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="password" class="form-control" name = "mpin" placeholder = "Set New MPIN Password">
                </div>
            </div>

            <div class="col-lg-12 ">
                <button type = "submit" class="default-btn btn-bg-two submit-btn "> Verify </button>
            </div>

            <div id="progressBarContainer" class="progress" style="height: 30px; display: none;">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    0%
                </div>
            </div>
            <div class="alert alert-success mt-1" role="alert" style="display: none;" id="success-alert">
                <!-- Success message will be displayed here -->
            </div>

            <div class="alert alert-danger mt-1" role="alert" style="display: none;" id="error-alert">
                <!-- error message will be displayed here -->
            </div>

            </div>

        </form>

    </div>
    </div>
    </div>
    </div>
    </div>
</div>

@endsection

@section('javascript')

<script>

$(document).ready(function() {

    function updateProgressBar(percent) {
        $('#progressBar').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
    }

    $(document).ready(function() {

              var expiryTime = new Date('{{ session('otp_expires_at') }}').getTime();
            var currentTime = new Date().getTime();
            var twoMinutes = 2 * 60 * 1000; // 2 minutes in milliseconds
            var adjustedExpiryTime = expiryTime - twoMinutes;

            function formatTime(ms) {
                var minutes = Math.floor(ms / (60 * 1000));
                var seconds = Math.floor((ms % (60 * 1000)) / 1000);
                return minutes + "m " + seconds + "s";
            }

            function updateButtonState() {
                var timeRemaining = expiryTime - new Date().getTime();
                if (timeRemaining <= twoMinutes) {
                    $('#resendOtpBtn').prop('disabled', false);
                } else {
                    $('#resendOtpBtn').prop('disabled', false);
                }
                if (timeRemaining <= 0) {
                    $('#resendOtpBtn').prop('disabled', false);
                }
                return timeRemaining;
            }

            function updateCountdown() {
                var timeRemaining = updateButtonState();
                $('#countdown').text('Resend OTP in : ' + formatTime(timeRemaining));
                if (timeRemaining > 0) {
                    setTimeout(updateCountdown, 1000); // Update countdown every second
                } else {
                    $('#countdown').text('');
                }
            }

            // Initial call to start the countdown
            updateCountdown();
            $('#resendOtpBtn').on('click', function() {
                var mobile_no = $('#mobile_no').val() ;
                $.ajax({
                    url: '/resend_otp',
                    method: 'POST',
                    data: {
                        mobile_no: mobile_no, // Replace with the actual user ID
                        _token: '{{ csrf_token() }}' // For Laravel CSRF protection
                    },
                    success: function(response) {
                        if(response.errors) {
                            $('#error-alert').text(response.errors).show() ;
                            } else {
                            var sucsMsg = `${response.success}`; 
                            $('#success-alert').text(response.success).show() ;
                            expiryTime = new Date().getTime() + 3 * 60 * 1000;
                            updateCountdown();
                            }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while resending the OTP.');
                    }
                });
            });
    });

    $('#VerifyForm').submit(function(e) {

        e.preventDefault() ;

        $('#progressBarContainer').show() ;
        updateProgressBar(0) ; // Reset progress bar to 0%

        // Disable the submit button to prevent double submission
        $('.submit-btn').prop('disabled', true);

        var formData = new FormData(this) ;

        $.ajax({
            url: '{{ url("verification") }}', // Your form action URL
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                // Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        updateProgressBar(Math.round(percentComplete * 100));
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                updateProgressBar(100); // Ensure progress bar is full on success

                if(response.errors) {
                    $('#error-alert').text(response.errors).show() ;

                } else {
                    var sucsMsg = `${response.success}`; 
                    $('#success-alert').text(response.success).show() ;
                    var newWindow = window.open('{{ url("/login") }}', '_self');

                }
            },
            error: function(response) {
                if (response.status === 422) {
                    $('.invalid-feedback').remove();
                    $('.is-invalid').removeClass('is-invalid').css('border-color', '');
                    $('.is-valid').removeClass('is-valid').css('border-color', '');

                    var errors = response.responseJSON.errors;
                    for (var field in errors) {
                        var input = $('[name="' + field + '"]');
                        input.addClass('is-invalid').css('border-color', 'red');
                        input.after('<div class="invalid-feedback">' + errors[field][0] + '</div>');
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            },
            complete: function() {
                // Re-enable the submit button after the request is complete
                $('.submit-btn').prop('disabled', false);
                
                // Hide the progress bar after a short delay
                setTimeout(function() {
                    $('#progressBarContainer').hide();
                    updateProgressBar(0); // Reset progress bar to 0% for future requests
                }, 1000);
            }
        });
    });
});

</script>

@endsection