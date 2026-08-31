/**
 * Page scroll position — accounts for body scroll-lock used by hero carousel.
 */
export function isCarouselScrollLocked() {
	return document.body.dataset.carouselScrollLock === 'true';
}

export function getPageScrollY() {
	if (isCarouselScrollLocked()) {
		return parseInt(document.body.dataset.carouselScrollY || '0', 10);
	}

	return window.scrollY;
}

export function updateSiteHeaderState() {
	const header = document.getElementById('mainNav');
	if (!header) {
		return;
	}

	// Full overlay header while hero carousel is active or fills the viewport.
	if (isCarouselScrollLocked() || isHeroFullyVisible()) {
		header.classList.remove('site-header--fixed');
		return;
	}

	header.classList.toggle('site-header--fixed', window.scrollY > 1);
}

function isHeroFullyVisible() {
	const hero = document.querySelector('.masthead');
	if (!hero) {
		return false;
	}

	const rect = hero.getBoundingClientRect();
	const tolerance = 30;
	return rect.top >= -tolerance && rect.bottom <= window.innerHeight + tolerance;
}
