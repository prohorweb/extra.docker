{{--
@component x-sections.clubs-carousel.mobile
@prop array $clubs — массив клубов [{name, address, image, url}]
@prop string $target — id для якоря
--}}

<section class="py-12 md:hidden" id="{{ $target ?? 'clubs-mobile' }}">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 text-center gap-0">
            @foreach($clubs as $club)
            <a href="{{ $club['url'] }}" class="group block mb-6 border-0 text-center text-white no-underline bg-card-overlay">
                <img
                    src="{{ $club['image'] }}"
                    alt="{{ $club['name'] }}"
                    class="w-full opacity-75 transition-opacity group-hover:opacity-100"
                >
                <div class="p-0">
                    <div class="flex group-hover:bg-accent group-hover:text-azure transition-colors">
                        <div class="w-full py-2">
                            <h5 class="uppercase font-heading m-0">{{ $club['name'] }}</h5>
                            <div class="text-sm">{{ $club['address'] }}</div>
                        </div>
                        <div class="flex items-center bg-accent text-surface-body text-5xl px-4 group-hover:text-azure transition-colors">
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
