import { getPageScrollY } from './scroll-state.js';

/**
 * Parallax background for the actions section (Yii2 parallax.js port).
 */
export function initParallax() {
	const actions = document.getElementById('actions');
	if (!actions) {
		return;
	}

	const update = () => {
		actions.style.backgroundPositionY = `${-(getPageScrollY() * 0.1)}px`;
	};

	update();
	window.addEventListener('scroll', update, { passive: true });
}
