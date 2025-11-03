<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportantTodo;
use Illuminate\Support\Facades\Auth;

class ImportantTodoController extends Controller
{
    // Menampilkan semua todo penting milik user
    public function index()
    {
        $todos = ImportantTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('important', compact('todos'));
    }

    // Menyimpan todo penting baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        ImportantTodo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return redirect()->route('important.index');
    }

    // Mengubah status selesai / belum
    public function toggle($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        return redirect()->route('important.index');
    }

    // Menghapus todo
    public function destroy($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('important.index');
    }
}
