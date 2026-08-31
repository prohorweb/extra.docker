<?php
/**
 * Single service view.
 *
 * @package ExtraSport
 */
?>

<div class="page-content bg-brand-dark py-12 md:py-16">
	<div class="mx-auto max-w-4xl px-4 lg:px-6">
		<?php
		while ( have_posts() ) {
			the_post();
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

				<div class="mt-8 flex flex-wrap gap-4">
					<button type="button" class="btn-primary btn-lg" data-modal-open="callModal">
						<?php esc_html_e( 'Заказать звонок', 'extrasport' ); ?>
					</button>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="btn-primary btn-lg bg-transparent border border-white/30 hover:bg-white/10">
						<?php esc_html_e( 'Все услуги', 'extrasport' ); ?>
					</a>
				</div>
			</article>
			<?php
		}
		?>
	</div>
</div>

<?php extrasport_render_test_drive_section(); ?>
