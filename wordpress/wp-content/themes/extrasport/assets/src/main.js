import './input.css';

import { initNav } from './modules/nav.js';
import { initModals } from './modules/modal.js';
import { initChat } from './modules/chat.js';
import { initForms } from './modules/forms.js';
import { initCarousels } from './modules/carousel.js';
import { initMap } from './modules/map.js';
import { initCookieConsent } from './modules/cookie-consent.js';
import { initPresentVideo } from './modules/present-video.js';
import { initTimer } from './modules/timer.js';
import { initRules } from './modules/rules.js';
import { initAnalytics } from './modules/analytics.js';
import { initParallax } from './modules/parallax.js';

document.addEventListener('DOMContentLoaded', () => {
	initModals();
	initNav();
	initChat();
	initForms();
	initCarousels();
	initMap();
	initAnalytics();
	initCookieConsent();
	initPresentVideo();
	initTimer();
	initRules();
	initParallax();
});
