<?php

namespace App\Http\Controllers;

use App\Models\UserTodo;
use App\Models\UserTodoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTodoController extends Controller
{
    /**
     * Tampilkan semua todo milik user (bisa filter per kategori)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil semua kategori milik user
        $categories = UserTodoCategory::where('user_id', $user->id)->get();

        // Kalau user klik kategori tertentu
        if ($request->has('category_id')) {
            $category = $categories->where('id', $request->category_id)->first();
        } else {
            // Ambil kategori pertama (jika ada)
            $category = $categories->first();
        }

        // Ambil todos berdasarkan kategori (jika ada)
        $todos = $category
            ? UserTodo::where('user_id', $user->id)
                ->where('category_id', $category->id)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        // === AJAX request ===
        if ($request->ajax()) {
            return view('partials.user', compact('category', 'todos'))->render();
        }

        // === Full page ===
        return view('dashboard', [
            'page' => 'user',
            'category' => $category,
            'todos' => $todos,
        ]);
    }

    /**
     * Tambah todo baru ke kategori tertentu
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:user_todo_categories,id',
        ]);

        $todo = UserTodo::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return $request->ajax()
            ? response()->json($todo)
            : redirect()->back()->with('success', 'Todo berhasil ditambahkan!');
    }

    /**
     * Toggle status todo (selesai / belum)
     */
    public function toggle($id)
    {
        $todo = UserTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        return request()->ajax()
            ? response()->json($todo)
            : redirect()->back()->with('updated', 'Status todo diperbarui!');
    }

    /**
     * Update judul todo
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = UserTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['title' => $request->title]);

        return request()->ajax()
            ? response()->json($todo)
            : redirect()->back()->with('updated', 'Todo berhasil diperbarui!');
    }

    /**
     * Hapus todo
     */
    public function destroy($id)
    {
        $todo = UserTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return request()->ajax()
            ? response()->json(['message' => 'Todo dihapus.'])
            : redirect()->back()->with('deleted', 'Todo berhasil dihapus!');
    }
}
