<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Siapkan Profil Kreatifmu - Konekin</title>
    @include('components.fonts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: white;
            overflow-x: hidden;
        }
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .step-inactive { opacity: 0.3; filter: blur(2px); pointer-events: none; }
        .gradient-text {
            background: linear-gradient(135deg, #60A5FA 0%, #2563EB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .role-card.active {
            border-color: #2563EB;
            background: rgba(37, 99, 235, 0.1);
            transform: translateY(-5px);
        }
        .skill-chip.active {
            background: #2563EB;
            color: white;
            border-color: #2563EB;
        }
    </style>
</head>
<body class="antialiased">
    @php
        $roles = $creativeRoleOptions ?? \App\Support\CreativeRoles::options();
        $skillsData = [];

        foreach ($roles as $name => $role) {
            $skillsData[$name] = $role['skills'] ?? [];
        }
    @endphp

    <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
    </div>

    <main class="min-h-screen flex flex-col items-center justify-center p-4 py-20">
        <div class="w-full max-w-4xl">
            <div class="text-center mb-12 animate-fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-blue-400">Login Berhasil</span>
                </div>
                <h1 class="font-display text-4xl md:text-5xl font-bold mb-4">Selamat Datang, <span class="gradient-text">{{ $user->name }}</span>!</h1>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">Mari siapkan profilmu agar UMKM tahu keahlian hebat apa yang kamu miliki.</p>
            </div>

            <form action="{{ route('onboarding.store') }}" method="POST" id="onboardingForm">
                @csrf

                <div id="step-1" class="space-y-8 transition-all duration-500">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">1</span>
                        <h2 class="text-2xl font-display font-bold">Pilih Peran Utamamu</h2>
                    </div>
                    <p class="text-slate-400 text-sm mb-5">Sekarang cuma ada 5 kategori inti untuk training model dan pencarian kreator.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $name => $role)
                            <div onclick="selectRole(@js($name))" id="role-{{ Str::slug($name) }}" class="role-card glass p-6 rounded-3xl cursor-pointer hover:border-blue-500/50 transition-all duration-300 group">
                                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform text-blue-400">
                                    <i class="{{ $role['icon'] }}"></i>
                                </div>
                                <h3 class="text-xl font-bold mb-1">{{ $name }}</h3>
                                <p class="text-slate-400 text-sm">{{ $role['description'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="creative_category" id="selected_role" required>
                </div>

                <div id="step-2" class="mt-20 space-y-8 step-inactive transition-all duration-500">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">2</span>
                        <h2 class="text-2xl font-display font-bold">Pilih Skill Kamu</h2>
                    </div>

                    <div id="skills-container" class="flex flex-wrap gap-3">
                        <p class="text-slate-500 italic">Pilih peran utamamu terlebih dahulu...</p>
                    </div>
                    <div id="selected_skills_input_container"></div>
                </div>

                <div id="step-3" class="mt-20 space-y-8 step-inactive transition-all duration-500">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">3</span>
                        <h2 class="text-2xl font-display font-bold">Ceritakan Tentang Dirimu</h2>
                    </div>

                    <div class="glass p-8 rounded-[2.5rem]">
                        <textarea name="bio" rows="5" class="w-full bg-transparent border-none focus:ring-0 text-lg placeholder:text-slate-600 resize-none" placeholder="Tulis bio singkatmu di sini... minimal 20 karakter agar UMKM tertarik!" required></textarea>
                    </div>

                    <div class="flex justify-end pt-8">
                        <button type="submit" class="px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-lg shadow-2xl shadow-blue-600/20 transition-all transform hover:-translate-y-1 active:translate-y-0">
                            Mulai Perjalananmu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        const skillsData = @json($skillsData);
        const selectedSkills = new Set();

        function selectRole(role) {
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));

            const slug = role.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
            const card = document.getElementById('role-' + slug);

            if (card) {
                card.classList.add('active');
            }

            document.getElementById('selected_role').value = role;
            document.getElementById('step-2').classList.remove('step-inactive');
            document.getElementById('step-3').classList.add('step-inactive');
            selectedSkills.clear();
            updateSkillsInput();

            const container = document.getElementById('skills-container');
            container.innerHTML = '';

            (skillsData[role] || []).forEach(skill => {
                const chip = document.createElement('div');
                chip.className = 'skill-chip glass px-6 py-3 rounded-full cursor-pointer hover:border-blue-500 transition-all font-medium text-sm border border-slate-700';
                chip.innerText = skill;
                chip.onclick = () => toggleSkill(skill, chip);
                container.appendChild(chip);
            });
        }

        function toggleSkill(skill, element) {
            if (selectedSkills.has(skill)) {
                selectedSkills.delete(skill);
                element.classList.remove('active');
            } else {
                selectedSkills.add(skill);
                element.classList.add('active');
            }

            updateSkillsInput();

            if (selectedSkills.size > 0) {
                document.getElementById('step-3').classList.remove('step-inactive');
            } else {
                document.getElementById('step-3').classList.add('step-inactive');
            }
        }

        function updateSkillsInput() {
            const container = document.getElementById('selected_skills_input_container');
            container.innerHTML = '';

            selectedSkills.forEach(skill => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'skills[]';
                input.value = skill;
                container.appendChild(input);
            });
        }
    </script>
</body>
</html>
