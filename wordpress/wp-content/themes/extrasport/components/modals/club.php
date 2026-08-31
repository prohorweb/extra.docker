<?php
/**
 * Club selector modal
 *
 * @package ExtraSport
 */

$clubs = extrasport_get_clubs();
?>

<div id="clubModal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="clubModalTitle">
	<div class="modal__backdrop" data-modal-close></div>
	<div class="modal__panel modal__panel--lg">
		<button type="button" class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'extrasport' ); ?>">
			<i class="fa-solid fa-xmark" aria-hidden="true"></i>
		</button>
		<h2 id="clubModalTitle" class="font-oswald text-2xl uppercase mb-6 text-center"><?php esc_html_e( 'Выберите клуб', 'extrasport' ); ?></h2>
		<div class="space-y-4">
			<?php foreach ( $clubs as $club_item ) : ?>
				<a href="<?php echo esc_url( $club_item['url'] ); ?>" class="club-card group flex items-center justify-between rounded-lg border border-white/10 bg-white/5 p-4 transition hover:border-brand-primary hover:bg-white/10">
					<div>
						<h3 class="font-oswald text-lg uppercase text-white group-hover:text-brand-primary"><?php echo esc_html( $club_item['title'] ); ?></h3>
						<p class="text-sm text-white/70 mt-1"><?php echo esc_html( $club_item['address'] ); ?></p>
					</div>
					<i class="fa-solid fa-arrow-right text-brand-primary opacity-0 group-hover:opacity-100 transition" aria-hidden="true"></i>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
