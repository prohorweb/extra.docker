<?php
/**
 * Shares archive view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
$uri  = EXTRASPORT_URI;
?>

<section
	id="actions"
	class="page-section page-section--actions page-section--actions-list"
	style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
>
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">

		<h1 class="section-heading mb-8 py-4 md:mb-10">
			<?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $club['title'] ) ); ?>
		</h1>

		<?php if ( have_posts() ) : ?>
			<div class="grid items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'components/cards/share' );
				}
				?>
			</div>
			<div class="mt-10 flex justify-center">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="grid items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( extrasport_get_share_placeholders( $uri ) as $share ) : ?>
					<?php get_template_part( 'components/cards/share', null, array( 'share' => $share ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php extrasport_render_test_drive_section(); ?>
