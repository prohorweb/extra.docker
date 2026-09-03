const PARALLAX_FACTOR = 0.1;
const PARALLAX_BUFFER_RATIO = 0.15;

/**
 * Parallax background for actions sections — stretched to block height (Yii2 port).
 */
export function initParallax() {
	const sections = [ ...document.querySelectorAll( '.page-section--actions' ) ].filter(
		( section ) => section.style.backgroundImage && section.style.backgroundImage !== 'none'
	);

	if ( ! sections.length ) {
		return;
	}

	const items = sections.map( ( section ) => setupSection( section ) ).filter( Boolean );

	const update = () => {
		for ( const { section, bg } of items ) {
			const relativeScroll = -section.getBoundingClientRect().top;
			bg.style.transform = `translate3d(0, ${ -( relativeScroll * PARALLAX_FACTOR ) }px, 0)`;
		}
	};

	const syncAll = () => {
		for ( const item of items ) {
			item.sync();
		}
		update();
	};

	syncAll();
	window.addEventListener( 'scroll', update, { passive: true } );

	if ( typeof ResizeObserver !== 'undefined' ) {
		const observer = new ResizeObserver( syncAll );
		for ( const { section } of items ) {
			observer.observe( section );
		}
		return;
	}

	window.addEventListener( 'resize', syncAll, { passive: true } );
}

/**
 * @param {HTMLElement} section
 * @returns {{ section: HTMLElement, bg: HTMLElement, sync: () => void }|null}
 */
function setupSection( section ) {
	const bgImage = section.style.backgroundImage;
	if ( ! bgImage || bgImage === 'none' ) {
		return null;
	}

	section.style.backgroundImage = 'none';

	const bg = document.createElement( 'div' );
	bg.className = 'page-section__parallax-bg';
	bg.style.backgroundImage = bgImage;
	bg.setAttribute( 'aria-hidden', 'true' );
	section.prepend( bg );

	const sync = () => {
		const height = section.offsetHeight;
		const buffer = Math.max( 80, height * PARALLAX_BUFFER_RATIO );

		bg.style.width = '100%';
		bg.style.top = `${ -buffer }px`;
		bg.style.height = `${ height + buffer * 2 }px`;
	};

	return { section, bg, sync };
}
