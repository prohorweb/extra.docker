<?php
/**
 * Post list / archive fallback view.
 *
 * @package ExtraSport
 */
?>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-7xl px-4 lg:px-6">
		<?php if ( is_archive() ) : ?>
			<h1 class="font-oswald mb-10 text-3xl uppercase md:text-4xl"><?php the_archive_title(); ?></h1>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 md:grid-cols-2">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'components/cards/' . extrasport_get_card_component( get_post_type() ) );
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
