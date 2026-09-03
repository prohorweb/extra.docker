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
		$slug = sanitize_title( $matches[1] );

		if ( function_exists( 'extrasport_get_current_group_programs_roster' ) ) {
			foreach ( extrasport_get_current_group_programs_roster() as $item ) {
				$item_slug = sanitize_title( (string) ( $item['slug'] ?? '' ) );
				$link_to   = sanitize_title( (string) ( $item['link_to'] ?? '' ) );
				if ( $item_slug === $slug && $link_to ) {
					$target_id = extrasport_find_top_level_service_by_slug( $link_to );
					if ( $target_id ) {
						wp_safe_redirect( get_permalink( $target_id ), 301 );
						exit;
					}
				}
			}
		}

		if ( 'latina-pro' === $slug && extrasport_is_devision_site() ) {
			wp_safe_redirect( extrasport_get_group_service_url( 'group-programs' ), 301 );
			exit;
		}

		$legacy = extrasport_get_group_program_legacy_slug_redirects();
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
		wp_safe_redirect( home_url( '/actions' . $suffix . '/' ), 301 );
		exit;
	}

	if ( preg_match( '#^/card/shares(/.*)?$#', $uri, $matches ) ) {
		$suffix = $matches[1] ?? '';
		wp_safe_redirect( home_url( '/actions' . $suffix . '/' ), 301 );
		exit;
	}

	if ( preg_match( '#^/card/type/?$#', $uri ) ) {
		wp_safe_redirect( home_url( '/cards/' ), 301 );
		exit;
	}

	if ( extrasport_redirect_legacy_command_url( $uri ) ) {
		exit;
	}

	if ( preg_match( '#^/(es|dv)/(club|news|events|job|jobs)(?:/(.+))?$#', $uri, $matches ) ) {
		$map    = extrasport_get_legacy_about_path_map();
		$legacy = $matches[2];
		$suffix = trim( (string) ( $matches[3] ?? '' ), '/' );

		if ( isset( $map[ $legacy ] ) ) {
			if ( 'news' === $map[ $legacy ] ) {
				if ( $suffix && function_exists( 'extrasport_find_news_post_id_by_slug' ) ) {
					$news_id = extrasport_find_news_post_id_by_slug( $suffix );
					if ( $news_id ) {
						wp_safe_redirect( get_permalink( $news_id ), 301 );
						exit;
					}
				}
				$target = extrasport_get_news_archive_url();
				wp_safe_redirect( $target, 301 );
				exit;
			}

			if ( 'jobs' === $map[ $legacy ] ) {
				wp_safe_redirect( extrasport_get_jobs_archive_url(), 301 );
				exit;
			}

			$target = extrasport_get_about_page_url( $map[ $legacy ] );
			if ( $suffix ) {
				$target = trailingslashit( $target ) . $suffix . '/';
			}
			wp_safe_redirect( $target, 301 );
			exit;
		}
	}

	// Legacy Yii index.php?r=... style URLs.
	if ( str_contains( $uri, 'index.php' ) && isset( $_GET['r'] ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'extrasport_handle_legacy_redirects', 0 );

/**
 * Redirect legacy Yii trainer URLs (/es/command/, /dv/command/) to /trainers/.
 *
 * @param string $uri Request path without trailing slash.
 * @return bool True when redirect was sent.
 */
function extrasport_redirect_legacy_command_url( $uri ) {
	if ( ! preg_match( '#^/(es|dv)/command(?:/(.+))?$#', $uri, $matches ) ) {
		return false;
	}

	$suffix = trim( (string) ( $matches[2] ?? '' ), '/' );

	if ( $suffix ) {
		$trainer_slug_aliases = array(
			'cvetkova-ludmila' => 'ludmila-cvetnikova',
		);

		if ( isset( $trainer_slug_aliases[ $suffix ] ) ) {
			$suffix = $trainer_slug_aliases[ $suffix ];
		}

		$trainer_id = extrasport_find_trainer_post_id_by_slug( $suffix );
		if ( $trainer_id ) {
			wp_safe_redirect( get_permalink( $trainer_id ), 301 );
			return true;
		}
	}

	$target = extrasport_get_trainers_archive_url();

	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && ! isset( $_POST['reset'] ) && isset( $_POST['filter'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter = sanitize_text_field( wp_unslash( (string) $_POST['filter'] ) );
		if ( $filter ) {
			$target = add_query_arg( 'filter', $filter, $target );
		}
	}

	wp_safe_redirect( $target, 301 );
	return true;
}
