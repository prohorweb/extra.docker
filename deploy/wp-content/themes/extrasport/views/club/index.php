<?php
/**
 * Club overview page.
 *
 * @package ExtraSport
 */

$club   = extrasport_get_club();
$uri    = EXTRASPORT_URI;
$slides = extrasport_get_club_gallery_slides();

$section_args = array(
	'club'   => $club,
	'uri'    => $uri,
	'slides' => $slides,
);
?>

<div class="page-content club-page">
	<?php
	get_template_part( 'sections/club/vr-banner', null, $section_args );
	get_template_part( 'sections/club/content', null, $section_args );
	extrasport_render_test_drive_section();
	?>
</div>
