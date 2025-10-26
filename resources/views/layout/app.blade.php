<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    <!-- CSRF (berguna jika nanti pakai form/ajax) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Project CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <!-- Inline styles khusus untuk halaman auth (hanya berlaku bila body-class = "auth") -->
    <style>
        body.auth {
            background-color: #6B46C1; /* ungu hanya untuk auth */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        body.auth .auth-container {
            width: 400px;
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        body.auth .btn-primary {
            background-color: #6B46C1;
            border-color: #6B46C1;
            color: #fff;
        }
        body.auth .btn-primary:hover {
            background-color: #805AD5;
            border-color: #805AD5;
        }
        body.auth .btn-google {
            background-color: #4285F4;
            color: white;
        }
        body.auth .btn-google:hover {
            background-color: #357ae8;
        }
    </style>
</head>
<body class="@yield('body-class')">

    {{-- 
        Logika tampilan:
        - Jika halaman mengatur body-class = "auth" -> tampilkan wrapper .auth-container (untuk form login/register)
        - Jika bukan ("dashboard" atau kosong) -> tampilkan full content (untuk halaman dashboard)
    --}}
    @php
        // Ambil isi dari section 'body-class' (trim untuk mencegah whitespace)
        $bodyClass = trim($__env->yieldContent('body-class'));
    @endphp

    @if($bodyClass === 'auth')
        <div class="auth-container">
            @yield('content')
        </div>
    @else
        {{-- Full layout (mis. dashboard) --}}
        @yield('content')
    @endif

    <!-- Optional: bootstrap bundle JS (jika butuh komponen bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
