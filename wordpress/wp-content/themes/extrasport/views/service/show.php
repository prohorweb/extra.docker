<?php
/**
 * Single service view.
 *
 * @package ExtraSport
 */

$uri = EXTRASPORT_URI;

while ( have_posts() ) {
	the_post();

	$service_id   = get_the_ID();
	$service_data = extrasport_normalize_service_post( get_post() );
	$intro        = extrasport_get_service_intro( $service_id );
	$other        = extrasport_get_other_services( $service_id, 3 );
	?>
	<section
		id="actions"
		class="page-section page-section--actions page-section--actions-list page-section--actions-dim-2x"
		style="<?php echo esc_attr( extrasport_get_parallax_bg_style( $service_id ) ); ?>"
	>
		<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">

			<?php if ( (int) get_post_field( 'post_parent', $service_id ) > 0 ) : ?>
				<?php extrasport_render_breadcrumbs( extrasport_get_service_breadcrumbs( $service_id ) ); ?>
			<?php endif; ?>

			<h1 class="section-heading mb-4 md:mb-5"><?php the_title(); ?></h1>

			<div class="share-page__panel">
				<div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
					<div class="share-page__media">
						<div class="share-card share-card--service share-card--static">
							<div class="share-card__media">
								<?php if ( ! empty( $service_data['image'] ) ) : ?>
									<img class="card-img-top" src="<?php echo esc_url( $service_data['image'] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php else : ?>
									<div class="card-img-top bg-white/10" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></div>
								<?php endif; ?>
							</div>
						</div>

						<div class="share-page__footer mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
							<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ); ?>" class="btn-primary btn-lg bg-transparent border border-white/30 hover:bg-white/10">
								<?php esc_html_e( 'Все услуги', 'extrasport' ); ?>
							</a>
							<button type="button" class="btn-primary btn-lg shrink-0" data-modal-open="callModal">
								<i class="fa-solid fa-phone-volume me-2" aria-hidden="true"></i>
								<?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?>
							</button>
						</div>
					</div>

					<div class="share-page__info text-white/90">
						<?php if ( $intro ) : ?>
							<div class="share-page__subtitle font-oswald mb-6 text-2xl uppercase md:text-3xl"><?php echo esc_html( $intro ); ?></div>
						<?php endif; ?>
						<div class="share-page__content prose prose-invert max-w-none entry-content text-white/85">
							<?php
							if ( get_the_content() ) {
								the_content();
							} else {
								?>
								<p><?php esc_html_e( 'Контент услуги будет добавлен позже.', 'extrasport' ); ?></p>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $other ) ) : ?>
		<section class="page-section page-section--actions page-section--actions-list page-section--h-75 bg-brand-dark" style="background-image: url('<?php echo esc_url( extrasport_get_default_actions_bg_url() ); ?>');">
			<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
				<h2 class="section-heading mb-4 md:mb-5"><?php esc_html_e( 'Другие услуги клуба', 'extrasport' ); ?></h2>
				<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
					<?php foreach ( $other as $item ) : ?>
						<?php get_template_part( 'components/cards/service', null, array( 'service' => $item ) ); ?>
					<?php endforeach; ?>
				</div>
				<div class="mt-10 flex justify-center">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ); ?>" class="btn-xl">
						<?php esc_html_e( 'Все услуги', 'extrasport' ); ?>
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

extrasport_render_test_drive_section();
