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
}
