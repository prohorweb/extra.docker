@extends('layouts.app')
@section('title', 'Услуги клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

<x-ui.hero.video
    src="/video/service.mp4"
    srcMobile="/video/service_mobile.mp4"
    height="h-72 md:h-[480px]"
    overlay="bg-gradient-to-b from-black/20 via-black/50 to-black">
    <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">{{ $club->title ?? 'EXTRASPORT' }}</p>
    <h1 class="font-heading font-bold text-5xl md:text-7xl uppercase tracking-tight text-white">Услуги</h1>
</x-ui.hero.video>

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Услуги'],
    ]
])

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        @foreach($services as $item)
        <a href="{{ $item->alias ? url('/services/' . $item->alias) : '#' }}"
           class="group relative overflow-hidden border border-white/5 hover:border-[var(--color-primary)]/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_rgba(255,102,0,0.12)]">
            <div class="relative overflow-hidden aspect-[16/9]">
                @if($item->img)
                    <img src="/uploads/services/{{ $item->img }}" alt="{{ $item->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
                @else
                    <div class="w-full h-full min-h-[200px] bg-gray-900 flex items-center justify-center">
                        <i class="fa-regular fa-dumbbell text-5xl text-gray-700"></i>
                    </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 p-5 flex items-end justify-between gap-4">
                    <h2 class="font-heading font-bold text-xl uppercase tracking-wide text-white group-hover:text-[var(--color-primary)] transition-colors leading-tight">
                        {{ $item->title }}
                    </h2>
                    <div class="shrink-0 w-9 h-9 border border-white/20 group-hover:border-[var(--color-primary)] group-hover:bg-[var(--color-primary)] flex items-center justify-center transition-all duration-300">
                        <i class="fa-solid fa-arrow-right text-xs text-white/60 group-hover:text-black transition-colors"></i>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

</section>

@include('layouts.parts.subscribe')
@endsection
