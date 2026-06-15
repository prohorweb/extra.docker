@extends('layouts.app')
@section('title', 'Мероприятия клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Мероприятия'],
    ]
])

@php
$months = ['01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня',
           '07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'];
@endphp

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <header class="mb-10">
        <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white">Мероприятия</h1>
        <p class="mt-2 text-white/40 text-sm font-heading uppercase tracking-widest">{{ $club->title ?? 'EXTRASPORT' }}</p>
    </header>

    {{-- Upcoming --}}
    @if($events->isNotEmpty())
    <div class="space-y-0 mb-16">
        @foreach($events as $item)
        @php $dt = \Carbon\Carbon::parse($item->date); @endphp
        <article class="group grid grid-cols-1 md:grid-cols-2 gap-0 border border-white/5 hover:border-[var(--color-primary)]/40 transition-all duration-300 mb-5 overflow-hidden">
            {{-- Image --}}
            <a href="{{ $item->alias ? url('/es/events/' . $item->alias) : '#' }}"
               class="relative overflow-hidden aspect-video md:aspect-auto min-h-[240px] block">
                @if($item->img)
                    <img src="/uploads/event/{{ $item->img }}" alt="{{ $item->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 absolute inset-0">
                @else
                    <div class="absolute inset-0 bg-gray-900 flex items-center justify-center">
                        <i class="fa-regular fa-calendar-star text-5xl text-gray-700"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/60 md:block hidden"></div>
            </a>

            {{-- Info --}}
            <div class="bg-gray-950 p-8 flex flex-col justify-center">
                <div class="flex items-end gap-3 mb-4">
                    <span class="font-heading font-bold text-5xl text-[var(--color-primary)] leading-none">{{ $dt->format('j') }}</span>
                    <div class="pb-1 font-heading uppercase tracking-wide text-xs text-white/40 leading-snug">
                        {{ $months[$dt->format('m')] ?? '' }}<br>{{ $dt->format('Y') }}
                    </div>
                </div>
                <h2 class="font-heading font-bold text-xl uppercase tracking-wide text-white mb-3 group-hover:text-[var(--color-primary)] transition-colors">
                    <a href="{{ $item->alias ? url('/es/events/' . $item->alias) : '#' }}">{{ $item->title }}</a>
                </h2>
                <p class="text-sm text-white/50 leading-relaxed mb-5 line-clamp-3">{{ $item->intro }}</p>
                <a href="{{ $item->alias ? url('/es/events/' . $item->alias) : '#' }}"
                   class="self-start inline-flex items-center gap-2 text-xs font-heading font-bold uppercase tracking-widest border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black px-5 py-2.5 transition-colors duration-200">
                    Подробнее <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </article>
        @endforeach
    </div>
    @endif

    {{-- Past --}}
    @if($eventsPast->isNotEmpty())
    <div class="border-t border-white/8 pt-10">
        <h2 class="font-heading font-bold text-sm uppercase tracking-[0.2em] text-white/30 mb-6">Прошедшие мероприятия</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($eventsPast as $item)
            @php $dt = \Carbon\Carbon::parse($item->date); @endphp
            <a href="{{ $item->alias ? url('/es/events/' . $item->alias) : '#' }}"
               class="group p-5 border border-white/5 hover:border-[var(--color-primary)]/40 transition-colors duration-200">
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="font-heading font-bold text-2xl text-white/30 group-hover:text-[var(--color-primary)] transition-colors">{{ $dt->format('j') }}</span>
                    <span class="text-xs font-heading uppercase tracking-wider text-white/20">{{ $months[$dt->format('m')] ?? '' }} {{ $dt->format('Y') }}</span>
                </div>
                <h3 class="font-heading font-bold text-sm uppercase tracking-wide text-white/70 group-hover:text-white transition-colors leading-snug">
                    {{ $item->title }}
                </h3>
            </a>
            @endforeach
        </div>
        <x-ui.pagination :paginator="$eventsPast" />
    </div>
    @endif

    @if($events->isEmpty() && $eventsPast->isEmpty())
        <p class="text-white/40 text-center py-20 font-heading uppercase tracking-widest">Мероприятий не найдено</p>
    @endif

</section>

@include('layouts.parts.subscribe')
@endsection
