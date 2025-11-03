<aside class="w-64 bg-white shadow-md flex flex-col">
    {{-- Header --}}
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

        <button id="btn-user"
            class= "block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100">
                + New List
            </a>
        </button>
    </nav>

    {{-- Avatar --}}
    <x-avatar />
</aside>
