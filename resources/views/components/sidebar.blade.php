@php
    use App\Models\UserTodoCategory;
    $categories = UserTodoCategory::where('user_id', auth()->id())->get();
@endphp

<aside class="w-64 bg-white shadow-md flex flex-col">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold text-gray-800">My List</h2>
    </div>

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

        @foreach ($categories as $category)
            <div class="category-item flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 transition"
                data-id="{{ $category->id }}">
                <a href="{{ route('user.index', ['category_id' => $category->id]) }}"
                    class="flex-1 text-gray-700 hover:text-blue-600">
                    📁 {{ ucfirst($category->name) }}
                </a>
                <div class="actions flex gap-1">
                    <button class="btn-edit text-blue-500 hover:text-blue-700" title="Edit">✏️</button>
                    <button class="btn-delete text-red-500 hover:text-red-700" title="Hapus">❌</button>
                </div>
            </div>
        @endforeach

        <div id="newListContainer" class="mt-2">
            <button id="btn-new-list"
                class="block w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
                + New List
            </button>
        </div>
    </nav>

    <x-avatar />
</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("newListContainer");
    const categoryList = document.getElementById("categoryList");

    function createCategoryItem(category) {
        const div = document.createElement("div");
        div.className = "category-item flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 transition";
        div.dataset.id = category.id;
        div.innerHTML = `
            <a href="/todos?category_id=${category.id}" class="flex-1 text-gray-700 hover:text-blue-600">
                📁 ${category.name.charAt(0).toUpperCase() + category.name.slice(1)}
            </a>
            <div class="actions flex gap-1">
                <button class="btn-edit text-blue-500 hover:text-blue-700" title="Edit">✏️</button>
                <button class="btn-delete text-red-500 hover:text-red-700" title="Hapus">❌</button>
            </div>
        `;
        return div;
    }

    container.addEventListener("click", function () {
        const btn = document.getElementById("btn-new-list");
        if (!btn) return;

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
                            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: JSON.stringify({ name })
                    });

                    if (!res.ok) throw new Error("HTTP " + res.status);
                    const newCategory = await res.json();
                    const newItem = createCategoryItem(newCategory);
                    categoryList.insertBefore(newItem, container);
                    showToast("✅ List baru ditambahkan", "success");
                } catch (error) {
                    console.error(error);
                    showToast("❌ Gagal menambah list", "error");
                }

                resetButton();
            }
        });
    });

    function resetButton() {
        const input = document.getElementById("newListInput");
        if (input) {
            input.outerHTML = `
                <button id="btn-new-list"
                    class="block w-full text-left px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
                    + New List
                </button>
            `;
        }
    }

    categoryList.addEventListener("click", async function (e) {
        const item = e.target.closest(".category-item");
        if (!item) return;
        const id = item.dataset.id;

        if (e.target.classList.contains("btn-edit")) {
            const link = item.querySelector("a");
            const currentName = link.textContent.replace("📁", "").trim();
            const newName = prompt("Ubah nama list:", currentName);
            if (!newName || newName === currentName) return;

            try {
                const res = await fetch(`/categories/${id}`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({ name: newName })
                });

                if (!res.ok) throw new Error("HTTP " + res.status);
                const updated = await res.json();
                link.textContent = "📁 " + updated.name;
                showToast("✏️ Nama list diperbarui", "success");
            } catch (err) {
                console.error(err);
                showToast("❌ Gagal update list", "error");
            }
        }

        if (e.target.classList.contains("btn-delete")) {
            if (!confirm("Yakin ingin menghapus list ini?")) return;

            try {
                const res = await fetch(`/categories/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!res.ok) throw new Error("HTTP " + res.status);
                item.remove();
                showToast("❌ List dihapus", "success");
            } catch (err) {
                console.error(err);
                showToast("❌ Gagal menghapus list", "error");
            }
        }
    });

    function showToast(msg, type = "info") {
        console.log(`[${type.toUpperCase()}] ${msg}`);
    }
});
</script>
