<?php
/**
 * Membership order modal.
 *
 * @package ExtraSport
 *
 * @var array{title: string, price: string, modal_id: string} $plan Plan data.
 */

$plan = $args['plan'] ?? null;

if ( empty( $plan['modal_id'] ) ) {
	return;
}

$form_id   = sanitize_key( $plan['modal_id'] . '-form' );
$accept_id = 'soglas-' . sanitize_key( $plan['modal_id'] );
$source    = extrasport_get_card_type_url();
?>

<div id="<?php echo esc_attr( $plan['modal_id'] ); ?>" class="modal" aria-hidden="true" role="dialog" aria-labelledby="<?php echo esc_attr( $plan['modal_id'] ); ?>Title">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--sm">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 id="<?php echo esc_attr( $plan['modal_id'] ); ?>Title" class="font-oswald mb-2 text-center text-xl uppercase">
			<?php echo esc_html( $plan['title'] ); ?>
		</h2>
		<?php if ( ! empty( $plan['price'] ) ) : ?>
			<p class="mb-6 text-center font-oswald text-2xl text-brand-primary"><?php echo esc_html( $plan['price'] ); ?></p>
		<?php endif; ?>

		<form
			id="<?php echo esc_attr( $form_id ); ?>"
			class="space-y-4"
			action="#"
			method="post"
			novalidate
			data-form-type="subscribe"
			data-form-variant="membership_card"
		>
			<?php get_template_part( 'components/form', 'honeypot', array( 'form_id' => $form_id ) ); ?>
			<input type="hidden" name="source_url" value="<?php echo esc_attr( $source ); ?>">
			<input type="hidden" name="plan_title" value="<?php echo esc_attr( $plan['title'] ); ?>">
			<input type="text" name="name" class="form-input" placeholder="<?php esc_attr_e( 'Ваше имя *', 'extrasport' ); ?>" autocomplete="name">
			<input type="tel" name="tel" class="form-input" placeholder="<?php esc_attr_e( 'Ваш телефон *', 'extrasport' ); ?>" autocomplete="tel">
			<?php get_template_part( 'components/form', 'consent', array( 'id_prefix' => $accept_id ) ); ?>
			<div class="form-error hidden text-sm text-red-400" role="alert"></div>
			<button type="submit" class="btn-primary btn-lg w-full justify-center"><?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?></button>
		</form>
	</div>
</div>
