<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\EscrowTransaction;
use App\Models\ProjectHistory;
use App\Models\Rating;
use App\Models\User;
use App\Support\CreativeRoles;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $ratingsCount = $user->ratings_count;
        $averageRating = $user->average_rating;
        $recentRatings = $user->recentRatings(5)->get();

        $invitations = Project::where('selected_creative_id', $user->id)
            ->where('status', 'waiting_confirmation')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.creative', [
            'user' => $user,
            'latestProjects' => $latestProjects,
            'invitations' => $invitations,
            'activeProjectsCount' => Project::where('selected_creative_id', $user->id)->where('status', 'in_progress')->count(),
            'completedProjectsCount' => $user->completed_projects_count,
            'averageRating' => $averageRating,
            'ratingsCount' => $ratingsCount,
            'recentRatings' => $recentRatings,
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
        $latestRecommendationResult = session('latest_recommendation_result') ?? $user->last_ai_recommendation;
        $recommendedCreators = collect(data_get($latestRecommendationResult, 'recommendations', []))
            ->take(2)
            ->values();

        return view('dashboard.umkm', [
            'user' => $user,
            'projects' => $projects,
            'projectsCount' => $projects->count(),
            'projectsInProgress' => $projectsInProgress,
            'totalApplications' => $totalApplications,
            'latestRecommendationResult' => $latestRecommendationResult,
            'recommendedCreators' => $recommendedCreators,
        ]);
    }

    public function adminDashboard()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $totalUsers = User::where('type', '!=', 'admin')->count();
        $totalCreativeWorkers = User::where('type', 'creative_worker')->count();
        $totalUMKM = User::where('type', 'umkm')->count();
        $totalProjects = Project::count();
        $activeProjects = Project::whereIn('status', ['hired', 'in_progress', 'pending_admin_approval'])->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $newUsersThisWeek = User::where('type', '!=', 'admin')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $pendingVerificationCount = EscrowTransaction::where('status', 'pending')->count();
        $pendingVerificationAmount = EscrowTransaction::where('status', 'pending')->sum('amount');
        $heldEscrowCount = EscrowTransaction::where('status', 'held')->count();
        $heldEscrowAmount = EscrowTransaction::where('status', 'held')->sum('net_amount');
        $releasingEscrowCount = EscrowTransaction::where('status', 'releasing')->count();
        $disputeCount = EscrowTransaction::where('status', 'disputed')->count();

        $pendingApprovalEscrows = EscrowTransaction::where('status', 'held')
            ->with(['project'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->filter(fn ($escrow) => ($escrow->project->status ?? null) === 'pending_admin_approval');

        $pendingApprovalCount = $pendingApprovalEscrows->count();
        $pendingApprovalAmount = $pendingApprovalEscrows->sum(fn ($escrow) => (int) ($escrow->net_amount ?? 0));

        $recentUsers = User::where('type', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $recentProjects = Project::orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $recentEscrows = EscrowTransaction::with(['project', 'payer', 'payee'])
            ->orderBy('updated_at', 'desc')
            ->limit(4)
            ->get();

        $recentActivities = collect()
            ->merge($recentUsers->map(fn ($recentUser) => (object) [
                'icon' => 'fa-user-plus',
                'tone' => 'blue',
                'title' => 'User baru mendaftar',
                'description' => $recentUser->name . ' bergabung sebagai ' . ($recentUser->type === 'umkm' ? 'UMKM' : 'Creative Worker'),
                'date' => $recentUser->created_at,
            ]))
            ->merge($recentProjects->map(fn ($project) => (object) [
                'icon' => 'fa-folder-plus',
                'tone' => 'emerald',
                'title' => 'Proyek baru dibuat',
                'description' => $project->title ?? 'Proyek tanpa judul',
                'date' => $project->created_at,
            ]))
            ->merge($recentEscrows->map(fn ($escrow) => (object) [
                'icon' => match ($escrow->status) {
                    'pending' => 'fa-receipt',
                    'held' => 'fa-lock',
                    'releasing' => 'fa-money-bill-transfer',
                    'released' => 'fa-circle-check',
                    'disputed' => 'fa-scale-balanced',
                    default => 'fa-shield-halved',
                },
                'tone' => match ($escrow->status) {
                    'pending' => 'amber',
                    'held' => 'indigo',
                    'releasing' => 'sky',
                    'released' => 'emerald',
                    'disputed' => 'red',
                    default => 'slate',
                },
                'title' => 'Update escrow',
                'description' => ($escrow->project->title ?? 'Transaksi escrow') . ' berstatus ' . ucfirst((string) $escrow->status),
                'date' => $escrow->updated_at ?? $escrow->created_at,
            ]))
            ->filter(fn ($activity) => !empty($activity->date))
            ->sortByDesc(fn ($activity) => $activity->date->timestamp ?? 0)
            ->take(6)
            ->values();

        return view('dashboard.admin', [
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalCreativeWorkers' => $totalCreativeWorkers,
            'totalUMKM' => $totalUMKM,
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'completedProjects' => $completedProjects,
            'newUsersThisWeek' => $newUsersThisWeek,
            'pendingVerificationCount' => $pendingVerificationCount,
            'pendingVerificationAmount' => $pendingVerificationAmount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'pendingApprovalAmount' => $pendingApprovalAmount,
            'heldEscrowCount' => $heldEscrowCount,
            'heldEscrowAmount' => $heldEscrowAmount,
            'releasingEscrowCount' => $releasingEscrowCount,
            'disputeCount' => $disputeCount,
            'recentUsers' => $recentUsers,
            'recentProjects' => $recentProjects,
            'recentActivities' => $recentActivities,
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
            'creative_category' => [
                Rule::requiredIf(fn () => $user->isCreativeWorker()),
                'nullable',
                'string',
                Rule::in(CreativeRoles::allChoices()),
            ],
        ]);

        if ($user->isCreativeWorker()) {
            $validated['creative_category'] = CreativeRoles::normalize($validated['creative_category'] ?? null);
            $validated['onboarding_completed'] = true;
        }

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

    public function creativeEarnings()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Hanya creative worker yang dapat melihat penghasilan.');
        }

        $transactions = EscrowTransaction::where('payee_id', $user->id)
            ->with(['project', 'payer'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEarned = $transactions->where('status', 'released')->sum('net_amount');
        $pendingApproval = $transactions->where('status', 'held')->sum('net_amount');
        $inDisbursement = $transactions->where('status', 'releasing')->sum('net_amount');
        $releasedCount = $transactions->where('status', 'released')->count();

        return view('earnings.index', compact(
            'transactions',
            'totalEarned',
            'pendingApproval',
            'inDisbursement',
            'releasedCount'
        ));
    }

    public function creativeHistory()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Hanya creative worker yang dapat melihat riwayat proyek.');
        }

        $histories = ProjectHistory::where('selected_creative_id', (string) $user->id)
            ->where('history_type', 'completed')
            ->orderBy('archived_at', 'desc')
            ->get();

        $ratings = Rating::where('to_user_id', (string) $user->id)
            ->with(['fromUser:id,name,profile_photo', 'project:id,title,category,budget,thumbnail,deadline,client_name,client_avatar'])
            ->orderBy('created_at', 'desc')
            ->get();

        $historyItems = $ratings->map(function ($rating) use ($histories) {
            $projectId = (string) ($rating->project_id ?? '');
            $history = $histories->firstWhere('original_project_id', $projectId);
            $project = $rating->project;

            return (object) [
                'id' => (string) $rating->id,
                'project_id' => $projectId,
                'title' => $history->title ?? optional($project)->title ?? $rating->project_title_snapshot ?? 'Proyek diarsipkan',
                'category' => $history->category ?? optional($project)->category ?? 'Creative Project',
                'budget' => $history->budget ?? optional($project)->budget,
                'thumbnail' => $history->thumbnail ?? optional($project)->thumbnail ?? 'https://images.unsplash.com/photo-1556761175-b413da4baf72?q=80&w=1200&auto=format&fit=crop',
                'client_name' => $history->client_name ?? optional($rating->fromUser)->name ?? optional($project)->client_name ?? 'UMKM',
                'client_avatar' => $history->client_avatar ?? optional($rating->fromUser)->profile_photo ?? optional($project)->client_avatar,
                'rating' => (int) ($rating->rating ?? $history->rating ?? 0),
                'comment' => $rating->comment ?? $history->comment,
                'rated_at' => $rating->created_at ?? $history->rated_at,
                'archived_at' => optional($history)->archived_at,
                'deadline' => optional($history)->deadline ?? optional($project)->deadline,
                'source' => $history ? 'history' : 'rating',
            ];
        });

        $ratedProjectIds = $ratings
            ->pluck('project_id')
            ->map(fn ($id) => (string) $id)
            ->filter()
            ->values();

        $historyOnlyItems = $histories
            ->reject(fn ($history) => $ratedProjectIds->contains((string) ($history->original_project_id ?? '')))
            ->map(fn ($history) => (object) [
                'id' => (string) $history->id,
                'project_id' => (string) ($history->original_project_id ?? ''),
                'title' => $history->title,
                'category' => $history->category ?? 'Creative Project',
                'budget' => $history->budget,
                'thumbnail' => $history->thumbnail ?? 'https://images.unsplash.com/photo-1556761175-b413da4baf72?q=80&w=1200&auto=format&fit=crop',
                'client_name' => $history->client_name ?? 'UMKM',
                'client_avatar' => $history->client_avatar,
                'rating' => (int) ($history->rating ?? 0),
                'comment' => $history->comment,
                'rated_at' => $history->rated_at,
                'archived_at' => $history->archived_at,
                'deadline' => $history->deadline,
                'source' => 'history',
            ]);

        $historyItems = $historyItems
            ->concat($historyOnlyItems)
            ->sortByDesc(fn ($item) => optional($item->rated_at ?? $item->archived_at)->timestamp ?? 0)
            ->values();

        $ratingsCount = $ratings->count();
        $averageRating = $ratingsCount > 0
            ? round((float) $ratings->avg(fn ($rating) => (float) ($rating->rating ?? 0)), 1)
            : 0;
        $fiveStarCount = $ratings->where('rating', 5)->count();
        $completedCount = $histories->count();

        return view('projects.history-creative', compact(
            'user',
            'historyItems',
            'ratingsCount',
            'averageRating',
            'fiveStarCount',
            'completedCount'
        ));
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
            'creative_category' => [
                Rule::requiredIf(fn () => $user->isCreativeWorker()),
                'nullable',
                'string',
                Rule::in(CreativeRoles::allChoices()),
            ],
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
        ]);

        if ($user->isCreativeWorker()) {
            $validated['creative_category'] = CreativeRoles::normalize($validated['creative_category'] ?? null);
            $validated['onboarding_completed'] = true;
        }

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
                    'bank_name' => $user->bank_name,
                    'bank_account_number' => $user->bank_account_number,
                    'bank_account_name' => $user->bank_account_name,
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
