export function initDailyPage() {
    console.log("✅ Daily page initialized");

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const titleInput = document.getElementById("daily-title");
    const descInput = document.getElementById("daily-description");
    const dateInput = document.getElementById("daily-due-date");
    const form = document.getElementById("daily-form");
    const extraFields = document.getElementById("extra-fields");
    const expandBtn = document.getElementById("expand-form");
    const saveBtn = document.getElementById("save-daily");

    expandBtn?.addEventListener("click", () => {
        extraFields.classList.toggle("hidden");
    });

    saveBtn?.addEventListener("click", async (e) => {
        e.preventDefault();
        const title = titleInput.value.trim();
        if (!title) return showToast("Judul wajib diisi!", "error");

        try {
            const res = await fetch("/daily", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    title,
                    description: descInput?.value ?? "",
                    due_date: dateInput?.value ?? null,
                }),
            });

            const data = await res.json();
            if (data?.success) {
                showToast("Berhasil ditambahkan", "success");
                await reloadDailyList();
                form.reset();
                extraFields.classList.add("hidden");
            } else showToast("Gagal menambah tugas", "error");
        } catch {
            showToast("Terjadi kesalahan koneksi", "error");
        }
    });

    document.querySelectorAll(".toggle-daily").forEach((checkbox) => {
        checkbox.addEventListener("change", async (e) => {
            const id = e.target.dataset.id;
            const prev = e.target.checked;
            e.target.disabled = true;

            try {
                const res = await fetch(`/daily/${id}/toggle`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Status diperbarui", "success");
                    await reloadDailyList();
                } else {
                    e.target.checked = !prev;
                    showToast("Gagal memperbarui status", "error");
                }
            } catch {
                e.target.checked = !prev;
                showToast("Koneksi gagal saat toggle", "error");
            } finally {
                e.target.disabled = false;
            }
        });
    });

    document.querySelectorAll(".delete-daily").forEach((btn) => {
        btn.addEventListener("click", async () => {
            const id = btn.dataset.id;
            if (!id) return;

            const confirmed = await confirmDelete();
            if (!confirmed) return;

            try {
                const res = await fetch(`/daily/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (res.ok) {
                    showToast("Berhasil dihapus", "success");
                    await reloadDailyList();
                } else showToast("Gagal menghapus", "error");
            } catch {
                showToast("Koneksi gagal saat menghapus", "error");
            }
        });
    });

    document.querySelectorAll(".editable-title").forEach((el) => {
        el.addEventListener("blur", async () => {
            const id = el.dataset.id;
            const title = el.innerText.trim();
            if (!title) {
                showToast("Judul tidak boleh kosong", "error");
                return await reloadDailyList();
            }

            try {
                const res = await fetch(`/daily/${id}`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify({ title }),
                });

                if (res.ok) showToast("Judul tersimpan", "success");
                else showToast("Gagal menyimpan perubahan", "error");
            } catch {
                showToast("Error koneksi saat menyimpan", "error");
            }
        });
    });

    async function reloadDailyList() {
        try {
            const res = await fetch("/daily", {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await res.text();

            const temp = document.createElement("div");
            temp.innerHTML = html;
            const newContent = temp.querySelector("#content")?.innerHTML || html;
            const contentEl = document.getElementById("content");
            if (contentEl) contentEl.innerHTML = newContent;

            initDailyPage();
        } catch {
            showToast("Gagal memuat ulang daftar", "error");
        }
    }
}
