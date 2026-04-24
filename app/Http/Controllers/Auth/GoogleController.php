<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle(\Illuminate\Http\Request $request)
    {
        if ($request->has('type')) {
            session(['google_user_type' => $request->type]);
        }
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $intendedType = session('google_user_type');
            
            \Log::info('Google Login Attempt', ['email' => $googleUser->email, 'intended_type' => $intendedType]);

            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika user sudah ada tapi belum punya type, dan ada intendedType dari session
                if (!$user->type && $intendedType) {
                    $user->update(['type' => $intendedType]);
                }

                \Log::info('User found, logging in', [
                    'id' => $user->id,
                    'type' => $user->type,
                    'onboarding' => $user->onboarding_completed ?? 'null'
                ]);
                
                // Update data google jika email cocok tapi google_id belum ada
                $user->update([
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                ]);
                
                Auth::login($user);
                
                // Jika user belum punya role (type), lempar ke pemilihan role
                if (!$user->type) {
                    \Log::info('Redirecting to register.role');
                    return redirect()->route('register.role');
                }

                // Redirect ke dashboard sesuai role
                if ($user->isAdmin()) return redirect()->route('dashboard.admin');
                if ($user->isUMKM()) {
                    if (empty($user->bio)) {
                        return redirect()->route('profile.index')->with('info', 'Halo! Lengkapi profil bisnis Anda dulu yuk agar lebih menarik.');
                    }
                    return redirect()->route('dashboard.umkm');
                }
                
                // Jika creative worker, cek onboarding
                if ($user->isCreativeWorker()) {
                    if (!$user->onboarding_completed) {
                        return redirect()->route('onboarding');
                    }
                    return redirect()->route('dashboard.creative');
                }

                return redirect()->route('home');
            } else {
                \Log::info('New user, creating account', ['email' => $googleUser->email, 'type' => $intendedType]);
                // Buat user baru jika belum ada
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'google_token' => $googleUser->token,
                    'type' => $intendedType, // Set type jika ada
                    'password' => bcrypt(Str::random(16)),
                    'profile_photo' => $googleUser->avatar,
                ]);

                Auth::login($newUser);

                // Jika type sudah ada, langsung ke dashboard/onboarding
                if ($newUser->type) {
                    if ($newUser->isUMKM()) return redirect()->route('profile.index')->with('info', 'Selamat datang! Yuk lengkapi profil bisnis Anda agar mudah ditemukan Kreator.');
                    return redirect()->route('onboarding');
                }

                // User baru WAJIB pilih role dulu kalau belum ada type
                return redirect()->route('register.role');
            }
        } catch (\Exception $e) {
            \Log::error('Google Login Error', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    /**
     * API Google Login (untuk Mobile App)
     */
    public function apiGoogleLogin(Request $request)
    {
        try {
            // Mobile app biasanya mengirimkan access_token
            $token = $request->input('access_token');
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token is required'
                ], 400);
            }

            // Ambil user data dari Google berdasarkan token
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($token);
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Buat user baru jika belum ada
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'type' => $request->input('type'), // Optional: role dikirim dari mobile
                    'password' => bcrypt(Str::random(16)),
                    'profile_photo' => $googleUser->avatar,
                ]);
            } else {
                // Update data google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            // Generate JWT Token
            $jwtToken = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Login Google berhasil',
                'data' => [
                    'user' => [
                        'id' => (string) $user->_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type' => $user->type,
                        'profile_photo' => $user->profile_photo,
                    ],
                    'token' => $jwtToken,
                    'token_type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal login Google: ' . $e->getMessage()
            ], 500);
        }
    }
}
