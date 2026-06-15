@props(['item', 'months'])
@php
    $dt = \Carbon\Carbon::parse($item->date);
    $month = $months[$dt->format('m')] ?? '';
@endphp
<article class="group flex flex-col gap-4 py-6 border-b border-white/8 last:border-0">
    <div class="flex gap-5">
        {{-- Date block --}}
        <div class="shrink-0 flex flex-col items-center justify-start pt-1 w-14 text-center">
            <span class="font-heading font-bold text-4xl text-[var(--color-primary)] leading-none">{{ $dt->format('j') }}</span>
            <span class="font-heading text-xs text-white/40 uppercase tracking-wider mt-1 leading-tight">{{ $month }}<br>{{ $dt->format('Y') }}</span>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <h2 class="font-heading font-bold text-lg uppercase tracking-wide text-white group-hover:text-[var(--color-primary)] transition-colors leading-snug mb-2">
                <a href="{{ $item->alias ? url('/es/news/' . $item->alias) : '#' }}">
                    {{ $item->title }}
                </a>
            </h2>
            <p class="text-sm text-white/50 leading-relaxed line-clamp-3">{{ $item->intro }}</p>
            <a href="{{ $item->alias ? url('/es/news/' . $item->alias) : '#' }}"
               class="inline-flex items-center gap-2 mt-3 text-xs font-heading font-bold uppercase tracking-widest text-[var(--color-primary)] hover:text-white transition-colors">
                Подробнее <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</article>
