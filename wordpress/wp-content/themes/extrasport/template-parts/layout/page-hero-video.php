<?php
/**
 * Video hero section for inner pages
 *
 * @package ExtraSport
 */

$uri      = EXTRASPORT_URI;
$basename = $args['video'] ?? 'service_clubs';
?>

<section class="relative h-[40vh] min-h-[280px] w-full overflow-hidden bg-brand-dark md:h-[50vh]">
	<video muted loop autoplay playsinline class="absolute inset-0 h-full w-full object-cover hidden md:block" aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '.webm' ); ?>" type="video/webm">
	</video>
	<video muted loop autoplay playsinline class="absolute inset-0 h-full w-full object-cover md:hidden" aria-hidden="true">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '_mobile.mp4' ); ?>" type="video/mp4">
		<source src="<?php echo esc_url( $uri . '/assets/video/' . $basename . '_mobile.webm' ); ?>" type="video/webm">
	</video>
	<div class="absolute inset-0 bg-black/50" aria-hidden="true"></div>
</section>
