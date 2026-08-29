<?php
/**
 * Present video floating widget (structure preserved)
 *
 * @package ExtraSport
 */
?>

<div class="present-video js-pv fixed bottom-24 right-6 z-30 hidden w-64 overflow-hidden rounded-lg border border-white/20 bg-black shadow-2xl" aria-hidden="true">
	<button type="button" class="js-pv-close absolute top-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Close video', 'extrasport' ); ?>">
		<i class="fa-solid fa-xmark" aria-hidden="true"></i>
	</button>
	<button type="button" class="js-pv-minimize absolute top-2 right-12 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Minimize', 'extrasport' ); ?>">
		<i class="fa-solid fa-minus" aria-hidden="true"></i>
	</button>
	<div class="present-video__body aspect-video bg-brand-dark flex items-center justify-center">
		<button type="button" class="js-pv-play flex h-12 w-12 items-center justify-center rounded-full bg-brand-primary/90 text-white">
			<i class="fa-solid fa-play ms-1" aria-hidden="true"></i>
		</button>
	</div>
</div>
