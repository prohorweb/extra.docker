/**
 * Present video floating widget — native replacement for present-video.js
 */
import { getCookie, setCookie, removeCookie } from './cookies.js';

export function initPresentVideo() {
	const widget = document.querySelector('.js-pv');
	if (!widget || getCookie('popup-video')) {
		return;
	}

	const video = widget.querySelector('video');
	const playBtn = widget.querySelector('.js-pv-play');
	const pauseBtn = widget.querySelector('.js-pv-pause');
	const closeBtn = widget.querySelector('.js-pv-close');
	const minimizeBtn = widget.querySelector('.js-pv-minimize');
	const muteBtn = widget.querySelector('.js-pv-mute');
	const volumeBtn = widget.querySelector('.js-pv-volume');

	widget.classList.remove('hidden');
	widget.classList.add('is-show', 'is-minimize', 'is-mute');
	widget.setAttribute('aria-hidden', 'false');

	const setMuted = (muted) => {
		if (video) {
			video.muted = muted;
		}
		widget.classList.toggle('is-mute', muted);
	};

	playBtn?.addEventListener('click', () => {
		widget.classList.remove('is-pause');
		video?.play();
	});

	pauseBtn?.addEventListener('click', () => {
		widget.classList.add('is-pause');
		video?.pause();
	});

	minimizeBtn?.addEventListener('click', () => {
		widget.classList.add('is-minimize', 'is-mute');
		setMuted(true);
	});

	muteBtn?.addEventListener('click', () => setMuted(false));
	volumeBtn?.addEventListener('click', () => setMuted(true));

	closeBtn?.addEventListener('click', () => {
		widget.classList.remove('is-show');
		widget.classList.add('hidden');
		widget.setAttribute('aria-hidden', 'true');
		video?.pause();
		setCookie('popup-video', '1', 1);
	});

	document.addEventListener('visibilitychange', () => {
		if (document.visibilityState === 'hidden') {
			removeCookie('popup-video');
		}
	});

	window.addEventListener('pagehide', () => removeCookie('popup-video'));
}
