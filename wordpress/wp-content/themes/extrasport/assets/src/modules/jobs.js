/**
 * Job vacancies page — detail modal → apply modal flow
 */
import { submitJobApply } from './ajax.js';

const RESUME_MAX_BYTES = 100 * 1024;

function validateJobApplyForm(form) {
	const name = form.querySelector('[name="name"]')?.value.trim() ?? '';
	const tel = form.querySelector('[name="tel"]')?.value.trim() ?? '';
	const accept = form.querySelector('[name="accept"]')?.checked ?? false;
	const fileInput = form.querySelector('[name="rezume"]');
	const file = fileInput?.files?.[0];

	if (!name) {
		return 'Поле имя не может быть пустым';
	}
	if (/[^а-яё ]+/gi.test(name)) {
		return 'Поле имя должно содержать только буквы кириллицы';
	}
	if (!tel) {
		return 'Поле телефон не может быть пустым';
	}
	if (tel.includes('_')) {
		return 'Поле телефон заполнено неправильно';
	}
	if (!file) {
		return 'Для продолжения, пожалуйста, прикрепите резюме';
	}
	if (file.size > RESUME_MAX_BYTES) {
		return 'Вес файла более 100 Кб';
	}
	if (!accept) {
		return 'Для продолжения установите флажок «Ознакомлен»';
	}

	return '';
}

function bindJobApplyTriggers() {
	document.querySelectorAll('[data-job-apply]').forEach((trigger) => {
		trigger.addEventListener('click', () => {
			const title = trigger.dataset.jobTitle ?? '';
			const form = document.getElementById('jobApplyForm');
			const titleInput = form?.querySelector('[name="title"]');
			const detailModal = trigger.closest('.modal');

			if (titleInput) {
				titleInput.value = title;
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

	const fileInput = form.querySelector('[name="rezume"]');
	const fileNameEl = form.querySelector('.job-apply__file-name');
	const errorEl = form.querySelector('.form-error');

	fileInput?.addEventListener('change', () => {
		const file = fileInput.files?.[0];
		if (!file) {
			if (fileNameEl) fileNameEl.textContent = '';
			return;
		}

		if (file.size > RESUME_MAX_BYTES) {
			if (errorEl) {
				errorEl.textContent = 'Вес файла более 100 Кб';
				errorEl.classList.remove('hidden');
			}
			fileInput.value = '';
			if (fileNameEl) fileNameEl.textContent = '';
			return;
		}

		if (errorEl) {
			errorEl.classList.add('hidden');
		}
		if (fileNameEl) {
			fileNameEl.textContent = file.name;
		}
	});

	form.addEventListener('submit', async (event) => {
		event.preventDefault();

		const error = validateJobApplyForm(form);
		if (error) {
			if (errorEl) {
				errorEl.textContent = error;
				errorEl.classList.remove('hidden');
			}
			return;
		}

		if (errorEl) {
			errorEl.classList.add('hidden');
		}

		const formData = new FormData(form);

		try {
			await submitJobApply(formData);
			form.reset();
			if (fileNameEl) {
				fileNameEl.textContent = '';
			}
			window.extrasportCloseModal?.(document.getElementById('jobApplyModal'));
			window.extrasportOpenModal?.('finish-popup');

			if (typeof window.dataLayer !== 'undefined') {
				window.dataLayer.push({ event: 'zayavka' });
			}
		} catch (err) {
			if (errorEl) {
				errorEl.textContent = err.message ?? 'Ошибка отправки формы';
				errorEl.classList.remove('hidden');
			}
		}
	});
}

export function initJobs() {
	bindJobApplyTriggers();
	bindJobApplyForm();
}
