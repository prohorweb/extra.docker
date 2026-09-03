/**
 * Job vacancies page — detail modal → apply modal flow
 */
import { submitJobApply } from './ajax.js';
import { bindFormFieldValidation, validateAccept } from './form-validation.js';

const RESUME_MAX_BYTES = 100 * 1024;

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

function validateJobApplyExtras(form) {
	const accept = form.querySelector('[name="accept"]')?.checked ?? false;
	const fileInput = form.querySelector('[name="rezume"]');
	const file = fileInput?.files?.[0];

	if (!file) {
		return 'Для продолжения, пожалуйста, прикрепите резюме';
	}

	if (file.size > RESUME_MAX_BYTES) {
		return 'Вес файла более 100 Кб';
	}

	return validateAccept(accept);
}

function bindJobApplyTriggers() {
	document.querySelectorAll('[data-job-apply]').forEach((trigger) => {
		trigger.addEventListener('click', () => {
			const title = trigger.dataset.jobTitle ?? '';
			const form = document.getElementById('jobApplyForm');
			const titleInput = form?.querySelector('[name="title"]');
			const vacancyWrap = document.getElementById('jobApplyVacancyWrap');
			const vacancyTitleEl = document.getElementById('jobApplyVacancyTitle');
			const detailModal = trigger.closest('.modal');

			if (titleInput) {
				titleInput.value = title;
			}
			if (vacancyTitleEl) {
				vacancyTitleEl.textContent = title;
			}
			if (vacancyWrap) {
				vacancyWrap.classList.toggle('hidden', !title);
			}

			if (detailModal) {
				window.extrasportCloseModal?.(detailModal);
			}

			window.extrasportOpenModal?.('jobApplyModal');
		});
	});
}

function bindJobApplyForm() {
	const form = document.getElementById('jobApplyForm');
	if (!form) return;

	const fieldValidation = bindFormFieldValidation(form);
	const fileInput = form.querySelector('[name="rezume"]');
	const fileNameEl = form.querySelector('.job-apply__file-name');
	const acceptInput = form.querySelector('[name="accept"]');

	acceptInput?.addEventListener('change', () => {
		if (acceptInput.checked) {
			setFormError(form, '');
		}
	});

	fileInput?.addEventListener('change', () => {
		const file = fileInput.files?.[0];
		if (!file) {
			if (fileNameEl) fileNameEl.textContent = '';
			return;
		}

		if (file.size > RESUME_MAX_BYTES) {
			setFormError(form, 'Вес файла более 100 Кб');
			fileInput.value = '';
			if (fileNameEl) fileNameEl.textContent = '';
			return;
		}

		setFormError(form, '');
		if (fileNameEl) {
			fileNameEl.textContent = file.name;
		}
	});

	form.addEventListener('submit', async (event) => {
		event.preventDefault();

		const fieldError = fieldValidation.validateAll();
		const extraError = validateJobApplyExtras(form);
		const error = fieldError || extraError;
		if (error) {
			if (!fieldError) {
				setFormError(form, error);
			} else {
				setFormError(form, '');
			}
			return;
		}

		setFormError(form, '');

		const titleInput = form.querySelector('[name="title"]');
		if (!titleInput?.value.trim()) {
			setFormError(form, 'Не указана вакансия. Обновите страницу и попробуйте снова.');
			return;
		}

		const formData = new FormData(form);

		try {
			await submitJobApply(formData);
			form.reset();
			fieldValidation.clearErrors();
			if (fileNameEl) {
				fileNameEl.textContent = '';
			}
			document.getElementById('jobApplyVacancyWrap')?.classList.add('hidden');
			const vacancyTitleEl = document.getElementById('jobApplyVacancyTitle');
			if (vacancyTitleEl) {
				vacancyTitleEl.textContent = '';
			}
			window.extrasportCloseModal?.(document.getElementById('jobApplyModal'));
			window.extrasportOpenModal?.('finish-popup');

			if (typeof window.dataLayer !== 'undefined') {
				window.dataLayer.push({ event: 'zayavka' });
			}
		} catch (err) {
			setFormError(form, err.message ?? 'Ошибка отправки формы');
		}
	});
}

export function initJobs() {
	bindJobApplyTriggers();
	bindJobApplyForm();
}
