<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function creativeWorkerDashboard()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $latestProjects = Project::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $latestProjects = $latestProjects->map(function ($project) {
            $applicationsCount = (int) ($project->applications_count ?? 0);
            $progress = $applicationsCount === 0 ? 0 : (int) ($project->progress_percentage ?? 0);
            $status = $project->status ?? 'open';

            if ($applicationsCount === 0) {
                $status = 'Belum Ada Apply';
            } elseif ($progress >= 100) {
                $status = 'Selesai';
            } elseif ($progress > 0) {
                $status = 'Sedang Dikerjakan';
            } else {
                $status = 'Sudah Di-apply';
            }

            $project->applications_count = $applicationsCount;
            $project->progress_percentage = $progress;
            $project->status_label = $status;

            return $project;
        });

        return view('dashboard.creative', [
            'user' => $user,
            'latestProjects' => $latestProjects,
            'activeProjectsCount' => Project::where('selected_creative_id', $user->id)->where('status', 'in_progress')->count(),
            'completedProjectsCount' => $user->completed_projects_count,
            'averageRating' => $user->average_rating,
        ]);
    }

    public function umkmDashboard()
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $projects = Project::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApplications = ProjectApplication::whereIn('project_id', $projects->pluck('id')->all())->count();
        $projectsInProgress = $projects->filter(fn ($project) => ($project->progress_percentage ?? 0) > 0)->count();

        return view('dashboard.umkm', [
            'user' => $user,
            'projects' => $projects,
            'projectsCount' => $projects->count(),
            'projectsInProgress' => $projectsInProgress,
            'totalApplications' => $totalApplications,
        ]);
    }

    public function adminDashboard()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        // Admin might want to see total stats
        $totalUsers = \App\Models\User::count();
        $totalCreativeWorkers = \App\Models\User::where('type', 'creative_worker')->count();
        $totalUMKM = \App\Models\User::where('type', 'umkm')->count();
        $totalProjects = Project::count();

        return view('dashboard.admin', [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalCreativeWorkers' => $totalCreativeWorkers,
            'totalUMKM' => $totalUMKM,
            'totalProjects' => $totalProjects,
        ]);
    }

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

    public function apiCreativeWorkerDashboard()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan Creative Worker.',
            ], 403);
        }

        $latestProjects = Project::whereNull('selected_creative_id')
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $latestProjects = $latestProjects->map(function ($project) {
            $applicationsCount = (int) ($project->applications_count ?? 0);
            $progress = $applicationsCount === 0 ? 0 : (int) ($project->progress_percentage ?? 0);
            $status = $project->status ?? 'open';

            if ($applicationsCount === 0) {
                $status = 'Belum Ada Apply';
            } elseif ($progress >= 100) {
                $status = 'Selesai';
            } elseif ($progress > 0) {
                $status = 'Sedang Dikerjakan';
            } else {
                $status = 'Sudah Di-apply';
            }

            $project->applications_count = $applicationsCount;
            $project->progress_percentage = $progress;
            $project->status_label = $status;

            return [
                'id' => (string) $project->id,
                'title' => $project->title,
                'category' => $project->category,
                'budget' => $project->budget,
                'deadline' => $project->deadline,
                'status_label' => $status,
                'thumbnail' => $project->thumbnail,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'user' => [
                    'id' => (string) $user->id,
                    'name' => $user->name,
                    'type' => $user->type,
                    'creative_category' => $user->creative_category,
                    'onboarding_completed' => (bool) $user->onboarding_completed,
                    'profile_photo' => $user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
                ],
                'stats' => [
                    'active_projects' => Project::where('selected_creative_id', $user->id)->where('status', 'in_progress')->count(),
                    'completed_projects' => (int) $user->completed_projects_count,
                    'average_rating' => (float) $user->average_rating,
                ],
                'latest_projects' => $latestProjects,
            ]
        ], 200);
    }

    public function apiUpdateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        $user->update(array_filter($validated));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'data' => [
                'user' => [
                    'id' => (string) $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'bio' => $user->bio,
                ]
            ]
        ], 200);
    }

    public function apiUMKMDashboard()
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan UMKM.',
            ], 403);
        }

        $projects = Project::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApplications = ProjectApplication::whereIn('project_id', $projects->pluck('id')->all())->count();
        $projectsInProgress = $projects->filter(fn ($project) => ($project->progress_percentage ?? 0) > 0 && ($project->progress_percentage ?? 0) < 100)->count();

        return response()->json([
            'success' => true,
            'message' => 'UMKM Dashboard data retrieved successfully',
            'data' => [
                'user' => [
                    'id' => (string) $user->id,
                    'name' => $user->name,
                    'type' => $user->type,
                    'profile_photo' => $user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
                ],
                'stats' => [
                    'total_projects' => $projects->count(),
                    'projects_in_progress' => $projectsInProgress,
                    'total_applications' => $totalApplications,
                ],
                'projects' => $projects->map(fn ($project) => [
                    'id' => (string) $project->id,
                    'title' => $project->title,
                    'status' => $project->status,
                    'progress' => (int) $project->progress_percentage,
                    'applications_count' => (int) $project->applications_count,
                    'thumbnail' => $project->thumbnail,
                    'created_at' => $project->created_at->toISOString(),
                ]),
            ]
        ], 200);
    }
}
