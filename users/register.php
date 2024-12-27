<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            width: 58.33%; /* 7/12 column size */
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .register-container h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .register-button {
            background-color: #4CAF50;
            color: white;
        }
        .register-button:disabled {
            background-color: grey;
        }
        .captcha {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }
        .captcha img {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2>Register</h2>
        <form id="registerForm" method="POST" action="process_register.php" enctype="multipart/form-data">
            <!-- Suffix, Firstname, Middlename, Lastname -->
            <div class="row">
                <div class="col-md-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="col-md-3">
                    <label for="middlename" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middlename" name="middlename">
                </div>
                <div class="col-md-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
            </div>

            <!-- Email, Mobile Number -->
            <div class="row">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <small id="emailError" class="text-danger"></small>
                </div>
            </div>

            <!-- Password and Confirm Password -->
            <div class="row">
                <div class="col-md-6">
                    <label for="password" class="form-label">Create Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    <small id="passwordError" class="text-danger"></small>
                </div>
            </div>
            <button type="submit" class="btn register-button w-100" id="registerBtn" disabled>Register</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    // Password Match Verification
    $('#password, #confirmPassword').on('keyup', function () {
        if ($('#password').val() === $('#confirmPassword').val()) {
            $('#passwordError').text('');
            $('#registerBtn').prop('disabled', false);

        } else {
            $('#passwordError').text('Passwords do not match');
            $('#registerBtn').prop('disabled', true);
        }
    });

    // Email Validation
    $('#email').on('input', function() {
        const email = $(this).val();
        $.ajax({
            url: 'reg_validate_email.php',
            type: 'POST',
            data: { email: email },
            success: function(response) {
                if (response === 'invalid') {
                    $('#emailError').text('Email already exists');
                    $('#registerBtn').prop('disabled', true);
                } else {
                    $('#emailError').text('');
                    $('#registerBtn').prop('disabled', false);
                }
            }
        });
    });
</script>

</body>
</html>
