<?php
/**
 * Single Share Template
 *
 * @package ExtraSport
 */

get_header();

$club = extrasport_get_club();
?>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-4xl px-4 lg:px-6">
		<?php
		while ( have_posts() ) {
			the_post();
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
							'url'   => get_post_type_archive_link( 'share' ),
						),
						array(
							'label' => get_the_title(),
						),
					),
				)
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="font-oswald mb-6 text-3xl uppercase md:text-4xl"><?php the_title(); ?></h1>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="mb-8 overflow-hidden rounded-xl">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full' ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="prose prose-invert max-w-none entry-content text-white/85">
					<?php the_content(); ?>
				</div>

				<div class="mt-8">
					<button type="button" class="btn-primary btn-lg" data-modal-open="callModal">
						<?php esc_html_e( 'Узнать подробности', 'extrasport' ); ?>
					</button>
				</div>
			</article>
			<?php
		}
		?>
	</div>
</div>

<?php get_template_part( 'template-parts/layout/subscribe', 'section' ); ?>

<?php
get_footer();
