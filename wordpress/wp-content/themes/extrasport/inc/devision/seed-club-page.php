<?php
/**
 * Import De-vision club overview content from production (/dv/club/).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_CLUB_PAGE_VERSION', 2 );

/**
 * Fetch a production HTML page for De-vision.
 *
 * @param string $path Path relative to site root, e.g. dv/club/.
 * @return string
 */
function extrasport_fetch_devision_production_html( $path ) {
	$base = trailingslashit( extrasport_get_legacy_club_public_base_url() );
	$url  = $base . ltrim( (string) $path, '/' );

	$response = wp_remote_get(
		$url,
		array(
			'timeout'     => 45,
			'redirection' => 3,
			'user-agent'  => 'ExtraSportMigration/1.0',
		)
	);

	if ( is_wp_error( $response ) ) {
		return '';
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return '';
	}

	return (string) wp_remote_retrieve_body( $response );
}

/**
 * Parse club overview fields from production HTML.
 *
 * @param string $html Raw HTML.
 * @return array{about_content: string, url_3d_tour: string, banners: array<int, string>, stats: array<int, array{num: string, text: string}>}
 */
function extrasport_parse_devision_club_page_html( $html ) {
	$data = array(
		'about_content' => '',
		'url_3d_tour'   => '',
		'banners'       => array(),
		'stats'         => array(),
	);

	if ( ! $html ) {
		return $data;
	}

	if ( preg_match( '/about-page__text[^>]*>([\s\S]*?)<\/div>\s*<div class="about-page__params/', $html, $match ) ) {
		$data['about_content'] = extrasport_normalize_club_content_html( $match[1] );
	} elseif ( preg_match( '/about-page__text[^>]*>([\s\S]*?)<\/div>/', $html, $match ) ) {
		$data['about_content'] = extrasport_normalize_club_content_html( $match[1] );
	}

	if ( preg_match( '/<section class="vr-banner">[\s\S]*?href="([^"]+)"/i', $html, $match ) ) {
		$data['url_3d_tour'] = esc_url_raw( html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) );
	} elseif ( preg_match( '/href="([^"]*(?:pro3d|3d-tour)[^"]*)"/i', $html, $match ) ) {
		$data['url_3d_tour'] = esc_url_raw( html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match_all( '/uploads\/image\/banners\/([^"\'\?]+)/', $html, $matches ) ) {
		$data['banners'] = array_values(
			array_unique(
				array_map( 'sanitize_file_name', $matches[1] )
			)
		);
	}

	if ( preg_match_all( '/param-block__num">(\d+)<\/div>\s*<div class="param-block__text">(.*?)<\/div>/s', $html, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $row ) {
			$text = preg_replace( '/\s+/u', ' ', wp_strip_all_tags( str_replace( '<br>', ' ', $row[2] ) ) );
			$text = trim( (string) $text );

			$data['stats'][] = array(
				'num'  => sanitize_text_field( $row[1] ),
				'text' => esc_html( $text ),
			);
		}
	}

	return $data;
}

/**
 * Import De-vision club overview content and gallery from production.
 *
 * Source: https://de-vision.ru/dv/club/
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_devision_club_page( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_club_page_version', 0 ) >= EXTRASPORT_DEVISION_CLUB_PAGE_VERSION ) {
		return;
	}

	$html = extrasport_fetch_devision_production_html( 'dv/club/' );
	if ( ! $html ) {
		$html = extrasport_fetch_devision_production_html( 'club/' );
	}

	$data = extrasport_parse_devision_club_page_html( $html );

	$club_update = array();

	if ( ! empty( $data['about_content'] ) ) {
		$club_update['about_content'] = $data['about_content'];
	}

	if ( ! empty( $data['url_3d_tour'] ) ) {
		$club_update['url_3d_tour'] = $data['url_3d_tour'];
	}

	if ( $club_update ) {
		extrasport_update_club( $club_update );
	}

	$attachment_ids = array();

	foreach ( $data['banners'] as $filename ) {
		$attachment_id = extrasport_import_club_banner_image( $filename );
		if ( $attachment_id ) {
			$attachment_ids[] = $attachment_id;
		}
	}

	if ( $attachment_ids ) {
		update_option( 'extrasport_club_gallery_ids', $attachment_ids, false );
	}

	update_option( 'extrasport_devision_club_page_version', EXTRASPORT_DEVISION_CLUB_PAGE_VERSION, false );
}
