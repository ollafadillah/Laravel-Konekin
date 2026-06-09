<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pembayaran - Konekin Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('components.fonts')
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
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Manajemen Pembayaran & Escrow 💸</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau arus kas, approval proyek, dan kelola pencairan dana.</p>
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

        <!-- Tabs Navigation -->
        <div class="mb-8 flex gap-2 border-b border-[#2563EB]/10 overflow-x-auto pb-2">
            <button onclick="switchTab('transactions')" id="tab-transactions-btn" class="px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-2 border-[#2563EB] text-[#2563EB] transition-all whitespace-nowrap">
                <i class="fas fa-list-ul mr-2"></i> Semua Transaksi
            </button>
            <button onclick="switchTab('pending')" id="tab-pending-btn" class="px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-2 border-transparent text-[#1E3A8A]/60 hover:text-[#1E3A8A] transition-all whitespace-nowrap">
                <i class="fas fa-check-double mr-2"></i> Pending Approval ({{ $pendingApprovalCount }})
            </button>
            <button onclick="switchTab('disputes')" id="tab-disputes-btn" class="px-6 py-3 font-bold text-sm uppercase tracking-wider border-b-2 border-transparent text-[#1E3A8A]/60 hover:text-[#1E3A8A] transition-all whitespace-nowrap">
                <i class="fas fa-scale-balanced mr-2"></i> Dispute ({{ $disputeCount ?? 0 }})
            </button>
        </div>

        <!-- Tab: Semua Transaksi -->
        <div id="tab-transactions" class="tab-content">
            <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Transaksi & Proyek</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Pihak Terkait</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Status Escrow</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Nominal (Net)</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2563EB]/5">
                            @forelse($transactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">{{ $tx->midtrans_order_id }}</div>
                                    <div class="text-xs text-[#1E3A8A]/40 font-bold">{{ $tx->project->title ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-xs">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-black uppercase">UMKM</span>
                                            <span class="font-bold">{{ $tx->payer->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 bg-purple-50 text-purple-600 rounded text-[9px] font-black uppercase">Creator</span>
                                            <span class="font-bold">{{ $tx->payee->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($tx->status === 'held')
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">Held (Secure)</span>
                                    @elseif($tx->status === 'released')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">Released</span>
                                    @elseif($tx->status === 'releasing')
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-wider">Releasing</span>
                                    @elseif($tx->status === 'pending')
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-wider">Pending Pay</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-wider">{{ $tx->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($tx->net_amount, 0, ',', '.') }}</div>
                                    <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Fee: Rp {{ number_format($tx->platform_fee, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($tx->status === 'held' && (int)($tx->project->progress_percentage ?? 0) < 100)
                                    <span class="text-[10px] font-black text-slate-300 uppercase italic">Waiting 100%</span>
                                    @else
                                    <span class="text-[10px] font-black text-slate-300 uppercase italic">Processed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center text-[#1E3A8A]/40 font-bold">Belum ada transaksi escrow.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Pending Approval -->
        <div id="tab-pending" class="tab-content hidden">
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pending Count -->
                <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors text-amber-600">
                            <i class="fas fa-hourglass-half text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Menunggu Approval</h3>
                    <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $pendingApprovalCount }}</p>
                    <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Proyek 100% complete & siap approve</p>
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
                    <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Siap untuk pencairan</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                @if($pendingApprovalCount > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Proyek & UMKM</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Creative Worker</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Progress</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Nominal Escrow</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2563EB]/5">
                            @foreach($pendingApprovalProjects as $project)
                            @if($project->escrow)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">{{ $project->title ?? 'Proyek Tanpa Nama' }}</div>
                                    <div class="text-xs text-[#1E3A8A]/40 font-medium">UMKM: {{ $project->client_name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">{{ $project->selected_creative_name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#2563EB]" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                        <span class="font-bold text-[#1E3A8A] text-sm">{{ $project->progress_percentage ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($project->escrow->net_amount ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Fee: Rp {{ number_format($project->escrow->platform_fee ?? 0, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('admin.escrow.release', $project->escrow->id) }}" method="POST" onsubmit="return confirm('Approve & cairkan dana ke kreator?')" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 bg-[#10B981] text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#059669] transition-all">
                                            Approve & Cairkan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center text-[#1E3A8A]/40 font-bold">Tidak ada proyek menunggu approval</div>
                @endif
            </div>
        </div>

        <!-- Tab: Dispute Resolution -->
        <div id="tab-disputes" class="tab-content hidden">
            <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                @if(($disputeCount ?? 0) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Proyek & Pihak</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Alasan Dispute</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Dana Ditahan</th>
                                <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Keputusan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#2563EB]/5">
                            @foreach($disputeEscrows as $escrow)
                            <tr class="hover:bg-slate-50/50 transition-colors align-top">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">{{ $escrow->project->title ?? 'N/A' }}</div>
                                    <div class="text-xs text-[#1E3A8A]/50 mt-2">UMKM: {{ $escrow->payer->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-[#1E3A8A]/50">Creative: {{ $escrow->payee->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-8 py-6 max-w-md">
                                    <p class="text-sm text-[#1E3A8A]/70 font-medium leading-7">{{ $escrow->dispute_reason ?? $escrow->project->dispute_reason ?? '-' }}</p>
                                    <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-3">Dana dibekukan sampai diputuskan</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($escrow->amount ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Fee platform 15%: Rp {{ number_format($escrow->platform_fee ?? 0, 0, ',', '.') }}</div>
                                    <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Net kreator: Rp {{ number_format($escrow->net_amount ?? 0, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-6 min-w-[320px]">
                                    <form action="{{ route('admin.disputes.resolve', $escrow->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <select name="resolution" required class="w-full rounded-xl border border-[#2563EB]/20 px-4 py-3 text-sm font-bold">
                                            <option value="">Pilih keputusan</option>
                                            <option value="release">Cairkan ke creative worker</option>
                                            <option value="refund">Kembalikan ke UMKM</option>
                                        </select>
                                        <textarea name="admin_resolution_notes" rows="3" required class="w-full rounded-xl border border-[#2563EB]/20 px-4 py-3 text-sm" placeholder="Catatan keputusan admin/mediator..."></textarea>
                                        <button type="submit" class="w-full px-4 py-3 bg-[#1E3A8A] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#2563EB] transition-all" onclick="return confirm('Selesaikan dispute dengan keputusan ini?')">
                                            Simpan Keputusan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center text-[#1E3A8A]/40 font-bold">Tidak ada dispute aktif</div>
                @endif
            </div>
        </div>
    </main>

    <style>
        .tab-content {
            display: block;
        }
        .tab-content.hidden {
            display: none !important;
        }
    </style>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Remove active state from all buttons
            document.querySelectorAll('button[id$="-btn"]').forEach(btn => {
                btn.classList.remove('border-[#2563EB]', 'text-[#2563EB]');
                btn.classList.add('border-transparent', 'text-[#1E3A8A]/60');
            });

            // Show selected tab
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
            
            // Add active state to selected button
            const selectedBtn = document.getElementById('tab-' + tabName + '-btn');
            if (selectedBtn) {
                selectedBtn.classList.remove('border-transparent', 'text-[#1E3A8A]/60');
                selectedBtn.classList.add('border-[#2563EB]', 'text-[#2563EB]');
            }
        }
    </script>
</body>
</html>
