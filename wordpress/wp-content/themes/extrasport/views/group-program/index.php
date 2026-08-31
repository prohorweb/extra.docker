<?php
/**
 * Group programs archive view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<div class="page-hero-image relative flex min-h-[280px] items-center justify-center bg-brand-dark md:min-h-[360px]">
	<div class="absolute inset-0 bg-gradient-to-b from-black/40 to-brand-dark" aria-hidden="true"></div>
	<h1 class="font-oswald relative z-10 text-3xl uppercase tracking-wide text-white md:text-5xl"><?php esc_html_e( 'Групповые программы', 'extrasport' ); ?></h1>
</div>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-7xl px-4 lg:px-6">
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
						'label' => __( 'Услуги', 'extrasport' ),
						'url'   => get_post_type_archive_link( 'service' ),
					),
					array(
						'label' => __( 'Групповые программы', 'extrasport' ),
					),
				),
			)
		);
		?>

		<h2 class="font-oswald mb-6 text-center text-2xl uppercase md:text-3xl"><?php esc_html_e( 'Направления групповых программ', 'extrasport' ); ?></h2>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'components/cards/group-program' );
				}
				?>
			</div>
			<div class="mt-10">
				<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'components/content-none' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php extrasport_render_test_drive_section(); ?>
