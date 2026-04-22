<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Progress Proyek - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(255,255,255,.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.3); }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <div class="max-w-3xl">
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Progress Proyek UMKM</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Pantau proyek yang sudah dipublikasikan, pilih creative worker terbaik, lalu lihat update progress kerja yang mereka kirimkan.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
        @endif

        @forelse($projects as $project)
            <section class="bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                <div class="p-8 md:p-10 border-b border-[#2563EB]/5">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex gap-5">
                            <div class="w-24 h-24 rounded-[1.8rem] overflow-hidden bg-slate-100 shrink-0">
                                @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                    <video src="{{ $project->media_url }}" class="w-full h-full object-cover" muted playsinline></video>
                                @else
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">{{ $project->category }}</span>
                                    <span class="px-4 py-2 rounded-full bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-extrabold uppercase tracking-widest">{{ strtoupper($project->status_label ?? str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                                    @if(!empty($project->media_url))
                                        <span class="px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">
                                            {{ ($project->media_type ?? 'image') === 'video' ? 'Video Brief' : 'Foto Brief' }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="font-display text-2xl md:text-3xl font-bold text-[#1E3A8A] mb-2">{{ $project->title }}</h2>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 max-w-3xl">{{ $project->description }}</p>
                                <p class="text-sm text-[#2563EB] font-medium mt-3">{{ $project->progress_summary }}</p>
                                <p class="text-sm text-[#1E3A8A]/55 font-medium mt-2">Deadline: {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:w-[260px]">
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-4 text-center">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Apply</p>
                                <p class="font-display text-2xl font-bold text-[#1E3A8A]">{{ $project->applications->count() }}</p>
                            </div>
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-4 text-center">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Progress</p>
                                <p class="font-display text-2xl font-bold text-[#2563EB]">{{ $project->progress_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[0.7fr_1.3fr] gap-0">
                    <div class="p-8 md:p-10 border-b xl:border-b-0 xl:border-r border-[#2563EB]/5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-4">Ringkasan Proyek</p>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden mb-4">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#2563EB] to-[#0A66C2]" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                        </div>
                        @if(!empty($project->media_url))
                            <a href="{{ $project->media_url }}" target="_blank" class="mb-4 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[#EFF6FF] text-[#2563EB] text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">
                                {{ ($project->media_type ?? 'image') === 'video' ? 'Buka Video Referensi' : 'Buka Foto Referensi' }}
                            </a>
                        @endif
                        <div class="space-y-4">
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-5">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Creative Worker Terpilih</p>
                                @if(!empty($project->selected_creative_name))
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $project->selected_creative_avatar }}" alt="{{ $project->selected_creative_name }}" class="w-12 h-12 rounded-2xl object-cover">
                                        <div>
                                            <p class="font-bold text-[#1E3A8A]">{{ $project->selected_creative_name }}</p>
                                            <p class="text-xs text-[#2563EB] font-bold uppercase tracking-[0.16em] mt-1">Sudah Disetujui</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium">Belum ada creative worker yang disetujui. Pilih salah satu pelamar di daftar sebelah kanan.</p>
                                @endif
                            </div>
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-5">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Catatan Monitoring</p>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7">UMKM sekarang hanya memantau progres kerja, melihat file update dari creative worker, dan menyetujui pelamar yang dipilih. Pengubahan angka progress dilakukan dari sisi creative worker.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">Creative Worker Yang Apply</p>
                                <h3 class="font-display text-2xl font-bold text-[#1E3A8A]">Daftar Pelamar</h3>
                            </div>
                            <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-3 rounded-2xl bg-[#EFF6FF] text-[#2563EB] text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">Lihat Detail</a>
                        </div>

                        @forelse($project->applications as $application)
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-5 p-5 rounded-[2rem] bg-[#F8FAFC] border border-[#2563EB]/8 {{ !$loop->last ? 'mb-4' : '' }}">
                                <div class="flex gap-4">
                                    <img src="{{ $application->creative_avatar }}" alt="{{ $application->creative_name }}" class="w-14 h-14 rounded-2xl object-cover">
                                    <div>
                                        <p class="font-bold text-[#1E3A8A]">{{ $application->creative_name }}</p>
                                        <p class="text-xs font-bold text-[#2563EB] uppercase tracking-[0.16em] mt-1">{{ $application->creative_city ?? 'Creative Worker' }}</p>
                                        <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 mt-3">{{ $application->message ?: 'Belum ada pesan pengantar dari creative worker ini.' }}</p>
                                        @if(!empty($application->proposal_url))
                                            <a href="{{ $application->proposal_url }}" target="_blank" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-white border border-[#2563EB]/10 text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] hover:text-white transition-all">
                                                Proposal{{ !empty($application->proposal_type) ? ' ' . strtoupper($application->proposal_type) : '' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 text-left md:text-right">
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-2">Status Apply</p>
                                    <span class="inline-flex px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 text-[#1E3A8A] text-[10px] font-extrabold uppercase tracking-[0.16em]">{{ strtoupper($application->status ?? 'applied') }}</span>
                                    @if(($application->status ?? 'applied') !== 'approved')
                                        <form action="{{ route('projects.progress.approve', [$project->id, $application->id]) }}" method="POST" class="mt-3">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-[#1E3A8A] text-white text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] transition-all">
                                                Setujui Pelamar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[2rem] bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                                <p class="font-bold text-[#1E3A8A]">Belum ada apply masuk</p>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Progress proyek ini masih 0% sampai ada creative worker yang mulai mengajukan diri.</p>
                            </div>
                        @endforelse

                        <div class="mt-8 pt-8 border-t border-[#2563EB]/5">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-4">Update Progress Dari Creative Worker</p>
                            @forelse($project->progress_updates as $update)
                                <div class="p-5 rounded-[2rem] bg-white border border-[#2563EB]/8 {{ !$loop->last ? 'mb-4' : '' }}">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="font-bold text-[#1E3A8A]">{{ $update->creative_name }}</p>
                                            <p class="text-xs text-[#2563EB] font-bold uppercase tracking-[0.16em] mt-1">{{ optional($update->created_at)->diffForHumans() ?? 'Baru saja' }}</p>
                                        </div>
                                        <span class="inline-flex px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-[0.16em]">{{ $update->progress_percentage }}%</span>
                                    </div>
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 mt-3">{{ $update->note }}</p>
                                    @if(!empty($update->media_url))
                                        <a href="{{ $update->media_url }}" target="_blank" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-[#EFF6FF] text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] hover:text-white transition-all">
                                            {{ ($update->media_type ?? 'image') === 'video' ? 'Lihat Video Progress' : 'Lihat Foto Progress' }}
                                        </a>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-[2rem] bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                                    <p class="font-bold text-[#1E3A8A]">Belum ada update progress</p>
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Begitu creative worker terpilih mulai mengirim update, progres kerja akan tampil di sini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <div class="bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm p-12 text-center">
                <h2 class="font-display text-3xl font-bold text-[#1E3A8A] mb-3">Belum Ada Proyek</h2>
                <p class="text-[#1E3A8A]/60 font-medium mb-6">Mulai publikasi proyek pertamamu, lalu pantau apply dan progresnya dari halaman ini.</p>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">Publikasikan Proyek</a>
            </div>
        @endforelse
    </main>
</body>
</html>
