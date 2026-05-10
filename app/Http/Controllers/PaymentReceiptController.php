<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\EscrowTransaction;
use App\Models\User;
use App\Notifications\EscrowPaymentReceived;
use App\Notifications\PaymentVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PaymentReceiptController extends Controller
{
    /**
     * Show form untuk upload resi pembayaran
     */
    public function create($projectId)
    {
        $user = auth()->user();
        $project = Project::findOrFail($projectId);

        // Cek ownership
        if ((string)$project->client_id !== (string)$user->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Check project status
        if ($project->status !== 'hired' || $project->escrow_status === 'held') {
            return redirect()->back()->with('error', 'Proyek belum dalam status siap untuk pembayaran atau sudah dibayar.');
        }

        return view('payment.receipt-upload', compact('project'));
    }

    /**
     * Store resi pembayaran
     */
    public function store($projectId, Request $request)
    {
        $user = auth()->user();
        $project = Project::findOrFail($projectId);

        if ((string)$project->client_id !== (string)$user->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'receipt_image' => 'required|image|max:5120', // 5MB
            'bank_name' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:transfer,cash,cheque,other',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Upload receipt image
            $receiptPath = $request->file('receipt_image')->store('payment-receipts', 'public');

            // Create escrow transaction
            $amount = (int) \App\Helpers\CurrencyHelper::extract($project->budget);
            $platformFee = $amount * 0.10;
            $netAmount = $amount - $platformFee;

            $escrow = EscrowTransaction::create([
                'project_id' => $project->id,
                'payer_id' => $project->client_id,
                'payee_id' => $project->selected_creative_id,
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'status' => 'pending', // Pending admin verification
                'midtrans_order_id' => 'RECEIPT-' . $project->id . '-' . time(),
                'midtrans_transaction_id' => 'RECEIPT-TX-' . uniqid(),
                'midtrans_payment_type' => 'manual_receipt',
            ]);

            // Update project
            $project->update([
                'escrow_transaction_id' => $escrow->id,
                'payment_receipt_image' => $receiptPath,
                'payment_bank_name' => $validated['bank_name'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'payment_notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Payment receipt uploaded', [
                'project_id' => $project->id,
                'umkm_id' => $user->id,
                'escrow_id' => $escrow->id,
                'bank_name' => $validated['bank_name'],
            ]);

            return redirect()->route('projects.show', $project->id)
                ->with('success', 'Resi pembayaran berhasil diunggah. Admin akan melakukan verifikasi dalam waktu 24 jam.');

        } catch (\Exception $e) {
            Log::error('Payment Receipt Upload Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal upload resi: ' . $e->getMessage());
        }
    }

    /**
     * Admin verify payment receipt
     */
    public function adminVerify($escrowId, Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'verified' => 'required|boolean',
            'verification_notes' => 'nullable|string|max:500',
        ]);

        try {
            $escrow = EscrowTransaction::findOrFail($escrowId);

            if ($validated['verified']) {
                // Update escrow status to held
                $escrow->update([
                    'status' => 'held',
                    'disbursement_status' => 'verified',
                ]);

                $project = $escrow->project;
                $project->update([
                    'status' => 'in_progress',
                    'escrow_status' => 'held',
                    'escrow_transaction_id' => $escrow->id
                ]);

                // Notify Creative Worker
                $creative = User::find($project->selected_creative_id);
                if ($creative) {
                    $creative->notify(new EscrowPaymentReceived($project, $escrow));
                }

                Log::info('Payment receipt verified by admin', [
                    'escrow_id' => $escrow->id,
                    'admin_id' => $user->id,
                ]);

                return redirect()->back()->with('success', 'Resi pembayaran terverifikasi. Escrow status: held.');
            } else {
                // Reject verification
                $escrow->update([
                    'status' => 'rejected',
                    'disbursement_status' => 'rejected',
                ]);

                Log::info('Payment receipt rejected by admin', [
                    'escrow_id' => $escrow->id,
                    'admin_id' => $user->id,
                    'notes' => $validated['verification_notes'],
                ]);

                return redirect()->back()->with('error', 'Resi pembayaran ditolak. UMKM perlu upload ulang.');
            }

        } catch (\Exception $e) {
            Log::error('Payment Receipt Verification Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }
}
