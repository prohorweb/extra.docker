{{--
@component x-sections.clubs-carousel.desktop
@prop array $clubs — массив клубов [{name, address, image, url}]
@prop string $target — id для якоря (default: clubs)
--}}

{{-- Скрипт для карусели --}}
<script>
let currentIndex = 0;
</script>

<section class="hidden md:block">
    <div
        class="flex justify-center items-center overflow-hidden m-0 h-[90vh] bg-cover bg-center bg-no-repeat bg-blend-overlay"
        style="background-image: url('/img/actions-bg.jpg'); background-color: rgb(0 0 0 / 0.6);"
    >
        <div class="relative w-[600px] h-[400px] clubs-carousel" id="{{ $target ?? 'clubs' }}">
            <div class="carousel_clubs absolute inset-0 flex justify-center items-center transition-transform duration-500 ease-in-out clubs-carousel__track">
                @foreach($clubs as $club)
                <div class="carousel_clubs-item absolute w-full text-center transition-all duration-500 ease-in-out clubs-carousel__slide">
                    <a href="{{ $club['url'] }}" class="group block mb-6 border-0 text-center text-white no-underline bg-card-overlay">
                        <img
                            src="{{ $club['image'] }}"
                            alt="{{ $club['name'] }}"
                            class="w-full h-full object-cover rounded-[var(--radius-club-card)] opacity-75 transition-opacity group-hover:opacity-100"
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
                </div>
                @endforeach
            </div>
            <button type="button" class="prev absolute top-1/2 -translate-y-1/2 -left-[130px] border-0 bg-transparent text-white text-5xl cursor-pointer p-2.5">❮</button>
            <button type="button" class="next absolute top-1/2 -translate-y-1/2 -right-[130px] border-0 bg-transparent text-white text-5xl cursor-pointer p-2.5">❯</button>
        </div>
    </div>
</section>
