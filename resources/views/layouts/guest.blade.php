<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ForSenses II') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- AdminLTE CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Custom Styles -->
        <style>
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                font-family: 'Figtree', sans-serif;
            }

            .login-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                overflow: hidden;
            }

            .login-header {
                background-color: #eaeaea;
                padding: 2rem;
                text-align: center;
                border-bottom: 1px solid #dee2e6;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .login-logo {
                max-width: 200px;
                max-height: 80px;
                margin: 0 auto 0 auto;
                display: block;
            }

            .login-title {
                color: #555355;
                font-size: 1.5rem;
                font-weight: 600;
                margin: 0;
            }

            .login-subtitle {
                color: #6c757d;
                font-size: 0.9rem;
                margin: 0.5rem 0 0 0;
            }

            .login-body {
                padding: 2.5rem;
            }

            .form-control {
                border: 1px solid #ced4da;
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: #555355;
                box-shadow: 0 0 0 0.2rem rgba(85, 83, 85, 0.25);
            }

            .form-label {
                color: #555355;
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .btn-login {
                background-color: #555355;
                border-color: #555355;
                color: #eaeaea;
                padding: 0.75rem 2rem;
                font-weight: 500;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }

            .btn-login:hover {
                background-color: #4a4848;
                border-color: #4a4848;
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(85, 83, 85, 0.3);
            }

            .btn-login:focus {
                box-shadow: 0 0 0 0.2rem rgba(85, 83, 85, 0.5);
            }

            .forgot-link {
                color: #555355;
                text-decoration: none;
                font-size: 0.9rem;
                transition: color 0.3s ease;
            }

            .forgot-link:hover {
                color: #4a4848;
                text-decoration: underline;
            }

            .alert {
                border-radius: 0.5rem;
                border: none;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .alert-danger {
                background-color: #f8d7da;
                color: #721c24;
            }

            .input-group-text {
                background-color: #f8f9fa;
                border: 1px solid #ced4da;
                border-right: none;
                color: #555355;
            }

            .form-control.with-icon {
                border-left: none;
            }

            @media (max-width: 576px) {
                .login-container {
                    margin: 1rem;
                    border-radius: 15px;
                }

                .login-header {
                    padding: 1.5rem;
                }

                .login-body {
                    padding: 2rem 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="d-flex justify-content-center align-items-center min-vh-100 p-3">
            <div class="login-container w-100" style="max-width: 450px;">
                <!-- Header -->
                <div class="login-header">
                    <img src="{{ asset('images/aikos_black.png') }}" alt="{{ config('app.name') }}" class="login-logo" onerror="this.style.display='none'">
                </div>

                <!-- Body -->
                <div class="login-body">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    </body>
</html>
