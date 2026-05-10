<?php

namespace App\Http\Controllers;

use App\Models\EscrowTransaction;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        // Payment pending verification (status = pending)
        $pendingVerificationEscrows = EscrowTransaction::where('status', 'pending')
            ->with(['project', 'payer', 'payee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $pendingVerificationProjects = $pendingVerificationEscrows->map(function ($escrow) {
            $payer = $escrow->payer;
            $payee = $escrow->payee;
            $project = $escrow->project;
            
            return (object) [
                'id' => (string) $escrow->project_id,
                'title' => $project->title ?? 'N/A',
                'client_name' => $payer ? $payer->name : 'N/A',
                'selected_creative_name' => $payee ? $payee->name : 'N/A',
                'progress_percentage' => (int) ($project->progress_percentage ?? 0),
                'escrow' => $escrow,
                'payment_method' => $project->payment_method ?? 'N/A',
                'payment_bank_name' => $project->payment_bank_name ?? 'N/A',
                'payment_date' => $project->payment_date,
                'receipt_image' => $project->payment_receipt_image,
                'payment_notes' => $project->payment_notes ?? null,
            ];
        });

        // Count verified today
        $today = now()->startOfDay();
        $verifiedToday = EscrowTransaction::where('status', 'held')
            ->where('updated_at', '>=', $today)
            ->count();

        $pendingCount = $pendingVerificationProjects->count();
        $totalPending = $pendingVerificationProjects->sum(fn ($p) => (int) $p->escrow->amount);

        return view('admin.payment-verification.index', compact(
            'pendingVerificationProjects',
            'pendingCount',
            'totalPending',
            'verifiedToday'
        ));
    }
}
