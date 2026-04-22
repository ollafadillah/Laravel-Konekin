@extends('layouts.app')

@section('content')
<div class="pt-32 pb-20 lg:pt-44 lg:pb-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto relative">
    
    <!-- Hero Section -->
    <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
        <div class="text-left flex flex-col items-start relative z-10">
            <!-- Creative Badge -->
            <div class="relative group cursor-pointer mb-8">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] rounded-full blur opacity-40 group-hover:opacity-75 transition duration-500"></div>
                <div class="relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-white border border-[#2563EB]/20 text-[#1E3A8A] font-bold text-xs tracking-widest uppercase shadow-sm">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-[#2563EB] animate-ping absolute"></span>
                    <span class="flex h-2.5 w-2.5 rounded-full bg-[#2563EB]"></span>
                    Platform Kolaborasi Masa Depan
                </div>
            </div>
            
            <h1 class="font-display text-5xl md:text-6xl lg:text-[4rem] font-bold text-[#1E3A8A] mb-6 leading-[1.05]">
                Hubungkan <br>
                <div class="relative inline-block mt-2">
                    <span class="relative z-10 text-white px-2">Kreativitas</span>
                    <span class="absolute bottom-1 left-0 w-full h-2/3 bg-[#2563EB] -rotate-2 scale-110 rounded-xl z-0"></span>
                </div> <br>
                dengan <span class="text-[#0A66C2] underline decoration-4 decoration-[#2563EB]/30 underline-offset-8">Peluang UMKM</span>
            </h1>
            
            <p class="text-lg md:text-xl text-[#1E3A8A]/70 mb-10 leading-relaxed max-w-lg font-medium">
                Ekosistem kolaboratif di mana talenta <strong>Creative Worker</strong> bertemu dengan <strong>UMKM</strong> idaman. Bangun <em>brand</em>, ciptakan karya, dan melesat bersama.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto">
                <a href="{{ route('kreator.index') }}" class="group relative inline-flex justify-center items-center px-8 py-4 bg-[#1E3A8A] text-white rounded-2xl font-bold text-base shadow-xl shadow-[#1E3A8A]/20 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#2563EB]/30 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <span class="relative z-10 flex items-center gap-2">
                        Mulai Kolaborasi
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </a>
                <a href="{{ route('about') }}" class="group inline-flex justify-center items-center px-8 py-4 bg-white hover:bg-[#EFF6FF] text-[#1E3A8A] border-2 border-[#2563EB]/20 hover:border-[#2563EB] rounded-2xl font-bold text-base shadow-sm transition-all duration-300 hover:-translate-y-1 gap-2">
                    <svg class="w-6 h-6 text-[#2563EB] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Cara Kerja
                </a>
            </div>
        </div>

        <div class="relative w-full aspect-square lg:aspect-[4/4] mt-10 lg:mt-0 z-10 p-4">
            <!-- Creative overlapping background blocks -->
            <div class="absolute inset-0 bg-[#2563EB] rounded-[3rem] rotate-6 opacity-20 transform scale-95 transition-transform duration-700 hover:rotate-12"></div>
            <div class="absolute inset-0 bg-[#0A66C2] rounded-[3rem] -rotate-3 opacity-30 transform scale-100 transition-transform duration-700 hover:-rotate-6"></div>
            
            <div class="relative w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl bg-white border-4 border-white/50 z-20 group">
                <div class="absolute inset-0 bg-gradient-to-t from-[#1E3A8A] to-transparent opacity-0 group-hover:opacity-40 transition-opacity duration-500 z-10"></div>
                <img src="{{ asset('kreatif.jpg') }}" alt="Ilustrasi Konekin" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="mt-32 relative z-10">
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-[#2563EB]/5 p-10 lg:p-14 border border-[#2563EB]/10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-10 text-center">
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#2563EB] mb-2">500+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Kreator Aktif</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#0A66C2] mb-2">1K+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">UMKM Terdaftar</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div class="relative">
                    <div class="font-display text-5xl font-bold text-[#1E3A8A] mb-2">800+</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Proyek Selesai</div>
                    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-px h-12 bg-[#2563EB]/20"></div>
                </div>
                <div>
                    <div class="font-display text-5xl font-bold text-[#2563EB] mb-2">99%</div>
                    <div class="text-[#1E3A8A] font-bold text-sm uppercase tracking-wider">Klien Puas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works Section -->
    <div class="mt-40 relative z-10">
        <div class="text-center mb-20">
            <h3 class="text-[#2563EB] font-black text-sm uppercase tracking-[0.3em] mb-4">Cara Kerja Platform</h3>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-[#1E3A8A] mb-6">Kolaborasi dalam 4 Langkah Mudah</h2>
            <p class="text-[#1E3A8A]/60 text-lg lg:text-xl font-medium max-w-2xl mx-auto leading-relaxed">
                Temukan cara Konekin menghubungkan UMKM dengan kreator profesional untuk hasil yang luar biasa.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="group relative bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/10 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#2563EB] rounded-t-[2.5rem] scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                <div class="w-16 h-16 bg-[#2563EB] rounded-full flex items-center justify-center text-white text-2xl font-bold mb-8 shadow-lg shadow-[#2563EB]/30 mx-auto">1</div>
                <h4 class="text-xl font-bold text-[#1E3A8A] mb-4 text-center">Daftar Akun</h4>
                <p class="text-[#1E3A8A]/60 text-center leading-relaxed font-medium">
                    Pilih peran Anda sebagai UMKM atau Kreator. Lengkapi profil untuk mendapatkan rekomendasi terbaik.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="group relative bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/10 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#2563EB] rounded-t-[2.5rem] scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                <div class="w-16 h-16 bg-[#2563EB] rounded-full flex items-center justify-center text-white text-2xl font-bold mb-8 shadow-lg shadow-[#2563EB]/30 mx-auto">2</div>
                <h4 class="text-xl font-bold text-[#1E3A8A] mb-4 text-center">Temukan Partner</h4>
                <p class="text-[#1E3A8A]/60 text-center leading-relaxed font-medium">
                    Cari dan temukan UMKM atau kreator yang sesuai dengan kebutuhan dan keahlian Anda.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="group relative bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/10 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#2563EB] rounded-t-[2.5rem] scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                <div class="w-16 h-16 bg-[#2563EB] rounded-full flex items-center justify-center text-white text-2xl font-bold mb-8 shadow-lg shadow-[#2563EB]/30 mx-auto">3</div>
                <h4 class="text-xl font-bold text-[#1E3A8A] mb-4 text-center">Mulai Kolaborasi</h4>
                <p class="text-[#1E3A8A]/60 text-center leading-relaxed font-medium">
                    Komunikasikan kebutuhan, buat perjanjian, dan mulai bekerja sama dalam platform yang aman.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="group relative bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/10 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-[#2563EB]/10 transition-all duration-500 hover:-translate-y-2">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#2563EB] rounded-t-[2.5rem] scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                <div class="w-16 h-16 bg-[#2563EB] rounded-full flex items-center justify-center text-white text-2xl font-bold mb-8 shadow-lg shadow-[#2563EB]/30 mx-auto">4</div>
                <h4 class="text-xl font-bold text-[#1E3A8A] mb-4 text-center">Hasil & Ulasan</h4>
                <p class="text-[#1E3A8A]/60 text-center leading-relaxed font-medium">
                    Selesaikan proyek, berikan ulasan, dan bangun portofolio yang semakin kuat.
                </p>
            </div>
        </div>
    </div>

    <!-- Video Tayangan Section -->
    <div class="mt-40 max-w-5xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-[#1E3A8A] mb-4">Kenali Konekin Lebih Dekat</h2>
            <p class="text-[#0A66C2] text-xl font-medium max-w-2xl mx-auto">Kami merancang platform kolaborasi tanpa batas untuk mempertemukan ide-ide brilian.</p>
        </div>
        
        <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl shadow-[#1E3A8A]/20 bg-white border-8 border-white group aspect-video">
            <iframe 
                src="https://drive.google.com/file/d/1PRoT6M42Hu33drAq0EQwrDo-awZ3MsC2/preview" 
                class="absolute inset-0 w-full h-full border-0 transform group-hover:scale-[1.01] transition-transform duration-500 rounded-3xl"
                allow="autoplay"
                allowfullscreen>
            </iframe>
        </div>
        
        <!-- Decorative blobs behind video -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#2563EB]/20 rounded-full blur-2xl -z-10"></div>
        <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-[#0A66C2]/20 rounded-full blur-3xl -z-10"></div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-40 relative z-10">
        <div class="text-center mb-16">
            <h3 class="text-[#2563EB] font-black text-sm uppercase tracking-[0.3em] mb-4">Frequently Asked <span class="font-latin text-[#2563EB]">Questions</span></h3>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-[#1E3A8A] mb-6">Pertanyaan yang Sering Ditanyakan</h2>
            <p class="text-[#1E3A8A]/60 text-lg lg:text-xl font-medium max-w-3xl mx-auto leading-relaxed">
                Beberapa hal yang paling sering ditanyakan pengguna sebelum mulai berkolaborasi di Konekin.
            </p>
        </div>

        <div class="grid lg:grid-cols-[0.9fr_1.1fr] gap-10 items-start">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#1E3A8A] via-[#2563EB] to-[#0A66C2] p-8 lg:p-10 text-white shadow-2xl shadow-[#2563EB]/20">
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff22_1px,transparent_1px)] bg-[size:1.3rem_1.3rem] opacity-30"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/15 border border-white/20 text-xs font-bold tracking-[0.2em] uppercase mb-6">
                        Tanya Jawab
                    </div>
                    <h3 class="font-display text-3xl lg:text-4xl font-bold leading-tight mb-5">
                        Mulai lebih tenang, paham alurnya lebih cepat.
                    </h3>
                    <p class="text-white/80 text-lg leading-relaxed font-medium mb-8">
                        Dari cara daftar, mencari partner, sampai bagaimana hasil karya tersimpan, semuanya kami rangkum di sini.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-white/10 border border-white/15 p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold">24/7</div>
                            <div class="text-sm text-white/75 mt-1">Akses platform kapan saja</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 border border-white/15 p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold">1 Klik</div>
                            <div class="text-sm text-white/75 mt-1">Mulai bangun profil profesional</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <details class="group rounded-[2rem] bg-white border border-[#2563EB]/10 shadow-xl shadow-slate-200/40 p-6 open:shadow-2xl open:shadow-[#2563EB]/10 transition-all duration-300" open>
                    <summary class="list-none cursor-pointer flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-[#1E3A8A]">Apa itu Konekin dan siapa yang bisa bergabung?</h4>
                            <p class="text-sm text-[#2563EB] font-semibold mt-2">Untuk creative worker dan pelaku UMKM</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 transition-transform duration-300 group-open:rotate-45">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                    </summary>
                    <p class="mt-5 text-[#1E3A8A]/70 leading-relaxed font-medium">
                        Konekin adalah platform yang mempertemukan creative worker dengan UMKM untuk membangun branding, konten, desain, dan kebutuhan kreatif lainnya dalam satu ekosistem kolaborasi.
                    </p>
                </details>

                <details class="group rounded-[2rem] bg-white border border-[#2563EB]/10 shadow-xl shadow-slate-200/40 p-6 open:shadow-2xl open:shadow-[#2563EB]/10 transition-all duration-300">
                    <summary class="list-none cursor-pointer flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-[#1E3A8A]">Bagaimana cara mulai kolaborasi di Konekin?</h4>
                            <p class="text-sm text-[#2563EB] font-semibold mt-2">Daftar, lengkapi profil, lalu temukan partner</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 transition-transform duration-300 group-open:rotate-45">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                    </summary>
                    <p class="mt-5 text-[#1E3A8A]/70 leading-relaxed font-medium">
                        Kamu cukup membuat akun sesuai peranmu, melengkapi profil, lalu menjelajahi partner yang relevan. Setelah itu, komunikasi dan proses kerja sama bisa dimulai langsung melalui alur platform.
                    </p>
                </details>

                <details class="group rounded-[2rem] bg-white border border-[#2563EB]/10 shadow-xl shadow-slate-200/40 p-6 open:shadow-2xl open:shadow-[#2563EB]/10 transition-all duration-300">
                    <summary class="list-none cursor-pointer flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-[#1E3A8A]">Apakah saya bisa menampilkan portofolio atau hasil karya?</h4>
                            <p class="text-sm text-[#2563EB] font-semibold mt-2">Bisa, agar profil terlihat lebih meyakinkan</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 transition-transform duration-300 group-open:rotate-45">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                    </summary>
                    <p class="mt-5 text-[#1E3A8A]/70 leading-relaxed font-medium">
                        Tentu. Creative worker bisa menampilkan portofolio, karya visual, atau file pendukung agar UMKM lebih mudah memahami kualitas dan gaya kerja yang kamu tawarkan.
                    </p>
                </details>

                <details class="group rounded-[2rem] bg-white border border-[#2563EB]/10 shadow-xl shadow-slate-200/40 p-6 open:shadow-2xl open:shadow-[#2563EB]/10 transition-all duration-300">
                    <summary class="list-none cursor-pointer flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-[#1E3A8A]">Apakah Konekin cocok untuk UMKM yang baru mulai bangun brand?</h4>
                            <p class="text-sm text-[#2563EB] font-semibold mt-2">Sangat cocok untuk kebutuhan branding awal</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 transition-transform duration-300 group-open:rotate-45">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                    </summary>
                    <p class="mt-5 text-[#1E3A8A]/70 leading-relaxed font-medium">
                        Iya. Konekin membantu UMKM menemukan kreator yang tepat untuk kebutuhan seperti desain logo, konten promosi, visual produk, hingga penguatan identitas brand secara bertahap.
                    </p>
                </details>

                <details class="group rounded-[2rem] bg-white border border-[#2563EB]/10 shadow-xl shadow-slate-200/40 p-6 open:shadow-2xl open:shadow-[#2563EB]/10 transition-all duration-300">
                    <summary class="list-none cursor-pointer flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-xl font-bold text-[#1E3A8A]">Di mana file atau foto yang diunggah pengguna disimpan?</h4>
                            <p class="text-sm text-[#2563EB] font-semibold mt-2">Tersimpan aman dan terhubung ke profil pengguna</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center shrink-0 transition-transform duration-300 group-open:rotate-45">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                    </summary>
                    <p class="mt-5 text-[#1E3A8A]/70 leading-relaxed font-medium">
                        File dan gambar yang diunggah pengguna akan disimpan melalui layanan cloud, lalu tautannya dihubungkan langsung ke data profil atau portofolio agar tetap ringan, rapi, dan mudah diakses.
                    </p>
                </details>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="mt-40">
        <div class="relative rounded-[3rem] bg-[#1E3A8A] overflow-hidden p-14 lg:p-24 text-center shadow-2xl shadow-[#1E3A8A]/30 border border-white/10 group">
            
            <!-- Dynamic abstract art background -->
            <div class="absolute inset-0 bg-[#0A66C2] opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            <div class="absolute top-[-50%] left-[-10%] w-[120%] h-[200%] bg-gradient-to-br from-[#2563EB]/40 to-transparent rotate-12 pointer-events-none"></div>
            <div class="absolute bottom-[-50%] right-[-10%] w-[80%] h-[150%] bg-gradient-to-tl from-[#0A66C2]/50 to-transparent -rotate-12 pointer-events-none"></div>
            
            <!-- Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff20_1px,transparent_1px)] bg-[size:1.5rem_1.5rem] opacity-30 pointer-events-none"></div>
            
            <div class="relative z-20 max-w-3xl mx-auto">
                <h2 class="font-display text-4xl md:text-6xl font-bold text-white mb-8 leading-tight">Berani Wujudkan <br> Ide Terbaikmu?</h2>
                <p class="text-xl text-[#EFF6FF]/80 mb-12 font-medium">Buat akun hari ini, gratis! Bergabunglah dengan ribuan pekerja kreatif dan UMKM di Konekin.</p>
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-10 py-5 bg-white text-[#1E3A8A] hover:text-[#2563EB] rounded-2xl font-bold text-lg shadow-xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-white/20 transition-all duration-300">
                    Daftar Sekarang Secara Gratis
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
