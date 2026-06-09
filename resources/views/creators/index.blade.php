<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cari Kreator - Konekin</title>
    
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
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .creative-tier-chip {
            width: 100%;
            background:
                radial-gradient(circle at 22% 12%, rgba(255,255,255,0.92), transparent 34%),
                linear-gradient(145deg, rgba(255,255,255,0.96), rgba(239,246,255,0.9));
            box-shadow: 0 16px 32px rgba(30, 58, 138, 0.12);
        }
        .creative-tier-chip .tier-art {
            background: linear-gradient(145deg, #FFFFFF, #F8FAFC);
            box-shadow: inset 0 0 0 1px rgba(37,99,235,0.08), 0 12px 24px rgba(37,99,235,0.12);
        }
        .creative-tier-chip .tier-art img {
            mix-blend-mode: multiply;
        }
        .creative-tier-chip.is-expert {
            background:
                radial-gradient(circle at 25% 10%, rgba(255,255,255,0.18), transparent 34%),
                linear-gradient(145deg, #0F172A, #1E3A8A 70%, #BE123C);
            box-shadow: 0 16px 32px rgba(244, 63, 94, 0.18);
        }
        .creative-tier-chip.is-expert .tier-art {
            background: linear-gradient(145deg, rgba(15,23,42,0.9), rgba(30,58,138,0.82));
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12), 0 12px 24px rgba(15,23,42,0.3);
        }
        .creative-tier-chip.is-expert .tier-art img {
            mix-blend-mode: screen;
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    @php
        $creativeRoleOptions = $creativeRoleOptions ?? config('creative_roles.options', []);
        $activeRole = $activeRole ?? request('role', request('skill', 'Semua'));
    @endphp
    
    <!-- Navbar Selection -->
    @if(auth()->check() && auth()->user()->isUMKM())
        <x-dashboard-nav-umkm />
    @elseif(auth()->check() && auth()->user()->isCreativeWorker())
        <x-dashboard-nav />
    @else
        <x-navbar />
    @endif

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Search Header -->
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Temukan Talenta Kreatif Terbaik</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg mb-8">Ribuan profesional kreatif siap membantu bisnismu naik kelas. Pilih yang paling sesuai dengan visi bisnismu.</p>
            
            <form action="{{ route('kreator.index') }}" method="GET" class="relative group">
                <input type="hidden" name="role" value="{{ $activeRole }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, role, atau domisili..." class="w-full px-8 py-5 rounded-[2.5rem] bg-white border-2 border-[#2563EB]/5 shadow-xl shadow-[#2563EB]/5 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] pr-32">
                <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 bg-[#2563EB] text-white rounded-full font-bold text-sm hover:bg-[#1E3A8A] transition-all">Cari</button>
            </form>
        </div>

        <!-- Role Filters -->
        <div class="text-center mb-4">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#2563EB]">{{ count($creativeRoleOptions) }} kategori inti creative worker</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <a href="{{ route('kreator.index', ['role' => 'Semua', 'search' => request('search')]) }}"
               class="px-6 py-3 rounded-full font-bold text-sm transition-all {{ $activeRole == 'Semua' ? 'bg-[#1E3A8A] text-white shadow-lg shadow-[#1E3A8A]/20' : 'bg-white text-[#1E3A8A]/60 hover:bg-[#EFF6FF] border border-[#2563EB]/5' }}">
                Semua Role
            </a>
            @foreach($creativeRoleOptions as $label => $role)
                <a href="{{ route('kreator.index', ['role' => $label, 'search' => request('search')]) }}"
                   class="px-6 py-3 rounded-full font-bold text-sm transition-all {{ $activeRole == $label ? 'bg-[#1E3A8A] text-white shadow-lg shadow-[#1E3A8A]/20' : 'bg-white text-[#1E3A8A]/60 hover:bg-[#EFF6FF] border border-[#2563EB]/5' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Creators Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($creators as $creator)
                @php
                    $borderClass = 'bg-white border-[#2563EB]/5 hover:shadow-[#2563EB]/10';
                    $borderStyle = '';
                    $borderDecor = '';
                    
                    if (($creator->profile_border ?? 'none') === 'ocean') {
                        $borderClass = 'bg-[#E0F2FE] border-[#bae6fd] hover:border-[#0284C7] ring-4 ring-transparent hover:ring-[#0284C7]/20 relative overflow-hidden hover:shadow-[#0284C7]/15';
                        $borderStyle = 'background-image: radial-gradient(circle at top left, rgba(255,255,255,0.8) 0%, transparent 60%), radial-gradient(circle at bottom right, rgba(14,165,233,0.3) 0%, transparent 50%);';
                        $borderDecor = '
                            <svg class="absolute top-2 left-2 w-12 h-12 text-blue-400 opacity-60 pointer-events-none transition-transform group-hover:-translate-y-1 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2a8 8 0 100 16 8 8 0 000-16zm0 2a6 6 0 110 12 6 6 0 010-12z"/></svg>
                            <svg class="absolute bottom-2 right-2 w-16 h-16 text-cyan-500 opacity-50 pointer-events-none transition-transform group-hover:translate-x-1 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ';
                    } elseif (($creator->profile_border ?? 'none') === 'math') {
                        $borderClass = 'bg-[#fdfbf7] border-[#e2e8f0] hover:border-[#d97706] ring-4 ring-transparent hover:ring-[#d97706]/20 relative overflow-hidden hover:shadow-[#d97706]/15';
                        $borderStyle = 'background-image: linear-gradient(#e5e7eb 1px, transparent 1px), linear-gradient(90deg, #e5e7eb 1px, transparent 1px); background-size: 20px 20px;';
                        $borderDecor = '
                            <div class="absolute top-4 left-4 text-xs font-bold text-orange-400 font-mono pointer-events-none opacity-80 transition-transform group-hover:scale-110">E=mc²</div>
                            <svg class="absolute bottom-4 right-4 w-12 h-12 text-green-500 opacity-50 pointer-events-none transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        ';
                    } elseif (($creator->profile_border ?? 'none') === 'stars') {
                        $borderClass = 'bg-white border-dashed border-slate-300 hover:border-[#8b5cf6] hover:border-solid ring-4 ring-transparent hover:ring-[#8b5cf6]/20 relative overflow-hidden hover:shadow-[#8b5cf6]/15';
                        $borderStyle = '';
                        $borderDecor = '
                            <svg class="absolute top-4 left-4 w-8 h-8 text-yellow-400 pointer-events-none opacity-80 transition-transform group-hover:rotate-45 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="absolute bottom-4 right-4 w-10 h-10 text-purple-400 pointer-events-none opacity-70 transition-transform group-hover:-rotate-45 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="absolute top-1/2 right-2 w-6 h-6 text-pink-400 pointer-events-none opacity-60 transition-transform group-hover:scale-125" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="absolute bottom-1/3 left-2 w-5 h-5 text-indigo-400 pointer-events-none opacity-50 transition-transform group-hover:scale-125" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        ';
                    }
                @endphp
                <div class="p-8 rounded-[3rem] border shadow-sm hover:shadow-2xl transition-all group flex flex-col h-full {{ $borderClass }}" style="{{ $borderStyle }}">
                    {!! $borderDecor !!}
                    <div class="relative z-10 flex flex-col h-full">
                        <!-- Profile Header -->
                        <div class="flex items-start gap-5 mb-5 relative">
                            <div class="w-20 h-20 rounded-[1.5rem] overflow-hidden shrink-0 border-2 border-white shadow-lg shadow-[#2563EB]/10 group-hover:scale-105 transition-transform">
                                <img src="{{ $creator->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($creator->name).'&background=2563EB&color=fff' }}" alt="{{ $creator->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow pt-1 min-w-0">
                                <h3 class="text-xl font-display font-bold text-[#1E3A8A] leading-tight group-hover:text-[#2563EB] transition-colors">{{ $creator->name }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                    <span class="text-xs font-bold text-[#2563EB] uppercase tracking-wider">{{ $creator->display_creative_category ?? 'Creative Worker' }}</span>
                                    <span class="w-1 h-1 bg-[#1E3A8A]/20 rounded-full"></span>
                                    <span class="text-xs font-medium text-[#1E3A8A]/40">{{ $creator->city ?? 'Domisili tidak diatur' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($creator->creative_tier)
                            <div class="creative-tier-chip {{ $creator->creative_tier['name'] === 'Expert' ? 'is-expert' : '' }} rounded-[1.8rem] border border-white/90 p-3 mb-6 flex items-center gap-4 transition-all group-hover:-translate-y-1" title="{{ $creator->creative_tier['name'] }} - {{ $creator->five_star_ratings_count }} ulasan bintang 5">
                                <div class="tier-art w-16 h-16 rounded-[1.35rem] flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="{{ $creator->creative_tier['badge'] }}" alt="{{ $creator->creative_tier['name'] }} Badge" class="w-14 h-14 object-contain transition-transform group-hover:scale-110">
                                </div>
                                <div class="min-w-0">
                                    <span class="inline-flex px-3 py-1 rounded-full {{ $creator->creative_tier['name'] === 'Expert' ? 'bg-white/10 text-rose-100 border border-white/10' : $creator->creative_tier['bg'].' '.$creator->creative_tier['color'] }} text-[10px] font-black uppercase tracking-wider">
                                        {{ $creator->creative_tier['name'] }}
                                    </span>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <span class="text-lg font-display font-bold {{ $creator->creative_tier['name'] === 'Expert' ? 'text-white' : 'text-[#1E3A8A]' }}">{{ $creator->five_star_ratings_count }}&times; 5&#9733;</span>
                                        <span class="text-[10px] font-extrabold uppercase tracking-[0.16em] {{ $creator->creative_tier['name'] === 'Expert' ? 'text-white/55' : 'text-[#1E3A8A]/40' }}">Ulasan Terbaik</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Skills & Stats -->
                        <div class="flex-grow">
                            <div class="flex flex-wrap gap-2 mb-6">
                                @forelse(($creator->role_skills_preview ?? []) as $skill)
                                    <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $skill }}</span>
                                @empty
                                    <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold tracking-widest rounded-full">{{ $creator->display_creative_category ?? 'Creative Worker' }}</span>
                                @endforelse
                            </div>
                            
                            <p class="text-[#1E3A8A]/60 text-sm mb-8 line-clamp-2 font-medium leading-relaxed">
                                {{ $creator->bio ?? 'Kreator ini belum menambahkan bio profil mereka.' }}
                            </p>

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="text-center p-3 rounded-2xl bg-white/60 backdrop-blur-sm border border-slate-100">
                                    <p class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase mb-1">Rating</p>
                                    <div class="flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                                        <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ $creator->average_rating }}</p>
                                    </div>
                                </div>
                                <div class="text-center p-3 rounded-2xl bg-white/60 backdrop-blur-sm border border-slate-100">
                                    <p class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase mb-1">Proyek</p>
                                    <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ $creator->completed_projects_count }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Footer -->
                        <div class="pt-6 border-t border-[#2563EB]/5 flex items-center justify-between mt-auto gap-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase">Role</span>
                                <span class="text-[#1E3A8A] font-display font-bold">{{ $creator->display_creative_category ?? 'Creative Worker' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="p-3 bg-[#EFF6FF] text-[#2563EB] hover:bg-[#2563EB] hover:text-white rounded-xl transition-all" title="Simpan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                </button>
                                @if(auth()->check())
                                    <a href="{{ route('kreator.show', $creator->id) }}" class="px-6 py-3 bg-[#1E3A8A] text-white hover:bg-[#2563EB] rounded-xl font-bold text-xs transition-all shadow-lg shadow-[#1E3A8A]/10 inline-flex items-center justify-center">Lihat Profil</a>
                                @else
                                    <button
                                        type="button"
                                        class="px-6 py-3 bg-[#1E3A8A] text-white hover:bg-[#2563EB] rounded-xl font-bold text-xs transition-all shadow-lg shadow-[#1E3A8A]/10"
                                        onclick="openGuestPreviewModal('{{ e($creator->name) }}', '{{ e($creator->city ?? 'Kreator pilihan di Konekin') }}')"
                                    >
                                        Lihat Profil
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mb-4 inline-block p-6 bg-white rounded-full shadow-sm">
                        <svg class="w-12 h-12 text-[#1E3A8A]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Belum Ada Kreator</h3>
                    <p class="text-[#1E3A8A]/60 font-medium mt-2">Maaf, saat ini belum ada kreator yang tersedia di kategori ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Placeholder -->
        <div class="mt-16 flex justify-center">
            <nav class="flex items-center gap-2">
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 flex items-center justify-center text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                <button class="w-12 h-12 rounded-2xl bg-[#2563EB] text-white font-bold text-sm shadow-lg shadow-[#2563EB]/20">1</button>
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 font-bold text-sm text-[#1E3A8A] hover:bg-[#EFF6FF]">2</button>
                <button class="w-12 h-12 rounded-2xl bg-white border border-[#2563EB]/5 flex items-center justify-center text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
            </nav>
        </div>

    </main>

    @guest
    <div id="guest-preview-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-md" onclick="closeGuestPreviewModal()"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-xl rounded-[2.5rem] bg-white border border-white/80 shadow-2xl shadow-[#1E3A8A]/20 overflow-hidden">
                <div class="relative px-8 sm:px-10 pt-10 pb-8 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.18),_transparent_42%),linear-gradient(135deg,#EFF6FF_0%,#FFFFFF_55%,#F8FAFC_100%)]">
                    <button type="button" onclick="closeGuestPreviewModal()" class="absolute top-5 right-5 w-10 h-10 rounded-full bg-white/90 border border-[#2563EB]/10 text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all">
                        <span class="sr-only">Tutup</span>
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 shadow-sm mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB]">Preview Eksklusif</span>
                    </div>

                    <h2 class="font-display text-3xl sm:text-[2rem] font-bold text-[#1E3A8A] leading-tight mb-4">
                        Mau lihat profil kreator secara utuh?
                    </h2>
                    <p class="text-[#1E3A8A]/65 font-medium leading-relaxed text-sm sm:text-base">
                        Bergabung dengan Konekin untuk membuka detail profil, lihat portofolio terbaik, dan temukan partner kreatif yang paling pas untuk bisnismu.
                    </p>
                </div>

                <div class="px-8 sm:px-10 py-8">
                    <div class="rounded-[2rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-6 mb-8">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Yang Akan Kamu Dapatkan</p>
                        <div class="space-y-3 text-sm text-[#1E3A8A]/75 font-medium">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Akses penuh ke profil <span id="guest-preview-name" class="font-bold text-[#1E3A8A]">kreator pilihan</span> dan talent lainnya.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Lihat detail domisili, bio, dan kecocokan kreator untuk kebutuhan brand-mu.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Mulai kolaborasi lebih cepat bersama talenta dari <span id="guest-preview-city" class="font-bold text-[#1E3A8A]">komunitas Konekin</span>.</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-[#2563EB] text-white font-bold text-sm shadow-xl shadow-[#2563EB]/20 hover:bg-[#1E3A8A] transition-all">
                            Gabung Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white border border-[#2563EB]/15 text-[#1E3A8A] font-bold text-sm hover:bg-[#EFF6FF] transition-all">
                            Sudah Punya Akun
                        </a>
                    </div>

                    <p class="text-center text-xs text-[#1E3A8A]/45 font-medium mt-5">
                        Konekin membantu UMKM dan creative worker bertemu dalam satu ekosistem kolaborasi yang aman dan relevan.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Wujudkan ide kreatifmu.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

    @guest
    <script>
        function openGuestPreviewModal(name, city) {
            document.getElementById('guest-preview-name').textContent = name || 'kreator pilihan';
            document.getElementById('guest-preview-city').textContent = city || 'komunitas Konekin';
            document.getElementById('guest-preview-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGuestPreviewModal() {
            document.getElementById('guest-preview-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeGuestPreviewModal();
            }
        });
    </script>
    @endguest

</body>
</html>
