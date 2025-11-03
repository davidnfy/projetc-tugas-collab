<div class="flex gap-8">
    {{-- Sidebar kategori user --}}
    <div class="w-1/4 bg-white p-4 shadow rounded-lg">
        <h2 class="text-lg font-semibold mb-3">📂 Kategori</h2>

        <ul id="category-list" class="space-y-2 mb-4">
            @foreach($categories as $cat)
                <li>
                    <button data-id="{{ $cat->id }}" class="category-btn w-full text-left px-3 py-2 rounded-md hover:bg-gray-100">
                        {{ $cat->name }}
                    </button>
                </li>
            @endforeach
        </ul>

        <form id="category-form" class="flex gap-2">
            <input type="text" id="category-name" placeholder="Tambah kategori..."
                   class="flex-1 px-3 py-2 border rounded-lg focus:ring focus:ring-indigo-300">
            <button type="submit" class="bg-indigo-600 text-white px-3 rounded-lg hover:bg-indigo-700">+</button>
        </form>
    </div>

    {{-- Daftar todo di dalam kategori --}}
    <div class="flex-1 bg-white p-6 shadow rounded-lg">
        <h2 id="category-title" class="text-2xl font-semibold mb-4 text-gray-800">Pilih kategori di kiri</h2>

        <form id="todo-form" class="flex gap-2 mb-4 hidden">
            <input type="text" id="todo-name" placeholder="Tambah list baru..."
                   class="flex-1 px-3 py-2 border rounded-lg focus:ring focus:ring-indigo-300">
            <button type="submit" class="bg-indigo-600 text-white px-4 rounded-lg hover:bg-indigo-700">Tambah</button>
        </form>

        <ul id="todo-list" class="space-y-3"></ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('todo-list');
    const title = document.getElementById('category-title');
    const todoForm = document.getElementById('todo-form');
    const todoInput = document.getElementById('todo-name');

    let currentCategory = null;

    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            currentCategory = btn.dataset.id;
            title.textContent = `📋 ${btn.textContent}`;
            todoForm.classList.remove('hidden');

            const res = await fetch(`/dashboard/user-todos/${currentCategory}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await res.text();
            list.innerHTML = html;
        });
    });

    todoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!currentCategory) return;
        const name = todoInput.value.trim();
        if (!name) return;

        const res = await fetch(`/dashboard/user-todos/${currentCategory}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ name }),
        });
        const html = await res.text();
        list.innerHTML = html;
        todoInput.value = '';
    });
});
</script>
