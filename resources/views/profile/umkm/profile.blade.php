<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil UMKM - Konekin</title>
    @include('components.fonts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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

    <x-dashboard-nav-umkm />

    <main class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-[#1E3A8A] mb-2">Edit Profil UMKM</h1>
            <p class="text-[#1E3A8A]/60 font-medium">Atur informasi usaha agar creative worker lebih mudah mengenal
                brand, kontak, dan identitas bisnismu.</p>
        </div>

        @if(session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="relative group">
                        <div
                            class="w-40 h-40 rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-[#2563EB] to-[#0A66C2] p-1 shadow-2xl shadow-[#2563EB]/20">
                            <img id="preview-image"
                                src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=256&background=random' }}"
                                class="w-full h-full object-cover rounded-[2.2rem] border-4 border-white">
                        </div>
                        <label for="profile_photo"
                            class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 rounded-[2.5rem] opacity-0 group-hover:opacity-100 transition-all cursor-pointer text-white backdrop-blur-sm">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest">Ganti Foto</span>
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*"
                            onchange="previewFile()">
                    </div>

                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl font-display font-bold text-[#1E3A8A] mb-2">Foto Profil Usaha</h3>
                        <p class="text-sm text-[#1E3A8A]/60 mb-4 font-medium">Upload logo atau foto representatif brand
                            agar creative worker lebih cepat mengenali usahamu. Format JPG, PNG max 2MB.</p>
                        <button type="button" onclick="document.getElementById('profile_photo').click()"
                            class="px-6 py-2.5 bg-[#EFF6FF] text-[#2563EB] rounded-xl text-xs font-bold hover:bg-[#2563EB] hover:text-white transition-all">Pilih
                            Gambar</button>
                    </div>
                </div>
                @error('profile_photo')
                    <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <h3 class="text-xl font-display font-bold text-[#1E3A8A] border-b border-[#2563EB]/5 pb-4">Informasi
                    Usaha</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Nama Usaha</label>
                        <input type="text" name="name" required maxlength="255" value="{{ old('name', $user->name) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]"
                            placeholder="Nama usaha atau brand">
                        @error('name') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">No. WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]"
                            placeholder="08xxxxxxxxxx">
                        @error('phone') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]"
                            placeholder="Contoh: Bandung">
                        @error('city') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Alamat Singkat</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]"
                            placeholder="Alamat usaha">
                        @error('address') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-[#1E3A8A]/70 ml-1">Deskripsi Usaha</label>
                    <textarea name="bio" rows="5"
                        class="w-full px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A] resize-none"
                        placeholder="Ceritakan bidang usaha, produk utama, dan kebutuhan kreatif brand-mu...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-[#2563EB]/5 shadow-sm space-y-6">
                <h3 class="text-xl font-display font-bold text-[#1E3A8A] border-b border-[#2563EB]/5 pb-4">Lokasi Usaha
                </h3>

                <div class="space-y-4">
                    <div class="relative">
                        <div class="flex gap-2">
                            <input type="text" id="search-location"
                                class="flex-grow px-5 py-4 rounded-2xl bg-[#F8FAFC] border border-[#2563EB]/10 focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 outline-none transition-all font-medium text-[#1E3A8A]"
                                placeholder="Cari alamat atau nama tempat... (tekan Enter)">
                            <button type="button" id="btn-search-location"
                                class="px-6 py-4 bg-[#2563EB] text-white rounded-2xl font-bold shadow-md hover:bg-[#1E3A8A] transition-all">Cari</button>
                        </div>
                        <ul id="search-results"
                            class="hidden absolute left-0 right-0 top-full mt-2 bg-white shadow-xl rounded-xl border border-[#2563EB]/10 z-50 max-h-60 overflow-y-auto divide-y divide-gray-100">
                        </ul>
                    </div>

                    <div id="map" class="w-full h-[400px] rounded-2xl border border-[#2563EB]/10 z-0 relative"></div>
                    <p class="text-xs font-medium text-[#1E3A8A]/60">*Geser marker atau klik pada peta untuk menentukan
                        lokasi persis usaha Anda.</p>
                </div>

                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $user->latitude ?? '') }}">
                <input type="hidden" name="longitude" id="longitude"
                    value="{{ old('longitude', $user->longitude ?? '') }}">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="px-10 py-4 bg-[#2563EB] text-white rounded-2xl font-bold text-base shadow-xl shadow-[#2563EB]/20 hover:bg-[#1E3A8A] hover:-translate-y-1 transition-all active:translate-y-0">Simpan
                    Perubahan</button>
            </div>
        </form>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi peta
            let defaultLat = -6.200000; // Default Jakarta
            let defaultLng = 106.816666;
            let zoomLevel = 13;

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput.value && lngInput.value) {
                defaultLat = parseFloat(latInput.value);
                defaultLng = parseFloat(lngInput.value);
                zoomLevel = 16;
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    if (!latInput.value && !lngInput.value) {
                        const newLat = position.coords.latitude;
                        const newLng = position.coords.longitude;
                        map.setView([newLat, newLng], 16);
                        marker.setLatLng([newLat, newLng]);
                        updateInputs(newLat, newLng);
                    }
                });
            }

            const map = L.map('map').setView([defaultLat, defaultLng], zoomLevel);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            function reverseGeocode(lat, lng) {
                // Gunakan Nominatim Reverse Geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.address) {
                            // Update field Kota
                            const cityInput = document.querySelector('input[name="city"]');
                            if (cityInput) {
                                cityInput.value = data.address.city || data.address.town || data.address.village || data.address.county || data.address.state || cityInput.value;
                            }

                            // Update field Alamat Singkat
                            const addressInput = document.querySelector('input[name="address"]');
                            if (addressInput) {
                                // Gunakan nama jalan atau display_name yang relevan
                                const street = data.address.road || '';
                                const sub = data.address.suburb || '';
                                let shortAddress = street ? (sub ? `${street}, ${sub}` : street) : data.display_name;
                                addressInput.value = shortAddress;
                            }

                            // Update text pencarian biar konsisten
                            const searchInput = document.getElementById('search-location');
                            if (searchInput) {
                                searchInput.value = data.display_name || '';
                            }
                        }
                    })
                    .catch(error => console.error('Gagal mengambil detail alamat:', error));
            }

            function updateInputs(lat, lng, doReverseGeocode = true) {
                latInput.value = lat;
                lngInput.value = lng;

                if (doReverseGeocode) {
                    reverseGeocode(lat, lng);
                }
            }

            // Update saat marker digeser
            marker.on('dragend', function (e) {
                const position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            // Update saat peta diklik
            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng, true);
            });

            // Fitur Pencarian dengan Nominatim
            const searchInput = document.getElementById('search-location');
            const searchBtn = document.getElementById('btn-search-location');
            const resultsContainer = document.getElementById('search-results');

            function performSearch() {
                const query = searchInput.value;
                if (!query) return;

                resultsContainer.innerHTML = '<li class="px-5 py-3 text-sm text-gray-500 text-center">Mencari...</li>';
                resultsContainer.classList.remove('hidden');

                // Tambahkan parameter addressdetails=1 agar bisa mengambil info detail kota saat search
                fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length === 0) {
                            resultsContainer.innerHTML = '<li class="px-5 py-3 text-sm text-gray-500 text-center">Lokasi tidak ditemukan</li>';
                            return;
                        }

                        data.forEach(place => {
                            const li = document.createElement('li');
                            li.className = 'px-5 py-3 text-sm text-[#1E3A8A] hover:bg-blue-50 cursor-pointer font-medium transition-colors';
                            li.textContent = place.display_name;
                            li.addEventListener('click', () => {
                                const lat = parseFloat(place.lat);
                                const lon = parseFloat(place.lon);

                                map.setView([lat, lon], 16);
                                marker.setLatLng([lat, lon]);
                                updateInputs(lat, lon, false); // Jangan reverse geocode karena udah dari hasil search

                                // Update manual field address dan city dari hasil search
                                const cityInput = document.querySelector('input[name="city"]');
                                const addressInput = document.querySelector('input[name="address"]');
                                if (cityInput) cityInput.value = place.address?.city || place.address?.town || place.address?.state || '';
                                if (addressInput) addressInput.value = place.display_name;

                                searchInput.value = place.display_name;
                                resultsContainer.classList.add('hidden');
                            });
                            resultsContainer.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error('Error searching location:', error);
                        resultsContainer.innerHTML = '<li class="px-5 py-3 text-sm text-red-500 text-center">Gagal mencari lokasi</li>';
                    });
            }

            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });

            // Sembunyikan hasil saat klik di luar
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
