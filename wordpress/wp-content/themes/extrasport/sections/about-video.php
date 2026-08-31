<?php
/**
 * Front page about / service clubs video section.
 *
 * @package ExtraSport
 */

$uri = $args['uri'] ?? EXTRASPORT_URI;
?>

<section id="about" class="page-section page-section--media bg-black">
	<video class="hidden md:block" muted autoplay loop playsinline>
		<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs.webm' ); ?>" type="video/webm">
	</video>
	<video class="block md:hidden" muted autoplay loop playsinline>
		<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/service_clubs_mobile.webm' ); ?>" type="video/webm">
	</video>
</section>
