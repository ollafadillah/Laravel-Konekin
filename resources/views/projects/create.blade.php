<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Proyek - Konekin</title>
    
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
    
    <!-- Navbar -->
    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('dashboard.umkm') }}" class="inline-flex items-center gap-2 text-[#2563EB] font-bold text-sm hover:gap-3 transition-all mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l(7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
            <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-4">Publikasikan Proyek Baru</h1>
            <p class="text-[#1E3A8A]/60 font-medium text-lg">Bagikan ide atau kebutuhan bisnismu dan biarkan talenta terbaik menemukannya.</p>
        </div>

        <!-- Form Card -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-[3rem] p-8 md:p-12 border border-[#2563EB]/5 shadow-2xl shadow-[#2563EB]/5">
            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Project Title -->
                <div class="space-y-3">
                    <label for="title" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Judul Proyek</label>
                    <input type="text" id="title" name="title" required value="{{ old('title') }}" placeholder="Contoh: Redesain Logo UMKM Kuliner" 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">
                    @error('title') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Category -->
                    <div class="space-y-3">
                        <label for="category" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kategori</label>
                        <select id="category" name="category" required 
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium appearance-none">
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori</option>
                            <option value="Branding" {{ old('category') === 'Branding' ? 'selected' : '' }}>Branding</option>
                            <option value="Social Media" {{ old('category') === 'Social Media' ? 'selected' : '' }}>Social Media</option>
                            <option value="Web Dev" {{ old('category') === 'Web Dev' ? 'selected' : '' }}>Web Development</option>
                            <option value="Videography" {{ old('category') === 'Videography' ? 'selected' : '' }}>Videography</option>
                            <option value="UI/UX Design" {{ old('category') === 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                            <option value="Illustration" {{ old('category') === 'Illustration' ? 'selected' : '' }}>Illustration</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Budget -->
                    <div class="space-y-3">
                        <label for="budget" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Estimasi Budget (Rp)</label>
                        <input type="text" id="budget" name="budget" required value="{{ old('budget') }}" placeholder="Contoh: 2.500.000" 
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">
                        @error('budget') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="deadline" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Deadline Pengerjaan</label>
                    <input type="date" id="deadline" name="deadline" required value="{{ old('deadline') }}"
                           min="{{ now()->toDateString() }}"
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">
                    <p class="text-sm text-[#1E3A8A]/60 font-medium">Tanggal ini akan terlihat oleh creative worker sebagai batas pengumpulan/pengerjaan proyek.</p>
                    @error('deadline') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label for="description" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Deskripsi Proyek</label>
                    <textarea id="description" name="description" required rows="5" placeholder="Jelaskan secara detail mengenai proyek yang ingin Anda buat..." 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                </div>

                <!-- Requirements -->
                <div class="space-y-3">
                    <label for="requirements" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Kebutuhan / Spesifikasi (Opsional)</label>
                    <textarea id="requirements" name="requirements" rows="3" placeholder="Contoh: Harus berpengalaman di industri F&B, paham gaya minimalis, dll." 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-100 focus:bg-white focus:border-[#2563EB] outline-none transition-all font-medium">{{ old('requirements') }}</textarea>
                    @error('requirements') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <label for="project_media" class="text-sm font-extrabold text-[#1E3A8A] uppercase tracking-wider ml-1">Upload Foto / Video (Opsional)</label>
                            <p class="text-sm text-[#1E3A8A]/60 font-medium mt-2">Tambahkan referensi visual supaya creative worker lebih cepat memahami brief. JPG, PNG, MP4, MOV, WEBM maksimal 20MB.</p>
                        </div>
                    </div>

                    <div class="rounded-[2.5rem] border border-dashed border-[#2563EB]/20 bg-[#F8FAFC] p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-[1fr_0.8fr] gap-6 items-center">
                            <label for="project_media" class="flex items-center justify-center min-h-[220px] rounded-[2rem] bg-white border border-[#2563EB]/10 cursor-pointer hover:border-[#2563EB]/25 transition-all overflow-hidden">
                                <div id="media-placeholder" class="text-center px-6">
                                    <div class="w-14 h-14 rounded-2xl bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 2v10m0 0l-4-4m4 4l4-4" /></svg>
                                    </div>
                                    <p class="font-bold text-[#1E3A8A]">Klik untuk pilih media proyek</p>
                                    <p class="text-sm text-[#1E3A8A]/55 font-medium mt-2">Bisa berupa foto produk, brief visual, atau video referensi.</p>
                                </div>
                                <img id="media-image-preview" class="hidden w-full h-full object-cover" alt="Preview media">
                                <video id="media-video-preview" class="hidden w-full h-full object-cover" controls></video>
                            </label>

                            <div class="space-y-4">
                                <input type="file" id="project_media" name="project_media" class="hidden" accept="image/*,video/*" onchange="previewProjectMedia()">
                                <button type="button" onclick="document.getElementById('project_media').click()" class="w-full px-6 py-4 rounded-2xl bg-[#1E3A8A] text-white font-bold text-sm hover:bg-[#2563EB] transition-all">
                                    Pilih File Referensi
                                </button>
                                <div class="rounded-[1.8rem] bg-white border border-[#2563EB]/10 p-5">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#2563EB] mb-2">Kenapa ini penting?</p>
                                    <p class="text-sm text-[#1E3A8A]/65 leading-7 font-medium">Media referensi membantu creative worker melihat vibe, kebutuhan, dan konteks proyek sejak awal. Ini opsional, tapi sangat membantu menaikkan kualitas apply yang masuk.</p>
                                </div>
                                @error('project_media') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips / Notice -->
                <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100/50 flex gap-4">
                    <div class="shrink-0 w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#2563EB] shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-sm text-[#1E3A8A]/80 font-medium leading-relaxed">
                        <span class="font-bold text-[#2563EB]">Tips:</span> Berikan deskripsi yang jelas dan anggaran yang realistis untuk mendapatkan tawaran terbaik dari para profesional kreatif kami.
                    </p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-5 bg-[#1E3A8A] text-white rounded-2xl font-bold text-lg hover:bg-[#2563EB] transition-all shadow-xl shadow-[#1E3A8A]/10 active:scale-[0.98]">
                    Publikasikan Proyek Sekarang
                </button>
            </form>
        </div>

    </main>

    <!-- Footer Simple -->
    <footer class="py-10 border-t border-[#2563EB]/10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[#1E3A8A]/40 text-sm font-bold">&copy; 2026 Konekin. Berdayakan UMKM Indonesia.</p>
        <div class="flex gap-8">
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Bantuan</a>
            <a href="#" class="text-[#1E3A8A]/40 hover:text-[#2563EB] text-sm font-bold transition-colors">Kebijakan</a>
        </div>
    </footer>

    <script>
        function previewProjectMedia() {
            const fileInput = document.getElementById('project_media');
            const file = fileInput.files[0];
            const imagePreview = document.getElementById('media-image-preview');
            const videoPreview = document.getElementById('media-video-preview');
            const placeholder = document.getElementById('media-placeholder');

            imagePreview.classList.add('hidden');
            videoPreview.classList.add('hidden');
            imagePreview.removeAttribute('src');
            videoPreview.removeAttribute('src');

            if (!file) {
                placeholder.classList.remove('hidden');
                return;
            }

            const fileUrl = URL.createObjectURL(file);
            placeholder.classList.add('hidden');

            if (file.type.startsWith('video/')) {
                videoPreview.src = fileUrl;
                videoPreview.classList.remove('hidden');
            } else {
                imagePreview.src = fileUrl;
                imagePreview.classList.remove('hidden');
            }
        }
    </script>

</body>
</html>
