<?php
/**
 * Placeholder for about-club pages not yet migrated.
 *
 * @package ExtraSport
 */

$slug  = extrasport_get_current_about_page_slug();
$title = extrasport_get_about_page_label( $slug );
?>

<section class="page-section page-section--actions page-section--actions-list bg-brand-dark">
	<div class="page-section__inner mx-auto w-full max-w-4xl px-4 py-12 lg:px-6 md:py-16">
		<h1 class="section-heading mb-6"><?php echo esc_html( $title ); ?></h1>
		<p class="text-white/80"><?php esc_html_e( 'Раздел будет опубликован в ближайшее время.', 'extrasport' ); ?></p>
	</div>
</section>

<?php extrasport_render_test_drive_section(); ?>
