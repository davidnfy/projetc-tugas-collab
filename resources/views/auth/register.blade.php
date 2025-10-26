<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Todo App</title>

    {{-- Vite CSS --}}
    @vite('resources/css/app.css')

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-100 to-gray-200">

    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl w-[420px] p-8 border border-gray-200">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8 border-b-4 border-gray-600 inline-block pb-1">
            Register
        </h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Username --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-gray-500 bg-gray-50">
                    <i class="fas fa-user text-gray-400 mr-3"></i>
                    <input type="text" name="name" placeholder="Enter your username" required
                        class="w-full bg-transparent outline-none border-none focus:ring-0 text-gray-800 placeholder-gray-400">
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-gray-500 bg-gray-50">
                    <i class="fas fa-envelope text-gray-400 mr-3"></i>
                    <input type="email" name="email" placeholder="Enter your email" required
                        class="w-full bg-transparent outline-none border-none focus:ring-0 text-gray-800 placeholder-gray-400">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-gray-500 bg-gray-50">
                    <i class="fas fa-lock text-gray-400 mr-3"></i>
                    <input type="password" name="password" placeholder="Enter your password" required
                        class="w-full bg-transparent outline-none border-none focus:ring-0 text-gray-800 placeholder-gray-400">
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-gray-500 bg-gray-50">
                    <i class="fas fa-lock text-gray-400 mr-3"></i>
                    <input type="password" name="password_confirmation" placeholder="Confirm your password" required
                        class="w-full bg-transparent outline-none border-none focus:ring-0 text-gray-800 placeholder-gray-400">
                </div>
            </div>

            {{-- Button --}}
            <button type="submit"
                class="w-full bg-gray-800 text-white font-semibold py-3 rounded-lg hover:bg-gray-700 transition">
                Register
            </button>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center mt-4">
                <div class="w-full border-t border-gray-300"></div>
                <span class="absolute bg-white px-3 text-gray-500 text-sm">or</span>
            </div>

            {{-- Google Register --}}
            <a href="{{ url('/auth/google') }}"
               class="flex items-center justify-center gap-3 w-full mt-3 border border-gray-300 py-3 rounded-lg hover:bg-gray-100 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google logo">
                <span class="text-gray-700 font-medium">Register with Google</span>
            </a>
        </form>

        <p class="text-sm text-gray-600 mt-6 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-800 font-semibold hover:underline">
                Login now
            </a>
        </p>

    </div>

</body>
</html>
