@if(auth()->check())
<nav class="fixed w-full top-0 z-50 glass border-b border-[#2563EB]/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <div class="absolute inset-0 bg-[#2563EB] rounded-xl rotate-3 group-hover:rotate-6 transition-all shadow-lg shadow-[#2563EB]/20"></div>
                    <span class="relative text-white font-display font-bold text-lg">K</span>
                </div>
                <span class="font-display font-bold text-xl tracking-tight">Konekin<span class="text-[#2563EB]">.</span></span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center bg-white/50 px-6 py-2 rounded-full border border-white/80 shadow-sm gap-1">
                <a href="{{ route('dashboard.creative') }}" class="px-4 py-2 {{ request()->routeIs('dashboard.creative') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 {{ request()->routeIs('projects.index') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Cari Proyek</a>
                <a href="{{ route('portfolio.index') }}" class="px-4 py-2 {{ request()->routeIs('portfolio.index') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Portfolio</a>
                <a href="{{ route('projects.progress.creative') }}" class="px-4 py-2 {{ request()->routeIs('projects.progress.creative') || request()->routeIs('projects.progress.creative.update') ? 'bg-[#2563EB] text-white shadow-md shadow-[#2563EB]/20' : 'text-[#1E3A8A]/70 hover:text-[#2563EB]' }} rounded-full text-sm font-bold transition-all">Progress Proyek</a>
            </div>

            <!-- Right Side: Notification & Profile -->
            <div class="flex items-center gap-4">
                @php
                    $user = auth()->user();
                    $approvalNotifications = \App\Models\ProjectApplication::with(['project.client'])
                        ->where('creative_id', $user->id)
                        ->where('status', 'approved')
                        ->whereNotNull('approved_at')
                        ->orderBy('approved_at', 'desc')
                        ->limit(5)
                        ->get()
                        ->map(function ($notification) {
                            $project = $notification->project;
                            $projectTitle = optional($project)->title ?? 'Proyek';
                            $client = optional($project)->client;
                            $clientAvatar = optional($client)->profile_photo
                                ?? optional($project)->client_avatar
                                ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional($client)->name ?? 'UMKM') . '&background=random';

                            return (object) [
                                'id' => (string) $notification->id,
                                'type' => 'approval',
                                'title' => 'Lamaran Disetujui',
                                'message' => 'UMKM telah menerima lamaranmu untuk proyek "' . $projectTitle . '".',
                                'project_title' => $projectTitle,
                                'avatar' => $clientAvatar,
                                'action_url' => route('projects.progress.creative') . '#project-' . $notification->project_id,
                                'timestamp' => $notification->approved_at ?? $notification->created_at ?? now(),
                                'level' => 'success',
                            ];
                        });

                    $deadlineNotifications = \App\Models\Project::where('selected_creative_id', $user->id)
                        ->where('status', '!=', 'completed')
                        ->orderBy('deadline', 'asc')
                        ->get()
                        ->map(function ($project) {
                            $deadline = \Illuminate\Support\Carbon::parse($project->deadline);
                            $daysLeft = now()->startOfDay()->diffInDays($deadline->copy()->startOfDay(), false);

                            if ($daysLeft > 3) {
                                return null;
                            }

                            if ($daysLeft < 0) {
                                $label = 'Deadline terlewat';
                                $message = 'Proyek "' . $project->title . '" sudah lewat ' . abs($daysLeft) . ' hari. Segera perbarui progress.';
                                $level = 'danger';
                            } elseif ($daysLeft === 0) {
                                $label = 'Deadline hari ini';
                                $message = 'Proyek "' . $project->title . '" jatuh tempo hari ini. Jangan lupa kirim update progress.';
                                $level = 'warning';
                            } else {
                                $label = 'Deadline dekat';
                                $message = 'Proyek "' . $project->title . '" tersisa ' . $daysLeft . ' hari lagi menuju deadline.';
                                $level = 'warning';
                            }

                            return (object) [
                                'id' => 'deadline-' . $project->id,
                                'type' => 'deadline',
                                'title' => $label,
                                'message' => $message,
                                'project_title' => $project->title,
                                'action_url' => route('projects.progress.creative') . '#project-' . $project->id,
                                'timestamp' => $deadline,
                                'level' => $level,
                            ];
                        })
                        ->filter()
                        ->values();

                    $creativeNotifications = $approvalNotifications
                        ->concat($deadlineNotifications)
                        ->sortByDesc(fn ($item) => optional($item->timestamp)->timestamp ?? now()->timestamp)
                        ->values();

                    $creativeNotificationCount = $creativeNotifications->count();
                @endphp

                <!-- Notification Bell & Dropdown -->
                <div class="relative" id="creative-notif-wrapper">
                    <button onclick="toggleCreativeNotifDropdown(event)" class="relative p-2.5 bg-white border border-[#2563EB]/10 rounded-xl hover:bg-[#EFF6FF] transition-all group">
                        <svg class="w-6 h-6 text-[#1E3A8A]/70 group-hover:text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($creativeNotificationCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-red-500 text-[10px] font-bold text-white border-2 border-white">
                                    {{ $creativeNotificationCount }}
                                </span>
                            </span>
                        @endif
                    </button>

                    <div id="creative-notif-dropdown" class="absolute hidden right-0 mt-4 w-[22rem] bg-white rounded-[2rem] shadow-2xl shadow-[#1E3A8A]/20 border border-[#2563EB]/10 overflow-hidden z-[60]">
                        <div class="p-5 border-b border-slate-50 flex items-center justify-between">
                            <div>
                                <h3 class="font-display font-bold text-[#1E3A8A]">Notifikasi</h3>
                                <p class="text-[11px] font-medium text-[#1E3A8A]/45 mt-1">Aktivitas proyek dan pengingat deadline</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-widest">{{ $creativeNotificationCount }} Baru</span>
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @forelse($creativeNotifications as $notification)
                                <a href="{{ $notification->action_url }}" class="block p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        @if($notification->type === 'approval')
                                            <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 shadow-sm border border-blue-100 bg-blue-50">
                                                <img src="{{ $notification->avatar }}" alt="{{ $notification->project_title }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 shadow-sm border {{ $notification->level === 'danger' ? 'bg-red-50 border-red-100 text-red-500' : ($notification->level === 'warning' ? 'bg-amber-50 border-amber-100 text-amber-500' : 'bg-blue-50 border-blue-100 text-blue-500') }} flex items-center justify-center">
                                                <i class="fas fa-hourglass-half"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[10px] font-black uppercase tracking-widest {{ $notification->level === 'danger' ? 'text-red-500' : ($notification->level === 'warning' ? 'text-amber-500' : 'text-[#2563EB]') }} mb-0.5">
                                                {{ $notification->title }}
                                            </p>
                                            <p class="text-sm font-bold text-[#1E3A8A] line-clamp-1 mb-0.5">{{ $notification->project_title }}</p>
                                            <p class="text-xs text-[#1E3A8A]/60 font-medium leading-relaxed">{{ $notification->message }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-10 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="fas fa-bell-slash text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-[#1E3A8A]/40 uppercase tracking-[0.2em]">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>

                        @if($creativeNotificationCount > 0)
                            <a href="{{ route('projects.progress.creative') }}" class="block p-4 text-center text-xs font-bold text-[#2563EB] bg-slate-50 hover:bg-[#EFF6FF] transition-all">
                                Lihat Semua Progress Proyek
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Profile -->
                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 pl-4 border-l border-[#2563EB]/10 group/profile cursor-pointer hover:opacity-80 transition-all">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-[#1E3A8A] leading-none group-hover/profile:text-[#2563EB] transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider mt-1">Creative Worker</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-0.5 shadow-lg shadow-[#2563EB]/20 group-hover/profile:scale-105 transition-transform">
                        <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random' }}" alt="Profile" class="w-full h-full object-cover rounded-[10px] border-2 border-white">
                    </div>
                </a>

                <!-- Logout Button -->
                <button onclick="confirmLogout()" class="p-2.5 bg-white border border-red-100 rounded-xl hover:bg-red-50 transition-all group ml-1" title="Logout">
                    <svg class="w-6 h-6 text-red-500 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Logout Form & Logic -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop with blur effect -->
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeLogoutModal()"></div>

    <!-- Modal content container -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-[3rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/20">
                <div class="bg-white px-6 pt-10 pb-8 sm:px-10">
                    <div class="flex flex-col items-center">
                        <!-- User Profile Info -->
                        <div class="relative mb-6">
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1 shadow-2xl shadow-[#2563EB]/20 rotate-3 transition-transform hover:rotate-0">
                                <img src="{{ auth()->user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random' }}" 
                                     alt="Profile" 
                                     class="w-full h-full object-cover rounded-[20px] border-4 border-white">
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-red-500 text-white p-2 rounded-xl shadow-lg border-4 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                        </div>

                        <div class="text-center">
                            <h3 class="text-2xl font-extrabold text-[#1E3A8A] tracking-tight mb-1" id="modal-title">
                                {{ auth()->user()->name }}
                            </h3>
                            <p class="text-xs font-black text-[#2563EB] uppercase tracking-[0.2em] mb-6">
                                {{ auth()->user()->type === 'creative_worker' ? 'Creative Worker' : 'Mitra UMKM' }}
                            </p>
                            
                            <div class="inline-block px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100 mb-2">
                                <p class="text-slate-500 font-medium">Apakah Anda yakin ingin logout?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/80 px-6 py-8 sm:px-10 flex flex-col gap-3">
                    <button type="button" onclick="document.getElementById('logout-form').submit()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-red-500/20 px-8 py-4 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all hover:scale-[1.02] active:scale-95">
                        Ya, Keluar Sekarang
                    </button>
                    <button type="button" onclick="closeLogoutModal()" 
                        class="w-full inline-flex justify-center rounded-2xl border border-slate-200 shadow-sm px-8 py-4 bg-white text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2563EB] transition-all hover:scale-[1.02] active:scale-95">
                        Tetap di Sini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCreativeNotifDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('creative-notif-dropdown');
        dropdown.classList.toggle('hidden');
    }

    function confirmLogout() {
        document.getElementById('logout-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutModal() {
        document.getElementById('logout-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const notifDropdown = document.getElementById('creative-notif-dropdown');
            if (notifDropdown) {
                notifDropdown.classList.add('hidden');
            }
            closeLogoutModal();
        }
    });

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('creative-notif-dropdown');
        const wrapper = document.getElementById('creative-notif-wrapper');

        if (dropdown && wrapper && !wrapper.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
@endif
