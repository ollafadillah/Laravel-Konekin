<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil Creative - Konekin</title>
    
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

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-[#1E3A8A] mb-2">Edit Profil</h1>
            <p class="text-[#1E3A8A]/60 font-medium">Atur informasi diri dan portofoliomu agar UMKM lebih mengenalmu.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- Profile Photo Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1 shadow-2xl shadow-[#2563EB]/20">
                            <img id="preview-image" src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&size=256&background=random' }}" class="w-full h-full object-cover rounded-[2.2rem] border-4 border-white">
                        </div>
                        <label for="profile_photo" class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 rounded-[2.5rem] opacity-0 group-hover:opacity-100 transition-all cursor-pointer text-white backdrop-blur-sm">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest">Ganti Foto</span>
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewFile()">
                    </div>
                    
                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Foto Profil</h3>
                        <p class="text-sm text-[#1E3A8A]/60 mb-4 font-medium">Gunakan foto asli agar meningkatkan kepercayaan klien. Format JPG, PNG max 2MB.</p>
                        <button type="button" onclick="document.getElementById('profile_photo').click()" class="px-6 py-2.5 bg-[#EFF6FF] text-[#2563EB] rounded-xl text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">Pilih Gambar</button>
                    </div>
                </div>
                @error('profile_photo')
                    <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- General Info Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <h3 class="text-xl font-display font-bold text-[#1E3A8A] border-b border-[#2563EB]/5 pb-4">Informasi Umum</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Nama Lengkap">
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="08123456xxx">
                        @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Contoh: Jakarta">
                        @error('city') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Alamat Singkat</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Contoh: Jl. Sudirman No. 123">
                        @error('address') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Tentang Saya (Bio)</label>
                    <textarea name="bio" rows="5" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A] resize-none" placeholder="Ceritakan pengalaman dan keahlianmu...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Bank Info Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-[#2563EB]/5 pb-4">
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Informasi Rekening</h3>
                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest">Wajib untuk Pencairan</span>
                </div>
                
                <p class="text-xs text-[#1E3A8A]/50 font-medium italic mb-4">Pastikan data rekening benar untuk kelancaran pencairan dana proyek via Midtrans Iris.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Kode Bank (Contoh: bca, mandiri, bni)</label>
                        <input type="text" name="bank_code" value="{{ old('bank_code', $user->bank_code) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="bca / mandiri / bni">
                        @error('bank_code') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="1234567890">
                        @error('bank_account_number') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Nama Pemilik Rekening (Sesuai Buku Tabungan)</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Nama Lengkap Pemilik">
                        @error('bank_account_name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-10 py-4 bg-[#2563EB] text-white rounded-2xl font-bold text-base shadow-xl shadow-[#2563EB]/20 hover:bg-[#1E3A8A] hover:-translate-y-1 transition-all active:translate-y-0">Simpan Perubahan</button>
            </div>
        </form>
    </main>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview-image');
            const file = document.getElementById('profile_photo').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
