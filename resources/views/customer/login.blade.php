{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>User Login</title>
</head>

<body>


    <section class="vh-100">
        <div class="container py-5 h-100">
          <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-md-8 col-lg-7 col-xl-6">
              <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
              <form>
                <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" id="form1Example13" class="form-control form-control-lg" />
                  <label class="form-label" for="form1Example13">Phone</label>
                </div>

                <div class="d-flex justify-content-around align-items-center mb-4">
                  <a href="#!">Forgot password?</a>
                </div>

                <!-- Submit button -->
                <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block">Sign in</button>

 <button onclick="sendOTP()" class="w-full bg-blue-500 text-white rounded-md py-2">
                    Send OTP
                </button>



              </form>
            </div>
          </div>
        </div>
      </section>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html> --}}


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Phone Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
 <!-- Option 1: Bootstrap Bundle with Popper -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
</head>

<body class="bg-gray-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form id="loginForm">
                            @csrf
                            <!-- Phone Input -->
                            <div class="form-group mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                                <span class="text-danger phone-error"></span>
                            </div>

                            <!-- OTP Input (initially hidden) -->
                            <div class="form-group mb-3" id="otpSection" style="display: none;">
                                <label for="otp">Enter OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp">
                                <span class="text-danger otp-error"></span>
                                <small class="text-muted">OTP will expire in 5 minutes</small>
                            </div>

                            <!-- Buttons -->
                            <button type="button" id="sendOtpBtn" class="btn btn-primary">Send OTP</button>
                            <button type="button" id="verifyOtpBtn" class="btn btn-success" style="display: none;">Verify & Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Send OTP
        $('#sendOtpBtn').click(function() {
            var phone = $('#phone').val();

            $.ajax({
                url: "{{ route('user.send.otp') }}",
                type: 'POST',
                data: {
                    phone: phone
                },
                success: function(response) {
                    if(response.status) {
                        // Show OTP input field
                        $('#otpSection').show();
                        $('#verifyOtpBtn').show();
                        $('#sendOtpBtn').text('Resend OTP');

                        // Show success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;

                    // Clear previous errors
                    $('.text-danger').text('');

                    // Display errors
                    if(errors) {
                        $.each(errors, function(key, value) {
                            $('.'+key+'-error').text(value[0]);
                        });
                    }

                    // Show error message using SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Something went wrong!'
                    });
                }
            });
        });

        // Verify OTP
        $('#verifyOtpBtn').click(function() {
            var phone = $('#phone').val();
            var otp = $('#otp').val();

            $.ajax({
                url: "{{ route('user.verify.otp') }}",
                type: 'POST',
                data: {
                    phone: phone,
                    otp: otp
                },
                success: function(response) {
                    if(response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;

                    // Clear previous errors
                    $('.text-danger').text('');

                    // Display errors
                    if(errors) {
                        $.each(errors, function(key, value) {
                            $('.'+key+'-error').text(value[0]);
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Invalid OTP!'
                    });
                }
            });
        });
    });
    </script>

</body>

</html>
