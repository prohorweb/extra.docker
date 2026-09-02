/**
 * Shared REST form submit helper
 */
async function submitExtrasportLead(type, formData) {
	const config = window.extrasportConfig ?? {};
	const headers = {
		'Content-Type': 'application/json',
	};

	if (config.isLoggedIn && config.restNonce) {
		headers['X-WP-Nonce'] = config.restNonce;
	}

	const response = await fetch(`${config.restUrl ?? '/wp-json/extrasport/v1/'}lead`, {
		method: 'POST',
		headers,
		body: JSON.stringify({
			type,
			...formData,
		}),
		credentials: 'same-origin',
	});

	let data = {};
	try {
		data = await response.json();
	} catch {
		data = {};
	}

	if (!response.ok) {
		throw new Error(data.message ?? 'Ошибка отправки формы');
	}

	return data;
}

async function submitJobApply(formData) {
	const config = window.extrasportConfig ?? {};
	const headers = {};

	if (config.isLoggedIn && config.restNonce) {
		headers['X-WP-Nonce'] = config.restNonce;
	}

	const response = await fetch(`${config.restUrl ?? '/wp-json/extrasport/v1/'}job-apply`, {
		method: 'POST',
		headers,
		body: formData,
		credentials: 'same-origin',
	});

	let data = {};
	try {
		data = await response.json();
	} catch {
		data = {};
	}

	if (!response.ok) {
		throw new Error(data.message ?? 'Ошибка отправки формы');
	}

	return data;
}

export { submitExtrasportLead, submitJobApply };
