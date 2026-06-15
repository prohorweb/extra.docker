@props(['placemarks' => []])

<section class="map-section relative w-full h-[85vh]" id="contacts">

    {{-- Full-bleed map --}}
    <div id="map" class="absolute inset-0 w-full h-full opacity-85"></div>

    {{-- Contacts overlay --}}
    {{ $slot ?? '' }}

</section>

@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ config('services.yandex_maps.key') }}" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ymaps === 'undefined') return;
    if (!document.getElementById('map')) return;

    ymaps.ready(function () {
        var myMap = new ymaps.Map('map', {
            center: [{{ !empty($placemarks) ? $placemarks[0]['coordinates'] : '59.9343, 30.3351' }}],
            zoom: 12,
            controls: []
        }, { suppressMapOpenBlock: true });

        myMap.behaviors.disable('scrollZoom');

        setTimeout(function () {
            var groundPane = document.querySelector('[class*="ground-pane"]');
            if (groundPane) groundPane.style.filter = 'invert(1) hue-rotate(-180deg)';
        }, 500);

        @foreach($placemarks as $placemark)
        (function (coords, hint, icon, url) {
            var pm = new ymaps.Placemark(coords, { hintContent: hint }, {
                iconLayout:      'default#image',
                iconImageHref:   icon,
                iconImageSize:   [57, 80],
                iconImageOffset: [-28, -80]
            });
            pm.events.add('click', function () { if (url !== '#') window.location.href = url; });
            myMap.geoObjects.add(pm);
        })(
            [{{ $placemark['coordinates'] }}],
            '{{ $placemark['hint'] ?? '' }}',
            '{{ $placemark['icon'] ?? '' }}',
            '{{ $placemark['url'] ?? '#' }}'
        );
        @endforeach

        // Only auto-fit when multiple markers — single marker uses the fixed zoom above
        if (myMap.geoObjects.getLength() > 1) {
            myMap.setBounds(myMap.geoObjects.getBounds(), {
                checkZoomRange: true,
                zoomMargin: 80
            });
        }
    });
});
</script>
@endpush
