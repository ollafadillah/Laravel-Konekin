<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Services\CloudinaryService;
use App\Helpers\CurrencyHelper;
use App\Notifications\PaymentInvoiceCreated;
use App\Notifications\PaymentProofSubmitted;
use App\Notifications\PaymentVerified;
use App\Notifications\PaymentRejected;
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

        // Check if payment already exists
        $existingPayment = Payment::where('project_id', $projectId)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if ($existingPayment) {
            return redirect()->back()->with('error', 'Pembayaran untuk proyek ini sudah ada.');
        }

        // Create payment record
        $payment = new Payment([
            'project_id' => $projectId,
            'client_id' => $project->client_id,
            'client_name' => $project->client_name,
            'client_avatar' => $project->client_avatar,
            'amount' => CurrencyHelper::extract($project->budget),
            'currency' => 'IDR',
            'description' => "Pembayaran untuk proyek: {$project->title}",
            'status' => 'pending',
        ]);

        $payment->payment_number = $payment->generatePaymentNumber();
        $payment->save();

        // Send notification to UMKM
        $umkm = User::find($project->client_id);
        if ($umkm) {
            $umkm->notify(new PaymentInvoiceCreated($payment));
        }

        return redirect()->route('payments.show', $payment->_id)
            ->with('success', 'Invoice pembayaran berhasil dibuat. Silakan lakukan pembayaran dan upload bukti.');
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
            'payment_method' => 'required|string|in:transfer,card,e-wallet,other',
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

            // Send notification to admin
            try {
                $admins = User::where('type', 'admin')->get();
                if ($admins && count($admins) > 0) {
                    foreach ($admins as $admin) {
                        if (method_exists($admin, 'notify')) {
                            $admin->notify(new PaymentProofSubmitted($payment));
                        }
                    }
                }
            } catch (\Exception $notificationError) {
                Log::warning('Failed to send notification to admin: ' . $notificationError->getMessage());
            }

            Log::info("Payment proof uploaded: {$payment->_id}");

            return redirect()->route('payments.show', $payment->_id)
                ->with('success', 'Bukti pembayaran berhasil di-upload. Admin akan memverifikasi dalam 1x24 jam.');
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

        if (!$payment->isPaid()) {
            return redirect()->back()->with('error', 'Hanya pembayaran dengan status "paid" yang dapat diverifikasi.');
        }

        $payment->update([
            'status' => 'paid',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Send notification to UMKM
        $umkm = User::find($payment->client_id);
        if ($umkm) {
            $umkm->notify(new PaymentVerified($payment));
        }

        // Update project status to completed
        $project = Project::find($payment->project_id);
        if ($project) {
            $project->update(['status' => 'completed']);
        }

        Log::info("Payment verified: {$payment->_id}");

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
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

        // Send notification to UMKM
        $umkm = User::find($payment->client_id);
        if ($umkm) {
            $umkm->notify(new PaymentRejected($payment));
        }

        $payment->update([
            'status' => 'failed',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
        ]);

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

        Log::info("Payment cancelled: {$payment->_id}");

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
