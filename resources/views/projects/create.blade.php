<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Proyek - Konekin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">

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

        .form-panel {
            background: #FFFFFF;
            border: 1px solid rgba(37, 99, 235, 0.07);
            box-shadow: 0 22px 60px rgba(30, 58, 138, 0.06);
        }

        .form-field {
            background: #F8FAFC;
            border: 1px solid rgba(148, 163, 184, 0.16);
            color: #1E3A8A;
        }

        .form-field:focus {
            background: #FFFFFF;
            border-color: rgba(37, 99, 235, 0.72);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }
    </style>
</head>

<body class="antialiased text-[#1E3A8A]">

    <!-- Navbar -->
    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        @php
            $categories = ['Branding', 'Social Media', 'Web Dev', 'Videography', 'UI/UX Design', 'Illustration'];
        @endphp

        <div class="mb-10">
            <a href="{{ route('dashboard.umkm') }}"
                class="inline-flex items-center gap-2 text-[#2563EB] font-bold text-sm hover:gap-3 transition-all mb-7">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-8 items-end">
                <div>
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[11px] font-extrabold uppercase tracking-[0.18em] mb-5">Brief Proyek UMKM</span>
                    <h1 class="font-display text-4xl md:text-5xl font-bold text-[#1E3A8A] leading-tight mb-4">
                        Publikasikan proyek yang mudah dipahami kreator
                    </h1>
                    <p class="text-[#1E3A8A]/60 font-medium text-lg leading-8 max-w-3xl">
                        Susun kebutuhan, budget, deadline, dan referensi visual dalam satu brief yang rapi agar apply
                        yang masuk lebih relevan dengan bisnismu.
                    </p>
                </div>

                <div class="hidden lg:grid grid-cols-3 gap-3">
                    <div class="rounded-3xl bg-white border border-[#2563EB]/5 p-4 shadow-sm">
                        <p class="text-2xl font-display font-bold text-[#1E3A8A]">01</p>
                        <p class="text-xs font-bold text-[#1E3A8A]/55 mt-1">Detail inti</p>
                    </div>
                    <div class="rounded-3xl bg-white border border-[#2563EB]/5 p-4 shadow-sm">
                        <p class="text-2xl font-display font-bold text-[#1E3A8A]">02</p>
                        <p class="text-xs font-bold text-[#1E3A8A]/55 mt-1">Brief jelas</p>
                    </div>
                    <div class="rounded-3xl bg-white border border-[#2563EB]/5 p-4 shadow-sm">
                        <p class="text-2xl font-display font-bold text-[#1E3A8A]">03</p>
                        <p class="text-xs font-bold text-[#1E3A8A]/55 mt-1">Referensi</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-8 items-start">
            @csrf

            <div class="space-y-6">
                <section class="form-panel rounded-[2rem] p-6 md:p-8">
                    <div class="flex items-start gap-4 mb-7">
                        <div
                            class="shrink-0 w-11 h-11 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5h6m-6 4h6m-7 4h8M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Detail Inti</h2>
                            <p class="text-sm text-[#1E3A8A]/55 font-medium mt-1">Informasi utama yang pertama dilihat
                                creative worker.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label for="title"
                                class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Judul
                                Proyek</label>
                            <input type="text" id="title" name="title" required maxlength="255"
                                value="{{ old('title') }}" placeholder="Contoh: Redesain Logo UMKM Kuliner"
                                class="form-field w-full px-6 py-4 rounded-2xl outline-none transition-all font-semibold"
                                oninput="syncBriefPreview()">
                            @error('title') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label for="category"
                                    class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kategori</label>
                                <div class="relative">
                                    <select id="category" name="category" required
                                        class="form-field w-full px-6 py-4 pr-12 rounded-2xl outline-none transition-all font-semibold appearance-none"
                                        onchange="syncBriefPreview()">
                                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori
                                        </option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="pointer-events-none absolute right-5 top-1/2 -translate-y-1/2 w-5 h-5 text-[#1E3A8A]/35"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                @error('category') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-3">
                                <label for="budget"
                                    class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Estimasi
                                    Budget (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-extrabold text-[#2563EB]">Rp</span>
                                    <input type="text" id="budget" name="budget" required maxlength="100"
                                        value="{{ old('budget') }}" placeholder="2.500.000"
                                        class="form-field w-full pl-14 pr-6 py-4 rounded-2xl outline-none transition-all font-semibold"
                                        inputmode="numeric" oninput="formatBudgetInput(); syncBriefPreview();">
                                </div>
                                @error('budget') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="deadline"
                                class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Deadline
                                Pengerjaan</label>
                            <input type="date" id="deadline" name="deadline" required value="{{ old('deadline') }}"
                                min="{{ now()->toDateString() }}"
                                class="form-field w-full px-6 py-4 rounded-2xl outline-none transition-all font-semibold"
                                onchange="syncBriefPreview()">
                            @error('deadline') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="form-panel rounded-[2rem] p-6 md:p-8">
                    <div class="flex items-start gap-4 mb-7">
                        <div
                            class="shrink-0 w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Brief Pekerjaan</h2>
                            <p class="text-sm text-[#1E3A8A]/55 font-medium mt-1">Ceritakan konteks, output, dan
                                preferensi agar scope proyek lebih rapi.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <div class="flex items-end justify-between gap-4">
                                <label for="description"
                                    class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Deskripsi
                                    Proyek</label>
                                <span id="description-count"
                                    class="text-[11px] font-bold text-[#1E3A8A]/40">0/2000</span>
                            </div>
                            <textarea id="description" name="description" required maxlength="2000" rows="7"
                                placeholder="Contoh: Kami ingin memperbarui identitas visual brand kuliner rumahan agar terlihat lebih modern, hangat, dan mudah dipakai di kemasan serta Instagram."
                                class="form-field w-full px-6 py-4 rounded-2xl outline-none transition-all font-medium leading-7 resize-y"
                                oninput="syncBriefPreview()">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-end justify-between gap-4">
                                <label for="requirements"
                                    class="text-xs font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kebutuhan
                                    / Spesifikasi</label>
                                <span class="text-[11px] font-bold text-[#1E3A8A]/40">Opsional</span>
                            </div>
                            <textarea id="requirements" name="requirements" rows="4" maxlength="2000"
                                placeholder="Contoh: 3 alternatif logo, file PNG transparan, panduan warna, dan revisi maksimal 2 kali."
                                class="form-field w-full px-6 py-4 rounded-2xl outline-none transition-all font-medium leading-7 resize-y">{{ old('requirements') }}</textarea>
                            @error('requirements') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="form-panel rounded-[2rem] p-6 md:p-8">
                    <div class="flex items-start gap-4 mb-7">
                        <div
                            class="shrink-0 w-11 h-11 rounded-2xl bg-[#FFF7ED] text-[#EA580C] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-2xl font-bold text-[#1E3A8A]">Referensi Visual</h2>
                            <p class="text-sm text-[#1E3A8A]/55 font-medium mt-1">Tambahkan foto produk, moodboard, atau
                                video referensi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_260px] gap-5">
                        <label for="project_media"
                            class="relative flex items-center justify-center min-h-[280px] rounded-[2rem] border-2 border-dashed border-[#2563EB]/18 bg-[#F8FAFC] cursor-pointer hover:border-[#2563EB]/45 hover:bg-[#EFF6FF]/45 transition-all overflow-hidden">
                            <div id="media-placeholder" class="text-center px-6">
                                <div
                                    class="w-16 h-16 rounded-3xl bg-white text-[#2563EB] flex items-center justify-center mx-auto mb-5 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 2v10m0 0l-4-4m4 4l4-4" />
                                    </svg>
                                </div>
                                <p class="font-display text-xl font-bold text-[#1E3A8A]">Pilih media proyek</p>
                                <p class="text-sm text-[#1E3A8A]/55 font-medium mt-2 max-w-md mx-auto">JPG, PNG, MP4,
                                    MOV, atau WEBM maksimal 20MB.</p>
                            </div>
                            <img id="media-image-preview" class="hidden w-full h-full min-h-[280px] object-cover"
                                alt="Preview media">
                            <video id="media-video-preview" class="hidden w-full h-full min-h-[280px] object-cover"
                                controls></video>
                        </label>

                        <div class="space-y-4">
                            <input type="file" id="project_media" name="project_media" class="hidden"
                                accept="image/*,video/*" onchange="previewProjectMedia()">
                            <button type="button" onclick="document.getElementById('project_media').click()"
                                class="w-full inline-flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all shadow-lg shadow-[#1E3A8A]/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Pilih File
                            </button>
                            <button type="button" id="remove-media-button" onclick="removeProjectMedia()"
                                class="hidden w-full px-6 py-4 rounded-2xl bg-white border border-red-100 text-red-500 font-bold text-sm hover:bg-red-50 transition-all">
                                Hapus Media
                            </button>
                            <div class="rounded-[1.5rem] bg-[#F8FAFC] border border-[#2563EB]/8 p-5">
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">
                                    File terpilih</p>
                                <p id="media-file-name" class="text-sm text-[#1E3A8A]/55 font-bold leading-6">Belum ada
                                    file</p>
                            </div>
                            @error('project_media') <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="form-panel rounded-[2rem] p-6">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <h2 class="font-display text-xl font-bold text-[#1E3A8A]">Ringkasan Brief</h2>
                        <span class="px-3 py-1 rounded-full bg-[#EFF6FF] text-[#2563EB] text-[10px] font-black uppercase tracking-wider">Preview</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-[#1E3A8A]/35">Judul</p>
                            <p id="summary-title" class="font-display font-bold text-[#1E3A8A] mt-1 leading-6">Belum
                                diisi</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-[#F8FAFC] p-4">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#1E3A8A]/35">Kategori</p>
                                <p id="summary-category" class="text-sm font-bold text-[#1E3A8A] mt-1">-</p>
                            </div>
                            <div class="rounded-2xl bg-[#F8FAFC] p-4">
                                <p class="text-[10px] font-black uppercase tracking-wider text-[#1E3A8A]/35">Deadline</p>
                                <p id="summary-deadline" class="text-sm font-bold text-[#1E3A8A] mt-1">-</p>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-[#F8FAFC] p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-[#1E3A8A]/35">Budget</p>
                            <p id="summary-budget" class="text-lg font-display font-bold text-[#1E3A8A] mt-1">-</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-3 py-5 bg-[#2563EB] text-white rounded-2xl font-bold text-base hover:bg-[#1E3A8A] transition-all shadow-xl shadow-[#2563EB]/20 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    Publikasikan Proyek
                </button>

                <div class="rounded-[2rem] bg-[#1E3A8A] text-white p-6 shadow-xl shadow-[#1E3A8A]/15">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-xl font-bold mb-2">Mau lebih presisi?</h3>
                    <p class="text-sm text-white/70 font-medium leading-7 mb-5">Setelah proyek dibuat, kamu bisa
                        mencocokkan kebutuhan UMKM dengan kreator lewat rekomendasi AI.</p>
                    <a href="{{ route('rekomendasi.kreator') }}"
                        class="inline-flex items-center justify-center w-full px-5 py-3 rounded-2xl bg-white text-[#1E3A8A] font-bold text-sm hover:bg-[#EFF6FF] transition-all">
                        Buka Rekomendasi AI
                    </a>
                </div>
            </aside>
        </form>

    </main>

    <!-- Footer Simple -->
    <footer
        class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Berdayakan UMKM Indonesia.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Kebijakan</a>
        </div>
    </footer>

    <script>
        let activeMediaUrl = null;

        function formatBudgetInput() {
            const budgetInput = document.getElementById('budget');
            const digits = budgetInput.value.replace(/\D/g, '');

            budgetInput.value = digits ? Number(digits).toLocaleString('id-ID') : '';
        }

        function previewProjectMedia() {
            const fileInput = document.getElementById('project_media');
            const file = fileInput.files[0];
            const imagePreview = document.getElementById('media-image-preview');
            const videoPreview = document.getElementById('media-video-preview');
            const placeholder = document.getElementById('media-placeholder');
            const fileName = document.getElementById('media-file-name');
            const removeButton = document.getElementById('remove-media-button');

            if (activeMediaUrl) {
                URL.revokeObjectURL(activeMediaUrl);
                activeMediaUrl = null;
            }

            imagePreview.classList.add('hidden');
            videoPreview.classList.add('hidden');
            imagePreview.removeAttribute('src');
            videoPreview.removeAttribute('src');

            if (!file) {
                placeholder.classList.remove('hidden');
                fileName.textContent = 'Belum ada file';
                removeButton.classList.add('hidden');
                return;
            }

            const fileUrl = URL.createObjectURL(file);
            activeMediaUrl = fileUrl;
            placeholder.classList.add('hidden');
            fileName.textContent = file.name;
            removeButton.classList.remove('hidden');

            if (file.type.startsWith('video/')) {
                videoPreview.src = fileUrl;
                videoPreview.classList.remove('hidden');
            } else {
                imagePreview.src = fileUrl;
                imagePreview.classList.remove('hidden');
            }
        }

        function removeProjectMedia() {
            const fileInput = document.getElementById('project_media');
            fileInput.value = '';
            previewProjectMedia();
        }

        function syncBriefPreview() {
            const title = document.getElementById('title').value.trim();
            const category = document.getElementById('category').value;
            const budget = document.getElementById('budget').value.trim();
            const deadline = document.getElementById('deadline').value;
            const description = document.getElementById('description');

            document.getElementById('summary-title').textContent = title || 'Belum diisi';
            document.getElementById('summary-category').textContent = category || '-';
            document.getElementById('summary-budget').textContent = budget ? `Rp ${budget}` : '-';
            document.getElementById('summary-deadline').textContent = deadline ? new Date(`${deadline}T00:00:00`).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            }) : '-';

            document.getElementById('description-count').textContent = `${description.value.length}/2000`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            formatBudgetInput();
            syncBriefPreview();
        });
    </script>

</body>

</html>
