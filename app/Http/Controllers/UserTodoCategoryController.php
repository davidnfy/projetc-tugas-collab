<?php

namespace App\Http\Controllers;

use App\Models\UserTodoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTodoCategoryController extends Controller
{
    /**
     * Tampilkan semua kategori milik user
     */
    public function index()
    {
        $categories = UserTodoCategory::where('user_id', Auth::id())->get();

        if (request()->ajax()) {
            return response()->json($categories);
        }

        return view('user', compact('categories'));
    }

    /**
     * Tampilkan 1 kategori + semua todos di dalamnya
     */
    public function show($id)
    {
    $category = UserTodoCategory::where('user_id', Auth::id())
        ->findOrFail($id);

    // Redirect ke halaman todo sesuai kategori
    return redirect()->route('user.index', ['category_id' => $category->id]);
    }


    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = UserTodoCategory::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return request()->ajax()
            ? response()->json($category)
            : redirect()->back()->with('success', 'Kategori berhasil dibuat!');
    }

    /**
     * Update nama kategori
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = UserTodoCategory::where('user_id', Auth::id())->findOrFail($id);
        $category->update(['name' => $request->name]);

        return request()->ajax()
            ? response()->json($category)
            : redirect()->back()->with('updated', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        $category = UserTodoCategory::where('user_id', Auth::id())->findOrFail($id);
        $category->delete();

        return request()->ajax()
            ? response()->json(['message' => 'Kategori dihapus.'])
            : redirect()->back()->with('deleted', 'Kategori berhasil dihapus!');
    }
}
