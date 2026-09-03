/**
 * Floating chat widget
 */
export function initChat() {
	const widget = document.querySelector('.chat-24');
	if (!widget) return;

	const showBtn = widget.querySelector('.js-chat-show');
	const hideBtn = widget.querySelector('.js-chat-hide');
	const content = widget.querySelector('.chat-24__content');
	const textEl = widget.querySelector('.chat-24__text');

	showBtn?.addEventListener('click', (e) => {
		e.preventDefault();
		showBtn.classList.add('hidden');
		content?.classList.remove('hidden');
	});

	hideBtn?.addEventListener('click', (e) => {
		e.preventDefault();
		showBtn?.classList.remove('hidden');
		content?.classList.add('hidden');
		if (textEl) textEl.textContent = '';
	});

	widget.querySelectorAll('.chat-24__item').forEach((item) => {
		item.addEventListener('mouseenter', () => {
			if (textEl) textEl.textContent = item.getAttribute('data-title') || '';
		});
	});
}
