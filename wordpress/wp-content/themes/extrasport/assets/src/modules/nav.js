/**
 * Mobile navigation — offcanvas replacement
 */
export function initNav() {
	const toggle = document.getElementById('navToggle');
	const mobileNav = document.getElementById('mobileNav');
	if (!toggle || !mobileNav) return;

	const openNav = () => {
		mobileNav.classList.remove('hidden');
		mobileNav.setAttribute('aria-hidden', 'false');
		toggle.setAttribute('aria-expanded', 'true');
		document.body.classList.add('overflow-hidden');
	};

	const closeNav = () => {
		mobileNav.classList.add('hidden');
		mobileNav.setAttribute('aria-hidden', 'true');
		toggle.setAttribute('aria-expanded', 'false');
		document.body.classList.remove('overflow-hidden');
	};

	toggle.addEventListener('click', () => {
		if (mobileNav.classList.contains('hidden')) {
			openNav();
		} else {
			closeNav();
		}
	});

	mobileNav.querySelectorAll('[data-nav-close]').forEach((el) => {
		el.addEventListener('click', closeNav);
	});

	// Dropdown toggles in mobile menu
	document.querySelectorAll('.nav-dropdown-toggle').forEach((btn) => {
		btn.addEventListener('click', () => {
			const targetId = btn.getAttribute('data-dropdown');
			const menu = document.getElementById(targetId);
			if (!menu) return;
			const isOpen = !menu.classList.contains('hidden');
			menu.classList.toggle('hidden', isOpen);
			btn.setAttribute('aria-expanded', String(!isOpen));
			btn.querySelector('.fa-chevron-down')?.classList.toggle('rotate-180', !isOpen);
		});
	});

	// Close mobile nav when opening a modal
	document.querySelectorAll('[data-modal-open]').forEach((btn) => {
		btn.addEventListener('click', closeNav);
	});
}
