<?php
/**
 * Singular content view (pages, posts, privacy, legal).
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-4xl px-4 lg:px-6">
		<?php
		while ( have_posts() ) {
			the_post();
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
							'label' => get_the_title(),
						),
					),
				)
			);
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="font-oswald mb-8 text-3xl uppercase md:text-4xl"><?php the_title(); ?></h1>

				<div class="prose prose-invert max-w-none entry-content text-white/85">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<div class="page-links mt-8 text-sm text-white/60">' . esc_html__( 'Pages:', 'extrasport' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</article>
			<?php
		}
		?>
	</div>
</div>
