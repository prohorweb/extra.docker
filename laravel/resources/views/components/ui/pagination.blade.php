@props(['paginator'])
@if($paginator->hasPages())
<nav class="flex items-center justify-center gap-1 mt-12" aria-label="Пагинация">
    {{-- Prev --}}
    @if($paginator->onFirstPage())
        <span class="w-9 h-9 flex items-center justify-center border border-white/10 text-white/20 cursor-default">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="w-9 h-9 flex items-center justify-center border border-white/20 text-white/60 hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] transition-colors">
            <i class="fa-solid fa-chevron-left text-xs"></i>
        </a>
    @endif

    {{-- Pages --}}
    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage()-2), min($paginator->lastPage(), $paginator->currentPage()+2)) as $page => $url)
        @if($page == $paginator->currentPage())
            <span class="w-9 h-9 flex items-center justify-center bg-[var(--color-primary)] text-black font-heading font-bold text-sm">
                {{ $page }}
            </span>
        @else
            <a href="{{ $url }}"
               class="w-9 h-9 flex items-center justify-center border border-white/20 text-white/60 hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] font-heading text-sm transition-colors">
                {{ $page }}
            </a>
        @endif
    @endforeach

    {{-- Next --}}
    @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="w-9 h-9 flex items-center justify-center border border-white/20 text-white/60 hover:border-[var(--color-primary)] hover:text-[var(--color-primary)] transition-colors">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </a>
    @else
        <span class="w-9 h-9 flex items-center justify-center border border-white/10 text-white/20 cursor-default">
            <i class="fa-solid fa-chevron-right text-xs"></i>
        </span>
    @endif
</nav>
@endif
