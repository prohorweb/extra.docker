@extends('layouts.app')
@section('title', 'Новости клуба ' . ($club->title ?? 'EXTRASPORT'))

@section('content')

@include('layouts.parts.breadcrumbs', [
    'items' => [
        ['label' => $club->title ?? 'EXTRASPORT', 'url' => route('home')],
        ['label' => 'Новости'],
    ]
])

@php
$months = ['01'=>'января','02'=>'февраля','03'=>'марта','04'=>'апреля','05'=>'мая','06'=>'июня',
           '07'=>'июля','08'=>'августа','09'=>'сентября','10'=>'октября','11'=>'ноября','12'=>'декабря'];
@endphp

<section class="max-w-7xl mx-auto px-4 pt-10 pb-16">

    <header class="mb-10">
        <h1 class="font-heading font-bold text-4xl md:text-5xl uppercase tracking-tight text-white">Новости</h1>
        <p class="mt-2 text-white/40 text-sm font-heading uppercase tracking-widest">{{ $club->title ?? 'EXTRASPORT' }}</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12">
        @forelse($news as $item)
            <x-ui.card.news :item="$item" :months="$months" />
        @empty
            <p class="text-white/40 col-span-2 text-center py-20 font-heading uppercase tracking-widest">Новостей не найдено</p>
        @endforelse
    </div>

    <x-ui.pagination :paginator="$news" />

</section>

@include('layouts.parts.subscribe')
@endsection
