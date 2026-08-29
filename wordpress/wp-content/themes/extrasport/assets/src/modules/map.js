/**
 * Yandex Maps — contacts section
 */
export function initMap() {
	const mapEl = document.getElementById('map');
	if (!mapEl || typeof ymaps === 'undefined') return;

	const coords = (mapEl.dataset.coords || '59.8533,30.3497').split(',').map(Number);
	const markerIcon = mapEl.dataset.marker || '';
	const offset = window.innerWidth >= 768 ? 200 : 0;

	ymaps.ready(() => {
		const map = new ymaps.Map(
			mapEl,
			{
				center: coords,
				zoom: 14,
				controls: [],
			},
			{ suppressMapOpenBlock: true }
		);

		if (offset) {
			const pixelCenter = map.getGlobalPixelCenter();
			const geoCenter = map.options.get('projection').fromGlobalPixels(
				[pixelCenter[0] - offset, pixelCenter[1]],
				map.getZoom()
			);
			map.setCenter(geoCenter);
		}

		map.behaviors.disable('scrollZoom');

		const placemarkOptions = markerIcon
			? {
					iconLayout: 'default#image',
					iconImageHref: markerIcon,
					iconImageSize: [57, 80],
					iconImageOffset: [-28, -80],
			  }
			: {};

		map.geoObjects.add(
			new ymaps.Placemark(coords, { hintContent: 'ExtraSport' }, placemarkOptions)
		);
	});
}
