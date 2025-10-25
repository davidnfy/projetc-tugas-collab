<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        $validatedData = request()->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully', 
            'user' => $user], 201);
    }

    public function getAllUser(){
        $user = User::all();
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validatedData = $request->validate([
        'nama' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
        'password' => 'sometimes|nullable|string|min:8',
    ]);

    if (!empty($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    } else {
        unset($validatedData['password']);
    }

    $user->fill($validatedData);
    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

}
 