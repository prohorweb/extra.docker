/**
 * Russian phone mask: +7 (999) 999-99-99
 */

const PHONE_DIGIT_COUNT = 10;
const PHONE_PLACEHOLDER = '+7 (___) ___-__-__';
const PHONE_MAX_LENGTH = 18;

/**
 * Extract local phone digits (without country code).
 *
 * @param {string} value Raw input value.
 * @return {string}
 */
export function extractPhoneDigits(value) {
	let digits = String(value ?? '').replace(/\D/g, '');

	if (digits.startsWith('8')) {
		digits = `7${digits.slice(1)}`;
	}

	if (digits.startsWith('7')) {
		digits = digits.slice(1);
	}

	return digits.slice(0, PHONE_DIGIT_COUNT);
}

/**
 * Format local digits as +7 (999) 999-99-99.
 *
 * @param {string} digits Local digits only.
 * @return {string}
 */
export function formatPhoneNumber(digits) {
	const local = digits.slice(0, PHONE_DIGIT_COUNT);
	if (!local.length) {
		return '';
	}

	let formatted = '+7 (';
	formatted += local.slice(0, 3);

	if (local.length >= 3) {
		formatted += ') ';
	}

	formatted += local.slice(3, 6);

	if (local.length >= 6) {
		formatted += '-';
	}

	formatted += local.slice(6, 8);

	if (local.length >= 8) {
		formatted += '-';
	}

	formatted += local.slice(8, 10);

	return formatted;
}

/**
 * Apply phone mask behaviour to a tel input.
 *
 * @param {HTMLInputElement} input
 * @return {void}
 */
export function bindPhoneMask(input) {
	if (!(input instanceof HTMLInputElement) || input.dataset.phoneMaskBound === '1') {
		return;
	}

	input.dataset.phoneMaskBound = '1';
	input.setAttribute('inputmode', 'tel');
	input.maxLength = PHONE_MAX_LENGTH;

	if (!input.placeholder || !input.placeholder.includes('+7')) {
		input.placeholder = PHONE_PLACEHOLDER;
	}

	input.addEventListener('focus', () => {
		if (!extractPhoneDigits(input.value).length && input.value.trim() === '') {
			input.value = '+7 (';
			input.setSelectionRange(input.value.length, input.value.length);
		}
	});

	input.addEventListener('blur', () => {
		if (!extractPhoneDigits(input.value).length) {
			input.value = '';
		}
	});

	input.addEventListener('input', () => {
		const digits = extractPhoneDigits(input.value);

		if (!digits.length) {
			input.value = input.value.includes('(') ? '+7 (' : '';
			return;
		}

		input.value = formatPhoneNumber(digits);
		input.setSelectionRange(input.value.length, input.value.length);
	});

	input.addEventListener('paste', (event) => {
		event.preventDefault();
		const pasted = event.clipboardData?.getData('text') ?? '';
		const digits = extractPhoneDigits(pasted);
		input.value = digits.length ? formatPhoneNumber(digits) : '';
		input.dispatchEvent(new Event('input', { bubbles: true }));
	});
}

/**
 * Bind phone mask to all tel fields in scope.
 *
 * @param {ParentNode} root
 * @return {void}
 */
export function initPhoneMasks(root = document) {
	root.querySelectorAll('input[name="tel"]').forEach((input) => {
		bindPhoneMask(input);
	});
}
