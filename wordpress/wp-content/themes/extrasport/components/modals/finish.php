<?php
/**
 * Form success popup
 *
 * @package ExtraSport
 */
?>

<div id="finish-popup" class="modal" aria-hidden="true" role="dialog">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--sm text-center">
		<div class="text-xl font-oswald uppercase mb-3"><?php esc_html_e( 'Спасибо, ваша заявка отправлена!', 'extrasport' ); ?></div>
		<p class="text-white/80 mb-2"><?php esc_html_e( 'В ближайшее время мы вам перезвоним.', 'extrasport' ); ?></p>
		<p class="text-white/60 text-sm"><?php esc_html_e( 'Данное окно закроется автоматически через 5 секунд', 'extrasport' ); ?></p>
	</div>
</div>
