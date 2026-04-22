<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} - Proyek | Konekin</title>

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
    @if(auth()->user()->isUMKM())
        <x-dashboard-nav-umkm />
    @else
        <x-dashboard-nav />
    @endif

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
        @endif

        <section class="grid grid-cols-1 xl:grid-cols-[1.3fr_0.7fr] gap-8">
            <div class="space-y-8">
                <div class="bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm overflow-hidden">
                    <div class="relative h-[320px] bg-slate-100">
                        @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                            <video src="{{ $project->media_url }}" class="w-full h-full object-cover" controls></video>
                        @else
                            <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                            <span class="px-4 py-2 bg-white/90 text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ $project->category }}</span>
                            <span class="px-4 py-2 bg-[#1E3A8A] text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full">{{ strtoupper($project->status_label ?? str_replace('_', ' ', $project->status ?? 'open')) }}</span>
                        </div>
                    </div>

                    <div class="p-8 md:p-10">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-6">
                            <div>
                                <h1 class="font-display text-3xl md:text-4xl font-bold text-[#1E3A8A] leading-tight">{{ $project->title }}</h1>
                                <div class="flex items-center gap-3 mt-4 text-sm text-[#1E3A8A]/55 font-medium">
                                    <span>{{ $project->client_name }}</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]/30"></span>
                                    <span>{{ $project->applications_count ?? 0 }} creative sudah apply</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB]/30"></span>
                                    <span>Deadline {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="px-6 py-4 rounded-[2rem] bg-[#EFF6FF] text-center shrink-0">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-1">Budget</p>
                                <p class="font-display text-2xl font-bold text-[#1E3A8A]">Rp {{ $project->budget }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-8">
                            <div>
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Deskripsi Proyek</p>
                                <p class="text-[#1E3A8A]/68 leading-8 font-medium">{{ $project->description }}</p>
                            </div>

                            <div>
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Kebutuhan / Spesifikasi</p>
                                <p class="text-[#1E3A8A]/68 leading-8 font-medium">{{ $project->requirements ?: 'Belum ada spesifikasi tambahan dari UMKM.' }}</p>
                            </div>

                            <div>
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Deadline Pengerjaan</p>
                                <p class="text-[#1E3A8A]/68 leading-8 font-medium">{{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('l, d F Y') }}</p>
                            </div>

                            @if(!empty($project->media_url))
                                <div class="rounded-[2rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-6">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-3">Media Referensi</p>
                                    <a href="{{ $project->media_url }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">
                                        {{ ($project->media_type ?? 'image') === 'video' ? 'Buka Video Referensi' : 'Buka Gambar Referensi' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">Progress Proyek</p>
                    <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-5">{{ $project->progress_percentage ?? 0 }}% Berjalan</h2>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden mb-4">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#2563EB] to-[#0A66C2]" style="width: {{ $project->progress_percentage ?? 0 }}%"></div>
                    </div>
                    <p class="text-sm text-[#1E3A8A]/60 font-medium">{{ $project->progress_summary ?? 'Saat belum ada pelamar, progress akan tetap 0%. Begitu ada creative worker yang apply, progress bisa dilanjutkan oleh UMKM dari halaman progress proyek.' }}</p>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <img src="{{ $project->client_avatar }}" alt="{{ $project->client_name }}" class="w-14 h-14 rounded-2xl object-cover">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#2563EB] mb-1">Dipublikasikan Oleh</p>
                            <p class="font-bold text-[#1E3A8A]">{{ $project->client_name }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isCreativeWorker())
                        @if(!empty($project->selected_creative_id) && (string) $project->selected_creative_id !== (string) auth()->id())
                            <div class="rounded-[2rem] bg-amber-50 border border-amber-200 p-5 text-amber-700 text-sm font-bold">
                                Creative worker untuk proyek ini sudah dipilih oleh UMKM, jadi pengajuan baru sudah ditutup.
                            </div>
                        @else
                            <form action="{{ route('projects.apply', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Deskripsi Pengajuan</label>
                                    <textarea name="message" rows="4" class="mt-2 w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border {{ $errors->has('message') ? 'border-red-400 focus:border-red-500' : 'border-[#2563EB]/10 focus:border-[#2563EB]' }} outline-none transition-all font-medium" placeholder="Ceritakan kenapa kamu cocok untuk proyek ini, pengalaman relevan, dan pendekatan yang akan kamu pakai...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Upload Proposal</label>
                                    <input type="file" name="proposal_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip" class="mt-2 w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border {{ $errors->has('proposal_file') ? 'border-red-400 focus:border-red-500' : 'border-[#2563EB]/10 focus:border-[#2563EB]' }} outline-none transition-all font-medium file:mr-4 file:rounded-xl file:border-0 file:bg-[#EFF6FF] file:px-4 file:py-2 file:text-xs file:font-bold file:text-[#2563EB] hover:file:bg-[#DBEAFE]">
                                    <p class="mt-2 text-xs text-[#1E3A8A]/50 font-medium">Format PDF, DOC, DOCX, PPT, PPTX, atau ZIP. Maksimal 20MB.</p>
                                    @error('proposal_file')
                                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10">
                                    Ajukan ke Proyek Ini
                                </button>
                            </form>
                        @endif
                    @elseif(auth()->user()->isUMKM())
                        <div class="space-y-3">
                            @if($project->status === 'hired' && ($project->escrow_status ?? '') !== 'held')
                                <a href="{{ route('escrow.checkout', $project->id) }}" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-[#2563EB] text-white font-bold text-sm hover:bg-[#1E3A8A] transition-all shadow-lg shadow-[#2563EB]/20">
                                    <i class="fas fa-wallet mr-2"></i> Bayar Escrow (Amankan Dana)
                                </a>
                            @endif
                            <a href="{{ route('projects.progress') }}" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">
                                Lihat Progress Proyek
                            </a>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-4">Pelamar Terbaru</p>
                    @forelse($applications->take(3) as $application)
                        <div class="flex items-start gap-4 {{ !$loop->last ? 'mb-5 pb-5 border-b border-[#2563EB]/6' : '' }}">
                            <img src="{{ $application->creative_avatar }}" alt="{{ $application->creative_name }}" class="w-12 h-12 rounded-2xl object-cover">
                            <div>
                                <p class="font-bold text-[#1E3A8A]">{{ $application->creative_name }}</p>
                                <p class="text-xs text-[#2563EB] font-bold uppercase tracking-wider mt-1">{{ $application->creative_city ?? 'Creative Worker' }}</p>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">{{ $application->message ?: 'Belum menambahkan pesan pengantar.' }}</p>
                                @if(!empty($application->proposal_url))
                                    <a href="{{ $application->proposal_url }}" target="_blank" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-[#EFF6FF] text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] hover:text-white transition-all">
                                        Lihat Proposal{{ !empty($application->proposal_type) ? ' (' . strtoupper($application->proposal_type) . ')' : '' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#1E3A8A]/60 font-medium">Belum ada creative worker yang apply ke proyek ini.</p>
                    @endforelse
                </div>

                <div class="bg-white rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm p-8">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-4">Update Progress Terbaru</p>
                    @forelse($progressUpdates->take(3) as $update)
                        <div class="{{ !$loop->last ? 'mb-5 pb-5 border-b border-[#2563EB]/6' : '' }}">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-bold text-[#1E3A8A]">{{ $update->creative_name }}</p>
                                <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-[0.16em]">{{ $update->progress_percentage }}%</span>
                            </div>
                            <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">{{ $update->note }}</p>
                            @if(!empty($update->media_url))
                                <a href="{{ $update->media_url }}" target="_blank" class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl bg-[#EFF6FF] text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.14em] hover:bg-[#2563EB] hover:text-white transition-all">
                                    {{ ($update->media_type ?? 'image') === 'video' ? 'Lihat Video Progress' : 'Lihat Foto Progress' }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-[#1E3A8A]/60 font-medium">Belum ada update progress yang dikirim creative worker.</p>
                    @endforelse
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
