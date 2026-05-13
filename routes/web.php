<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EscrowController;
use App\Http\Controllers\AdminEscrowController;
use App\Http\Controllers\ProjectApprovalController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MlRecommendationController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PaymentVerificationController;
use App\Http\Controllers\PaymentReceiptController;
use Illuminate\Support\Facades\Route;

// Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Http\Controllers\CreatorController;
use App\Http\Controllers\PaymentController;

// Halaman Cari Kreator
Route::get('/kreator', [CreatorController::class, 'index'])->name('kreator.index');

// Halaman Proyek UMKM Publik
Route::get('/umkm', [ProjectController::class, 'publicIndex'])->name('umkm.index');

// Halaman Tentang Kami
Route::get('/tentang-kami', function () {
    return view('about');
})->name('about');

// Halaman Syarat dan Ketentuan
Route::get('/syarat-ketentuan', function () {
    return view('terms-conditions');
})->name('terms-conditions');

// Halaman Kebijakan Privasi
Route::get('/kebijakan-privasi', function () {
    return view('privacy-policy');
})->name('privacy-policy');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:usage');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/register-role', function () {
    return view('auth.register-role'); // Halaman pilih role
})->name('register.role');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:usage');

// Dashboard & Profile & Projects & Portfolio
Route::middleware(['auth', 'throttle:usage'])->group(function () {
    Route::get('/kreator/{id}', [CreatorController::class, 'show'])->name('kreator.show');

    Route::get('/dashboard/umkm', [DashboardController::class, 'umkmDashboard'])->name('dashboard.umkm');
    Route::get('/dashboard/creative', [DashboardController::class, 'creativeWorkerDashboard'])->name('dashboard.creative');
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    Route::get('/penghasilan', [DashboardController::class, 'creativeEarnings'])->name('earnings.index');
    Route::get('/rekomendasi-kreator', [MlRecommendationController::class, 'index'])->name('rekomendasi.kreator');
    Route::post('/rekomendasi-kreator', [MlRecommendationController::class, 'store'])->name('rekomendasi.kreator.store');
    Route::post('/rekomendasi-kreator/hire', [MlRecommendationController::class, 'hire'])->name('rekomendasi.kreator.hire');

    // Admin Management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/{id}/warn', [AdminController::class, 'warnUser'])->name('admin.users.warn');
    Route::post('/admin/users/{id}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::post('/admin/users/{id}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');

    Route::get('/admin/projects', [AdminController::class, 'projects'])->name('admin.projects');
    Route::delete('/admin/projects/{id}', [AdminController::class, 'destroyProject'])->name('admin.projects.destroy');
    Route::get('/admin/jobs', [AdminController::class, 'jobs'])->name('admin.jobs');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Project Routes
    Route::get('/cari-proyek', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/proyek/buat', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/proyek', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/proyek/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/proyek/{id}/apply', [ProjectController::class, 'apply'])->name('projects.apply');
    Route::get('/proposal/{applicationId}/preview', [ProjectController::class, 'previewProposal'])->name('project-applications.proposal.preview');
    Route::get('/proposal/{applicationId}/download', [ProjectController::class, 'downloadProposal'])->name('project-applications.proposal.download');
    Route::post('/proyek/{id}/accept', [ProjectController::class, 'acceptInvitation'])->name('projects.accept');
    Route::post('/proyek/{id}/reject', [ProjectController::class, 'rejectInvitation'])->name('projects.reject');
    Route::get('/progress-proyek', [ProjectController::class, 'progress'])->name('projects.progress');
    Route::post('/progress-proyek/{id}/approve/{applicationId}', [ProjectController::class, 'approveApplication'])->name('projects.progress.approve');
    Route::delete('/progress-proyek/{id}', [ProjectController::class, 'destroyProgressProject'])->name('projects.progress.destroy');
    Route::post('/progress-proyek/{id}/approve-completion', [ProjectApprovalController::class, 'approveCompletion'])->name('projects.progress.approve-completion');
    Route::post('/progress-proyek/{id}/revision', [ProjectApprovalController::class, 'requestRevision'])->name('projects.progress.revision');
    Route::post('/progress-proyek/{id}/dispute', [ProjectApprovalController::class, 'openDispute'])->name('projects.progress.dispute');
    Route::get('/progress-proyek-kreator', [ProjectController::class, 'creativeProgress'])->name('projects.progress.creative');
    Route::post('/progress-proyek-kreator/{id}', [ProjectController::class, 'storeCreativeProgress'])->name('projects.progress.creative.update');

    // Portfolio Routes
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::delete('/portfolio/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    
    // Admin Escrow
    Route::get('/admin/escrow', [AdminEscrowController::class, 'index'])->name('admin.escrow.index');
    Route::post('/admin/escrow/{id}/release', [AdminEscrowController::class, 'release'])->name('admin.escrow.release');

    // Admin Payment Verification (separate from escrow)
    Route::get('/admin/verifikasi-resi', [PaymentVerificationController::class, 'index'])->name('admin.payment-verification.index');
    Route::post('/admin/verifikasi-resi/{escrowId}', [PaymentReceiptController::class, 'adminVerify'])->name('admin.payment.verify');

    // Admin Project Approvals (for completion & disbursement)
    Route::get('/admin/project-approvals', function () {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $escrows = \App\Models\EscrowTransaction::where('status', 'held')
            ->with(['project', 'payer', 'payee'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $projects = $escrows
            ->filter(fn ($escrow) => ($escrow->project->status ?? null) === 'pending_admin_approval')
            ->map(function ($escrow) {
                return (object) [
                    'id' => (string) $escrow->project_id,
                    'title' => $escrow->project->title ?? 'N/A',
                    'client_name' => $escrow->payer->name ?? 'N/A',
                    'selected_creative_name' => $escrow->payee->name ?? 'N/A',
                    'progress_percentage' => (int) ($escrow->project->progress_percentage ?? 0),
                    'escrow' => $escrow,
                ];
            })
            ->values(); // Reset keys

        $pendingCount = $projects->count();
        $totalPending = $projects->sum(fn ($p) => (int) $p->escrow->net_amount);
        $disburssingCount = \App\Models\EscrowTransaction::where('status', 'releasing')->count();

        return view('admin.project-approvals.index', compact('projects', 'pendingCount', 'totalPending', 'disburssingCount'));
    })->name('admin.project-approvals.index');

    Route::post('/admin/projects/{id}/approve', [ProjectApprovalController::class, 'adminApproveCompletion'])->name('admin.projects.approve');
    Route::post('/admin/projects/{id}/reject', [ProjectApprovalController::class, 'adminRejectCompletion'])->name('admin.projects.reject');
    Route::post('/admin/disputes/{id}/resolve', [ProjectApprovalController::class, 'resolveDispute'])->name('admin.disputes.resolve');

    // Rating Routes
    Route::post('/rating', [RatingController::class, 'store'])->name('rating.store');

    // Onboarding Routes
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Payment Routes
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/proyek/{projectId}/pembayaran/buat', [PaymentController::class, 'generatePayment'])->name('payments.generate');
    Route::get('/pembayaran/{paymentId}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/pembayaran/{paymentId}/bukti-upload', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::post('/pembayaran/{paymentId}/batalkan', [PaymentController::class, 'cancel'])->name('payments.cancel');
    
    // Admin Payment Routes
    Route::get('/admin/pembayaran', [AdminController::class, 'payments'])->name('admin.payments.index');
    Route::get('/admin/pembayaran/{paymentId}/detail', [AdminController::class, 'paymentDetail'])->name('admin.payments.detail');
    Route::post('/admin/pembayaran/{paymentId}/verifikasi', [PaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::post('/admin/pembayaran/{paymentId}/tolak', [PaymentController::class, 'reject'])->name('admin.payments.reject');

});

// Chatbot Route
Route::post('/chat/ask', [ChatbotController::class, 'ask'])->name('chat.ask')->middleware('throttle:usage');

// Public Webhook (No Auth)
Route::post('/payment/midtrans/notification', [EscrowController::class, 'handleMidtransNotification'])->name('midtrans.notification');
