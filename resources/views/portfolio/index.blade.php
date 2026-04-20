<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Saya - Konekin</title>
    
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
    <x-dashboard-nav />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <h1 class="font-display text-4xl font-bold text-[#1E3A8A] mb-2">Portfolio Saya</h1>
                <p class="text-[#1E3A8A]/60 font-medium">Pamerkan karya terbaikmu (Gambar, PDF, Video) untuk menarik perhatian UMKM.</p>
            </div>
            <button onclick="document.getElementById('upload-form').classList.toggle('hidden')" class="px-8 py-4 bg-[#2563EB] text-white rounded-2xl font-bold text-base shadow-xl shadow-[#2563EB]/20 hover:bg-[#1E3A8A] hover:-translate-y-1 transition-all flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Karya Baru
            </button>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
                <ul class="list-disc list-inside text-sm font-bold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Upload Form Section -->
        <div id="upload-form" class="hidden mb-12 bg-white p-8 rounded-[3rem] border-2 border-[#2563EB]/10 shadow-xl shadow-[#2563EB]/5 animate-fade-in-up">
            <h2 class="font-display text-2xl font-bold text-[#1E3A8A] mb-6">Upload Karya Baru</h2>
            <form action="{{ route('portfolio.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Judul Karya</label>
                        <input type="text" name="title" required class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Contoh: Branding Logo Kopi Kita">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Deskripsi Karya</label>
                        <textarea name="description" rows="4" required class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] resize-none" placeholder="Ceritakan tentang proses pembuatan atau hasil dari karya ini..."></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">File Tambahan (PDF, Video, dll - Max 20MB)</label>
                        <div class="relative group">
                            <input type="file" name="attachment" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] outline-none transition-all font-medium text-[#1E3A8A] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#EFF6FF] file:text-[#2563EB] hover:file:bg-[#2563EB] hover:file:text-white">
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Gambar Preview (Wajib)</label>
                        <div class="relative group h-[218px]">
                            <div id="image-placeholder" class="w-full h-full rounded-2xl border-2 border-dashed border-[#2563EB]/20 bg-[#F8FAFC] flex flex-col items-center justify-center cursor-pointer hover:bg-[#EFF6FF] transition-all">
                                <svg class="w-10 h-10 text-[#2563EB]/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-xs font-bold text-[#2563EB]/50 uppercase tracking-widest">Pilih Gambar Preview</span>
                            </div>
                            <img id="preview-img" class="hidden w-full h-full object-cover rounded-2xl border-2 border-[#2563EB]/10">
                            <input type="file" name="image" id="portfolio-img" required class="hidden" accept="image/*" onchange="previewFile()">
                            <button type="button" onclick="document.getElementById('portfolio-img').click()" class="absolute inset-0 w-full h-full"></button>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-2">
                        <button type="submit" class="flex-grow py-4 bg-[#1E3A8A] text-white rounded-2xl font-bold hover:bg-[#2563EB] transition-all shadow-lg shadow-[#1E3A8A]/10">Simpan Karya</button>
                        <button type="button" onclick="document.getElementById('upload-form').classList.add('hidden')" class="px-8 py-4 bg-[#F8FAFC] text-[#1E3A8A]/60 rounded-2xl font-bold border border-[#2563EB]/10 hover:bg-[#EFF6FF] transition-all">Batal</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($portfolios as $portfolio)
                <div class="bg-white p-6 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all group flex flex-col h-full relative">
                    <!-- Delete Button -->
                    <form action="{{ route('portfolio.destroy', $portfolio->id) }}" method="POST" class="absolute top-8 right-8 z-20 opacity-0 group-hover:opacity-100 transition-all">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus karya ini?')" class="p-3 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>

                    <div class="relative h-64 rounded-[2rem] overflow-hidden mb-6">
                        <img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @if($portfolio->file_url)
                            <div class="absolute bottom-4 right-4">
                                <a href="{{ $portfolio->file_url }}" target="_blank" class="p-3 bg-white/90 backdrop-blur-md text-[#2563EB] rounded-xl shadow-lg flex items-center gap-2 hover:bg-[#2563EB] hover:text-white transition-all">
                                    @if(in_array($portfolio->file_type, ['mp4', 'mov']))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    @endif
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest">{{ $portfolio->file_type }}</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow px-2">
                        <h3 class="text-xl font-display font-bold text-[#1E3A8A] mb-3 leading-tight">{{ $portfolio->title }}</h3>
                        <p class="text-[#1E3A8A]/60 text-sm font-medium leading-relaxed line-clamp-3">{{ $portfolio->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="mb-4 inline-block p-6 bg-white rounded-full shadow-sm">
                        <svg class="w-12 h-12 text-[#1E3A8A]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Belum Ada Portofolio</h3>
                    <p class="text-[#1E3A8A]/60 font-medium mt-2">Mulai upload karya terbaikmu sekarang!</p>
                </div>
            @endforelse
        </div>

    </main>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview-img');
            const placeholder = document.getElementById('image-placeholder');
            const file = document.getElementById('portfolio-img').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
