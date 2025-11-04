<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Inline style (khusus halaman auth) -->
    <style>
        body.auth {
            background-color: #6B46C1;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
    @php
        $bodyClass = trim($__env->yieldContent('body-class'));
    @endphp

    @if ($bodyClass === 'auth')
        <div class="auth-container">
            @yield('content')
        </div>
    @else
        @yield('content')
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/js/app.js'])

    <!-- SweetAlert Flash -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('updated'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Diperbarui!',
                text: '{{ session('updated') }}',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('deleted'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: '{{ session('deleted') }}',
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    @endif
</body>
</html>
