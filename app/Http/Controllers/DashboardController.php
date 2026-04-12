<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Creative Worker
     */
    public function creativeWorkerDashboard()
    {
        $user = auth()->user();
        
        // Pastikan hanya creative worker yang bisa akses
        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        return view('dashboard.creative', [
            'user' => $user,
        ]);
    }

    /**
     * Dashboard UMKM
     */
    public function umkmDashboard()
    {
        $user = auth()->user();
        
        // Pastikan hanya UMKM yang bisa akses
        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        return view('dashboard.umkm', [
            'user' => $user,
        ]);
    }

    /**
     * Update profil
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        $routeName = $user->isCreativeWorker() ? 'dashboard.creative' : 'dashboard.umkm';
        
        return redirect()->route($routeName)->with('success', 'Profil berhasil diperbarui!');
    }
}
