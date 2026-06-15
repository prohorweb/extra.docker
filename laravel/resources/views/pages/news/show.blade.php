@extends('layouts.app')
@section('title', ($article->meta_title ?: $article->title) . ' — ' . ($club->title ?? 'EXTRASPORT'))

@php
$months = ['01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня',
           '07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'];
$dt = \Carbon\Carbon::parse($article->date);
@endphp

@section('content')

{{-- 1/2 left image + 1/2 right text --}}
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-[70vh]">

    {{-- Left: image or date block --}}
    <div class="relative overflow-hidden bg-gray-950" style="min-height:50vw;max-height:90vh">
        @if($article->img)
            <img src="/uploads/news/{{ $article->img }}" alt="{{ $article->title }}"
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/50 hidden lg:block"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        @else
            {{-- No image — styled date backdrop --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-4"
                 style="background: radial-gradient(ellipse at center, rgba(255,102,0,0.12) 0%, transparent 70%)">
                <span class="font-heading font-bold text-[18vw] lg:text-[12vw] text-white/5 leading-none select-none">
                    {{ $dt->format('j') }}
                </span>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-heading font-bold text-8xl md:text-[140px] text-[var(--color-primary)] leading-none opacity-20">
                        {{ $dt->format('j') }}
                    </span>
                </div>
                <div class="absolute bottom-10 left-0 right-0 text-center">
                    <p class="font-heading font-bold text-2xl text-white/70 uppercase tracking-widest">
                        {{ $months[$dt->format('m')] ?? '' }} {{ $dt->format('Y') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Date stamp always visible bottom-left --}}
        <div class="absolute bottom-6 left-6 flex items-end gap-3">
            <span class="font-heading font-bold text-6xl text-[var(--color-primary)] leading-none">{{ $dt->format('j') }}</span>
            <div class="pb-1 font-heading uppercase tracking-wide text-sm text-white/50 leading-tight">
                {{ $months[$dt->format('m')] ?? '' }}<br>{{ $dt->format('Y') }}
            </div>
        </div>
    </div>

    {{-- Right: content panel --}}
    <div class="flex flex-col justify-center gap-7 px-8 md:px-14 py-14 bg-black/30 backdrop-blur-sm border-l border-white/5">

        {{-- Breadcrumbs --}}
        <div class="flex items-center gap-2 text-xs font-heading uppercase tracking-widest text-white/25">
            <a href="{{ route('home') }}" class="hover:text-[var(--color-primary)] transition-colors">{{ $club->title ?? 'EXTRASPORT' }}</a>
            <span>/</span>
            <a href="{{ route('news.index') }}" class="hover:text-[var(--color-primary)] transition-colors">Новости</a>
            <span>/</span>
            <span class="text-white/50 line-clamp-1">{{ $article->title }}</span>
        </div>

        {{-- Title --}}
        <div>
            <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">Новости клуба</p>
            <h1 class="font-heading font-bold text-3xl md:text-4xl uppercase tracking-tight text-white leading-tight">
                {{ $article->title }}
            </h1>
        </div>

        <div class="w-10 h-px bg-[var(--color-primary)]"></div>

        {{-- Intro --}}
        @if($article->intro)
        <p class="text-lg text-white/60 leading-relaxed">{{ $article->intro }}</p>
        @endif

        {{-- Content --}}
        @if($article->content)
        <div class="prose prose-invert max-w-none
            text-white/70 leading-relaxed
            [&_p]:mb-3 [&_strong]:text-white
            [&_h2]:font-heading [&_h2]:uppercase [&_h2]:tracking-wide [&_h2]:text-white [&_h2]:text-xl [&_h2]:mt-6 [&_h2]:mb-3
            [&_ul]:space-y-2 [&_li]:text-white/65
            [&_a]:text-[var(--color-primary)] [&_a]:no-underline hover:[&_a]:underline">
            {!! $article->content !!}
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-4 pt-2">
            <button onclick="document.getElementById('news-callback-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest px-7 py-3.5 transition-colors">
                <i class="fa-solid fa-phone text-xs"></i> Записаться
            </button>
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 border border-white/20 hover:border-white/50 text-white/50 hover:text-white font-heading font-bold uppercase text-sm tracking-widest px-6 py-3.5 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Все новости
            </a>
            <a href="https://vk.com/share.php?url={{ urlencode(request()->url()) }}"
               target="_blank" rel="noopener"
               class="w-10 h-10 rounded-full bg-[#4C75A3] hover:bg-[#3d6090] flex items-center justify-center transition-colors">
                <i class="fa-brands fa-vk text-white text-sm"></i>
            </a>
        </div>

    </div>
</div>

{{-- Related news --}}
@if($related->isNotEmpty())
<section class="border-t border-white/8 py-14">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-heading font-bold text-xs uppercase tracking-[0.3em] text-white/30 mb-8">Другие новости</h2>
        <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-white/8">
            @foreach($related as $item)
                <div class="px-0 lg:px-8 first:pl-0 last:pr-0">
                    <x-ui.card.news :item="$item" :months="$months" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Callback modal --}}
<div id="news-callback-modal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="relative bg-gray-950 border border-white/10 max-w-sm w-full">
        <div class="border-b border-white/8 px-7 py-5 flex items-center justify-between">
            <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">Записаться</h3>
            <button onclick="document.getElementById('news-callback-modal').classList.add('hidden')"
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
            <input type="hidden" name="title" value="Новость: {{ $article->title }}">
            <input type="hidden" name="url" value="{{ request()->url() }}">
        </form>
    </div>
</div>

@include('layouts.parts.subscribe')
@endsection
