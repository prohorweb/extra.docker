@extends('layouts.app')
@section('title', ($trainer->meta_title ?: $trainer->title) . ' — ' . ($club->title ?? 'EXTRASPORT'))

@push('head')
<style>
#trainer-page {
    position: relative;
}
#trainer-parallax-bg {
    position: fixed;
    inset: 0;
    background: url('/uploads/trainer/{{ $trainer->img }}') center/cover no-repeat;
    filter: blur(22px);
    opacity: 0.30;
    transform: scale(1.12);
    will-change: transform;
    pointer-events: none;
    z-index: -2;
}
#trainer-parallax-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0.50) 100%);
    pointer-events: none;
    z-index: -1;
}
</style>
@endpush

@section('content')

<div id="trainer-page">
<div id="trainer-parallax-bg" aria-hidden="true"></div>
<div id="trainer-parallax-overlay" aria-hidden="true"></div>

{{-- 1/2 + 1/2 hero split --}}
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-[80vh]">

    {{-- Left: full-bleed image --}}
    <div class="relative overflow-hidden" style="min-height:60vw;max-height:100vh">
        @if($trainer->img)
        <img src="/uploads/trainer/{{ $trainer->img }}" alt="{{ $trainer->title }}"
             class="absolute inset-0 w-full h-full object-cover object-top">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/60 hidden lg:block"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent lg:hidden"></div>
        @else
        <div class="absolute inset-0 bg-gray-900 flex items-center justify-center">
            <i class="fa-regular fa-user text-8xl text-gray-700"></i>
        </div>
        @endif
    </div>

    {{-- Right: content panel --}}
    <div class="flex flex-col justify-center gap-8 px-8 md:px-14 py-16 bg-black/40 backdrop-blur-sm">

        {{-- Breadcrumbs inline --}}
        <div class="flex items-center gap-2 text-xs font-heading uppercase tracking-widest text-white/25">
            <a href="{{ route('home') }}" class="hover:text-[var(--color-primary)] transition-colors">{{ $club->title ?? 'EXTRASPORT' }}</a>
            <span>/</span>
            <a href="{{ route('trainers.index') }}" class="hover:text-[var(--color-primary)] transition-colors">Тренеры</a>
            <span>/</span>
            <span class="text-white/50">{{ $trainer->title }}</span>
        </div>

        {{-- Name --}}
        <div>
            <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">Тренер клуба</p>
            <h1 class="font-heading font-bold text-4xl md:text-6xl uppercase tracking-tight text-white leading-tight">
                {{ $trainer->title }}
            </h1>
            @if($trainer->post)
            <p class="mt-3 text-base text-white/45 font-heading uppercase tracking-widest">{{ $trainer->post }}</p>
            @endif
        </div>

        {{-- Divider --}}
        <div class="w-12 h-px bg-[var(--color-primary)]"></div>

        {{-- Bio --}}
        @if($trainer->content)
        <div class="prose prose-invert max-w-none
            text-white/70 leading-relaxed
            [&_p]:mb-3 [&_strong]:text-white
            [&_ul]:space-y-2 [&_li]:text-white/65
            [&_a]:text-[var(--color-primary)] [&_a]:no-underline hover:[&_a]:underline">
            {!! $trainer->content !!}
        </div>
        @endif

        {{-- Club chips --}}
        <div class="flex flex-wrap gap-3">
            <span class="inline-flex items-center gap-2 border border-white/12 text-white/40 text-xs font-heading uppercase tracking-widest px-4 py-2">
                <i class="fa-regular fa-building text-[var(--color-primary)]"></i>
                {{ $club->title ?? 'EXTRASPORT' }}
            </span>
            @if($club->address)
            <span class="inline-flex items-center gap-2 border border-white/12 text-white/40 text-xs font-heading uppercase tracking-widest px-4 py-2">
                <i class="fa-regular fa-location-dot text-[var(--color-primary)]"></i>
                {{ $club->address }}
            </span>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-4">
            <button onclick="document.getElementById('trainer-callback-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest px-8 py-4 transition-colors">
                <i class="fa-solid fa-phone text-xs"></i> Записаться
            </button>
            <a href="{{ route('trainers.index') }}"
               class="inline-flex items-center gap-2 border border-white/20 hover:border-white/50 text-white/50 hover:text-white font-heading font-bold uppercase text-sm tracking-widest px-6 py-4 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Все тренеры
            </a>
            <a href="https://vk.com/share.php?url={{ urlencode(request()->url()) }}"
               target="_blank" rel="noopener"
               class="w-11 h-11 rounded-full bg-[#4C75A3] hover:bg-[#3d6090] flex items-center justify-center transition-colors">
                <i class="fa-brands fa-vk text-white text-sm"></i>
            </a>
        </div>

    </div>
</div>

{{-- Other trainers --}}
@if($others->isNotEmpty())
<div class="max-w-7xl mx-auto px-4 py-14 border-t border-white/8">
    <h2 class="font-heading font-bold text-xs uppercase tracking-[0.3em] text-white/30 mb-8">Другие тренеры</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        @foreach($others as $item)
            <x-ui.card.trainer :item="$item" />
        @endforeach
    </div>
</div>
@endif
</div>

{{-- Callback modal --}}
<div id="trainer-callback-modal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="relative bg-gray-950 border border-white/10 max-w-sm w-full">
        <div class="border-b border-white/8 px-7 py-5 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">Записаться</h3>
                <p class="text-xs text-white/30 font-heading uppercase tracking-widest mt-0.5">{{ $trainer->title }}</p>
            </div>
            <button onclick="document.getElementById('trainer-callback-modal').classList.add('hidden')"
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
            <input type="hidden" name="title" value="Тренер: {{ $trainer->title }}">
            <input type="hidden" name="url" value="{{ request()->url() }}">
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var bg = document.getElementById('trainer-parallax-bg');
    if (!bg) return;
    var raf = null;
    function update() {
        bg.style.transform = 'scale(1.12) translateY(' + (window.scrollY * 0.25) + 'px)';
        raf = null;
    }
    window.addEventListener('scroll', function () {
        if (!raf) raf = requestAnimationFrame(update);
    }, { passive: true });
    update();
}());
</script>
@endpush

@include('layouts.parts.subscribe')
@endsection
