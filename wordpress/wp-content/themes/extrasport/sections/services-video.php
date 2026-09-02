<?php
/**
 * Front page services clubs video section.
 *
 * @package ExtraSport
 */

$uri      = $args['uri'] ?? EXTRASPORT_URI;
$basename = $args['video'] ?? extrasport_get_service_clubs_video_basename();
?>

<section id="services" class="page-section page-section--media page-section--services-video bg-black">
	<video class="hidden md:block" muted autoplay loop playsinline>
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '.webm' ); ?>" type="video/webm">
	</video>
	<video class="block md:hidden" muted autoplay loop playsinline>
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '_mobile.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '_mobile.webm' ); ?>" type="video/webm">
	</video>
</section>
