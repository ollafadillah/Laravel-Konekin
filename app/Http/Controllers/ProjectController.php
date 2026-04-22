<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\ProjectProgressUpdate;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
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

        $projects = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($project) => $this->decorateProject($project));

        if ($projects->isEmpty()) {
            $projects = $this->getDummyProjects();
        }

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

        $projects = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($project) => $this->decorateProject($project));

        if ($projects->isEmpty()) {
            $projects = $this->getDummyProjects();
        }

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
        $project = $this->decorateProject(Project::findOrFail($id));
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
            $proposalUrl = $cloudinary->upload($proposalFile, [
                'folder' => 'konekin/projects/proposals',
                'resource_type' => 'raw',
            ]);
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

    public function progress()
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat melihat progress proyek.');
        }

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

                return $this->decorateProject($project, $project->applications->count());
            });

        return view('projects.progress', compact('projects'));
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

        $project->selected_creative_id = $application->creative_id;
        $project->selected_creative_name = $application->creative_name;
        $project->selected_creative_avatar = $application->creative_avatar;
        $project->applications_count = ProjectApplication::where('project_id', $project->id)->count();
        $project->status = (int) ($project->progress_percentage ?? 0) >= 100 ? 'completed' : 'applied';
        $project->save();

        return back()->with('success', 'Creative worker berhasil disetujui untuk mengerjakan proyek ini.');
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
        $project->status = $validated['progress_percentage'] >= 100 ? 'completed' : 'in_progress';
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

            $projects = $query->orderBy('created_at', 'desc')->get()
                ->map(fn ($project) => $this->decorateProject($project));

            if ($projects->isEmpty()) {
                $projects = $this->getDummyProjects();
            }

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
            $project = Project::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail proyek berhasil diambil',
                'data' => $this->transformProject($project),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan',
            ], 404);
        }
    }

    private function getDummyProjects()
    {
        return collect([
            (object) [
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

        if ($count === 0) {
            $progress = 0;
            $status = 'open';
        } elseif ($progress >= 100) {
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
            'in_progress' => 'Sedang Dikerjakan',
            'revision' => 'Revisi',
            'completed' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
        $project->progress_summary = $count === 0
            ? 'Belum ada creative worker yang apply. Progress masih 0%.'
            : match ($status) {
                'applied' => !empty($project->selected_creative_id)
                    ? 'Creative worker sudah disetujui. Menunggu progres kerja pertama.'
                    : 'Sudah ada apply masuk. Kamu bisa mulai menyeleksi creative worker.',
                'in_progress' => 'Kolaborasi sedang berjalan. Pantau update proyek hingga selesai.',
                'revision' => 'Proyek sedang dalam tahap revisi.',
                'completed' => 'Proyek telah selesai 100%.',
                default => 'Progress proyek siap dilanjutkan dari 0% sampai 100%.',
            };

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
}
