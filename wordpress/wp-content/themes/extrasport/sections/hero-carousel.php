<?php
/**
 * Front page hero carousel section.
 *
 * @package ExtraSport
 */

$banners          = $args['banners'] ?? array();
$club             = $args['club'] ?? extrasport_get_club();
$uri              = $args['uri'] ?? EXTRASPORT_URI;
$hero_slide_count = $args['hero_slide_count'] ?? extrasport_get_hero_slide_count( $banners );
?>

<header class="masthead">
	<div
		class="carousel carousel--hero hidden md:block"
		data-carousel
		data-carousel-wheel
		data-carousel-interval="false"
		id="carouselDesktop"
	>
		<div class="carousel-track">
			<?php extrasport_render_carousel_slides( $banners, $club, $uri ); ?>
		</div>
		<?php extrasport_render_hero_carousel_dots( $hero_slide_count ); ?>
	</div>

	<div
		class="carousel carousel--hero md:hidden"
		data-carousel
		data-carousel-interval="8000"
		id="carouselMobile"
	>
		<div class="carousel-track">
			<?php extrasport_render_carousel_slides( $banners, $club, $uri ); ?>
		</div>
		<?php extrasport_render_hero_carousel_dots( $hero_slide_count ); ?>
	</div>
</header>
