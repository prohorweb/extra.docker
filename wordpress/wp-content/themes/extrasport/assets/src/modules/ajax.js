/**
 * Shared AJAX form submit helper
 */
async function submitExtrasportForm(action, formData) {
	const config = window.extrasportConfig ?? {};
	const body = new FormData();
	body.append('action', action);
	body.append('nonce', config.nonce ?? '');

	Object.entries(formData).forEach(([key, value]) => {
		body.append(key, value);
	});

	const response = await fetch(config.ajaxUrl ?? '/wp-admin/admin-ajax.php', {
		method: 'POST',
		body,
		credentials: 'same-origin',
	});

	const data = await response.json();

	if (!response.ok || !data.success) {
		throw new Error(data.data?.message ?? 'Ошибка отправки формы');
	}

	return data;
}

export { submitExtrasportForm };
