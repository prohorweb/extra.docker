@extends('layouts.app')

@section('content')

{{-- Scroll-driven hero slider (GSAP ScrollTrigger) --}}
<x-sections.hero-slider
    :banners="$banners ?? []"
    :heading="'Сеть фитнес клубов на результат!'"
    :subheading="$club['title'] ?? null"
    :video="asset('video/bg_moution.mp4')"
    :cta="['text' => 'Записаться', 'url' => '#callback']"
/>


{{-- About / Service video --}}
@php $prefix = (explode('.', request()->getHost())[0] ?? '') !== 'piter' ? '_clubs' : ''; @endphp
<section id="about" class="relative w-full overflow-hidden">
    <video muted loop autoplay playsinline class="hidden md:block w-full">
        <source src="{{ asset('video/service' . $prefix . '.mp4') }}"        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="{{ asset('video/service' . $prefix . '.webm') }}"       type='video/webm; codecs="vp8, vorbis"'>
    </video>
    <video muted loop autoplay playsinline class="block md:hidden w-full">
        <source src="{{ asset('video/service' . $prefix . '_mobile.mp4') }}"  type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="{{ asset('video/service' . $prefix . '_mobile.webm') }}" type='video/webm; codecs="vp8, vorbis"'>
    </video>
</section>


{{-- Shares / Actions --}}
<section class="py-16" id="actions">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-heading font-bold uppercase text-center mb-10">
            Акции клуба {{ $club['title'] ?? '' }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
            @foreach($shares ?? [] as $i => $share)
            <a href="{{ url('/card/shares/' . ($share['alias'] ?? '')) }}"
               class="group relative flex flex-col bg-neutral-900 overflow-hidden hover:ring-1 hover:ring-[var(--color-primary)] transition-all
                       {{ $i === 2 ? 'hidden lg:flex' : '' }}">
                @if(!empty($share['title2']))
                <span class="absolute top-3 left-3 z-10 text-xs font-heading uppercase tracking-widest
                             text-[var(--color-primary)] bg-black/60 px-2 py-1">
                    {{ $share['title2'] }}
                </span>
                @endif
                <img src="{{ !empty($share['img']) ? '/uploads/share/'.$share['img'] : 'https://placehold.co/876x680/111/333?text=Share' }}"
                     alt="{{ $share['title'] ?? '' }}"
                     class="w-full aspect-[4/3] object-cover">
                <div class="flex items-center gap-4 p-4 text-left">
                    <div class="flex-1">
                        <h3 class="font-heading font-bold text-white text-sm uppercase">{{ $share['title'] ?? '' }}</h3>
                        <p class="mt-1 text-xs text-gray-400">{{ $share['intro'] ?? '' }}</p>
                    </div>
                    <i class="fa-sharp fa-solid fa-arrow-right text-[var(--color-primary)] shrink-0"></i>
                </div>
            </a>
            @endforeach
        </div>
        @if(!empty($shares))
        <div class="flex justify-center mt-10">
            <a href="{{ url('/card/shares/') }}"
               class="px-6 py-3 font-heading font-bold uppercase border border-[var(--color-primary)]
                      text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black transition-colors">
                Все акции
            </a>
        </div>
        @endif
    </div>
</section>


{{-- Subscribe / Test-drive --}}
@include('layouts.parts.subscribe')


{{-- Map & Contacts overlay --}}
<x-sections.map :placemarks="isset($club['coordinates']) ? [[
    'coordinates' => $club['coordinates'],
    'hint'        => $club['title'] ?? '',
    'icon'        => asset('img/marker.png'),
    'url'         => '#',
]] : []">
    {{-- Contacts panel overlaid on the map (left side) --}}
    <div class="absolute inset-y-0 left-0 z-10 flex items-center">
        <div class="bg-black/70 backdrop-blur-sm px-10 py-12 h-full flex flex-col justify-center min-w-[320px] max-w-[420px]">
            <h2 class="font-heading font-bold uppercase text-3xl text-white mb-8 tracking-wide">
                Контакты
            </h2>
            <ul class="space-y-5 text-white/85 text-sm leading-relaxed">
                <li class="flex items-center gap-4">
                    <i class="fa-solid fa-phone text-[var(--color-primary)] text-xl w-5 shrink-0"></i>
                    <a href="tel:{{ $club['tel'] ?? '' }}"
                       class="text-2xl font-heading font-bold text-[var(--color-primary)] hover:text-[var(--color-primary-hover)] transition-colors">
                        {{ $club['tel'] ?? '' }}
                    </a>
                </li>
                <li class="flex items-center gap-4">
                    <i class="fa-solid fa-envelope text-[var(--color-primary)] w-5 shrink-0"></i>
                    <a href="mailto:{{ $club['email'] ?? '' }}"
                       class="text-[var(--color-primary)] hover:underline transition-colors">
                        {{ $club['email'] ?? '' }}
                    </a>
                </li>
                <li class="flex items-start gap-4">
                    <i class="fa-solid fa-location-dot text-[var(--color-primary)] w-5 shrink-0 mt-0.5"></i>
                    <span>{{ $club['address'] ?? '' }}</span>
                </li>
                @if(!empty($metros))
                <li class="flex items-start gap-4">
                    <i class="fa-solid fa-train-subway text-[var(--color-primary)] w-5 shrink-0 mt-0.5"></i>
                    <span>{{ implode(', ', $metros) }}</span>
                </li>
                @endif
                <li class="flex items-start gap-4">
                    <i class="fa-solid fa-clock text-[var(--color-primary)] w-5 shrink-0 mt-0.5"></i>
                    <span>
                        Время работы<br>
                        пн–пт: {{ $club['start_work'] ?? '' }}<br>
                        сб–вс: {{ $club['start_work_weekend'] ?? '' }}
                    </span>
                </li>
                <li class="flex items-start gap-4">
                    <i class="fa-solid fa-user-tie text-[var(--color-primary)] w-5 shrink-0 mt-0.5"></i>
                    <span>Отдел продаж:<br>пн-вс: 10:00 до 22:00</span>
                </li>
            </ul>
        </div>
    </div>
</x-sections.map>

@endsection


