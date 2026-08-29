/**
 * Native carousel — replacement for Bootstrap carousel + wheel navigation
 */
import { updateSiteHeaderState } from './scroll-state.js';

function isCarouselFullyVisible(root) {
	const rect = root.getBoundingClientRect();
	const tolerance = 30;
	return rect.top >= -tolerance && rect.bottom <= window.innerHeight + tolerance;
}

function createScrollLock() {
	let locked = false;
	let scrollY = 0;

	return {
		lock() {
			if (locked) {
				return;
			}

			scrollY = window.scrollY;
			locked = true;
			document.body.dataset.carouselScrollLock = 'true';
			document.body.dataset.carouselScrollY = String(scrollY);
			document.body.style.overflow = 'hidden';
			document.body.style.position = 'fixed';
			document.body.style.width = '100%';
		},
		unlock() {
			if (!locked) {
				return;
			}

			const restoreY = scrollY;
			locked = false;
			delete document.body.dataset.carouselScrollLock;
			delete document.body.dataset.carouselScrollY;
			document.body.style.overflow = '';
			document.body.style.position = '';
			document.body.style.width = '';
			window.scrollTo({ top: restoreY, behavior: 'auto' });
			updateSiteHeaderState();
		},
		isLocked() {
			return locked;
		},
	};
}

function initCarouselWheel(root, { next, prev, getCurrent, getTotal }) {
	const scrollLock = createScrollLock();
	let isScrolling = false;
	let accumulatedDelta = 0;
	const scrollThreshold = parseInt(root.dataset.carouselWheelThreshold || '180', 10);
	const cooldownMs = parseInt(root.dataset.carouselWheelCooldown || '1000', 10);

	const startCooldown = () => {
		isScrolling = true;
		setTimeout(() => {
			isScrolling = false;
		}, cooldownMs);
	};

	const releaseAtBoundary = (goingDown) => {
		accumulatedDelta = 0;
		scrollLock.unlock();

		if (goingDown) {
			window.scrollBy({ top: 50, behavior: 'auto' });
		} else {
			window.scrollTo({ top: 0, behavior: 'auto' });
		}

		updateSiteHeaderState();
	};

	const onWheel = (event) => {
		if (event.ctrlKey || !window.matchMedia('(min-width: 768px)').matches) {
			return;
		}

		const fullyVisible = isCarouselFullyVisible(root);

		if (!fullyVisible) {
			scrollLock.unlock();
			accumulatedDelta = 0;
			updateSiteHeaderState();
			return;
		}

		if (isScrolling) {
			if (scrollLock.isLocked()) {
				event.preventDefault();
			}
			return;
		}

		const currentIndex = getCurrent();
		const totalSlides = getTotal();
		const goingDown = event.deltaY > 0;
		const goingUp = event.deltaY < 0;
		const atLastSlide = currentIndex >= totalSlides - 1;
		const atFirstSlide = currentIndex <= 0;

		// Lock page scroll while hero carousel is in view (Yii2 carousel-wheel behaviour).
		if (!scrollLock.isLocked()) {
			scrollLock.lock();
			updateSiteHeaderState();
		}

		event.preventDefault();
		accumulatedDelta += event.deltaY;

		if (Math.abs(accumulatedDelta) < scrollThreshold) {
			return;
		}

		const direction = accumulatedDelta > 0 ? 1 : -1;
		accumulatedDelta = 0;

		if (direction > 0) {
			if (atLastSlide) {
				releaseAtBoundary(true);
				return;
			}

			next();
			startCooldown();
			return;
		}

		if (atFirstSlide) {
			releaseAtBoundary(false);
			return;
		}

		prev();
		startCooldown();
	};

	const onResize = () => {
		if (!isCarouselFullyVisible(root)) {
			scrollLock.unlock();
			accumulatedDelta = 0;
		}

		updateSiteHeaderState();
	};

	window.addEventListener('wheel', onWheel, { passive: false });
	window.addEventListener('resize', onResize, { passive: true });
	window.addEventListener('beforeunload', () => scrollLock.unlock());

	return () => {
		window.removeEventListener('wheel', onWheel);
		window.removeEventListener('resize', onResize);
		scrollLock.unlock();
	};
}

export function initCarousels() {
	document.querySelectorAll('[data-carousel]').forEach((root) => {
		const slides = root.querySelectorAll('[data-carousel-slide]');
		if (!slides.length) {
			return;
		}

		let current = 0;
		let timer = null;
		const useWheel = root.hasAttribute('data-carousel-wheel');
		const desktopQuery = window.matchMedia('(min-width: 768px)');
		const intervalRaw = root.dataset.carouselInterval;
		const interval = intervalRaw === 'false' || intervalRaw === '0'
			? 0
			: parseInt(intervalRaw || '8000', 10);

		const show = (index) => {
			slides.forEach((slide, i) => {
				slide.classList.toggle('is-active', i === index);
				slide.setAttribute('aria-hidden', i === index ? 'false' : 'true');
			});
			root.querySelectorAll('[data-carousel-dot]').forEach((dot, i) => {
				dot.classList.toggle('is-active', i === index);
				dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
			});
			current = index;
			updateSiteHeaderState();
		};

		const next = () => show((current + 1) % slides.length);
		const prev = () => show((current - 1 + slides.length) % slides.length);

		const restart = () => {
			clearInterval(timer);
			const useInterval = interval > 0 && slides.length > 1 && (!useWheel || !desktopQuery.matches);
			if (useInterval) {
				timer = setInterval(next, interval);
			}
		};

		root.querySelector('[data-carousel-prev]')?.addEventListener('click', () => {
			prev();
			restart();
		});

		root.querySelector('[data-carousel-next]')?.addEventListener('click', () => {
			next();
			restart();
		});

		root.querySelectorAll('[data-carousel-dot]').forEach((dot, i) => {
			dot.addEventListener('click', () => {
				show(i);
				restart();
			});
		});

		show(0);
		restart();
		desktopQuery.addEventListener('change', restart);

		if (useWheel && slides.length > 1) {
			initCarouselWheel(root, {
				next,
				prev,
				getCurrent: () => current,
				getTotal: () => slides.length,
			});
		}
	});
}
