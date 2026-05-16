<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            $fileDisk = null;
            $filePath = null;
            $fileOriginalName = null;

            // 2. Upload Attachment (jika ada)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachment = $this->storeAttachment($file, $cloudinary);

                $fileUrl = $attachment['url'];
                $fileType = $attachment['type'];
                $fileDisk = $attachment['disk'];
                $filePath = $attachment['path'];
                $fileOriginalName = $attachment['original_name'];
            }

            Portfolio::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'image_url' => $imageUrl,
                'file_url' => $fileUrl,
                'file_type' => $fileType,
                'file_disk' => $fileDisk,
                'file_path' => $filePath,
                'file_original_name' => $fileOriginalName,
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

    public function attachment(string $id, CloudinaryService $cloudinary)
    {
        $portfolio = Portfolio::findOrFail($id);

        abort_if(empty($portfolio->file_url), 404);

        if ($portfolio->file_disk === 'public' && $portfolio->file_path) {
            abort_unless(Storage::disk('public')->exists($portfolio->file_path), 404);

            $path = Storage::disk('public')->path($portfolio->file_path);
            $fileName = str_replace('"', '', $portfolio->file_original_name ?: basename($portfolio->file_path));
            $mimeType = Storage::disk('public')->mimeType($portfolio->file_path) ?: 'application/octet-stream';

            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        $isDocument = in_array(strtolower((string) $portfolio->file_type), ['pdf', 'zip', 'docx'], true);
        $signedDownloadUrl = $isDocument
            ? $cloudinary->signedDownloadUrl($portfolio->file_url, $portfolio->file_type)
            : null;

        return redirect()->away($signedDownloadUrl ?: $this->normalizeCloudinaryAttachmentUrl($portfolio->file_url, $portfolio->file_type));
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
            $fileDisk = null;
            $filePath = null;
            $fileOriginalName = null;

            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $storedAttachment = $this->storeAttachment($attachment, $cloudinary);

                $fileUrl = $storedAttachment['url'];
                $fileType = $storedAttachment['type'];
                $fileDisk = $storedAttachment['disk'];
                $filePath = $storedAttachment['path'];
                $fileOriginalName = $storedAttachment['original_name'];
            }

            $portfolio = Portfolio::create([
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image_url' => $imageUrl,
                'file_url' => $fileUrl,
                'file_type' => $fileType,
                'file_disk' => $fileDisk,
                'file_path' => $filePath,
                'file_original_name' => $fileOriginalName,
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
            'file_open_url' => $portfolio->file_url ? route('portfolio.attachment', $portfolio->id) : null,
            'file_type' => $portfolio->file_type,
            'category' => $portfolio->category,
            'created_at' => optional($portfolio->created_at)?->toISOString(),
            'updated_at' => optional($portfolio->updated_at)?->toISOString(),
        ];
    }

    private function storeAttachment(UploadedFile $file, CloudinaryService $cloudinary): array
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $originalName = $file->getClientOriginalName();

        if (in_array($extension, ['mp4', 'mov'], true)) {
            return [
                'url' => $cloudinary->upload($file, [
                    'folder' => 'konekin/portfolios/files',
                    'resource_type' => 'video',
                    'filename_override' => $originalName,
                ]),
                'type' => $extension,
                'disk' => 'cloudinary',
                'path' => null,
                'original_name' => $originalName,
            ];
        }

        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: 'portfolio-file';
        $fileName = $baseName . '-' . now()->format('YmdHis') . '-' . Str::random(8) . '.' . $extension;
        $path = $file->storeAs('portfolio-attachments', $fileName, 'public');

        if (!$path) {
            throw new \RuntimeException('File portfolio gagal disimpan.');
        }

        return [
            'url' => Storage::disk('public')->url($path),
            'type' => $extension,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $originalName,
        ];
    }

    private function normalizeCloudinaryAttachmentUrl(string $url, ?string $fileType): string
    {
        if (
            in_array(strtolower((string) $fileType), ['pdf', 'zip', 'docx'], true)
            && str_contains($url, 'res.cloudinary.com')
            && str_contains($url, '/image/upload/')
        ) {
            return str_replace('/image/upload/', '/raw/upload/', $url);
        }

        return $url;
    }
}
