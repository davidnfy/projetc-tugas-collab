<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman depan
Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTH SECTION ====================

// Halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses login (POST)
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Kredensial tidak cocok dengan catatan kami.',
    ])->onlyInput('email');
});

// Halaman register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Proses register (POST)
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Gunakan kolom "nama" agar sesuai dengan tabel users
    $user = User::create([
        'nama' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Auth::login($user);
    return redirect('/dashboard')->with('success', 'Akun berhasil dibuat!');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Lupa password (dummy page)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// ==================== DASHBOARD ====================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// ==================== GOOGLE LOGIN ====================

// Arahkan ke controller agar lebih bersih
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
