<?php
/**
 * Seed club overview content and gallery from the legacy Yii2 database.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CLUB_PAGE_VERSION', 1 );

/**
 * Download a remote image into the media library.
 *
 * @param string $url    Remote image URL.
 * @param string $source Import source key.
 * @return int Attachment ID or 0.
 */
function extrasport_import_remote_image( $url, $source ) {
	$source = ltrim( (string) $source, '/' );
	$url    = esc_url_raw( (string) $url );

	if ( ! $url ) {
		return 0;
	}

	if ( $source ) {
		$existing_id = extrasport_find_attachment_by_import_source( $source );
		if ( $existing_id ) {
			return $existing_id;
		}
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}

	$file_array = array(
		'name'     => basename( wp_parse_url( $url, PHP_URL_PATH ) ?: 'club-banner.jpg' ),
		'tmp_name' => $tmp,
	);

	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachment_id = media_handle_sideload( $file_array, 0 );
	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}

	if ( $source ) {
		update_post_meta( (int) $attachment_id, EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );
	}

	return (int) $attachment_id;
}

/**
 * Import a Yii main banner image.
 *
 * @param string $filename Banner filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_club_banner_image( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	$source   = 'yii/main_banners/' . $filename;

	$existing_id = extrasport_find_attachment_by_import_source( $source );
	if ( $existing_id ) {
		return $existing_id;
	}

	$path = extrasport_resolve_yii_main_banner_path( $filename );
	if ( $path ) {
		return extrasport_import_local_image( $path, $source );
	}

	$remote = extrasport_get_legacy_club_public_base_url() . '/uploads/image/banners/' . rawurlencode( $filename );

	return extrasport_import_remote_image( $remote, $source );
}

/**
 * Seed club overview content and gallery once per site/version.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_club_page( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_club_page_version', 0 ) >= EXTRASPORT_CLUB_PAGE_VERSION ) {
		return;
	}

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return;
	}

	$data = array();

	$row = $yii->get_row( 'SELECT content, url_3d_tour FROM club LIMIT 1' );
	if ( $row && ! empty( $row->content ) ) {
		$data['about_content'] = extrasport_normalize_club_content_html( $row->content );
	}

	if ( $row && ! empty( $row->url_3d_tour ) ) {
		$data['url_3d_tour'] = esc_url_raw( (string) $row->url_3d_tour );
	}

	if ( $data ) {
		extrasport_update_club( $data );
	}

	$attachment_ids = array();
	$rows           = $yii->get_results(
		'SELECT img FROM main_banners WHERE status = 10 ORDER BY position ASC'
	);

	if ( is_array( $rows ) ) {
		foreach ( $rows as $banner_row ) {
			if ( empty( $banner_row->img ) ) {
				continue;
			}

			$attachment_id = extrasport_import_club_banner_image( (string) $banner_row->img );
			if ( $attachment_id ) {
				$attachment_ids[] = $attachment_id;
			}
		}
	}

	if ( $attachment_ids ) {
		update_option( 'extrasport_club_gallery_ids', $attachment_ids, false );
	}

	update_option( 'extrasport_club_page_version', EXTRASPORT_CLUB_PAGE_VERSION, false );
}

/**
 * Seed club page data on front-end bootstrap.
 *
 * @return void
 */
function extrasport_maybe_seed_club_page() {
	if ( is_admin() ) {
		return;
	}

	extrasport_seed_club_page( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_club_page', 37 );
