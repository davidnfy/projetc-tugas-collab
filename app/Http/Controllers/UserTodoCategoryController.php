<?php

namespace App\Http\Controllers;

use App\Models\UserTodoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTodoCategoryController extends Controller
{
    public function index()
    {
        $categories = UserTodoCategory::where('user_id', Auth::id())->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = UserTodoCategory::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Category created', 'category' => $category], 201);
    }

    public function destroy($id)
    {
        $category = UserTodoCategory::where('user_id', Auth::id())->findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
