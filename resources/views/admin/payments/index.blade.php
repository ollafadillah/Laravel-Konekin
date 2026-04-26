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
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Verifikasi Pembayaran 💰</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Kelola dan verifikasi bukti pembayaran dari UMKM.</p>
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

        <!-- Tabs for Payment Status -->
        <div class="mb-8 flex gap-2 border-b border-[#2563EB]/10">
            <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="px-6 py-3 font-bold text-sm border-b-2 transition-all {{ request('status') === 'paid' || !request('status') ? 'border-[#2563EB] text-[#2563EB]' : 'border-transparent text-[#1E3A8A]/40 hover:text-[#1E3A8A]' }}">
                <i class="fas fa-clock"></i> Menunggu Verifikasi ({{ $pendingCount ?? 0 }})
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'verified']) }}" class="px-6 py-3 font-bold text-sm border-b-2 transition-all {{ request('status') === 'verified' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-[#1E3A8A]/40 hover:text-[#1E3A8A]' }}">
                <i class="fas fa-check-circle"></i> Terverifikasi ({{ $verifiedCount ?? 0 }})
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'failed']) }}" class="px-6 py-3 font-bold text-sm border-b-2 transition-all {{ request('status') === 'failed' ? 'border-red-500 text-red-600' : 'border-transparent text-[#1E3A8A]/40 hover:text-[#1E3A8A]' }}">
                <i class="fas fa-times-circle"></i> Ditolak ({{ $rejectedCount ?? 0 }})
            </a>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">No. Invoice & Proyek</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">UMKM</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Nominal</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Metode</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Tanggal Pembayaran</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#2563EB]/5">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-bold text-[#1E3A8A]">{{ $payment->payment_number }}</div>
                                <div class="text-xs text-[#1E3A8A]/40 font-medium">{{ $payment->project->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    @if($payment->client_avatar)
                                        <img src="{{ $payment->client_avatar }}" alt="{{ $payment->client_name }}" class="w-8 h-8 rounded-lg object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-[#2563EB]/10 flex items-center justify-center">
                                            <i class="fas fa-building text-[#2563EB]/40 text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-sm">{{ $payment->client_name }}</div>
                                        <div class="text-xs text-[#1E3A8A]/40">{{ $payment->client_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <div class="text-xs text-[#1E3A8A]/40 font-medium">{{ $payment->currency }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    @switch($payment->payment_method)
                                        @case('transfer')
                                            <i class="fas fa-bank"></i> Transfer
                                        @break
                                        @case('card')
                                            <i class="fas fa-credit-card"></i> Kartu Kredit
                                        @break
                                        @case('e-wallet')
                                            <i class="fas fa-wallet"></i> E-Wallet
                                        @break
                                        @default
                                            {{ ucfirst($payment->payment_method) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-medium">{{ $payment->payment_date->format('d M Y') ?? 'N/A' }}</div>
                                <div class="text-xs text-[#1E3A8A]/40">{{ $payment->payment_date->format('H:i') ?? 'N/A' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($payment->status === 'paid' && !$payment->verified_at)
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                        <i class="fas fa-hourglass-half"></i> Menunggu
                                    </span>
                                @elseif($payment->status === 'paid' && $payment->verified_at)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                        <i class="fas fa-check"></i> Terverifikasi
                                    </span>
                                @elseif($payment->status === 'failed')
                                    <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-wider">{{ $payment->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @if($payment->status === 'paid' && !$payment->verified_at)
                                    <div class="flex gap-2">
                                        <button onclick="openDetailModal('{{ $payment->_id }}')" class="bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-blue-100 transition-all" title="Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <form action="{{ route('admin.payments.verify', $payment->_id) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran ini?')" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-emerald-100 transition-all" title="Verifikasi">
                                                <i class="fas fa-check"></i> Verifikasi
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal('{{ $payment->_id }}')" class="bg-red-50 text-red-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-red-100 transition-all" title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>
                                @elseif($payment->verified_at)
                                    <button onclick="openDetailModal('{{ $payment->_id }}')" class="bg-emerald-50 text-emerald-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-emerald-100 transition-all">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                @elseif($payment->status === 'failed')
                                    <button onclick="openDetailModal('{{ $payment->_id }}')" class="bg-red-50 text-red-600 px-3 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-red-100 transition-all">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                @else
                                    <span class="text-[10px] font-black text-slate-300 uppercase italic">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="text-6xl mb-4 text-[#1E3A8A]/20">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p class="text-[#1E3A8A] font-bold">Tidak ada pembayaran</p>
                                    <p class="text-sm text-[#1E3A8A]/40 font-medium">Belum ada pembayaran dengan status ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl border border-white/20 transform transition-all">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-8 text-white">
                        <h3 class="text-2xl font-display font-bold">Detail Pembayaran</h3>
                    </div>
                    <div id="modal-content" class="p-8 max-h-[70vh] overflow-y-auto">
                        <div class="flex justify-center py-8">
                            <div class="animate-spin">
                                <i class="fas fa-spinner text-3xl text-[#2563EB]"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/80 px-8 py-4 border-t border-slate-100 flex justify-end gap-4">
                        <button type="button" onclick="closeDetailModal()" class="px-6 py-3 font-bold text-slate-600 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-red-500 to-orange-600 p-8 text-white relative">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-display font-bold mb-1">Tolak Pembayaran</h3>
                        <p class="text-red-100 text-sm">Berikan alasan penolakan pembayaran</p>
                    </div>
                </div>
                <form id="reject-form" method="POST" action="" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-[#1E3A8A] mb-3">Alasan Penolakan</label>
                        <textarea name="rejection_reason" rows="4" class="w-full px-5 py-4 rounded-2xl border-2 border-[#2563EB]/10 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all outline-none" placeholder="Tuliskan detail alasan penolakan di sini..." required></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" onclick="closeRejectModal()" class="flex-1 py-4 px-6 font-bold text-[#1E3A8A]/60 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="flex-2 py-4 px-10 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-all">Tolak Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(paymentId) {
            const modal = document.getElementById('detail-modal');
            const content = document.getElementById('modal-content');
            modal.classList.remove('hidden');

            // Fetch payment details via AJAX
            fetch(`/admin/pembayaran/${paymentId}/detail`)
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = `<div class="text-center text-red-600"><i class="fas fa-exclamation"></i> Gagal memuat detail pembayaran</div>`;
                });
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }

        function openRejectModal(paymentId) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            form.action = `/admin/pembayaran/${paymentId}/tolak`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
            document.getElementById('reject-form').reset();
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeRejectModal();
            }
        });
    </script>
</body>
</html>
