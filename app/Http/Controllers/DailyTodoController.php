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

        // kalau request AJAX → kirim partial saja
        if (request()->ajax()) {
            return view('partials.daily', compact('todos'))->render();
        }

        // kalau akses langsung (misal buka /daily di browser)
        return view('dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        DailyTodo::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'is_completed' => false,
        ]);

        return $this->returnUpdatedList();
    }

    public function toggle($id)
    {
        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->update(['is_completed' => !$todo->is_completed]);

        return $this->returnUpdatedList();
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $todo = DailyTodo::where('user_id', Auth::id())->findOrFail($id);
        $todo->update($validated);

        return $this->returnUpdatedList();
    }

    public function destroy($id)
    {
        DailyTodo::where('user_id', Auth::id())->findOrFail($id)->delete();
        return $this->returnUpdatedList();
    }

    private function returnUpdatedList()
    {
        $todos = DailyTodo::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.daily', compact('todos'))->render();
    }
}
