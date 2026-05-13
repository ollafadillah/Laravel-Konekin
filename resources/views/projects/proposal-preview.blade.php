<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Proposal - Konekin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8FAFC; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="min-h-screen text-[#1E3A8A]">
    <main class="min-h-screen p-4 md:p-8">
        <section class="mx-auto max-w-6xl space-y-5">
            <div class="rounded-3xl border border-[#2563EB]/10 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="mb-2 text-[10px] font-black uppercase tracking-[0.2em] text-[#2563EB]">Preview Proposal</p>
                        <h1 class="font-display text-2xl font-bold md:text-3xl">{{ $application->proposal_display_name }}</h1>
                        <p class="mt-2 text-sm font-semibold uppercase tracking-widest text-[#1E3A8A]/45">
                            {{ strtoupper($extension ?: 'FILE') }} dari {{ $application->creative_name }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('project-applications.proposal.download', $application->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-[#1E3A8A] px-5 py-3 text-sm font-bold text-white transition-all hover:bg-[#2563EB]">
                            Download File
                        </a>
                        <a href="{{ $application->proposal_url }}" target="_blank" class="inline-flex items-center justify-center rounded-2xl bg-[#EFF6FF] px-5 py-3 text-sm font-bold text-[#2563EB] transition-all hover:bg-blue-100">
                            Buka Asli
                        </a>
                    </div>
                </div>
            </div>

            @if($canUseDocumentViewer)
                <div class="h-[78vh] overflow-hidden rounded-3xl border border-[#2563EB]/10 bg-white shadow-sm">
                    <iframe
                        src="https://docs.google.com/gview?embedded=1&url={{ urlencode($application->proposal_url) }}"
                        class="h-full w-full"
                        title="Preview {{ $application->proposal_display_name }}">
                    </iframe>
                </div>
            @else
                <div class="rounded-3xl border border-amber-200 bg-amber-50 p-8 text-amber-800">
                    <h2 class="font-display text-2xl font-bold">Preview belum tersedia untuk format ini</h2>
                    <p class="mt-3 max-w-3xl text-sm font-semibold leading-7">
                        Format {{ strtoupper($extension ?: 'file') }} biasanya tidak bisa ditampilkan langsung di browser. Gunakan tombol download untuk membuka file dengan aplikasi yang sesuai di perangkatmu.
                    </p>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
