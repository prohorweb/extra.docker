/**
 * Mobile navigation — offcanvas replacement
 */
import { updateSiteHeaderState } from './scroll-state.js';

function getContactsNavLinks() {
	return [ ...document.querySelectorAll( 'a[href*="#contacts"]' ) ];
}

function setContactsNavActive( isActive ) {
	getContactsNavLinks().forEach( ( link ) => {
		link.classList.toggle( 'is-active', isActive );
	} );
}

function syncContactsNavState() {
	const section = document.getElementById( 'contacts' );
	const onFrontPage = document.body.classList.contains( 'is-front-page' );

	if ( ! onFrontPage || ! section ) {
		setContactsNavActive( false );
		return;
	}

	if ( window.location.hash === '#contacts' ) {
		setContactsNavActive( true );
		return;
	}

	setContactsNavActive( false );
}

function initContactsNavObserver() {
	const section = document.getElementById( 'contacts' );

	if ( ! section || ! document.body.classList.contains( 'is-front-page' ) ) {
		return;
	}

	const observer = new IntersectionObserver(
		( [ entry ] ) => {
			if ( window.location.hash && window.location.hash !== '#contacts' ) {
				return;
			}

			setContactsNavActive( entry.isIntersecting && entry.intersectionRatio >= 0.35 );
		},
		{
			threshold: [ 0, 0.35, 0.6 ],
		}
	);

	observer.observe( section );
}

export function initNav() {
	const toggle = document.getElementById('navToggle');
	const mobileNav = document.getElementById('mobileNav');

	updateSiteHeaderState();
	window.addEventListener('scroll', updateSiteHeaderState, { passive: true });

	syncContactsNavState();
	window.addEventListener( 'hashchange', syncContactsNavState );
	initContactsNavObserver();

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
