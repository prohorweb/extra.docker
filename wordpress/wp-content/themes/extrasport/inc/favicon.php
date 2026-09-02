<?php
/**
 * Theme favicon assets (per club).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Relative favicon directory for the current club.
 *
 * @return string
 */
function extrasport_get_favicon_dirname() {
	return extrasport_is_devision_site() ? 'favicon/devision' : 'favicon';
}

/**
 * Base URI for favicon assets.
 *
 * @return string
 */
function extrasport_get_favicon_uri() {
	return EXTRASPORT_URI . '/assets/' . extrasport_get_favicon_dirname();
}

/**
 * Mask-icon / tile accent color for the current club.
 *
 * @return string
 */
function extrasport_get_favicon_theme_color() {
	return extrasport_is_devision_site() ? '#069444' : '#ff6600';
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

	$base  = extrasport_get_favicon_uri();
	$color = extrasport_get_favicon_theme_color();
	?>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $base . '/apple-touch-icon.png' ); ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $base . '/favicon-32x32.png' ); ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $base . '/favicon-16x16.png' ); ?>">
	<link rel="manifest" href="<?php echo esc_url( $base . '/site.webmanifest' ); ?>">
	<link rel="mask-icon" href="<?php echo esc_url( $base . '/safari-pinned-tab.svg' ); ?>" color="<?php echo esc_attr( $color ); ?>">
	<link rel="shortcut icon" href="<?php echo esc_url( $base . '/favicon.ico' ); ?>">
	<meta name="msapplication-TileColor" content="<?php echo esc_attr( $color ); ?>">
	<meta name="msapplication-config" content="<?php echo esc_url( $base . '/browserconfig.xml' ); ?>">
	<meta name="theme-color" content="<?php echo esc_attr( extrasport_is_devision_site() ? '#ffffff' : '#080809' ); ?>">
	<?php
}
add_action( 'wp_head', 'extrasport_output_favicon_tags', 1 );
