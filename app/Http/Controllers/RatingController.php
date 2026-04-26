<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Project;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        // Ensure only UMKM (client) can rate
        if (auth()->id() !== $project->client_id) {
            return back()->with('error', 'Hanya UMKM pemilik proyek yang dapat memberikan rating.');
        }

        // Ensure project is completed
        if ($project->status !== 'completed') {
            return back()->with('error', 'Rating hanya bisa diberikan setelah proyek selesai.');
        }

        // Ensure there is a creative worker selected
        if (empty($project->selected_creative_id)) {
            return back()->with('error', 'Tidak ada creative worker yang mengerjakan proyek ini.');
        }

        // Check if already rated
        $existingRating = Rating::where('project_id', $project->id)->first();
        if ($existingRating) {
            return back()->with('error', 'Kamu sudah memberikan rating untuk proyek ini.');
        }

        Rating::create([
            'project_id' => $project->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $project->selected_creative_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Terima kasih atas ratingnya!');
    }

    public function apiIndex(Request $request)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya creative worker yang dapat melihat rating mereka.',
            ], 403);
        }

        $ratings = Rating::where('to_user_id', $user->id)
            ->with(['fromUser:id,name,profile_photo', 'project:id,title'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($rating) {
                return [
                    'id' => (string) $rating->id,
                    'rating' => (int) $rating->rating,
                    'comment' => $rating->comment,
                    'from_user_name' => $rating->fromUser->name,
                    'from_user_avatar' => $rating->fromUser->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($rating->fromUser->name) . '&background=random',
                    'project_title' => $rating->project->title,
                    'created_at' => $rating->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Daftar rating berhasil diambil',
            'data' => $ratings,
        ], 200);
    }

    public function apiStore(Request $request)
    {
        $user = auth()->user();

        try {
            $validated = $request->validate([
                'project_id' => 'required|string',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
            ]);

            $project = Project::findOrFail($validated['project_id']);

            if ((string) $user->id !== (string) $project->client_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya UMKM pemilik proyek yang dapat memberikan rating.',
                ], 403);
            }

            if ($project->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating hanya bisa diberikan setelah proyek selesai.',
                ], 400);
            }

            $existingRating = Rating::where('project_id', $project->id)->first();
            if ($existingRating) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah memberikan rating untuk proyek ini.',
                ], 400);
            }

            $rating = Rating::create([
                'project_id' => $project->id,
                'from_user_id' => $user->id,
                'to_user_id' => $project->selected_creative_id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas ratingnya!',
                'data' => [
                    'id' => (string) $rating->id,
                    'rating' => (int) $rating->rating,
                    'comment' => $rating->comment,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memberikan rating: ' . $e->getMessage(),
            ], 500);
        }
    }
}
