<?php
/**
 * Form consent checkbox — privacy policy + marketing.
 *
 * @package ExtraSport
 *
 * @var string $id_prefix Unique prefix for the checkbox id.
 */

$id_prefix = sanitize_key( (string) ( $args['id_prefix'] ?? 'soglas' ) );
$accept_id = $id_prefix;
?>

<div class="form-consent flex items-start gap-2 text-sm">
	<input type="checkbox" name="accept" id="<?php echo esc_attr( $accept_id ); ?>" class="mt-1">
	<label for="<?php echo esc_attr( $accept_id ); ?>">
		<?php echo extrasport_get_form_consent_label_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</label>
</div>
