<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Pembayaran - Konekin Admin</title>
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
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Manajemen Pembayaran 💸</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau arus kas dan kelola pencairan dana proyek.</p>
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
                                @if($tx->status === 'held')
                                <form action="{{ route('admin.escrow.release', $tx->id) }}" method="POST" onsubmit="return confirm('Cairkan dana ke kreator?')">
                                    @csrf
                                    <button type="submit" class="bg-[#2563EB] text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#1E3A8A] transition-all">
                                        Release Funds
                                    </button>
                                </form>
                                @else
                                <span class="text-[10px] font-black text-slate-300 uppercase italic">No Action</span>
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
    </main>
</body>
</html>
