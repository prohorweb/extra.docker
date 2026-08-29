/**
 * Native carousel — replacement for Bootstrap carousel
 */
export function initCarousels() {
	document.querySelectorAll('[data-carousel]').forEach((root) => {
		const slides = root.querySelectorAll('[data-carousel-slide]');
		if (slides.length <= 1) return;

		let current = 0;
		let timer = null;
		const interval = parseInt(root.dataset.carouselInterval || '8000', 10);

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
		};

		const next = () => show((current + 1) % slides.length);
		const prev = () => show((current - 1 + slides.length) % slides.length);

		const restart = () => {
			clearInterval(timer);
			timer = setInterval(next, interval);
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
	});
}
