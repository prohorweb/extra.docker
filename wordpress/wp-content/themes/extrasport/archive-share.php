<?php
/**
 * Archive Share (Акции) Template
 *
 * @package ExtraSport
 */

get_header();

$club = extrasport_get_club();
?>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-7xl px-4 lg:px-6">
		<?php
		get_template_part(
			'template-parts/layout/breadcrumbs',
			null,
			array(
				'items' => array(
					array(
						'label' => $club['title'],
						'url'   => home_url( '/' ),
					),
					array(
						'label' => __( 'Акции', 'extrasport' ),
					),
				),
			)
		);
		?>

		<h1 class="font-oswald mb-10 text-3xl uppercase md:text-4xl">
			<?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $club['title'] ) ); ?>
		</h1>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 md:grid-cols-2">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', 'share' );
				}
				?>
			</div>
			<div class="mt-10">
				<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php get_template_part( 'template-parts/layout/subscribe', 'section' ); ?>

<?php
get_footer();
