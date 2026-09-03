/**
 * Deferred analytics injection after cookie consent
 */
import { getCookie } from './cookies.js';

let injected = false;

function injectHtml(html, target = 'head') {
	if (!html?.trim()) {
		return;
	}

	const container = target === 'body' ? document.body : document.head;
	if (!container) {
		return;
	}

	const range = document.createRange();
	range.selectNodeContents(container);
	const fragment = range.createContextualFragment(html);
	container.appendChild(fragment);
}

export function injectAnalytics() {
	if (injected) {
		return;
	}

	const snippets = window.extrasportConfig?.analytics ?? {};

	injectHtml(snippets.head, 'head');
	injectHtml(snippets.body, 'body');
	injectHtml(snippets.yandex, 'body');
	injectHtml(snippets.google, 'body');

	injected = true;
}

export function initAnalytics() {
	if (getCookie('cookieconsent_dismissed')) {
		injectAnalytics();
	}

	document.addEventListener('extrasport:cookie-accepted', injectAnalytics);
}
