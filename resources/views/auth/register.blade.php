<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Todo App</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="flex items-center justify-center min-h-screen bg-purple-600">

    <div class="bg-white rounded-2xl shadow-2xl w-[420px] p-8">
        <!-- Judul -->
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8 border-b-4 border-purple-500 inline-block pb-1">
            Register
        </h2>

        <!-- Form Register -->
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Username -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Username</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500">
                    <i class="fas fa-user text-gray-400 mr-3"></i>
                    <input type="text" name="name" placeholder="Enter your username" required
                        class="w-full outline-none border-none focus:ring-0 text-gray-700 placeholder-gray-400">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Email</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500">
                    <i class="fas fa-envelope text-gray-400 mr-3"></i>
                    <input type="email" name="email" placeholder="Enter your email" required
                        class="w-full outline-none border-none focus:ring-0 text-gray-700 placeholder-gray-400">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500">
                    <i class="fas fa-lock text-gray-400 mr-3"></i>
                    <input type="password" name="password" placeholder="Enter your password" required
                        class="w-full outline-none border-none focus:ring-0 text-gray-700 placeholder-gray-400">
                </div>
            </div>

            <!-- Tombol Register -->
            <button type="submit"
                class="w-full bg-purple-600 text-white font-semibold py-3 rounded-lg hover:bg-purple-700 transition">
                Register
            </button>

            <!-- Garis Pemisah -->
            <div class="relative flex items-center justify-center mt-4">
                <div class="w-full border-t border-gray-300"></div>
                <span class="absolute bg-white px-3 text-gray-500 text-sm">or</span>
            </div>

            <!-- Tombol Google -->
            <a href="{{ url('/auth/google') }}"
               class="flex items-center justify-center gap-3 w-full mt-3 border border-gray-300 py-3 rounded-lg hover:bg-gray-100 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google logo">
                <span class="text-gray-700 font-medium">Register with Google</span>
            </a>
        </form>

        <!-- Link ke Login -->
        <p class="text-sm text-gray-600 mt-6 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">
                Login now
            </a>
        </p>
    </div>

</body>
</html>
