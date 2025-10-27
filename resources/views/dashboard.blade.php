<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Tasks Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
<div class="flex flex-col h-screen">
    <div class="flex flex-1">
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">My Tasks</h2>
            </div>

            {{-- MENU --}}
            <nav class="flex-1 px-4 py-3 space-y-2">
                <button id="btn-daily" 
                        class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 font-medium transition">
                    🗓️ Daily
                </button>
                <button id="btn-important" 
                        class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 font-medium transition">
                    ⭐ Important
                </button>

                <hr class="my-3 border-gray-300">

                <div id="user-lists" class="space-y-2">
                    <a href="#" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100">
                        + New List
                    </a>
                </div>
            </nav>

            {{-- Avatar pakai komponen --}}
            <x-avatar />
        </aside>

        {{-- MAIN CONTENT --}}
        <main id="main-content" class="flex-1 p-8 overflow-y-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Welcome to Your Todo App</h1>
            <p class="text-gray-600 mb-6">“Your personal space to manage tasks efficiently.”</p>

            <div class="bg-white shadow rounded-xl p-6">
                <p class="text-gray-700">
                    Ini area utama dashboard kamu. Klik menu di kiri untuk membuka halaman Daily atau Important Tasks.
                </p>
            </div>
        </main>
    </div>

    {{-- Footer pakai komponen --}}
    <x-footer />
</div>

{{-- SCRIPT FETCH --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const main = document.getElementById('main-content');

   const loadContent = async (url) => {
    main.innerHTML = `
        <div class="flex justify-center items-center h-64">
            <div class="text-gray-500 animate-pulse">Loading...</div>
        </div>
    `;
    try {
        const res = await fetch(url, {
            credentials: 'same-origin', // kirim cookie login
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('Gagal memuat halaman');
        const html = await res.text();
        main.innerHTML = html;
    } catch (error) {
        main.innerHTML = `
            <div class="text-center text-red-500 mt-10">
                ⚠️ Gagal memuat halaman: ${error.message}
            </div>
        `;
    }
};

    document.getElementById('btn-daily').addEventListener('click', () => loadContent('{{ route("daily.index") }}'));
    document.getElementById('btn-important').addEventListener('click', () => loadContent('{{ route("important.index") }}'));
});
</script>
</body>
</html>
