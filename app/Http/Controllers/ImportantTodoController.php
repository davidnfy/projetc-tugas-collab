<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportantTodo;
use Illuminate\Support\Facades\Auth;

class ImportantTodoController extends Controller
{
    public function index()
    {
        $todos = ImportantTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->ajax()) {
            return view('partials.important', compact('todos'))->render();
        }

        return view('dashboard', [
            'page' => 'important',
            'todos' => $todos
        ]);
    }

    // ✅ CREATE
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

        // 🔔 SweetAlert notif
        return redirect()->route('important.index')->with('success', 'Tugas penting berhasil ditambahkan!');
    }

    // ✅ TOGGLE (ubah status)
    public function toggle($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        // 🔔 SweetAlert notif
        return redirect()->route('important.index')->with('updated', 'Status tugas penting diperbarui!');
    }

    // ✅ UPDATE (ubah judul)
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->title = $request->title;
        $todo->save();

        // 🔔 SweetAlert notif
        return redirect()->route('important.index')->with('updated', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('important.index')->with('deleted', 'Tugas berhasil dihapus!');
    }
}
