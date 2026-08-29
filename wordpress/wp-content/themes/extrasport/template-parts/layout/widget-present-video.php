<?php
/**
 * Present video floating widget
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();

if ( empty( $club['present_video_embed'] ) || isset( $_COOKIE['popup-video'] ) ) {
	return;
}
?>

<div class="present-video js-pv fixed bottom-24 right-6 z-30 hidden w-72 overflow-hidden rounded-lg border border-white/20 bg-black shadow-2xl is-minimize is-mute" aria-hidden="true">
	<button type="button" class="present-video__close js-pv-close absolute top-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Close video', 'extrasport' ); ?>">
		<i class="fa-solid fa-xmark" aria-hidden="true"></i>
	</button>
	<button type="button" class="present-video__minimize js-pv-minimize absolute top-2 right-12 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Minimize', 'extrasport' ); ?>">
		<i class="fa-solid fa-minus" aria-hidden="true"></i>
	</button>
	<button type="button" class="present-video__mute js-pv-mute absolute bottom-2 right-2 z-10 hidden h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Unmute', 'extrasport' ); ?>">
		<i class="fa-solid fa-volume-xmark" aria-hidden="true"></i>
	</button>
	<button type="button" class="present-video__volume js-pv-volume absolute bottom-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-brand-primary" aria-label="<?php esc_attr_e( 'Mute', 'extrasport' ); ?>">
		<i class="fa-solid fa-volume-high" aria-hidden="true"></i>
	</button>

	<div class="present-video__embed aspect-video bg-brand-dark">
		<?php echo $club['present_video_embed']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-provided embed HTML ?>
	</div>

	<button type="button" class="present-video__play js-pv-play absolute inset-0 z-[5] flex items-center justify-center bg-black/30">
		<span class="present-video__play-btn flex h-14 w-14 items-center justify-center rounded-full bg-brand-primary/90 text-white">
			<i class="fa-solid fa-play ms-1 text-xl" aria-hidden="true"></i>
		</span>
	</button>
	<button type="button" class="present-video__pause js-pv-pause absolute inset-0 z-[5] hidden cursor-pointer bg-transparent" aria-label="<?php esc_attr_e( 'Pause', 'extrasport' ); ?>"></button>
</div>
