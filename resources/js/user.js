export function initUserTodoPage() {
    console.log("✅ User Todo Page initialized");
    console.log("Swal test:", typeof Swal);
    Swal.fire({ title: "Test", text: "Apakah muncul?", icon: "info" });


    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const addForm = document.getElementById("addUserTodoForm");
    const mainContent = document.querySelector("#mainContent");

    if (!csrfToken) {
        console.warn("⚠️ CSRF token not found — check your <meta> tag in layout!");
        return;
    }

    // 🟢 1. Tambah todo baru
    if (addForm) {
        addForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const formData = new FormData(addForm);
            const title = formData.get("title")?.trim();
            if (!title) return showToast("Judul tidak boleh kosong!", "error");

            try {
                const res = await fetch(addForm.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: formData,
                });

                if (res.ok) {
                    showToast("Berhasil ditambahkan!", "success");
                    await reloadUserTodoList();
                    addForm.reset();
                } else showToast("Gagal menambah", "error");
            } catch {
                showToast("Terjadi kesalahan koneksi!", "error");
            }
        });
    }

    // 🟢 2. Event delegation (edit, save, delete, toggle)
    if (mainContent && !mainContent.dataset.listenerAttached) {
        mainContent.dataset.listenerAttached = "true"; // cegah double listener

        mainContent.addEventListener("click", async (e) => {
            const btn = e.target.closest("button");
            if (!btn) return;

            // ✏️ Edit
            if (btn.classList.contains("edit-btn")) {
                e.preventDefault();
                const parent = btn.closest("li");
                const input = parent.querySelector("input[name='title']");
                const span = parent.querySelector(".todo-title");
                const saveBtn = parent.querySelector(".save-btn");

                input.classList.toggle("hidden");
                span.classList.toggle("hidden");
                saveBtn.classList.toggle("hidden");
                input.focus();
                return;
            }

            // 💾 Save
            if (btn.classList.contains("save-btn")) {
                e.preventDefault();
                const form = btn.closest("form");
                const input = form.querySelector("input[name='title']");
                const title = input.value.trim();

                if (!title) return showToast("Judul tidak boleh kosong!", "error");

                try {
                    const res = await fetch(form.action, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": csrfToken,
                            "X-Requested-With": "XMLHttpRequest",
                        },
                        body: new URLSearchParams({ title }),
                    });

                    if (res.ok) {
                        showToast("Perubahan disimpan!", "success");
                        await reloadUserTodoList();
                    } else showToast("Gagal menyimpan perubahan!", "error");
                } catch {
                    showToast("Koneksi gagal saat update!", "error");
                }
                return;
            }

            if (btn.classList.contains("delete-btn")) {
                e.preventDefault();

                const form = btn.closest("form");
                if (!form) return;
                const url = form.getAttribute("action");

                const result = await Swal.fire({
                    title: "Loh dibusek tah?",
                    text: "Ga iso mbok batalno loh.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Iyo, busek",
                    cancelButtonText: "Gasido",
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    reverseButtons: true,
                });

                if (!result.isConfirmed) return;

                try {
                    const res = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "X-Requested-With": "XMLHttpRequest",
                        },
                    });

                    if (res.ok) {
                        await Swal.fire({
                            icon: "success",
                            title: "Tugas dihapus!",
                            showConfirmButton: false,
                            timer: 1200,
                        });
                        reloadUserTodoList(400);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal menghapus!",
                            text: "Terjadi kesalahan saat menghapus tugas.",
                        });
                    }
                } catch {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Koneksi gagal saat menghapus tugas.",
                    });
                }
                return;
            }
        });
    }

    // 🟢 3. Fungsi reload todo list
    async function reloadUserTodoList(delay = 0) {
        const categoryId = addForm?.dataset.categoryId;
        if (!categoryId) return location.reload();

        if (delay > 0) await new Promise((r) => setTimeout(r, delay));

        try {
            const res = await fetch(`/categories/${categoryId}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await res.text();

            const temp = document.createElement("div");
            temp.innerHTML = html;
            const newContent = temp.querySelector("#mainContent")?.innerHTML || html;
            const contentEl = document.getElementById("mainContent");
            if (contentEl) contentEl.innerHTML = newContent;

            // ⚡ re-inisialisasi halaman setelah isi diganti
            initUserTodoPage();
        } catch {
            showToast("Gagal memuat ulang daftar tugas", "error");
        }
    }
}

// 🔔 Toast helper
function showToast(message, type = "info") {
    console.log(`[${type.toUpperCase()}] ${message}`);
}
