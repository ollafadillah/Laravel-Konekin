<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cari Kreator - Konekin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

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
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, keahlian, atau domisili..." class="w-full px-8 py-5 rounded-[2.5rem] bg-white border-2 border-[#2563EB]/5 shadow-xl shadow-[#2563EB]/5 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] pr-32">
                <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 bg-[#2563EB] text-white rounded-full font-bold text-sm hover:bg-[#1E3A8A] transition-all">Cari</button>
            </form>
        </div>

        <!-- Categories / Skills Filters -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            @php
                $skills = ['Semua', 'Graphic Designer', 'Content Creator', 'Web Developer', 'Videographer', 'Copywriter', 'UI/UX Designer'];
                $activeSkill = request('skill', 'Semua');
            @endphp
            @foreach($skills as $skill)
                <a href="{{ route('kreator.index', ['skill' => $skill, 'search' => request('search')]) }}" 
                   class="px-6 py-3 rounded-full font-bold text-sm transition-all {{ $activeSkill == $skill ? 'bg-[#1E3A8A] text-white shadow-lg shadow-[#1E3A8A]/20' : 'bg-white text-[#1E3A8A]/60 hover:bg-[#EFF6FF] border border-[#2563EB]/5' }}">
                    {{ $skill }}
                </a>
            @endforeach
        </div>

        <!-- Creators Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($creators as $creator)
                <div class="bg-white p-8 rounded-[3rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all group flex flex-col h-full">
                    <!-- Profile Header -->
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-20 h-20 rounded-[1.5rem] overflow-hidden shrink-0 border-2 border-white shadow-lg shadow-[#2563EB]/10 group-hover:scale-105 transition-transform">
                            <img src="{{ $creator->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($creator->name).'&background=2563EB&color=fff' }}" alt="{{ $creator->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-xl font-display font-bold text-[#1E3A8A] leading-tight group-hover:text-[#2563EB] transition-colors">{{ $creator->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-bold text-[#2563EB] uppercase tracking-wider">Graphic Designer</span>
                                <span class="w-1 h-1 bg-[#1E3A8A]/20 rounded-full"></span>
                                <span class="text-xs font-medium text-[#1E3A8A]/40">{{ $creator->city ?? 'Domisili tidak diatur' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Skills & Stats -->
                    <div class="flex-grow">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Branding</span>
                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">Logo Design</span>
                            <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">+3</span>
                        </div>
                        
                        <p class="text-[#1E3A8A]/60 text-sm mb-8 line-clamp-2 font-medium leading-relaxed">
                            {{ $creator->bio ?? 'Kreator ini belum menambahkan bio profil mereka.' }}
                        </p>

                        @if($creator->completed_projects_count > 0)
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="text-center p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase mb-1">Rating</p>
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/></svg>
                                    <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ $creator->average_rating }}</p>
                                </div>
                            </div>
                            <div class="text-center p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <p class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase mb-1">Proyek</p>
                                <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ $creator->completed_projects_count }}</p>
                            </div>
                        </div>
                        @else
                        <div class="mb-8 p-4 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center">
                            <p class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase italic">Kreator Baru di Konekin</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Footer -->
                    <div class="pt-6 border-t border-[#2563EB]/5 flex items-center justify-between mt-auto gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-[#1E3A8A]/40 uppercase">Mulai dari</span>
                            <span class="text-[#1E3A8A] font-display font-bold">Rp 350rb</span>
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
