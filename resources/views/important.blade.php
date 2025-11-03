<div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">⭐ Important Tasks</h2>

    <form id="important-form" class="flex gap-2 mb-6">
        <input type="text" id="important-title" name="title" placeholder="Tambah tugas penting..."
               class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-yellow-300">
        <button type="submit"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
            + Tambah
        </button>
    </form>

    <ul id="important-list" class="space-y-3">
        @forelse($todos as $todo)
            <li class="flex items-center justify-between bg-white shadow p-3 rounded-lg">
                <label class="flex items-center gap-3">
                    <input type="checkbox" class="toggle-important accent-yellow-500"
                           data-id="{{ $todo->id }}" {{ $todo->is_completed ? 'checked' : '' }}>
                    <span class="{{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                        {{ $todo->title }}
                    </span>
                </label>
                <button data-id="{{ $todo->id }}" class="delete-important text-red-500 hover:text-red-700">🗑️</button>
            </li>
        @empty
            <p class="text-gray-500 text-center">Belum ada tugas penting.</p>
        @endforelse
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('important-list');
    const form = document.getElementById('important-form');
    const input = document.getElementById('important-title');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const title = input.value.trim();
        if (!title) return;

        const res = await fetch('{{ route("important.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ title }),
        });
        const html = await res.text();
        document.querySelector('#main-content').innerHTML = html;
    });

    list.addEventListener('click', async (e) => {
        if (e.target.classList.contains('toggle-important')) {
            const id = e.target.dataset.id;
            await fetch(`/dashboard/important/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            location.reload();
        }

        if (e.target.classList.contains('delete-important')) {
            const id = e.target.dataset.id;
            await fetch(`/dashboard/important/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            location.reload();
        }
    });
});
</script>
