<div id="content" class="max-w-3xl mx-auto py-10">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center gap-2">🗓️ Daily Tasks</h2>

    <div class="bg-white shadow-sm rounded-xl p-4 mb-6 border border-gray-100">
        <form id="daily-form" class="flex items-center gap-3">
            <input type="text" id="daily-title" name="title"
                   placeholder="Tambah tugas baru..."
                   class="flex-1 px-4 py-2 text-gray-700 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-400">
            <button type="button" id="expand-form" class="text-blue-600 text-2xl font-bold hover:text-blue-700">+</button>
        </form>

        <div id="extra-fields" class="hidden mt-4 space-y-3">
            <textarea id="daily-description" rows="2" placeholder="Deskripsi tugas..."
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-400 text-gray-700"></textarea>
            <input type="date" id="daily-due-date"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-400 text-gray-700">
            <button id="save-daily" class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700">
                Simpan Tugas
            </button>
        </div>
    </div>

    <div>
        <h3 class="text-xl font-semibold text-gray-700 mb-3">🕒 Tugas Aktif</h3>
        <ul id="pending-list" class="space-y-3">
            @foreach ($todos->where('is_completed', 0) as $todo)
                <li class="flex items-start justify-between bg-white p-4 shadow-sm rounded-xl border border-gray-100">
                    <div>
                        <input type="checkbox" class="toggle-daily accent-blue-600 mt-1" data-id="{{ $todo->id }}">
                        <span contenteditable="true" data-id="{{ $todo->id }}" class="editable-title ml-2 text-gray-800 font-medium">{{ $todo->title }}</span>
                        @if($todo->due_date)
                            <p class="text-xs text-gray-400 mt-1">🎯 {{ \Carbon\Carbon::parse($todo->due_date)->format('d M Y') }}</p>
                        @endif
                    </div>
                    <button data-id="{{ $todo->id }}" class="delete-daily text-red-500 hover:text-red-700">🗑️</button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-10">
        <h3 class="text-xl font-semibold text-gray-700 mb-3">✅ Selesai</h3>
        <ul id="completed-list" class="space-y-3">
            @foreach ($todos->where('is_completed', 1) as $todo)
                <li class="flex items-start justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 opacity-70">
                    <span class="line-through text-gray-500">{{ $todo->title }}</span>
                    <button data-id="{{ $todo->id }}" class="delete-daily text-red-500 hover:text-red-700">🗑️</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
