<div class="p-6">
    {{-- Judul kategori --}}
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">📁 {{ ucfirst($category->name) }}</h2>

{{-- Form tambah todo baru di kategori ini --}}
<form action="{{ route('user.store') }}" method="POST" class="flex mb-4">
    @csrf
    <input type="hidden" name="category_id" value="{{ $category->id }}">

    <input
        type="text"
        name="title"
        placeholder="Tambah tugas di {{ $category->name }}..."
        class="flex-grow p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-700"
        required
    >
    <button
        type="submit"
        class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition"
    >
        Add
    </button>
</form>

{{-- Daftar todo --}}
@if ($todos->isEmpty())
    <p class="text-gray-500 text-center mt-8">Belum ada tugas di kategori ini.</p>
@else
    <ul class="space-y-3">
        @foreach ($todos as $todo)
            <li class="flex items-center justify-between bg-white shadow-sm p-3 rounded-lg hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    {{-- Checkbox toggle --}}
                    <form action="{{ route('user.toggle', $todo->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="focus:outline-none">
                            @if ($todo->is_completed)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <div class="w-5 h-5 border border-gray-400 rounded"></div>
                            @endif
                        </button>
                    </form>

                    {{-- Judul tugas + inline edit --}}
                    <form action="{{ route('user.update', $todo->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input
                            type="text"
                            name="title"
                            value="{{ $todo->title }}"
                            class="hidden p-1 border rounded text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 w-48"
                        >
                        <span class="todo-title {{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                            {{ $todo->title }}
                        </span>
                        <button type="submit" class="hidden text-green-500 hover:text-green-600">💾</button>
                    </form>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex gap-3 items-center">
                    <button
                        class="text-blue-500 hover:text-blue-600 transition edit-btn"
                        title="Edit"
                    >
                        ✏️
                    </button>

                    <form action="{{ route('user.destroy', $todo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="text-red-500 hover:text-red-600 transition"
                            onclick="return confirm('Hapus tugas ini?')"
                        >
                            ❌
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endif

</div>

<script>
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const parent = this.closest('li');
        const input = parent.querySelector('input[name="title"]');
        const title = parent.querySelector('.todo-title');
        const saveBtn = parent.querySelector('button[type="submit"]');

        input.classList.toggle('hidden');
        title.classList.toggle('hidden');
        saveBtn.classList.toggle('hidden');

        input.focus();
    });
});
</script>
