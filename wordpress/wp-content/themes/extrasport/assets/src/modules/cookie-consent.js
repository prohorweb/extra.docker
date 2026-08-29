/**
 * Cookie consent banner — native replacement for cookieconsent.min.js
 */
import { getCookie, setCookie } from './cookies.js';

export function initCookieConsent() {
	if (getCookie('cookieconsent_dismissed')) {
		return;
	}

	const config = window.extrasportConfig?.cookieConsent ?? {};
	const message = config.message ?? 'Мы используем файлы cookie для улучшения работы сайта.';
	const dismiss = config.dismiss ?? 'Принять';

	const banner = document.createElement('div');
	banner.className = 'cookie-banner';
	banner.setAttribute('role', 'dialog');
	banner.setAttribute('aria-live', 'polite');
	banner.innerHTML = `
		<div class="cookie-banner__inner">
			<p class="cookie-banner__message">${message}</p>
			<button type="button" class="cookie-banner__btn">${dismiss}</button>
		</div>
	`;

	document.body.appendChild(banner);

	banner.querySelector('.cookie-banner__btn')?.addEventListener('click', () => {
		setCookie('cookieconsent_dismissed', '1', 365);
		banner.remove();
	});
}
