@props([
    'href' => null,
    'label' => 'Kembali',
])

@php
    $fallbackHref = $href;

    if (!$fallbackHref && auth()->check()) {
        $user = auth()->user();

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $fallbackHref = route('dashboard.admin');
        } elseif (method_exists($user, 'isUMKM') && $user->isUMKM()) {
            $fallbackHref = route('dashboard.umkm');
        } elseif (method_exists($user, 'isCreativeWorker') && $user->isCreativeWorker()) {
            $fallbackHref = route('dashboard.creative');
        }
    }

    $fallbackHref ??= url('/');
@endphp

<a
    href="{{ $fallbackHref }}"
    onclick="if (window.history.length > 1 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey) { event.preventDefault(); window.history.back(); }"
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-[#2563EB] shadow-sm ring-1 ring-[#2563EB]/10 transition-all hover:bg-[#EFF6FF] hover:gap-3']) }}
>
    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    {{ $label }}
</a>
