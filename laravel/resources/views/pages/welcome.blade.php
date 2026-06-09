@extends('layouts.app')

@section('content')
    <x-sections.hero
        :video="$hero['video'] ?? '/video/bg_moution.mp4'"
        :poster="$hero['poster'] ?? ''"
        :logo="$hero['logo'] ?? asset('img/logo.svg')"
        :heading="$hero['heading'] ?? 'Сеть фитнес клубов на результат!'"
        :cta="$hero['cta'] ?? ['text' => 'Выберите клуб', 'url' => '#clubs', 'url-mobile' => '#clubs-mobile']"
    />

    <x-sections.clubs-carousel.desktop :clubs="$clubs ?? []" target="clubs" />
    <x-sections.clubs-carousel.mobile :clubs="$clubs ?? []" target="clubs-mobile" />
   
    {{-- Карта --}}
    <x-sections.map :placemarks="$placemarks ?? []" />

@endsection

