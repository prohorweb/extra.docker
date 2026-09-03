/**
 * Form validation and REST submit
 */
import { submitExtrasportLead } from './ajax.js';
import { bindFormFieldValidation, validateAccept } from './form-validation.js';

function getFormPayload(form) {
	const name = form.querySelector('[name="name"]');
	const tel = form.querySelector('[name="tel"]');
	const accept = form.querySelector('[name="accept"]');
	const website = form.querySelector('[name="website"]');
	const formToken = form.querySelector('[name="form_token"]');

	const sourceUrl = form.querySelector('[name="source_url"]');
	const planTitle = form.querySelector('[name="plan_title"]');

	return {
		name: name?.value.trim() ?? '',
		tel: tel?.value.trim() ?? '',
		accept: Boolean(accept?.checked),
		website: website?.value.trim() ?? '',
		form_token: formToken?.value.trim() ?? '',
		form_type: form.dataset.formVariant ?? 'test_drive',
		source_url: sourceUrl?.value.trim() ?? '',
		plan_title: planTitle?.value.trim() ?? '',
	};
}

function setFormError(form, message) {
	const errorEl = form.querySelector('.form-error');
	if (!errorEl) {
		return;
	}

	if (message) {
		errorEl.textContent = message;
		errorEl.classList.remove('hidden');
		return;
	}

	errorEl.textContent = '';
	errorEl.classList.add('hidden');
}

function bindLeadForm(form, options = {}) {
	if (!form) return;

	const fieldValidation = bindFormFieldValidation(form);
	const acceptInput = form.querySelector('[name="accept"]');

	acceptInput?.addEventListener('change', () => {
		if (acceptInput.checked) {
			setFormError(form, '');
		}
	});

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const payload = getFormPayload(form);
		const fieldError = fieldValidation.validateAll();
		const acceptError = options.requireAccept === false ? '' : validateAccept(payload.accept);
		const firstError = fieldError || acceptError;

		if (firstError) {
			if (acceptError && !fieldError) {
				setFormError(form, acceptError);
			} else {
				setFormError(form, '');
			}
			return;
		}

		setFormError(form, '');

		try {
			await submitExtrasportLead(options.type ?? form.dataset.formType ?? 'subscribe', payload);

			fieldValidation.clearErrors();
			if (typeof options.onSuccess === 'function') {
				options.onSuccess();
			}
		} catch (err) {
			setFormError(form, err.message);
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
				document.querySelectorAll('.modal.is-open').forEach((modal) => {
					window.extrasportCloseModal?.(modal);
				});
				window.extrasportOpenModal?.('finish-popup');
			},
		});
	});

	bindLeadForm(document.getElementById('popup-timer-form'), {
		type: 'timer',
		onSuccess: () => {
			if (typeof window.dataLayer !== 'undefined') {
				window.dataLayer.push({ event: 'timer' });
			}
			window.extrasportCloseModal?.(document.getElementById('popup-timer'));
			window.extrasportOpenModal?.('finish-popup');
		},
	});
}
