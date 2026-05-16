<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\EscrowTransaction;
use App\Services\CloudinaryService;
use App\Helpers\CurrencyHelper;
use App\Notifications\PaymentInvoiceCreated;
use App\Notifications\PaymentProofSubmitted;
use App\Notifications\PaymentVerified;
use App\Notifications\PaymentApproved;
use App\Notifications\PaymentApprovedToCreative;
use App\Notifications\PaymentRejected;
use App\Notifications\PaymentRejectedToCreative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Generate payment when project is completed
     */
    public function generatePayment(string $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        if (!auth()->user()->isUMKM()) {
            return redirect()->back()->with('error', 'Hanya UMKM yang dapat membuat pembayaran.');
        }

        if ((string)$project->client_id !== (string)auth()->id()) {
            return redirect()->back()->with('error', 'Proyek ini bukan milik Anda.');
        }

        if (empty($project->selected_creative_id)) {
            return redirect()->back()->with('error', 'Pilih creative worker terlebih dahulu sebelum membuat pembayaran.');
        }

        if ((int) ($project->progress_percentage ?? 0) < 100) {
            return redirect()->back()->with('error', 'Pembayaran baru bisa dibuat setelah creative worker mengirim draft/progress 100%.');
        }

        if (in_array($project->escrow_status, ['held', 'released'], true)) {
            return redirect()->back()->with('error', 'Dana proyek ini sudah masuk escrow.');
        }

        $existingPayment = Payment::where('project_id', $projectId)
            ->whereIn('status', ['pending', 'paid'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingPayment) {
            return redirect()->route('payments.show', $existingPayment->_id)
                ->with('success', 'Invoice pembayaran proyek ini sudah tersedia.');
        }

        $amount = (int) CurrencyHelper::extract($project->budget);
        $platformFee = (int) round($amount * 0.15);
        $netAmount = $amount - $platformFee;
        $paymentNumber = (new Payment())->generatePaymentNumber();
        $vaBank = 'BCA';
        $vaNumber = Payment::generateVirtualAccountNumber((string) $project->id);
        $dueAt = now()->addDay();

        // Create payment record
        $payment = new Payment([
            'project_id' => $projectId,
            'client_id' => $project->client_id,
            'client_name' => $project->client_name,
            'client_avatar' => $project->client_avatar,
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'currency' => 'IDR',
            'payment_number' => $paymentNumber,
            'description' => "Pembayaran escrow untuk draft/revisi proyek: {$project->title}",
            'status' => 'pending',
            'payment_method' => 'virtual_account',
            'virtual_account_bank' => $vaBank,
            'virtual_account_number' => $vaNumber,
            'payment_due_at' => $dueAt,
        ]);

        $payment->save();

        $escrow = EscrowTransaction::create([
            'project_id' => $project->id,
            'payer_id' => $project->client_id,
            'payee_id' => $project->selected_creative_id,
            'payment_id' => $payment->id,
            'amount' => $amount,
            'platform_fee' => $platformFee,
            'net_amount' => $netAmount,
            'status' => 'pending',
            'midtrans_order_id' => $paymentNumber,
            'midtrans_transaction_id' => null,
            'midtrans_payment_type' => 'manual_virtual_account',
            'virtual_account_bank' => $vaBank,
            'virtual_account_number' => $vaNumber,
            'payment_due_at' => $dueAt,
            'disbursement_status' => 'waiting_payment',
        ]);

        $payment->update(['escrow_transaction_id' => $escrow->id]);

        $project->update([
            'status' => 'payment_pending',
            'escrow_status' => 'pending',
            'payment_id' => $payment->id,
            'escrow_transaction_id' => $escrow->id,
        ]);

        // Send notification to UMKM
        $umkm = User::find($project->client_id);
        $this->sendUserNotification($umkm, new PaymentInvoiceCreated($payment), 'payment_invoice_created');

        return redirect()->route('payments.show', $payment->_id)
            ->with('success', 'VA pembayaran berhasil dibuat otomatis. Silakan transfer dan upload bukti pembayaran.');
    }

    /**
     * Show payment detail
     */
    public function show(string $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        
        if ((string)$payment->client_id !== (string)auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $project = Project::find($payment->project_id);

        return view('payments.show', compact('payment', 'project'));
    }

    /**
     * Upload proof of payment
     */
    public function uploadProof(Request $request, string $paymentId, CloudinaryService $cloudinary)
    {
        $payment = Payment::findOrFail($paymentId);
        
        if ((string)$payment->client_id !== (string)auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if (!$payment->isPending()) {
            return redirect()->back()->with('error', 'Pembayaran ini sudah diproses dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'proof_file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:10240',
            'payment_method' => 'required|string|in:virtual_account,transfer,card,e-wallet,other',
            'notes' => 'nullable|string|max:500',
            'payment_date' => 'required|date|before_or_equal:today',
        ], [
            'proof_file.required' => 'File bukti pembayaran wajib di-upload.',
            'proof_file.mimes' => 'File harus berformat PDF, JPG, JPEG, PNG, atau GIF.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_date.required' => 'Tanggal pembayaran wajib diisi.',
        ]);

        try {
            $proofFile = $request->file('proof_file');
            $fileType = $proofFile->getClientOriginalExtension();
            
            $proofUrl = $cloudinary->upload($proofFile, [
                'folder' => 'konekin/payments/proofs',
                'resource_type' => 'auto',
            ]);

            $payment->update([
                'proof_file_url' => $proofUrl,
                'proof_file_type' => $fileType,
                'payment_method' => $validated['payment_method'],
                'notes_from_umkm' => $validated['notes'] ?? null,
                'payment_date' => $validated['payment_date'],
                'status' => 'paid',
            ]);

            EscrowTransaction::where('payment_id', $payment->id)->update([
                'proof_file_url' => $proofUrl,
                'proof_file_type' => $fileType,
                'payment_date' => $validated['payment_date'],
                'status' => 'pending',
                'disbursement_status' => 'waiting_verification',
            ]);

            // Send notification to admin
            try {
                $admins = User::where('type', 'admin')->get();
                if ($admins && count($admins) > 0) {
                    foreach ($admins as $admin) {
                        if (method_exists($admin, 'notify')) {
                            $this->sendUserNotification($admin, new PaymentProofSubmitted($payment), 'payment_proof_submitted');
                        }
                    }
                }
            } catch (\Exception $notificationError) {
                Log::warning('Failed to send notification to admin: ' . $notificationError->getMessage());
            }

            Log::info("Payment proof uploaded: {$payment->_id}");

            return redirect()->route('payments.show', $payment->_id)
                ->with('success', 'Bukti pembayaran berhasil di-upload. Dana akan ditahan di escrow setelah admin memverifikasi.');
        } catch (\Exception $e) {
            Log::error('Payment Proof Upload Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Bukti pembayaran gagal di-upload: ' . $e->getMessage());
        }
    }

    /**
     * List all payments for UMKM
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->isUMKM()) {
            return redirect()->route('home')->with('error', 'Hanya UMKM yang dapat melihat pembayaran.');
        }

        $payments = Payment::where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Verify payment (Admin only)
     */
    public function verify(Request $request, string $paymentId)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya admin yang dapat memverifikasi pembayaran.');
        }

        $payment = Payment::findOrFail($paymentId);

        if (!$payment->isPaid() || $payment->isVerified()) {
            return redirect()->back()->with('error', 'Hanya pembayaran dengan status "paid" yang dapat diverifikasi.');
        }

        $payment->update([
            'status' => 'paid',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Get project for notification
        $project = Project::find($payment->project_id);

        // Send notification to UMKM
        $umkm = User::find($payment->client_id);
        if ($project) {
            $this->sendUserNotification($umkm, new PaymentApproved($payment, $project), 'payment_approved_umkm');
        }

        // Update project status to ready for UMKM final review and hold funds in escrow
        if ($project) {
            $amount = (int) CurrencyHelper::extract($project->budget);
            $platformFee = (int) round($amount * 0.15);
            $netAmount = $amount - $platformFee;

            $escrow = EscrowTransaction::where('payment_id', $payment->id)->first()
                ?? EscrowTransaction::where('project_id', $project->id)->where('status', 'pending')->first();

            if (!$escrow) {
                $escrow = EscrowTransaction::create([
                    'project_id' => $project->id,
                    'payer_id' => $project->client_id,
                    'payee_id' => $project->selected_creative_id,
                    'payment_id' => $payment->id,
                    'midtrans_order_id' => $payment->payment_number,
                    'midtrans_payment_type' => 'manual_virtual_account',
                ]);
            }

            $escrow->update([
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'status' => 'held',
                'midtrans_transaction_id' => 'MANUAL-TX-' . uniqid(),
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'disbursement_status' => 'held_by_platform',
            ]);

            $project->update([
                'status' => 'ready_for_review',
                'escrow_status' => 'held',
                'escrow_transaction_id' => $escrow->id
            ]);

            // Notify Creative Worker
            $creative = User::find($project->selected_creative_id);
            if ($creative) {
                $this->sendUserNotification($creative, new \App\Notifications\EscrowPaymentReceived($project, $escrow), 'escrow_payment_received_creative');
                $this->sendUserNotification($creative, new PaymentApprovedToCreative($payment, $project), 'payment_approved_creative');
            }
        }

        Log::info("Payment verified: {$payment->_id}");

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi. Dana sudah ditahan di escrow platform.');
    }

    /**
     * Reject payment (Admin only)
     */
    public function reject(Request $request, string $paymentId)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menolak pembayaran.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment = Payment::findOrFail($paymentId);

        if (!$payment->isPaid()) {
            return redirect()->back()->with('error', 'Hanya pembayaran dengan status "paid" yang dapat ditolak.');
        }

        $payment->update([
            'status' => 'failed',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
        ]);

        EscrowTransaction::where('payment_id', $payment->id)->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
            'disbursement_status' => 'payment_rejected',
        ]);

        $project = Project::find($payment->project_id);

        // Send notification to UMKM
        $umkm = User::find($payment->client_id);
        $this->sendUserNotification($umkm, new PaymentRejected($payment), 'payment_rejected_umkm');

        if ($project) {
            if ($creative = User::find($project->selected_creative_id)) {
                $this->sendUserNotification($creative, new PaymentRejectedToCreative($payment, $project), 'payment_rejected_creative');
            }

            $project->update([
                'status' => 'awaiting_payment',
                'escrow_status' => 'unpaid',
            ]);
        }

        Log::info("Payment rejected: {$payment->_id}. Reason: {$validated['rejection_reason']}");

        return redirect()->back()->with('success', 'Pembayaran berhasil ditolak.');
    }

    /**
     * Cancel payment
     */
    public function cancel(string $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ((string)$payment->client_id !== (string)auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if (!$payment->isPending()) {
            return redirect()->back()->with('error', 'Hanya pembayaran dengan status "pending" yang dapat dibatalkan.');
        }

        $payment->update(['status' => 'cancelled']);

        EscrowTransaction::where('payment_id', $payment->id)->update([
            'status' => 'cancelled',
            'disbursement_status' => 'payment_cancelled',
        ]);

        if ($project = Project::find($payment->project_id)) {
            $project->update([
                'status' => 'awaiting_payment',
                'escrow_status' => 'unpaid',
            ]);
        }

        Log::info("Payment cancelled: {$payment->_id}");

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }

    private function sendUserNotification(?User $user, object $notification, string $context): void
    {
        if (!$user || !method_exists($user, 'notifyNow')) {
            return;
        }

        try {
            $user->notifyNow($notification);
        } catch (\Throwable $notificationError) {
            Log::warning('Payment notification failed', [
                'context' => $context,
                'user_id' => (string) $user->getKey(),
                'notification' => $notification::class,
                'message' => $notificationError->getMessage(),
            ]);
        }
    }
}
