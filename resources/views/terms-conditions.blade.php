<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syarat dan Ketentuan - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .legal-copy p { color: #475569; line-height: 1.85; margin-top: 0.9rem; }
        .legal-copy h2 { color: #1E3A8A; font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.55rem, 2vw, 2rem); font-weight: 700; letter-spacing: 0; }
        .legal-copy h3 { color: #1E3A8A; font-size: 1.05rem; font-weight: 800; margin-top: 1.35rem; }
        .legal-copy ul { color: #475569; line-height: 1.8; margin-top: 0.85rem; padding-left: 1.15rem; }
        .legal-copy li { padding-left: 0.35rem; margin-top: 0.55rem; }
        .legal-copy strong { color: #1E3A8A; font-weight: 800; }
        .toc-link[aria-current="true"] { background: #EFF6FF; color: #2563EB; border-color: rgba(37, 99, 235, .22); }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    @php
        $dashboardRoute = null;
        if (auth()->check()) {
            $dashboardRoute = auth()->user()->isAdmin()
                ? route('dashboard.admin')
                : (auth()->user()->isCreativeWorker() ? route('dashboard.creative') : route('dashboard.umkm'));
        }

        $sections = [
            ['id' => 'pendahuluan', 'title' => 'Pendahuluan', 'icon' => 'fa-handshake'],
            ['id' => 'akun', 'title' => 'Akun Pengguna', 'icon' => 'fa-user-shield'],
            ['id' => 'tanggung-jawab', 'title' => 'Tanggung Jawab', 'icon' => 'fa-scale-balanced'],
            ['id' => 'creative-worker', 'title' => 'Creative Worker', 'icon' => 'fa-pen-nib'],
            ['id' => 'umkm', 'title' => 'UMKM', 'icon' => 'fa-store'],
            ['id' => 'konten', 'title' => 'Konten & Hak Cipta', 'icon' => 'fa-copyright'],
            ['id' => 'pembayaran', 'title' => 'Pembayaran Escrow', 'icon' => 'fa-wallet'],
            ['id' => 'dispute', 'title' => 'Revisi & Dispute', 'icon' => 'fa-comments'],
            ['id' => 'keamanan', 'title' => 'Keamanan Data', 'icon' => 'fa-lock'],
            ['id' => 'sanksi', 'title' => 'Pelanggaran', 'icon' => 'fa-triangle-exclamation'],
            ['id' => 'kontak', 'title' => 'Kontak', 'icon' => 'fa-envelope'],
        ];
    @endphp

    <header class="fixed top-0 z-50 w-full border-b border-[#2563EB]/10 bg-white/85 backdrop-blur-xl">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <div class="relative flex h-11 w-11 items-center justify-center">
                    <div class="absolute inset-0 rounded-2xl bg-[#2563EB] shadow-lg shadow-blue-200 transition-all duration-300 group-hover:rotate-6 group-hover:scale-105"></div>
                    <div class="absolute inset-0 -rotate-6 rounded-2xl bg-[#0A66C2] opacity-50 transition-all duration-300 group-hover:-rotate-12"></div>
                    <span class="relative font-display text-xl font-bold text-white">K</span>
                </div>
                <span class="font-display text-2xl font-bold tracking-tight text-[#1E3A8A] transition-colors group-hover:text-[#2563EB]">
                    Konekin<span class="text-[#2563EB]">.</span>
                </span>
            </a>

            <nav class="hidden items-center gap-7 md:flex">
                <a href="{{ route('home') }}" class="text-sm font-bold text-[#1E3A8A]/65 transition-colors hover:text-[#2563EB]">Beranda</a>
                <a href="{{ route('about') }}" class="text-sm font-bold text-[#1E3A8A]/65 transition-colors hover:text-[#2563EB]">Tentang</a>
                <a href="{{ route('privacy-policy') }}" class="text-sm font-bold text-[#1E3A8A]/65 transition-colors hover:text-[#2563EB]">Privasi</a>
                @auth
                    <a href="{{ $dashboardRoute }}" class="rounded-2xl bg-[#1E3A8A] px-5 py-3 text-sm font-bold text-white transition-all hover:bg-[#2563EB]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-[#1E3A8A] transition-colors hover:text-[#2563EB]">Masuk</a>
                    <a href="{{ route('register.role') }}" class="rounded-2xl bg-[#1E3A8A] px-5 py-3 text-sm font-bold text-white shadow-xl shadow-[#1E3A8A]/10 transition-all hover:bg-[#2563EB]">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="pt-24">
        <section class="border-b border-[#2563EB]/8 bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-12 sm:px-6 md:py-16 lg:grid-cols-[1fr_380px] lg:px-8">
                <div class="max-w-4xl">
                    <a href="{{ route('home') }}" class="mb-7 inline-flex items-center gap-2 rounded-full border border-[#2563EB]/12 bg-[#EFF6FF] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[#2563EB] transition-all hover:border-[#2563EB]/30 hover:bg-white">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke beranda
                    </a>
                    <p class="mb-4 text-[11px] font-black uppercase tracking-[0.24em] text-[#2563EB]">Legal Center</p>
                    <h1 class="font-display text-4xl font-bold leading-tight text-[#1E3A8A] md:text-6xl">
                        Syarat dan Ketentuan
                    </h1>
                    <p class="mt-6 max-w-3xl text-lg font-medium leading-8 text-[#475569]">
                        Kami merapikan aturan ini supaya hubungan UMKM, creative worker, dan Konekin berjalan adil, jelas, dan nyaman sejak proposal dikirim sampai dana escrow dicairkan.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-black uppercase tracking-widest text-emerald-700">
                            <i class="fas fa-shield-halved"></i>
                            Escrow Aman
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-black uppercase tracking-widest text-amber-700">
                            <i class="fas fa-file-signature"></i>
                            Scope Jelas
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-4 py-2 text-xs font-black uppercase tracking-widest text-violet-700">
                            <i class="fas fa-scale-balanced"></i>
                            Dispute Fair
                        </span>
                    </div>
                </div>

                <aside class="rounded-3xl border border-[#2563EB]/10 bg-[#F8FAFC] p-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#1E3A8A]/45">Terakhir Diperbarui</p>
                    <p class="mt-2 font-display text-3xl font-bold text-[#1E3A8A]">{{ date('d M Y') }}</p>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-2xl font-black text-[#2563EB]">15%</p>
                            <p class="mt-1 text-xs font-bold leading-5 text-[#475569]">fee platform dari nilai proyek</p>
                        </div>
                        <div class="rounded-2xl bg-white p-4">
                            <p class="text-2xl font-black text-emerald-600">100%</p>
                            <p class="mt-1 text-xs font-bold leading-5 text-[#475569]">dana ditahan sebelum release</p>
                        </div>
                    </div>
                    <p class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold leading-7 text-amber-800">
                        Dengan memakai Konekin, pengguna dianggap memahami alur proyek, pembayaran escrow, revisi, dan dispute di bawah ini.
                    </p>
                </aside>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[300px_1fr] lg:px-8">
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <div class="rounded-3xl border border-[#2563EB]/10 bg-white p-5 shadow-sm">
                    <p class="mb-4 text-[10px] font-black uppercase tracking-[0.2em] text-[#2563EB]">Daftar Isi</p>
                    <nav class="space-y-2">
                        @foreach($sections as $section)
                            <a href="#{{ $section['id'] }}" class="toc-link flex items-center gap-3 rounded-2xl border border-transparent px-4 py-3 text-sm font-bold text-[#1E3A8A]/60 transition-all hover:border-[#2563EB]/15 hover:bg-[#EFF6FF] hover:text-[#2563EB]">
                                <i class="fas {{ $section['icon'] }} w-4 text-center"></i>
                                {{ $section['title'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="space-y-6">
                <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-3xl border border-[#2563EB]/10 bg-white p-6">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF6FF] text-[#2563EB]">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h2 class="font-display text-xl font-bold">Proyek Harus Jelas</h2>
                        <p class="mt-3 text-sm font-medium leading-7 text-[#475569]">Brief, deadline, budget, proposal, dan revisi harus ditulis secara profesional agar tidak ada ekspektasi yang kabur.</p>
                    </div>
                    <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-emerald-600">
                            <i class="fas fa-vault"></i>
                        </div>
                        <h2 class="font-display text-xl font-bold text-emerald-900">Dana Ditahan</h2>
                        <p class="mt-3 text-sm font-medium leading-7 text-emerald-800">Pembayaran UMKM ditahan platform sebelum hasil akhir disetujui atau dispute diputuskan admin.</p>
                    </div>
                    <div class="rounded-3xl border border-rose-200 bg-rose-50 p-6">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-rose-600">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h2 class="font-display text-xl font-bold text-rose-900">Tidak Ada Penyalahgunaan</h2>
                        <p class="mt-3 text-sm font-medium leading-7 text-rose-800">Spam, penipuan, plagiarisme, harassment, atau permintaan di luar scope dapat dikenai sanksi.</p>
                    </div>
                </section>

                <article class="legal-copy rounded-[2rem] border border-[#2563EB]/10 bg-white p-6 shadow-sm md:p-9">
                    <section id="pendahuluan" class="scroll-mt-32">
                        <h2>1. Pendahuluan</h2>
                        <p>Konekin adalah platform digital yang menghubungkan creative worker dengan UMKM atau bisnis yang membutuhkan layanan kreatif. Syarat dan Ketentuan ini mengatur penggunaan platform oleh semua pengguna.</p>
                        <p>Dengan mendaftar atau menggunakan Konekin, Anda menyetujui aturan ini. Jika Anda tidak setuju dengan bagian tertentu, mohon tidak menggunakan layanan Konekin.</p>
                    </section>

                    <section id="akun" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>2. Akun Pengguna</h2>
                        <h3>2.1 Syarat Kepemilikan Akun</h3>
                        <ul>
                            <li>Pengguna wajib memberikan informasi yang akurat, lengkap, dan terbaru.</li>
                            <li>Pengguna harus menjaga keamanan email, password, dan akses akun.</li>
                            <li>Akun tidak boleh dipindahtangankan, diperjualbelikan, atau dipakai untuk menipu pengguna lain.</li>
                            <li>Konekin dapat membatasi akses akun jika terdapat indikasi pelanggaran atau penyalahgunaan.</li>
                        </ul>

                        <h3>2.2 Role Pengguna</h3>
                        <p>Konekin memiliki tiga role utama: UMKM sebagai pemilik proyek, creative worker sebagai pelamar dan pelaksana proyek, serta admin sebagai pengelola platform dan mediator.</p>
                    </section>

                    <section id="tanggung-jawab" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>3. Hak dan Tanggung Jawab Pengguna</h2>
                        <ul>
                            <li>Menggunakan platform secara legal, etis, dan profesional.</li>
                            <li>Tidak mengganggu, menipu, mengancam, atau merugikan pengguna lain.</li>
                            <li>Menghormati privasi, hak cipta, dan data milik pihak lain.</li>
                            <li>Menyampaikan informasi proyek, proposal, progress, dan pembayaran secara jujur.</li>
                            <li>Mematuhi semua hukum dan regulasi yang berlaku.</li>
                        </ul>
                    </section>

                    <section id="creative-worker" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>4. Ketentuan Creative Worker</h2>
                        <h3>4.1 Proposal dan Portofolio</h3>
                        <ul>
                            <li>Proposal harus relevan dengan proyek yang dilamar.</li>
                            <li>File proposal tidak boleh mengandung malware, spam, atau konten melanggar hukum.</li>
                            <li>Portofolio harus merupakan karya sendiri atau karya yang memang boleh ditampilkan.</li>
                            <li>Informasi skill, pengalaman, lokasi, dan kontak harus dibuat sejujur mungkin.</li>
                        </ul>

                        <h3>4.2 Pengerjaan Proyek</h3>
                        <ul>
                            <li>Creative worker wajib mengerjakan proyek sesuai brief dan kesepakatan.</li>
                            <li>Update progress harus menggambarkan kondisi pekerjaan sebenarnya.</li>
                            <li>Creative worker harus merespons revisi yang masih berada dalam scope proyek.</li>
                            <li>Creative worker tidak boleh memakai aset bajakan atau karya orang lain tanpa izin.</li>
                        </ul>
                    </section>

                    <section id="umkm" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>5. Ketentuan UMKM</h2>
                        <h3>5.1 Publikasi Proyek</h3>
                        <ul>
                            <li>UMKM wajib memberikan deskripsi proyek, kebutuhan, budget, dan deadline yang jelas.</li>
                            <li>UMKM tidak boleh membuat proyek palsu, menipu creative worker, atau mengarahkan transaksi di luar platform.</li>
                            <li>UMKM wajib menilai proposal secara profesional dan tidak diskriminatif.</li>
                        </ul>

                        <h3>5.2 Review Hasil Kerja</h3>
                        <ul>
                            <li>UMKM wajib membayar escrow ketika progress/draft sudah mencapai tahap yang ditentukan platform.</li>
                            <li>UMKM boleh meminta revisi jika hasil belum sesuai brief dan masih dalam scope.</li>
                            <li>UMKM tidak boleh menahan approval tanpa alasan yang jelas.</li>
                        </ul>
                    </section>

                    <section id="konten" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>6. Konten dan Properti Intelektual</h2>
                        <ul>
                            <li>Konten yang diunggah pengguna tetap menjadi tanggung jawab pengguna yang mengunggahnya.</li>
                            <li>Hasil kerja dapat dialihkan kepada UMKM setelah pembayaran dan approval selesai sesuai kesepakatan proyek.</li>
                            <li>Creative worker dapat menampilkan hasil kerja sebagai portofolio jika tidak ada perjanjian kerahasiaan atau larangan khusus.</li>
                            <li>Konten yang melanggar hak cipta, merek dagang, atau hak pihak lain dapat dihapus oleh Konekin.</li>
                        </ul>
                    </section>

                    <section id="pembayaran" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>7. Pembayaran dan Escrow</h2>
                        <p>Konekin menggunakan sistem escrow agar UMKM dan creative worker sama-sama terlindungi. Dana ditahan platform terlebih dahulu sebelum dicairkan.</p>
                        <ul>
                            <li>UMKM melakukan pembayaran melalui VA atau metode pembayaran yang tersedia.</li>
                            <li>UMKM wajib mengunggah bukti transfer jika diminta oleh sistem.</li>
                            <li>Admin dapat memverifikasi atau menolak bukti pembayaran.</li>
                            <li>Platform mengambil fee sebesar <strong>15%</strong> dari nilai proyek.</li>
                            <li>Dana creative worker dicairkan setelah hasil disetujui atau dispute diputuskan.</li>
                        </ul>

                        <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                            <p class="m-0 text-sm font-bold leading-7 text-emerald-800">
                                Contoh: jika budget proyek Rp 1.000.000, fee platform Rp 150.000 dan creative worker menerima Rp 850.000 setelah dana direlease.
                            </p>
                        </div>
                    </section>

                    <section id="dispute" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>8. Revisi dan Dispute Resolution</h2>
                        <h3>8.1 Revisi</h3>
                        <p>UMKM dapat meminta revisi jika hasil belum sesuai brief. Permintaan revisi harus jelas, spesifik, dan masih berada dalam scope pekerjaan yang disepakati.</p>

                        <h3>8.2 Dispute</h3>
                        <p>Jika UMKM dan creative worker tidak mencapai kesepakatan, salah satu pihak dapat membuka dispute. Admin atau mediator akan menilai bukti, progress, scope, dan komunikasi antar pihak.</p>
                        <ul>
                            <li>Dana tetap ditahan selama dispute berjalan.</li>
                            <li>Admin dapat memutuskan dana dicairkan ke creative worker, dikembalikan ke UMKM, atau meminta bukti tambahan.</li>
                            <li>Keputusan admin dibuat berdasarkan data yang tersedia di platform.</li>
                        </ul>
                    </section>

                    <section id="keamanan" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>9. Keamanan Data</h2>
                        <p>Konekin berupaya menjaga keamanan data pengguna melalui validasi akses, pembatasan role, hashing password, dan pengelolaan file yang terproteksi. Namun pengguna tetap wajib menjaga keamanan akun masing-masing.</p>
                        <ul>
                            <li>Jangan membagikan password, OTP, atau akses akun.</li>
                            <li>Jangan mengunggah file berbahaya atau data sensitif yang tidak perlu.</li>
                            <li>Laporkan aktivitas mencurigakan kepada tim Konekin.</li>
                        </ul>
                    </section>

                    <section id="sanksi" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>10. Pelanggaran dan Sanksi</h2>
                        <p>Konekin dapat memberi peringatan, membatasi fitur, menangguhkan akun, atau menghapus akun jika pengguna melanggar aturan.</p>
                        <ul>
                            <li>Penipuan, spam, harassment, plagiarisme, atau transaksi ilegal dilarang.</li>
                            <li>Manipulasi rating, proposal palsu, dan bukti pembayaran palsu dilarang.</li>
                            <li>Konekin dapat bekerja sama dengan pihak berwenang jika terdapat pelanggaran hukum.</li>
                        </ul>
                    </section>

                    <section id="kontak" class="mt-10 scroll-mt-32 border-t border-slate-100 pt-8">
                        <h2>11. Hubungi Kami</h2>
                        <p>Jika ada pertanyaan tentang Syarat dan Ketentuan ini, hubungi tim Konekin melalui kanal berikut:</p>
                        <ul>
                            <li><strong>Email support:</strong> support@konekin.com</li>
                            <li><strong>Email abuse:</strong> abuse@konekin.com</li>
                            <li><strong>Support chat:</strong> tersedia di dalam aplikasi saat layanan aktif</li>
                        </ul>
                    </section>
                </article>

                <section class="rounded-[2rem] border border-[#2563EB]/10 bg-[#1E3A8A] p-6 text-white md:p-8">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.22em] text-blue-200">Siap pakai Konekin?</p>
                            <h2 class="mt-2 font-display text-3xl font-bold">Mulai kolaborasi dengan aturan yang jelas.</h2>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('privacy-policy') }}" class="inline-flex items-center justify-center rounded-2xl bg-white/10 px-5 py-3 text-sm font-bold text-white ring-1 ring-white/20 transition-all hover:bg-white/20">
                                Kebijakan Privasi
                            </a>
                            @auth
                                <a href="{{ $dashboardRoute }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-bold text-[#1E3A8A] transition-all hover:bg-blue-50">
                                    Ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('register.role') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-bold text-[#1E3A8A] transition-all hover:bg-blue-50">
                                    Daftar Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    @include('components.footer')

    <script>
        const tocLinks = Array.from(document.querySelectorAll('.toc-link'));
        const observedSections = tocLinks
            .map((link) => document.querySelector(link.getAttribute('href')))
            .filter(Boolean);

        const observer = new IntersectionObserver((entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

            if (!visible) {
                return;
            }

            tocLinks.forEach((link) => {
                link.setAttribute('aria-current', link.getAttribute('href') === `#${visible.target.id}` ? 'true' : 'false');
            });
        }, {
            rootMargin: '-120px 0px -65% 0px',
            threshold: [0.12, 0.25, 0.5],
        });

        observedSections.forEach((section) => observer.observe(section));
    </script>
</body>
</html>
