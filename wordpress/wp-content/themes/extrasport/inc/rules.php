<?php
/**
 * Rules modal helpers
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get rules slug for current club.
 *
 * @return string
 */
function extrasport_get_rules_slug() {
	$club = extrasport_get_club();
	return ! empty( $club['rules_slug'] ) ? $club['rules_slug'] : 'piter';
}

/**
 * Render club rules HTML content.
 *
 * @param string|null $slug Rules variant slug.
 * @return void
 */
function extrasport_render_rules_content( $slug = null ) {
	$slug = $slug ?: extrasport_get_rules_slug();
	$file = EXTRASPORT_DIR . '/inc/rules/' . sanitize_file_name( $slug ) . '.php';

	if ( ! file_exists( $file ) ) {
		$file = EXTRASPORT_DIR . '/inc/rules/piter.php';
	}

	include $file;
}
