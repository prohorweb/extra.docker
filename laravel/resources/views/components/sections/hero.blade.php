{{--
@component x-sections.hero
@prop string $video — путь к видео
@prop string $poster — постер
@prop string $logo — путь к логотипу
@prop string $heading — заголовок
@prop array $cta — ['text' => '...', 'url' => '...']
--}}

<header class="main" style="overflow: hidden; position: relative;">
    <video muted loop autoplay class="w-100" playsinline>
        <source src="{{ $video }}" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        <source src="{{ str_replace('.mp4', '.webm', $video) }}" type='video/webm; codecs="vp8, vorbis"'>
    </video>

    <div class="masthead d-flex align-items-center mt-0">
        <div class="container fade-in hero-content">
            <div class="d-flex justify-content-center">
                <div class="col-md-6">
                    <img class="w-100" src="{{ $logo }}" alt="extrasport logo">
                </div>
            </div>
            <div class="masthead-heading text-uppercase">{{ $heading }}</div>
            <div class="d-none d-md-block">
                <a class="btn btn-primary btn-xl text-uppercase bg-black" href="{{ $cta['url'] }}">{{ $cta['text'] }}</a>
            </div>
            <div class="d-block d-md-none">
                <a class="btn btn-primary btn-xl text-uppercase bg-black" href="{{ $cta['url-mobile'] ?? $cta['url'] }}">{{ $cta['text'] }}</a>
            </div>
        </div>
    </div>
</header>