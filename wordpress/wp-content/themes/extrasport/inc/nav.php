<?php
/**
 * Front-end navigation helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current request path without trailing slash.
 *
 * @return string
 */
function extrasport_get_request_path() {
	global $wp;

	$path = isset( $wp->request ) ? '/' . trim( (string) $wp->request, '/' ) : '/';

	return untrailingslashit( $path ) ?: '/';
}

/**
 * Normalize nav URL for comparisons.
 *
 * @param string $url Nav URL.
 * @return string
 */
function extrasport_normalize_nav_url( $url ) {
	$clean = strtok( (string) $url, '#' );

	return untrailingslashit( $clean ?: '' );
}

/**
 * Check whether a nav item matches the current request.
 *
 * @param string $url Nav item URL.
 * @return bool
 */
function extrasport_is_nav_link_active( $url ) {
	$fragment = (string) wp_parse_url( $url, PHP_URL_FRAGMENT );
	$link     = extrasport_normalize_nav_url( $url );

	if ( 'contacts' === $fragment ) {
		return is_front_page();
	}

	$share_link = extrasport_normalize_nav_url( get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' ) );
	if ( $link === $share_link ) {
		return is_post_type_archive( 'share' ) || is_singular( 'share' );
	}

	$programs_link = extrasport_normalize_nav_url( extrasport_get_card_type_url() );
	if ( $link === $programs_link ) {
		return extrasport_is_card_type_page();
	}

	$programs_archive = extrasport_normalize_nav_url( get_post_type_archive_link( 'group_program' ) ?: home_url( '/services/programs/' ) );
	if ( $link === $programs_archive ) {
		return is_post_type_archive( 'group_program' ) || is_singular( 'group_program' );
	}

	$service_link = extrasport_normalize_nav_url( get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) );
	if ( $link === $service_link ) {
		return is_post_type_archive( 'service' ) || is_singular( 'service' );
	}

	$link_path    = wp_parse_url( $link, PHP_URL_PATH );
	$link_path    = untrailingslashit( (string) ( $link_path ?: '/' ) ) ?: '/';
	$current_path = extrasport_get_request_path();

	if ( $current_path === $link_path ) {
		return true;
	}

	if ( str_starts_with( $current_path, $link_path . '/' ) ) {
		return true;
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );
	if ( $posts_page_id && $link === extrasport_normalize_nav_url( get_permalink( $posts_page_id ) ) ) {
		return is_home() || ( is_archive() && ! is_post_type_archive() );
	}

	return false;
}

/**
 * Check whether any item in the about submenu is active.
 *
 * @param array<int, array{label: string, url: string}> $links About links.
 * @return bool
 */
function extrasport_is_about_nav_active( array $links ) {
	foreach ( $links as $link ) {
		if ( extrasport_is_nav_link_active( $link['url'] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Build nav item class list with optional active state.
 *
 * @param string $base_class Base classes.
 * @param string $url        Nav item URL.
 * @param string $extra      Optional extra classes.
 * @return string
 */
function extrasport_nav_item_class( $base_class, $url, $extra = '' ) {
	$classes = trim( $base_class . ' ' . $extra );

	if ( extrasport_is_nav_link_active( $url ) ) {
		$classes .= ' is-active';
	}

	return trim( $classes );
}
