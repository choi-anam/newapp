<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            }
            .auth-container {
                width: 100%;
                max-width: 420px;
                padding: 1rem;
            }
            .auth-card {
                border: none;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                overflow: hidden;
            }
            .auth-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2rem 1rem;
                text-align: center;
            }
            .auth-header h1 {
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }
            .auth-header p {
                font-size: 0.95rem;
                opacity: 0.9;
                margin: 0;
            }
            .form-control {
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 0.75rem;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
                color: white;
            }
            .form-label {
                font-weight: 600;
                color: #495057;
                margin-bottom: 0.5rem;
                font-size: 0.95rem;
            }
            .auth-link {
                color: #667eea;
                text-decoration: none;
                font-weight: 600;
            }
            .auth-link:hover {
                color: #764ba2;
                text-decoration: underline;
            }
            .auth-divider {
                text-align: center;
                margin: 1.5rem 0;
                color: #999;
                font-size: 0.9rem;
            }
            .auth-footer {
                background-color: #f8f9fa;
                padding: 1.5rem;
                text-align: center;
                border-top: 1px solid #e9ecef;
            }
            .auth-footer p {
                margin: 0;
                color: #6c757d;
                font-size: 0.95rem;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="card auth-card">
                {{ $slot }}
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
