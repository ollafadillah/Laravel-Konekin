<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Konekin</title>

    <!-- Fonts -->
    @include('components.fonts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-admin />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Welcome Header -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Pusat Kendali Admin</h1>
                <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau pengguna, proyek, pembayaran, dan escrow dalam satu dashboard operasional Konekin.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.payment-verification.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#1E3A8A] text-white rounded-2xl text-sm font-bold shadow-lg shadow-[#1E3A8A]/10 hover:bg-[#2563EB] transition-all">
                    <i class="fas fa-receipt text-xs"></i>
                    Verifikasi Resi
                </a>
                <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-[#1E3A8A] rounded-2xl text-sm font-bold border border-[#2563EB]/10 shadow-sm hover:shadow-md hover:border-[#2563EB]/20 transition-all">
                    <i class="fas fa-users-cog text-xs text-[#2563EB]"></i>
                    Kelola User
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <span class="text-blue-500 text-xs font-bold bg-blue-50 px-2.5 py-1 rounded-full">+{{ $newUsersThisWeek }} Baru</span>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Pengguna</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ number_format($totalUsers) }}</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F5F3FF] rounded-2xl group-hover:bg-[#7C3AED] group-hover:text-white transition-colors text-[#7C3AED]">
                        <i class="fas fa-briefcase text-xl"></i>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Creative Workers</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ number_format($totalCreativeWorkers) }}</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#0A66C2] group-hover:text-white transition-colors text-[#0A66C2]">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Mitra UMKM</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ number_format($totalUMKM) }}</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <i class="fas fa-folder-open text-xl"></i>
                    </div>
                    <span class="text-emerald-600 text-xs font-bold bg-emerald-50 px-2.5 py-1 rounded-full">{{ $activeProjects }} Aktif</span>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Proyek</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ number_format($totalProjects) }}</p>
            </div>
        </div>

        <!-- Priority Work -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Prioritas Admin</h2>
                    <p class="text-sm text-[#1E3A8A]/60 font-medium mt-1">Daftar pekerjaan yang paling perlu dicek terlebih dahulu.</p>
                </div>
                <a href="{{ route('admin.escrow.index') }}" class="hidden sm:inline-flex items-center gap-2 text-[#2563EB] text-sm font-bold hover:underline">
                    Buka Escrow
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.payment-verification.index') }}" class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-4 bg-[#FEF3C7] rounded-3xl group-hover:bg-[#F59E0B] group-hover:text-white transition-all text-[#F59E0B]">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>
                        <i class="fas fa-chevron-right text-[#1E3A8A]/25 group-hover:text-[#2563EB] group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-4xl font-display font-bold text-[#1E3A8A]">{{ $pendingVerificationCount }}</p>
                    <h3 class="font-bold text-[#1E3A8A] mt-2">Resi Menunggu Verifikasi</h3>
                    <p class="text-sm text-[#1E3A8A]/50 font-medium mt-2">Rp {{ number_format($pendingVerificationAmount, 0, ',', '.') }}</p>
                </a>

                <a href="{{ route('admin.project-approvals.index') }}" class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-4 bg-[#F0FDF4] rounded-3xl group-hover:bg-[#16A34A] group-hover:text-white transition-all text-[#16A34A]">
                            <i class="fas fa-check-double text-xl"></i>
                        </div>
                        <i class="fas fa-chevron-right text-[#1E3A8A]/25 group-hover:text-[#2563EB] group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-4xl font-display font-bold text-[#1E3A8A]">{{ $pendingApprovalCount }}</p>
                    <h3 class="font-bold text-[#1E3A8A] mt-2">Proyek Siap Approval</h3>
                    <p class="text-sm text-[#1E3A8A]/50 font-medium mt-2">Rp {{ number_format($pendingApprovalAmount, 0, ',', '.') }}</p>
                </a>

                <a href="{{ route('admin.escrow.index') }}" class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-4 bg-red-50 rounded-3xl group-hover:bg-red-500 group-hover:text-white transition-all text-red-500">
                            <i class="fas fa-scale-balanced text-xl"></i>
                        </div>
                        <i class="fas fa-chevron-right text-[#1E3A8A]/25 group-hover:text-[#2563EB] group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-4xl font-display font-bold text-[#1E3A8A]">{{ $disputeCount }}</p>
                    <h3 class="font-bold text-[#1E3A8A] mt-2">Dispute Terbuka</h3>
                    <p class="text-sm text-[#1E3A8A]/50 font-medium mt-2">{{ $releasingEscrowCount }} pencairan sedang diproses</p>
                </a>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-[#2563EB]/5 bg-[#F8FAFC] flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Aktivitas Terbaru</h2>
                            <p class="text-sm text-[#1E3A8A]/50 font-medium mt-1">Update terbaru dari user, proyek, dan escrow.</p>
                        </div>
                        <a href="{{ route('admin.jobs') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Detail</a>
                    </div>
                    <div class="divide-y divide-[#2563EB]/5">
                        @forelse($recentActivities as $activity)
                            @php
                                $activityClasses = match ($activity->tone) {
                                    'amber' => 'bg-amber-50 text-amber-600',
                                    'emerald' => 'bg-emerald-50 text-emerald-600',
                                    'indigo' => 'bg-indigo-50 text-indigo-600',
                                    'sky' => 'bg-sky-50 text-sky-600',
                                    'red' => 'bg-red-50 text-red-600',
                                    'blue' => 'bg-[#EFF6FF] text-[#2563EB]',
                                    default => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <div class="p-5 flex gap-4 hover:bg-[#F8FAFC] transition-colors">
                                <div class="w-11 h-11 rounded-2xl {{ $activityClasses }} flex items-center justify-center shrink-0">
                                    <i class="fas {{ $activity->icon }} text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                        <p class="text-sm font-bold text-[#1E3A8A] truncate">{{ $activity->title }}</p>
                                        <p class="text-[11px] font-bold text-[#1E3A8A]/35 shrink-0">{{ $activity->date->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-xs text-[#1E3A8A]/50 font-medium mt-1 truncate">{{ $activity->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-sm font-bold text-[#1E3A8A]/35">Belum ada aktivitas terbaru.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-[#2563EB]/5 bg-[#F8FAFC] flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Proyek Terbaru</h2>
                            <p class="text-sm text-[#1E3A8A]/50 font-medium mt-1">Proyek terakhir yang masuk ke platform.</p>
                        </div>
                        <a href="{{ route('admin.projects') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white border-b border-[#2563EB]/5">
                                    <th class="px-6 py-4 text-[11px] font-black uppercase tracking-widest text-[#1E3A8A]/40">Proyek</th>
                                    <th class="px-6 py-4 text-[11px] font-black uppercase tracking-widest text-[#1E3A8A]/40">Status</th>
                                    <th class="px-6 py-4 text-[11px] font-black uppercase tracking-widest text-[#1E3A8A]/40">Budget</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#2563EB]/5">
                                @forelse($recentProjects as $project)
                                    @php
                                        $statusConfig = match ($project->status ?? 'open') {
                                            'open' => ['label' => 'Terbuka', 'class' => 'bg-[#EFF6FF] text-[#2563EB]'],
                                            'hired' => ['label' => 'Kreator Terpilih', 'class' => 'bg-amber-50 text-amber-600'],
                                            'in_progress' => ['label' => 'Dikerjakan', 'class' => 'bg-purple-50 text-purple-600'],
                                            'pending_admin_approval' => ['label' => 'Menunggu Admin', 'class' => 'bg-indigo-50 text-indigo-600'],
                                            'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-50 text-emerald-600'],
                                            'disputed' => ['label' => 'Dispute', 'class' => 'bg-red-50 text-red-600'],
                                            default => ['label' => ucfirst(str_replace('_', ' ', $project->status ?? 'draft')), 'class' => 'bg-slate-100 text-slate-500'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                                        <td class="px-6 py-5">
                                            <p class="font-bold text-[#1E3A8A] max-w-xs truncate">{{ $project->title ?? 'Proyek tanpa judul' }}</p>
                                            <p class="text-xs text-[#1E3A8A]/40 font-bold mt-1 max-w-xs truncate">{{ $project->client_name ?? 'UMKM' }}</p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-bold text-[#1E3A8A]">Rp {{ number_format((int) ($project->budget ?? 0), 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-sm font-bold text-[#1E3A8A]/35">Belum ada proyek baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Ringkasan Escrow</h2>
                            <p class="text-sm text-[#1E3A8A]/50 font-medium mt-1">Status dana platform.</p>
                        </div>
                        <div class="p-3 bg-[#EFF6FF] rounded-2xl text-[#2563EB]">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <div class="bg-[#F8FAFC] rounded-[2rem] p-5 border border-[#2563EB]/5">
                        <p class="text-xs font-black uppercase tracking-widest text-[#1E3A8A]/40">Dana Tertahan</p>
                        <p class="font-display text-3xl font-bold text-[#1E3A8A] mt-2">Rp {{ number_format($heldEscrowAmount, 0, ',', '.') }}</p>
                        <p class="text-sm text-[#1E3A8A]/50 font-medium mt-2">{{ $heldEscrowCount }} transaksi escrow berstatus held.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <a href="{{ route('admin.projects') }}" class="px-4 py-3 rounded-2xl bg-slate-50 text-sm font-bold text-[#1E3A8A] hover:bg-[#EFF6FF] transition-colors">
                            <i class="fas fa-diagram-project mr-2 text-[#2563EB]"></i>Proyek
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="px-4 py-3 rounded-2xl bg-slate-50 text-sm font-bold text-[#1E3A8A] hover:bg-[#EFF6FF] transition-colors">
                            <i class="fas fa-wallet mr-2 text-[#2563EB]"></i>Bayar
                        </a>
                        <a href="{{ route('admin.jobs') }}" class="px-4 py-3 rounded-2xl bg-slate-50 text-sm font-bold text-[#1E3A8A] hover:bg-[#EFF6FF] transition-colors">
                            <i class="fas fa-chart-line mr-2 text-[#2563EB]"></i>Activity
                        </a>
                        <a href="{{ route('admin.escrow.index') }}" class="px-4 py-3 rounded-2xl bg-slate-50 text-sm font-bold text-[#1E3A8A] hover:bg-[#EFF6FF] transition-colors">
                            <i class="fas fa-shield-alt mr-2 text-[#2563EB]"></i>Escrow
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-[#2563EB]/5 bg-[#F8FAFC] flex items-center justify-between">
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Pengguna Terbaru</h2>
                            <p class="text-sm text-[#1E3A8A]/50 font-medium mt-1">Akun baru di platform.</p>
                        </div>
                        <a href="{{ route('admin.users') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Semua</a>
                    </div>
                    <div class="divide-y divide-[#2563EB]/5">
                        @forelse($recentUsers as $recentUser)
                            <div class="p-5 flex items-center gap-4 hover:bg-[#F8FAFC] transition-colors">
                                <img src="{{ $recentUser->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($recentUser->name).'&background=2563EB&color=fff' }}" alt="{{ $recentUser->name }}" class="w-11 h-11 rounded-2xl object-cover shadow-sm">
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-[#1E3A8A] truncate">{{ $recentUser->name }}</p>
                                    <p class="text-xs text-[#1E3A8A]/40 font-bold truncate">{{ $recentUser->email }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full {{ $recentUser->type === 'umkm' ? 'bg-[#EFF6FF] text-[#2563EB]' : 'bg-purple-50 text-purple-600' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $recentUser->type === 'umkm' ? 'UMKM' : 'Creative' }}
                                </span>
                            </div>
                        @empty
                            <div class="p-10 text-center text-sm font-bold text-[#1E3A8A]/35">Belum ada user baru.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin Admin Panel.</p>
        <div class="flex gap-8">
            <a href="{{ route('admin.users') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Users</a>
            <a href="{{ route('admin.projects') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Projects</a>
            <a href="{{ route('admin.escrow.index') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Escrow</a>
        </div>
    </footer>
</body>
</html>
