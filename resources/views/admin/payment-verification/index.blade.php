<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Pembayaran - Konekin Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-admin />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Verifikasi Resi Pembayaran 📋</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Verifikasi bukti pembayaran dari UMKM sebelum kreator mulai bekerja.</p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-4 text-emerald-700 font-bold text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-3xl flex items-center gap-4 text-red-700 font-bold text-sm">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Pending Verification -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors text-amber-600">
                        <i class="fas fa-file-check text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Menunggu Verifikasi</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $pendingCount }}</p>
                <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Resi siap diperiksa</p>
            </div>

            <!-- Total Pending Amount -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition-colors text-blue-600">
                        <i class="fas fa-money-bill text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Nilai Pending</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
                <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Menunggu persetujuan</p>
            </div>

            <!-- Verified Today -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-green-50 rounded-2xl group-hover:bg-green-500 group-hover:text-white transition-colors text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Terverifikasi Hari Ini</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $verifiedToday }}</p>
                <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">{{ now()->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Payment Verification List -->
        @if($pendingCount > 0)
            @foreach($pendingVerificationProjects as $project)
            <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden mb-6">
                <!-- Header -->
                <div class="p-6 border-b border-[#2563EB]/5 bg-slate-50/50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-display text-lg font-bold text-[#1E3A8A]">{{ $project->title }}</h3>
                            <p class="text-sm text-[#1E3A8A]/60">UMKM: {{ $project->client_name }} | Kreator: {{ $project->selected_creative_name }}</p>
                        </div>
                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">Menunggu</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Payment Details -->
                        <div class="space-y-3">
                            <h4 class="font-bold text-[#1E3A8A] text-sm uppercase tracking-wider">Detail Pembayaran</h4>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <p class="text-xs text-[#1E3A8A]/60 font-bold">Bank</p>
                                <p class="font-bold text-[#1E3A8A]">{{ $project->payment_bank_name ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <p class="text-xs text-[#1E3A8A]/60 font-bold">Metode</p>
                                <p class="font-bold text-[#1E3A8A] capitalize">{{ $project->payment_method ?? '-' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <p class="text-xs text-[#1E3A8A]/60 font-bold">Tanggal Pembayaran</p>
                                <p class="font-bold text-[#1E3A8A]">{{ $project->payment_date ? \Carbon\Carbon::parse($project->payment_date)->translatedFormat('d M Y') : 'N/A' }}</p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <p class="text-xs text-[#1E3A8A]/60 font-bold">Nominal Proyek</p>
                                <p class="font-bold text-[#2563EB] text-lg">Rp {{ number_format($project->escrow->amount, 0, ',', '.') }}</p>
                            </div>
                            @if($project->payment_notes)
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                                <p class="text-xs text-blue-600 font-bold">Catatan UMKM</p>
                                <p class="text-sm text-[#1E3A8A]">{{ $project->payment_notes }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Receipt Image Preview -->
                        <div>
                            <h4 class="font-bold text-[#1E3A8A] text-sm uppercase tracking-wider mb-3">Foto Resi Pembayaran</h4>
                            @if($project->receipt_image)
                                <img src="{{ Storage::url($project->receipt_image) }}" alt="Receipt" class="w-full rounded-lg border-2 border-[#2563EB]/20 max-h-64 object-cover cursor-pointer hover:shadow-lg transition-shadow" onclick="openImageModal(this.src)">
                                <p class="text-xs text-[#1E3A8A]/40 text-center mt-2">Klik untuk lihat ukuran penuh</p>
                            @else
                                <div class="w-full h-64 bg-slate-100 rounded-lg flex items-center justify-center text-[#1E3A8A]/40">
                                    <i class="fas fa-image text-5xl"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <div class="border-t border-[#2563EB]/5 pt-6">
                        <form action="{{ route('admin.payment.verify', $project->escrow->id) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-bold text-[#1E3A8A] mb-2">Catatan Verifikasi (Opsional)</label>
                                <textarea name="verification_notes" rows="2" placeholder="Contoh: Resi sudah diverifikasi dan sesuai dengan nominal proyek..." class="w-full px-4 py-3 border border-[#2563EB]/20 rounded-xl focus:outline-none focus:border-[#2563EB] font-medium text-[#1E3A8A]"></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" name="verified" value="0" class="flex-1 px-4 py-3 bg-red-500 text-white rounded-lg text-sm font-black uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-times"></i> Tolak Resi
                                </button>
                                <button type="submit" name="verified" value="1" class="flex-1 px-4 py-3 bg-[#10B981] text-white rounded-lg text-sm font-black uppercase tracking-widest hover:bg-[#059669] transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Terima & Aktivkan Escrow
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-white p-16 text-center rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm">
                <i class="fas fa-check-circle text-6xl text-[#10B981] mb-4"></i>
                <h3 class="font-display text-2xl font-bold text-[#1E3A8A] mb-2">Semua Resi Sudah Terverifikasi</h3>
                <p class="text-[#1E3A8A]/60 font-medium">Tidak ada pembayaran yang menunggu verifikasi saat ini.</p>
            </div>
        @endif
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4" onclick="closeImageModal(event)">
        <div class="bg-white rounded-lg max-w-2xl max-h-[90vh] overflow-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b border-[#2563EB]/5 p-4 flex justify-between items-center">
                <h3 class="font-bold text-[#1E3A8A]">Resi Pembayaran</h3>
                <button onclick="closeImageModal()" class="text-[#1E3A8A]/60 hover:text-[#1E3A8A]">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <img id="modalImage" src="" alt="Receipt" class="w-full">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal(event) {
            if (event && event.target.id !== 'imageModal') return;
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
