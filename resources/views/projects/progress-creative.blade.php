<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Progress Proyek Creative Worker - Konekin</title>

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
    <x-dashboard-nav />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <div class="max-w-3xl">
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Progress Proyek Creative Worker</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Lihat proyek yang sudah disetujui untukmu, kirim update progress 0% sampai 100%, dan upload bukti pengerjaan berupa foto atau video.</p>
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
                                </div>
                                <h2 class="font-display text-2xl md:text-3xl font-bold text-[#1E3A8A] mb-2">{{ $project->title }}</h2>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 max-w-3xl">{{ $project->description }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-[#1E3A8A]/55 font-medium">
                                    <span>Klien: {{ $project->client_name }}</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]/30"></span>
                                    <span>Deadline: {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:w-[260px]">
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-4 text-center">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Budget</p>
                                <p class="font-display text-xl font-bold text-[#1E3A8A]">Rp {{ $project->budget }}</p>
                            </div>
                            <div class="rounded-[1.6rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-4 text-center">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#1E3A8A]/40 mb-1">Progress</p>
                                <p class="font-display text-2xl font-bold text-[#2563EB]">{{ $project->progress_percentage ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[0.75fr_1.25fr] gap-0">
                    <div class="p-8 md:p-10 border-b xl:border-b-0 xl:border-r border-[#2563EB]/5">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-4">Kirim Update Progress</p>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden mb-5">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#2563EB] to-[#0A66C2]" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                        </div>

                        <form action="{{ route('projects.progress.creative.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Persentase Progress</label>
                                <select name="progress_percentage" class="mt-2 w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] outline-none font-medium">
                                    @foreach([0, 10, 25, 40, 50, 60, 75, 90, 100] as $progress)
                                        <option value="{{ $progress }}" {{ (int) ($project->progress_percentage ?? 0) === $progress ? 'selected' : '' }}>{{ $progress }}%</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Catatan Progress</label>
                                <textarea name="note" rows="4" class="mt-2 w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border {{ $errors->has('note') ? 'border-red-400 focus:border-red-500' : 'border-[#2563EB]/10 focus:border-[#2563EB]' }} outline-none transition-all font-medium" placeholder="Ceritakan progres pekerjaan terbaru yang sudah kamu kerjakan...">{{ old('note') }}</textarea>
                                @error('note')
                                    <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Upload Bukti Progress (Opsional)</label>
                                <input type="file" name="progress_media" accept="image/*,video/*" class="mt-2 w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border {{ $errors->has('progress_media') ? 'border-red-400 focus:border-red-500' : 'border-[#2563EB]/10 focus:border-[#2563EB]' }} outline-none transition-all font-medium file:mr-4 file:rounded-xl file:border-0 file:bg-[#EFF6FF] file:px-4 file:py-2 file:text-xs file:font-bold file:text-[#2563EB] hover:file:bg-[#DBEAFE]">
                                <p class="mt-2 text-xs text-[#1E3A8A]/50 font-medium">Bisa berupa foto hasil kerja, draft visual, atau video progress. Maksimal 20MB.</p>
                                @error('progress_media')
                                    <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">
                                Simpan Update Progress
                            </button>
                        </form>
                    </div>

                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between gap-4 mb-6">
                            <div>
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">Riwayat Update</p>
                                <h3 class="font-display text-2xl font-bold text-[#1E3A8A]">Progress Yang Sudah Dikirim</h3>
                            </div>
                            <a href="{{ route('projects.show', $project->id) }}" class="px-5 py-3 rounded-2xl bg-[#EFF6FF] text-[#2563EB] text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">Lihat Detail</a>
                        </div>

                        @forelse($project->progress_updates as $update)
                            <div class="p-5 rounded-[2rem] bg-[#F8FAFC] border border-[#2563EB]/8 {{ !$loop->last ? 'mb-4' : '' }}">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-[#1E3A8A]">{{ $update->creative_name }}</p>
                                        <p class="text-xs text-[#2563EB] font-bold uppercase tracking-[0.16em] mt-1">{{ optional($update->created_at)->diffForHumans() ?? 'Baru saja' }}</p>
                                    </div>
                                    <span class="inline-flex px-4 py-2 rounded-full bg-white border border-[#2563EB]/10 text-[#2563EB] text-[10px] font-extrabold uppercase tracking-[0.16em]">{{ $update->progress_percentage }}%</span>
                                </div>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 mt-3">{{ $update->note }}</p>
                                @if(!empty($update->media_url))
                                    <a href="{{ $update->media_url }}" target="_blank" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-white border border-[#2563EB]/10 text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] hover:text-white transition-all">
                                        {{ ($update->media_type ?? 'image') === 'video' ? 'Lihat Video Progress' : 'Lihat Foto Progress' }}
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-[2rem] bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                                <p class="font-bold text-[#1E3A8A]">Belum ada update progress</p>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Kirim update progress pertama agar UMKM bisa memantau perkembangan pekerjaanmu.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        @empty
            <div class="bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm p-12 text-center">
                <h2 class="font-display text-3xl font-bold text-[#1E3A8A] mb-3">Belum Ada Proyek Aktif</h2>
                <p class="text-[#1E3A8A]/60 font-medium mb-6">Begitu UMKM menyetujui lamaranmu, proyek tersebut akan muncul di sini dan bisa kamu update progresnya.</p>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">Cari Proyek Baru</a>
            </div>
        @endforelse
    </main>
</body>
</html>
