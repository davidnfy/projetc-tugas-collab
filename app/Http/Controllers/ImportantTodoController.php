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

        return redirect()->route('important.index')->with('success', 'Berhasil ditambahkan!');
    }

    public function toggle($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        return redirect()->route('important.index')->with('updated', 'Status diperbarui!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->title = $request->title;
        $todo->save();

        return redirect()->route('important.index')->with('updated', 'Berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $todo = ImportantTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('important.index')->with('deleted', 'Berhasil dihapus!');
    }
}
