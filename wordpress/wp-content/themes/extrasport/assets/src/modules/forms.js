/**
 * Form validation — native JS replacement for jQuery validators
 */
export function initForms() {
	const callbackForm = document.getElementById('callback');
	if (callbackForm) {
		callbackForm.addEventListener('submit', (e) => {
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

			// Placeholder: server handler in future phase
			window.extrasportCloseModal?.(document.getElementById('callModal'));
			window.extrasportOpenModal?.('finish-popup');
		});
	}

	const subscribeForm = document.getElementById('subscribe');
	if (subscribeForm) {
		subscribeForm.addEventListener('submit', (e) => {
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
			window.extrasportOpenModal?.('finish-popup');
		});
	}
}
