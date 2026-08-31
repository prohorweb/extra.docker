<?php
/**
 * Callback request modal
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<div id="callModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="callModalTitle">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--sm">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 id="callModalTitle" class="font-oswald text-xl uppercase mb-6 text-center"><?php esc_html_e( 'Обратный звонок', 'extrasport' ); ?></h2>

		<form id="callback" class="space-y-4" action="#" method="post" novalidate data-form-type="callback">
			<?php get_template_part( 'components/form', 'honeypot', array( 'form_id' => 'callback' ) ); ?>
			<div class="form-group">
				<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
			</div>
			<div class="form-group">
				<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
			</div>
			<div class="form-group flex items-start gap-2 text-sm">
				<input type="checkbox" name="accept" id="soglas-callback" class="mt-1">
				<label for="soglas-callback">
					<?php
					printf(
						/* translators: %s: privacy policy URL */
						wp_kses_post( __( 'Ознакомлен с <a href="%s" target="_blank" rel="noopener noreferrer">политикой конфиденциальности</a>', 'extrasport' ) ),
						esc_url( $club['privacy_url'] )
					);
					?>
				</label>
			</div>
			<div class="form-error hidden text-sm text-red-400" role="alert"></div>
			<button type="submit" class="btn-primary w-full justify-center"><?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?></button>
		</form>
	</div>
</div>
