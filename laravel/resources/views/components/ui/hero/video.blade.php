@props(['src', 'srcMobile' => null, 'height' => 'h-72 md:h-[520px]', 'overlay' => 'bg-gradient-to-b from-black/30 via-black/40 to-black'])
<section class="relative overflow-hidden {{ $height }}">
    <video muted loop autoplay playsinline
           class="{{ $srcMobile ? 'hidden md:block' : 'block' }} absolute inset-0 w-full h-full object-cover">
        <source src="{{ $src }}" type="video/mp4">
    </video>
    @if($srcMobile)
    <video muted loop autoplay playsinline class="block md:hidden absolute inset-0 w-full h-full object-cover">
        <source src="{{ $srcMobile }}" type="video/mp4">
    </video>
    @endif
    <div class="absolute inset-0 {{ $overlay }}"></div>
    <div class="relative z-10 h-full flex flex-col justify-end max-w-7xl mx-auto px-4 pb-10">
        {{ $slot }}
    </div>
</section>
