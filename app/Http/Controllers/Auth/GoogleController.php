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
            
            // Normalisasi email ke lowercase untuk konsistensi lookup
            $email = strtolower($googleUser->email);
            
            \Log::info('Google Login Attempt', [
                'email' => $email, 
                'intended_type' => $intendedType,
                'google_id' => $googleUser->id
            ]);

            // 1. Cari user berdasarkan google_id atau email
            // Menggunakan email lowercase untuk menghindari isu case-sensitivity
            $user = User::where('google_id', (string)$googleUser->id)
                        ->orWhere('email', $email)
                        ->first();

            if ($user) {
                \Log::info('User found, logging in', [
                    'id' => $user->id,
                    'type' => $user->type
                ]);
                
                // Update data Google (selalu sinkronkan token terbaru)
                // Dan update google_id jika sebelumnya login via email saja
                $user->update([
                    'google_id' => (string)$googleUser->id,
                    'google_token' => $googleUser->token,
                    // Opsional: perbarui foto profil jika dari google lebih baru
                    'profile_photo' => $googleUser->avatar ?: $user->profile_photo,
                ]);
                
                // Jika user sudah ada tapi belum punya type, dan ada intendedType dari session
                if (!$user->type && $intendedType) {
                    $user->update(['type' => $intendedType]);
                }
                
                Auth::login($user, true); // Gunakan remember me agar session lebih awet
                
            } else {
                \Log::info('New user, creating account', ['email' => $email, 'type' => $intendedType]);
                
                // 2. Buat user baru jika benar-benar belum ada
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $email,
                    'google_id' => (string)$googleUser->id,
                    'google_token' => $googleUser->token,
                    'type' => $intendedType, // Set type jika ada (dari proses registrasi)
                    'password' => bcrypt(Str::random(16)),
                    'profile_photo' => $googleUser->avatar,
                ]);

                Auth::login($user, true);
            }

            // --- Redirection Logic Berdasarkan Role ---

            // Jika user baru/lama tapi belum punya role (type), WAJIB pilih role dulu
            if (!$user->type) {
                \Log::info('No type found for user, redirecting to register.role');
                return redirect()->route('register.role')->with('info', 'Selamat datang! Silakan pilih peran Anda untuk melanjutkan.');
            }

            // Redirect ke dashboard/onboarding sesuai role
            if ($user->isAdmin()) return redirect()->route('dashboard.admin');
            
            if ($user->isUMKM()) {
                // Untuk UMKM, jika bio kosong dianggap belum melengkapi profil minimal
                if (empty($user->bio)) {
                    return redirect()->route('profile.index')->with('info', 'Selamat datang! Yuk lengkapi profil bisnis Anda agar lebih menarik bagi Kreator.');
                }
                return redirect()->route('dashboard.umkm');
            }
            
            if ($user->isCreativeWorker()) {
                // Untuk Creative Worker, cek status onboarding
                if (!$user->onboarding_completed) {
                    return redirect()->route('onboarding');
                }
                return redirect()->route('dashboard.creative');
            }

            return redirect()->route('home');

        } catch (\Exception $e) {
            \Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba beberapa saat lagi.');
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
