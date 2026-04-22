<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyek UMKM - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

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

    @if(auth()->check() && auth()->user()->isUMKM())
        <x-dashboard-nav-umkm />
    @elseif(auth()->check() && auth()->user()->isCreativeWorker())
        <x-dashboard-nav />
    @else
        <x-navbar />
    @endif

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-12 text-center max-w-3xl mx-auto">
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Lihat Proyek UMKM Yang Sedang Buka Kolaborasi</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg mb-8">Temukan brief terbaru dari UMKM, lihat kebutuhan kreatifnya, dan mulai peluang kolaborasi yang relevan untuk brand maupun karya terbaikmu.</p>

            <form action="{{ route('umkm.index') }}" method="GET" class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul proyek, kategori, atau kata kunci..." class="w-full px-8 py-5 rounded-[2.5rem] bg-white border-2 border-[#2563EB]/5 shadow-xl shadow-[#2563EB]/5 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] pr-32">
                <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 bg-[#2563EB] text-white rounded-full font-bold text-sm hover:bg-[#1E3A8A] transition-all">Cari</button>
            </form>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-12">
            @php
                $categories = ['Semua', 'Branding', 'Social Media', 'Web Dev', 'Videography', 'UI/UX Design', 'Illustration'];
                $activeCat = request('category', 'Semua');
            @endphp
            @foreach($categories as $cat)
                <a href="{{ route('umkm.index', ['category' => $cat, 'search' => request('search')]) }}"
                   class="px-6 py-3 rounded-full font-bold text-sm transition-all {{ $activeCat == $cat ? 'bg-[#1E3A8A] text-white shadow-lg shadow-[#1E3A8A]/20' : 'bg-white text-[#1E3A8A]/60 hover:bg-[#EFF6FF] border border-[#2563EB]/5' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($projects as $project)
                <div class="bg-white p-8 rounded-[3rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all group flex flex-col h-full">
                    <div class="relative h-56 rounded-[2.5rem] overflow-hidden mb-6 bg-slate-100">
                        @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                            <video src="{{ $project->media_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" muted playsinline></video>
                        @else
                            <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @endif

                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">{{ $project->category }}</span>
                            @if(isset($project->created_at) && $project->created_at && $project->created_at->diffInHours(now()) < 24)
                                <span class="px-4 py-2 bg-[#1E3A8A] text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">Baru</span>
                            @endif
                            @if(!empty($project->media_url))
                                <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[#1E3A8A] text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">
                                    {{ ($project->media_type ?? 'image') === 'video' ? 'Video Brief' : 'Foto Brief' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-4 gap-4">
                            <h3 class="text-2xl font-display font-bold text-[#1E3A8A] leading-tight">{{ $project->title }}</h3>
                            <span class="text-[#2563EB] font-display font-bold shrink-0 text-xl">Rp {{ $project->budget }}</span>
                        </div>

                        <p class="text-[#1E3A8A]/60 text-sm mb-6 line-clamp-3 font-medium leading-relaxed">{{ $project->description }}</p>

                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">{{ $project->applications_count ?? 0 }} Apply</span>
                            <span class="px-4 py-2 rounded-full bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest">{{ $project->progress_percentage ?? 0 }}% Progress</span>
                            <span class="px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest">{{ $project->status_label ?? strtoupper(str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                        </div>

                        <p class="text-[#1E3A8A]/55 text-xs font-medium mb-3">Deadline: {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</p>
                        <p class="text-[#2563EB] text-xs font-medium mb-6">{{ $project->progress_summary ?? 'Login untuk melihat update proyek lebih lengkap.' }}</p>
                    </div>

                    <div class="pt-6 border-t border-[#2563EB]/5 flex items-center justify-between gap-4 mt-auto">
                        <div class="flex items-center gap-3">
                            <img src="{{ $project->client_avatar }}" class="w-10 h-10 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-[#1E3A8A]">{{ $project->client_name }}</p>
                                <p class="text-[10px] font-bold text-[#2563EB] uppercase tracking-wider">UMKM Terverifikasi</p>
                            </div>
                        </div>

                        @if(auth()->check())
                            <a href="{{ route('projects.show', $project->id) }}" class="px-8 py-3 bg-[#EFF6FF] text-[#2563EB] hover:bg-[#2563EB] hover:text-white rounded-2xl font-bold text-xs transition-all inline-flex items-center justify-center">Lihat Detail</a>
                        @else
                            <button
                                type="button"
                                class="px-8 py-3 bg-[#EFF6FF] text-[#2563EB] hover:bg-[#2563EB] hover:text-white rounded-2xl font-bold text-xs transition-all inline-flex items-center justify-center"
                                onclick="openGuestProjectModal('{{ e($project->title) }}', '{{ e($project->client_name) }}')"
                            >
                                Lihat Detail
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mb-4 inline-block p-6 bg-white rounded-full shadow-sm">
                        <svg class="w-12 h-12 text-[#1E3A8A]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Belum Ada Proyek UMKM</h3>
                    <p class="text-[#1E3A8A]/60 font-medium mt-2">Coba lagi sebentar lagi atau gunakan kata kunci lain untuk mencari proyek yang cocok.</p>
                </div>
            @endforelse
        </div>
    </main>

    @guest
    <div id="guest-project-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-md" onclick="closeGuestProjectModal()"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-xl rounded-[2.5rem] bg-white border border-white/80 shadow-2xl shadow-[#1E3A8A]/20 overflow-hidden">
                <div class="relative px-8 sm:px-10 pt-10 pb-8 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.18),_transparent_42%),linear-gradient(135deg,#EFF6FF_0%,#FFFFFF_55%,#F8FAFC_100%)]">
                    <button type="button" onclick="closeGuestProjectModal()" class="absolute top-5 right-5 w-10 h-10 rounded-full bg-white/90 border border-[#2563EB]/10 text-[#1E3A8A] hover:bg-[#EFF6FF] transition-all">
                        <span class="sr-only">Tutup</span>
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 shadow-sm mb-6">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2563EB]"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB]">Akses Penuh Proyek</span>
                    </div>

                    <h2 class="font-display text-3xl sm:text-[2rem] font-bold text-[#1E3A8A] leading-tight mb-4">
                        Mau lihat brief proyek secara lengkap?
                    </h2>
                    <p class="text-[#1E3A8A]/65 font-medium leading-relaxed text-sm sm:text-base">
                        Login atau daftar ke Konekin untuk membuka detail penuh proyek, melihat kebutuhan lengkap dari UMKM, dan mulai mengajukan kolaborasi yang relevan.
                    </p>
                </div>

                <div class="px-8 sm:px-10 py-8">
                    <div class="rounded-[2rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-6 mb-8">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Yang Akan Kamu Dapatkan</p>
                        <div class="space-y-3 text-sm text-[#1E3A8A]/75 font-medium">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Akses detail lengkap proyek <span id="guest-project-title" class="font-bold text-[#1E3A8A]">pilihan UMKM</span>.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Lihat kebutuhan dari <span id="guest-project-client" class="font-bold text-[#1E3A8A]">UMKM di Konekin</span> secara utuh.</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-1 w-2 h-2 rounded-full bg-[#2563EB] shrink-0"></span>
                                <span>Kirim pengajuan kolaborasi dan bangun portofolio profesionalmu.</span>
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
                        Konekin mempertemukan creative worker dan UMKM dalam satu ekosistem kolaborasi yang relevan dan aman.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Proyek bertemu talenta yang tepat.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Privasi</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Syarat & Ketentuan</a>
        </div>
    </footer>

    @guest
    <script>
        function openGuestProjectModal(title, client) {
            document.getElementById('guest-project-title').textContent = title || 'pilihan UMKM';
            document.getElementById('guest-project-client').textContent = client || 'UMKM di Konekin';
            document.getElementById('guest-project-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGuestProjectModal() {
            document.getElementById('guest-project-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeGuestProjectModal();
            }
        });
    </script>
    @endguest
</body>
</html>
