@extends('layout.app')

@section('title', 'Dashboard - Todo List')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light p-3" style="min-height: 100vh;">
            <h4 class="mb-4">My To Do</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link active fw-bold text-primary">📅 Day</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-dark">⭐ Important</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-dark">➕ Tambah List</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 id="listTitle">📅 Day</h3>
                <button class="btn btn-danger btn-sm">Logout</button>
            </div>

            <!-- Input Todo -->
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Tambah tugas baru..." id="todoInput">
                <button class="btn btn-primary" id="addTodoBtn">Tambah</button>
            </div>

            <!-- Todo List -->
            <ul class="list-group" id="todoList">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" class="form-check-input me-2">
                        <span>Belajar Laravel</span>
                    </div>
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    const input = document.getElementById('todoInput');
    const list = document.getElementById('todoList');
    const addBtn = document.getElementById('addTodoBtn');

    addBtn.addEventListener('click', () => {
        const value = input.value.trim();
        if (!value) return;

        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            <div>
                <input type="checkbox" class="form-check-input me-2">
                <span>${value}</span>
            </div>
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        `;

        li.querySelector('button').addEventListener('click', () => li.remove());
        list.appendChild(li);
        input.value = '';
    });
</script>
@endsection
