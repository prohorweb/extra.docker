@extends('layouts.app')
@section('title', 'Обзор клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

<x-ui.hero.video
    src="/video/service.mp4"
    srcMobile="/video/service_mobile.mp4"
    height="h-72 md:h-[480px]"
    overlay="bg-gradient-to-b from-black/20 via-black/50 to-black">
    <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">{{ $club->title ?? 'EXTRASPORT' }}</p>
    <h1 class="font-heading font-bold text-5xl md:text-7xl uppercase tracking-tight text-white">О клубе</h1>
</x-ui.hero.video>

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'О клубе', 'url' => url('/es/club/')],
        ['label' => 'Обзор клуба'],
    ]
])

{{-- VR tour --}}
@if(!empty($club->url_3d_tour))
<a href="{{ $club->url_3d_tour }}" target="_blank" rel="noopener noreferrer"
   class="group relative flex items-center justify-center overflow-hidden h-40 md:h-56 bg-gray-950 border-y border-white/5 hover:border-[var(--color-primary)]/40 transition-colors">
    <img src="/images/vr-full-piter-img.jpg" alt="VR-тур" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-60 transition-opacity scale-105 group-hover:scale-100 duration-700">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 text-center">
        <i class="fa-regular fa-vr-cardboard text-3xl text-[var(--color-primary)] mb-3 block"></i>
        <span class="font-heading font-bold text-2xl md:text-4xl uppercase tracking-widest text-white group-hover:text-[var(--color-primary)] transition-colors">VR-тур по клубу</span>
    </div>
</a>
@endif

{{-- Photo slider --}}
@if($banners->isNotEmpty())
<div class="relative overflow-hidden bg-black" id="clubSlider">
    <div class="flex transition-transform duration-500 ease-out" id="clubSlides">
        @foreach($banners as $banner)
        <div class="min-w-full">
            <img src="/uploads/banners/{{ $banner->img }}" alt="{{ $banner->title ?? '' }}"
                 class="w-full h-64 md:h-[480px] object-cover">
        </div>
        @endforeach
    </div>
    {{-- Arrows --}}
    <button onclick="slideClub(-1)"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-black/70 hover:bg-[var(--color-primary)] border border-white/20 hover:border-[var(--color-primary)] text-white hover:text-black flex items-center justify-center transition-all duration-200">
        <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>
    <button onclick="slideClub(1)"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-black/70 hover:bg-[var(--color-primary)] border border-white/20 hover:border-[var(--color-primary)] text-white hover:text-black flex items-center justify-center transition-all duration-200">
        <i class="fa-solid fa-chevron-right text-sm"></i>
    </button>
    {{-- Dots --}}
    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-1.5" id="clubDots">
        @foreach($banners as $i => $banner)
        <button onclick="slideClub(0, {{ $i }})"
                class="w-1.5 h-1.5 rounded-full bg-white/30 hover:bg-[var(--color-primary)] transition-colors slider-dot {{ $i === 0 ? 'bg-[var(--color-primary)]' : '' }}"></button>
        @endforeach
    </div>
</div>
@push('scripts')
<script>
(function () {
    var idx = 0;
    var slides = document.getElementById('clubSlides');
    var dots = document.querySelectorAll('.slider-dot');
    if (!slides) return;
    var total = slides.children.length;
    function go(i) {
        idx = (i + total) % total;
        slides.style.transform = 'translateX(-' + idx * 100 + '%)';
        dots.forEach(function(d, di) { d.classList.toggle('!bg-[var(--color-primary)]', di === idx); });
    }
    window.slideClub = function (dir, to) { go(to !== undefined ? to : idx + dir); };
    var timer = setInterval(function () { go(idx + 1); }, 5000);
    slides.parentElement.addEventListener('mouseenter', function() { clearInterval(timer); });
    slides.parentElement.addEventListener('mouseleave', function() { timer = setInterval(function() { go(idx + 1); }, 5000); });
}());
</script>
@endpush
@endif

{{-- About text --}}
@if(!empty($club->content))
<section class="max-w-4xl mx-auto px-4 py-14">
    <div class="prose prose-invert prose-lg max-w-none text-white/70 leading-relaxed [&_h2]:font-heading [&_h2]:uppercase [&_h2]:tracking-wide [&_h2]:text-white">
        {!! $club->content !!}
    </div>
</section>
@endif

{{-- Stats --}}
@php
$stats = [
    ['num' => '2008', 'label' => 'год открытия клуба'],
    ['num' => '2240', 'label' => 'м² общая площадь'],
    ['num' => '450', 'label' => 'м² тренажёрный зал'],
    ['num' => '290', 'label' => 'м² два аэробных зала'],
    ['num' => '96', 'label' => 'м² зал единоборств'],
    ['num' => '35', 'label' => 'м² Cycle-студия'],
    ['num' => '20', 'label' => 'метров бассейн 3 дорожки'],
];
@endphp
<section class="border-t border-white/8 bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="font-heading font-bold text-xs uppercase tracking-[0.3em] text-white/30 text-center mb-12">
            Технические характеристики
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-8 text-center">
            @foreach($stats as $s)
            <div>
                <div class="font-heading font-bold text-4xl md:text-5xl text-[var(--color-primary)] leading-none mb-2">{{ $s['num'] }}</div>
                <div class="text-xs text-white/35 leading-snug uppercase tracking-wide">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('layouts.parts.subscribe')
@endsection
