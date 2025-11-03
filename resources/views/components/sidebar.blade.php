@php
    use App\Models\UserTodoCategory;
    $categories = \App\Models\UserTodoCategory::where('user_id', auth()->id())->get();
@endphp

<aside class="w-64 bg-white shadow-md flex flex-col">
    {{-- Header --}}
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold text-gray-800">My Tasks</h2>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-4 py-3 space-y-2" id="categoryList">
        <button id="btn-daily"
            class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 font-medium transition">
            🗓️ Daily
        </button>

        <button id="btn-important"
            class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 font-medium transition">
            ⭐ Important
        </button>

        <hr class="my-3 border-gray-300">

        {{-- Dynamic Categories --}}
       @foreach ($categories as $category)
    <a 
        href="{{ route('user.index', ['category_id' => $category->id]) }}"
        class="block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition">
        📁 {{ ucfirst($category->name) }}
    </a>
@endforeach

    {{-- New List button --}}
    <div id="newListContainer">
        <button id="btn-new-list"
            class="block w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
            + New List
        </button>
    </div>
</nav>

    {{-- Avatar --}}
    <x-avatar />
</aside>

{{-- Inline Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("newListContainer");
    const categoryList = document.getElementById("categoryList");

    // === Tambah list baru secara inline ===
    container.addEventListener("click", function (e) {
        const btn = document.getElementById("btn-new-list");

        // Ganti tombol jadi input
        btn.outerHTML = `
            <input type="text" id="newListInput" 
                placeholder="Nama list baru..." 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
                autofocus>
        `;

        const input = document.getElementById("newListInput");

        input.addEventListener("blur", resetButton);
        input.addEventListener("keydown", async function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                const name = input.value.trim();
                if (!name) return resetButton();

                try {
                    const res = await fetch("{{ route('category.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({ name })
                    });

                    if (res.ok) {
                        const newCategory = await res.json();

                        // ✅ Tambahkan langsung ke sidebar tanpa reload
                        const newButton = document.createElement("button");
                        newButton.className = "category-btn block w-full text-left px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition";
                        newButton.innerHTML = "📁 " + name;
                        newButton.dataset.id = newCategory.id;

                        // Sisipkan sebelum tombol "+ New List"
                        categoryList.insertBefore(newButton, container);

                        // tambahkan event klik biar bisa langsung diakses
                        newButton.addEventListener("click", handleCategoryClick);
                    }
                } catch (error) {
                    console.error("Gagal menambahkan list:", error);
                }

                resetButton();
            }
        });

        function resetButton() {
            input.outerHTML = `
                <button id="btn-new-list"
                    class="block w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
                    + New List
                </button>
            `;
        }
    });

    // === Biar kategori bisa diklik ===
    function handleCategoryClick(e) {
        const id = e.currentTarget.dataset.id;
        if (!id) return;
        window.location.href = `/categories/${id}`;
    }

    // Tambahkan event click ke semua kategori yang sudah ada
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener("click", handleCategoryClick);
    });
});
</script>
