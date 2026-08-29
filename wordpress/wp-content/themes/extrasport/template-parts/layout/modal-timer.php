<?php
/**
 * Timer promo popup (structure preserved, hidden by default)
 *
 * @package ExtraSport
 */
?>

<div id="popup-timer" class="modal" aria-hidden="true" role="dialog" data-timer-end="0">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--md">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 class="font-oswald text-2xl uppercase mb-2 text-center"><?php esc_html_e( 'Специальное предложение', 'extrasport' ); ?></h2>
		<p class="text-center text-white/70 mb-6"><?php esc_html_e( 'Оставьте заявку до окончания акции', 'extrasport' ); ?></p>
		<div id="timer" class="flex justify-center gap-4 mb-6 font-oswald text-3xl text-brand-primary" aria-live="polite"></div>
		<form id="popup-timer-form" class="grid gap-3 sm:grid-cols-3" action="#" method="post" novalidate>
			<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Имя *', 'extrasport' ); ?>" required>
			<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Телефон *', 'extrasport' ); ?>" required>
			<button type="submit" class="btn-primary justify-center"><?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?></button>
		</form>
	</div>
</div>
