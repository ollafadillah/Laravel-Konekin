<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil Creative - Konekin</title>
    
    <!-- Fonts -->
    @include('components.fonts')

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
    @php
        $creativeRoleOptions = $creativeRoleOptions ?? config('creative_roles.options', []);
        $selectedCreativeCategory = old('creative_category', \App\Support\CreativeRoles::normalize($user->creative_category));
    @endphp
    
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

        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 18h.01M12 6h.01" /></svg>
                <span class="font-bold text-sm">{{ session('info') }}</span>
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

            <!-- Creative Role Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-[#2563EB]/5 pb-4">
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Role Kreatif Utama</h3>
                    <span class="px-3 py-1 bg-[#EFF6FF] text-[#2563EB] rounded-full text-[10px] font-black uppercase tracking-widest">Visible di pencarian</span>
                </div>

                <p class="text-sm text-[#1E3A8A]/60 font-medium">
                    Pilih role yang paling menggambarkan spesialisasimu. Role ini akan tampil saat UMKM mencari kreator di halaman <a href="{{ route('kreator.index') }}" class="text-[#2563EB] font-bold hover:underline">Cari Kreator</a>.
                </p>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Role Kreatif</label>
                    <select
                        name="creative_category"
                        class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A] @error('creative_category') border-red-500 @enderror"
                        required
                    >
                        <option value="" disabled {{ $selectedCreativeCategory ? '' : 'selected' }}>Pilih role kreatif utama</option>
                        @foreach($creativeRoleOptions as $label => $role)
                            <option value="{{ $label }}" {{ $selectedCreativeCategory === $label ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('creative_category') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- General Info Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <h3 class="text-xl font-display font-bold text-[#1E3A8A] border-b border-[#2563EB]/5 pb-4">Informasi Umum</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" required maxlength="255" value="{{ old('name', $user->name) }}" class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]" placeholder="Nama Lengkap">
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

            <!-- Profile Border Theme Section -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-[#2563EB]/5 pb-4">
                    <h3 class="text-xl font-display font-bold text-[#1E3A8A]">Pilih Border Profil</h3>
                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-black uppercase tracking-widest">Premium Aesthetic</span>
                </div>
                
                <p class="text-sm text-[#1E3A8A]/60 font-medium mb-4">Pilih gaya border yang akan menghiasi profilmu di halaman "Cari Kreator" saat UMKM mencari bakat. Pilih yang paling sesuai dengan kepribadian kreatifmu!</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- None -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="profile_border" value="none" class="peer sr-only" {{ old('profile_border', $user->profile_border ?? 'none') === 'none' ? 'checked' : '' }}>
                        <div class="h-32 rounded-2xl bg-slate-50 border-2 border-slate-200 peer-checked:border-[#2563EB] peer-checked:ring-4 peer-checked:ring-[#2563EB]/10 transition-all flex items-center justify-center flex-col gap-2">
                            <div class="w-10 h-10 rounded-full bg-slate-200"></div>
                            <span class="text-xs font-bold text-slate-500">Minimalis (Polos)</span>
                        </div>
                    </label>

                    <!-- Ocean -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="profile_border" value="ocean" class="peer sr-only" {{ old('profile_border', $user->profile_border) === 'ocean' ? 'checked' : '' }}>
                        <div class="h-32 rounded-2xl bg-[#E0F2FE] border-2 border-[#bae6fd] peer-checked:border-[#0284C7] peer-checked:ring-4 peer-checked:ring-[#0284C7]/20 transition-all flex items-center justify-center flex-col gap-2 relative overflow-hidden" style="background-image: radial-gradient(circle at top left, rgba(255,255,255,0.4) 0%, transparent 50%), radial-gradient(circle at bottom right, rgba(14,165,233,0.3) 0%, transparent 50%);">
                            <!-- SVG Ocean Decor -->
                            <svg class="absolute top-1 left-1 w-6 h-6 text-blue-400 opacity-60" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2a8 8 0 100 16 8 8 0 000-16zm0 2a6 6 0 110 12 6 6 0 010-12z"/></svg>
                            <svg class="absolute bottom-1 right-1 w-8 h-8 text-cyan-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            
                            <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white shadow-sm relative z-10"></div>
                            <span class="text-xs font-bold text-blue-800 relative z-10">Samudera Kreatif</span>
                        </div>
                    </label>

                    <!-- Math/School -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="profile_border" value="math" class="peer sr-only" {{ old('profile_border', $user->profile_border) === 'math' ? 'checked' : '' }}>
                        <div class="h-32 rounded-2xl bg-[#fdfbf7] border-2 border-[#e2e8f0] peer-checked:border-[#d97706] peer-checked:ring-4 peer-checked:ring-[#d97706]/20 transition-all flex items-center justify-center flex-col gap-2 relative overflow-hidden" style="background-image: linear-gradient(#e5e7eb 1px, transparent 1px), linear-gradient(90deg, #e5e7eb 1px, transparent 1px); background-size: 10px 10px;">
                            <!-- SVG Math Decor -->
                            <div class="absolute top-2 left-2 text-[10px] font-bold text-orange-400 font-mono">E=mc²</div>
                            <svg class="absolute bottom-2 right-2 w-6 h-6 text-green-500 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>

                            <div class="w-10 h-10 rounded-full bg-amber-50 border-2 border-white shadow-sm relative z-10"></div>
                            <span class="text-xs font-bold text-amber-800 relative z-10 bg-white/60 px-2 py-0.5 rounded-full">Ilmu & Logika</span>
                        </div>
                    </label>

                    <!-- Stars -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="profile_border" value="stars" class="peer sr-only" {{ old('profile_border', $user->profile_border) === 'stars' ? 'checked' : '' }}>
                        <div class="h-32 rounded-2xl bg-white border-2 border-dashed border-slate-300 peer-checked:border-[#8b5cf6] peer-checked:ring-4 peer-checked:ring-[#8b5cf6]/20 transition-all flex items-center justify-center flex-col gap-2 relative overflow-hidden">
                            <!-- SVG Stars Decor -->
                            <svg class="absolute top-2 left-2 w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="absolute bottom-2 right-2 w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>

                            <div class="w-10 h-10 rounded-full bg-purple-50 border-2 border-white shadow-sm relative z-10"></div>
                            <span class="text-xs font-bold text-purple-700 relative z-10">Bintang Keberuntungan</span>
                        </div>
                    </label>
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
