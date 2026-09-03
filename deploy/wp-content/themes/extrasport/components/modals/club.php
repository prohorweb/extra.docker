<?php
/**
 * Club selector modal
 *
 * @package ExtraSport
 */

$clubs        = extrasport_get_clubs();
$current_slug = extrasport_get_current_club_slug();
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
				<?php
				$is_current = ( $club_item['slug'] ?? '' ) === $current_slug;
				$card_class = 'club-card' . ( $is_current ? ' is-current' : '' );
				?>
				<?php if ( $is_current ) : ?>
					<div class="<?php echo esc_attr( $card_class ); ?>" aria-current="true">
						<div>
							<h3 class="club-card__title font-oswald text-lg uppercase text-brand-primary"><?php echo esc_html( $club_item['title'] ); ?></h3>
							<p class="text-sm text-white/70 mt-1"><?php echo esc_html( $club_item['address'] ); ?></p>
						</div>
						<i class="fa-solid fa-check text-brand-primary" aria-hidden="true"></i>
					</div>
				<?php else : ?>
					<a href="<?php echo esc_url( $club_item['url'] ); ?>" class="<?php echo esc_attr( $card_class ); ?>">
						<div>
							<h3 class="club-card__title font-oswald text-lg uppercase text-white"><?php echo esc_html( $club_item['title'] ); ?></h3>
							<p class="text-sm text-white/70 mt-1"><?php echo esc_html( $club_item['address'] ); ?></p>
						</div>
						<i class="club-card__arrow fa-solid fa-arrow-right text-brand-primary opacity-0 transition" aria-hidden="true"></i>
					</a>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
