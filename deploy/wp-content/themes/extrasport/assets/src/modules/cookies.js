/**
 * Cookie helpers — native replacement for js-cookie
 */
export function getCookie(name) {
	const match = document.cookie.match(new RegExp(`(?:^|; )${name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}=([^;]*)`));
	return match ? decodeURIComponent(match[1]) : null;
}

export function setCookie(name, value, days = 1) {
	const expires = new Date(Date.now() + days * 864e5).toUTCString();
	document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
}

export function removeCookie(name) {
	document.cookie = `${encodeURIComponent(name)}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax`;
}
