<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Todo App</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="flex items-center justify-center min-h-screen bg-purple-600">

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden w-[400px] p-10">

<h2 class="text-3xl font-bold text-center text-gray-800 mb-8 border-b-4 border-purple-500 inline-block pb-1">
    Login
</h2>

        

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Email</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500">
                    <i class="fas fa-envelope text-gray-400 mr-3"></i>
                    <input type="email" name="email" placeholder="Enter your email" required
                           class="w-full outline-none border-none focus:ring-0 text-gray-700 placeholder-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500">
                    <i class="fas fa-lock text-gray-400 mr-3"></i>
                    <input type="password" name="password" placeholder="Enter your password" required
                           class="w-full outline-none border-none focus:ring-0 text-gray-700 placeholder-gray-400">
                </div>
                <a href="#" class="text-sm text-purple-600 hover:underline mt-2 inline-block">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full bg-purple-600 text-white font-semibold py-3 rounded-lg hover:bg-purple-700 transition">
                Login
            </button>

            <div class="relative flex items-center justify-center mt-4">
                <div class="w-full border-t border-gray-300"></div>
                <span class="absolute bg-white px-3 text-gray-500 text-sm">or</span>
            </div>

            <a href="{{ url('/auth/google') }}"
               class="flex items-center justify-center gap-3 w-full mt-3 border border-gray-300 py-3 rounded-lg hover:bg-gray-100 transition">
                <span class="text-gray-700 font-medium">Login with Google</span>
            </a>
        </form>

        <p class="text-sm text-gray-600 mt-6 text-center">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-purple-600 font-semibold hover:underline">
                Sign up now
            </a>
        </p>

    </div>

</body>
</html>
