<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\User;

class CreatorController extends Controller
{
    public function index()
    {
        // Untuk sementara, kita ambil semua user yang tipenya 'creative_worker'
        $creators = User::where('type', 'creative_worker')->get();
        
        return view('creators.index', compact('creators'));
    }

    public function show(string $id)
    {
        $creator = User::where('type', 'creative_worker')->findOrFail($id);

        $portfolios = Portfolio::where('user_id', $creator->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('creators.show', compact('creator', 'portfolios'));
    }
}
