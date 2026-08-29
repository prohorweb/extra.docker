/**
 * Lazy-load club rules modal content via REST API
 */
export function initRules() {
	const modal = document.getElementById('rules');
	const container = modal?.querySelector('[data-rules-content]');

	if (!modal || !container) {
		return;
	}

	let loaded = false;
	let loading = false;

	const loadRules = async () => {
		if (loaded || loading) {
			return;
		}

		loading = true;

		try {
			const config = window.extrasportConfig ?? {};
			const response = await fetch(`${config.restUrl ?? '/wp-json/extrasport/v1/'}rules`, {
				credentials: 'same-origin',
			});
			const data = await response.json();

			if (!response.ok) {
				throw new Error(data.message ?? 'load failed');
			}

			container.innerHTML = data.html ?? '';
			loaded = true;
		} catch {
			container.innerHTML = '<p>Не удалось загрузить правила. Попробуйте позже.</p>';
		} finally {
			loading = false;
		}
	};

	document.querySelectorAll('[data-modal-open="rules"]').forEach((trigger) => {
		trigger.addEventListener('click', loadRules);
	});
}
