<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekomendasi Kreator AI - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.12), transparent 28%),
                radial-gradient(circle at top right, rgba(124, 58, 237, 0.10), transparent 26%),
                #F8FAFC;
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.36);
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <x-dashboard-nav-umkm />

    @php
        $formOptions = $formOptions ?? [];
        $jenisUsahaOptions = $formOptions['jenis_usaha'] ?? [];
        $marketplaceOptions = $formOptions['marketplace'] ?? [];
        $statusLegalitasOptions = $formOptions['status_legalitas'] ?? [];
        $experienceLevels = $formOptions['experience_levels'] ?? [];
        $topNOptions = $formOptions['top_n'] ?? [];
        $recommendationResult = $recommendationResult ?? session('recommendation_result');
        $serviceStatus = $serviceStatus ?? ['available' => false, 'models_loaded' => false, 'artifacts_ready' => false, 'message' => 'Flask ML service belum aktif.'];
        $formValues = $formValues ?? [];
        $projects = $projects ?? collect([]);
    @endphp

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-10">
            <a href="{{ route('dashboard.umkm') }}" class="inline-flex items-center gap-2 text-[#2563EB] font-bold text-sm hover:gap-3 transition-all mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
                <div class="max-w-3xl">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.24em] text-[#2563EB] mb-4">Flask ML Recommendation</p>
                    <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] mb-4 leading-tight">
                        Rekomendasi Kreator AI untuk UMKM Kamu
                    </h1>
                    <p class="text-[#1E3A8A]/65 font-medium text-lg leading-relaxed">
                        Masukkan data UMKM, lalu ambil daftar creative worker yang paling cocok berdasarkan cluster dan kesamaan skill.
                    </p>
                </div>

                <div class="glass rounded-[2rem] px-5 py-4 shadow-xl shadow-[#2563EB]/10 border border-white/70 w-full lg:w-auto">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full {{ $serviceStatus['available'] ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#2563EB]">Status Flask</p>
                            <p class="text-sm font-bold text-[#1E3A8A]">{{ $serviceStatus['available'] ? 'Terhubung' : 'Offline' }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-[#1E3A8A]/60 font-medium mt-2">
                        {{ $serviceStatus['message'] }}
                    </p>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest {{ $serviceStatus['models_loaded'] ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $serviceStatus['models_loaded'] ? 'Model loaded' : 'Model belum load' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest {{ $serviceStatus['artifacts_ready'] ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $serviceStatus['artifacts_ready'] ? 'Artifact ready' : 'Artifact belum lengkap' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-8">
            <section class="bg-white rounded-[3rem] p-6 md:p-10 border border-[#2563EB]/5 shadow-2xl shadow-[#2563EB]/5">
                <div class="flex items-start justify-between gap-4 mb-8">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-2">Data UMKM</p>
                        <h2 class="font-display text-2xl md:text-3xl font-bold text-[#1E3A8A]">Isi parameter untuk analisis model</h2>
                        <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2 leading-7">
                            Field di bawah ini disesuaikan dengan input yang dipakai model KMeans + TF-IDF.
                        </p>
                    </div>

                    <div class="hidden md:block text-right">
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-[#1E3A8A]/40">Akun Terhubung</p>
                        <p class="font-bold text-[#1E3A8A]">{{ $user->name }}</p>
                        <p class="text-xs text-[#1E3A8A]/55">{{ $user->city ?: 'Lokasi belum diatur' }}</p>
                    </div>
                </div>

                <form action="{{ route('rekomendasi.kreator.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="omset">Omset</label>
                            <input type="text" id="omset" name="omset" inputmode="numeric" data-format-money
                                   value="{{ old('omset', $formValues['omset']) }}"
                                   placeholder="50000000"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('omset') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="laba">Laba</label>
                            <input type="text" id="laba" name="laba" inputmode="numeric" data-format-money
                                   value="{{ old('laba', $formValues['laba']) }}"
                                   placeholder="10000000"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('laba') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="aset">Aset</label>
                            <input type="text" id="aset" name="aset" inputmode="numeric" data-format-money
                                   value="{{ old('aset', $formValues['aset']) }}"
                                   placeholder="20000000"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('aset') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="tahun_berdiri">Tahun Berdiri</label>
                            <input type="text" id="tahun_berdiri" name="tahun_berdiri" inputmode="numeric"
                                   value="{{ old('tahun_berdiri', $formValues['tahun_berdiri']) }}"
                                   placeholder="2018"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('tahun_berdiri') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="jenis_usaha">Jenis Usaha</label>
                            <select id="jenis_usaha" name="jenis_usaha" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] appearance-none">
                                @foreach($jenisUsahaOptions as $option)
                                    <option value="{{ $option }}" {{ old('jenis_usaha', $formValues['jenis_usaha']) === $option ? 'selected' : '' }}>
                                        {{ $option === 'unknown' ? 'Lainnya / Unknown' : $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_usaha') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="marketplace">Marketplace</label>
                            <select id="marketplace" name="marketplace" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] appearance-none">
                                @foreach($marketplaceOptions as $option)
                                    <option value="{{ $option }}" {{ old('marketplace', $formValues['marketplace']) === $option ? 'selected' : '' }}>
                                        {{ $option === 'unknown' ? 'Lainnya / Unknown' : $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('marketplace') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="status_legalitas">Status Legalitas</label>
                            <select id="status_legalitas" name="status_legalitas" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] appearance-none">
                                @foreach($statusLegalitasOptions as $option)
                                    <option value="{{ $option }}" {{ old('status_legalitas', $formValues['status_legalitas']) === $option ? 'selected' : '' }}>
                                        {{ $option === 'unknown' ? 'Lainnya / Unknown' : $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_legalitas') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="tenaga_kerja_perempuan">Tenaga Kerja Perempuan</label>
                            <input type="text" id="tenaga_kerja_perempuan" name="tenaga_kerja_perempuan" inputmode="numeric"
                                   value="{{ old('tenaga_kerja_perempuan', $formValues['tenaga_kerja_perempuan']) }}"
                                   placeholder="3"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('tenaga_kerja_perempuan') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="tenaga_kerja_laki_laki">Tenaga Kerja Laki-laki</label>
                            <input type="text" id="tenaga_kerja_laki_laki" name="tenaga_kerja_laki_laki" inputmode="numeric"
                                   value="{{ old('tenaga_kerja_laki_laki', $formValues['tenaga_kerja_laki_laki']) }}"
                                   placeholder="2"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            @error('tenaga_kerja_laki_laki') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="top_n">Jumlah Hasil</label>
                            <select id="top_n" name="top_n" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] appearance-none">
                                @foreach($topNOptions as $option)
                                    <option value="{{ $option }}" {{ (int) old('top_n', $formValues['top_n']) === (int) $option ? 'selected' : '' }}>
                                        Top {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            @error('top_n') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="experience_level">Filter Level Pengalaman</label>
                            <select id="experience_level" name="experience_level" class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] appearance-none">
                                <option value="" {{ blank(old('experience_level', $formValues['experience_level'])) ? 'selected' : '' }}>Semua tingkat</option>
                                @foreach($experienceLevels as $option)
                                    <option value="{{ $option }}" {{ old('experience_level', $formValues['experience_level']) === $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-[#1E3A8A]/55 font-medium ml-1">Opsional, kalau ingin menyaring kreator berdasarkan senioritas.</p>
                            @error('experience_level') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1" for="min_budget">Budget Minimum Kreator</label>
                            <input type="text" id="min_budget" name="min_budget" inputmode="numeric" data-format-money
                                   value="{{ old('min_budget', $formValues['min_budget']) }}"
                                   placeholder="3000000"
                                   class="w-full px-5 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]">
                            <p class="text-xs text-[#1E3A8A]/55 font-medium ml-1">Opsional. Kosongkan jika tidak ingin memberi filter budget.</p>
                            @error('min_budget') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="p-5 bg-[#EFF6FF] rounded-[2rem] border border-[#2563EB]/10">
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">Catatan Input</p>
                        <p class="text-sm text-[#1E3A8A]/70 font-medium leading-7">
                            Kamu boleh mengetik angka pakai pemisah ribuan. Sistem akan membersihkannya otomatis sebelum dikirim ke Flask ML service.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-5 bg-[#1E3A8A] text-white rounded-2xl font-bold text-lg hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10 active:scale-[0.98]">
                        Jalankan Rekomendasi
                    </button>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="bg-white rounded-[2.5rem] p-6 border border-[#2563EB]/5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden shrink-0 border-2 border-white shadow-lg shadow-[#2563EB]/10">
                            <img src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=2563EB&color=fff' }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-2">Profil UMKM</p>
                            <h3 class="text-xl font-display font-bold text-[#1E3A8A]">{{ $user->name }}</h3>
                            <p class="text-sm text-[#1E3A8A]/60 font-medium mt-1">{{ $user->city ?: 'Lokasi belum diatur' }}</p>
                        </div>
                    </div>
                    <div class="mt-5 rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/10 p-4">
                        <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[#2563EB] mb-2">Bio / Deskripsi</p>
                        <p class="text-sm text-[#1E3A8A]/65 font-medium leading-7">
                            {{ $user->bio ?: 'Lengkapi bio usaha di profil agar kreator lebih mudah memahami brand kamu.' }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-6 border border-[#2563EB]/5 shadow-sm">
                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-3">Cara Kerja</p>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 font-black text-xs">1</div>
                            <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">Masukkan data bisnis yang relevan dengan kondisi UMKM kamu.</p>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 font-black text-xs">2</div>
                            <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">Laravel mengirim payload ke Flask ML service untuk prediksi cluster.</p>
                        </div>
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 font-black text-xs">3</div>
                            <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">Hasil rekomendasi creative worker langsung tampil dan bisa dibuka profilnya.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#1E3A8A] to-[#2563EB] rounded-[2.5rem] p-6 text-white shadow-2xl shadow-[#2563EB]/15">
                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/70 mb-3">Kenapa ini penting?</p>
                    <h3 class="font-display text-2xl font-bold mb-3">Lebih cepat menemukan kreator yang pas.</h3>
                    <p class="text-white/80 font-medium leading-7 text-sm">
                        Kamu tidak perlu menebak role mana yang cocok. Model machine learning membantu menyaring creative worker berdasarkan pola UMKM yang mirip.
                    </p>
                </div>
            </aside>
        </div>

        @if($recommendationResult)
            <section class="mt-12">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-8">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-2">Hasil Rekomendasi</p>
                        <h2 class="font-display text-3xl font-bold text-[#1E3A8A]">Cluster {{ data_get($recommendationResult, 'cluster') }} - {{ data_get($recommendationResult, 'cluster_label') }}</h2>
                        <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Total kandidat ditemukan: {{ data_get($recommendationResult, 'total_found', 0) }}</p>
                    </div>

                    <div class="glass rounded-[1.8rem] px-5 py-4 border border-white/70 shadow-lg shadow-[#2563EB]/10">
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2563EB] mb-2">Input Ringkas</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">Jenis: {{ data_get($recommendationResult, 'input_summary.jenis_usaha') }}</span>
                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">Marketplace: {{ data_get($recommendationResult, 'input_summary.marketplace') }}</span>
                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">Legal: {{ data_get($recommendationResult, 'input_summary.status_legalitas') }}</span>
                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">Top: {{ data_get($recommendationResult, 'input_summary.top_n') }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    @foreach(data_get($recommendationResult, 'recommendations', []) as $worker)
                        <article class="bg-white rounded-[2.75rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all overflow-hidden">
                            <div class="p-6 md:p-7">
                                <div class="flex flex-col md:flex-row md:items-center gap-5">
                                    <div class="w-20 h-20 rounded-[1.6rem] overflow-hidden shrink-0 border-2 border-white shadow-lg shadow-[#2563EB]/10">
                                        <img src="{{ $worker['profile_photo'] }}" alt="{{ $worker['full_name'] }}" class="w-full h-full object-cover">
                                    </div>

                                    <div class="flex-grow">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-extrabold uppercase tracking-widest">
                                                {{ $worker['display_role'] }}
                                            </span>
                                            <span class="px-3 py-1 rounded-full {{ $worker['profile_verified'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} text-[10px] font-extrabold uppercase tracking-widest">
                                                {{ $worker['verified_label'] }}
                                            </span>
                                            <span class="px-3 py-1 rounded-full bg-[#F5F3FF] text-[#7C3AED] text-[10px] font-extrabold uppercase tracking-widest">
                                                Match {{ $worker['match_percentage'] }}%
                                            </span>
                                        </div>

                                        <h3 class="text-2xl font-display font-bold text-[#1E3A8A] leading-tight">{{ $worker['full_name'] }}</h3>
                                        <p class="text-sm text-[#1E3A8A]/60 font-medium mt-1">
                                            {{ $worker['city'] ?: 'Lokasi tidak diatur' }}
                                            @if($worker['specific_role'])
                                                <span class="mx-2 text-[#1E3A8A]/25">•</span>
                                                {{ $worker['specific_role'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    @forelse($worker['skills'] as $skill)
                                        <span class="px-3 py-1.5 bg-slate-50 text-[#1E3A8A]/70 border border-slate-100 rounded-full text-[11px] font-bold">{{ $skill }}</span>
                                    @empty
                                        <span class="px-3 py-1.5 bg-slate-50 text-[#1E3A8A]/70 border border-slate-100 rounded-full text-[11px] font-bold">{{ $worker['display_role'] }}</span>
                                    @endforelse
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-1">Rating</p>
                                        <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ number_format((float) $worker['client_rating'], 1) }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-1">Project</p>
                                        <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ number_format((float) $worker['jobs_completed'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-1">Success</p>
                                        <p class="text-sm font-display font-bold text-[#1E3A8A]">{{ number_format((float) $worker['success_rate_job'], 1) }}%</p>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50 border border-slate-100 p-3">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-1">Budget</p>
                                        <p class="text-sm font-display font-bold text-[#1E3A8A]">Rp {{ number_format((float) $worker['min_budget_idr'], 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-[#1E3A8A]/40 mb-1">Profile</p>
                                        <p class="text-sm text-[#1E3A8A]/65 font-medium">
                                            {{ $worker['bio'] ?: 'Creative worker yang direkomendasikan oleh model machine learning.' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        @if($worker['profile_url'])
                                            <a href="{{ $worker['profile_url'] }}" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-slate-100 text-[#1E3A8A] font-bold text-sm hover:bg-slate-200 transition-all">
                                                Lihat Profil
                                            </a>
                                        @endif

                                        <button onclick="openHireModal('{{ $worker['id'] }}', '{{ $worker['full_name'] }}')" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all shadow-lg shadow-[#1E3A8A]/10">
                                            Hire Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @else
            <section class="mt-12 bg-white rounded-[3rem] border border-[#2563EB]/5 shadow-sm p-8 md:p-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="max-w-2xl">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-[#2563EB] mb-2">Belum Ada Hasil</p>
                        <h2 class="font-display text-2xl md:text-3xl font-bold text-[#1E3A8A]">Isi form di atas untuk mulai menghitung rekomendasi.</h2>
                        <p class="text-sm text-[#1E3A8A]/60 font-medium leading-7 mt-3">
                            Setelah data dikirim, Flask ML service akan mengembalikan cluster UMKM dan daftar kreator yang paling relevan.
                        </p>
                    </div>

                    <div class="rounded-[2rem] bg-[#EFF6FF] border border-[#2563EB]/10 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#2563EB] mb-2">Output yang akan muncul</p>
                        <ul class="space-y-2 text-sm text-[#1E3A8A]/70 font-medium">
                            <li>Cluster UMKM</li>
                            <li>Label cluster</li>
                            <li>Daftar creative worker teratas</li>
                            <li>Tautan ke profil kreator</li>
                        </ul>
                    </div>
                </div>
            </section>
        @endif
    </main>

    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Rekomendasi kreator berbasis AI.</p>
        <div class="flex gap-8">
            <a href="{{ route('dashboard.umkm') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Dashboard</a>
            <a href="{{ route('projects.create') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Upload Proyek</a>
            <a href="{{ route('kreator.index') }}" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Cari Kreator</a>
        </div>
    </footer>

    <!-- Hire Modal -->
    <div id="hireModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-[#1E3A8A]/40 backdrop-blur-sm" onclick="closeHireModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
            <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-[#2563EB]/10">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#2563EB] mb-2">Hiring Process</p>
                            <h3 class="text-2xl font-display font-bold text-[#1E3A8A]">Hire <span id="modalWorkerName"></span></h3>
                        </div>
                        <button onclick="closeHireModal()" class="text-slate-400 hover:text-[#1E3A8A] transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if($projects->isEmpty())
                        <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100 mb-6">
                            <p class="text-sm text-amber-700 font-medium leading-relaxed">
                                Kamu belum memiliki proyek yang aktif. Silakan buat proyek terlebih dahulu untuk bisa melakukan proses hiring.
                            </p>
                            <a href="{{ route('projects.create') }}" class="inline-block mt-4 text-[#2563EB] font-bold text-sm underline">Buat Proyek Sekarang</a>
                        </div>
                    @else
                        <form action="{{ route('rekomendasi.kreator.hire') }}" method="POST">
                            @csrf
                            <input type="hidden" name="creative_id" id="modalCreativeId">
                            
                            <div class="space-y-4 mb-8">
                                <label class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Pilih Proyek Kamu</label>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($projects as $project)
                                        <label class="relative flex items-center p-4 rounded-2xl border-2 border-slate-100 hover:border-[#2563EB]/30 cursor-pointer transition-all has-[:checked]:border-[#2563EB] has-[:checked]:bg-blue-50">
                                            <input type="radio" name="project_id" value="{{ $project->id }}" class="sr-only" required>
                                            <div class="flex-grow">
                                                <p class="font-bold text-[#1E3A8A]">{{ $project->title }}</p>
                                                <p class="text-xs text-[#1E3A8A]/60 font-medium">Budget: Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center ml-4 group-has-[:checked]:border-[#2563EB]">
                                                <div class="w-2.5 h-2.5 rounded-full bg-[#2563EB] scale-0 transition-transform duration-200"></div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-[#1E3A8A] text-white rounded-2xl font-bold hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10">
                                Konfirmasi & Lanjut ke Pembayaran
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatMoneyValue(value) {
            const digits = value.replace(/\D/g, '');

            if (!digits) {
                return '';
            }

            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.querySelectorAll('[data-format-money]').forEach((input) => {
            input.addEventListener('input', function () {
                this.value = formatMoneyValue(this.value);
            });
        });

        // Modal Functions
        function openHireModal(id, name) {
            document.getElementById('modalCreativeId').value = id;
            document.getElementById('modalWorkerName').innerText = name;
            document.getElementById('hireModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeHireModal() {
            document.getElementById('hireModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
