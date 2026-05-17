<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Progress Proyek - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .readable-text {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
    </style>
</head>

<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
        <header class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div class="max-w-3xl">
                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-3">Workspace UMKM</p>
                <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] mb-4">Progress Proyek</h1>
                <p class="text-[#1E3A8A]/62 font-medium text-lg leading-8">Kelola pelamar, pantau draft, amankan
                    pembayaran escrow, lalu approve hasil akhir dengan alur yang jelas.</p>
            </div>
            <a href="{{ route('projects.create') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">
                <i class="fas fa-plus"></i>
                Publikasikan Proyek
            </a>
        </header>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm">
                {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">
                {{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
                <p class="font-bold mb-2">Ada input yang perlu dicek:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @forelse($projects as $project)
            @php
                $status = $project->status ?? 'open';
                $escrowStatus = $project->escrow_status ?? 'unpaid';
                $progress = (int) ($project->progress_percentage ?? 0);
                $applicationsCount = $project->applications->count();
                $hasWorker = !empty($project->selected_creative_id);
                $payment = $project->payment ?? null;
                $isAwaitingPayment = $hasWorker && $progress >= 100 && !in_array($escrowStatus, ['held', 'released', 'disputed'], true);
                $isReadyForReview = $escrowStatus === 'held' && $progress >= 100 && $status === 'ready_for_review';
                $stage = !$hasWorker ? 1 : ($progress < 100 ? 2 : ($isAwaitingPayment ? 3 : (in_array($status, ['ready_for_review', 'revision', 'disputed'], true) ? 4 : (in_array($status, ['pending_admin_approval', 'completed'], true) ? 5 : 3))));
                $steps = [
                    ['title' => 'Pilih Worker', 'note' => 'Review pelamar'],
                    ['title' => 'Draft 100%', 'note' => 'Tunggu progress'],
                    ['title' => 'Bayar Escrow', 'note' => 'VA + bukti'],
                    ['title' => 'Review Hasil', 'note' => 'Approve/revisi'],
                    ['title' => 'Admin Release', 'note' => 'Cair/refund'],
                ];
            @endphp

            <section id="project-{{ $project->id }}"
                class="scroll-mt-32 bg-white border border-[#2563EB]/8 rounded-[2rem] shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-[#2563EB]/8">
                    @if($project->is_overdue)
                        <div class="mb-6 p-5 rounded-2xl bg-red-50 border border-red-200 text-red-800">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-11 h-11 rounded-2xl bg-red-600 text-white flex items-center justify-center shrink-0">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-red-500 mb-1">Keterlambatan
                                        Proyek</p>
                                    <p class="font-bold leading-7">{{ $project->overdue_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">
                        <div class="flex gap-5 min-w-0">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                                @if(($project->media_type ?? null) === 'video' && !empty($project->media_url))
                                    <video src="{{ $project->media_url }}" class="w-full h-full object-cover" muted
                                        playsinline></video>
                                @else
                                    <img src="{{ $project->thumbnail }}" alt="{{ $project->title }}"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-widest">{{ $project->category }}</span>
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-slate-100 text-[#1E3A8A]/70 text-[10px] font-black uppercase tracking-widest">{{ $project->status_label }}</span>
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-white border border-[#2563EB]/10 text-[#1E3A8A]/60 text-[10px] font-black uppercase tracking-widest">{{ $applicationsCount }}
                                        apply</span>
                                </div>
                                <h2 class="font-display text-2xl md:text-3xl font-bold text-[#1E3A8A] leading-tight">
                                    {{ $project->title }}</h2>
                                <p class="mt-3 text-sm text-[#1E3A8A]/62 font-medium leading-7">
                                    {{ \Illuminate\Support\Str::limit($project->description, 220) }}</p>
                                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs font-bold text-[#1E3A8A]/55">
                                    <span><i class="fas fa-calendar mr-1 text-[#2563EB]"></i> Deadline
                                        {{ \Illuminate\Support\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</span>
                                    <span><i class="fas fa-wallet mr-1 text-[#2563EB]"></i> Rp {{ $project->budget }}</span>
                                    @if(!empty($project->selected_creative_name))
                                        <span><i class="fas fa-user-check mr-1 text-[#0A66C2]"></i>
                                            {{ $project->selected_creative_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 p-5">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#1E3A8A]/45">Progress</p>
                                <p class="font-display text-3xl font-bold text-[#2563EB]">{{ $progress }}%</p>
                            </div>
                            <div class="h-3 rounded-full bg-white overflow-hidden border border-[#2563EB]/10">
                                <div class="h-full bg-gradient-to-r from-[#1E3A8A] via-[#2563EB] to-[#0EA5E9]"
                                    style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="mt-3 text-xs text-[#1E3A8A]/60 font-medium leading-6">{{ $project->progress_summary }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-5 gap-3">
                        @foreach($steps as $index => $step)
                            @php
                                $stepNumber = $index + 1;
                                $stateClass = $stepNumber < $stage
                                    ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
                                    : ($stepNumber === $stage ? 'bg-[#EFF6FF] border-[#2563EB] text-[#1E3A8A]' : 'bg-slate-50 border-slate-200 text-slate-400');
                            @endphp
                            <div class="rounded-2xl border p-4 {{ $stateClass }}">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/80 flex items-center justify-center text-xs font-black">
                                        {{ $stepNumber }}</div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-wider">{{ $step['title'] }}</p>
                                        <p class="text-[11px] font-semibold opacity-75 mt-0.5">{{ $step['note'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[360px_1fr] gap-0">
                    <aside class="p-6 md:p-8 bg-[#FBFDFF] border-b xl:border-b-0 xl:border-r border-[#2563EB]/8 space-y-5">
                        <div class="rounded-2xl bg-white border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-3">Aksi
                                Berikutnya</p>

                            @if(!$hasWorker && $applicationsCount > 0)
                                <h3 class="font-display text-xl font-bold mb-2">Pilih creative worker</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7 mb-4">Buka proposal pelamar, cek kecocokan, lalu
                                    setujui satu creative worker untuk mulai kerja.</p>
                            @elseif(!$hasWorker)
                                <h3 class="font-display text-xl font-bold mb-2">Menunggu apply</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7">Proyek belum punya pelamar. Jika belum
                                    dibutuhkan, proyek masih bisa dihapus.</p>
                                <button type="button"
                                    class="mt-4 w-full rounded-2xl bg-amber-600 px-5 py-3 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-700 transition-all"
                                    onclick="openDeleteModal(@js($project->id), @js($project->title))">
                                    Hapus Proyek
                                </button>
                            @elseif($progress < 100)
                                <h3 class="font-display text-xl font-bold mb-2">Pantau pengerjaan</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7">Creative worker sedang mengirim update.
                                    Pembayaran escrow akan diminta setelah draft mencapai 100%.</p>
                            @elseif($isAwaitingPayment)
                                <h3 class="font-display text-xl font-bold mb-2">Amankan dana escrow</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7 mb-4">UMKM wajib membayar setelah draft 100%. Dana
                                    ditahan platform, bukan langsung cair ke creative worker.</p>
                                @if($payment && $payment->status === 'pending')
                                    <a href="{{ route('payments.show', $payment->_id) }}"
                                        class="w-full inline-flex justify-center rounded-2xl bg-amber-600 px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-700 transition-all">Buka
                                        VA & Upload Bukti</a>
                                @elseif($payment && $payment->isAwaitingVerification())
                                    <a href="{{ route('payments.show', $payment->_id) }}"
                                        class="w-full inline-flex justify-center rounded-2xl bg-amber-600 px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-700 transition-all">Lihat
                                        Verifikasi Bukti</a>
                                @else
                                    <form action="{{ route('payments.generate', $project->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full rounded-2xl bg-amber-600 px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-amber-700 transition-all">Generate
                                            VA Escrow</button>
                                    </form>
                                @endif
                            @elseif($isReadyForReview)
                                <h3 class="font-display text-xl font-bold mb-2">Review hasil akhir</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7 mb-4">Dana sudah ditahan platform. Setujui jika
                                    puas, minta revisi jika masih kurang, atau ajukan dispute jika buntu.</p>
                                <div class="space-y-3">
                                    <form action="{{ route('projects.progress.approve-completion', $project->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Setujui proyek selesai dan ajukan pencairan dana ke admin?')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full rounded-2xl bg-[#10B981] px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-[#059669] transition-all">Setuju,
                                            Proyek Selesai</button>
                                    </form>
                                    <button type="button" onclick="openRevisionModal(@js($project->id), @js($project->title))"
                                        class="w-full rounded-2xl bg-[#EFF6FF] px-5 py-4 text-[#2563EB] text-xs font-black uppercase tracking-widest hover:bg-blue-100 transition-all">Minta
                                        Revisi</button>
                                    <button type="button" onclick="openDisputeModal(@js($project->id), @js($project->title))"
                                        class="w-full rounded-2xl bg-red-600 px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-red-700 transition-all">Ajukan
                                        Dispute</button>
                                </div>
                            @elseif($status === 'revision')
                                <h3 class="font-display text-xl font-bold mb-2">Revisi berjalan</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7">Permintaan revisi sudah dikirim. Dana tetap
                                    ditahan platform sampai revisi disetujui.</p>
                                @if(!empty($project->revision_reason))
                                    <p class="mt-3 rounded-xl bg-[#EFF6FF] p-3 text-xs font-bold text-[#1E3A8A]/70">
                                        {{ $project->revision_reason }}</p>
                                @endif
                            @elseif($status === 'disputed')
                                <h3 class="font-display text-xl font-bold mb-2 text-red-700">Dispute aktif</h3>
                                <p class="text-sm text-red-700/80 leading-7">Dana dibekukan sampai admin/mediator menentukan
                                    dana dicairkan atau dikembalikan.</p>
                            @elseif($status === 'pending_admin_approval')
                                <h3 class="font-display text-xl font-bold mb-2">Menunggu admin</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7">UMKM sudah menyetujui hasil. Admin sedang
                                    memproses pencairan ke creative worker.</p>
                            @elseif($status === 'completed' && !($project->is_rated ?? false))
                                <h3 class="font-display text-xl font-bold mb-2">Beri feedback</h3>
                                <form action="{{ route('rating.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <button type="button" onclick="setRating('{{ $project->id }}', {{ $i }})"
                                                class="rating-star-{{ $project->id }} text-slate-300 hover:text-amber-400 transition-colors">
                                                <svg class="w-7 h-7 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z" />
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating_input_{{ $project->id }}" value="0" required>
                                    <textarea name="comment" rows="3"
                                        class="w-full rounded-2xl border border-[#2563EB]/10 px-4 py-3 text-sm"
                                        placeholder="Tulis feedback opsional..."></textarea>
                                    <button type="submit"
                                        class="w-full rounded-2xl bg-[#1E3A8A] px-5 py-4 text-white text-xs font-black uppercase tracking-widest hover:bg-[#2563EB] transition-all">Kirim
                                        Rating</button>
                                </form>
                            @else
                                <h3 class="font-display text-xl font-bold mb-2">Status aman</h3>
                                <p class="text-sm text-[#1E3A8A]/60 leading-7">Tidak ada aksi mendesak untuk proyek ini.</p>
                            @endif
                        </div>

                        <div class="rounded-2xl bg-white border border-[#2563EB]/8 p-5">
                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-3">Ringkasan Dana
                            </p>
                            <div class="space-y-3 text-sm font-bold">
                                <div class="flex justify-between gap-4"><span
                                        class="text-[#1E3A8A]/55">Budget</span><span>Rp {{ $project->budget }}</span></div>
                                <div class="flex justify-between gap-4"><span
                                        class="text-[#1E3A8A]/55">Escrow</span><span>{{ strtoupper($escrowStatus) }}</span>
                                </div>
                                <div class="flex justify-between gap-4"><span
                                        class="text-[#1E3A8A]/55">Pelamar</span><span>{{ $applicationsCount }}</span></div>
                            </div>
                        </div>
                    </aside>

                    <div class="p-6 md:p-8 space-y-8">
                        <section>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-1">
                                        Pelamar</p>
                                    <h3 class="font-display text-2xl font-bold">Proposal Creative Worker</h3>
                                </div>
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-[#EFF6FF] text-[#2563EB] text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">Lihat
                                    Detail</a>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @forelse($project->applications as $application)
                                    @php
                                        $applicationMessage = $application->message ?: 'Belum ada pesan pengantar.';
                                        $applicationMessageLimit = 180;
                                        $isApplicationMessageLong = \Illuminate\Support\Str::length($applicationMessage) > $applicationMessageLimit;
                                        $applicationMessageId = 'application-message-' . $application->id;
                                    @endphp
                                    <article
                                        class="rounded-2xl border {{ ($application->status ?? 'applied') === 'approved' ? 'border-emerald-200 bg-emerald-50/40' : 'border-[#2563EB]/8 bg-[#F8FAFC]' }} p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex gap-4 min-w-0">
                                                <img src="{{ $application->creative_avatar }}"
                                                    alt="{{ $application->creative_name }}"
                                                    class="w-12 h-12 rounded-2xl object-cover shrink-0">
                                                <div class="min-w-0">
                                                    <p class="font-bold text-[#1E3A8A]">{{ $application->creative_name }}</p>
                                                    <p
                                                        class="text-[10px] font-black uppercase tracking-widest text-[#2563EB] mt-1">
                                                        {{ $application->creative_city ?? 'Creative Worker' }}</p>
                                                </div>
                                            </div>
                                            <span
                                                class="shrink-0 px-3 py-1.5 rounded-full bg-white border border-[#2563EB]/10 text-[10px] font-black uppercase tracking-widest">{{ $application->status ?? 'applied' }}</span>
                                        </div>

                                        <p id="{{ $applicationMessageId }}"
                                            class="readable-text mt-4 text-sm text-[#1E3A8A]/62 font-medium leading-7">
                                            @if($isApplicationMessageLong)
                                                <span
                                                    data-readable-short>{{ \Illuminate\Support\Str::limit($applicationMessage, $applicationMessageLimit, '') }}</span><span
                                                    data-readable-full class="hidden">{{ $applicationMessage }}</span><button
                                                    type="button"
                                                    class="ml-1 font-black text-[#2563EB] hover:text-[#1E3A8A] transition-colors"
                                                    data-show-label="...Lihat Selengkapnya" data-hide-label="Sembunyikan"
                                                    onclick="toggleReadableText(@js($applicationMessageId), this)">...Lihat
                                                    Selengkapnya</button>
                                            @else
                                                {{ $applicationMessage }}
                                            @endif
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @if(!empty($application->proposal_url))
                                                <a href="{{ route('project-applications.proposal.preview', $application->id) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#2563EB]/10 text-[#2563EB] text-[11px] font-black uppercase tracking-widest hover:bg-[#2563EB] hover:text-white transition-all">
                                                    <i class="fas fa-file-lines"></i>
                                                    Lihat {{ strtoupper($application->proposal_type ?? 'FILE') }}
                                                </a>
                                                <a href="{{ route('project-applications.proposal.download', $application->id) }}"
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#10B981]/20 text-[#059669] text-[11px] font-black uppercase tracking-widest hover:bg-[#10B981] hover:text-white transition-all">
                                                    <i class="fas fa-file-arrow-down"></i>
                                                    Download
                                                </a>
                                                <span
                                                    class="inline-flex items-center px-3 py-2 rounded-xl bg-white text-[#1E3A8A]/45 text-[11px] font-bold max-w-full truncate">
                                                    {{ $application->proposal_display_name }}
                                                </span>
                                            @endif

                                            @if(($application->status ?? 'applied') !== 'approved')
                                                <form
                                                    action="{{ route('projects.progress.approve', [$project->id, $application->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-4 py-2 rounded-xl bg-[#1E3A8A] text-white text-[11px] font-black uppercase tracking-widest hover:bg-[#2563EB] transition-all">
                                                        Setujui
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div
                                        class="lg:col-span-2 rounded-2xl bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                                        <p class="font-bold">Belum ada apply masuk</p>
                                        <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Proposal creative worker akan
                                            muncul di sini setelah mereka apply.</p>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                        <section>
                            @php
                                $progressUpdates = $project->progress_updates ?? collect();
                                $latestUpdate = $progressUpdates->first();
                                $olderUpdates = $progressUpdates->skip(1)->values();
                            @endphp

                            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-5">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-1">Riwayat</p>
                                    <h3 class="font-display text-2xl font-bold">Update Progress</h3>
                                </div>
                                @if($progressUpdates->isNotEmpty())
                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[11px] font-black uppercase tracking-widest">
                                        <i class="fas fa-clock-rotate-left"></i>
                                        {{ $progressUpdates->count() }} Update
                                    </span>
                                @endif
                            </div>

                            @if($latestUpdate)
                                @php
                                    $latestNote = $latestUpdate->note ?: 'Creative worker belum menambahkan catatan progress.';
                                    $latestNoteLimit = 220;
                                    $isLatestNoteLong = \Illuminate\Support\Str::length($latestNote) > $latestNoteLimit;
                                    $latestNoteId = 'latest-progress-note-' . $latestUpdate->id;
                                @endphp

                                <div class="rounded-[1.75rem] border border-[#2563EB]/10 bg-gradient-to-br from-[#EFF6FF] to-white p-5 shadow-sm">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                                <span class="px-3 py-1 rounded-full bg-white text-[#2563EB] text-[10px] font-black uppercase tracking-widest border border-[#2563EB]/10">Update Terbaru</span>
                                                <span class="text-xs text-[#1E3A8A]/45 font-bold">{{ optional($latestUpdate->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                            </div>

                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-3">
                                                <p class="font-display text-xl font-bold text-[#1E3A8A]">{{ $latestUpdate->creative_name }}</p>
                                                <span class="w-fit px-4 py-1.5 rounded-full bg-[#2563EB] text-white text-[11px] font-black uppercase tracking-widest">{{ $latestUpdate->progress_percentage }}%</span>
                                            </div>

                                            <p id="{{ $latestNoteId }}" class="readable-text text-sm text-[#1E3A8A]/68 font-medium leading-7">
                                                @if($isLatestNoteLong)
                                                    <span data-readable-short>{{ \Illuminate\Support\Str::limit($latestNote, $latestNoteLimit, '') }}</span><span data-readable-full class="hidden">{{ $latestNote }}</span><button type="button"
                                                        class="ml-1 font-black text-[#2563EB] hover:text-[#1E3A8A] transition-colors"
                                                        data-show-label="...Lihat Selengkapnya" data-hide-label="Sembunyikan"
                                                        onclick="toggleReadableText(@js($latestNoteId), this)">...Lihat Selengkapnya</button>
                                                @else
                                                    {{ $latestNote }}
                                                @endif
                                            </p>
                                        </div>

                                        @if(!empty($latestUpdate->media_url))
                                            <a href="{{ $latestUpdate->media_url }}" target="_blank"
                                                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white border border-[#2563EB]/10 text-[#2563EB] text-xs font-black uppercase tracking-widest hover:bg-[#2563EB] hover:text-white transition-all shrink-0">
                                                <i class="fas fa-up-right-from-square"></i>
                                                {{ ($latestUpdate->media_type ?? 'image') === 'video' ? 'Lihat Video' : 'Lihat Foto' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if($olderUpdates->isNotEmpty())
                                    <details class="mt-4 group rounded-[1.75rem] border border-[#2563EB]/8 bg-white overflow-hidden">
                                        <summary class="list-none cursor-pointer px-5 py-4 flex items-center justify-between gap-4 hover:bg-[#F8FAFC] transition-all">
                                            <div>
                                                <p class="text-sm font-bold text-[#1E3A8A]">Update sebelumnya</p>
                                                <p class="text-xs text-[#1E3A8A]/45 font-semibold mt-1">{{ $olderUpdates->count() }} update lama disimpan di sini</p>
                                            </div>
                                            <span class="w-10 h-10 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center group-open:rotate-180 transition-transform">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </summary>

                                        <div class="border-t border-[#2563EB]/6 bg-[#FBFDFF] max-h-80 overflow-y-auto">
                                            @foreach($olderUpdates as $update)
                                                @php
                                                    $updateNote = $update->note ?: 'Creative worker belum menambahkan catatan progress.';
                                                @endphp
                                                <article class="px-5 py-4 border-b border-[#2563EB]/6 last:border-b-0">
                                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                                <p class="font-bold text-[#1E3A8A]">{{ $update->creative_name }}</p>
                                                                <span class="text-[11px] text-[#2563EB] font-black uppercase tracking-widest">{{ optional($update->created_at)->diffForHumans() ?? 'Baru saja' }}</span>
                                                            </div>
                                                            <p class="readable-text text-sm text-[#1E3A8A]/62 font-medium leading-6 line-clamp-2">{{ $updateNote }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-2 shrink-0">
                                                            <span class="px-3 py-1.5 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-widest">{{ $update->progress_percentage }}%</span>
                                                            @if(!empty($update->media_url))
                                                                <a href="{{ $update->media_url }}" target="_blank"
                                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-[#2563EB]/10 text-[#2563EB] hover:bg-[#2563EB] hover:text-white transition-all"
                                                                    title="{{ ($update->media_type ?? 'image') === 'video' ? 'Lihat Video' : 'Lihat Foto' }}">
                                                                    <i class="fas fa-up-right-from-square text-xs"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </details>
                                @endif
                            @else
                                <div class="rounded-2xl bg-[#F8FAFC] border border-dashed border-[#2563EB]/15 p-8 text-center">
                                    <p class="font-bold">Belum ada update progress</p>
                                    <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Update dari creative worker akan tampil sebagai ringkasan singkat di sini.</p>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </section>
        @empty
            <div class="bg-white rounded-[2rem] border border-[#2563EB]/8 shadow-sm p-12 text-center">
                <h2 class="font-display text-3xl font-bold mb-3">Belum Ada Proyek</h2>
                <p class="text-[#1E3A8A]/60 font-medium mb-6">Mulai publikasi proyek pertamamu, lalu pantau apply dan
                    progresnya dari halaman ini.</p>
                <a href="{{ route('projects.create') }}"
                    class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">Publikasikan
                    Proyek</a>
            </div>
        @endforelse

        <section class="pt-6">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-2">History</p>
                    <h2 class="font-display text-2xl font-bold">Proyek Selesai</h2>
                </div>
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[11px] font-black uppercase tracking-widest">
                    <i class="fas fa-box-archive"></i>
                    Arsip
                </span>
            </div>

            @forelse($historyProjects ?? collect() as $history)
                <article class="bg-white rounded-2xl border border-[#2563EB]/8 shadow-sm p-6 md:p-8 mb-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">
                        <div class="flex gap-4">
                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                                <img src="{{ $history->thumbnail ?? 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop' }}"
                                    alt="{{ $history->title }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-display text-xl md:text-2xl font-bold">{{ $history->title }}</h3>
                                <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2 leading-7">{{ $history->description }}
                                </p>
                                <div class="flex flex-wrap gap-3 mt-4 text-xs font-bold text-[#1E3A8A]/55">
                                    <span>{{ optional($history->archived_at)->translatedFormat('d M Y, H:i') ?? '-' }}</span>
                                    <span>Creative: {{ $history->selected_creative_name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="min-w-[220px] rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-2">Feedback UMKM
                            </p>
                            <div class="flex items-center gap-1 mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= (int) ($history->rating ?? 0) ? 'text-amber-400' : 'text-slate-200' }} fill-current"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.97a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 00-.364 1.118l1.287 3.97c.3.921-.755 1.688-1.54 1.118l-3.388-2.46a1 1 0 00-1.175 0l-3.388 2.46c-.784.57-1.838-.197-1.539-1.118l1.287-3.97a1 1 0 00-.364-1.118L2.245 9.397c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.97z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7">
                                {{ $history->comment ?: 'UMKM memberikan rating tanpa komentar.' }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-[2rem] border border-dashed border-[#2563EB]/10 p-12 text-center">
                    <h3 class="font-display text-2xl font-bold mb-2">Belum Ada History</h3>
                    <p class="text-[#1E3A8A]/60 font-medium">Proyek yang sudah selesai dan diberi rating akan otomatis
                        pindah ke sini.</p>
                </div>
            @endforelse
        </section>
    </main>

    <div id="dispute-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDisputeModal()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-full p-4">
            <div class="w-full max-w-xl bg-white rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-slate-100">
                    <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-5">
                        <i class="fas fa-scale-balanced text-2xl"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-2">Ajukan Dispute?</h3>
                    <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7">Gunakan dispute jika hasil/revisi proyek
                        <span id="dispute-project-name" class="font-bold text-[#1E3A8A]"></span> tidak sesuai dan belum
                        ada kesepakatan. Dana akan tetap ditahan sampai admin/mediator memutuskan.</p>
                </div>
                <form id="dispute-form" action="" method="POST" class="p-6 bg-[#F8FAFC] space-y-4">
                    @csrf
                    <textarea name="reason" rows="5" required minlength="20"
                        class="w-full px-5 py-4 rounded-2xl bg-white border border-red-200 focus:border-red-500 outline-none font-medium"
                        placeholder="Jelaskan masalahnya, revisi mana yang tidak sesuai, dan bukti yang perlu admin cek..."></textarea>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center rounded-2xl bg-red-600 px-6 py-4 text-white font-bold text-sm hover:bg-red-700 transition-all">Kirim
                            Dispute</button>
                        <button type="button" onclick="closeDisputeModal()"
                            class="flex-1 inline-flex justify-center rounded-2xl border border-slate-200 px-6 py-4 bg-white font-bold text-slate-600 hover:bg-slate-50 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="revision-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeRevisionModal()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-full p-4">
            <div class="w-full max-w-xl bg-white rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-slate-100">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                        <i class="fas fa-pen-to-square text-2xl"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-2">Minta Revisi</h3>
                    <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7">Tulis revisi yang dibutuhkan untuk <span
                            id="revision-project-name" class="font-bold text-[#1E3A8A]"></span>. Dana tetap ditahan di
                        platform selama revisi berjalan.</p>
                </div>
                <form id="revision-form" action="" method="POST" class="p-6 bg-[#F8FAFC] space-y-4">
                    @csrf
                    <textarea name="reason" rows="5" required minlength="10"
                        class="w-full px-5 py-4 rounded-2xl bg-white border border-blue-200 focus:border-blue-500 outline-none font-medium"
                        placeholder="Contoh: warna belum sesuai brand, layout bagian katalog perlu dirapikan..."></textarea>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center rounded-2xl bg-blue-600 px-6 py-4 text-white font-bold text-sm hover:bg-blue-700 transition-all">Kirim
                            Revisi</button>
                        <button type="button" onclick="closeRevisionModal()"
                            class="flex-1 inline-flex justify-center rounded-2xl border border-slate-200 px-6 py-4 bg-white font-bold text-slate-600 hover:bg-slate-50 transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-[120] hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="relative z-10 flex items-center justify-center min-h-full p-4">
            <div class="w-full max-w-lg bg-white rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-slate-100">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                        <i class="fas fa-triangle-exclamation text-2xl"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-2">Hapus Proyek?</h3>
                    <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7">Kamu akan menghapus proyek <span
                            id="project-name-modal" class="font-bold text-[#1E3A8A]"></span>. Karena belum ada apply
                        masuk, proyek akan benar-benar hilang dari daftar aktif.</p>
                </div>
                <div class="p-6 bg-[#F8FAFC] flex flex-col sm:flex-row gap-3">
                    <form id="delete-form" action="" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-2xl bg-amber-600 px-6 py-4 text-white font-bold text-sm hover:bg-amber-700 transition-all">Ya,
                            Hapus Proyek</button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 inline-flex justify-center rounded-2xl border border-slate-200 px-6 py-4 bg-white font-bold text-slate-600 hover:bg-slate-50 transition-all">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleReadableText(targetId, button) {
            const wrapper = document.getElementById(targetId);
            const shortText = wrapper?.querySelector('[data-readable-short]');
            const fullText = wrapper?.querySelector('[data-readable-full]');

            if (!wrapper || !shortText || !fullText || !button) {
                return;
            }

            const isExpanded = !fullText.classList.contains('hidden');
            shortText.classList.toggle('hidden', !isExpanded);
            fullText.classList.toggle('hidden', isExpanded);
            button.textContent = isExpanded ? button.dataset.showLabel : button.dataset.hideLabel;
        }

        function setRating(projectId, value) {
            document.getElementById('rating_input_' + projectId).value = value;
            const stars = document.querySelectorAll('.rating-star-' + projectId);
            stars.forEach((star, index) => {
                star.classList.toggle('text-amber-400', index < value);
                star.classList.toggle('text-slate-300', index >= value);
            });
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete-form').action = "{{ url('/progress-proyek') }}/" + id;
            document.getElementById('project-name-modal').textContent = name;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function openDisputeModal(id, name) {
            document.getElementById('dispute-form').action = "{{ url('/progress-proyek') }}/" + id + "/dispute";
            document.getElementById('dispute-project-name').textContent = name || 'ini';
            document.getElementById('dispute-modal').classList.remove('hidden');
        }

        function closeDisputeModal() {
            document.getElementById('dispute-modal').classList.add('hidden');
            document.getElementById('dispute-form')?.reset();
        }

        function openRevisionModal(id, name) {
            document.getElementById('revision-form').action = "{{ url('/progress-proyek') }}/" + id + "/revision";
            document.getElementById('revision-project-name').textContent = name || 'proyek ini';
            document.getElementById('revision-modal').classList.remove('hidden');
        }

        function closeRevisionModal() {
            document.getElementById('revision-modal').classList.add('hidden');
            document.getElementById('revision-form')?.reset();
        }
    </script>
</body>

</html>
