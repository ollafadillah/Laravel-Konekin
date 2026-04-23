<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil creative worker
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Akses hanya untuk Creative Worker.');
        }

        return view('profile.creative.profile', compact('user'));
    }

    /**
     * Update data profil
     */
    public function update(Request $request)
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
                
                // Coba upload dengan cara paling sederhana dulu untuk memastikan koneksi aman
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'konekin/profiles',
                ]);
                
                $data['profile_photo'] = $uploadedFile->getSecurePath();
            } catch (\Exception $e) {
                Log::error('Cloudinary Error: ' . $e->getMessage());
                return back()->with('error', 'Gagal upload ke Cloudinary: ' . $e->getMessage());
            }
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
