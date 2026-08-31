<?php
/**
 * Front page view — composes sections.
 *
 * @package ExtraSport
 */

$club             = extrasport_get_club();
$brand            = extrasport_get_brand();
$uri              = EXTRASPORT_URI;
$tel_clean        = preg_replace( '/\s+/', '', $club['tel'] );
$banners          = get_posts(
	array(
		'post_type'      => 'banner',
		'posts_per_page' => 10,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);
$shares           = extrasport_get_front_page_shares( $uri );
$hero_slide_count = extrasport_get_hero_slide_count( $banners );

$section_args = array(
	'club'             => $club,
	'brand'            => $brand,
	'uri'              => $uri,
	'tel_clean'        => $tel_clean,
	'banners'          => $banners,
	'shares'           => $shares,
	'hero_slide_count' => $hero_slide_count,
);
?>

<div class="page-content front-page-main">
	<?php
	get_template_part( 'sections/hero-carousel', null, $section_args );
	get_template_part( 'sections/about-video', null, $section_args );
	get_template_part( 'sections/shares', null, $section_args );
	extrasport_render_test_drive_section();
	get_template_part( 'sections/contacts-map', null, $section_args );
	?>
</div>

<?php wp_reset_postdata(); ?>
