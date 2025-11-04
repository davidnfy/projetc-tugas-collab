export function initImportantPage() {
    console.log("✅ Important page initialized");

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const form = document.getElementById("important-form");
    const input = document.getElementById("important-title");

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const title = input.value.trim();
        if (!title) return showToast("Judul tidak boleh kosong", "error");

        try {
            const res = await fetch("/important", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({ title }),
            });

            const data = await res.json();
            if (data?.success) {
                showToast("Berhasil ditambahkan", "success");
                await reloadImportantList();
                input.value = "";
            } else showToast("Gagal menambahkan", "error");
        } catch {
            showToast("Terjadi kesalahan koneksi", "error");
        }
    });

    document.querySelectorAll(".toggle-important-task").forEach((checkbox) => {
        checkbox.addEventListener("change", async (e) => {
            const id = e.target.dataset.id;
            try {
                const res = await fetch(`/important/${id}/toggle`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Status diperbarui", "success");
                    await reloadImportantList();
                } else showToast("Gagal memperbarui status", "error");
            } catch {
                showToast("Koneksi gagal saat toggle", "error");
            }
        });
    });

    document.querySelectorAll(".delete-important-task").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            const confirmed = await confirmDelete();
            if (!confirmed) return;

            try {
                const res = await fetch(`/important/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Berhasil dihapus", "success");
                    await reloadImportantList();
                } else showToast("Gagal menghapus", "error");
            } catch {
                showToast("Koneksi gagal saat menghapus", "error");
            }
        });
    });

    async function reloadImportantList() {
        try {
            const res = await fetch("/important", {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await res.text();

            const temp = document.createElement("div");
            temp.innerHTML = html;
            const newContent = temp.querySelector("#content")?.innerHTML || html;

            const contentEl = document.getElementById("content");
            if (contentEl) contentEl.innerHTML = newContent;

            initImportantPage();
        } catch {
            showToast("Gagal memuat ulang daftar tugas", "error");
        }
    }
}
