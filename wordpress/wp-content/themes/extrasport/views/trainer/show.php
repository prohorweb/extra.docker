<?php
/**
 * Single trainer view.
 *
 * @package ExtraSport
 */

$uri = EXTRASPORT_URI;

while ( have_posts() ) {
	the_post();

	$banner_url = extrasport_get_trainer_banner_url();
	$position   = (string) get_post_meta( get_the_ID(), EXTRASPORT_TRAINER_POST_META, true );
	$other      = extrasport_get_other_trainers( get_the_ID(), 3 );
	?>
	<section
		class="page-section page-section--actions page-section--actions-list page-section--actions-dim-2x"
		style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
	>
		<div class="page-section__inner mx-auto w-full max-w-7xl px-4 py-10 lg:px-6 md:py-14">
			<?php extrasport_render_breadcrumbs( extrasport_get_trainer_breadcrumbs(), array( 'class' => 'hidden md:block' ) ); ?>

			<h1 class="section-heading mb-4 md:mb-5"><?php the_title(); ?></h1>

			<div class="share-page__panel">
				<div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
					<div class="share-page__info text-white/90">
						<?php if ( $position ) : ?>
							<div class="share-page__subtitle font-oswald mb-6 text-2xl uppercase md:text-3xl">
								<?php echo esc_html( $position ); ?>
							</div>
						<?php endif; ?>

						<div class="share-page__content prose prose-invert max-w-none entry-content text-white/85">
							<?php
							if ( get_the_content() ) {
								the_content();
							} else {
								?>
								<p><?php esc_html_e( 'Информация о тренере будет добавлена позже.', 'extrasport' ); ?></p>
								<?php
							}
							?>
						</div>
					</div>

					<div class="share-page__media">
						<?php if ( $banner_url ) : ?>
							<img class="h-auto w-full rounded-lg object-cover object-top" src="<?php echo esc_url( $banner_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php else : ?>
							<div class="share-page__placeholder aspect-square w-full rounded-lg bg-white/10">
								<img class="membership-card__logo" src="<?php echo esc_url( extrasport_get_trainer_placeholder_logo_url() ); ?>" alt="">
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $other ) ) : ?>
		<section
			class="page-section page-section--actions page-section--actions-list page-section--actions-dim-2x"
			style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
		>
			<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
				<h2 class="section-heading mb-4 md:mb-5"><?php esc_html_e( 'Другие тренеры', 'extrasport' ); ?></h2>
				<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
					<?php foreach ( $other as $item ) : ?>
						<?php get_template_part( 'components/cards/trainer', null, array( 'trainer' => $item ) ); ?>
					<?php endforeach; ?>
				</div>
				<div class="mt-10 flex justify-center">
					<a href="<?php echo esc_url( extrasport_get_trainers_archive_url() ); ?>" class="btn-xl">
						<?php esc_html_e( 'Все тренеры', 'extrasport' ); ?>
					</a>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

extrasport_render_test_drive_section();
