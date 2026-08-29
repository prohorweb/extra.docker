import './input.css';

import { initNav } from './modules/nav.js';
import { initModals } from './modules/modal.js';
import { initChat } from './modules/chat.js';
import { initForms } from './modules/forms.js';
import { initCarousels } from './modules/carousel.js';
import { initMap } from './modules/map.js';

document.addEventListener('DOMContentLoaded', () => {
	initModals();
	initNav();
	initChat();
	initForms();
	initCarousels();
	initMap();
});
