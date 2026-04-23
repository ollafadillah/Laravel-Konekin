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
}
