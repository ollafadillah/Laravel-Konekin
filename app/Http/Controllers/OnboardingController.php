<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isCreativeWorker() || $user->onboarding_completed) {
            return redirect()->route('dashboard.creative');
        }

        return view('auth.onboarding', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'creative_category' => 'required|string|in:Graphic Designer,Web Developer,Video Editor,Content Creator,Social Media',
            'skills' => 'required|array|min:1',
            'bio' => 'required|string|min:20|max:500',
        ]);

        $user->update([
            'creative_category' => $validated['creative_category'],
            'skills' => $validated['skills'],
            'bio' => $validated['bio'],
            'onboarding_completed' => true,
        ]);

        return redirect()->route('dashboard.creative')->with('success', 'Profil kamu berhasil disiapkan! Selamat berkarya.');
    }

    public function apiStore(Request $request)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan Creative Worker.',
            ], 403);
        }

        if ($user->onboarding_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Onboarding sudah diselesaikan.',
            ], 400);
        }

        $validated = $request->validate([
            'creative_category' => 'required|string|in:Graphic Designer,Web Developer,Video Editor,Content Creator,Social Media',
            'skills' => 'required|array|min:1',
            'bio' => 'required|string|min:20|max:500',
        ]);

        $user->update([
            'creative_category' => $validated['creative_category'],
            'skills' => $validated['skills'],
            'bio' => $validated['bio'],
            'onboarding_completed' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil kamu berhasil disiapkan! Selamat berkarya.',
            'data' => [
                'user' => [
                    'id' => (string) $user->id,
                    'creative_category' => $user->creative_category,
                    'skills' => $user->skills,
                    'bio' => $user->bio,
                    'onboarding_completed' => (bool) $user->onboarding_completed,
                ]
            ]
        ], 200);
    }
}
