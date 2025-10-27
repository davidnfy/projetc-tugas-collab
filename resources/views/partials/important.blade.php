<div class="p-8 bg-gray-50 min-h-full pb-24 transition-opacity duration-200 ease-in-out">
    <h1 class="text-2xl font-bold mb-6">⭐ Important Tasks</h1>

    {{-- Form Tambah Task --}}
    <form id="important-form" action="{{ route('important.store') }}" method="POST" class="mb-6">
        @csrf
        <div class="flex items-center bg-[#fff9f2] border border-gray-200 rounded-lg shadow-sm px-4 py-3">
            <span class="text-yellow-500 mr-3 text-xl">+</span>
            <input 
                type="text" 
                name="title" 
                placeholder="Add an important task"
                class="flex-1 bg-transparent outline-none text-gray-700 placeholder-gray-500"
                required
            >
        </div>
    </form>

    {{-- Daftar Task --}}
    <div id="important-list" class="space-y-3">
        @forelse ($todos as $todo)
            <div class="bg-white p-4 rounded-lg shadow flex justify-between items-center hover:shadow-md transition">
                <div>
                    <h2 class="font-medium {{ $todo->is_completed ? 'line-through text-gray-400' : '' }}">
                        {{ $todo->title }}
                    </h2>
                </div>
                <div class="flex gap-3">
                    <form action="{{ route('important.toggle', $todo->id) }}" method="POST" class="toggle-form inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-green-600 hover:underline">
                            {{ $todo->is_completed ? 'Undo' : 'Done' }}
                        </button>
                    </form>
                    <form action="{{ route('important.destroy', $todo->id) }}" method="POST" class="delete-form inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center mt-6">Belum ada task</p>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('important-form');
    const list = document.getElementById('important-list');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Tambah Task tanpa reload
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
            });

            if (!res.ok) throw new Error('Gagal menambah task penting.');
            const html = await res.text();
            list.innerHTML = html;
            form.reset();
        } catch (err) {
            alert(err.message);
        }
    });

    // Toggle & Delete pakai event delegation
    list.addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target.closest('form');
        if (!form) return;

        try {
            const res = await fetch(form.action, {
                method: form.method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
            });

            if (!res.ok) throw new Error('Gagal memperbarui task penting.');
            const html = await res.text();
            list.innerHTML = html;
        } catch (err) {
            alert(err.message);
        }
    });
});
</script>
