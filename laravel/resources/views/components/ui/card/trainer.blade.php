@props(['item'])
<a href="{{ $item->alias ? url('/es/command/' . $item->alias) : '#' }}"
   class="group relative overflow-hidden bg-gray-950 border border-white/5 hover:border-[var(--color-primary)]/60 transition-all duration-300 hover:shadow-[0_8px_30px_rgba(255,102,0,0.12)]">

    {{-- Square portrait --}}
    <div class="relative overflow-hidden aspect-square">
        @if($item->img)
            <img src="/uploads/trainer/{{ $item->img }}" alt="{{ $item->title }}"
                 class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-70"></div>
        @else
            <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                <i class="fa-regular fa-user text-6xl text-gray-700"></i>
            </div>
        @endif

        {{-- Name overlay on image --}}
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h2 class="font-heading font-bold text-white uppercase tracking-wide text-sm leading-tight group-hover:text-[var(--color-primary)] transition-colors">
                {{ $item->title }}
            </h2>
            @if($item->post)
            <p class="text-white/50 text-xs mt-0.5 leading-tight">{{ $item->post }}</p>
            @endif
        </div>
    </div>

    {{-- Hover arrow --}}
    <div class="absolute top-3 right-3 w-7 h-7 bg-black/60 border border-white/10 group-hover:border-[var(--color-primary)] group-hover:bg-[var(--color-primary)] flex items-center justify-center transition-all duration-300 opacity-0 group-hover:opacity-100">
        <i class="fa-solid fa-arrow-right text-[10px] text-white group-hover:text-black"></i>
    </div>
</a>
