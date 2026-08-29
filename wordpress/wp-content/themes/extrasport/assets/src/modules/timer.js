/**
 * Promo timer popup — native replacement for FlipClock + jQuery timer logic
 */
import { getCookie, setCookie } from './cookies.js';

function renderCountdown(container, endMs) {
	const diff = Math.max(0, endMs - Date.now());
	const totalSec = Math.floor(diff / 1000);
	const days = Math.floor(totalSec / 86400);
	const hours = Math.floor((totalSec % 86400) / 3600);
	const minutes = Math.floor((totalSec % 3600) / 60);
	const seconds = totalSec % 60;

	const pad = (n) => String(n).padStart(2, '0');
	const parts = [];

	if (days > 0) {
		parts.push(`<span class="timer-unit"><span class="timer-value">${days}</span><span class="timer-label">д</span></span>`);
	}
	parts.push(
		`<span class="timer-unit"><span class="timer-value">${pad(hours)}</span><span class="timer-label">ч</span></span>`,
		`<span class="timer-unit"><span class="timer-value">${pad(minutes)}</span><span class="timer-label">м</span></span>`,
		`<span class="timer-unit"><span class="timer-value">${pad(seconds)}</span><span class="timer-label">с</span></span>`
	);

	container.innerHTML = parts.join('');
}

export function initTimer() {
	const popup = document.getElementById('popup-timer');
	const timerEl = document.getElementById('timer');

	if (!popup || !timerEl || getCookie('popup-timer')) {
		return;
	}

	const endMs = parseInt(popup.dataset.timerEnd, 10);
	if (!endMs || endMs <= Date.now()) {
		return;
	}

	const tick = () => {
		if (Date.now() >= endMs) {
			window.extrasportCloseModal?.(popup);
			return;
		}
		renderCountdown(timerEl, endMs);
	};

	tick();
	const interval = setInterval(() => {
		if (Date.now() >= endMs) {
			clearInterval(interval);
			window.extrasportCloseModal?.(popup);
			return;
		}
		renderCountdown(timerEl, endMs);
	}, 1000);

	// Show after short delay so modals module is ready
	setTimeout(() => {
		window.extrasportOpenModal?.('popup-timer');
	}, 1500);

	popup.querySelectorAll('[data-modal-close]').forEach((btn) => {
		btn.addEventListener('click', () => {
			setCookie('popup-timer', '1', 0.5);
		});
	});
}
