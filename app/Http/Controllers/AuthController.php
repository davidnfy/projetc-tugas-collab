<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ================= VIEW =================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('dashboard')->with('success', 'Akun berhasil dibuat!');
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!'
        ])->onlyInput('email');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }

    // ================= GOOGLE LOGIN =================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'nama' => $googleUser->getName(), 
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(12)),
                ]
            );

            Auth::guard('web')->login($user);
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google.' . $e->getMessage());
        }
    }
}
