<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Syarat dan Ketentuan - Konekin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
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
        
        .toc {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(10, 102, 194, 0.05) 100%);
            border: 2px solid rgba(37, 99, 235, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        
        .toc-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: #1E3A8A;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .toc-list li {
            margin-bottom: 0.75rem;
        }
        
        .toc-list a {
            color: #2563EB;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .toc-list a:hover {
            color: #1E40AF;
            padding-left: 0.5rem;
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(10, 102, 194, 0.05) 100%);
            border-left: 4px solid #2563EB;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
        }
        
        .highlight-box strong {
            color: #1E40AF;
        }
    </style>
</head>
<body class="antialiased text-[#1E3A8A]">
    <!-- Navbar -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-xl bg-white/70 border-b border-[#2563EB]/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
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

                <!-- Navigation Links -->
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
            
            <!-- Header -->
            <div class="mb-12">
                <a href="{{ route('home') }}" class="inline-flex items-center text-[#2563EB] font-bold text-sm mb-6 hover:gap-2 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <h1 class="font-display text-5xl md:text-6xl font-bold text-[#1E3A8A] mb-4">
                    Syarat dan Ketentuan
                </h1>
                <p class="text-lg text-[#64748B] mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-[#2563EB]"></i>Terakhir diperbarui: {{ date('d M Y') }}
                </p>
                <p class="text-[#64748B]">
                    Sebelum menggunakan platform Konekin, harap baca dan pahami syarat dan ketentuan berikut dengan seksama.
                </p>
            </div>

            <!-- Table of Contents -->
            <div class="toc">
                <div class="toc-title">
                    <i class="fas fa-list"></i> Daftar Isi
                </div>
                <ul class="toc-list">
                    <li><a href="#pendahuluan">1. Pendahuluan</a></li>
                    <li><a href="#perjanjian">2. Perjanjian Pengguna</a></li>
                    <li><a href="#hak-tanggung-jawab">3. Hak dan Tanggung Jawab Pengguna</a></li>
                    <li><a href="#creative-worker">4. Ketentuan Khusus Creative Worker</a></li>
                    <li><a href="#umkm">5. Ketentuan Khusus UMKM/Bisnis</a></li>
                    <li><a href="#konten">6. Konten dan Properti Intelektual</a></li>
                    <li><a href="#pembayaran">7. Sistem Pembayaran dan Escrow</a></li>
                    <li><a href="#keamanan">8. Keamanan Data</a></li>
                    <li><a href="#pelanggaran">9. Pelanggaran dan Sanksi</a></li>
                    <li><a href="#penolakan-tanggung-jawab">10. Penolakan Tanggung Jawab</a></li>
                    <li><a href="#hubungi-kami">11. Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- Content -->
            <div class="prose">
                <!-- Section 1 -->
                <h2 id="pendahuluan">1. Pendahuluan</h2>
                <p>
                    Konekin adalah platform digital yang menghubungkan <strong>Creative Worker</strong> (kreator, desainer, developer, copywriter, dan profesional kreatif lainnya) dengan <strong>UMKM dan Bisnis</strong> yang membutuhkan layanan kreatif. Syarat dan Ketentuan ini mengatur penggunaan platform kami oleh semua pengguna.
                </p>
                <p>
                    Dengan mendaftar dan menggunakan Konekin, Anda menyetujui untuk terikat oleh syarat dan ketentuan ini. Jika Anda tidak setuju dengan bagian mana pun dari dokumen ini, harap jangan menggunakan platform kami.
                </p>

                <!-- Section 2 -->
                <h2 id="perjanjian">2. Perjanjian Pengguna</h2>
                <h3>2.1 Syarat Kepemilikan Akun</h3>
                <p>
                    Untuk menggunakan Konekin, Anda harus:
                </p>
                <ul>
                    <li>Berusia minimal 18 tahun atau memiliki izin dari orang tua/wali</li>
                    <li>Memberikan informasi pendaftaran yang akurat, lengkap, dan terbaru</li>
                    <li>Memiliki yurisdiksi hukum untuk mengikat diri secara hukum dengan kami</li>
                    <li>Tidak terdaftar sebagai pengguna yang ditangguhkan atau dilarang sebelumnya</li>
                </ul>

                <h3>2.2 Tanggung Jawab Akun</h3>
                <p>
                    Anda bertanggung jawab untuk menjaga kerahasiaan informasi login Anda dan semua aktivitas yang terjadi di akun Anda. Anda setuju untuk:
                </p>
                <ul>
                    <li>Segera memberi tahu kami tentang akses tidak sah</li>
                    <li>Logout dari akun Anda pada perangkat bersama</li>
                    <li>Tidak membagikan akun dengan orang lain</li>
                </ul>

                <!-- Section 3 -->
                <h2 id="hak-tanggung-jawab">3. Hak dan Tanggung Jawab Pengguna</h2>
                <h3>3.1 Hak Pengguna</h3>
                <p>
                    Sebagai pengguna Konekin, Anda memiliki hak untuk:
                </p>
                <ul>
                    <li>Menggunakan platform sesuai dengan tujuannya yang ditentukan</li>
                    <li>Membuat profil profesional yang mewakili keahlian Anda</li>
                    <li>Mengakses fitur-fitur yang tersedia untuk tipe akun Anda</li>
                    <li>Menghubungi layanan pelanggan kami untuk bantuan</li>
                    <li>Mendownload dan menyimpan data pekerjaan Anda (sesuai kebijakan privasi)</li>
                </ul>

                <h3>3.2 Tanggung Jawab Pengguna</h3>
                <p>
                    Anda setuju untuk:
                </p>
                <ul>
                    <li>Menggunakan platform secara legal dan etis</li>
                    <li>Tidak melakukan aktivitas yang dapat merugikan pengguna lain</li>
                    <li>Mematuhi semua hukum dan peraturan yang berlaku</li>
                    <li>Tidak menggunakan platform untuk tujuan ilegal atau tidak sah</li>
                    <li>Menghormati hak kekayaan intelektual orang lain</li>
                </ul>

                <!-- Section 4 -->
                <h2 id="creative-worker">4. Ketentuan Khusus Creative Worker</h2>
                <h3>4.1 Profil dan Portfolio</h3>
                <p>
                    Sebagai Creative Worker, Anda harus:
                </p>
                <ul>
                    <li>Membuat profil yang jujur dan akurat</li>
                    <li>Menampilkan portofolio dengan karya-karya terbaik Anda</li>
                    <li>Memastikan semua karya di portfolio adalah milik Anda sendiri atau Anda memiliki izin penggunaan</li>
                    <li>Memperbarui profil dan portfolio secara berkala</li>
                </ul>

                <h3>4.2 Ketentuan Pengajuan Proposal</h3>
                <p>
                    Ketika mengajukan proposal untuk proyek:
                </p>
                <ul>
                    <li>Baca deskripsi proyek dengan cermat sebelum mengajukan</li>
                    <li>Berikan estimasi waktu dan harga yang realistis</li>
                    <li>Jangan mengirim proposal spam atau tidak relevan</li>
                    <li>Hormati keputusan UMKM dalam memilih candidate</li>
                </ul>

                <h3>4.3 Tanggung Jawab Dalam Pengerjaan</h3>
                <p>
                    Selama mengerjakan proyek:
                </p>
                <ul>
                    <li>Kerjakan sesuai dengan detail dan timeline yang disepakati</li>
                    <li>Berkomunikasi secara profesional dan responsif</li>
                    <li>Jangan gunakan tools atau teknologi yang tidak Anda kuasai tanpa disclosure</li>
                    <li>Berikan hasil kerja yang berkualitas dan sesuai standar industri</li>
                    <li>Hormati deadline dan jangan menunda tanpa informasi</li>
                </ul>

                <!-- Section 5 -->
                <h2 id="umkm">5. Ketentuan Khusus UMKM/Bisnis</h2>
                <h3>5.1 Posting Proyek</h3>
                <p>
                    Sebagai UMKM/Bisnis, ketika posting proyek Anda harus:
                </p>
                <ul>
                    <li>Memberikan deskripsi proyek yang jelas dan detail</li>
                    <li>Menetapkan budget yang realistis sesuai standar industri</li>
                    <li>Menjelaskan timeline dan deadline dengan spesifik</li>
                    <li>Memastikan informasi bisnis Anda akurat dan dapat diverifikasi</li>
                </ul>

                <h3>5.2 Proses Seleksi Creative Worker</h3>
                <p>
                    Dalam memilih Creative Worker:
                </p>
                <ul>
                    <li>Evaluasi portfolio dan pengalaman dengan objektif</li>
                    <li>Komunikasikan ekspektasi dengan jelas kepada candidate terpilih</li>
                    <li>Hormati keputusan Creative Worker untuk tidak menerima proyek Anda</li>
                    <li>Berikan feedback yang konstruktif jika proyek ditolak</li>
                </ul>

                <h3>5.3 Tanggung Jawab Sebagai Client</h3>
                <p>
                    Sebagai client/pemberi proyek:
                </p>
                <ul>
                    <li>Berikan brief yang jelas dan komunikasi yang responsif</li>
                    <li>Bayar sesuai dengan kesepakatan dan timeline yang telah ditetapkan</li>
                    <li>Jangan meminta revisi di luar scope yang telah disepakati</li>
                    <li>Hormati hak kekayaan intelektual Creative Worker selama proyek berlangsung</li>
                </ul>

                <!-- Section 6 -->
                <h2 id="konten">6. Konten dan Properti Intelektual</h2>
                <h3>6.1 Hak Konten</h3>
                <p>
                    Terkait konten dan hasil kerja proyek:
                </p>
                <ul>
                    <li><strong>Sebelum Pembayaran Selesai:</strong> Konten adalah hak Creative Worker</li>
                    <li><strong>Setelah Pembayaran Selesai:</strong> Sesuai dengan perjanjian proyek</li>
                    <li><strong>Portfolio:</strong> Creative Worker berhak menggunakan hasil kerja di portfolio mereka</li>
                </ul>

                <h3>6.2 Pelanggaran Hak Cipta</h3>
                <p>
                    Jika ada dugaan pelanggaran hak cipta atau plagiarisme, pengguna harus segera melaporkan kepada tim kami. Kami akan menyelidiki dan mengambil tindakan yang sesuai.
                </p>

                <h3>6.3 Konten yang Anda Upload</h3>
                <p>
                    Dengan mengunggah konten ke Konekin, Anda memberikan lisensi kepada kami untuk menampilkan konten tersebut di platform sesuai dengan kebutuhan operasional.
                </p>

                <!-- Section 7 -->
                <h2 id="pembayaran">7. Sistem Pembayaran dan Escrow</h2>
                <h3>7.1 Sistem Escrow</h3>
                <p>
                    Konekin menggunakan sistem escrow untuk melindungi semua pihak:
                </p>
                <ul>
                    <li>UMKM mentransfer dana ke escrow di awal proyek</li>
                    <li>Dana ditahan hingga proyek selesai dan disetujui</li>
                    <li>Setelah approval, dana ditransfer ke Creative Worker</li>
                </ul>

                <h3>7.2 Metode Pembayaran</h3>
                <p>
                    Konekin menerima pembayaran melalui berbagai metode:
                </p>
                <ul>
                    <li>Transfer Bank</li>
                    <li>E-wallet (Gopay, OVO, Dana, LinkAja)</li>
                    <li>Kartu Kredit/Debit</li>
                </ul>

                <h3>7.3 Biaya dan Komisi</h3>
                <p>
                    Konekin menerapkan komisi sebagai berikut:
                </p>
                <ul>
                    <li>Komisi platform: <strong>10%</strong> dari nilai proyek</li>
                    <li>Biaya transaksi pembayaran: Sesuai dengan metode yang dipilih</li>
                    <li>Biaya pencairan dana: Sesuai dengan sistem bank atau e-wallet</li>
                </ul>

                <h3>7.4 Refund Policy</h3>
                <p>
                    Kebijakan pengembalian dana:
                </p>
                <ul>
                    <li>Jika UMKM membatalkan sebelum Creative Worker mulai: Refund 100%</li>
                    <li>Jika Creative Worker membatalkan setelah menerima proyek: Refund ke UMKM 100%</li>
                    <li>Jika dispute/perselisihan: Tim kami akan mediasi dan memutuskan</li>
                </ul>

                <!-- Section 8 -->
                <h2 id="keamanan">8. Keamanan Data</h2>
                <h3>8.1 Perlindungan Data</h3>
                <p>
                    Konekin menggunakan standar keamanan industri untuk melindungi data Anda:
                </p>
                <ul>
                    <li>Enkripsi SSL/TLS untuk semua transmisi data</li>
                    <li>Password di-hash dengan algoritma yang aman</li>
                    <li>Two-Factor Authentication (2FA) untuk keamanan ekstra</li>
                    <li>Regular security audits dan penetration testing</li>
                </ul>

                <h3>8.2 Privasi Data</h3>
                <p>
                    Kami mematuhi regulasi privasi data yang berlaku. Data pribadi Anda tidak akan dibagikan kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.
                </p>

                <div class="highlight-box">
                    <strong>💡 Catatan Penting:</strong> Anda bertanggung jawab untuk tidak membagikan informasi sensitif (password, nomor rekening) kepada siapapun di luar Konekin.
                </div>

                <!-- Section 9 -->
                <h2 id="pelanggaran">9. Pelanggaran dan Sanksi</h2>
                <h3>9.1 Pelanggaran Umum</h3>
                <p>
                    Aktivitas yang dilarang termasuk namun tidak terbatas pada:
                </p>
                <ul>
                    <li>Harassment, bullying, atau ancaman kepada pengguna lain</li>
                    <li>Konten yang mengandung unsur SARA, pornografi, atau violence</li>
                    <li>Fraud, scam, atau tindakan penipuan</li>
                    <li>Spam atau aktivitas marketing yang tidak sah</li>
                    <li>Hacking, phishing, atau akses tidak sah ke akun lain</li>
                    <li>Money laundering atau aktivitas finansial ilegal</li>
                </ul>

                <h3>9.2 Tingkatan Sanksi</h3>
                <p>
                    Tergantung pada tingkat keparahan pelanggaran:
                </p>
                <ul>
                    <li><strong>Warning:</strong> Pelanggaran ringan, warning pertama kali</li>
                    <li><strong>Suspension Sementara:</strong> Akun dibekukan untuk periode tertentu (7-30 hari)</li>
                    <li><strong>Suspension Permanen:</strong> Akun dihapus sepenuhnya dari platform</li>
                    <li><strong>Laporan ke Hukum:</strong> Untuk aktivitas yang melanggar hukum</li>
                </ul>

                <h3>9.3 Hak Banding</h3>
                <p>
                    Jika Anda merasa akun Anda disuspend secara tidak adil, Anda dapat mengajukan banding kepada tim kami dalam waktu 14 hari sejak notifikasi.
                </p>

                <!-- Section 10 -->
                <h2 id="penolakan-tanggung-jawab">10. Penolakan Tanggung Jawab</h2>
                <h3>10.1 Platform "Sebagaimana Adanya"</h3>
                <p>
                    Platform Konekin disediakan "sebagaimana adanya" tanpa jaminan apapun. Kami tidak menjamin:
                </p>
                <ul>
                    <li>Ketersediaan platform 24/7 tanpa downtime</li>
                    <li>Kualitas atau kelayakan hasil kerja Creative Worker</li>
                    <li>Bahwa platform akan memenuhi kebutuhan spesifik Anda</li>
                </ul>

                <h3>10.2 Batasan Tanggung Jawab</h3>
                <p>
                    Dalam hal apapun, Konekin tidak bertanggung jawab untuk:
                </p>
                <ul>
                    <li>Kerugian finansial yang timbul dari penggunaan platform</li>
                    <li>Data loss atau kehilangan file pekerjaan</li>
                    <li>Dispute antara Creative Worker dan UMKM</li>
                    <li>Akses tidak sah ke akun Anda (jika bukan karena kelalaian kami)</li>
                </ul>

                <h3>10.3 Force Majeure</h3>
                <p>
                    Konekin tidak bertanggung jawab untuk kegagalan atau penundaan dalam memberikan layanan yang disebabkan oleh keadaan di luar kontrol kami (natural disasters, war, pandemics, dll).
                </p>

                <!-- Section 11 -->
                <h2 id="hubungi-kami">11. Hubungi Kami</h2>
                <h3>11.1 Informasi Kontak</h3>
                <p>
                    Jika Anda memiliki pertanyaan atau keluhan tentang Syarat dan Ketentuan ini:
                </p>
                <ul>
                    <li><strong>Email:</strong> support@konekin.com</li>
                    <li><strong>WhatsApp:</strong> +62 812-XXXX-XXXX</li>
                    <li><strong>Chat Support:</strong> Tersedia di dalam aplikasi (Senin-Jumat, 09:00-17:00 WIB)</li>
                    <li><strong>Form Bantuan:</strong> <a href="{{ route('home') }}" class="text-[#2563EB] font-bold hover:underline">Hubungi kami melalui website</a></li>
                </ul>

                <h3>11.2 Pelaporan Pelanggaran</h3>
                <p>
                    Jika Anda menyaksikan atau menjadi korban pelanggaran di platform, segera laporkan kepada tim kami melalui:
                </p>
                <ul>
                    <li>Menu "Laporkan" di profil pengguna</li>
                    <li>Email ke abuse@konekin.com</li>
                    <li>Chat support dengan detail dan bukti</li>
                </ul>

                <!-- Footer Info -->
                <div class="highlight-box" style="margin-top: 3rem; margin-bottom: 3rem;">
                    <strong>📝 Terakhir Diperbarui:</strong> {{ date('d M Y, H:i') }} WIB
                    <br><br>
                    Konekin berhak untuk memperbarui Syarat dan Ketentuan ini kapan saja. Perubahan akan diumumkan melalui email dan notifikasi di platform. Penggunaan berkelanjutan dari platform setelah perubahan berarti Anda menyetujui syarat yang baru.
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="px-8 py-3 bg-white border-2 border-[#2563EB] text-[#2563EB] font-bold rounded-lg hover:bg-blue-50 transition-all text-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                </a>
                <a href="{{ route('register.role') }}" class="px-8 py-3 bg-gradient-to-r from-[#2563EB] to-[#0A66C2] text-white font-bold rounded-lg hover:shadow-lg transition-all text-center">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')
</body>
</html>
