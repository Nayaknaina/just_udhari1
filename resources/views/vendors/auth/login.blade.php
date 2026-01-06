
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> Admin Login </title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet" href = "{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href = "{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href = "{{ asset('theme/dist/css/adminlte.min.css?v=3.2.0') }}">

</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="card card-outline card-primary">

            <div class="card-header text-center">
            <h1 class="h1"><b>JUST </b>UDHARI</h1>
            </div>

            <div class="card-body">

                <div id="error-alert" class="alert alert-danger mt-2" style="display: none;"></div>
                <div id="success-alert" class="alert alert-success mt-2" style="display: none;"></div>
           
                <form id = "loginForm" method="post">

                    @csrf

                    <div class="input-group mb-3">
                        <input type = "number" name = "mobile_no" class="form-control" placeholder = "User Id">
                        <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                        </div>
                        </div>
                    </div>

                    <div id="mobile_no"></div>

                    <div class="input-group mb-3">
                        <input type="password" name = "password"  class="form-control" placeholder="Password">
                        <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                        </div>
                        </div>
                    </div>

                    <div id="password"></div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

<script src = "{{ asset('theme/plugins/jquery/jquery.min.js')}}"></script>
<script src = "{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src = "{{ asset('theme/dist/js/adminlte.min.js?v=3.2.0')}}"></script>

<script>

    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("ss_manager.login") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#success-alert').text(response.success).show();
                    $('#error-alert').hide();
                    window.location.href = '/ss_manager'; // Redirect to dashboard or intended URL
                },
                error: function(response) {

                    if (response.status === 422) {
                        var errors = response.responseJSON.errors ;
                        var errorHtml = '<ul>' ;
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value[0] + '</li>' ;
                        });
                        errorHtml += '</ul>' ;
                        $('#error-alert').html(errorHtml).show() ;
                    }
                    $('#success-alert').hide() ;

                }
            });
        });
    });

</script>

</body>
</html>
