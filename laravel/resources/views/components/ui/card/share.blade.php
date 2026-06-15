@props(['item'])
<a href="{{ $item->alias ? url('/card/shares/' . $item->alias) : '#' }}"
   class="group relative flex flex-col overflow-hidden bg-gray-950 border border-white/5 hover:border-[var(--color-primary)]/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(255,102,0,0.15)]">

    {{-- Image --}}
    <div class="relative overflow-hidden aspect-[16/9]">
        @if($item->img)
            <img src="/uploads/share/{{ $item->img }}" alt="{{ $item->title }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        @else
            <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                <i class="fa-regular fa-tag text-4xl text-gray-700"></i>
            </div>
        @endif

        {{-- Badge --}}
        @if($item->title2)
        <span class="absolute top-3 left-0 bg-[var(--color-primary)] text-black text-xs font-heading font-bold uppercase px-4 py-1 tracking-wider">
            {{ $item->title2 }}
        </span>
        @endif
    </div>

    {{-- Body --}}
    <div class="flex items-center justify-between gap-4 p-5">
        <div class="flex-1 min-w-0">
            <h2 class="font-heading font-bold text-base uppercase tracking-wide text-white group-hover:text-[var(--color-primary)] transition-colors line-clamp-2 leading-snug">
                {{ $item->title }}
            </h2>
            @if($item->intro)
            <p class="mt-1.5 text-sm text-white/50 line-clamp-2 leading-relaxed">{{ $item->intro }}</p>
            @endif
        </div>
        <div class="shrink-0 w-8 h-8 border border-white/20 group-hover:border-[var(--color-primary)] group-hover:bg-[var(--color-primary)] flex items-center justify-center transition-all duration-300">
            <i class="fa-solid fa-arrow-right text-xs text-white/60 group-hover:text-black transition-colors"></i>
        </div>
    </div>
</a>
