<?php
/**
 * Single news view.
 *
 * @package ExtraSport
 */

while ( have_posts() ) {
	the_post();
	?>
	<section class="page-section page-section--actions-list single-news bg-brand-dark">
		<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
			<?php extrasport_render_breadcrumbs( extrasport_get_news_breadcrumbs() ); ?>

			<h1 class="section-heading news-single__title mb-8 py-4 text-2xl font-semibold md:mb-10 md:text-3xl lg:text-4xl"><?php the_title(); ?></h1>

			<div class="news-block__wrap mx-auto max-w-4xl">
				<div class="news-block__text prose prose-invert max-w-none entry-content text-white">
					<?php
					if ( get_the_content() ) {
						the_content();
					} else {
						$intro = extrasport_get_news_intro();
						if ( $intro ) {
							echo '<p>' . esc_html( $intro ) . '</p>';
						}
					}
					?>
				</div>

				<div class="mt-10 flex justify-center">
					<a href="<?php echo esc_url( extrasport_get_news_archive_url() ); ?>" class="btn-xl">
						<?php esc_html_e( 'Все новости', 'extrasport' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php
}

extrasport_render_test_drive_section();
