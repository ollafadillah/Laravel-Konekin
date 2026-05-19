<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.45);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-admin />

    <main class="mx-auto max-w-7xl px-4 pb-20 pt-32 sm:px-6 lg:px-8">
        <section class="mb-8 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
            <div class="grid gap-0 lg:grid-cols-[1.45fr_0.55fr]">
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Admin Center
                    </div>
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h1 class="font-display text-3xl font-bold tracking-tight text-[#1E3A8A] sm:text-4xl lg:text-5xl">Pusat Kendali Admin</h1>
                            <p class="mt-3 max-w-2xl text-base font-medium leading-7 text-[#1E3A8A]/60">Pantau operasi Konekin dari satu layar: pengguna, proyek, verifikasi pembayaran, escrow, dan antrean approval.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Login sebagai</p>
                            <p class="mt-1 text-sm font-extrabold text-[#1E3A8A]">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-200 bg-slate-900 p-6 text-white lg:border-l lg:border-t-0 lg:p-8">
                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">Prioritas terbuka</p>
                    <div class="mt-5 grid grid-cols-3 gap-3 lg:grid-cols-1">
                        <a href="{{ route('admin.payment-verification.index') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 transition hover:bg-white/20">
                            <p class="text-2xl font-black">{{ $pendingVerificationCount }}</p>
                            <p class="mt-1 text-xs font-bold text-slate-300">Resi</p>
                        </a>
                        <a href="{{ route('admin.project-approvals.index') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 transition hover:bg-white/20">
                            <p class="text-2xl font-black">{{ $pendingApprovalCount }}</p>
                            <p class="mt-1 text-xs font-bold text-slate-300">Approval</p>
                        </a>
                        <a href="{{ route('admin.escrow.index') }}" class="rounded-2xl border border-white/10 bg-white/10 p-4 transition hover:bg-white/20">
                            <p class="text-2xl font-black">{{ $disputeCount }}</p>
                            <p class="mt-1 text-xs font-bold text-slate-300">Dispute</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-slate-500">+{{ $newUsersThisWeek }} minggu ini</span>
                </div>
                <p class="text-sm font-bold text-[#1E3A8A]/55">Total Pengguna</p>
                <p class="mt-1 font-display text-3xl font-bold text-[#1E3A8A]">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="fas fa-briefcase text-sm"></i>
                </div>
                <p class="text-sm font-bold text-[#1E3A8A]/55">Creative Workers</p>
                <p class="mt-1 font-display text-3xl font-bold text-[#1E3A8A]">{{ number_format($totalCreativeWorkers) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700">
                    <i class="fas fa-store text-sm"></i>
                </div>
                <p class="text-sm font-bold text-[#1E3A8A]/55">Mitra UMKM</p>
                <p class="mt-1 font-display text-3xl font-bold text-[#1E3A8A]">{{ number_format($totalUMKM) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="fas fa-folder-open text-sm"></i>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-emerald-700">{{ $activeProjects }} aktif</span>
                </div>
                <p class="text-sm font-bold text-[#1E3A8A]/55">Total Proyek</p>
                <p class="mt-1 font-display text-3xl font-bold text-[#1E3A8A]">{{ number_format($totalProjects) }}</p>
            </div>
        </section>

        <section class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-[1.25fr_0.75fr]">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/35">Triage Operasional</p>
                        <h2 class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Prioritas Hari Ini</h2>
                    </div>
                    <a href="{{ route('admin.escrow.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-extrabold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                        Buka Escrow
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <a href="{{ route('admin.payment-verification.index') }}" class="group rounded-2xl border border-amber-100 bg-amber-50/70 p-5 transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-100/60">
                        <div class="mb-5 flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-amber-600 shadow-sm">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <i class="fas fa-chevron-right text-amber-500 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="font-display text-3xl font-bold text-amber-700">{{ $pendingVerificationCount }}</p>
                        <p class="mt-1 text-sm font-extrabold text-[#1E3A8A]">Resi menunggu verifikasi</p>
                        <p class="mt-3 text-xs font-bold text-amber-700/75">Rp {{ number_format($pendingVerificationAmount, 0, ',', '.') }}</p>
                    </a>

                    <a href="{{ route('admin.project-approvals.index') }}" class="group rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/60">
                        <div class="mb-5 flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <i class="fas fa-chevron-right text-emerald-500 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="font-display text-3xl font-bold text-emerald-700">{{ $pendingApprovalCount }}</p>
                        <p class="mt-1 text-sm font-extrabold text-[#1E3A8A]">Proyek siap approval</p>
                        <p class="mt-3 text-xs font-bold text-emerald-700/75">Rp {{ number_format($pendingApprovalAmount, 0, ',', '.') }}</p>
                    </a>

                    <a href="{{ route('admin.escrow.index') }}" class="group rounded-2xl border border-rose-100 bg-rose-50/70 p-5 transition hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-100/60">
                        <div class="mb-5 flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-rose-600 shadow-sm">
                                <i class="fas fa-scale-balanced"></i>
                            </div>
                            <i class="fas fa-chevron-right text-rose-500 transition group-hover:translate-x-1"></i>
                        </div>
                        <p class="font-display text-3xl font-bold text-rose-700">{{ $disputeCount }}</p>
                        <p class="mt-1 text-sm font-extrabold text-[#1E3A8A]">Dispute perlu dipantau</p>
                        <p class="mt-3 text-xs font-bold text-rose-700/75">{{ $releasingEscrowCount }} pencairan diproses</p>
                    </a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/35">Ringkasan Dana</p>
                <h2 class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Escrow Platform</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider text-slate-400">Dana tertahan</p>
                                <p class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Rp {{ number_format($heldEscrowAmount, 0, ',', '.') }}</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-black text-slate-600">{{ $heldEscrowCount }} escrow</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.users') }}" class="rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50">
                            <i class="fas fa-users-cog text-blue-600"></i>
                            <p class="mt-3 text-sm font-extrabold text-[#1E3A8A]">Kelola User</p>
                        </a>
                        <a href="{{ route('admin.projects') }}" class="rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50">
                            <i class="fas fa-diagram-project text-emerald-600"></i>
                            <p class="mt-3 text-sm font-extrabold text-[#1E3A8A]">Cek Proyek</p>
                        </a>
                        <a href="{{ route('admin.jobs') }}" class="rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50">
                            <i class="fas fa-chart-line text-violet-600"></i>
                            <p class="mt-3 text-sm font-extrabold text-[#1E3A8A]">Aktivitas</p>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50">
                            <i class="fas fa-wallet text-amber-600"></i>
                            <p class="mt-3 text-sm font-extrabold text-[#1E3A8A]">Pembayaran</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/35">Live Feed</p>
                        <h2 class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Aktivitas Terbaru</h2>
                    </div>
                    <a href="{{ route('admin.jobs') }}" class="text-xs font-black uppercase tracking-wider text-blue-600 hover:text-blue-800">Detail</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        @php
                            $activityClasses = match ($activity->tone) {
                                'amber' => 'bg-amber-50 text-amber-600',
                                'emerald' => 'bg-emerald-50 text-emerald-600',
                                'indigo' => 'bg-indigo-50 text-indigo-600',
                                'sky' => 'bg-sky-50 text-sky-600',
                                'red' => 'bg-rose-50 text-rose-600',
                                'blue' => 'bg-blue-50 text-blue-600',
                                default => 'bg-slate-100 text-slate-500',
                            };
                        @endphp
                        <div class="flex gap-4 rounded-2xl border border-slate-100 p-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $activityClasses }}">
                                <i class="fas {{ $activity->icon }} text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-extrabold text-[#1E3A8A]">{{ $activity->title }}</p>
                                <p class="mt-1 truncate text-xs font-semibold text-[#1E3A8A]/50">{{ $activity->description }}</p>
                                <p class="mt-2 text-[10px] font-black uppercase tracking-wider text-slate-400">{{ $activity->date->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center">
                            <p class="text-sm font-bold text-slate-400">Belum ada aktivitas terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/35">User Monitoring</p>
                            <h2 class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Pengguna Terbaru</h2>
                        </div>
                        <a href="{{ route('admin.users') }}" class="text-xs font-black uppercase tracking-wider text-blue-600 hover:text-blue-800">Semua</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentUsers as $recentUser)
                            <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                                <img src="{{ $recentUser->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($recentUser->name).'&background=2563EB&color=fff' }}" alt="{{ $recentUser->name }}" class="h-11 w-11 rounded-xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-extrabold text-[#1E3A8A]">{{ $recentUser->name }}</p>
                                    <p class="truncate text-xs font-semibold text-[#1E3A8A]/45">{{ $recentUser->email }}</p>
                                </div>
                                <span class="rounded-full {{ $recentUser->type === 'umkm' ? 'bg-cyan-50 text-cyan-700' : 'bg-violet-50 text-violet-700' }} px-3 py-1 text-[10px] font-black uppercase tracking-wider">
                                    {{ $recentUser->type === 'umkm' ? 'UMKM' : 'Creative' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm font-bold text-slate-400">Belum ada user baru.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/35">Project Monitoring</p>
                            <h2 class="mt-1 font-display text-2xl font-bold text-[#1E3A8A]">Proyek Terbaru</h2>
                        </div>
                        <a href="{{ route('admin.projects') }}" class="text-xs font-black uppercase tracking-wider text-blue-600 hover:text-blue-800">Semua</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentProjects as $project)
                            @php
                                $statusClasses = match ($project->status) {
                                    'open' => 'bg-blue-50 text-blue-700',
                                    'hired' => 'bg-amber-50 text-amber-700',
                                    'in_progress' => 'bg-violet-50 text-violet-700',
                                    'pending_admin_approval' => 'bg-emerald-50 text-emerald-700',
                                    'completed' => 'bg-slate-100 text-slate-600',
                                    default => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-extrabold text-[#1E3A8A]">{{ $project->title ?? 'Proyek tanpa judul' }}</p>
                                    <p class="truncate text-xs font-semibold text-[#1E3A8A]/45">{{ $project->client_name ?? 'UMKM' }}</p>
                                </div>
                                <span class="rounded-full {{ $statusClasses }} px-3 py-1 text-[10px] font-black uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $project->status ?? 'draft') }}
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm font-bold text-slate-400">Belum ada proyek baru.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 border-t border-slate-200 px-4 py-8 sm:px-6 md:flex-row lg:px-8">
        <p class="text-sm font-bold text-slate-400">&copy; 2026 Konekin Admin Panel.</p>
        <div class="flex gap-6">
            <a href="{{ route('admin.users') }}" class="text-sm font-bold text-slate-400 transition hover:text-blue-600">Users</a>
            <a href="{{ route('admin.projects') }}" class="text-sm font-bold text-slate-400 transition hover:text-blue-600">Projects</a>
            <a href="{{ route('admin.escrow.index') }}" class="text-sm font-bold text-slate-400 transition hover:text-blue-600">Escrow</a>
        </div>
    </footer>
</body>
</html>
