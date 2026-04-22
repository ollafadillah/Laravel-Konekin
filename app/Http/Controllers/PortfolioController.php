<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
}
