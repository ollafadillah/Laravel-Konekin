<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'attachment' => 'nullable|file|mimes:pdf,mp4,mov,zip,docx|max:20480', // Max 20MB
        ]);

        try {
            // 1. Upload Image (Thumbnail)
            $imageUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'konekin/portfolios/thumbnails',
            ])->getSecurePath();

            $fileUrl = null;
            $fileType = null;

            // 2. Upload Attachment (jika ada)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileType = $file->getClientOriginalExtension();
                
                // Cloudinary upload auto detect resource type (image, video, raw)
                $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'konekin/portfolios/files',
                    'resource_type' => 'auto' 
                ]);
                
                $fileUrl = $uploadedFile->getSecurePath();
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
