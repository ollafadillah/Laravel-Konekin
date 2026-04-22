<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Tampilkan halaman cari proyek
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter Pencarian
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->has('category') && $request->category != 'Semua') {
            $query->where('category', $request->category);
        }

        $projects = $query->orderBy('created_at', 'desc')->get();

        // Dummy data jika database kosong biar UI tetap kelihatan bagus
        if ($projects->isEmpty()) {
            $projects = $this->getDummyProjects();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Tampilkan form buat proyek (UMKM)
     */
    public function create()
    {
        if (!auth()->user()->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat membuat proyek.');
        }

        return view('projects.create');
    }

    /**
     * Simpan proyek baru
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat membuat proyek.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'budget' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
        ]);

        $project = new Project($validated);
        $project->client_id = auth()->id();
        $project->client_name = auth()->user()->name;
        $project->client_avatar = auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random';
        $project->status = 'open';
        $project->thumbnail = 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop'; // Default placeholder
        $project->save();

        return redirect()->route('dashboard.umkm')->with('success', 'Proyek berhasil dipublikasikan!');
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

            $projects = $query->orderBy('created_at', 'desc')->get();

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
            (object)[
                'title' => 'Redesain Identitas Visual "Kopi Kita"',
                'description' => 'Kami membutuhkan desainer kreatif untuk memperbarui logo dan kemasan produk kopi artisan kami agar lebih modern.',
                'category' => 'Branding',
                'budget' => '3.500.000',
                'client_name' => 'UMKM Kopi Kita',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Kopi+Kita&background=2563EB&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop',
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'title' => 'Konten Instagram & TikTok "Batik Solo"',
                'description' => 'Mencari content creator untuk mengelola feed dan membuat video pendek kreatif untuk mempromosikan koleksi terbaru.',
                'category' => 'Social Media',
                'budget' => '2.000.000',
                'client_name' => 'UMKM Batik Solo',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Batik+Solo&background=DB2777&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=1974&auto=format&fit=crop',
                'created_at' => now()->subHours(5)
            ],
            (object)[
                'title' => 'Pembuatan Website Katalog "Mebel Jati"',
                'description' => 'Dibutuhkan web developer untuk membuat website landing page katalog produk mebel yang responsif.',
                'category' => 'Web Dev',
                'budget' => '5.000.000',
                'client_name' => 'Mebel Jati Perkasa',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Mebel+Jati&background=16A34A&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2015&auto=format&fit=crop',
                'created_at' => now()->subDays(1)
            ],
            (object)[
                'title' => 'Video Profile UMKM "Kerajinan Bambu"',
                'description' => 'Videografer untuk membuat video dokumentasi proses pembuatan kerajinan bambu untuk keperluan branding.',
                'category' => 'Videography',
                'budget' => '4.500.000',
                'client_name' => 'Bambu Indah',
                'client_avatar' => 'https://ui-avatars.com/api/?name=Bambu+Indah&background=EA580C&color=fff',
                'thumbnail' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=2071&auto=format&fit=crop',
                'created_at' => now()->subDays(2)
            ]
        ]);
    }

    private function transformProject($project): array
    {
        return [
            'id' => isset($project->id) ? (string) $project->id : null,
            'title' => $project->title,
            'description' => $project->description,
            'category' => $project->category,
            'budget' => $project->budget,
            'client_id' => isset($project->client_id) ? (string) $project->client_id : null,
            'client_name' => $project->client_name,
            'client_avatar' => $project->client_avatar,
            'status' => $project->status ?? null,
            'requirements' => $project->requirements ?? null,
            'thumbnail' => $project->thumbnail ?? null,
            'created_at' => isset($project->created_at) && $project->created_at ? $project->created_at->toISOString() : null,
            'updated_at' => isset($project->updated_at) && $project->updated_at ? $project->updated_at->toISOString() : null,
        ];
    }
}
