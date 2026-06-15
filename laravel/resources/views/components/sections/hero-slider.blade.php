{{--
  x-sections.hero-slider
  ──────────────────────
  Props:
    array  $banners    — banner items from controller (may be empty)
    string $heading    — main heading on the video slide
    string|null $subheading — club name line
    array  $cta        — ['text' => string, 'url' => string, 'url-mobile' => string?]
    string $video      — path to the fallback background video (.mp4)
--}}
@php
    $heading    = $heading    ?? 'Сеть фитнес клубов на результат!';
    $subheading = $subheading ?? null;
    $video      = $video      ?? asset('video/bg_moution.mp4');
    $cta        = $cta        ?? ['text' => 'Записаться', 'url' => '#callback'];
    $banners    = $banners    ?? [];
    $slideCount = count($banners) + 1; // +1 for the video slide
@endphp

{{--
  Preload first banner image so it's ready before GSAP snaps to slide 1.
  Only emitted when there's at least one image banner.
--}}
@if(!empty($banners[0]['img1200']))
@push('head')
<link rel="preload" as="image"
      href="/uploads/banners/1200/{{ $banners[0]['img1200'] }}"
      fetchpriority="high">
@endpush
@endif

<section
    class="hero-slider relative overflow-hidden overscroll-none"
    aria-roledescription="carousel"
    aria-label="Hero banners"
>
    {{--
      Track
      • flex by default  → horizontal (animated by GSAP)
      • motion-reduce:flex-col  → vertically stacked when user prefers no motion
        (the JS module returns early in that case, so no GSAP initialises)
    --}}
    <div
        class="hero-slider__track flex
               motion-reduce:flex-col
               motion-safe:will-change-transform"
        aria-live="off"
    >

        {{-- ── Slide 0: full-screen video ─────────────────────────── --}}
        <article
            class="hero-slider__slide relative h-screen w-screen shrink-0
                   flex items-center justify-center overflow-hidden
                   motion-reduce:w-full motion-reduce:shrink"
            role="group"
            aria-roledescription="slide"
            aria-label="1 of {{ $slideCount }}"
        >
            {{-- Video background --}}
            <video
                muted loop autoplay playsinline
                class="absolute inset-0 h-full w-full object-cover object-center"
                aria-hidden="true"
            >
                <source src="{{ $video }}" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                <source src="{{ str_replace('.mp4', '.webm', $video) }}" type='video/webm; codecs="vp8, vorbis"'>
            </video>

            {{-- Gradient overlay — strong bottom for text, lighter top --}}
            <div class="absolute inset-0 bg-gradient-to-t
                        from-black/80 via-black/30 to-black/10"
                 aria-hidden="true">
            </div>

            {{-- Content --}}
            <div class="relative z-10 w-full max-w-5xl mx-auto px-6 md:px-10 text-center text-white">
                <h1 class="font-heading font-bold uppercase tracking-tight leading-tight
                           text-[clamp(2rem,5vw,4.5rem)]
                           [text-shadow:0_2px_20px_rgba(0,0,0,.7)]">
                    {{ $heading }}
                </h1>

                @if($subheading)
                <p class="mt-3 md:mt-4 font-heading font-light uppercase
                          text-[clamp(1rem,2.5vw,1.75rem)] text-white/75
                          [text-shadow:0_1px_8px_rgba(0,0,0,.5)]">
                    {{ $subheading }}
                </p>
                @endif

                <div class="mt-8 md:mt-10 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ $cta['url'] }}"
                       class="inline-block px-7 py-3 font-heading font-bold uppercase
                              text-sm md:text-base tracking-wider
                              border border-[var(--color-primary)] text-[var(--color-primary)]
                              bg-transparent transition-colors duration-200
                              hover:bg-[var(--color-primary)] hover:text-black
                              focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--color-primary)]">
                        {{ $cta['text'] }}
                    </a>
                </div>
            </div>

            {{-- Scroll hint — hides once user starts scrolling (CSS only) --}}
            <div class="absolute bottom-16 left-1/2 -translate-x-1/2 z-10
                        hidden md:flex flex-col items-center gap-2 text-white/50
                        animate-bounce motion-reduce:hidden"
                 aria-hidden="true">
                <span class="text-[10px] uppercase tracking-[0.2em] font-heading">Scroll</span>
                <svg width="16" height="24" viewBox="0 0 16 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.75" y="0.75" width="14.5" height="22.5" rx="7.25" stroke="currentColor" stroke-width="1.5"/>
                    <rect x="7" y="4" width="2" height="5" rx="1" fill="currentColor"/>
                </svg>
            </div>
        </article>

        {{-- ── Banner slides ───────────────────────────────────────── --}}
        @foreach($banners as $i => $banner)
        <article
            class="hero-slider__slide relative h-screen w-screen shrink-0
                   flex items-center justify-center overflow-hidden
                   motion-reduce:w-full motion-reduce:shrink"
            role="group"
            aria-roledescription="slide"
            aria-label="{{ $i + 2 }} of {{ $slideCount }}"
        >
            {{-- Background priority: video → img → picture (responsive img1200/img1440) --}}
            @if(!empty($banner['video']))
            <video
                muted loop autoplay playsinline
                class="absolute inset-0 h-full w-full object-cover object-center"
                aria-hidden="true"
            >
                <source src="/uploads/video/{{ $banner['video'] }}" type="video/mp4">
            </video>

            @elseif(!empty($banner['img']))
            <img
                src="{{ $banner['img'] }}"
                alt="{{ $banner['title'] ?? '' }}"
                class="absolute inset-0 h-full w-full object-cover object-center"
                loading="eager"
                fetchpriority="{{ $i === 0 ? 'high' : 'auto' }}"
                decoding="async"
                aria-hidden="true"
            >

            @else
            <picture>
                {{-- Mobile crop (img1440) --}}
                @if(!empty($banner['img1440']))
                <source media="(max-width: 767px)" srcset="/uploads/banners/1440/{{ $banner['img1440'] }}">
                @endif
                {{-- Desktop (img1200) --}}
                <img
                    src="{{ !empty($banner['img1200']) ? '/uploads/banners/1200/'.$banner['img1200'] : 'https://placehold.co/1920x1080/111/222?text=' }}"
                    alt="{{ $banner['title'] ?? '' }}"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    loading="eager"
                    fetchpriority="{{ $i === 0 ? 'high' : 'auto' }}"
                    decoding="async"
                    aria-hidden="true"
                >
            </picture>
            @endif

            {{-- Gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t
                        from-black/80 via-black/30 to-black/10"
                 aria-hidden="true">
            </div>

            {{-- Content --}}
            <div class="relative z-10 w-full max-w-5xl mx-auto px-6 md:px-10 text-center text-white">

                @if(!empty($banner['title2']))
                <p class="mb-3 text-[clamp(0.65rem,1.2vw,0.85rem)] font-heading uppercase
                          tracking-[0.25em] text-[var(--color-primary)]">
                    {{ $banner['title2'] }}
                </p>
                @endif

                <h2 class="font-heading font-bold uppercase tracking-tight leading-tight
                           text-[clamp(1.75rem,4vw,3.75rem)]
                           [text-shadow:0_2px_20px_rgba(0,0,0,.7)]">
                    {{ $banner['title'] ?? '' }}
                </h2>

                @if(!empty($banner['intro']))
                <p class="mt-3 md:mt-4 text-[clamp(0.9rem,1.8vw,1.2rem)]
                          font-light text-white/75 max-w-2xl mx-auto leading-relaxed">
                    {{ $banner['intro'] }}
                </p>
                @endif

                @if(!empty($banner['url']))
                <div class="mt-8 md:mt-10">
                    <a href="{{ $banner['url'] }}"
                       class="inline-block px-7 py-3 font-heading font-bold uppercase
                              text-sm md:text-base tracking-wider
                              border border-[var(--color-primary)] text-[var(--color-primary)]
                              bg-transparent transition-colors duration-200
                              hover:bg-[var(--color-primary)] hover:text-black
                              focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--color-primary)]">
                        Узнать больше
                    </a>
                </div>
                @endif

            </div>
        </article>
        @endforeach

    </div>

    {{-- ── Dot navigation ────────────────────────────────────────────── --}}
    {{--
      Visible only when there are banner slides.
      aria-hidden="true" because dots have no useful label for screen readers;
      keyboard/screen-reader users rely on the section's aria-roledescription.
    --}}
    @if(count($banners) > 0)
    <div
        class="hero-slider__dots
               absolute bottom-6 left-1/2 -translate-x-1/2 z-20
               flex items-center gap-3
               motion-reduce:hidden"
        aria-hidden="true"
        role="tablist"
    >
        @for($d = 0; $d < $slideCount; $d++)
        <button
            type="button"
            class="hero-slider__dot
                   w-2 h-2 rounded-full bg-white opacity-40
                   transition-all duration-300 cursor-pointer
                   hover:opacity-70
                   focus-visible:outline focus-visible:outline-2 focus-visible:outline-white/60"
            data-index="{{ $d }}"
            aria-selected="{{ $d === 0 ? 'true' : 'false' }}"
        ></button>
        @endfor
    </div>
    @endif

</section>

@push('scripts')
    @vite('resources/js/pages/home-slider.js')
@endpush
