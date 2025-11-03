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
        <x-sidebar />

        {{-- MAIN CONTENT --}}
        <main id="main-content" class="flex-1 p-8 overflow-y-auto transition-all duration-300">
            <div id="content">
                <div id="default-dashboard">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-2">Welcome to Your Todo App</h1>
                    <p class="text-gray-600 mb-6">“Your personal space to manage tasks efficiently.”</p>

                    <div class="bg-white shadow rounded-xl p-6">
                        <p class="text-gray-700">
                            Ini area utama dashboard kamu. Klik menu di kiri untuk membuka halaman Daily atau Important Tasks.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <x-footer />
</div>

{{-- === DYNAMIC PAGE LOADER === --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const main = document.getElementById('main-content');

    const loadContent = async (url, push = true) => {
        main.innerHTML = `
            <div class="flex justify-center items-center h-64">
                <div class="text-gray-500 animate-pulse">Loading...</div>
            </div>
        `;

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            main.innerHTML = html;

            if (push) history.pushState(null, '', url);

            // panggil JS sesuai halaman
            if (url.includes('/daily')) import('/js/daily.js').then(m => m.initDailyPage());
            if (url.includes('/important')) import('/js/important.js').then(m => m.initImportantPage());
            if (url.includes('/user')) import('/js/user.js').then(m => m.initUserPage());
        } catch (error) {
            main.innerHTML = `<div class="text-center text-red-500 mt-10">⚠️ ${error.message}</div>`;
        }
    };

    document.getElementById('btn-daily')?.addEventListener('click', e => {
        e.preventDefault(); loadContent('{{ route("daily.index") }}');
    });
    document.getElementById('btn-important')?.addEventListener('click', e => {
        e.preventDefault(); loadContent('{{ route("important.index") }}');
    });
    document.getElementById('btn-user')?.addEventListener('click', e => {
        e.preventDefault(); loadContent('{{ route("user.index") }}');
    });

    window.addEventListener('popstate', () => loadContent(location.pathname, false));
});
</script>
</body>
</html>
