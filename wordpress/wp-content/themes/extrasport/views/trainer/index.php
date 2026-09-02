<?php
/**
 * Trainers archive view.
 *
 * @package ExtraSport
 */

$club = extrasport_get_club();
?>

<section class="page-section page-section--actions-list bg-brand-dark">
	<div class="page-section__inner mx-auto w-full max-w-7xl px-4 lg:px-6">
		<h1 class="section-heading mb-4 py-4 text-2xl md:mb-5 md:text-3xl lg:text-4xl">
			<?php
			printf(
				/* translators: %s: club title */
				esc_html__( 'Тренеры клуба %s', 'extrasport' ),
				esc_html( $club['title'] )
			);
			?>
		</h1>

		<?php
		extrasport_render_trainers_section(
			array(
				'heading'     => false,
				'show_filter' => true,
			)
		);
		?>
	</div>
</section>

<?php extrasport_render_test_drive_section(); ?>
