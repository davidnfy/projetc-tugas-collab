<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseTodoController extends Controller
{
    protected $model;

    public function __construct($modelClass)
    {
        $this->model = $modelClass;
    }

    public function index(Request $request){
    $query = $this->model::where('user_id', Auth::id());

    $categoryId = $request->category_id ?? null;
    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($request->has('filter')) {
        switch ($request->filter) {
            case 'pending':
                $query->where('is_completed', false)
                      ->where(function ($q) {
                          $q->whereNull('due_date')
                            ->orWhere('due_date', '>=', now());
                      });
                break;
            case 'completed':
                $query->where('is_completed', true);
                break;
            case 'overdue':
                $query->where('is_completed', false)
                      ->where('due_date', '<', now());
                break;
        }
    }

    $todos = $query->orderBy('created_at', 'desc')->get();

    $todos->map(function($todo) {
        $todo->active_list_count = $todo->is_completed ? 0 : 1;
        return $todo;
    });

    $pendingCountQuery = $this->model::where('user_id', Auth::id())
                                    ->where('is_completed', false);
    if ($categoryId) {
        $pendingCountQuery->where('category_id', $categoryId);
    }
    $pendingCount = $pendingCountQuery->count();

    return response()->json([
        'count_pending' => $pendingCount,
        'todos' => $todos
    ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|integer|exists:user_todo_categories,id',
        ]);

        $todo = $this->model::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
        ]);

        return response()->json(['message' => 'Todo created', 'todo' => $todo], 201);
    }

    public function update(Request $request, $id)
    {
        $todo = $this->model::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
            'due_date' => 'nullable|date',
        ]);

        $todo->update($validated);

        return response()->json(['message' => 'Todo updated', 'todo' => $todo]);
    }

    public function destroy($id)
    {
        $todo = $this->model::where('user_id', Auth::id())->findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted']);
    }
}
