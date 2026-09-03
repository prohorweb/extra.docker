<?php
/**
 * News archive view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<section class="page-section page-section--actions-list bg-brand-dark">
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
		<h1 class="section-heading news-archive__title mb-8 py-4 text-2xl font-semibold md:mb-10 md:text-3xl lg:text-4xl">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Новости клуба %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h1>

		<?php if ( have_posts() ) : ?>
			<div class="news-blog__list">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'components/cards/news', null, array( 'news' => extrasport_normalize_news_post( get_post() ) ) );
				}
				?>
			</div>
			<div class="news-blog__pagination mt-10">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => '<i class="fa-solid fa-arrow-left-long" aria-hidden="true"></i>',
						'next_text' => '<i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>',
					)
				);
				?>
			</div>
		<?php else : ?>
			<p class="text-center text-white/70"><?php esc_html_e( 'Записей не найдено', 'extrasport' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php extrasport_render_test_drive_section(); ?>
