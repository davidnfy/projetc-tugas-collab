document.addEventListener('DOMContentLoaded', () => {
    function initTodo(formSelector, listSelector) {
        const form = document.querySelector(formSelector);
        const list = document.querySelector(listSelector);
        if (!form || !list) return;

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Animasi fade
        const fadeIn = (el) => {
            el.style.opacity = 0;
            el.style.transition = "opacity 0.3s ease";
            requestAnimationFrame(() => {
                el.style.opacity = 1;
            });
        };

        // Tambah Task via AJAX
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                });

                if (!res.ok) throw new Error('Gagal menambah task.');

                const html = await res.text();
                list.innerHTML = html;
                fadeIn(list);
                form.reset();
            } catch (err) {
                alert(err.message);
            }
        });

        // Toggle & Delete pakai event delegation
        list.addEventListener('submit', async (e) => {
            e.preventDefault();
            const subForm = e.target.closest('form');
            if (!subForm) return;

            try {
                const res = await fetch(subForm.action, {
                    method: subForm.method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                });

                if (!res.ok) throw new Error('Gagal memperbarui task.');

                const html = await res.text();
                list.innerHTML = html;
                fadeIn(list);
            } catch (err) {
                alert(err.message);
            }
        });
    }

    // Inisialisasi untuk halaman yang ada
    initTodo('#daily-form', '#daily-list');
    initTodo('#important-form', '#important-list');
});
