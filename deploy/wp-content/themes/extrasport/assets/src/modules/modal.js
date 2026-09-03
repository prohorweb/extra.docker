/**
 * Modal open/close — native JS replacement for Bootstrap modals
 */
export function initModals() {
	const modals = document.querySelectorAll('.modal');

	const openModal = (id) => {
		const modal = document.getElementById(id);
		if (!modal) return;
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('overflow-hidden');
	};

	const closeModal = (modal) => {
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		if (!document.querySelector('.modal.is-open')) {
			document.body.classList.remove('overflow-hidden');
		}
	};

	document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
		trigger.addEventListener('click', (e) => {
			e.preventDefault();
			const id = trigger.getAttribute('data-modal-open');
			if (id) openModal(id);
		});
	});

	document.querySelectorAll('[data-modal-close]').forEach((trigger) => {
		trigger.addEventListener('click', () => {
			const modal = trigger.closest('.modal');
			if (modal) closeModal(modal);
		});
	});

	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			document.querySelectorAll('.modal.is-open').forEach(closeModal);
		}
	});

	// Auto-close finish popup after 5s when opened
	const finishPopup = document.getElementById('finish-popup');
	if (finishPopup) {
		const observer = new MutationObserver(() => {
			if (finishPopup.classList.contains('is-open')) {
				setTimeout(() => closeModal(finishPopup), 5000);
			}
		});
		observer.observe(finishPopup, { attributes: true, attributeFilter: ['class'] });
	}

	// Expose for forms module
	window.extrasportOpenModal = openModal;
	window.extrasportCloseModal = closeModal;
}
