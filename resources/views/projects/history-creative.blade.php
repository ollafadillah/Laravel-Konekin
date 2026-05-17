<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Proyek - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.08), transparent 32rem),
                #F8FAFC;
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.74);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.42);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-10">
            <x-page-back :href="route('dashboard.creative')" label="Kembali ke Dashboard" class="mb-7" />

            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-8 items-end">
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.18em] mb-5">Riwayat Creative Worker</span>
                    <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] leading-tight mb-4">
                        Proyek selesai dan review dari UMKM
                    </h1>
                    <p class="text-[#1E3A8A]/60 font-medium text-lg leading-8 max-w-3xl">
                        Semua proyek yang sudah diselesaikan dan diberi rating akan tersimpan di sini sebagai bukti reputasi kerja kreatifmu.
                    </p>
                </div>

                <div class="rounded-[2rem] bg-[#1E3A8A] text-white p-6 shadow-xl shadow-[#1E3A8A]/15">
                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/55 mb-2">Reputasi Saat Ini</p>
                    <div class="flex items-end gap-3 mb-4">
                        <p class="font-display text-5xl font-bold">{{ $ratingsCount > 0 ? rtrim(rtrim(number_format($averageRating, 1, '.', ''), '0'), '.') : '-' }}</p>
                        <p class="pb-2 text-sm font-bold text-white/65">dari {{ $ratingsCount }} review</p>
                    </div>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-amber-300' : 'text-white/20' }} fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass rounded-[2rem] p-6 shadow-sm">
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#1E3A8A]/40 mb-2">Proyek Selesai</p>
                <p class="font-display text-4xl font-bold text-[#1E3A8A]">{{ $completedCount }}</p>
            </div>
            <div class="glass rounded-[2rem] p-6 shadow-sm">
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#1E3A8A]/40 mb-2">Total Review</p>
                <p class="font-display text-4xl font-bold text-[#1E3A8A]">{{ $ratingsCount }}</p>
            </div>
            <div class="glass rounded-[2rem] p-6 shadow-sm">
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#1E3A8A]/40 mb-2">Review Bintang 5</p>
                <p class="font-display text-4xl font-bold text-[#1E3A8A]">{{ $fiveStarCount }}</p>
            </div>
        </section>

        <section class="space-y-5">
            @forelse($historyItems as $item)
                <article class="bg-white rounded-[2rem] border border-[#2563EB]/6 shadow-sm overflow-hidden hover:shadow-xl hover:shadow-[#2563EB]/6 transition-all">
                    <div class="grid grid-cols-1 lg:grid-cols-[260px_minmax(0,1fr)_310px]">
                        <div class="h-56 lg:h-full min-h-[220px] bg-slate-100 overflow-hidden">
                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        </div>

                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-widest">{{ $item->category }}</span>
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest">Selesai</span>
                            </div>

                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A] leading-tight mb-4">{{ $item->title }}</h2>

                            <div class="flex flex-wrap gap-4 text-sm text-[#1E3A8A]/55 font-bold">
                                <span>UMKM: {{ $item->client_name }}</span>
                                @if($item->budget)
                                    <span>Budget: Rp {{ is_numeric($item->budget) ? number_format((float) $item->budget, 0, ',', '.') : $item->budget }}</span>
                                @endif
                                @if($item->rated_at)
                                    <span>Review: {{ optional($item->rated_at)->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-[#F8FAFC] border-t lg:border-t-0 lg:border-l border-[#2563EB]/6 p-6 md:p-8">
                            <div class="flex items-center gap-3 mb-5">
                                <img src="{{ $item->client_avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($item->client_name) . '&background=2563EB&color=fff' }}" alt="{{ $item->client_name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white shadow-sm">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-[#2563EB]">Feedback UMKM</p>
                                    <p class="font-bold text-[#1E3A8A] truncate">{{ $item->client_name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $item->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm font-black text-[#1E3A8A]">{{ $item->rating }}/5</span>
                            </div>

                            <p class="text-sm text-[#1E3A8A]/65 font-medium leading-7">
                                "{{ $item->comment ?: 'UMKM memberikan rating tanpa komentar.' }}"
                            </p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-[2rem] border border-dashed border-[#2563EB]/12 p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-2">Belum ada riwayat review</h2>
                    <p class="text-[#1E3A8A]/60 font-medium leading-7 max-w-xl mx-auto">
                        Setelah proyek selesai dan UMKM memberikan rating, riwayat proyek beserta review bintangnya akan tampil di sini.
                    </p>
                </div>
            @endforelse
        </section>
    </main>
</body>
</html>
