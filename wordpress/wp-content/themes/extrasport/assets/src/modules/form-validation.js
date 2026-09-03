/**
 * Shared lead form field validation (name + phone)
 */
import { extractPhoneDigits } from './phone-mask.js';

const CYRILLIC_NAME_PATTERN = /[^а-яё ]+/i;
const PHONE_DIGIT_COUNT = 10;

export function validateName(value) {
	const trimmed = String(value ?? '').trim();

	if (!trimmed) {
		return 'Поле имя не может быть пустым';
	}

	if (CYRILLIC_NAME_PATTERN.test(trimmed)) {
		return 'Поле имя должно содержать только буквы кириллицы';
	}

	return '';
}

export function validatePhone(value) {
	const trimmed = String(value ?? '').trim();

	if (!trimmed || trimmed === '+7 (') {
		return 'Поле телефон не может быть пустым';
	}

	const digits = extractPhoneDigits(trimmed);
	if (digits.length < PHONE_DIGIT_COUNT) {
		return 'Поле телефон заполнено неправильно';
	}

	return '';
}

function ensureFieldErrorElement(input) {
	const parent = input.parentElement;
	if (!parent) {
		return null;
	}

	let errorEl = parent.querySelector(':scope > .form-field__error');
	if (!errorEl) {
		errorEl = document.createElement('div');
		errorEl.className = 'form-field__error hidden';
		errorEl.setAttribute('role', 'alert');
		input.insertAdjacentElement('afterend', errorEl);
	}

	return errorEl;
}

function setFieldError(input, message) {
	const errorEl = ensureFieldErrorElement(input);
	if (!errorEl) {
		return;
	}

	input.classList.toggle('is-invalid', Boolean(message));
	input.setAttribute('aria-invalid', message ? 'true' : 'false');

	if (message) {
		errorEl.textContent = message;
		errorEl.classList.remove('hidden');
		return;
	}

	errorEl.textContent = '';
	errorEl.classList.add('hidden');
}

/**
 * Bind live validation for name/tel fields inside a form.
 *
 * @param {HTMLFormElement} form
 * @returns {{ validateAll: () => string, clearErrors: () => void }}
 */
export function bindFormFieldValidation(form) {
	/** @type {Array<{ input: HTMLInputElement, validate: (value: string) => string, touched: boolean }>} */
	const fields = [];

	form.querySelectorAll('[name="name"], [name="tel"]').forEach((input) => {
		if (!(input instanceof HTMLInputElement)) {
			return;
		}

		const validate = input.name === 'name' ? validateName : validatePhone;
		const field = { input, validate, touched: false };
		fields.push(field);

		const showIfNeeded = (force = false) => {
			if (!force && !field.touched) {
				return '';
			}

			const message = validate(input.value);
			setFieldError(input, message);
			return message;
		};

		input.addEventListener('blur', () => {
			field.touched = true;
			showIfNeeded(true);
		});

		input.addEventListener('input', () => {
			if (field.touched) {
				showIfNeeded(true);
			}
		});
	});

	return {
		validateAll() {
			let firstError = '';

			fields.forEach((field) => {
				field.touched = true;
				const message = field.validate(field.input.value);
				setFieldError(field.input, message);
				if (!firstError && message) {
					firstError = message;
				}
			});

			return firstError;
		},
		clearErrors() {
			fields.forEach((field) => {
				setFieldError(field.input, '');
			});
		},
	};
}

export function validateAccept(checked) {
	return checked ? '' : 'Для продолжения установите флажок «Ознакомлен»';
}
