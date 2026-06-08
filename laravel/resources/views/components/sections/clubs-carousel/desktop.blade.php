{{--
@component x-sections.clubs-carousel.desktop
@prop array $clubs — массив клубов [{name, address, image, url}]
@prop string $target — id для якоря (default: clubs)
--}}

<section class="d-none d-md-block">
    <div class="clubs_container hieght-100">
        <div class="carousel_clubs-container" id="{{ $target ?? 'clubs' }}">
            <div class="carousel_clubs">
                @foreach($clubs as $club)
                <div class="carousel_clubs-item">
                    <a class="card" href="{{ $club['url'] }}">
                        <img src="{{ $club['image'] }}" alt="{{ $club['name'] }}">
                        <div class="card-body p-0">
                            <div class="d-flex">
                                <div class="w-100 py-2">
                                    <h5 class="card-title">{{ $club['name'] }}</h5>
                                    <div class="card-text">{{ $club['address'] }}</div>
                                </div>
                                <div class="btn-arrow d-flex align-items-center">
                                    <i class="fa-sharp fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <button class="prev">❮</button>
            <button class="next">❯</button>
        </div>
    </div>
</section>