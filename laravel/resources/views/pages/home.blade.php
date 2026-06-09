@extends('layouts.app')

@section('content')
    <x-sections.hero
        :video="$hero['video'] ?? '/video/bg_moution.mp4'"
        :poster="$hero['poster'] ?? ''"
        :logo="$hero['logo'] ?? asset('img/logo.svg')"
        :heading="$hero['heading'] ?? $club['name'] ?? 'Фитнес клуб'"
        :subheading="$hero['subheading'] ?? null"
        :show-logo="$hero['showLogo'] ?? false"
        :cta="$hero['cta'] ?? ['text' => 'Записаться', 'url' => '#callback']"
    />

    @if(isset($services) && count($services))
        <section class="max-w-7xl mx-auto px-4 py-16">
            <h2 class="text-3xl font-bold text-center mb-10">Наши услуги</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <x-ui.card>
                        <x-slot name="content">
                            <h3 class="text-lg font-bold mb-2">{{ $service['title'] ?? 'Услуга' }}</h3>
                            <p class="text-gray-400 text-sm">{{ $service['description'] ?? '' }}</p>
                        </x-slot>
                    </x-ui.card>
                @endforeach
            </div>
        </section>
    @endif

    <x-sections.cta
        :heading="'Готовы начать?'"
        :description="'Запишитесь на бесплатное пробное занятие'"
        :actions="[['text' => 'Оставить заявку', 'variant' => 'brand', 'url' => '#callback']]"
    />
@endsection
