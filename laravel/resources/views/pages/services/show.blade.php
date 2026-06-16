@extends('layouts.app')
@section('title', ($seo?->meta_title ?: $service->title) . ' — ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

{{-- Hero --}}
<div class="relative overflow-hidden h-64 md:h-[400px] bg-black">
    @if($service->img)
    <img src="/uploads/services/{{ $service->img }}" alt="{{ $service->title }}"
         class="absolute inset-0 w-full h-full object-cover opacity-60">
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/50 to-black"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
        <p class="text-xs font-heading uppercase tracking-[0.3em] text-[var(--color-primary)] mb-3">{{ $club->title ?? 'EXTRASPORT' }}</p>
        <h1 class="font-heading font-bold text-4xl md:text-6xl uppercase tracking-tight text-white">{{ $service->title }}</h1>
    </div>
</div>

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Услуги', 'url' => route('services.index')],
        ['label' => $service->title],
    ]
])

<div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10">

    {{-- Main content --}}
    <article>
        @if($service->content)
        <div class="prose prose-invert prose-lg max-w-none
            text-white/75 leading-relaxed
            [&_h2]:font-heading [&_h2]:uppercase [&_h2]:tracking-wide [&_h2]:text-white [&_h2]:text-2xl [&_h2]:mt-10 [&_h2]:mb-4
            [&_h3]:font-heading [&_h3]:uppercase [&_h3]:tracking-wide [&_h3]:text-white/90 [&_h3]:text-lg [&_h3]:mt-8 [&_h3]:mb-3
            [&_ul]:space-y-2 [&_ul>li]:text-white/65 [&_ul>li]:before:text-[var(--color-primary)]
            [&_strong]:text-white [&_strong]:font-bold
            [&_a]:text-[var(--color-primary)] [&_a]:no-underline hover:[&_a]:underline">
            {!! $service->content !!}
        </div>
        @else
        <p class="text-white/40 font-heading uppercase tracking-widest py-10">Описание услуги скоро появится</p>
        @endif

        {{-- CTA --}}
        <div class="mt-12 flex flex-col sm:flex-row gap-4">
            <button onclick="document.getElementById('service-callback-modal').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-black font-heading font-bold uppercase text-sm tracking-widest px-8 py-4 transition-colors">
                <i class="fa-solid fa-phone text-xs"></i> Заказать звонок
            </button>
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center justify-center gap-2 border border-white/20 hover:border-white/50 text-white/60 hover:text-white font-heading font-bold uppercase text-sm tracking-widest px-8 py-4 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Все услуги
            </a>
        </div>
    </article>

    {{-- Sidebar: other services --}}
    <aside>
        <h2 class="font-heading font-bold text-xs uppercase tracking-[0.25em] text-white/30 mb-5">Другие услуги</h2>
        <nav class="flex flex-col gap-1">
            @foreach($services ?? [] as $s)
            <a href="{{ route('services.show', $s->alias) }}"
               class="group flex items-center gap-3 p-3 border-l-2 transition-all duration-200
                      {{ $s->id === $service->id
                            ? 'border-[var(--color-primary)] bg-white/4'
                            : 'border-white/8 hover:border-[var(--color-primary)] hover:bg-white/3' }}">
                @if($s->img)
                <img src="/uploads/services/{{ $s->img }}" alt=""
                     class="w-10 h-10 object-cover shrink-0 {{ $s->id === $service->id ? 'opacity-100' : 'opacity-50 group-hover:opacity-80' }} transition-opacity">
                @endif
                <span class="font-heading font-bold text-xs uppercase tracking-wide leading-tight
                             {{ $s->id === $service->id ? 'text-[var(--color-primary)]' : 'text-white/60 group-hover:text-white' }} transition-colors">
                    {{ $s->title }}
                </span>
            </a>
            @endforeach
        </nav>
    </aside>

</div>

{{-- Callback modal --}}
<div id="service-callback-modal"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-sm px-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="relative bg-gray-950 border border-white/10 max-w-sm w-full">
        <div class="border-b border-white/8 px-7 py-5 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-xl uppercase tracking-wide text-white">Заказать звонок</h3>
                <p class="text-xs text-white/30 font-heading uppercase tracking-widest mt-0.5">{{ $service->title }}</p>
            </div>
            <button onclick="document.getElementById('service-callback-modal').classList.add('hidden')"
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
            <input type="hidden" name="title" value="Услуга: {{ $service->title }}">
            <input type="hidden" name="url" value="{{ request()->url() }}">
        </form>
    </div>
</div>

@include('layouts.parts.subscribe')
@endsection
