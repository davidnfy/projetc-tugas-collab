<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    {{-- Import Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex flex-col h-screen">

        <div class="flex flex-1">

            {{-- Sidebar --}}
            <aside class="w-64 bg-white shadow-md flex flex-col">
                {{-- Header --}}
                <div class="px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-800">My Tasks</h2>
                </div>

                {{-- Default List --}}
                <nav class="flex-1 px-4 py-3 space-y-2">
                    <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                        🗓️ Daily
                    </a>
                    <a href="#" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                        ⭐ Important
                    </a>

                    {{-- Garis pemisah --}}
                    <hr class="my-3 border-gray-300">

                    {{-- List Tambahan User --}}
                    <div id="user-lists" class="space-y-2">
                        <a href="#" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100">
                            + New List
                        </a>
                    </div>
                </nav>
                
                <div class="border-t px-6 py-4 flex items-center space-x-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-full" alt="avatar">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->nama ?? Auth::user()->name }}</p>
                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:underline">Logout</button>
                        </form>
                    </div>
                </div>
            </aside>


            <main class="flex-1 p-8 overflow-y-auto">
                <h1 class="text-2xl font-semibold text-gray-800 mb-2">Welcome to Your Todo App</h1>
                <p class="text-gray-600 mb-6">“Your personal space to manage tasks efficiently.”</p>

                <div class="bg-white shadow rounded-xl p-6">
                    <p class="text-gray-700">
                        Ini area utama dashboard kamu. Di sini nanti bisa ditampilkan daftar tugas, grafik, atau data penting lainnya.
                    </p>
                </div>
            </main>
        </div>


        <footer class="h-10 bg-gray-800"></footer>
    </div>
</body>
</html>
