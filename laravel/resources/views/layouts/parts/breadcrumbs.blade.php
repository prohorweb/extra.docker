<nav class="hidden md:block border-b border-white/5" aria-label="breadcrumb">
    <div class="max-w-7xl mx-auto px-4 py-2.5">
        <ol class="flex items-center gap-2 text-xs font-heading uppercase tracking-widest text-white/30">
            @foreach($items as $i => $item)
                @if($i > 0)
                    <li><span class="text-white/15">/</span></li>
                @endif
                @if(isset($item['url']) && $i < count($items) - 1)
                    <li><a href="{{ $item['url'] }}" class="hover:text-[var(--color-primary)] transition-colors">{{ $item['label'] }}</a></li>
                @else
                    <li class="text-white/60">{{ $item['label'] }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>
