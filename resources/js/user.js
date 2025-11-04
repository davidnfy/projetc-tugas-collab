export function initUserTodoPage() {
    console.log("✅ User Todo Page initialized");

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const addForm = document.getElementById("addUserTodoForm");

    if (!csrfToken) {
        console.warn("⚠️ CSRF token not found — check your <meta> tag in layout!");
        return;
    }

    addForm?.addEventListener("submit", async (e) => {
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

    document.querySelectorAll(".toggle-user-todo").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const url = btn.dataset.url;
            if (!url) return;

            btn.disabled = true;
            try {
                const res = await fetch(url, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Status diperbarui!", "success");
                    await reloadUserTodoList();
                } else showToast("Gagal toggle status!", "error");
            } catch {
                showToast("Koneksi gagal!", "error");
            } finally {
                btn.disabled = false;
            }
        });
    });

    document.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const parent = btn.closest("li");
            const input = parent.querySelector("input[name='title']");
            const span = parent.querySelector(".todo-title");
            const saveBtn = parent.querySelector(".save-btn");

            input.classList.toggle("hidden");
            span.classList.toggle("hidden");
            saveBtn.classList.toggle("hidden");
            input.focus();
        });
    });

    document.querySelectorAll(".save-btn").forEach((btn) => {
        btn.addEventListener("click", async (e) => {
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
        });
    });

    document.querySelectorAll(".delete-user-todo").forEach((btn) => {
        btn.addEventListener("click", async (e) => {
            e.preventDefault();
            const confirmed = confirm("Hapus tugas ini?");
            if (!confirmed) return;

            const url = btn.dataset.url;
            if (!url) return;

            try {
                const res = await fetch(url, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Tugas dihapus!", "success");
                    await reloadUserTodoList();
                } else showToast("Gagal menghapus tugas!", "error");
            } catch {
                showToast("Koneksi gagal saat hapus!", "error");
            }
        });
    });

    async function reloadUserTodoList() {
        const categoryId = document.querySelector("#addUserTodoForm")?.dataset.categoryId;
        if (!categoryId) return location.reload();

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

            initUserTodoPage();
        } catch {
            showToast("Gagal memuat ulang daftar tugas", "error");
        }
    }
}

function showToast(message, type = "info") {
    console.log(`[${type.toUpperCase()}] ${message}`);
}
