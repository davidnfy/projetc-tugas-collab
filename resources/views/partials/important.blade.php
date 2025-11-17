<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">⭐ Important Tasks</h2>

    <form id="addImportantForm" action="{{ route('important.store') }}" method="POST" class="flex mb-4">
        @csrf
        <input 
            type="text" 
            name="title" 
            placeholder="Add an important task..." 
            class="flex-grow p-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-400"
            required
        >
        <button 
            type="submit" 
            class="px-4 py-2 bg-indigo-500 text-white rounded-r-md hover:bg-indigo-600 transition"
        >
            Add
        </button>
    </form>

    @if ($todos->isEmpty())
        <p class="text-gray-500 text-center mt-8">No important tasks yet.</p>
    @else
        <ul class="space-y-3">
            @foreach ($todos as $todo)
                <li class="flex items-center justify-between bg-white shadow-sm p-3 rounded-lg hover:shadow-md transition">
                    <div class="flex items-center gap-3">
                        <form action="{{ route('important.toggle', $todo->id) }}" method="POST">
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

                        <form action="{{ route('important.update', $todo->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input 
                                type="text" 
                                name="title" 
                                value="{{ $todo->title }}" 
                                class="hidden p-1 border rounded text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
                            >
                            <span class="todo-title {{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                                {{ $todo->title }}
                            </span>
                            <button class="hidden save-btn text-green-500 hover:text-green-600"></button>
                        </form>
                    </div>

                    <div class="flex gap-3 items-center">
                        <button 
                            class="text-blue-500 hover:text-blue-600 transition edit-btn"
                            title="Edit"
                        >
                            ✏️
                        </button>

                        <form action="{{ route('important.destroy', $todo->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="text-red-500 hover:text-red-600 transition delete-important-task"
                                data-id="{{ $todo->id }}"
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


