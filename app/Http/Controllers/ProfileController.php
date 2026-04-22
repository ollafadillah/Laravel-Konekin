<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil sesuai role user
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->isCreativeWorker() && !$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Role akun tidak dikenali.');
        }

        $view = $user->isUMKM()
            ? 'profile.umkm.profile'
            : 'profile.creative.profile';

        return view($view, compact('user'));
    }

    /**
     * Update data profil
     */
    public function update(Request $request, CloudinaryService $cloudinary)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'phone', 'address', 'city', 'bio']);

        // Handle upload ke Cloudinary jika ada foto baru
        if ($request->hasFile('profile_photo')) {
            try {
                $file = $request->file('profile_photo');

                $data['profile_photo'] = $cloudinary->upload($file, [
                    'folder' => 'konekin/profiles',
                    'resource_type' => 'image',
                ]);
            } catch (\Exception $e) {
                Log::error('Cloudinary Error: ' . $e->getMessage());
                return back()->with('error', 'Gagal upload ke Cloudinary: ' . $e->getMessage());
            }
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function apiShow(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diambil',
                'data' => $this->transformUser($request->user()),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiUpdate(Request $request, CloudinaryService $cloudinary)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'bio' => 'nullable|string|max:1000',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_photo')) {
                $validated['profile_photo'] = $cloudinary->upload($request->file('profile_photo'), [
                    'folder' => 'konekin/profiles',
                    'resource_type' => 'image',
                ]);
            }

            $request->user()->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $this->transformUser($request->user()->fresh()),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Profile API Update Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function transformUser($user): array
    {
        return [
            'id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'type' => $user->type,
            'phone' => $user->phone,
            'address' => $user->address,
            'city' => $user->city,
            'bio' => $user->bio,
            'profile_photo' => $user->profile_photo,
            'created_at' => optional($user->created_at)?->toISOString(),
            'updated_at' => optional($user->updated_at)?->toISOString(),
        ];
    }
}
