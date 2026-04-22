<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('portfolio.index', compact('portfolios'));
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'attachment' => 'nullable|file|mimes:pdf,mp4,mov,zip,docx|max:20480', // Max 20MB
        ]);

        try {
            // 1. Upload Image (Thumbnail)
            $imageUrl = $cloudinary->upload($request->file('image'), [
                'folder' => 'konekin/portfolios/thumbnails',
                'resource_type' => 'image',
            ]);

            $fileUrl = null;
            $fileType = null;

            // 2. Upload Attachment (jika ada)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileType = $file->getClientOriginalExtension();

                $fileUrl = $cloudinary->upload($file, [
                    'folder' => 'konekin/portfolios/files',
                    'resource_type' => 'auto'
                ]);
            }

            Portfolio::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'image_url' => $imageUrl,
                'file_url' => $fileUrl,
                'file_type' => $fileType,
            ]);

            return back()->with('success', 'Portfolio & File berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Portfolio Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $portfolio->delete();
        return back()->with('success', 'Portfolio berhasil dihapus!');
    }

    public function apiIndex(Request $request)
    {
        try {
            $portfolios = Portfolio::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn ($portfolio) => $this->transformPortfolio($portfolio))
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Daftar portfolio berhasil diambil',
                'data' => $portfolios,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiStore(Request $request, CloudinaryService $cloudinary)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'attachment' => 'nullable|file|mimes:pdf,mp4,mov,zip,docx|max:20480',
            ]);

            $imageUrl = $cloudinary->upload($request->file('image'), [
                'folder' => 'konekin/portfolios/thumbnails',
                'resource_type' => 'image',
            ]);

            $fileUrl = null;
            $fileType = null;

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $fileType = $attachment->getClientOriginalExtension();
                $fileUrl = $cloudinary->upload($attachment, [
                    'folder' => 'konekin/portfolios/files',
                    'resource_type' => 'auto',
                ]);
            }

            $portfolio = Portfolio::create([
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_url' => $imageUrl,
                'file_url' => $fileUrl,
                'file_type' => $fileType,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Portfolio berhasil ditambahkan',
                'data' => $this->transformPortfolio($portfolio),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Portfolio API Store Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function apiShow(Request $request, $id)
    {
        try {
            $portfolio = Portfolio::where('user_id', $request->user()->id)->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail portfolio berhasil diambil',
                'data' => $this->transformPortfolio($portfolio),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio tidak ditemukan',
            ], 404);
        }
    }

    public function apiDestroy(Request $request, $id)
    {
        try {
            $portfolio = Portfolio::where('user_id', $request->user()->id)->findOrFail($id);
            $portfolio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio tidak ditemukan',
            ], 404);
        }
    }

    private function transformPortfolio(Portfolio $portfolio): array
    {
        return [
            'id' => (string) $portfolio->id,
            'user_id' => (string) $portfolio->user_id,
            'title' => $portfolio->title,
            'description' => $portfolio->description,
            'image_url' => $portfolio->image_url,
            'file_url' => $portfolio->file_url,
            'file_type' => $portfolio->file_type,
            'category' => $portfolio->category,
            'created_at' => optional($portfolio->created_at)?->toISOString(),
            'updated_at' => optional($portfolio->updated_at)?->toISOString(),
        ];
    }
}
