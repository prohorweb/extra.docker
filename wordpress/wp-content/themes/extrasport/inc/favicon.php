<?php
/**
 * Theme favicon assets (from legacy frontend/web/favicon).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base URI for favicon assets.
 *
 * @return string
 */
function extrasport_get_favicon_uri() {
	return EXTRASPORT_URI . '/assets/favicon';
}

/**
 * Output favicon link tags in document head.
 *
 * @return void
 */
function extrasport_output_favicon_tags() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$base = extrasport_get_favicon_uri();
	?>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $base . '/apple-touch-icon.png' ); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $base . '/favicon-32x32.png' ); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $base . '/favicon-16x16.png' ); ?>">
	<link rel="manifest" href="<?php echo esc_url( $base . '/site.webmanifest' ); ?>">
	<link rel="mask-icon" href="<?php echo esc_url( $base . '/safari-pinned-tab.svg' ); ?>" color="#ff6600">
	<link rel="shortcut icon" href="<?php echo esc_url( $base . '/favicon.ico' ); ?>">
	<?php
}
add_action( 'wp_head', 'extrasport_output_favicon_tags', 1 );
