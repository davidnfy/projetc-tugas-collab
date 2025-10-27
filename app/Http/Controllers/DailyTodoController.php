<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyTodo;
use Illuminate\Support\Facades\Auth;

class DailyTodoController extends Controller
{
    /**
     * Menampilkan daftar todo harian user
     */
    public function index()
    {
        $todos = DailyTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika request via fetch (AJAX)
        if (request()->ajax()) {
            return view('partials.daily', compact('todos'))->render();
        }

        // Jika akses langsung
        return view('partials.daily', compact('todos'));
    }

    /**
     * Menambah todo baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        DailyTodo::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return $this->returnUpdatedList($request);
    }

    /**
     * Toggle selesai/belum
     */
    public function toggle($id, Request $request)
    {
        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['is_completed' => !$todo->is_completed]);

        return $this->returnUpdatedList($request);
    }

    /**
     * Hapus todo
     */
    public function destroy($id, Request $request)
    {
        DailyTodo::where('user_id', Auth::id())->findOrFail($id)->delete();

        return $this->returnUpdatedList($request);
    }

    /**
     * Mengembalikan tampilan daftar terbaru
     */
    private function returnUpdatedList(Request $request)
    {
        $todos = DailyTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

       if ($request->ajax()) {
    return view('partials.daily', compact('todos'))->render();
}
return view('partials.daily', compact('todos'));

    }
}
