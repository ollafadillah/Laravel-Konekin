@extends('layouts.app')

@section('title', 'Rekam Jejak Penghasilan - Konekin')

@section('content')
<x-dashboard-nav-creative />

<main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">
    <div class="mb-12">
        <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Rekam Jejak Penghasilan 💰</h1>
        <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau semua penghasilan dari proyek yang diselesaikan, termasuk status pencairan dana.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Total Earned -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Penghasilan Diterima</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($totalEarned, 0, ',', '.') }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Dari {{ $releasedCount }} proyek selesai</p>
        </div>

        <!-- Pending Release -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors text-amber-600">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Menunggu Approval Admin</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($pendingApproval, 0, ',', '.') }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Proyek completed, menunggu admin review</p>
        </div>

        <!-- In Disbursement -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition-colors text-blue-600">
                    <i class="fas fa-arrow-up-right text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Sedang Diproses Pencairan</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($inDisbursement, 0, ',', '.') }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Akan sampai dalam 1-2 hari kerja</p>
        </div>
    </div>

    <!-- Escrow Transactions Table -->
    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-[#2563EB]/5">
            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Histori Transaksi Escrow</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-[#2563EB]/5">
                        <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Proyek</th>
                        <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">UMKM</th>
                        <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Nominal</th>
                        <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Status Escrow</th>
                        <th class="px-8 py-6 text-[11px] font-black text-[#1E3A8A]/40 uppercase tracking-widest">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2563EB]/5">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">{{ $tx->project->title ?? 'N/A' }}</div>
                            <div class="text-xs text-[#1E3A8A]/40 font-medium">{{ substr($tx->midtrans_order_id, 0, 20) }}...</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">{{ $tx->payer->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($tx->net_amount, 0, ',', '.') }}</div>
                            <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Fee: Rp {{ number_format($tx->platform_fee, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            @if($tx->status === 'held')
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">Held (Aman)</span>
                            @elseif($tx->status === 'releasing')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-wider">Releasing</span>
                            @elseif($tx->status === 'released')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-wider">Released ✓</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-wider">{{ $tx->status }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm font-medium text-[#1E3A8A]">{{ $tx->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-xs text-[#1E3A8A]/40 font-medium">{{ $tx->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-[#1E3A8A]/40 font-bold">Belum ada transaksi escrow</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Information Box -->
    <div class="mt-12 p-8 rounded-[2.5rem] bg-[#EFF6FF] border-2 border-[#2563EB] space-y-4">
        <div class="flex gap-4">
            <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white flex items-center justify-center shrink-0 font-bold">
                <i class="fas fa-info"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#1E3A8A] mb-2">Cara Kerja Pembayaran Escrow</h3>
                <ul class="space-y-2 text-sm text-[#1E3A8A]/70 font-medium">
                    <li><strong>1. Held (Aman):</strong> UMKM sudah bayar, dana ditahan platform untuk keamanan kedua belah pihak</li>
                    <li><strong>2. Releasing:</strong> Proyek 100% selesai, admin sedang memproses pencairan dana</li>
                    <li><strong>3. Released (Diterima):</strong> Dana berhasil dikirim ke rekening bank Anda</li>
                    <li><strong>Platform Fee (15%):</strong> Potongan dari nominal proyek untuk operasional platform</li>
                </ul>
            </div>
        </div>
    </div>
</main>
@endsection
