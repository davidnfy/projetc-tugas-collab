export function initDailyPage() {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const addBtn = document.getElementById('btn-add-task');
    const addForm = document.getElementById('form-add-task');
    const pendingList = document.getElementById('pending-list');
    const completedList = document.getElementById('completed-list');

    // toggle form tambah
    addBtn.addEventListener('click', () => {
        addForm.classList.toggle('hidden');
    });

    // tambah task baru
    addForm.addEventListener('submit', async e => {
        e.preventDefault();
        const title = document.getElementById('new-title').value.trim();
        const desc = document.getElementById('new-description').value.trim();
        const due = document.getElementById('new-due-date').value;

        if (!title) return alert('Masukkan judul task.');

        const res = await fetch('/daily', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ title, description: desc, due_date: due })
        });

        const html = await res.text();
        document.querySelector('#main-content').innerHTML = html;
        initDailyPage(); // reinit event listener
    });

    // edit title inline
    document.querySelectorAll('.edit-title').forEach(input => {
        input.addEventListener('blur', async e => {
            const id = e.target.dataset.id;
            const newTitle = e.target.value.trim();
            await fetch(`/daily/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ title: newTitle })
            });
        });
    });

    // toggle selesai
    document.querySelectorAll('.toggle-complete').forEach(chk => {
        chk.addEventListener('change', async e => {
            const id = e.target.dataset.id;
            await fetch(`/daily/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': token }
            });
            location.reload();
        });
    });

    // hapus task
    document.querySelectorAll('.delete-task').forEach(btn => {
        btn.addEventListener('click', async e => {
            const id = e.target.dataset.id;
            if (!confirm('Hapus task ini?')) return;

            await fetch(`/daily/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token }
            });

            e.target.closest('li').remove();
        });
    });
}
