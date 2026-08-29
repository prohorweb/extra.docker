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
 * Get rules slug for current club (extrasport|devision).
 *
 * @return string
 */
function extrasport_get_rules_slug() {
	$club = extrasport_get_club();
	$slug = ! empty( $club['rules_slug'] ) ? (string) $club['rules_slug'] : extrasport_get_current_club_slug();

	return extrasport_normalize_club_slug( $slug );
}

/**
 * Modal heading for club rules.
 *
 * @return string
 */
function extrasport_get_rules_modal_title() {
	$club = extrasport_get_club();

	if ( ! empty( $club['rules_modal_title'] ) ) {
		return (string) $club['rules_modal_title'];
	}

	if ( 'devision' === extrasport_get_current_club_slug() ) {
		return 'ПРАВИЛА СПОРТИВНОГО КЛУБА DE-VISION';
	}

	$suffix = ! empty( $club['rules_title_suffix'] ) ? $club['rules_title_suffix'] : 'ТК «ПИТЕР»';

	return sprintf( 'ПРАВИЛА СПОРТИВНОГО КЛУБА «ЭКСТРА СПОРТ» %s', $suffix );
}

/**
 * Render club rules HTML content from theme partials.
 *
 * @param string|null $slug Rules variant slug.
 * @return void
 */
function extrasport_render_rules_content( $slug = null ) {
	$slug = extrasport_normalize_club_slug( $slug ?: extrasport_get_rules_slug() );
	$file = EXTRASPORT_DIR . '/inc/rules/' . sanitize_file_name( $slug ) . '.php';

	if ( ! file_exists( $file ) ) {
		$file = EXTRASPORT_DIR . '/inc/rules/extrasport.php';
	}

	include $file;
}

/**
 * Resolve rules HTML: WP page first, theme partial as fallback.
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
