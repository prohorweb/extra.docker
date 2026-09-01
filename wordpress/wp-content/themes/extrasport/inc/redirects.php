<?php
/**
 * 301 fallback redirects for legacy / temporary URL paths
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect temporary WP paths and legacy Yii query URLs to Yii2-compatible permalinks.
 *
 * @return void
 */
function extrasport_handle_legacy_redirects() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || wp_is_json_request() ) {
		return;
	}

	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$path = wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH );
	if ( ! is_string( $path ) ) {
		return;
	}

	$uri = untrailingslashit( $path );

	if ( preg_match( '#^/programs(/.*)?$#', $uri, $matches ) ) {
		$suffix = trim( $matches[1] ?? '', '/' );
		$target = extrasport_get_group_service_url( 'group-programs' );
		if ( $suffix ) {
			$target = trailingslashit( $target ) . $suffix . '/';
		}
		wp_safe_redirect( $target, 301 );
		exit;
	}

	if ( preg_match( '#^/services/programs(/.*)?$#', $uri, $matches ) ) {
		$suffix = trim( $matches[1] ?? '', '/' );
		$target = extrasport_get_group_service_url( 'group-programs' );
		if ( $suffix ) {
			$target = trailingslashit( $target ) . $suffix . '/';
		}
		wp_safe_redirect( $target, 301 );
		exit;
	}

	if ( preg_match( '#^/services/group-programs/([^/]+)/?$#', $uri, $matches ) ) {
		$legacy = extrasport_get_group_program_legacy_slug_redirects();
		$slug   = sanitize_title( $matches[1] );
		if ( isset( $legacy[ $slug ] ) ) {
			wp_safe_redirect(
				trailingslashit( extrasport_get_group_service_url( 'group-programs' ) ) . $legacy[ $slug ] . '/',
				301
			);
			exit;
		}
	}

	if ( preg_match( '#^/shares(/.*)?$#', $uri, $matches ) ) {
		$suffix = $matches[1] ?? '';
		wp_safe_redirect( home_url( '/card/shares' . $suffix . '/' ), 301 );
		exit;
	}

	// Legacy Yii index.php?r=... style URLs.
	if ( str_contains( $uri, 'index.php' ) && isset( $_GET['r'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'extrasport_handle_legacy_redirects', 0 );
