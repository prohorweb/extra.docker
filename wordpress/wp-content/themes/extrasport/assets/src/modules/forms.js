/**
 * Form validation and REST submit
 */
import { submitExtrasportLead } from './ajax.js';

function getFormPayload(form) {
	const name = form.querySelector('[name="name"]');
	const tel = form.querySelector('[name="tel"]');
	const accept = form.querySelector('[name="accept"]');
	const website = form.querySelector('[name="website"]');
	const formToken = form.querySelector('[name="form_token"]');

	const sourceUrl = form.querySelector('[name="source_url"]');

	return {
		name: name?.value.trim() ?? '',
		tel: tel?.value.trim() ?? '',
		accept: Boolean(accept?.checked),
		website: website?.value.trim() ?? '',
		form_token: formToken?.value.trim() ?? '',
		form_type: form.dataset.formVariant ?? 'test_drive',
		source_url: sourceUrl?.value.trim() ?? '',
	};
}

function validateFormPayload(payload, requireAccept = true) {
	const errors = [];

	if (!payload.name) {
		errors.push('Поле имя не может быть пустым');
	} else if (/[^а-яё ]+/gi.test(payload.name)) {
		errors.push('Поле имя должно содержать только буквы кириллицы');
	}

	if (!payload.tel) {
		errors.push('Поле телефон не может быть пустым');
	} else if (payload.tel.includes('_')) {
		errors.push('Поле телефон заполнено неправильно');
	}

	if (requireAccept && !payload.accept) {
		errors.push('Для продолжения установите флажок «Ознакомлен»');
	}

	return errors;
}

function bindLeadForm(form, options = {}) {
	if (!form) return;

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const errorEl = form.querySelector('.form-error');
		const payload = getFormPayload(form);
		const errors = validateFormPayload(payload, options.requireAccept !== false);

		if (errors.length) {
			if (options.useAlert) {
				alert(errors[0]);
			} else if (errorEl) {
				errorEl.textContent = errors[0];
				errorEl.classList.remove('hidden');
			}
			return;
		}

		if (errorEl) {
			errorEl.classList.add('hidden');
		}

		try {
			await submitExtrasportLead(options.type ?? form.dataset.formType ?? 'subscribe', payload);

			if (typeof options.onSuccess === 'function') {
				options.onSuccess();
			}
		} catch (err) {
			if (options.useAlert) {
				alert(err.message);
			} else if (errorEl) {
				errorEl.textContent = err.message;
				errorEl.classList.remove('hidden');
			}
		}
	});
}

export function initForms() {
	bindLeadForm(document.getElementById('callback'), {
		type: 'callback',
		onSuccess: () => {
			window.extrasportCloseModal?.(document.getElementById('callModal'));
			window.extrasportOpenModal?.('finish-popup');
		},
	});

	document.querySelectorAll('[data-form-type="subscribe"]').forEach((form) => {
		bindLeadForm(form, {
			type: 'subscribe',
			onSuccess: () => {
				window.extrasportOpenModal?.('finish-popup');
			},
		});
	});

	bindLeadForm(document.getElementById('popup-timer-form'), {
		type: 'timer',
		requireAccept: false,
		useAlert: true,
		onSuccess: () => {
			window.extrasportCloseModal?.(document.getElementById('popup-timer'));
			window.extrasportOpenModal?.('finish-popup');
		},
	});
}
