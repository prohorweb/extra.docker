<?php
/**
 * Front page shares / promotions section.
 *
 * @package ExtraSport
 */

$club   = $args['club'] ?? extrasport_get_club();
$uri    = $args['uri'] ?? EXTRASPORT_URI;
$shares = $args['shares'] ?? array();

if ( empty( $shares ) ) {
	return;
}
?>

<section
	id="actions"
	class="page-section page-section--centered page-section--actions"
	style="background-image: url('<?php echo esc_url( $uri . '/assets/img/actions-bg.jpg' ); ?>');"
>
	<div class="page-section__inner">
		<div class="text-center">
			<h2 class="section-heading mb-8 md:mb-10">
				<?php printf( esc_html__( 'Акции клуба %s', 'extrasport' ), esc_html( $club['title'] ) ); ?>
			</h2>
		</div>

		<div class="grid items-start gap-6 text-center md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ( $shares as $index => $share ) : ?>
				<?php
				get_template_part(
					'components/cards/share',
					null,
					array(
						'share' => $share,
						'class' => trim( 'share-card--home' . ( ( 2 === $index ) ? ' md:hidden lg:block' : '' ) ),
					)
				);
				?>
			<?php endforeach; ?>
		</div>

		<div class="flex justify-center pb-8 pt-4">
			<a href="<?php echo esc_url( extrasport_get_shares_archive_url() ); ?>" class="btn-xl">
				<?php esc_html_e( 'Все акции', 'extrasport' ); ?>
			</a>
		</div>
	</div>
</section>
