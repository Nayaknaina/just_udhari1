@extends('layouts.website.app')

@section('content')

<div class="inner-banner">
    <div class="container">
    <div class="inner-title text-center">
    <h3> Sign up </h3>
    <ul>
    <li>
    <a href="{{ url('/') }}">Home</a>
    </li>
    <li>
    <i class="bx bx-chevrons-right"></i>
    </li>
    <li> Sign up </li>
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
    <h2> Sign up Here </h2>

        <form id = "RegisterForm" enctype = "multipart/form-data" > 
 
            @csrf

            <div class="row">

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="text" class="form-control" name = "shop_name" data-error="Please enter your Shop Name" placeholder="Enter Shop Name">
                </div>
            </div>

            <div class="col-lg-6 ">
                <div class="form-group">
                <input type="text" class="form-control" name = "name" data-error="Please enter your Name" placeholder="Enter Name">
                </div>
            </div>

            <div class="col-lg-6 ">
                <div class="form-group">
                <input type="text" class="form-control"  name = "mobile_no"  data-error="Please enter your Mobile Numer" placeholder="Enter Whatsapp No" oninput="digitonly(event,10);">
                </div>
            </div>

            <div class="col-lg-12 ">
                <div class="form-group">
                <input type="text" class="form-control" name = "address" data-error="Please enter your Username or Email" placeholder="Enter Address">
                </div>
            </div>

            <div class="col-6">
            <div class="form-group">
            <select name="state" class="form-control select2" id = "state" >
                <option value="">Select State</option>
                @foreach (states() as $state )
                <option value = "{{ $state->code }}"> {{ $state->name }} </option>
                @endforeach
            </select>
            </div>
            </div>

            <div class="col-6">
            <div class="form-group">
            <select name="district" class="form-control" id = "district"  >
                <option value="">Select District</option>
            </select>
            </div>
            </div>

            <div class="col-lg-12 ">
                <button type = "submit" class="default-btn btn-bg-two submit-btn "> Sign up </button>
            </div>

            <div id="progressBarContainer" class="progress mt-1" style="height: 30px; display: none;">
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

            <div class="col-12">
            <p class="account-desc"> Already have an account? <a href="{{ url('login') }}"> Log In  Now</a>
            </p>
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

<script type="text/javascript">

    $(document).ready(function() {
        $('#state').change(function() {
            var state = $(this).val();
            if (state) {
                $.ajax({
                    url: '/get-districts',
                    type: 'GET',
                    data: { state: state },
                    success: function(response) {
                        $('#district').empty();
                        $('#district').append('<option value="">Select District</option>');
                        $.each(response, function(key, district) {
                            $('#district').append('<option value="' + district.code + '">' + district.name + '</option>');
                        });
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#district').empty();
                $('#district').append('<option value="">Select District</option>');
            }
        });
    });

</script>

<script>
$(document).ready(function() {

    // Define the updateProgressBar function
    function updateProgressBar(percent) {
        $('#progressBar').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
    }

    $('#RegisterForm').submit(function(e) {
        e.preventDefault();
        // Show the progress bar
        $('#progressBarContainer').show();
        updateProgressBar(0); // Reset progress bar to 0%

        // Disable the submit button to prevent double submission
        $('.submit-btn').prop('disabled', true);

        var formData = new FormData(this);

        $.ajax({
            url: '{{ url("register") }}', // Your form action URL
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
                    var newWindow = window.open('{{ url("/verification") }}', '_self');

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