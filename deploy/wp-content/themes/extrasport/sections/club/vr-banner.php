<?php
/**
 * VR tour hero banner for the club overview page.
 *
 * @package ExtraSport
 */

$club  = $args['club'] ?? extrasport_get_club();
$uri   = $args['uri'] ?? EXTRASPORT_URI;
$url   = extrasport_get_club_vr_tour_url();
$image = extrasport_get_club_vr_banner_image();

if ( ! $url ) {
	return;
}
?>

<section class="vr-banner">
	<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="service-header group relative block">
		<div class="title flex items-center justify-center">
			<?php esc_html_e( 'VR-тур по клубу', 'extrasport' ); ?>
		</div>
		<div class="overlay" aria-hidden="true"></div>
		<img class="w-full" src="<?php echo esc_url( $uri . '/assets/img/' . $image ); ?>" alt="">
	</a>
</section>
