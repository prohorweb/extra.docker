/**
 * Yandex Maps — contacts section
 */

function getElementClassName(el) {
	if (!el?.className) {
		return '';
	}

	return String(typeof el.className === 'string' ? el.className : el.className.baseVal ?? '');
}

function isRouteFooterElement(el) {
	const className = getElementClassName(el);
	return className.includes('gotoymaps') || className.includes('gototaxi');
}

function isCopyrightFooterElement(el) {
	const className = getElementClassName(el);

	if (!className || isRouteFooterElement(el)) {
		return false;
	}

	return (
		className.includes('map-copyrights-promo')
		|| className.includes('copyrights__')
		|| /(?:^|[-_])copyright(?:$|[-_])/.test(className)
	);
}

function hideCopyrightLinks(mapEl) {
	mapEl.querySelectorAll('a[href]').forEach((link) => {
		if (isRouteFooterElement(link) || link.closest('[class*="gotoymaps"], [class*="gototaxi"]')) {
			return;
		}

		const href = link.href || '';
		if (/createmap|legal|yandex\.(ru|com)\/maps\/\?|company\/|support\/maps/i.test(href)) {
			link.style.setProperty('display', 'none', 'important');
		}
	});
}

function tuneMapFooter(mapEl) {
	const isMobile = window.matchMedia('(max-width: 767px)').matches;

	mapEl.querySelectorAll('*').forEach((el) => {
		if (isRouteFooterElement(el)) {
			el.style.setProperty('display', isMobile ? 'block' : 'none', 'important');
			return;
		}

		if (isCopyrightFooterElement(el)) {
			el.style.setProperty('display', 'none', 'important');
		}
	});

	hideCopyrightLinks(mapEl);
}

export function initMap() {
	const mapEl = document.getElementById('map');
	if (!mapEl || typeof ymaps === 'undefined') return;

	const coords = (mapEl.dataset.coords || '59.8533,30.3497').split(',').map(Number);
	const markerIcon = mapEl.dataset.marker || '';
	const offset = window.innerWidth >= 768 ? 200 : 0;
	const mobileQuery = window.matchMedia('(max-width: 767px)');

	ymaps.ready(() => {
		const map = new ymaps.Map(
			mapEl,
			{
				center: coords,
				zoom: 14,
				controls: [],
			},
			{
				// Route/taxi footer is toggled in tuneMapFooter(); keep block available on mobile.
				suppressMapOpenBlock: false,
			}
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

		const applyFooter = () => tuneMapFooter(mapEl);
		applyFooter();

		const observer = new MutationObserver(applyFooter);
		observer.observe(mapEl, { childList: true, subtree: true });

		const onViewportChange = () => applyFooter();
		if (typeof mobileQuery.addEventListener === 'function') {
			mobileQuery.addEventListener('change', onViewportChange);
		} else {
			mobileQuery.addListener(onViewportChange);
		}
	});
}
