<footer class="mt-24 border-t border-[#2563EB]/10 bg-white text-[#1E3A8A]">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
            <div class="flex flex-col items-center text-center sm:flex-row sm:text-left lg:justify-start">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <div class="relative flex h-11 w-11 items-center justify-center">
                        <div class="absolute inset-0 rounded-2xl bg-[#2563EB] shadow-lg shadow-[#2563EB]/20 transition-all duration-300 group-hover:rotate-6 group-hover:scale-105"></div>
                        <div class="absolute inset-0 -rotate-6 rounded-2xl bg-[#0A66C2] opacity-50 transition-all duration-300 group-hover:-rotate-12"></div>
                        <span class="relative font-display text-xl font-bold text-white">K</span>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-bold leading-none tracking-tight">
                            Konekin<span class="text-[#2563EB]">.</span>
                        </p>
                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-[#1E3A8A]/45">
                            Creative x UMKM
                        </p>
                    </div>
                </a>
            </div>

            <div class="flex items-center justify-center gap-3 lg:justify-end">
                <a href="#" aria-label="Twitter Konekin" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-[#2563EB]/10 bg-[#EFF6FF] text-[#2563EB] transition-all hover:-translate-y-0.5 hover:bg-[#2563EB] hover:text-white">
                    <span class="text-xs font-black">X</span>
                </a>
                <a href="#" aria-label="Instagram Konekin" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-[#DB2777]/10 bg-pink-50 text-pink-600 transition-all hover:-translate-y-0.5 hover:bg-pink-600 hover:text-white">
                    <span class="text-xs font-black">IG</span>
                </a>
                <a href="#" aria-label="LinkedIn Konekin" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-[#0A66C2]/10 bg-sky-50 text-[#0A66C2] transition-all hover:-translate-y-0.5 hover:bg-[#0A66C2] hover:text-white">
                    <span class="text-xs font-black">in</span>
                </a>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-[#2563EB]/8 pt-6 text-center md:flex-row md:text-left">
            <p class="text-sm font-semibold text-[#1E3A8A]/45">
                &copy; {{ date('Y') }} Konekin. Hak cipta dilindungi.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm font-bold">
                <a href="{{ route('terms-conditions') }}" class="text-[#1E3A8A]/50 transition-colors hover:text-[#2563EB]">Syarat & Ketentuan</a>
                <a href="{{ route('privacy-policy') }}" class="text-[#1E3A8A]/50 transition-colors hover:text-[#2563EB]">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>
