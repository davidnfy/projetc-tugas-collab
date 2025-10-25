<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .auth-container {
            width: 400px;
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-google {
            background-color: #4285F4;
            color: white;
        }
        .btn-google:hover {
            background-color: #357ae8;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>
