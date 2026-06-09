<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kebijakan Privasi - Konekin</title>
    
    <!-- Fonts -->
    @include('components.fonts')
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        
        .prose {
            max-width: 4xl;
            margin: 0 auto;
        }
        
        .prose h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: bold;
            font-size: 1.875rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1E3A8A;
            border-left: 4px solid #2563EB;
            padding-left: 1rem;
        }
        
        .prose h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 1.25rem;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #1E3A8A;
        }
        
        .prose p {
            line-height: 1.8;
            margin-bottom: 1rem;
            color: #475569;
        }
        
        .prose ul, .prose ol {
            margin-left: 2rem;
            margin-bottom: 1rem;
            color: #475569;
            line-height: 1.8;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
        }
        
        .prose strong {
            color: #1E3A8A;
            font-weight: 700;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(10, 102, 194, 0.05) 100%);
            border-left: 4px solid #2563EB;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <!-- Navbar -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-xl bg-white/70 border-b border-[#2563EB]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 cursor-pointer group">
                    <div class="relative w-11 h-11 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[#2563EB] rounded-2xl rotate-3 group-hover:rotate-6 group-hover:scale-105 transition-all duration-300 shadow-lg shadow-blue-200"></div>
                        <div class="absolute inset-0 bg-[#0A66C2] rounded-2xl -rotate-6 opacity-50 group-hover:-rotate-12 transition-all duration-300"></div>
                        <span class="relative text-white font-display font-bold text-xl">K</span>
                    </div>
                    <span class="font-display font-bold text-2xl text-[#1E3A8A] tracking-tight group-hover:text-[#2563EB] transition-colors">
                        Konekin<span class="text-[#2563EB]">.</span>
                    </span>
                </a>

                <div class="flex items-center space-x-6">
                    <a href="{{ route('about') }}" class="text-[#1E3A8A] font-medium text-sm hover:text-[#2563EB] transition-colors">Tentang</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-[#1E3A8A] font-medium text-sm hover:text-[#2563EB] transition-colors">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-[#1E3A8A] font-bold text-sm hover:text-[#2563EB] transition-colors">Masuk</a>
                        <a href="{{ route('register.role') }}" class="px-6 py-2 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] text-white font-bold rounded-lg hover:shadow-lg transition-all">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-12">
                <a href="{{ route('home') }}" class="inline-flex items-center text-[#2563EB] font-bold text-sm mb-6 hover:gap-2 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <h1 class="font-display text-5xl md:text-6xl font-bold text-[#1E3A8A] mb-4">
                    Kebijakan Privasi
                </h1>
                <p class="text-lg text-[#64748B] mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-[#2563EB]"></i>Terakhir diperbarui: {{ date('d M Y') }}
                </p>
                <p class="text-[#64748B]">
                    Kami menghormati privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan dan menggunakan data Anda.
                </p>
            </div>

            <div class="prose">
                <h2 id="pengenalan">1. Pengenalan</h2>
                <p>
                    Konekin ("kami" atau "Konekin") berkomitmen untuk melindungi privasi Anda. Kebijakan Privasi ini menjelaskan praktik privasi kami dan bagaimana kami menangani data pribadi Anda.
                </p>

                <h2 id="data-kami-kumpulkan">2. Data Apa yang Kami Kumpulkan</h2>
                <h3>2.1 Data yang Anda Berikan</h3>
                <ul>
                    <li><strong>Informasi Akun:</strong> Nama, email, nomor telepon, kata sandi</li>
                    <li><strong>Profil:</strong> Foto profil, bio, portofolio, pengalaman kerja</li>
                    <li><strong>Informasi Bisnis:</strong> Nama bisnis, alamat, detail pembayaran</li>
                    <li><strong>Komunikasi:</strong> Pesan, chat, feedback yang Anda kirimkan</li>
                </ul>

                <h3>2.2 Data yang Kami Kumpulkan Otomatis</h3>
                <ul>
                    <li><strong>Log Data:</strong> IP address, browser type, halaman yang dikunjungi</li>
                    <li><strong>Cookies & Tracking:</strong> Untuk meningkatkan pengalaman pengguna</li>
                    <li><strong>Device Info:</strong> Tipe perangkat, sistem operasi, ID unik</li>
                </ul>

                <h2 id="penggunaan-data">3. Bagaimana Kami Menggunakan Data Anda</h2>
                <p>
                    Kami menggunakan data Anda untuk:
                </p>
                <ul>
                    <li>Menyediakan, meningkatkan, dan mengoperasikan platform Konekin</li>
                    <li>Memproses transaksi dan pembayaran</li>
                    <li>Mengirimkan notifikasi dan update tentang proyek Anda</li>
                    <li>Menyediakan customer support</li>
                    <li>Mencegah fraud dan melindungi keamanan</li>
                    <li>Menganalisis penggunaan platform untuk improvement</li>
                    <li>Mematuhi kewajiban hukum</li>
                </ul>

                <h2 id="keamanan-data">4. Keamanan Data</h2>
                <p>
                    Kami menggunakan berbagai teknik keamanan untuk melindungi data Anda:
                </p>
                <ul>
                    <li>Enkripsi SSL/TLS untuk transmisi data</li>
                    <li>Password hashing dengan algoritma aman</li>
                    <li>Firewall dan intrusion detection systems</li>
                    <li>Regular security audits dan penetration testing</li>
                    <li>Access control dan authentication mechanisms</li>
                </ul>

                <div class="highlight-box">
                    <strong>💡 Catatan:</strong> Meskipun kami mengambil tindakan keamanan yang ketat, tidak ada sistem yang 100% aman. Kami tidak bisa menjamin keamanan mutlak dari data Anda.
                </div>

                <h2 id="pembagian-data">5. Pembagian Data</h2>
                <h3>5.1 Kapan Kami Membagikan Data</h3>
                <p>
                    Data Anda dapat dibagikan dengan:
                </p>
                <ul>
                    <li><strong>Service Providers:</strong> Perusahaan payment, email service, hosting provider</li>
                    <li><strong>Legal Requirements:</strong> Jika diwajibkan oleh hukum atau perintah pengadilan</li>
                    <li><strong>Business Partners:</strong> Untuk integrasi dan layanan tambahan (dengan persetujuan Anda)</li>
                </ul>

                <h3>5.2 Kami Tidak Menjual Data</h3>
                <p>
                    Kami tidak menjual data pribadi Anda kepada pihak ketiga untuk tujuan marketing.
                </p>

                <h2 id="hak-pengguna">6. Hak Anda</h2>
                <p>
                    Anda memiliki hak untuk:
                </p>
                <ul>
                    <li><strong>Akses:</strong> Meminta salinan data pribadi Anda</li>
                    <li><strong>Koreksi:</strong> Memperbarui informasi yang tidak akurat</li>
                    <li><strong>Hapus:</strong> Meminta penghapusan data (Right to be Forgotten)</li>
                    <li><strong>Portabilitas:</strong> Menerima data dalam format yang dapat dipindahkan</li>
                    <li><strong>Keberatan:</strong> Menolak pemrosesan data tertentu</li>
                </ul>

                <p>
                    Untuk menggunakan hak-hak ini, silakan hubungi kami di <strong>privacy@konekin.com</strong>
                </p>

                <h2 id="cookies">7. Cookies dan Tracking</h2>
                <p>
                    Platform kami menggunakan cookies untuk:
                </p>
                <ul>
                    <li>Mempertahankan sesi login Anda</li>
                    <li>Mengingat preferensi Anda</li>
                    <li>Menganalisis penggunaan platform</li>
                    <li>Meningkatkan pengalaman pengguna</li>
                </ul>
                <p>
                    Anda dapat mengatur browser Anda untuk menolak cookies, tetapi ini dapat mempengaruhi fungsionalitas platform.
                </p>

                <h2 id="retensi-data">8. Retensi Data</h2>
                <p>
                    Kami menyimpan data Anda selama:
                </p>
                <ul>
                    <li><strong>Data Aktif:</strong> Selama akun Anda aktif</li>
                    <li><strong>Data Backup:</strong> Hingga 30 hari setelah penghapusan akun</li>
                    <li><strong>Data Legal:</strong> Sesuai dengan persyaratan undang-undang perpajakan dan keuangan</li>
                </ul>

                <h2 id="anak-anak">9. Perlindungan Anak-Anak</h2>
                <p>
                    Konekin tidak ditujukan untuk anak-anak di bawah 18 tahun. Kami tidak sengaja mengumpulkan data dari anak-anak. Jika kami mengetahui bahwa kami telah mengumpulkan data dari anak-anak, kami akan menghapusnya segera.
                </p>

                <h2 id="perubahan-kebijakan">10. Perubahan Kebijakan Ini</h2>
                <p>
                    Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Kami akan memberi tahu Anda tentang perubahan material melalui email atau notifikasi di platform. Penggunaan berkelanjutan platform berarti Anda menerima perubahan kebijakan.
                </p>

                <h2 id="kontak">11. Hubungi Kami</h2>
                <p>
                    Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini:
                </p>
                <ul>
                    <li><strong>Email:</strong> privacy@konekin.com</li>
                    <li><strong>Support Chat:</strong> Tersedia di dalam aplikasi</li>
                    <li><strong>WhatsApp:</strong> +62 812-XXXX-XXXX</li>
                </ul>

                <div class="highlight-box" style="margin-top: 3rem;">
                    <strong>📝 Terakhir Diperbarui:</strong> {{ date('d M Y, H:i') }} WIB
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('terms-conditions') }}" class="px-8 py-3 bg-white border-2 border-[#2563EB] text-[#2563EB] font-bold rounded-lg hover:bg-blue-50 transition-all text-center">
                    <i class="fas fa-file-contract mr-2"></i>Syarat & Ketentuan
                </a>
                <a href="{{ route('home') }}" class="px-8 py-3 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] text-white font-bold rounded-lg hover:shadow-lg transition-all text-center">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')
</body>
</html>
