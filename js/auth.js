$(document).ready(function () {
    // Login Form Submission
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'actions/login.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Register Form Submission
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'actions/register.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Registration successful! Please login.');
                    $('#pills-login-tab').tab('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Forgot Password Form Submission
    $('#forgotPasswordForm').on('submit', function (e) {
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.text();
        $btn.prop('disabled', true).text('Sending...');

        $.ajax({
            type: 'POST',
            url: 'actions/forgot_password.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#forgotPasswordModal').modal('hide');
                    $('#loginModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            },
            complete: function () {
                $btn.prop('disabled', false).text(originalText);
            }
        });
    });
});
