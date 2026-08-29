<?php
/**
 * Honeypot field for form anti-spam
 *
 * @package ExtraSport
 *
 * @var string $form_id Unique form identifier for label/input ids.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$form_id = isset( $args['form_id'] ) ? sanitize_key( $args['form_id'] ) : 'form';
?>

<input type="hidden" name="form_token" value="<?php echo esc_attr( extrasport_create_form_token() ); ?>">

<div class="hp-field" aria-hidden="true">
	<label for="website-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Website', 'extrasport' ); ?></label>
	<input
		type="text"
		name="website"
		id="website-<?php echo esc_attr( $form_id ); ?>"
		tabindex="-1"
		autocomplete="off"
		value=""
	>
</div>
