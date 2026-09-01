<?php
/**
 * About-club section routes (/club/, /trainers/, …) without /es/ or /dv/ prefixes.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_ABOUT_PAGE_QUERY_VAR', 'extrasport_about_page' );
define( 'EXTRASPORT_ABOUT_PAGES_REWRITE_VERSION', 1 );

/**
 * Internal about-club pages.
 *
 * @return array<string, array{label: string, view: string}>
 */
function extrasport_get_about_page_definitions() {
	return array(
		'club'     => array(
			'label' => __( 'Обзор клуба', 'extrasport' ),
			'view'  => 'views/club/index',
		),
		'trainers' => array(
			'label' => __( 'Тренеры', 'extrasport' ),
			'view'  => 'views/about/stub',
		),
		'news'     => array(
			'label' => __( 'Новости', 'extrasport' ),
			'view'  => 'views/about/stub',
		),
		'events'   => array(
			'label' => __( 'Мероприятия', 'extrasport' ),
			'view'  => 'views/about/stub',
		),
		'jobs'     => array(
			'label' => __( 'Вакансии', 'extrasport' ),
			'view'  => 'views/about/stub',
		),
	);
}

/**
 * Legacy Yii path segment => about page slug.
 *
 * @return array<string, string>
 */
function extrasport_get_legacy_about_path_map() {
	return array(
		'club'    => 'club',
		'command' => 'trainers',
		'news'    => 'news',
		'events'  => 'events',
		'job'     => 'jobs',
		'jobs'    => 'jobs',
	);
}

/**
 * Register pretty routes for about-club pages.
 *
 * @return void
 */
function extrasport_register_about_page_rewrites() {
	add_rewrite_tag( '%' . EXTRASPORT_ABOUT_PAGE_QUERY_VAR . '%', '([^&]+)' );

	foreach ( array_keys( extrasport_get_about_page_definitions() ) as $slug ) {
		add_rewrite_rule(
			'^' . preg_quote( $slug, '/' ) . '/?$',
			'index.php?' . EXTRASPORT_ABOUT_PAGE_QUERY_VAR . '=' . $slug,
			'top'
		);
	}
}
add_action( 'init', 'extrasport_register_about_page_rewrites', 21 );

/**
 * Flush rewrite rules when about routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_about_page_rewrites() {
	if ( get_option( 'extrasport_about_pages_rewrite_version' ) === (string) EXTRASPORT_ABOUT_PAGES_REWRITE_VERSION ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_about_pages_rewrite_version', (string) EXTRASPORT_ABOUT_PAGES_REWRITE_VERSION, false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_about_page_rewrites', 99 );

/**
 * Current about page slug or empty string.
 *
 * @return string
 */
function extrasport_get_current_about_page_slug() {
	return sanitize_key( (string) get_query_var( EXTRASPORT_ABOUT_PAGE_QUERY_VAR ) );
}

/**
 * Whether the request matches an about page.
 *
 * @param string $slug Optional slug to check.
 * @return bool
 */
function extrasport_is_about_page( $slug = '' ) {
	$current = extrasport_get_current_about_page_slug();

	if ( ! $current ) {
		return false;
	}

	if ( $slug ) {
		return $current === sanitize_key( $slug );
	}

	return true;
}

/**
 * About page URL.
 *
 * @param string $slug Page slug.
 * @return string
 */
function extrasport_get_about_page_url( $slug ) {
	$slug = sanitize_key( $slug );

	if ( ! isset( extrasport_get_about_page_definitions()[ $slug ] ) ) {
		return home_url( '/' );
	}

	return home_url( '/' . $slug . '/' );
}

/**
 * About page label.
 *
 * @param string $slug Page slug.
 * @return string
 */
function extrasport_get_about_page_label( $slug ) {
	$definitions = extrasport_get_about_page_definitions();

	return $definitions[ sanitize_key( $slug ) ]['label'] ?? '';
}

/**
 * Resolved view slug for the current about page.
 *
 * @return string|null
 */
function extrasport_get_current_about_page_view() {
	$slug = extrasport_get_current_about_page_slug();

	if ( ! $slug ) {
		return null;
	}

	return extrasport_get_about_page_definitions()[ $slug ]['view'] ?? null;
}

/**
 * Internal about submenu links.
 *
 * @return array<int, array{label: string, url: string}>
 */
function extrasport_get_about_nav_links() {
	$links = array();

	foreach ( extrasport_get_about_page_definitions() as $slug => $definition ) {
		$links[] = array(
			'label' => $definition['label'],
			'url'   => extrasport_get_about_page_url( $slug ),
		);
	}

	return $links;
}

/**
 * External about submenu links (YouTube etc.).
 *
 * @return array<int, array{label: string, url: string, external: bool}>
 */
function extrasport_get_about_external_links() {
	$youtube = trim( (string) ( extrasport_get_club()['youtube'] ?? '' ) );

	if ( ! $youtube ) {
		return array();
	}

	return array(
		array(
			'label'    => __( 'Истории успеха', 'extrasport' ),
			'url'      => $youtube,
			'external' => true,
		),
		array(
			'label'    => __( 'Советы тренеров', 'extrasport' ),
			'url'      => $youtube,
			'external' => true,
		),
	);
}

/**
 * Short intro for the club overview page.
 *
 * @return string
 */
function extrasport_get_club_about_intro() {
	$club = extrasport_get_club();
	$text = trim( (string) ( $club['about_intro'] ?? '' ) );

	if ( $text ) {
		return $text;
	}

	if ( 'devision' === extrasport_get_current_club_slug() ) {
		return __( 'De-vision — современный фитнес-клуб в ТРЦ «Родео Драйв»: тренажёрный зал, групповые программы и зона отдыха.', 'extrasport' );
	}

	return __( 'Extra Sport — фитнес-клуб в ТК «Питер»: тренажёрный зал, бассейн, групповые программы и детский клуб.', 'extrasport' );
}
