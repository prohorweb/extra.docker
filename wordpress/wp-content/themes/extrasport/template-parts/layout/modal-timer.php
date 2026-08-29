<?php
/**
 * Timer promo popup
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();

$timer_end_ms = 0;
if ( ! empty( $club['timer_enabled'] ) && ! empty( $club['timer_end'] ) && ! isset( $_COOKIE['popup-timer'] ) ) {
	$end_ts = strtotime( $club['timer_end'] );
	if ( $end_ts && $end_ts > time() ) {
		$timer_end_ms = $end_ts * 1000;
	}
}

if ( ! $timer_end_ms ) {
	return;
}
?>

<div id="popup-timer" class="modal" aria-hidden="true" role="dialog" data-timer-end="<?php echo esc_attr( (string) $timer_end_ms ); ?>">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--md">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 class="font-oswald text-2xl uppercase mb-2 text-center"><?php echo esc_html( $club['timer_title'] ); ?></h2>
		<p class="text-center text-white/70 mb-6"><?php echo esc_html( $club['timer_intro'] ); ?></p>
		<div id="timer" class="flex justify-center gap-3 mb-6 font-oswald text-3xl text-brand-primary" aria-live="polite"></div>
		<form id="popup-timer-form" class="grid gap-3 sm:grid-cols-3" action="#" method="post" novalidate data-form-type="timer">
			<?php get_template_part( 'template-parts/layout/form', 'honeypot', array( 'form_id' => 'timer' ) ); ?>
			<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Имя *', 'extrasport' ); ?>" required>
			<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Телефон *', 'extrasport' ); ?>" required>
			<button type="submit" class="btn-primary justify-center"><?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?></button>
		</form>
	</div>
</div>
