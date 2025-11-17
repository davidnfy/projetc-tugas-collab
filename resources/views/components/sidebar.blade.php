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
            <div class="category-item group flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 transition"
                data-id="{{ $category->id }}">
                <a href="{{ route('user.index', ['category_id' => $category->id]) }}"
                class="category-link flex-1 text-gray-700 hover:text-blue-600 select-none">
                    📁 {{ ucfirst($category->name) }}
                </a>

                {{-- tombol delete tersembunyi kecuali saat hover (group-hover) --}}
                <button class="btn-delete text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition"
                        title="Hapus" aria-label="Hapus list">
                    ❌
                </button>
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

    const capitalize = str => str.charAt(0).toUpperCase() + str.slice(1);

    function createCategoryItem(category) {
        const div = document.createElement("div");
        div.className = "category-item group flex items-center justify-between px-3 py-2 rounded-md hover:bg-gray-100 transition";
        div.dataset.id = category.id;
        div.innerHTML = `
            <a href="/todos?category_id=${category.id}" 
               class="category-link flex-1 text-gray-700 hover:text-blue-600 select-none">
                📁 ${capitalize(category.name)}
            </a>
            <button class="btn-delete text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition" 
                    title="Hapus">❌</button>
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

    let clickTimeout = null;
    categoryList.addEventListener("click", function (e) {
        const item = e.target.closest(".category-item");
        if (!item) return;
        const id = item.dataset.id;

        // Delete button
        if (e.target.classList.contains("btn-delete")) {
            e.preventDefault();
            Swal.fire({
                title: "Loh dibusek tah?",
                text: "Ga iso mbok batalno loh.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Iyo, busek",
                cancelButtonText: "Gasido"
            }).then(async (result) => {
                if (result.isConfirmed) {
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
                        Swal.fire("Wes mari!", "List wes tak busek.", "success");
                    } catch (err) {
                        console.error(err);
                        Swal.fire("Gagal!", "Tidak bisa menghapus list.", "error");
                    }
                }
            });
            return;
        }

        const link = e.target.closest(".category-link");
        if (link) {
            e.preventDefault();

            if (clickTimeout) clearTimeout(clickTimeout);

            clickTimeout = setTimeout(() => {
                window.location.href = link.getAttribute("href");
                clickTimeout = null;
            }, 250);

            return;
        }
    });

    categoryList.addEventListener("dblclick", function (e) {
        const link = e.target.closest(".category-link");
        const item = e.target.closest(".category-item");
        if (!link || !item) return;

        if (clickTimeout) {
            clearTimeout(clickTimeout);
            clickTimeout = null;
        }

        const id = item.dataset.id;
        const currentName = link.textContent.replace("📁", "").trim();

        const input = document.createElement("input");
        input.type = "text";
        input.value = currentName;
        input.className = "flex-1 px-2 py-1 border rounded focus:outline-none focus:ring focus:ring-blue-200";
        link.replaceWith(input);
        input.focus();

        input.addEventListener("keydown", async (ev) => {
            if (ev.key === "Enter") await saveEdit(input, id);
        });

        input.addEventListener("blur", async () => await saveEdit(input, id));
    });

    async function saveEdit(input, id) {
        const newName = input.value.trim();
        if (!newName) {
            resetInput(input, id);
            return;
        }

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
            await res.json();

            // Swal.fire({
            //     icon: "success",
            //     title: "List diperbarui!",
            //     showConfirmButton: false,
            //     timer: 1000
            // });

            setTimeout(() => window.location.reload(), 500);

        } catch (err) {
            console.error(err);
            Swal.fire("Gagal!", "Tidak bisa mengupdate list.", "error");
            resetInput(input, id);
        }
    }

    function resetInput(input, id, name = null) {
        const a = document.createElement("a");
        a.href = `/todos?category_id=${id}`;
        a.className = "category-link flex-1 text-gray-700 hover:text-blue-600 select-none";
        a.textContent = "📁 " + capitalize(name || input.value);
        input.replaceWith(a);
    }

    function showToast(msg, type = "info") {
        console.log(`[${type.toUpperCase()}] ${msg}`);
    }
});
</script>
