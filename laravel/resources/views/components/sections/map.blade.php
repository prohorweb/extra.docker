{{--
@component x-sections.map
@prop array $placemarks — массив меток [{lat, lng, hint, icon}] + [{url}]
@prop string $apiKey — ключ Яндекс.Карт (опционально)
--}}

<section class="map-section welcome hieght-100">
    <div class="map-section__map" id="map"></div>
</section>

@push('scripts')
<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script>
    ymaps.ready(init);
    function init () {
        var host = window.location.hostname;

        var myMap = new ymaps.Map('map', {
            center: [{{ $placemarks[0]['lat'] ?? 0 }}, {{ $placemarks[0]['lng'] ?? 0 }}],
            zoom: 11,
            controls: []
        }, { suppressMapOpenBlock: true });

        myMap.behaviors.disable('scrollZoom');

        @foreach($placemarks as $pm)
        var placemark{{ $loop->index }} = new ymaps.Placemark(
            [{{ $pm['lat'] }}, {{ $pm['lng'] }}],
            { hintContent: '{{ $pm['hint'] ?? '' }}' },
            {
                iconLayout: 'default#image',
                iconImageHref: '{{ $pm['icon'] ?? '/images/marker.png' }}',
                iconImageSize: [57, 80],
                iconImageOffset: [-28, -80]
            }
        );
        myMap.geoObjects.add(placemark{{ $loop->index }});
        @if(isset($pm['url']))
        placemark{{ $loop->index }}.events.add('click', function (e) { window.location.href = '{{ $pm['url'] }}'; });
        @endif
        @endforeach

        myMap.setBounds(myMap.geoObjects.getBounds());
    }
</script>
@endpush