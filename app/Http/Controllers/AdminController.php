<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $type = $request->query('type');
        $query = User::where('type', '!=', 'admin');

        if ($type) {
            $query->where('type', $type);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users', 'type'));
    }

    public function warnUser(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $user = User::findOrFail($id);
        $reason = $request->input('reason');

        $warnings = $user->warnings ?? [];
        $warnings[] = [
            'reason' => $reason,
            'date' => now()->toDateTimeString(),
            'admin_id' => auth()->id()
        ];

        $user->warnings = $warnings;
        $user->status = 'warned';
        $user->save();

        return back()->with('success', 'Surat peringatan berhasil dikirim ke ' . $user->name);
    }

    public function suspendUser($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        $user->status = 'suspended';
        $user->save();

        return back()->with('success', 'Akun ' . $user->name . ' telah ditangguhkan.');
    }

    public function activateUser($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return back()->with('success', 'Akun ' . $user->name . ' telah diaktifkan kembali.');
    }

    public function projects(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $query = Project::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $projects = $query->orderBy('created_at', 'desc')->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function destroyProject($id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $project = Project::findOrFail($id);
        $project->delete();

        return back()->with('success', 'Proyek berhasil dihapus.');
    }

    public function jobs()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        // Monitoring job activity: projects that are active or in progress
        $activeProjects = Project::whereIn('status', ['hired', 'in_progress', 'completed'])->orderBy('updated_at', 'desc')->get();

        return view('admin.jobs.index', compact('activeProjects'));
    }
}
