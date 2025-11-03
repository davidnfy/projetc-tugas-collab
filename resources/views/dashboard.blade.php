<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Tasks Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- macOS style toast --- */
        .toast {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 9999;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.75);
            color: #111;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: .75rem;
            opacity: 0;
            transform: translateY(-15px);
            transition: all .3s ease;
            font-size: 0.95rem;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast-icon { font-size: 1.25rem; }

        /* --- macOS style confirm dialog --- */
        .confirm-dialog {
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }
        .confirm-dialog.show { opacity: 1; pointer-events: all; }

        .confirm-box {
            background: rgba(255,255,255,0.9);
            border-radius: 1rem;
            padding: 1.5rem;
            width: 320px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .confirm-box button {
            padding: .5rem 1.25rem;
            border-radius: .75rem;
            margin: 0 .25rem;
            transition: background .2s;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">
<div class="flex flex-col h-screen">
    <div class="flex flex-1">
        {{-- SIDEBAR --}}
        <x-sidebar />

        <main id="main-content" class="flex-1 p-8 overflow-y-auto transition-all duration-300">
            <div id="content">
                @if(isset($page))
                    @includeIf('partials.' . $page)
                @else
                    <div id="default-dashboard">
                        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Welcome to Your Todo App</h1>
                        <p class="text-gray-600 mb-6">“Your personal space to manage tasks efficiently.”</p>

                        <div class="bg-white shadow rounded-xl p-6">
                            <p class="text-gray-700">
                                Ini area utama dashboard kamu. Klik menu di kiri untuk membuka halaman Daily atau Important Tasks.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    {{-- FOOTER --}}
    <x-footer />
</div>

{{-- === TOAST CONTAINER === --}}
<div id="toast" class="toast hidden"></div>

{{-- === CONFIRM DIALOG === --}}
<div id="confirm-dialog" class="confirm-dialog">
    <div class="confirm-box">
        <h2 class="text-lg font-semibold mb-3">Yakin ingin menghapus?</h2>
        <p class="text-gray-600 mb-4 text-sm">Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex justify-center">
            <button id="confirm-yes" class="bg-red-500 hover:bg-red-600 text-white">Hapus</button>
            <button id="confirm-no" class="bg-gray-200 hover:bg-gray-300">Batal</button>
        </div>
    </div>
</div>

{{-- === JS === --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const main = document.getElementById('main-content');
    const toast = document.getElementById('toast');
    const confirmDialog = document.getElementById('confirm-dialog');
    const btnYes = document.getElementById('confirm-yes');
    const btnNo = document.getElementById('confirm-no');

    // ===== Toast notification =====
    window.showToast = (message, type = 'info') => {
        const icons = { success: '✅', error: '❌', info: '💡' };
        toast.innerHTML = `<span class="toast-icon">${icons[type] || '💬'}</span>${message}`;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3000);
    };

    // ===== Confirm dialog =====
    window.confirmDelete = () => new Promise(resolve => {
        confirmDialog.classList.add('show');
        const close = (result) => {
            confirmDialog.classList.remove('show');
            setTimeout(() => resolve(result), 200);
        };
        btnYes.onclick = () => close(true);
        btnNo.onclick = () => close(false);
    });

    // ===== Dynamic content loader =====
    const loadContent = async (url, push = true) => {
        const content = document.getElementById('content');
        content.innerHTML = `
            <div class="flex justify-center items-center h-64">
                <div class="text-gray-500 animate-pulse">Loading...</div>
            </div>
        `;
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            const temp = document.createElement('div');
            temp.innerHTML = html;
            const newContent = temp.querySelector('#content')?.innerHTML || html;
            content.innerHTML = newContent;
            if (push) history.pushState(null, '', url);
            if (url.includes('/daily')) import('/js/daily.js').then(m => m.initDailyPage?.());
            if (url.includes('/important')) import('/js/important.js').then(m => m.initImportantPage?.());
        } catch (error) {
            content.innerHTML = `<div class="text-center text-red-500 mt-10">⚠️ ${error.message}</div>`;
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

    // ✅ Flash Toasts dari Laravel session
    @if(session('success'))
        showToast("{{ session('success') }}", "success");
    @endif
    @if(session('updated'))
        showToast("{{ session('updated') }}", "info");
    @endif
    @if(session('deleted'))
        showToast("{{ session('deleted') }}", "error");
    @endif
    @if(session('error'))
        showToast("{{ session('error') }}", "error");
    @endif
});
</script>
</body>
</html>
