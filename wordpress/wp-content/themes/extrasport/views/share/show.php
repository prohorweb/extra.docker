<?php
/**
 * Single share view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$uri  = EXTRASPORT_URI;

while ( have_posts() ) {
	the_post();

	$share_id   = get_the_ID();
	$share_data = extrasport_normalize_share_post( get_post() );
	$intro      = extrasport_get_share_intro( $share_id );
	$permalink  = get_permalink();
	$other      = extrasport_get_other_shares( $share_id, 6 );
	?>
	<section
		id="actions"
		class="page-section page-section--actions page-section--actions-list"
		style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
	>
		<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6 py-12 md:py-16">
			<?php
			get_template_part(
				'components/breadcrumbs',
				null,
				array(
					'items' => array(
						array(
							'label' => $club['title'],
							'url'   => home_url( '/' ),
						),
						array(
							'label' => __( 'Акции', 'extrasport' ),
							'url'   => get_post_type_archive_link( 'share' ),
						),
						array(
							'label' => get_the_title(),
						),
					),
				)
			);
			?>

			<h1 class="section-heading mb-8 md:mb-10"><?php the_title(); ?></h1>

			<div class="share-page__panel">
				<div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
					<div class="share-page__media">
						<div class="share-card share-card--static">
							<?php if ( ! empty( $share_data['date'] ) ) : ?>
								<div class="date-action"><?php echo esc_html( $share_data['date'] ); ?></div>
							<?php endif; ?>
							<div class="share-card__media">
								<?php if ( ! empty( $share_data['image'] ) ) : ?>
									<img class="card-img-top" src="<?php echo esc_url( $share_data['image'] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php else : ?>
									<div class="card-img-top bg-white/10"></div>
								<?php endif; ?>
							</div>
						</div>

						<div class="share-page__footer mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
							<div class="flex items-center gap-3 text-sm text-white/80">
								<span><?php esc_html_e( 'Поделиться', 'extrasport' ); ?></span>
								<a
									href="<?php echo esc_url( extrasport_get_vk_share_url( $permalink, get_the_title() ) ); ?>"
									class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-brand-primary"
									target="_blank"
									rel="noopener noreferrer"
									aria-label="<?php esc_attr_e( 'Поделиться ВКонтакте', 'extrasport' ); ?>"
								>
									<i class="fa-brands fa-vk text-lg" aria-hidden="true"></i>
								</a>
							</div>
							<button type="button" class="btn-primary btn-lg shrink-0" data-modal-open="callModal">
								<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
								<?php esc_html_e( 'Забронировать', 'extrasport' ); ?>
							</button>
						</div>
					</div>

					<div class="share-page__info text-white/90">
						<?php if ( $intro ) : ?>
							<div class="share-page__subtitle font-oswald mb-6 text-2xl uppercase md:text-3xl"><?php echo esc_html( $intro ); ?></div>
						<?php endif; ?>
						<div class="share-page__content prose prose-invert max-w-none entry-content text-white/85">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $other ) ) : ?>
		<section class="page-section page-section--actions page-section--actions-list page-section--h-75 bg-brand-dark" style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');">
			<div class="page-section__inner mx-auto w-full max-w-7xl px-4 py-12 lg:px-6 md:py-16">
				<h2 class="section-heading mb-8 md:mb-10"><?php esc_html_e( 'Другие акции клуба', 'extrasport' ); ?></h2>
				<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
					<?php foreach ( $other as $item ) : ?>
						<?php get_template_part( 'components/cards/share', null, array( 'share' => $item ) ); ?>
					<?php endforeach; ?>
				</div>
				<div class="mt-10 flex justify-center">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' ) ); ?>" class="btn-xl">
						<?php esc_html_e( 'Все акции', 'extrasport' ); ?>
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

extrasport_render_test_drive_section();
