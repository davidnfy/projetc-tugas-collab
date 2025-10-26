<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Todo App</title>
    @vite('resources/css/forgot-password.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="forgot-card">
    <h2>Forgot Password</h2>
    <p>Masukkan email Anda untuk menerima link reset password.</p>

    <form action="#" method="POST">
        @csrf
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn-primary">Send Reset Link</button>
    </form>

    <div class="back-login">
        Kembali ke <a href="{{ url('/login') }}">Login</a>
    </div>
</div>

</body>
</html>
