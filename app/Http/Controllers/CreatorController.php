<?php

namespace App\Http\Controllers;

use App\Support\CreativeRoles;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreatorController extends Controller
{
    public function index()
    {
        $activeRole = request('role', request('skill', 'Semua'));
        $search = trim((string) request('search', ''));

        // Untuk sementara, kita ambil semua user yang tipenya 'creative_worker'
        $creators = User::where('type', 'creative_worker')->get();

        if ($activeRole !== 'Semua') {
            $creators = $creators->filter(function ($creator) use ($activeRole) {
                return CreativeRoles::hasRole($creator->creative_category, $activeRole);
            });
        }

        if ($search !== '') {
            $searchLower = Str::lower($search);

            $creators = $creators->filter(function ($creator) use ($searchLower) {
                $haystack = Str::lower(implode(' ', array_filter([
                    $creator->name,
                    $creator->city,
                    $creator->bio,
                    CreativeRoles::display($creator->creative_category),
                    implode(' ', Arr::wrap($creator->skills)),
                    implode(' ', CreativeRoles::aliases($creator->creative_category)),
                ])));

                return Str::contains($haystack, $searchLower);
            });
        }

        $creators = $creators->map(function ($creator) {
            $normalizedRole = CreativeRoles::display($creator->creative_category);
            $roleSkills = Arr::wrap($creator->skills);

            if (empty($roleSkills)) {
                $roleSkills = CreativeRoles::skillSuggestions($creator->creative_category);
            }

            $creator->display_creative_category = $normalizedRole;
            $creator->role_skills_preview = array_slice(array_values(array_filter($roleSkills)), 0, 3);

            return $creator;
        })->values();

        return view('creators.index', [
            'creators' => $creators,
            'creativeRoleOptions' => CreativeRoles::options(),
            'activeRole' => $activeRole,
        ]);
    }

    public function show(string $id)
    {
        $creator = User::where('type', 'creative_worker')->findOrFail($id);
        $creator->display_creative_category = CreativeRoles::display($creator->creative_category);
        $creator->role_skills_preview = array_slice(array_values(array_filter(Arr::wrap($creator->skills) ?: CreativeRoles::skillSuggestions($creator->creative_category))), 0, 3);

        $portfolios = Portfolio::where('user_id', $creator->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $recentRatings = $creator->recentRatings(3)->get();

        return view('creators.show', compact('creator', 'portfolios', 'recentRatings'));
    }
}
