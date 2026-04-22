<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konekin - Platform Kreatif & UMKM</title>
    <meta name="description" content="Konekin adalah platform yang menghubungkan Creative Worker dengan UMKM untuk menciptakan kolaborasi yang luar biasa.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Kita pakai font yang sangat modern, kreatif, tapi tetap readable -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, .font-display {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
        }

        .font-latin {
            font-family: 'Cinzel', serif;
            letter-spacing: 0.08em;
        }

        .custom-cursor,
        .custom-cursor-ring {
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            opacity: 0;
            z-index: 9999;
            transition:
                opacity 180ms ease,
                transform 180ms ease,
                width 180ms ease,
                height 180ms ease,
                border-color 180ms ease,
                background-color 180ms ease,
                box-shadow 180ms ease;
        }

        .custom-cursor {
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            background: linear-gradient(135deg, #2563EB, #0A66C2);
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.28);
            transform: translate(-50%, -50%);
        }

        .custom-cursor-ring {
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            border: 1.2px solid rgba(37, 99, 235, 0.24);
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            transform: translate(-50%, -50%);
        }

        .custom-cursor.is-visible,
        .custom-cursor-ring.is-visible {
            opacity: 1;
        }

        .custom-cursor.is-active {
            transform: translate(-50%, -50%) scale(0.82);
        }

        .custom-cursor-ring.is-hover {
            width: 40px;
            height: 40px;
            border-color: rgba(37, 99, 235, 0.38);
            background-color: rgba(37, 99, 235, 0.08);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1);
        }

        .custom-cursor-ring.is-active {
            width: 24px;
            height: 24px;
        }
        
        /* Organic animation for creative vibe */
        .blob-shape {
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: morph 8s ease-in-out infinite;
        }
        
        @keyframes morph {
            0%, 100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            34% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; }
            67% { border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%; }
        }

        @media (hover: hover) and (pointer: fine) {
            body.cursor-enhanced,
            body.cursor-enhanced a,
            body.cursor-enhanced button,
            body.cursor-enhanced [role="button"],
            body.cursor-enhanced input,
            body.cursor-enhanced textarea,
            body.cursor-enhanced select,
            body.cursor-enhanced summary,
            body.cursor-enhanced label {
                cursor: none !important;
            }
        }

        @media (hover: none), (pointer: coarse) {
            .custom-cursor,
            .custom-cursor-ring {
                display: none;
            }
        }
    </style>
</head>
<body class="antialiased bg-[#EFF6FF] text-[#1E3A8A] selection:bg-[#2563EB] selection:text-white">
    <div class="custom-cursor-ring" aria-hidden="true"></div>
    <div class="custom-cursor" aria-hidden="true"></div>
    <div class="min-h-screen flex flex-col relative overflow-x-hidden">
        
        <!-- Creative Decorative Background Shapes -->
        <div class="absolute top-0 -left-64 w-[600px] h-[600px] bg-[#2563EB]/10 blob-shape mix-blend-multiply filter blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute top-40 right-[-20%] w-[500px] h-[500px] bg-[#0A66C2]/15 rounded-full mix-blend-multiply filter blur-3xl opacity-50 pointer-events-none animate-pulse duration-1000"></div>

        @include('components.navbar')

        <main class="flex-grow z-10 relative">
            @yield('content')
        </main>

        @include('components.footer')
    </div>
</body>
</html>
