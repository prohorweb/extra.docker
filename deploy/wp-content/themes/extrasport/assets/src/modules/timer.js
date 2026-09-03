/**
 * Promo timer popup — flip-clock countdown with page-flip animation
 */
import { getCookie, setCookie } from './cookies.js';

const LABELS = {
	hours: 'часов',
	minutes: 'минут',
	seconds: 'секунд',
};

function pad2(value) {
	return String(value).padStart(2, '0');
}

function setDigitInn(li, digit) {
	li.querySelectorAll('.inn').forEach((inn) => {
		inn.textContent = digit;
	});
}

function createFlipDigitElement() {
	const ul = document.createElement('ul');
	ul.className = 'flip';
	ul.innerHTML = `
		<li class="flip-clock-before">
			<a href="#" tabindex="-1" aria-hidden="true">
				<div class="up"><div class="shadow"></div><div class="inn">0</div></div>
				<div class="down"><div class="shadow"></div><div class="inn">0</div></div>
			</a>
		</li>
		<li class="flip-clock-active">
			<a href="#" tabindex="-1" aria-hidden="true">
				<div class="up"><div class="shadow"></div><div class="inn">0</div></div>
				<div class="down"><div class="shadow"></div><div class="inn">0</div></div>
			</a>
		</li>
	`;

	ul.querySelectorAll('a').forEach((link) => {
		link.addEventListener('click', (event) => event.preventDefault());
	});

	return ul;
}

function createFlipDigit() {
	const el = createFlipDigitElement();
	let value = null;
	let resetTimer = null;

	return {
		el,
		setValue(nextDigit) {
			nextDigit = String(nextDigit);

			const before = el.querySelector('.flip-clock-before');
			const active = el.querySelector('.flip-clock-active');

			if (value === null) {
				setDigitInn(before, nextDigit);
				setDigitInn(active, nextDigit);
				value = nextDigit;
				return;
			}

			if (value === nextDigit) {
				return;
			}

			setDigitInn(before, value);
			setDigitInn(active, nextDigit);

			el.classList.remove('play');
			void el.offsetWidth;
			el.classList.add('play');

			clearTimeout(resetTimer);
			resetTimer = setTimeout(() => {
				el.classList.remove('play');
				setDigitInn(before, nextDigit);
				setDigitInn(active, nextDigit);
				value = nextDigit;
			}, 800);
		},
	};
}

class FlipClock {
	constructor(container) {
		this.container = container;
		this.digits = [];
		this.build();
	}

	build() {
		this.container.innerHTML = '';
		this.container.classList.add('clearfix');
		this.digits = [];

		[
			{ label: 'hours', withDots: false },
			{ label: 'minutes', withDots: true },
			{ label: 'seconds', withDots: true },
		].forEach(({ label, withDots }) => {
			const divider = document.createElement('span');
			divider.className = `flip-clock-divider ${label}`;
			divider.innerHTML = `
				<span class="flip-clock-label">${LABELS[label]}</span>
				${withDots ? '<span class="flip-clock-dot top"></span><span class="flip-clock-dot bottom"></span>' : ''}
			`;
			this.container.appendChild(divider);

			for (let i = 0; i < 2; i += 1) {
				const digit = createFlipDigit();
				this.digits.push(digit);
				this.container.appendChild(digit.el);
			}
		});
	}

	setRemaining(endMs) {
		const diff = Math.max(0, endMs - Date.now());
		const totalSec = Math.floor(diff / 1000);
		const hours = Math.min(Math.floor(totalSec / 3600), 99);
		const minutes = Math.floor((totalSec % 3600) / 60);
		const seconds = totalSec % 60;
		const digits = `${pad2(hours)}${pad2(minutes)}${pad2(seconds)}`.split('');

		this.digits.forEach((digit, index) => {
			digit.setValue(digits[index]);
		});
	}
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

	const clock = new FlipClock(timerEl);

	const tick = () => {
		if (Date.now() >= endMs) {
			window.extrasportCloseModal?.(popup);
			return false;
		}

		clock.setRemaining(endMs);
		return true;
	};

	if (!tick()) {
		return;
	}

	const interval = setInterval(() => {
		if (!tick()) {
			clearInterval(interval);
		}
	}, 1000);

	setTimeout(() => {
		window.extrasportOpenModal?.('popup-timer');
	}, 1500);

	popup.querySelectorAll('[data-modal-close]').forEach((btn) => {
		btn.addEventListener('click', () => {
			setCookie('popup-timer', '1', 0.5);
		});
	});
}
