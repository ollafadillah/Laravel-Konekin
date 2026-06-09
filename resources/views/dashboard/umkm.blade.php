<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard UMKM - Konekin</title>
    
    <!-- Fonts -->
    @include('components.fonts')

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
    
    <!-- Navbar -->
    <x-dashboard-nav-umkm />

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Welcome Header -->
        <div class="mb-12">
            <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] mb-2">Halo, {{ $user->name }}! 🏢</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Selamat datang di dashboard UMKM. Temukan talenta kreatif terbaik untuk bisnismu.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Stat 1 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#EFF6FF] rounded-2xl group-hover:bg-[#2563EB] group-hover:text-white transition-colors text-[#2563EB]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <span class="text-blue-500 text-xs font-bold bg-blue-50 px-2.5 py-1 rounded-full">+1 Baru</span>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Proyek Berjalan</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsInProgress }}</p>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FFF7ED] rounded-2xl group-hover:bg-[#EA580C] group-hover:text-white transition-colors text-[#EA580C]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Total Proyek</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsCount }}</p>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#F0FDF4] rounded-2xl group-hover:bg-[#16A34A] group-hover:text-white transition-colors text-[#16A34A]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Apply Masuk</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $totalApplications }}</p>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white p-6 rounded-3xl border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#FAF5FF] rounded-2xl group-hover:bg-[#9333EA] group-hover:text-white transition-colors text-[#9333EA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
                <h3 class="text-[#1E3A8A]/60 text-sm font-bold uppercase tracking-wider">Status Akun</h3>
                <p class="text-3xl font-display font-bold mt-1 text-[#1E3A8A]">{{ $projectsCount > 0 ? 'Aktif' : '0' }}</p>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Recommended Creators (Left 2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Hiring Status Section -->
                @php
                    $hiringProjects = $projects->filter(fn($p) => in_array($p->status, ['waiting_confirmation', 'hired']));
                @endphp

                @if($hiringProjects->isNotEmpty())
                    <div class="space-y-4 mb-10">
                        <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Status Kerja Sama</h2>
                        @foreach($hiringProjects as $project)
                            <div class="bg-white p-6 rounded-[2.5rem] border-2 {{ $project->status === 'hired' ? 'border-green-200 bg-green-50/30' : 'border-[#2563EB]/10' }} shadow-sm flex flex-col md:flex-row gap-6 items-center">
                                <div class="flex-grow text-center md:text-left">
                                    <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                                        @if($project->status === 'waiting_confirmation')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-full animate-pulse">Menunggu Konfirmasi Kreator</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest rounded-full">Undangan Diterima</span>
                                        @endif
                                    </div>
                                    <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-1">{{ $project->title }}</h4>
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium">Kreator: <span class="font-bold text-[#1E3A8A]">{{ $project->selected_creative_name }}</span></p>
                                </div>
                                <div class="shrink-0">
                                    @if($project->status === 'hired')
                                        <form action="{{ route('payments.generate', $project->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-8 py-3 bg-[#1E3A8A] text-white text-sm font-bold rounded-2xl hover:bg-[#2563EB] transition-all shadow-lg shadow-[#1E3A8A]/20">Bayar Sekarang</button>
                                        </form>
                                    @else
                                        <span class="px-6 py-3 bg-slate-100 text-slate-400 text-sm font-bold rounded-2xl cursor-not-allowed">Menunggu...</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Kreator Rekomendasi</h2>
                    <a href="{{ route('kreator.index') }}" class="text-[#2563EB] text-sm font-bold hover:underline">Lihat Semua</a>
                </div>
                <p class="text-sm text-[#1E3A8A]/60 font-medium -mt-2">
                    @if($recommendedCreators->isNotEmpty())
                        Menampilkan hasil terbaru dari <a href="{{ route('rekomendasi.kreator') }}" class="text-[#2563EB] font-bold hover:underline">Rekomendasi Kreator AI</a>.
                    @else
                        Untuk hasil yang lebih personal berdasarkan data UMKM, buka <a href="{{ route('rekomendasi.kreator') }}" class="text-[#2563EB] font-bold hover:underline">Rekomendasi Kreator AI</a>.
                    @endif
                </p>

                @forelse($recommendedCreators as $worker)
                    @php
                        $creatorName = data_get($worker, 'full_name', 'Creative Worker');
                        $creatorPhoto = data_get($worker, 'profile_photo') ?: 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=2563EB&color=fff';
                        $creatorBudget = (float) data_get($worker, 'min_budget_idr', 0);
                    @endphp
                    <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-xl hover:shadow-[#2563EB]/5 transition-all flex flex-col md:flex-row gap-6 items-center">
                        <div class="w-full md:w-40 h-40 rounded-[2rem] overflow-hidden shrink-0 bg-slate-100">
                            <img src="{{ $creatorPhoto }}" alt="{{ $creatorName }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow w-full min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ data_get($worker, 'display_role', 'Creative Worker') }}</span>
                                <span class="px-3 py-1 bg-[#F5F3FF] text-[#7C3AED] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Match {{ data_get($worker, 'match_percentage', 0) }}%</span>
                                <span class="text-[#1E3A8A]/40 text-xs font-bold">{{ data_get($worker, 'verified_label', 'Belum terverifikasi') }}</span>
                            </div>
                            <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2 line-clamp-1">{{ $creatorName }}</h4>
                            <p class="text-[#1E3A8A]/60 text-sm line-clamp-2 mb-4 font-medium">
                                {{ data_get($worker, 'bio') ?: 'Creative worker yang direkomendasikan oleh model AI Konekin.' }}
                            </p>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex text-yellow-400 items-center">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="ml-1 text-xs font-bold text-[#1E3A8A]">{{ number_format((float) data_get($worker, 'client_rating', 0), 1) }} Rating</span>
                                    </div>
                                    <span class="text-xs font-bold text-[#1E3A8A]/50">{{ number_format((float) data_get($worker, 'jobs_completed', 0), 0, ',', '.') }} Proyek</span>
                                </div>
                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="text-[#1E3A8A] font-display font-bold text-sm">
                                        {{ $creatorBudget > 0 ? 'Mulai dari Rp ' . number_format($creatorBudget, 0, ',', '.') : 'Budget belum diatur' }}
                                    </span>
                                    <a href="{{ route('rekomendasi.kreator') }}" class="px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Undang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-7 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                        <div>
                            <h4 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Belum ada hasil AI</h4>
                            <p class="text-[#1E3A8A]/60 text-sm font-medium leading-7">Jalankan rekomendasi untuk melihat kreator yang paling cocok dengan profil UMKM kamu.</p>
                        </div>
                        <a href="{{ route('rekomendasi.kreator') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#1E3A8A] text-white text-sm font-bold rounded-2xl hover:bg-[#2563EB] transition-colors shadow-lg shadow-[#1E3A8A]/10">Mulai Rekomendasi</a>
                    </div>
                @endforelse
            </div>

            <!-- Project History & Quick Actions (Right 1/3) -->
            <div class="space-y-8">
                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Riwayat Proyek</h2>
                    <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm divide-y divide-[#2563EB]/5 overflow-hidden">
                        @forelse($projects->take(3) as $project)
                            @php
                                $statusConfig = match ($project->status ?? 'open') {
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-50 text-emerald-600'],
                                    'pending_admin_approval' => ['label' => 'Menunggu Admin', 'class' => 'bg-indigo-50 text-indigo-600'],
                                    'ready_for_review' => ['label' => 'Review UMKM', 'class' => 'bg-blue-50 text-blue-600'],
                                    'in_progress' => ['label' => 'Berjalan', 'class' => 'bg-sky-50 text-sky-600'],
                                    'hired' => ['label' => 'Siap Bayar', 'class' => 'bg-amber-50 text-amber-600'],
                                    'waiting_confirmation' => ['label' => 'Menunggu Kreator', 'class' => 'bg-orange-50 text-orange-600'],
                                    'revision' => ['label' => 'Revisi', 'class' => 'bg-purple-50 text-purple-600'],
                                    'disputed' => ['label' => 'Dispute', 'class' => 'bg-red-50 text-red-600'],
                                    'open' => ['label' => 'Terbuka', 'class' => 'bg-slate-100 text-slate-500'],
                                    'applied' => ['label' => 'Ada Apply', 'class' => 'bg-[#EFF6FF] text-[#2563EB]'],
                                    default => ['label' => ucfirst(str_replace('_', ' ', $project->status ?? 'open')), 'class' => 'bg-slate-100 text-slate-500'],
                                };
                                $progress = (int) ($project->progress_percentage ?? 0);
                                $budget = (float) ($project->budget ?? 0);
                            @endphp

                            <a href="{{ route('projects.progress') }}#project-{{ $project->id }}" class="p-5 block hover:bg-[#F8FAFC] transition-colors">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="min-w-0">
                                        <h5 class="text-sm font-bold text-[#1E3A8A] line-clamp-1">{{ $project->title }}</h5>
                                        <p class="text-[11px] text-[#1E3A8A]/45 font-bold mt-1">
                                            {{ optional($project->updated_at)->diffForHumans() ?? 'Baru dibuat' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between text-[11px] font-bold text-[#1E3A8A]/50 mb-1.5">
                                            <span>Progress</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full bg-[#2563EB] rounded-full" style="width: {{ min(100, max(0, $progress)) }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <p class="text-[#1E3A8A]/40 font-bold">Budget</p>
                                            <p class="font-display font-bold text-[#1E3A8A] mt-0.5">{{ $budget > 0 ? 'Rp ' . number_format($budget, 0, ',', '.') : 'Belum diatur' }}</p>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[#1E3A8A]/40 font-bold">Kreator</p>
                                            <p class="font-display font-bold text-[#1E3A8A] mt-0.5 truncate">{{ $project->selected_creative_name ?: 'Belum dipilih' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                </div>
                                <h5 class="text-sm font-bold text-[#1E3A8A] mb-2">Belum ada proyek</h5>
                                <p class="text-xs text-[#1E3A8A]/55 font-medium leading-6 mb-5">Riwayat proyek UMKM kamu akan tampil di sini setelah proyek pertama dibuat.</p>
                                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-[#1E3A8A] text-white text-xs font-bold rounded-xl hover:bg-[#2563EB] transition-colors">Buat Proyek</a>
                            </div>
                        @endforelse

                        @if($projects->isNotEmpty())
                            <div class="p-4 text-center">
                                <a href="{{ route('projects.progress') }}" class="text-xs font-bold text-[#2563EB] hover:underline">Lihat Semua Riwayat</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Aksi Cepat</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('projects.create') }}" class="flex items-center gap-4 bg-[#1E3A8A] p-5 rounded-3xl text-white hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10 group">
                            <div class="p-3 bg-white/10 rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Upload Proyek Baru</h4>
                                <p class="text-xs text-white/60">Mulai cari talenta hari ini</p>
                            </div>
                        </a>
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-4 bg-white p-5 rounded-3xl border border-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all shadow-sm group">
                            <div class="p-3 bg-[#EFF6FF] text-[#2563EB] rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#1E3A8A]">Lengkapi Profil Usaha</h4>
                                <p class="text-xs text-[#1E3A8A]/60">Tingkatkan kepercayaan kreator</p>
                            </div>
                        </a>
                        <a href="{{ route('projects.progress') }}" class="flex items-center gap-4 bg-white p-5 rounded-3xl border border-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all shadow-sm group">
                            <div class="p-3 bg-[#EFF6FF] text-[#2563EB] rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-3M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#1E3A8A]">Progress Proyek</h4>
                                <p class="text-xs text-[#1E3A8A]/60">Pantau apply dan update progress</p>
                            </div>
                        </a>
                        <a href="{{ route('rekomendasi.kreator') }}" class="flex items-center gap-4 bg-white p-5 rounded-3xl border border-[#2563EB]/5 hover:border-[#2563EB]/20 transition-all shadow-sm group">
                            <div class="p-3 bg-[#F5F3FF] text-[#7C3AED] rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-[#1E3A8A]">Rekomendasi Kreator AI</h4>
                                <p class="text-xs text-[#1E3A8A]/60">Cocokkan UMKM dengan kreator terbaik</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Kembangkan bisnismu bersama kami.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

</body>
</html>
