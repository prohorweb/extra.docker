{{--
@component x-sections.clubs-carousel.mobile
@prop array $clubs — массив клубов [{name, address, image, url}]
@prop string $target — id для якоря
--}}

<section class="clubs_container py-5 d-block d-md-none" id="{{ $target ?? 'clubs-mobile' }}">
    <div class="container">
        <div class="row text-center">
            @foreach($clubs as $club)
            <div class="col-lg-4 col-md-6 mb-4">
                <a class="card" href="{{ $club['url'] }}">
                    <img class="card-img-top" src="{{ $club['image'] }}" alt="{{ $club['name'] }}">
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
    </div>
</section>