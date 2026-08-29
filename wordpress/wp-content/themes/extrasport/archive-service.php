<?php
/**
 * Archive Service Template
 *
 * @package ExtraSport
 */

get_header();

$club = extrasport_get_club();
?>

<?php get_template_part( 'template-parts/layout/page', 'hero-video', array( 'video' => 'service_clubs' ) ); ?>

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
						'label' => __( 'Услуги', 'extrasport' ),
					),
				),
			)
		);
		?>

		<h1 class="font-oswald mb-10 text-3xl uppercase md:text-4xl">
			<?php printf( esc_html__( 'Услуги клуба %s', 'extrasport' ), esc_html( $club['title'] ) ); ?>
		</h1>

		<?php
		$terms = get_terms(
			array(
				'taxonomy'   => 'service_category',
				'hide_empty' => true,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
			?>
			<div class="mb-10 flex flex-wrap gap-2">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="filter-chip is-active"><?php esc_html_e( 'Все услуги', 'extrasport' ); ?></a>
				<?php foreach ( $terms as $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="filter-chip"><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 sm:grid-cols-2">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', 'service' );
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
