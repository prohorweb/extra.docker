/**
 * Form validation and AJAX submit — native JS replacement for jQuery validators
 */
import { submitExtrasportForm } from './ajax.js';

export function initForms() {
	const callbackForm = document.getElementById('callback');
	if (callbackForm) {
		callbackForm.addEventListener('submit', async (e) => {
			e.preventDefault();
			const errorEl = callbackForm.querySelector('.form-error');
			const name = callbackForm.querySelector('[name="name"]');
			const tel = callbackForm.querySelector('[name="tel"]');
			const accept = callbackForm.querySelector('[name="accept"]');
			const errors = [];

			if (!name?.value.trim()) {
				errors.push('Поле имя не может быть пустым');
			} else if (/[^а-яё ]+/gi.test(name.value)) {
				errors.push('Поле имя должно содержать только буквы кириллицы');
			}

			if (!tel?.value.trim()) {
				errors.push('Поле телефон не может быть пустым');
			} else if (tel.value.includes('_')) {
				errors.push('Поле телефон заполнено неправильно');
			}

			if (!accept?.checked) {
				errors.push('Для продолжения установите флажок «Ознакомлен»');
			}

			if (errors.length && errorEl) {
				errorEl.textContent = errors[0];
				errorEl.classList.remove('hidden');
				return;
			}

			if (errorEl) errorEl.classList.add('hidden');

			try {
				await submitExtrasportForm('extrasport_submit_callback', {
					name: name.value.trim(),
					tel: tel.value.trim(),
					accept: accept?.checked ? '1' : '',
				});
				window.extrasportCloseModal?.(document.getElementById('callModal'));
				window.extrasportOpenModal?.('finish-popup');
			} catch (err) {
				if (errorEl) {
					errorEl.textContent = err.message;
					errorEl.classList.remove('hidden');
				}
			}
		});
	}

	const subscribeForm = document.getElementById('subscribe');
	if (subscribeForm) {
		subscribeForm.addEventListener('submit', async (e) => {
			e.preventDefault();
			const errorEl = subscribeForm.querySelector('.form-error');
			const name = subscribeForm.querySelector('[name="name"]');
			const tel = subscribeForm.querySelector('[name="tel"]');
			const accept = subscribeForm.querySelector('[name="accept"]');
			const errors = [];

			if (!name?.value.trim()) {
				errors.push('Поле имя не может быть пустым');
			} else if (/[^а-яё ]+/gi.test(name.value)) {
				errors.push('Поле имя должно содержать только буквы кириллицы');
			}

			if (!tel?.value.trim()) {
				errors.push('Поле телефон не может быть пустым');
			}

			if (!accept?.checked) {
				errors.push('Для продолжения установите флажок «Ознакомлен»');
			}

			if (errors.length && errorEl) {
				errorEl.textContent = errors[0];
				errorEl.classList.remove('hidden');
				return;
			}

			if (errorEl) errorEl.classList.add('hidden');

			try {
				await submitExtrasportForm('extrasport_submit_subscribe', {
					name: name.value.trim(),
					tel: tel.value.trim(),
					accept: accept?.checked ? '1' : '',
					form_type: 'subscribe',
				});
				window.extrasportOpenModal?.('finish-popup');
			} catch (err) {
				if (errorEl) {
					errorEl.textContent = err.message;
					errorEl.classList.remove('hidden');
				}
			}
		});
	}

	const timerForm = document.getElementById('popup-timer-form');
	if (timerForm) {
		timerForm.addEventListener('submit', async (e) => {
			e.preventDefault();
			const name = timerForm.querySelector('[name="name"]');
			const tel = timerForm.querySelector('[name="tel"]');
			const errors = [];

			if (!name?.value.trim()) {
				errors.push('Поле имя не может быть пустым');
			} else if (/[^а-яё ]+/gi.test(name.value)) {
				errors.push('Поле имя должно содержать только буквы кириллицы');
			}

			if (!tel?.value.trim()) {
				errors.push('Поле телефон не может быть пустым');
			}

			if (errors.length) {
				alert(errors[0]);
				return;
			}

			try {
				await submitExtrasportForm('extrasport_submit_timer', {
					name: name.value.trim(),
					tel: tel.value.trim(),
				});
				window.extrasportCloseModal?.(document.getElementById('popup-timer'));
				window.extrasportOpenModal?.('finish-popup');
			} catch (err) {
				alert(err.message);
			}
		});
	}
}
