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
        $type = in_array($type, ['creative_worker', 'umkm'], true) ? $type : null;
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'joined_newest');
        $sort = in_array($sort, ['name_asc', 'name_desc', 'joined_newest', 'joined_oldest'], true)
            ? $sort
            : 'joined_newest';
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $query = User::where('type', '!=', 'admin');

        if ($type) {
            $query->where('type', $type);
        }

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'joined_oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users', 'type', 'search', 'sort', 'perPage'));
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

    public function payments(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $status = $request->query('status', 'paid');
        $query = \App\Models\Payment::query();

        if ($status === 'paid') {
            // Payments waiting for verification (paid but not verified)
            $query->where('status', 'paid')->whereNull('verified_at');
        } elseif ($status === 'verified') {
            // Verified payments
            $query->where('status', 'paid')->whereNotNull('verified_at');
        } elseif ($status === 'failed') {
            // Rejected payments
            $query->where('status', 'failed');
        }

        $payments = $query->with('project')->orderBy('created_at', 'desc')->get();

        // Get counts for tabs
        $pendingCount = \App\Models\Payment::where('status', 'paid')->whereNull('verified_at')->count();
        $verifiedCount = \App\Models\Payment::where('status', 'paid')->whereNotNull('verified_at')->count();
        $rejectedCount = \App\Models\Payment::where('status', 'failed')->count();

        return view('admin.payments.index', compact('payments', 'pendingCount', 'verifiedCount', 'rejectedCount', 'status'));
    }

    public function paymentDetail($paymentId)
    {
        if (!auth()->user()->isAdmin()) {
            return response('Akses ditolak.', 403);
        }

        $payment = \App\Models\Payment::with('project')->findOrFail($paymentId);

        return view('admin.payments.detail-content', compact('payment'));
    }
}
