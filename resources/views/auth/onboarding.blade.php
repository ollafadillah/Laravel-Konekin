<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Siapkan Profil Kreatifmu - Konekin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
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
    
    <!-- Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
    </div>

    <main class="min-h-screen flex flex-col items-center justify-center p-4 py-20">
        
        <div class="w-full max-w-4xl">
            <!-- Success Message -->
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
                
                <!-- Step 1: Role Selection -->
                <div id="step-1" class="space-y-8 transition-all duration-500">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold">1</span>
                        <h2 class="text-2xl font-display font-bold">Pilih Peran Utamamu</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $roles = [
                                ['name' => 'Graphic Designer', 'icon' => '🎨', 'desc' => 'Logo, Branding, Illustration'],
                                ['name' => 'Web Developer', 'icon' => '💻', 'desc' => 'Frontend, Backend, Fullstack'],
                                ['name' => 'Video Editor', 'icon' => '🎬', 'desc' => 'Motion Graphics, Editing'],
                                ['name' => 'Content Creator', 'icon' => '📸', 'desc' => 'UGC, Photography, TikTok'],
                                ['name' => 'Social Media', 'icon' => '📱', 'desc' => 'Management, Strategy'],
                            ];
                        @endphp

                        @foreach($roles as $role)
                            <div onclick="selectRole('{{ $role['name'] }}')" id="role-{{ Str::slug($role['name']) }}" class="role-card glass p-6 rounded-3xl cursor-pointer hover:border-blue-500/50 transition-all duration-300 group">
                                <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ $role['icon'] }}</div>
                                <h3 class="text-xl font-bold mb-1">{{ $role['name'] }}</h3>
                                <p class="text-slate-400 text-sm">{{ $role['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="creative_category" id="selected_role" required>
                </div>

                <!-- Step 2: Skills Selection -->
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

                <!-- Step 3: Bio -->
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
        const skillsData = {
            'Graphic Designer': ['Canva', 'Figma', 'Adobe Photoshop', 'Adobe Illustrator', 'InDesign', 'CorelDraw', 'Sketch'],
            'Web Developer': ['HTML/CSS', 'JavaScript', 'React.js', 'Next.js', 'PHP Laravel', 'Node.js', 'WordPress', 'Python'],
            'Video Editor': ['CapCut', 'Adobe Premiere Pro', 'After Effects', 'DaVinci Resolve', 'Final Cut Pro', 'Sony Vegas'],
            'Content Creator': ['Photography', 'Videography', 'Script Writing', 'Copywriting', 'Voice Over', 'Lighting'],
            'Social Media': ['Instagram Ads', 'TikTok Marketing', 'Facebook Ads', 'Content Planner', 'Analytics', 'Copywriting']
        };

        function selectRole(role) {
            // Update UI
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
            const slug = role.toLowerCase().replace(' ', '-');
            document.getElementById('role-' + slug).classList.add('active');
            
            // Set Input
            document.getElementById('selected_role').value = role;
            
            // Enable Step 2
            document.getElementById('step-2').classList.remove('step-inactive');
            
            // Render Skills
            const container = document.getElementById('skills-container');
            container.innerHTML = '';
            
            skillsData[role].forEach(skill => {
                const chip = document.createElement('div');
                chip.className = 'skill-chip glass px-6 py-3 rounded-full cursor-pointer hover:border-blue-500 transition-all font-medium text-sm border border-slate-700';
                chip.innerText = skill;
                chip.onclick = () => toggleSkill(skill, chip);
                container.appendChild(chip);
            });

            // Smooth scroll to step 2 if not in view
            // document.getElementById('step-2').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        const selectedSkills = new Set();

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
