@extends('layouts.app')
@section('title', 'Тренеры клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'О клубе', 'url' => url('/es/club/')],
        ['label' => 'Тренеры'],
    ]
])

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <header class="mb-8">
        <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white">Тренеры</h1>
        <p class="mt-2 text-white/40 text-sm font-heading uppercase tracking-widest">{{ $club->title ?? 'EXTRASPORT' }}</p>
    </header>

    {{-- Filter --}}
    @if($trainerOptions->isNotEmpty())
    <form method="POST" action="{{ url('/es/command/') }}"
          class="flex flex-wrap items-center gap-3 mb-8 p-4 border border-white/8 bg-white/2">
        @csrf
        <span class="text-xs font-heading uppercase tracking-[0.2em] text-white/30 shrink-0">Направление:</span>
        <select name="filter"
                class="flex-1 min-w-[200px] bg-black border border-white/15 text-white text-sm font-heading px-3 py-2 focus:outline-none focus:border-[var(--color-primary)] transition-colors">
            <option value="">Все направления</option>
            @foreach($trainerOptions as $opt)
                <option value="{{ $opt->id }}" {{ request()->post('filter') == $opt->id ? 'selected' : '' }}>
                    {{ $opt->title }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="shrink-0 text-xs font-heading font-bold uppercase tracking-widest border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-black px-5 py-2 transition-colors">
            Показать
        </button>
        <button type="submit" name="reset" value="1"
                class="shrink-0 text-xs font-heading font-bold uppercase tracking-widest border border-white/15 text-white/40 hover:border-white/40 hover:text-white px-5 py-2 transition-colors">
            Сбросить
        </button>
    </form>
    @endif

    @if($trainers->isEmpty())
        <p class="text-white/40 text-center py-20 font-heading uppercase tracking-widest">Тренеров не найдено</p>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($trainers as $item)
                <x-ui.card.trainer :item="$item" />
            @endforeach
        </div>
    @endif

</section>

@include('layouts.parts.subscribe')
@endsection
