{{--
@component x-sections.hero
@prop string $video — путь к видео
@prop string $poster — постер
@prop string $logo — путь к логотипу
@prop string $heading — заголовок
@prop string|null $subheading — подзаголовок (клуб)
@prop bool $showLogo — показывать логотип (default: true)
@prop array $cta — ['text' => '...', 'url' => '...']
--}}

@php
    $showLogo = $showLogo ?? true;
@endphp

<header class="relative overflow-hidden">
    <video muted loop autoplay playsinline class="w-full h-screen object-cover object-top">
        <source src="{{ $video }}" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="{{ str_replace('.mp4', '.webm', $video) }}" type='video/webm; codecs="vp8, vorbis"'>
    </video>

    <div class="absolute inset-0 flex items-center justify-center h-screen w-full text-center text-azure lg:-mt-[117px]">
        <div class="w-full max-w-7xl mx-auto px-4 animate-fade-in hero-content">
            @if($showLogo)
            <div class="flex justify-center">
                <div class="w-full md:w-1/2">
                    <img class="w-full" src="{{ $logo }}" alt="extrasport logo">
                </div>
            </div>
            @endif

            <h1 class="mt-0 mb-8 max-sm:mb-16 text-hero max-sm:text-[2.5rem] max-sm:leading-[3.5rem] font-heading font-bold uppercase tracking-tight text-accent [text-shadow:var(--shadow-hero-heading)]">
                {{ $heading }}
                @if(!empty($subheading))
                <span class="block mt-0 mb-6 max-sm:mb-8 text-subhero max-sm:text-xl max-sm:leading-9 font-heading font-light uppercase text-white">
                    {{ $subheading }}
                </span>
                @endif
            </h1>

            <div class="hidden md:block">
                <a
                    href="{{ $cta['url'] }}"
                    class="inline-block px-4 py-2 font-heading text-lg font-bold uppercase whitespace-nowrap border border-accent text-accent bg-transparent transition-colors hover:text-azure hover:bg-accent-hover hover:border-accent-border-hover"
                >{{ $cta['text'] }}</a>
            </div>
            <div class="block md:hidden">
                <a
                    href="{{ $cta['url-mobile'] ?? $cta['url'] }}"
                    class="inline-block px-4 py-2 font-heading text-lg font-bold uppercase whitespace-nowrap border border-accent text-accent bg-transparent transition-colors hover:text-azure hover:bg-accent-hover hover:border-accent-border-hover"
                >{{ $cta['text'] }}</a>
            </div>
        </div>
    </div>
</header>
