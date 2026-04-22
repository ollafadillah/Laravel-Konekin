<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $creator->name }} - Profil Kreator | Konekin</title>

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
    @if(auth()->user()->isUMKM())
        <x-dashboard-nav-umkm />
    @else
        <x-dashboard-nav />
    @endif

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <section class="relative overflow-hidden rounded-[3rem] border border-[#2563EB]/10 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.14),_transparent_38%),linear-gradient(135deg,#EFF6FF_0%,#FFFFFF_55%,#F8FAFC_100%)] p-8 md:p-10 shadow-xl shadow-[#2563EB]/10">
            <div class="absolute -top-16 -right-10 w-48 h-48 rounded-full bg-[#2563EB]/10 blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center gap-8">
                <div class="flex items-center gap-5">
                    <div class="w-28 h-28 md:w-36 md:h-36 rounded-[2rem] overflow-hidden bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1.5 shadow-2xl shadow-[#2563EB]/20">
                        <img src="{{ $creator->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($creator->name).'&size=256&background=random' }}" alt="{{ $creator->name }}" class="w-full h-full object-cover rounded-[1.6rem] border-4 border-white">
                    </div>

                    <div>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB] mb-2">Creative Worker</p>
                        <h1 class="font-display text-3xl md:text-5xl font-bold text-[#1E3A8A] leading-tight">{{ $creator->name }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-[#1E3A8A]/55 font-medium">
                            <span>{{ $creator->city ?? 'Domisili belum diatur' }}</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]/30"></span>
                            <span>{{ $creator->phone ?? 'Kontak belum ditambahkan' }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:ml-auto grid grid-cols-3 gap-3 w-full lg:w-auto">
                    <div class="rounded-[1.8rem] bg-white/80 border border-white p-4 text-center shadow-sm">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Portfolio</p>
                        <p class="font-display text-2xl font-bold text-[#1E3A8A]">{{ $portfolios->count() }}</p>
                    </div>
                    <div class="rounded-[1.8rem] bg-white/80 border border-white p-4 text-center shadow-sm">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Status</p>
                        <p class="font-display text-lg font-bold text-[#2563EB]">Aktif</p>
                    </div>
                    <div class="rounded-[1.8rem] bg-white/80 border border-white p-4 text-center shadow-sm">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Konekin</p>
                        <p class="font-display text-lg font-bold text-[#1E3A8A]">Verified</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-[1.35fr_0.65fr] gap-8">
            <div class="space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB] mb-2">Tentang Kreator</p>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Profil Singkat</h2>
                        </div>
                        <a href="{{ route('kreator.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[#EFF6FF] text-[#2563EB] font-bold text-xs hover:bg-[#2563EB] hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            Kembali
                        </a>
                    </div>

                    <p class="text-[#1E3A8A]/68 text-base leading-8 font-medium">
                        {{ $creator->bio ?? 'Kreator ini belum menambahkan bio. Namun kamu tetap bisa melihat identitas dasar dan kumpulan portfolio yang sudah mereka tampilkan di Konekin.' }}
                    </p>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <div class="mb-8">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB] mb-2">Portfolio Showcase</p>
                        <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Karya Pilihan</h2>
                    </div>

                    @if($portfolios->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($portfolios as $portfolio)
                                <article class="group overflow-hidden rounded-[2rem] border border-[#2563EB]/8 bg-[#F8FAFC] hover:bg-white hover:shadow-xl hover:shadow-[#2563EB]/8 transition-all">
                                    <div class="relative aspect-[16/11] overflow-hidden">
                                        <img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-[#0F172A]/60 to-transparent">
                                            @if($portfolio->file_url)
                                                <a href="{{ $portfolio->file_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/95 text-[#1E3A8A] text-[11px] font-extrabold uppercase tracking-[0.16em] hover:bg-[#2563EB] hover:text-white transition-all">
                                                    Lihat File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-[0.16em]">
                                                {{ $portfolio->category ?? 'Portfolio' }}
                                            </span>
                                            @if($portfolio->file_type)
                                                <span class="px-3 py-1 rounded-full bg-white border border-[#2563EB]/10 text-[#1E3A8A]/60 text-[10px] font-extrabold uppercase tracking-[0.16em]">
                                                    {{ $portfolio->file_type }}
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-display text-xl font-bold text-[#1E3A8A] mb-3">{{ $portfolio->title }}</h3>
                                        <p class="text-sm leading-7 text-[#1E3A8A]/60 font-medium">
                                            {{ $portfolio->description }}
                                        </p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-[2rem] bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-10 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-white shadow-sm mx-auto mb-5 flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-display text-xl font-bold text-[#1E3A8A] mb-2">Portfolio Belum Ditambahkan</h3>
                            <p class="text-[#1E3A8A]/60 font-medium max-w-lg mx-auto">
                                Kreator ini belum mengunggah karya ke profilnya. Kamu masih bisa melihat identitas dasarnya dan kembali nanti saat portfolio sudah tersedia.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#2563EB] mb-2">Informasi Ringkas</p>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Highlight Profil</h2>

                    <div class="space-y-4">
                        <div class="rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Email</p>
                            <p class="text-sm font-bold text-[#1E3A8A] break-all">{{ $creator->email }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Domisili</p>
                            <p class="text-sm font-bold text-[#1E3A8A]">{{ $creator->city ?? 'Belum diatur' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Kontak</p>
                            <p class="text-sm font-bold text-[#1E3A8A]">{{ $creator->phone ?? 'Belum ditambahkan' }}</p>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Bergabung</p>
                            <p class="text-sm font-bold text-[#1E3A8A]">{{ optional($creator->created_at)->format('d M Y') ?? 'Baru bergabung' }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-[2.5rem] border border-[#2563EB]/10 bg-[linear-gradient(160deg,#1E3A8A_0%,#2563EB_55%,#0A66C2_100%)] p-8 text-white shadow-2xl shadow-[#2563EB]/20">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-white/70 mb-2">Next Step</p>
                    <h2 class="font-display text-2xl font-bold leading-tight mb-4">Sudah cocok dengan gaya {{ strtok($creator->name, ' ') }}?</h2>
                    <p class="text-sm leading-7 text-white/80 font-medium mb-6">
                        Simpan profil ini sebagai referensi dan lanjutkan proses pencarian kreator terbaik untuk kebutuhan brand, campaign, atau konten usahamu.
                    </p>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('kreator.index') }}" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-white text-[#1E3A8A] font-bold text-sm hover:bg-[#EFF6FF] transition-all">
                            Lihat Kreator Lain
                        </a>
                        @if(auth()->user()->isUMKM())
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl border border-white/20 text-white font-bold text-sm hover:bg-white/10 transition-all">
                                Upload Proyek
                            </a>
                        @else
                            <a href="{{ route('portfolio.index') }}" class="inline-flex items-center justify-center px-6 py-4 rounded-2xl border border-white/20 text-white font-bold text-sm hover:bg-white/10 transition-all">
                                Kelola Portfolio Saya
                            </a>
                        @endif
                    </div>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
