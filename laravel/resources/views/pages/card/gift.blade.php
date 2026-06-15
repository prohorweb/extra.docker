@extends('layouts.app')
@section('title', 'Фитнес тест-драйв — ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Абонементы и цены', 'url' => route('card.type')],
        ['label' => 'Фитнес тест-драйв'],
    ]
])

{{-- Sub-nav tabs --}}
<div class="border-b border-white/8 bg-black/40">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-0">
            <a href="{{ route('card.type') }}"
               class="font-heading font-bold text-xs uppercase tracking-widest px-6 py-4 border-b-2 border-transparent text-white/40 hover:text-white hover:border-white/30 transition-colors">
                Выбор абонемента
            </a>
            <a href="{{ route('card.gift') }}"
               class="font-heading font-bold text-xs uppercase tracking-widest px-6 py-4 border-b-2 border-[var(--color-primary)] text-[var(--color-primary)] transition-colors">
                Фитнес тест-драйв
            </a>
        </div>
    </div>
</div>

{{-- 1/2 + 1/2 layout --}}
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-[80vh]">

    {{-- Left: visual --}}
    <div class="relative overflow-hidden bg-black" style="min-height:50vw;max-height:90vh">
        <video muted loop autoplay playsinline class="absolute inset-0 w-full h-full object-cover opacity-60">
            <source src="/video/card-bg-1.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/70 hidden lg:block"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>

        {{-- Centered text on video --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-8">
            <img src="/images/logo-short.svg" alt="" class="h-16 opacity-70 mb-6">
            <p class="font-heading font-bold text-5xl md:text-7xl uppercase text-white/10 leading-none select-none tracking-tight">
                FREE
            </p>
            <p class="font-heading font-bold text-2xl md:text-4xl uppercase text-white tracking-widest mt-2">
                Тест-драйв
            </p>
            <p class="text-white/40 text-sm font-heading uppercase tracking-widest mt-2">безлимитная неделя фитнеса</p>
        </div>
    </div>

    {{-- Right: form --}}
    <div class="flex flex-col justify-center gap-8 px-8 md:px-14 py-16 bg-black/30 backdrop-blur-sm border-l border-white/5">

        {{-- Breadcrumb inline --}}
        <div class="flex items-center gap-2 text-xs font-heading uppercase tracking-widest text-white/25">
            <a href="{{ route('card.type') }}" class="hover:text-[var(--color-primary)] transition-colors">Абонементы</a>
            <span>/</span>
            <span class="text-white/50">Тест-драйв</span>
        </div>

        <div>
            <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">{{ $club->title ?? 'EXTRASPORT' }}</p>
            <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white leading-tight">
                Фитнес тест-драйв
            </h1>
            <p class="mt-3 text-white/50 leading-relaxed">
                Хотите больше узнать о нашем клубе? Оставьте заявку — наши менеджеры проведут для вас подробную экскурсию.
                Для тех, кому экскурсии мало, мы предлагаем <strong class="text-white">безлимитную неделю фитнеса!</strong>
            </p>
        </div>

        <div class="w-10 h-px bg-[var(--color-primary)]"></div>

        <p class="font-heading font-bold text-base uppercase tracking-widest text-white/60">
            Заявка на визит в {{ $club->title ?? 'клуб' }}
        </p>

        <form action="{{ url('/club/subscribe/') }}" method="POST" class="space-y-4" id="gift-form">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Ваше имя *" required
                       class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
                <input type="tel" name="tel" placeholder="Ваш телефон *" required
                       class="w-full bg-black border border-white/15 text-white placeholder-white/25 px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            </div>
            <label class="flex items-start gap-2 text-xs text-white/40 cursor-pointer">
                <input type="checkbox" name="accept" class="mt-0.5 accent-[var(--color-primary)]" required>
                <span>Ознакомлен с <a href="{{ url('/privacy/') }}" target="_blank" class="text-[var(--color-primary)] hover:underline">политикой конфиденциальности</a></span>
            </label>
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest px-10 py-4 transition-colors">
                <i class="fa-solid fa-bolt text-xs"></i> Записаться
            </button>
            <input type="hidden" name="url" value="{{ request()->url() }}">
        </form>

        {{-- What's included --}}
        <div class="border-t border-white/8 pt-6">
            <p class="text-xs font-heading uppercase tracking-[0.25em] text-white/25 mb-4">Что входит в тест-драйв</p>
            <ul class="space-y-2">
                @foreach([
                    'Тренажёрный зал',
                    'Два аэробных зала (30+ направлений)',
                    'Зал единоборств',
                    'Финская сауна',
                    'Плавательный бассейн',
                    'Cycle-студия',
                ] as $item)
                <li class="flex items-center gap-3 text-sm text-white/55">
                    <i class="fa-solid fa-check text-[var(--color-primary)] text-xs shrink-0"></i>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

    </div>
</div>

@include('layouts.parts.subscribe')
@endsection
