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
 * Render club rules HTML content from legacy PHP partials.
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

/**
 * Resolve rules HTML: WP page first, legacy PHP partial as fallback.
 *
 * @return string
 */
function extrasport_get_rules_html() {
	$rules_slug = extrasport_get_rules_slug();
	$candidates = array(
		'rules-' . $rules_slug,
		'rules',
	);

	foreach ( $candidates as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			return apply_filters( 'the_content', $page->post_content );
		}
	}

	ob_start();
	extrasport_render_rules_content();
	return (string) ob_get_clean();
}
