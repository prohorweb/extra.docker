@extends('layouts.app')
@section('title', 'Абонементы и цены — ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Абонементы и цены', 'url' => route('card.type')],
        ['label' => 'Выбор абонемента'],
    ]
])


<section class="max-w-7xl mx-auto px-4 pt-12 pb-16">

    {{-- Heading --}}
    <div class="text-center mb-12">
        <h1 class="font-heading font-bold text-3xl md:text-5xl uppercase tracking-tight text-white">
            Выбор абонемента <span class="text-[var(--color-primary)]">{{ $club->title ?? 'EXTRASPORT' }}</span>
        </h1>
    </div>

    {{-- Included amenities --}}
    <div class="mb-12">
        <h2 class="font-heading font-bold text-center text-sm uppercase tracking-[0.25em] text-white/50 mb-8">В каждый абонемент входит</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center max-w-3xl mx-auto">
            @php
            $amenities = [
                ['img' => '/uploads/layout/icones/card-choice-services-1.svg', 'label' => "Безлимитный\nфитнес *"],
                ['img' => '/uploads/layout/icones/card-choice-services-3.svg', 'label' => "Полный доступ\nво все зоны клуба *"],
                ['img' => '/uploads/layout/icones/card-choice-services-5.svg', 'label' => "Финская сухая сауна,\nнеограничено"],
                ['img' => '/uploads/layout/icones/card-choice-services-6.svg', 'label' => "Плавательный\nбассейн *"],
            ];
            @endphp
            @foreach($amenities as $a)
            <div class="flex flex-col items-center gap-3">
                <img src="{{ $a['img'] }}" alt="" class="h-14 md:h-16 opacity-80 filter brightness-[1.2]">
                <p class="text-xs text-white/45 uppercase tracking-wide leading-snug whitespace-pre-line">{{ $a['label'] }}</p>
            </div>
            @endforeach
        </div>
        <p class="text-center text-xs text-white/20 mt-6 font-heading tracking-widest">
            * наличие и период варьируются в зависимости от абонемента
        </p>
    </div>

    {{-- Cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($models as $i => $card)
        <div class="group border border-white/8 hover:border-[var(--color-primary)]/50 transition-all duration-300 cursor-pointer overflow-hidden"
             onclick="document.getElementById('card-modal-{{ $card->id }}').classList.remove('hidden')">

            {{-- Video card --}}
            <div class="relative overflow-hidden" style="aspect-ratio:16/9">
                <video muted loop autoplay playsinline
                       class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500">
                    <source src="/video/card-bg-{{ $loop->iteration }}.mp4" type="video/mp4">
                </video>
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>

                {{-- Centered title + logo + price --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="flex items-center gap-4 md:gap-6">
                        <h2 class="font-heading font-bold text-3xl md:text-5xl uppercase text-white tracking-wide drop-shadow-lg">
                            {{ $card->title }}
                        </h2>
                        <img src="/uploads/layout/icones/logo-short.svg" alt="" class="h-12 md:h-16 opacity-90 drop-shadow-lg">
                        @if($card->price)
                        <span class="font-heading font-bold text-3xl md:text-5xl text-white tracking-wide drop-shadow-lg">
                            {{ number_format($card->price, 0, '.', ' ') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CTA bar --}}
            <div class="flex items-center justify-center gap-2 py-4 bg-black/80 border-t border-white/5
                        group-hover:bg-[var(--color-primary)]/10 transition-colors">
                <i class="fa-sharp fa-solid fa-phone-arrow-down-left text-[var(--color-primary)] text-xs"></i>
                <span class="font-heading font-bold text-xs uppercase tracking-[0.25em] text-white/70 group-hover:text-white transition-colors">
                    Заказать звонок
                </span>
            </div>
        </div>

        {{-- Modal --}}
        <div id="card-modal-{{ $card->id }}"
             class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
             onclick="if(event.target===this)this.classList.add('hidden')">
            <div class="relative bg-gray-950 border border-white/10 max-w-sm w-full">
                <div class="border-b border-white/8 px-7 py-5 flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">{{ $card->title }}</h3>
                        @if($card->price)
                        <p class="text-[var(--color-primary)] font-heading font-bold text-lg mt-0.5">
                            {{ number_format($card->price, 0, '.', ' ') }} ₽
                        </p>
                        @endif
                    </div>
                    <button onclick="document.getElementById('card-modal-{{ $card->id }}').classList.add('hidden')"
                            class="w-8 h-8 border border-white/15 hover:border-white/40 flex items-center justify-center text-white/40 hover:text-white transition-colors text-lg leading-none shrink-0">
                        &times;
                    </button>
                </div>
                <form action="{{ url('/club/subscribe3/') }}" method="POST" class="px-7 py-6 space-y-4">
                    @csrf
                    <p class="text-xs text-white/30 font-heading uppercase tracking-widest">Оставьте заявку — мы перезвоним</p>
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
                        Заказать звонок
                    </button>
                    <input type="hidden" name="title" value="{{ $card->title }}">
                    <input type="hidden" name="url" value="{{ request()->url() }}">
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- SEO text --}}
    @if(!empty($params?->text))
    <div class="mt-14 prose prose-invert prose-sm max-w-none text-white/20 border-t border-white/5 pt-8">
        {!! $params->text !!}
    </div>
    @endif

</section>

@include('layouts.parts.subscribe')
@endsection
