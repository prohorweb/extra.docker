<?php
/**
 * Club rules modal
 *
 * @package ExtraSport
 */

$club       = extrasport_get_club();
$rules_slug = extrasport_get_rules_slug();
$rules_doc  = EXTRASPORT_DIR . '/assets/docs/rules-' . $rules_slug . '.docx';
$rules_url  = file_exists( $rules_doc ) ? EXTRASPORT_URI . '/assets/docs/rules-' . $rules_slug . '.docx' : '';
?>

<div id="rules" class="modal" aria-hidden="true" role="dialog" aria-labelledby="rulesModalTitle">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--lg">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 id="rulesModalTitle" class="font-oswald text-xl uppercase mb-4 pr-8">
			<?php
			printf(
				/* translators: %s: club title suffix */
				esc_html__( 'Правила спортивного клуба «Экстра Спорт» %s', 'extrasport' ),
				esc_html( $club['rules_title_suffix'] ?? '' )
			);
			?>
		</h2>
		<div class="modal__scroll prose prose-invert max-w-none text-sm text-white/80 space-y-3 max-h-[60vh] overflow-y-auto pe-2">
			<?php extrasport_render_rules_content(); ?>
		</div>
		<div class="mt-6 pt-4 border-t border-white/10">
			<?php if ( $rules_url ) : ?>
			<a class="btn-primary btn-lg" href="<?php echo esc_url( $rules_url ); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Скачать', 'extrasport' ); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>
