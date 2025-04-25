<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color:rgba(6, 6, 6, 0.97);
            --secondary-color:rgba(15, 15, 15, 0.9);
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
        }
        
        body {
            background-color: var(--light-color);
            height: 100vh;
            display: flex;
            align-items: center;
            transition: backdrop-filter 0.3s ease;
        }
        
        .blur-active {
            position: relative;
        }
        
        .blur-active::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            backdrop-filter: blur(5px);
            z-index: 10;
            pointer-events: none;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        
        /* Rest of your existing styles remain the same */
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo img {
            height: 140px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        /* SweetAlert customizations */
        .swal2-container {
            z-index: 2000 !important;
        }
        
        .swal2-popup {
            border-radius: 10px !important;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2) !important;
        }
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .loader-container.active {
            opacity: 1;
            visibility: visible;
        }
        
        .loader {
            width: 80px;
            height: 80px;
            position: relative;
        }
        
        .loader-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 8px solid transparent;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .loader-circle:nth-child(2) {
            border: 8px solid transparent;
            border-bottom-color: var(--secondary-color);
            animation: spinReverse 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes spinReverse {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }
        
        .loader-text {
            margin-top: 20px;
            font-size: 1.2rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .progress-bar {
            width: 200px;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 15px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
<div class="loader-container" id="loader">
        <div class="loader">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
        </div>
        <div class="loader-text">Loading Dashboard</div>
        <div class="progress-bar">
            <div class="progress-bar-fill" id="progressBar"></div>
        </div>
    </div>

    <div class="container">
        <div class="login-container">
            <div class="login-logo">
               <h3><b>Finance App</b></h3>
            </div>
            <h4 style="text-align: center;">Login Area</h4>
            <form id="loginForm" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="username" placeholder="Enter username" required>
                        <div class="invalid-feedback">
                            Please enter a valid username.
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                        <div class="invalid-feedback">
                            Please Enter a valid password.
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
                <div class="text-center mt-3">
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        const body = document.body;

        // Form validation
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();

            this.classList.add('was-validated');

            if (this.checkValidity()) {
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Authenticating...';
                submitButton.disabled = true;

                // Add blur to background
                body.classList.add('blur-active');

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                // Send AJAX request
                $.ajax({
                    url: 'login_ajax.php',
                    type: 'POST',
                    data: { username: username, password: password, login:'login' },
                    success: function (response) {
                        console.log(response);
                        if (response.trim() === 'success') {
                            window.location.href = 'dashboard.php';
                        } else {
                            Swal.fire({
                                title: 'Login Failed',
                                text: 'Invalid username or password. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Try Again',
                                confirmButtonColor: "#000",
                                willClose: () => {
                                    body.classList.remove('blur-active');
                                    submitButton.innerHTML = originalText;
                                    submitButton.disabled = false;
                                    //document.getElementById('password').value = '';
                                    document.getElementById('password').focus();
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Server Error',
                            text: 'Could not connect to the server. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: "#000",
                            willClose: () => {
                                body.classList.remove('blur-active');
                                submitButton.innerHTML = originalText;
                                submitButton.disabled = false;
                            }
                        });
                    }
                });

            } else {
                // Add blur to background
                body.classList.add('blur-active');

                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please fill in all required fields correctly.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: "#000",
                    willClose: () => {
                        body.classList.remove('blur-active');
                        const firstInvalidField = document.querySelector(':invalid');
                        if (firstInvalidField) {
                            firstInvalidField.focus();
                        }
                    }
                });
            }
        });

        // Remove validation class when user starts typing
        document.getElementById('username').addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });

        document.getElementById('password').addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    });
</script>

</body>
</html>