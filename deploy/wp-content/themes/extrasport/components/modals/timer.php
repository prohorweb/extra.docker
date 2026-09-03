<?php
/**
 * Timer promo popup
 *
 * @package ExtraSport
 */

if ( ! extrasport_is_timer_active() ) {
	return;
}

$timer_end_ms = extrasport_get_timer_end_ms();

if ( ! $timer_end_ms ) {
	return;
}

$club = extrasport_get_club();
?>

<div id="popup-timer" class="modal modal--timer" aria-hidden="true" role="dialog" data-timer-end="<?php echo esc_attr( (string) $timer_end_ms ); ?>">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--timer">
		<button type="button" class="modal__close modal__close--timer" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 class="timer-popup__title"><?php echo esc_html( $club['timer_title'] ); ?></h2>
		<p class="timer-popup__desc"><?php echo esc_html( $club['timer_intro'] ); ?></p>
		<div class="timer-popup__clock">
			<div id="timer" class="flip-clock-wrapper" aria-live="polite"></div>
		</div>
		<form id="popup-timer-form" class="timer-popup__form" action="#" method="post" novalidate data-form-type="timer">
			<?php get_template_part( 'components/form', 'honeypot', array( 'form_id' => 'timer' ) ); ?>
			<input type="hidden" name="source_url" value="<?php echo esc_url( home_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ) ); ?>">
			<div class="timer-popup__fields">
				<div class="timer-popup__field">
					<input type="text" name="name" class="timer-popup__input" placeholder="<?php esc_attr_e( 'Имя *', 'extrasport' ); ?>" autocomplete="name">
				</div>
				<div class="timer-popup__field">
					<input type="tel" name="tel" class="timer-popup__input" placeholder="<?php esc_attr_e( 'Телефон *', 'extrasport' ); ?>" autocomplete="tel">
				</div>
				<button type="submit" class="timer-popup__submit"><?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?></button>
			</div>
			<?php get_template_part( 'components/form', 'consent', array( 'id_prefix' => 'soglas-timer' ) ); ?>
			<div class="form-error hidden text-sm text-red-200" role="alert"></div>
		</form>
	</div>
</div>
