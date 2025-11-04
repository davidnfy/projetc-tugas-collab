<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyTodo;
use Illuminate\Support\Facades\Auth;

class DailyTodoController extends Controller
{
    public function index()
    {
        $todos = DailyTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        if (request()->ajax()) {
            return view('partials.daily', compact('todos'))->render();
        }

        return view('dashboard', [
            'page' => 'daily',
            'todos' => $todos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        DailyTodo::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'is_completed' => false,
        ]);

        return redirect()->route('daily.index')->with('success', 'Berhasil ditambahkan!');
    }

    public function toggle($id)
    {
        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->is_completed = !$todo->is_completed;
        $todo->save();

        return redirect()->route('daily.index')->with('updated', 'Status diperbarui!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->title = $request->title;
        $todo->save();

        return redirect()->route('daily.index')->with('updated', 'Berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return redirect()->route('daily.index')->with('deleted', 'Berhasil dihapus!');
    }
}
