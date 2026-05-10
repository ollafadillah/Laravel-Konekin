@extends('layouts.app')

@section('title', 'Project Approval - Admin Dashboard')

@section('content')
<x-dashboard-nav-admin />

<main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-12">
        <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Approval Proyek & Pencairan Dana 🎯</h1>
        <p class="text-[#1E3A8A]/60 font-medium text-lg">Review proyek yang sudah 100% selesai dan approve untuk pencairan dana ke creative workers.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Pending Approval -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors text-amber-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Menunggu Approval</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $pendingCount }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Proyek siap direview</p>
        </div>

        <!-- Total Pending Amount -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition-colors text-blue-600">
                    <i class="fas fa-money-bill text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Nilai Menunggu Disetujui</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Akan dicairkan jika diapprove</p>
        </div>

        <!-- In Disbursement -->
        <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-500 group-hover:text-white transition-colors text-blue-600">
                    <i class="fas fa-arrow-right text-2xl"></i>
                </div>
            </div>
            <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Sedang Diproses Pencairan</h3>
            <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $disburssingCount }}</p>
            <p class="text-xs text-[#1E3A8A]/40 font-medium mt-2">Dalam antrian pencairan</p>
        </div>
    </div>

    <!-- Pending Projects Table -->
    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-[#2563EB]/5">
            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Proyek Menunggu Approval</h2>
        </div>

        @if($projects->count() > 0)
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
                    @foreach($projects as $project)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">{{ $project->title }}</div>
                            <div class="text-xs text-[#1E3A8A]/40 font-medium">UMKM: {{ $project->client_name }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">{{ $project->selected_creative_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#2563EB]" style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                                <span class="font-bold text-[#1E3A8A] text-sm">{{ $project->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="font-bold text-[#1E3A8A]">Rp {{ number_format($project->escrow->net_amount ?? 0, 0, ',', '.') }}</div>
                            <div class="text-[9px] text-[#1E3A8A]/40 font-black uppercase">Fee: Rp {{ number_format($project->escrow->platform_fee ?? 0, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex gap-2 flex-wrap">
                                <form action="{{ route('admin.projects.approve', $project->id) }}" method="POST" onsubmit="return confirm('Approve & cairkan dana ke kreator?')" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 bg-[#10B981] text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#059669] transition-all">
                                        Approve & Cairkan
                                    </button>
                                </form>
                                <button onclick="openRejectModal('{{ $project->id }}')" class="px-3 py-2 bg-red-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all">
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-[#1E3A8A]/40 font-bold">Tidak ada proyek menunggu approval</div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] max-w-md w-full p-8">
            <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-4">Reject Proyek</h2>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-[#1E3A8A] mb-2">Alasan Rejection</label>
                    <textarea name="reason" class="w-full p-3 border border-[#2563EB]/20 rounded-xl focus:outline-none focus:border-[#2563EB]" rows="4" placeholder="Jelaskan alasan penolakan..." required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300 transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition-all">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Information Box -->
    <div class="mt-12 p-8 rounded-[2.5rem] bg-[#EFF6FF] border-2 border-[#2563EB] space-y-4">
        <div class="flex gap-4">
            <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white flex items-center justify-center shrink-0 font-bold">
                <i class="fas fa-info"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#1E3A8A] mb-2">Workflow Approval Proyek</h3>
                <ul class="space-y-2 text-sm text-[#1E3A8A]/70 font-medium">
                    <li><strong>✓ 100% Complete:</strong> Creative worker menyelesaikan proyek (progress 100%)</li>
                    <li><strong>✓ UMKM Approve:</strong> UMKM menyetujui proyek selesai</li>
                    <li><strong>⏳ Pending Approval (Saat Ini):</strong> Proyek menunggu review admin</li>
                    <li><strong>→ Approve:</strong> Admin approve → Escrow status: releasing → Automatic transfer ke rekening kreator</li>
                    <li><strong>✗ Reject:</strong> Admin reject → Proyek kembali ke in_progress → Kreator bisa revisi</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script>
function openRejectModal(projectId) {
    document.getElementById('rejectForm').action = `/admin/projects/${projectId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('rejectModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'rejectModal') closeRejectModal();
});
</script>
@endsection
