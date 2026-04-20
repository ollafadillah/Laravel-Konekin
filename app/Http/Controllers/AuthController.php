<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister(Request $request)
    {
        $type = $request->query('type'); // Menangkap 'umkm' atau 'creative_worker'
        
        // Kalau user coba akses /register langsung tanpa pilih role, balikin ke halaman pilih role
        if (!$type) {
            return redirect()->route('register.role');
        }

        return view('auth.register', compact('type'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:creative_worker,umkm',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
        ]);

        Auth::login($user);

        return ($user->type === 'umkm') 
            ? redirect()->route('dashboard.umkm') 
            : redirect()->route('dashboard.creative');
    }

    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return ($user->type === 'umkm') 
                ? redirect()->route('dashboard.umkm') 
                : redirect()->route('dashboard.creative');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
