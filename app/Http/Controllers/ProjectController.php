<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\ProjectHistory;
use App\Models\ProjectProgressUpdate;
use App\Notifications\ProjectApplicationApproved;
use App\Services\CloudinaryService;
use App\Services\ProjectArchiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function publicIndex(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Only show projects that are not completed and don't have a selected creative worker yet
        // And project deadline must be in the future
        $query->where('status', '!=', 'completed')
              ->whereNull('selected_creative_id')
              ->where('deadline', '>=', now()->startOfDay()->format('Y-m-d'));

        $projects = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($project) => $this->decorateProject($project));

        return view('projects.public-index', compact('projects'));
    }

    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Only show projects that are not completed and don't have a selected creative worker yet
        // And project deadline must be in the future
        $query->where('status', '!=', 'completed')
              ->whereNull('selected_creative_id')
              ->where('deadline', '>=', now()->startOfDay()->format('Y-m-d'));

        $projects = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($project) => $this->decorateProject($project));

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        if (!auth()->user()->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat membuat proyek.');
        }

        return view('projects.create');
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        if (!auth()->user()->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat membuat proyek.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'budget' => 'required|string|max:100',
            'deadline' => 'required|date|after_or_equal:today',
            'description' => 'required|string|max:2000',
            'requirements' => 'nullable|string|max:2000',
            'project_media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,webm|max:20480',
        ]);

        $project = new Project($request->only([
            'title',
            'category',
            'budget',
            'deadline',
            'description',
            'requirements',
        ]));

        $project->client_id = auth()->id();
        $project->client_name = auth()->user()->name;
        $project->client_avatar = auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random';
        $project->status = 'open';
        $project->progress_percentage = 0;
        $project->applications_count = 0;
        $project->thumbnail = 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop';

        if ($request->hasFile('project_media')) {
            try {
                $file = $request->file('project_media');
                $mimeType = $file->getMimeType() ?? '';
                $resourceType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                $mediaUrl = $cloudinary->upload($file, [
                    'folder' => 'konekin/projects/media',
                    'resource_type' => $resourceType,
                ]);

                $project->media_url = $mediaUrl;
                $project->media_type = $resourceType;

                if ($resourceType === 'image') {
                    $project->thumbnail = $mediaUrl;
                }
            } catch (\Exception $e) {
                Log::error('Project Media Upload Error: ' . $e->getMessage());

                return back()
                    ->withInput()
                    ->with('error', 'Media proyek gagal di-upload: ' . $e->getMessage());
            }
        }

        $project->save();

        return redirect()->route('projects.progress')->with('success', 'Proyek berhasil dipublikasikan!');
    }

    public function show(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            // Handle dummy projects so it doesn't throw 404 when clicked
            $dummyProject = collect($this->getDummyProjects())->firstWhere('id', (int) $id);
            
            if ($dummyProject) {
                $project = $this->decorateProject($dummyProject);
                $applications = collect();
                $progressUpdates = collect();
                return view('projects.show', compact('project', 'applications', 'progressUpdates'));
            }
            
            abort(404);
        }

        $project = $this->decorateProject($project);
        $applications = ProjectApplication::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $progressUpdates = ProjectProgressUpdate::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.show', compact('project', 'applications', 'progressUpdates'));
    }

    public function apply(Request $request, string $id, CloudinaryService $cloudinary)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('projects.show', $id)->with('error', 'Hanya creative worker yang dapat mengajukan proyek.');
        }

        $project = Project::findOrFail($id);

        if (!empty($project->selected_creative_id) && (string) $project->selected_creative_id !== (string) $user->id) {
            return back()->with('error', 'UMKM sudah memilih creative worker lain untuk proyek ini.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:20|max:1000',
            'proposal_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
        ], [
            'message.required' => 'Deskripsi pengajuan wajib diisi sebelum mengajukan ke UMKM.',
            'message.min' => 'Deskripsi pengajuan minimal 20 karakter ya.',
            'proposal_file.required' => 'File proposal wajib di-upload sebelum mengajukan ke UMKM.',
        ]);

        $existingApplication = ProjectApplication::where('project_id', $project->id)
            ->where('creative_id', $user->id)
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'Kamu sudah mengajukan diri ke proyek ini.');
        }

        try {
            $proposalFile = $request->file('proposal_file');
            $proposalType = $proposalFile->getClientOriginalExtension();
            $proposalOriginalName = $proposalFile->getClientOriginalName();
            $proposalMimeType = $proposalFile->getClientMimeType();
            $proposalUrl = $cloudinary->uploadDocument($proposalFile, 'konekin/projects/proposals');
        } catch (\Exception $e) {
            Log::error('Project Proposal Upload Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Proposal gagal di-upload: ' . $e->getMessage());
        }

        ProjectApplication::create([
            'project_id' => $project->id,
            'creative_id' => $user->id,
            'creative_name' => $user->name,
            'creative_avatar' => $user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
            'creative_city' => $user->city,
            'message' => $validated['message'],
            'proposal_url' => $proposalUrl,
            'proposal_type' => $proposalType,
            'proposal_original_name' => $proposalOriginalName,
            'proposal_mime_type' => $proposalMimeType,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        $applicationsCount = ProjectApplication::where('project_id', $project->id)->count();

        $project->applications_count = $applicationsCount;
        $project->status = $project->progress_percentage >= 100 ? 'completed' : 'applied';
        $project->progress_percentage = max((int) ($project->progress_percentage ?? 0), 0);
        $project->save();

        return back()->with('success', 'Pengajuan berhasil dikirim ke UMKM.');
    }

    public function previewProposal(string $applicationId, CloudinaryService $cloudinary)
    {
        $application = ProjectApplication::findOrFail($applicationId);
        $this->ensureProposalCanBeOpened($application);

        $extension = strtolower($application->proposal_type ?: pathinfo($application->proposal_display_name, PATHINFO_EXTENSION));

        if ($extension === 'pdf') {
            return $this->serveProposalFile($application, false, $cloudinary);
        }

        return view('projects.proposal-preview', [
            'application' => $application,
            'extension' => $extension,
            'canUseDocumentViewer' => in_array($extension, ['doc', 'docx', 'ppt', 'pptx'], true),
        ]);
    }

    public function downloadProposal(string $applicationId, CloudinaryService $cloudinary)
    {
        $application = ProjectApplication::findOrFail($applicationId);
        $this->ensureProposalCanBeOpened($application);

        return $this->serveProposalFile($application, true, $cloudinary);
    }

    public function progress(ProjectArchiveService $archiveService)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat melihat progress proyek.');
        }

        $archiveService->syncCompletedProjectsForClient($user->id);

        $projects = Project::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) {
                $project->applications = ProjectApplication::where('project_id', $project->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $project->progress_updates = ProjectProgressUpdate::where('project_id', $project->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $project->is_rated = \App\Models\Rating::where('project_id', $project->id)->exists();
                $project->payment = \App\Models\Payment::where('project_id', $project->id)
                    ->whereIn('status', ['pending', 'paid'])
                    ->orderBy('created_at', 'desc')
                    ->first();
                $project->escrow = \App\Models\EscrowTransaction::where('project_id', $project->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                return $this->decorateProject($project, $project->applications->count());
            });

        $historyProjects = ProjectHistory::where('client_id', $user->id)
            ->orderBy('archived_at', 'desc')
            ->get();

        return view('projects.progress', compact('projects', 'historyProjects'));
    }

    public function destroyProgressProject(string $id, ProjectArchiveService $archiveService)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat menghapus proyek.');
        }

        $project = Project::where('client_id', $user->id)->findOrFail($id);

        $applicationsCount = ProjectApplication::where('project_id', $project->id)->count();
        if ($applicationsCount > 0 || !empty($project->selected_creative_id)) {
            return back()->with('error', 'Proyek ini sudah menerima apply atau sedang berjalan, jadi tidak bisa dihapus langsung.');
        }

        $archiveService->deleteUnfinishedProject($project);

        return redirect()
            ->route('projects.progress')
            ->with('success', 'Proyek berhasil dihapus setelah persetujuan UMKM.');
    }

    public function approveApplication(string $id, string $applicationId)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat menyetujui pelamar proyek.');
        }

        $project = Project::where('client_id', $user->id)->findOrFail($id);
        $application = ProjectApplication::where('project_id', $project->id)->findOrFail($applicationId);

        ProjectApplication::where('project_id', $project->id)
            ->where('status', 'approved')
            ->update([
                'status' => 'applied',
                'approved_at' => null,
            ]);

        $application->status = 'approved';
        $application->approved_at = now();
        $application->save();

        if ($creativeWorker = $application->creative) {
            $creativeWorker->notify(new ProjectApplicationApproved($project, $application));
        }

        $project->selected_creative_id = $application->creative_id;
        $project->selected_creative_name = $application->creative_name;
        $project->selected_creative_avatar = $application->creative_avatar;
        $project->applications_count = ProjectApplication::where('project_id', $project->id)->count();
        $project->status = 'hired';
        $project->escrow_status = 'unpaid';
        $project->save();

        return redirect()->to(route('projects.progress') . '#project-' . $project->id)
            ->with('success', 'Creative worker disetujui. Pekerjaan bisa dimulai, dan pembayaran escrow akan diminta setelah draft/progress 100% dikirim.');
    }

    public function creativeProgress()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Hanya creative worker yang dapat melihat progress proyek ini.');
        }

        $projects = Project::where('selected_creative_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) use ($user) {
                $project->progress_updates = ProjectProgressUpdate::where('project_id', $project->id)
                    ->where('creative_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                return $this->decorateProject($project);
            });

        return view('projects.progress-creative', compact('projects'));
    }

    public function storeCreativeProgress(Request $request, string $id, CloudinaryService $cloudinary)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return redirect()->route('home')->with('error', 'Hanya creative worker yang dapat memperbarui progress proyek.');
        }

        $project = Project::where('selected_creative_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'note' => 'required|string|min:10|max:1500',
            'progress_media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,webm|max:20480',
        ], [
            'note.required' => 'Catatan progress wajib diisi ya.',
            'note.min' => 'Catatan progress minimal 10 karakter.',
        ]);

        $mediaUrl = null;
        $mediaType = null;

        if ($request->hasFile('progress_media')) {
            try {
                $file = $request->file('progress_media');
                $mimeType = $file->getMimeType() ?? '';
                $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                $mediaUrl = $cloudinary->upload($file, [
                    'folder' => 'konekin/projects/progress',
                    'resource_type' => $mediaType,
                ]);
            } catch (\Exception $e) {
                Log::error('Project Progress Media Upload Error: ' . $e->getMessage());

                return back()
                    ->withInput()
                    ->with('error', 'Media progress gagal di-upload: ' . $e->getMessage());
            }
        }

        ProjectProgressUpdate::create([
            'project_id' => $project->id,
            'creative_id' => $user->id,
            'creative_name' => $user->name,
            'note' => $validated['note'],
            'progress_percentage' => $validated['progress_percentage'],
            'media_url' => $mediaUrl,
            'media_type' => $mediaType,
        ]);

        $project->progress_percentage = $validated['progress_percentage'];
        if ((int) $validated['progress_percentage'] >= 100) {
            $project->status = ($project->escrow_status ?? 'unpaid') === 'held'
                ? 'ready_for_review'
                : 'awaiting_payment';
        } else {
            $project->status = 'in_progress';
        }
        $project->save();

        return back()->with('success', 'Progress proyek berhasil diperbarui.');
    }

    public function apiIndex(Request $request)
    {
        try {
            $query = Project::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('category') && $request->category !== 'Semua') {
                $query->where('category', $request->category);
            }

            // Only show projects that are not completed and don't have a selected creative worker yet
            // And project deadline must be in the future
            $query->where('status', '!=', 'completed')
                  ->whereNull('selected_creative_id')
                  ->where('deadline', '>=', now()->startOfDay()->format('Y-m-d'));

            $projects = $query->orderBy('created_at', 'desc')->get()
                ->map(fn ($project) => $this->decorateProject($project));

            return response()->json([
                'success' => true,
                'message' => 'Daftar proyek berhasil diambil',
                'data' => collect($projects)->map(fn ($project) => $this->transformProject($project))->values(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiShow($id)
    {
        try {
            $project = Project::find($id);

            if (!$project) {
                $dummyProject = collect($this->getDummyProjects())->firstWhere('id', (int) $id);
                if ($dummyProject) {
                    $project = $dummyProject;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Proyek tidak ditemukan',
                    ], 404);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail proyek berhasil diambil',
                'data' => $this->transformProject($project),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiApply(Request $request, $id, CloudinaryService $cloudinary)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya creative worker yang dapat mengajukan proyek.',
            ], 403);
        }

        try {
            $project = Project::findOrFail($id);

            if (!empty($project->selected_creative_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'UMKM sudah memilih creative worker lain untuk proyek ini.',
                ], 400);
            }

            $validated = $request->validate([
                'message' => 'required|string|min:20|max:1000',
                'proposal_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480',
            ]);

            $existingApplication = ProjectApplication::where('project_id', $project->id)
                ->where('creative_id', $user->id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah mengajukan diri ke proyek ini.',
                ], 400);
            }

            $proposalFile = $request->file('proposal_file');
            $proposalType = $proposalFile->getClientOriginalExtension();
            $proposalOriginalName = $proposalFile->getClientOriginalName();
            $proposalMimeType = $proposalFile->getClientMimeType();
            $proposalUrl = $cloudinary->uploadDocument($proposalFile, 'konekin/projects/proposals');

            ProjectApplication::create([
                'project_id' => $project->id,
                'creative_id' => $user->id,
                'creative_name' => $user->name,
                'creative_avatar' => $user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
                'creative_city' => $user->city,
                'message' => $validated['message'],
                'proposal_url' => $proposalUrl,
                'proposal_type' => $proposalType,
                'proposal_original_name' => $proposalOriginalName,
                'proposal_mime_type' => $proposalMimeType,
                'status' => 'applied',
                'applied_at' => now(),
            ]);

            $project->applications_count = ProjectApplication::where('project_id', $project->id)->count();
            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim ke UMKM.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiCreativeProgress()
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya creative worker yang dapat melihat progress proyek.',
            ], 403);
        }

        $projects = Project::where('selected_creative_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) use ($user) {
                $project->progress_updates = ProjectProgressUpdate::where('project_id', $project->id)
                    ->where('creative_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                return $this->transformProject($project);
            });

        return response()->json([
            'success' => true,
            'message' => 'Daftar progress proyek berhasil diambil',
            'data' => $projects,
        ], 200);
    }

    public function apiStoreCreativeProgress(Request $request, $id, CloudinaryService $cloudinary)
    {
        $user = auth()->user();

        if (!$user->isCreativeWorker()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya creative worker yang dapat memperbarui progress proyek.',
            ], 403);
        }

        try {
            $project = Project::where('selected_creative_id', $user->id)->findOrFail($id);

            $validated = $request->validate([
                'progress_percentage' => 'required|integer|min:0|max:100',
                'note' => 'required|string|min:10|max:1500',
                'progress_media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,webm|max:20480',
            ]);

            $mediaUrl = null;
            $mediaType = null;

            if ($request->hasFile('progress_media')) {
                $file = $request->file('progress_media');
                $mimeType = $file->getMimeType() ?? '';
                $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                $mediaUrl = $cloudinary->upload($file, [
                    'folder' => 'konekin/projects/progress',
                    'resource_type' => $mediaType,
                ]);
            }

            ProjectProgressUpdate::create([
                'project_id' => $project->id,
                'creative_id' => $user->id,
                'creative_name' => $user->name,
                'note' => $validated['note'],
                'progress_percentage' => $validated['progress_percentage'],
                'media_url' => $mediaUrl,
                'media_type' => $mediaType,
            ]);

            $project->progress_percentage = $validated['progress_percentage'];
            if ((int) $validated['progress_percentage'] >= 100) {
                $project->status = ($project->escrow_status ?? 'unpaid') === 'held'
                    ? 'ready_for_review'
                    : 'awaiting_payment';
            } else {
                $project->status = 'in_progress';
            }
            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Progress proyek berhasil diperbarui.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui progress: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiStore(Request $request, CloudinaryService $cloudinary)
    {
        if (!auth()->user()->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya UMKM yang dapat membuat proyek.',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'budget' => 'required|string|max:100',
                'deadline' => 'required|date|after_or_equal:today',
                'description' => 'required|string|max:2000',
                'requirements' => 'nullable|string|max:2000',
                'project_media' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov,webm|max:20480',
            ]);

            $project = new Project($request->only([
                'title',
                'category',
                'budget',
                'deadline',
                'description',
                'requirements',
            ]));

            $project->client_id = auth()->id();
            $project->client_name = auth()->user()->name;
            $project->client_avatar = auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random';
            $project->status = 'open';
            $project->progress_percentage = 0;
            $project->applications_count = 0;
            $project->thumbnail = 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop';

            if ($request->hasFile('project_media')) {
                $file = $request->file('project_media');
                $mimeType = $file->getMimeType() ?? '';
                $resourceType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

                $mediaUrl = $cloudinary->upload($file, [
                    'folder' => 'konekin/projects/media',
                    'resource_type' => $resourceType,
                ]);

                $project->media_url = $mediaUrl;
                $project->media_type = $resourceType;

                if ($resourceType === 'image') {
                    $project->thumbnail = $mediaUrl;
                }
            }

            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil dipublikasikan!',
                'data' => $this->transformProject($project),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mempublikasikan proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiUMKMProjectProgress(ProjectArchiveService $archiveService)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya UMKM yang dapat melihat progress proyek.',
            ], 403);
        }

        $archiveService->syncCompletedProjectsForClient($user->id);

        $projects = Project::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) {
                $project->applications = ProjectApplication::where('project_id', $project->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $project->progress_updates = ProjectProgressUpdate::where('project_id', $project->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $decorated = $this->decorateProject($project);
                $transformed = $this->transformProject($decorated);
                
                $transformed['applications'] = $project->applications->map(fn($app) => [
                    'id' => (string) $app->id,
                    'creative_name' => $app->creative_name,
                    'creative_avatar' => $app->creative_avatar,
                    'status' => $app->status,
                    'message' => $app->message,
                    'proposal_url' => $app->proposal_url,
                    'proposal_download_url' => $app->proposal_download_url,
                    'proposal_type' => $app->proposal_type,
                    'proposal_original_name' => $app->proposal_original_name,
                    'applied_at' => $app->applied_at,
                ]);

                $transformed['progress_updates'] = $project->progress_updates->map(fn($update) => [
                    'id' => (string) $update->id,
                    'note' => $update->note,
                    'percentage' => (int) $update->progress_percentage,
                    'media_url' => $update->media_url,
                    'media_type' => $update->media_type,
                    'created_at' => $update->created_at->toISOString(),
                ]);

                return $transformed;
            });

        $historyProjects = ProjectHistory::where('client_id', $user->id)
            ->orderBy('archived_at', 'desc')
            ->get()
            ->map(fn ($history) => [
                'id' => (string) $history->id,
                'original_project_id' => (string) ($history->original_project_id ?? ''),
                'title' => $history->title,
                'category' => $history->category,
                'budget' => $history->budget,
                'thumbnail' => $history->thumbnail,
                'selected_creative_name' => $history->selected_creative_name,
                'progress_percentage' => (int) ($history->progress_percentage ?? 0),
                'rating' => isset($history->rating) ? (int) $history->rating : null,
                'comment' => $history->comment,
                'archive_reason' => $history->archive_reason,
                'archived_at' => optional($history->archived_at)->toISOString(),
                'rated_at' => optional($history->rated_at)->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar progress proyek UMKM berhasil diambil',
            'data' => $projects,
            'history' => $historyProjects,
        ], 200);
    }

    public function apiDestroyProgressProject($id, ProjectArchiveService $archiveService)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya UMKM yang dapat menghapus proyek.',
            ], 403);
        }

        try {
            $project = Project::where('client_id', $user->id)->findOrFail($id);

            $applicationsCount = ProjectApplication::where('project_id', $project->id)->count();
            if ($applicationsCount > 0 || !empty($project->selected_creative_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proyek ini sudah menerima apply atau sedang berjalan, jadi tidak bisa dihapus langsung.',
                ], 400);
            }

            $archiveService->deleteUnfinishedProject($project);

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil dihapus setelah persetujuan UMKM.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus proyek: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiGetApplications($id)
    {
        $user = auth()->user();
        $project = Project::where('client_id', $user->id)->findOrFail($id);

        $applications = ProjectApplication::where('project_id', $project->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($app) => [
                'id' => (string) $app->id,
                'creative_id' => (string) $app->creative_id,
                'creative_name' => $app->creative_name,
                'creative_avatar' => $app->creative_avatar,
                'creative_city' => $app->creative_city,
                'status' => $app->status,
                'message' => $app->message,
                'proposal_url' => $app->proposal_url,
                'proposal_download_url' => $app->proposal_download_url,
                'proposal_type' => $app->proposal_type,
                'proposal_original_name' => $app->proposal_original_name,
                'applied_at' => $app->applied_at,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pelamar berhasil diambil',
            'data' => $applications,
        ], 200);
    }

    public function apiApproveApplication($id, $applicationId)
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya UMKM yang dapat menyetujui pelamar proyek.',
            ], 403);
        }

        try {
            $project = Project::where('client_id', $user->id)->findOrFail($id);
            $application = ProjectApplication::where('project_id', $project->id)->findOrFail($applicationId);

            ProjectApplication::where('project_id', $project->id)
                ->where('status', 'approved')
                ->update([
                    'status' => 'applied',
                    'approved_at' => null,
                ]);

        $application->status = 'approved';
        $application->approved_at = now();
        $application->save();

        if ($creativeWorker = $application->creative) {
            $creativeWorker->notify(new ProjectApplicationApproved($project, $application));
        }

        $project->selected_creative_id = $application->creative_id;
        $project->selected_creative_name = $application->creative_name;
            $project->selected_creative_avatar = $application->creative_avatar;
            $project->status = 'hired';
            $project->escrow_status = 'unpaid';
            $project->save();

            return response()->json([
                'success' => true,
                'message' => 'Creative worker berhasil disetujui. Pembayaran escrow akan diminta setelah draft/progress 100% dikirim.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pelamar: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getDummyProjects()
    {
        return collect([
            (object) [
                'id' => 9991,
                'title' => 'Redesain Identitas Visual "Kopi Kita"',
                'description' => 'Kami membutuhkan desainer kreatif untuk memperbarui logo dan kemasan produk kopi artisan kami agar lebih modern.',
                'category' => 'Branding',
                'budget' => '3.500.000',
                'deadline' => now()->addDays(7),
                'client_name' => 'UMKM Kopi Kita',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Kopi+Kita&background=2563EB&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop',
                'media_url' => null,
                'media_type' => null,
                'progress_percentage' => 0,
                'applications_count' => 0,
                'status' => 'open',
                'created_at' => now()->subHours(2),
            ],
            (object) [
                'id' => 9992,
                'title' => 'Konten Instagram & TikTok "Batik Solo"',
                'description' => 'Mencari content creator untuk mengelola feed dan membuat video pendek kreatif untuk mempromosikan koleksi terbaru.',
                'category' => 'Social Media',
                'budget' => '2.000.000',
                'deadline' => now()->addDays(10),
                'client_name' => 'UMKM Batik Solo',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Batik+Solo&background=DB2777&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=1974&auto=format&fit=crop',
                'media_url' => null,
                'media_type' => null,
                'progress_percentage' => 25,
                'applications_count' => 1,
                'status' => 'applied',
                'created_at' => now()->subHours(5),
            ],
            (object) [
                'id' => 9993,
                'title' => 'Pembuatan Website Katalog "Mebel Jati"',
                'description' => 'Dibutuhkan web developer untuk membuat website landing page katalog produk mebel yang responsif.',
                'category' => 'Web Dev',
                'budget' => '5.000.000',
                'deadline' => now()->addDays(14),
                'client_name' => 'Mebel Jati Perkasa',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Mebel+Jati&background=16A34A&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2015&auto=format&fit=crop',
                'media_url' => null,
                'media_type' => null,
                'progress_percentage' => 60,
                'applications_count' => 3,
                'status' => 'in_progress',
                'created_at' => now()->subDay(),
            ],
        ]);
    }

    private function decorateProject($project, ?int $applicationsCount = null)
    {
        $count = $applicationsCount ?? (int) ($project->applications_count ?? 0);
        $progress = (int) ($project->progress_percentage ?? 0);
        $status = $project->status ?? 'open';

        $preserveStatuses = [
            'hired',
            'awaiting_payment',
            'payment_pending',
            'ready_for_review',
            'pending_admin_approval',
            'revision',
            'disputed',
            'completed',
            'payment_refunded',
        ];

        if ($count === 0 && empty($project->selected_creative_id)) {
            $progress = 0;
            $status = 'open';
        } elseif ($progress >= 100 && !in_array($status, $preserveStatuses, true)) {
            $status = 'completed';
        } elseif ($progress > 0 && $status === 'applied') {
            $status = 'in_progress';
        }

        $project->applications_count = $count;
        $project->progress_percentage = max(0, min(100, $progress));
        $project->status = $status;
        $project->status_label = match ($status) {
            'open' => 'Belum Ada Apply',
            'applied' => 'Sudah Di-apply',
            'hired' => 'Worker Dipilih',
            'in_progress' => 'Sedang Dikerjakan',
            'awaiting_payment' => 'Menunggu Pembayaran',
            'payment_pending' => 'VA Menunggu Transfer',
            'ready_for_review' => 'Siap Direview',
            'pending_admin_approval' => 'Menunggu Admin',
            'revision' => 'Revisi',
            'disputed' => 'Dispute',
            'payment_refunded' => 'Dana Dikembalikan',
            'completed' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
        $project->progress_summary = $count === 0
            ? 'Belum ada creative worker yang apply. Progress masih 0%.'
            : match ($status) {
                'applied' => !empty($project->selected_creative_id)
                    ? 'Creative worker sudah disetujui. Menunggu progres kerja pertama.'
                    : 'Sudah ada apply masuk. Kamu bisa mulai menyeleksi creative worker.',
                'hired' => 'Creative worker sudah dipilih. Pekerjaan bisa dimulai hingga draft siap direview.',
                'in_progress' => 'Kolaborasi sedang berjalan. Pantau update proyek hingga selesai.',
                'awaiting_payment' => 'Draft sudah 100%. UMKM wajib membayar escrow sebelum memberi persetujuan akhir.',
                'payment_pending' => 'VA pembayaran sudah dibuat. Transfer dan upload bukti agar admin bisa menahan dana di escrow.',
                'ready_for_review' => 'Dana sudah ditahan platform. Review hasil/revisi, lalu setujui selesai atau ajukan dispute jika ada masalah.',
                'pending_admin_approval' => 'UMKM sudah menyetujui hasil. Admin sedang memeriksa sebelum dana dicairkan.',
                'revision' => 'Proyek sedang dalam tahap revisi.',
                'disputed' => 'Proyek masuk dispute. Admin/mediator akan menentukan dana dicairkan atau dikembalikan.',
                'payment_refunded' => 'Dispute selesai dan dana dikembalikan ke UMKM.',
                'completed' => 'Proyek telah selesai 100%.',
                default => 'Progress proyek siap dilanjutkan dari 0% sampai 100%.',
            };

        // Handle Overdue Logic
        $deadline = \Illuminate\Support\Carbon::parse($project->deadline);
        $project->is_overdue = $deadline->isPast() && $status !== 'completed';
        $project->overdue_reason = null;

        if ($project->is_overdue) {
            if ($count === 0) {
                $project->overdue_reason = 'Maaf, tidak ada creative worker yang mengajukan diri (apply) hingga batas waktu berakhir.';
            } elseif (empty($project->selected_creative_id)) {
                $project->overdue_reason = 'Batas waktu berakhir namun kamu belum memilih (approve) creative worker untuk proyek ini.';
            } elseif ($progress < 100) {
                $project->overdue_reason = 'Proyek terlambat! Creative worker tidak menyelesaikan progres tepat waktu (deadline sudah lewat).';
            }
        }

        return $project;
    }

    private function transformProject($project): array
    {
        $project = $this->decorateProject($project);

        return [
            'id' => isset($project->id) ? (string) $project->id : null,
            'title' => $project->title,
            'description' => $project->description,
            'category' => $project->category,
            'budget' => $project->budget,
            'deadline' => isset($project->deadline) && $project->deadline ? \Illuminate\Support\Carbon::parse($project->deadline)->toISOString() : null,
            'client_id' => isset($project->client_id) ? (string) $project->client_id : null,
            'client_name' => $project->client_name,
            'client_avatar' => $project->client_avatar,
            'status' => $project->status ?? null,
            'requirements' => $project->requirements ?? null,
            'thumbnail' => $project->thumbnail ?? null,
            'media_url' => $project->media_url ?? null,
            'media_type' => $project->media_type ?? null,
            'progress_percentage' => $project->progress_percentage ?? 0,
            'applications_count' => $project->applications_count ?? 0,
            'status_label' => $project->status_label ?? null,
            'progress_summary' => $project->progress_summary ?? null,
            'created_at' => isset($project->created_at) && $project->created_at ? $project->created_at->toISOString() : null,
            'updated_at' => isset($project->updated_at) && $project->updated_at ? $project->updated_at->toISOString() : null,
        ];
    }

    private function ensureProposalCanBeOpened(ProjectApplication $application): void
    {
        $user = auth()->user();
        $project = Project::find($application->project_id);

        if (!$user || !$project || empty($application->proposal_url)) {
            abort(404);
        }

        $isProjectOwner = (string) $project->client_id === (string) $user->id;
        $isApplicant = (string) $application->creative_id === (string) $user->id;
        $isAdmin = method_exists($user, 'isAdmin') && $user->isAdmin();

        if (!$isProjectOwner && !$isApplicant && !$isAdmin) {
            abort(403, 'Kamu tidak punya akses ke proposal ini.');
        }
    }

    private function serveProposalFile(ProjectApplication $application, bool $asDownload, CloudinaryService $cloudinary)
    {
        $fileName = str_replace(['"', '\\', '/', "\r", "\n"], '', $application->proposal_display_name);
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION) ?: ($application->proposal_type ?? 'file'));

        $candidateUrls = collect([
            $this->signedProposalUrl($application, $cloudinary, $extension),
            $this->normalizeCloudinaryProposalUrl($application->proposal_url),
            $application->proposal_url,
        ])->filter()->unique()->values();

        $response = null;
        $failedAttempts = [];

        foreach ($candidateUrls as $candidateUrl) {
            try {
                $candidateResponse = Http::timeout(60)
                    ->withHeaders(['User-Agent' => 'Konekin-Proposal-Proxy/1.0'])
                    ->get($candidateUrl);

                if ($candidateResponse->successful()) {
                    $response = $candidateResponse;
                    break;
                }

                $failedAttempts[] = [
                    'status' => $candidateResponse->status(),
                    'url' => $candidateUrl,
                ];
            } catch (\Throwable $e) {
                $failedAttempts[] = [
                    'status' => 'exception',
                    'url' => $candidateUrl,
                    'message' => $e->getMessage(),
                ];
            }
        }

        if (!$response) {
            Log::warning('Proposal file could not be fetched.', [
                'application_id' => (string) $application->id,
                'url' => $application->proposal_url,
                'attempts' => $failedAttempts,
            ]);

            abort(404, 'File proposal tidak bisa dibuka dari storage.');
        }

        $contentType = $application->proposal_mime_type ?: $this->proposalMimeType($extension);
        $disposition = $asDownload ? 'attachment' : 'inline';

        return response($response->body(), 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => $disposition . '; filename="' . $fileName . '"',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }

    private function signedProposalUrl(ProjectApplication $application, CloudinaryService $cloudinary, string $extension): ?string
    {
        if (!str_contains((string) $application->proposal_url, 'res.cloudinary.com')) {
            return null;
        }

        try {
            return $cloudinary->signedDownloadUrl($application->proposal_url, $extension);
        } catch (\Throwable $e) {
            Log::warning('Proposal signed URL could not be generated.', [
                'application_id' => (string) $application->id,
                'url' => $application->proposal_url,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function normalizeCloudinaryProposalUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (str_contains($url, 'res.cloudinary.com') && str_contains($url, '/image/upload/')) {
            return str_replace('/image/upload/', '/raw/upload/', $url);
        }

        return $url;
    }

    private function proposalMimeType(string $extension): string
    {
        return match (strtolower($extension)) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip',
            default => 'application/octet-stream',
        };
    }

    public function acceptInvitation($id)
    {
        $user = auth()->user();
        $project = Project::where('selected_creative_id', $user->id)
            ->where('status', 'waiting_confirmation')
            ->findOrFail($id);

        $project->update([
            'status' => 'hired',
        ]);

        return redirect()->route('dashboard.creative')
            ->with('success', 'Selamat! Kamu telah menerima proyek: ' . $project->title . '. Tunggu UMKM melakukan pembayaran escrow sebelum mulai bekerja.');
    }

    public function rejectInvitation($id)
    {
        $user = auth()->user();
        $project = Project::where('selected_creative_id', $user->id)
            ->where('status', 'waiting_confirmation')
            ->findOrFail($id);

        $project->update([
            'status' => 'open',
            'selected_creative_id' => null,
            'selected_creative_name' => null,
            'selected_creative_avatar' => null,
        ]);

        return redirect()->route('dashboard.creative')
            ->with('success', 'Kamu telah menolak tawaran proyek: ' . $project->title);
    }
}
