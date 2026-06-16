@extends('layouts.app')
@section('title', ($seo?->meta_title ?: $share->title) . ' — ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Акции', 'url' => route('shares.index')],
        ['label' => $share->title],
    ]
])

{{-- Page wrapper with parallax background --}}
<div class="relative min-h-screen overflow-hidden" id="share-page">

    @if($share->img)
    @push('head')
    <style>
    #share-parallax-bg {
        position: absolute;
        inset: -30% 0;
        background-image: url('/uploads/share/{{ $share->img }}');
        background-size: cover;
        background-position: center;
        filter: blur(20px);
        opacity: 0.65;
        will-change: transform;
        pointer-events: none;
    }
    #share-parallax-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.48) 100%);
        pointer-events: none;
    }
    </style>
    @endpush
    <div id="share-parallax-bg" aria-hidden="true"></div>
    <div id="share-parallax-overlay" aria-hidden="true"></div>

    @push('scripts')
    <script>
    (function () {
        var bg = document.getElementById('share-parallax-bg');
        var page = document.getElementById('share-page');
        if (!bg || !page) return;
        var raf = null;
        function update() {
            var rect = page.getBoundingClientRect();
            var progress = -rect.top / (page.offsetHeight - window.innerHeight || 1);
            var y = window.scrollY * 0.35;
            bg.style.transform = 'translateY(' + y + 'px)';
            raf = null;
        }
        window.addEventListener('scroll', function () {
            if (!raf) raf = requestAnimationFrame(update);
        }, { passive: true });
        update();
    }());
    </script>
    @endpush
    @endif

    <div class="relative max-w-7xl mx-auto px-4 py-12">

        {{-- Title --}}
        <h1 class="font-heading font-bold text-3xl md:text-5xl uppercase tracking-tight text-white mb-10 max-w-3xl">
            {{ $share->title }}
        </h1>

        {{-- Two-column layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-[520px_1fr] gap-8 items-start">

            {{-- Left: Image --}}
            <div class="relative">
                @if($share->img)
                <div class="relative overflow-hidden" style="height:50vh;min-height:280px;">
                    <img src="/uploads/share/{{ $share->img }}" alt="{{ $share->title }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>
                @else
                <div class="bg-gray-900 flex items-center justify-center" style="height:50vh;min-height:280px;">
                    <i class="fa-regular fa-tag text-6xl text-gray-700"></i>
                </div>
                @endif

                {{-- Badge --}}
                @if($share->subtitle)
                <span class="absolute top-4 left-0 bg-[var(--color-primary)] text-black text-sm font-heading font-bold uppercase px-5 py-1.5 tracking-widest">
                    {{ $share->subtitle }}
                </span>
                @endif

                {{-- Share + CTA bar --}}
                <div class="flex items-center justify-between gap-4 mt-4">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-white/40 font-heading uppercase tracking-widest">Поделиться</span>
                        <a href="https://vk.com/share.php?url={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="w-9 h-9 rounded-full bg-[#4C75A3] hover:bg-[#3d6090] flex items-center justify-center transition-colors">
                            <i class="fa-brands fa-vk text-white text-sm"></i>
                        </a>
                    </div>
                    <button onclick="document.getElementById('share-callback-modal').classList.remove('hidden')"
                            class="border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black font-heading font-bold uppercase text-sm tracking-widest px-7 py-2.5 transition-colors duration-200">
                        Забронировать
                    </button>
                </div>
            </div>

            {{-- Right: Content --}}
            <div class="flex flex-col gap-6">

                {{-- Price --}}
                @if($share->price)
                <div class="inline-flex items-baseline gap-2 border-l-4 border-[var(--color-primary)] pl-4">
                    <span class="font-heading font-bold text-5xl text-[var(--color-primary)]">
                        {{ number_format($share->price, 0, '.', ' ') }} ₽
                    </span>
                    @if($share->comment)
                    <span class="text-sm text-white/40">{{ $share->comment }}</span>
                    @endif
                </div>
                @endif

                {{-- Intro --}}
                @if($share->intro)
                <p class="text-lg text-white/60 leading-relaxed">{{ $share->intro }}</p>
                @endif

                {{-- Main content --}}
                @if($share->content)
                <div class="prose prose-invert max-w-none
                    text-white/70 leading-relaxed
                    [&_p]:mb-3
                    [&_h2]:font-heading [&_h2]:uppercase [&_h2]:tracking-wide [&_h2]:text-white [&_h2]:text-xl [&_h2]:mt-6 [&_h2]:mb-3
                    [&_ul]:space-y-1.5 [&_li]:text-white/65
                    [&_strong]:text-white [&_strong]:font-bold
                    [&_a]:text-[var(--color-primary)] [&_a]:no-underline hover:[&_a]:underline">
                    {!! $share->content !!}
                </div>
                @endif

                {{-- CTA button (desktop repeat) --}}
                <div class="mt-4">
                    <button onclick="document.getElementById('share-callback-modal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest px-8 py-4 transition-colors">
                        <i class="fa-solid fa-phone text-xs"></i> Заказать звонок
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Related shares --}}
@if(isset($related) && $related->isNotEmpty())
<section class="border-t border-white/8 py-14" style="background:rgba(3,7,18,0.5)">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-heading font-bold text-xs uppercase tracking-[0.3em] text-white/30 mb-8">Другие акции</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($related as $item)
                <x-ui.card.share :item="$item" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Callback modal --}}
<div id="share-callback-modal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="relative bg-gray-950 border border-white/10 max-w-sm w-full">
        <div class="border-b border-white/8 px-7 py-5 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">Забронировать</h3>
                <p class="text-xs text-white/30 font-heading uppercase tracking-widest mt-0.5 line-clamp-1">{{ $share->title }}</p>
            </div>
            <button onclick="document.getElementById('share-callback-modal').classList.add('hidden')"
                    class="w-8 h-8 border border-white/15 hover:border-white/40 flex items-center justify-center text-white/40 hover:text-white transition-colors text-lg leading-none shrink-0">
                &times;
            </button>
        </div>
        <form action="{{ url('/club/subscribe3/') }}" method="POST" class="px-7 py-6 space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Ваше имя *"
                   class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            <input type="tel" name="tel" placeholder="Ваш телефон *"
                   class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            <label class="flex items-start gap-2 text-xs text-white/40 cursor-pointer">
                <input type="checkbox" name="accept" class="mt-0.5 accent-[var(--color-primary)]">
                <span>Ознакомлен с <a href="{{ url('/privacy/') }}" target="_blank" class="text-[var(--color-primary)] hover:underline">политикой конфиденциальности</a></span>
            </label>
            <button type="submit"
                    class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest py-3.5 transition-colors">
                Отправить
            </button>
            <input type="hidden" name="title" value="Акция: {{ $share->title }}">
            <input type="hidden" name="url" value="{{ request()->url() }}">
        </form>
    </div>
</div>

@include('layouts.parts.subscribe')
@endsection
