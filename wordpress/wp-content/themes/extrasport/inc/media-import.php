<?php
/**
 * Import static assets into the WordPress media library.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Absolute path to Yii2 frontend uploads root.
 *
 * @return string
 */
function extrasport_get_yii_uploads_dir() {
	if ( ! extrasport_allows_legacy_file_paths() ) {
		return '';
	}

	$candidates = array(
		'/var/www/yii-uploads',
		EXTRASPORT_DIR . '/../../../../frontend/web/uploads',
	);

	foreach ( $candidates as $candidate ) {
		$resolved = realpath( $candidate );
		if ( $resolved && is_dir( $resolved ) ) {
			return trailingslashit( $resolved );
		}
	}

	return '';
}

/**
 * Absolute path to Yii2 frontend service images (legacy img/service).
 *
 * @return string
 */
function extrasport_get_yii_service_image_dir() {
	$candidates = array();

	if ( extrasport_allows_legacy_file_paths() ) {
		$candidates[] = '/var/www/yii-service-images';
		$candidates[] = EXTRASPORT_DIR . '/../../../../frontend/web/img/service';
	}

	$candidates[] = EXTRASPORT_DIR . '/assets/img/service';

	foreach ( $candidates as $candidate ) {
		$resolved = realpath( $candidate );
		if ( $resolved && is_dir( $resolved ) ) {
			return trailingslashit( $resolved );
		}
	}

	return '';
}

/**
 * Resolve a Yii upload image file on disk.
 *
 * @param string $subdir   Upload subdirectory, e.g. services or group_programs.
 * @param string $filename Image file name.
 * @return string
 */
function extrasport_resolve_yii_upload_image_path( $subdir, $filename ) {
	$subdir   = sanitize_file_name( (string) $subdir );
	$filename = sanitize_file_name( (string) $filename );
	$dir      = extrasport_get_yii_uploads_dir();

	if ( ! $dir || ! $subdir || ! $filename ) {
		return '';
	}

	$path = $dir . $subdir . '/' . $filename;

	return file_exists( $path ) ? $path : '';
}

/**
 * Resolve a Yii service image file on disk (legacy img/service).
 *
 * @param string $filename Image file name, e.g. serv-1.jpg.
 * @return string
 */
function extrasport_resolve_yii_service_image_path( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	$dir      = extrasport_get_yii_service_image_dir();

	if ( ! $dir || ! $filename ) {
		return '';
	}

	$path = $dir . $filename;

	return file_exists( $path ) ? $path : '';
}

/**
 * Parse a service image reference into source type and path parts.
 *
 * Supported formats:
 * - services/foo.jpg
 * - group_programs/foo.jpg
 * - serv-1.jpg
 *
 * @param string $source Image reference.
 * @return array{type: string, subdir: string, filename: string}
 */
function extrasport_parse_service_image_source( $source ) {
	$source = ltrim( (string) $source, '/' );

	if ( preg_match( '#^(services|group_programs)/([^/]+)$#', $source, $matches ) ) {
		return array(
			'type'     => 'upload',
			'subdir'   => $matches[1],
			'filename' => $matches[2],
		);
	}

	return array(
		'type'     => 'legacy',
		'subdir'   => '',
		'filename' => basename( $source ),
	);
}

/**
 * Resolve any supported service image path on disk.
 *
 * @param string $source Image reference.
 * @return string
 */
function extrasport_resolve_service_image_path( $source ) {
	$parsed = extrasport_parse_service_image_source( $source );

	if ( 'upload' === $parsed['type'] ) {
		return extrasport_resolve_yii_upload_image_path( $parsed['subdir'], $parsed['filename'] );
	}

	return extrasport_resolve_yii_service_image_path( $parsed['filename'] );
}

/**
 * Meta key that links an attachment to its Yii import source.
 */
define( 'EXTRASPORT_IMPORT_SOURCE_META_KEY', '_extrasport_import_source' );

/**
 * Find an existing attachment imported from a source path.
 *
 * @param string $source Image reference, e.g. services/foo.jpg.
 * @return int Attachment ID or 0.
 */
function extrasport_find_attachment_by_import_source( $source ) {
	$source = ltrim( (string) $source, '/' );

	if ( ! $source ) {
		return 0;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => EXTRASPORT_IMPORT_SOURCE_META_KEY,
			'meta_value'     => $source,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	return ! empty( $posts ) ? (int) $posts[0] : 0;
}

/**
 * Import a local image file into the media library.
 *
 * @param string $file_path Absolute file path.
 * @param string $source    Optional import source key for deduplication.
 * @return int Attachment ID or 0.
 */
function extrasport_import_local_image( $file_path, $source = '' ) {
	$source = ltrim( (string) $source, '/' );

	if ( $source ) {
		$existing_id = extrasport_find_attachment_by_import_source( $source );
		if ( $existing_id ) {
			return $existing_id;
		}
	}

	if ( ! is_string( $file_path ) || ! file_exists( $file_path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	$filename = basename( $file_path );
	$upload   = wp_upload_bits( $filename, null, (string) file_get_contents( $file_path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$filetype   = wp_check_filetype( $filename );
	$attachment = array(
		'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'] );
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		return 0;
	}

	if ( $source ) {
		update_post_meta( $attach_id, EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	return (int) $attach_id;
}

/**
 * Regenerate attachment metadata so custom image sizes are available.
 *
 * @param int $attachment_id Attachment ID.
 * @return void
 */
function extrasport_regenerate_attachment_metadata( $attachment_id ) {
	$attachment_id = (int) $attachment_id;

	if ( ! $attachment_id || 'attachment' !== get_post_type( $attachment_id ) ) {
		return;
	}

	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! file_exists( $file ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$metadata = wp_generate_attachment_metadata( $attachment_id, $file );
	if ( ! empty( $metadata ) ) {
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}
}

/**
 * Import a theme-relative image into the media library.
 *
 * @param string $relative_path Path relative to the theme directory.
 * @return int Attachment ID or 0.
 */
function extrasport_import_theme_image( $relative_path ) {
	$file_path = EXTRASPORT_DIR . '/' . ltrim( $relative_path, '/' );

	return extrasport_import_local_image( $file_path );
}

/**
 * Import a Yii upload image into the media library.
 *
 * @param string $subdir   Upload subdirectory, e.g. services or group_programs.
 * @param string $filename Image file name.
 * @return int Attachment ID or 0.
 */
function extrasport_import_yii_upload_image( $subdir, $filename ) {
	$file_path = extrasport_resolve_yii_upload_image_path( $subdir, $filename );

	return extrasport_import_local_image( $file_path );
}

/**
 * Import a Yii service image into the media library.
 *
 * @param string $filename Image file name, e.g. serv-1.jpg.
 * @return int Attachment ID or 0.
 */
function extrasport_import_yii_service_image( $filename ) {
	$file_path = extrasport_resolve_yii_service_image_path( $filename );

	return extrasport_import_local_image( $file_path );
}

/**
 * Import a service image reference into the media library.
 *
 * @param string $source Image reference.
 * @return int Attachment ID or 0.
 */
function extrasport_import_service_image( $source ) {
	$source = ltrim( (string) $source, '/' );

	if ( ! $source ) {
		return 0;
	}

	$existing_id = extrasport_find_attachment_by_import_source( $source );
	if ( $existing_id ) {
		return $existing_id;
	}

	$file_path = extrasport_resolve_service_image_path( $source );

	return extrasport_import_local_image( $file_path, $source );
}

/**
 * Cached service image imports.
 *
 * @param string $source Image reference.
 * @return int Attachment ID or 0.
 */
function extrasport_get_service_attachment_id( $source ) {
	static $cache = array();

	$source = ltrim( (string) $source, '/' );

	if ( ! $source ) {
		return 0;
	}

	if ( isset( $cache[ $source ] ) ) {
		return (int) $cache[ $source ];
	}

	$cache[ $source ] = extrasport_find_attachment_by_import_source( $source );

	return (int) $cache[ $source ];
}

/**
 * Cached Yii service image imports (legacy img/service).
 *
 * @param string $filename Image file name.
 * @return int Attachment ID or 0.
 */
function extrasport_get_yii_service_attachment_id( $filename ) {
	return extrasport_get_service_attachment_id( $filename );
}

/**
 * Assign a service image as the post thumbnail.
 *
 * @param int    $post_id  Post ID.
 * @param string $source   Image reference.
 * @param bool   $force    Replace an existing thumbnail.
 * @return bool
 */
function extrasport_set_service_thumbnail_from_yii( $post_id, $source, $force = false ) {
	$post_id = (int) $post_id;

	if ( ! $post_id || ( has_post_thumbnail( $post_id ) && ! $force ) ) {
		return has_post_thumbnail( $post_id );
	}

	$attachment_id = extrasport_import_service_image( $source );
	if ( ! $attachment_id ) {
		return false;
	}

	return (bool) set_post_thumbnail( $post_id, $attachment_id );
}

/**
 * Public URL for a service image (media library or Yii fallback).
 *
 * @param string $source Image reference.
 * @return string
 */
function extrasport_get_yii_service_image_url( $source ) {
	$attachment_id = extrasport_get_service_attachment_id( $source );
	if ( $attachment_id ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}

	return extrasport_get_direct_service_image_url( $source );
}

/**
 * Direct URL for a service image file without importing into media library.
 *
 * @param string $source Image reference.
 * @return string
 */
function extrasport_get_direct_service_image_url( $source ) {
	if ( ! extrasport_allows_legacy_file_paths() && ! str_starts_with( (string) $source, 'serv-' ) ) {
		$parsed = extrasport_parse_service_image_source( $source );
		if ( 'upload' === $parsed['type'] ) {
			return '';
		}
	}

	$parsed = extrasport_parse_service_image_source( $source );

	if ( 'upload' === $parsed['type'] ) {
		$uploads_dir = extrasport_get_yii_uploads_dir();
		$path        = extrasport_resolve_yii_upload_image_path( $parsed['subdir'], $parsed['filename'] );
		if ( $path && $uploads_dir && str_starts_with( $path, $uploads_dir ) ) {
			$relative = ltrim( str_replace( $uploads_dir, '', $path ), '/' );

			return home_url( '/uploads/' . $relative );
		}
	}

	$path = extrasport_resolve_yii_service_image_path( $parsed['filename'] );
	if ( $path && str_starts_with( $path, EXTRASPORT_DIR ) ) {
		return EXTRASPORT_URI . '/' . ltrim( str_replace( EXTRASPORT_DIR, '', $path ), '/' );
	}

	return '';
}

/**
 * Media library cleanup version — bump to re-run deduplication.
 */
define( 'EXTRASPORT_MEDIA_CLEANUP_VERSION', 2 );

/**
 * Hash of an attachment file on disk.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function extrasport_get_attachment_file_hash( $attachment_id ) {
	$file = get_attached_file( (int) $attachment_id );

	if ( ! $file || ! file_exists( $file ) ) {
		return '';
	}

	return md5_file( $file );
}

/**
 * Pick the attachment that should be kept in a duplicate group.
 *
 * @param array<int, int> $attachment_ids Attachment IDs.
 * @return int
 */
function extrasport_pick_attachment_to_keep( array $attachment_ids ) {
	global $wpdb;

	if ( empty( $attachment_ids ) ) {
		return 0;
	}

	$attachment_ids = array_values( array_unique( array_map( 'intval', $attachment_ids ) ) );

	foreach ( $attachment_ids as $attachment_id ) {
		$has_source = (string) get_post_meta( $attachment_id, EXTRASPORT_IMPORT_SOURCE_META_KEY, true );
		if ( $has_source ) {
			return $attachment_id;
		}
	}

	foreach ( $attachment_ids as $attachment_id ) {
		$in_use = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1) FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
				$attachment_id
			)
		);

		if ( $in_use > 0 ) {
			return $attachment_id;
		}
	}

	return (int) min( $attachment_ids );
}

/**
 * Point featured images from one attachment to another.
 *
 * @param int $from_id Old attachment ID.
 * @param int $to_id   New attachment ID.
 * @return void
 */
function extrasport_reassign_attachment_usages( $from_id, $to_id ) {
	global $wpdb;

	$from_id = (int) $from_id;
	$to_id   = (int) $to_id;

	if ( ! $from_id || ! $to_id || $from_id === $to_id ) {
		return;
	}

	$post_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
			$from_id
		)
	);

	foreach ( $post_ids as $post_id ) {
		update_post_meta( (int) $post_id, '_thumbnail_id', $to_id );
	}
}

/**
 * Delete duplicate image attachments with identical file contents.
 *
 * @return int Number of deleted attachments.
 */
function extrasport_cleanup_duplicate_attachments_by_hash() {
	global $wpdb;

	$attachment_ids = $wpdb->get_col(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'"
	);

	$groups  = array();
	$deleted = 0;

	foreach ( $attachment_ids as $attachment_id ) {
		$hash = extrasport_get_attachment_file_hash( (int) $attachment_id );
		if ( ! $hash ) {
			continue;
		}

		$groups[ $hash ][] = (int) $attachment_id;
	}

	foreach ( $groups as $group_ids ) {
		if ( count( $group_ids ) <= 1 ) {
			continue;
		}

		$keep_id = extrasport_pick_attachment_to_keep( $group_ids );
		if ( ! $keep_id ) {
			continue;
		}

		foreach ( $group_ids as $attachment_id ) {
			if ( (int) $attachment_id === (int) $keep_id ) {
				continue;
			}

			extrasport_reassign_attachment_usages( $attachment_id, $keep_id );
			wp_delete_attachment( $attachment_id, true );
			++$deleted;
		}
	}

	return $deleted;
}

/**
 * Delete trainer import attachments that are no longer referenced anywhere.
 *
 * @return int Number of deleted attachments.
 */
function extrasport_cleanup_orphan_trainer_attachments() {
	global $wpdb;

	if ( ! defined( 'EXTRASPORT_TRAINER_BANNER_META' ) ) {
		return 0;
	}

	$attachment_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT p.ID
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
			WHERE p.post_type = 'attachment'
				AND pm.meta_key = %s
				AND (
					pm.meta_value LIKE %s
					OR pm.meta_value LIKE %s
					OR pm.meta_value LIKE %s
					OR pm.meta_value LIKE %s
				)",
			EXTRASPORT_IMPORT_SOURCE_META_KEY,
			$wpdb->esc_like( 'yii/trainers/' ) . '%',
			$wpdb->esc_like( 'yii/trainer-banners/' ) . '%',
			$wpdb->esc_like( 'production/trainers/' ) . '%',
			$wpdb->esc_like( 'production/trainer-banners/' ) . '%'
		)
	);

	$deleted = 0;

	foreach ( $attachment_ids as $attachment_id ) {
		$attachment_id = (int) $attachment_id;

		$in_use = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1)
				FROM {$wpdb->postmeta}
				WHERE meta_value = %d
					AND meta_key IN (%s, %s)",
				$attachment_id,
				'_thumbnail_id',
				EXTRASPORT_TRAINER_BANNER_META
			)
		);

		if ( $in_use > 0 ) {
			continue;
		}

		wp_delete_attachment( $attachment_id, true );
		++$deleted;
	}

	return $deleted;
}

/**
 * Delete unused legacy serv-* demo attachments.
 *
 * @return int Number of deleted attachments.
 */
function extrasport_cleanup_legacy_serv_attachments() {
	global $wpdb;

	$deleted        = 0;
	$attachment_ids = $wpdb->get_col(
		"SELECT p.ID
		FROM {$wpdb->posts} p
		INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
		WHERE p.post_type = 'attachment'
			AND pm.meta_key = '_wp_attached_file'
			AND ( pm.meta_value LIKE '%/serv-%.jpg' OR pm.meta_value LIKE 'serv-%.jpg' )"
	);

	foreach ( $attachment_ids as $attachment_id ) {
		$attachment_id = (int) $attachment_id;
		$in_use        = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(1) FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
				$attachment_id
			)
		);

		if ( $in_use > 0 ) {
			continue;
		}

		wp_delete_attachment( $attachment_id, true );
		++$deleted;
	}

	return $deleted;
}

/**
 * Tag canonical service imports after deduplication.
 *
 * @return void
 */
function extrasport_tag_service_import_sources() {
	if ( ! function_exists( 'extrasport_get_service_image_import_sources' ) ) {
		return;
	}

	foreach ( extrasport_get_service_image_import_sources() as $source ) {
		if ( extrasport_find_attachment_by_import_source( $source ) ) {
			continue;
		}

		$matches = extrasport_find_attachment_ids_for_import_source( $source );
		if ( empty( $matches ) ) {
			continue;
		}

		$keep_id = extrasport_pick_attachment_to_keep( $matches );
		if ( $keep_id ) {
			update_post_meta( $keep_id, EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );
		}
	}
}

/**
 * Remove all known duplicate/orphan attachments from the media library.
 *
 * @return void
 */
function extrasport_cleanup_all_media_duplicates() {
	extrasport_cleanup_legacy_serv_attachments();
	extrasport_cleanup_duplicate_attachments_by_hash();
	extrasport_cleanup_orphan_trainer_attachments();

	if ( function_exists( 'extrasport_cleanup_duplicate_service_attachments' ) ) {
		extrasport_cleanup_duplicate_service_attachments();
	}

	extrasport_tag_service_import_sources();
}

/**
 * Run media cleanup once after theme updates.
 *
 * @return void
 */
function extrasport_maybe_cleanup_media_library() {
	if ( (int) get_option( 'extrasport_media_cleanup_version', 0 ) >= EXTRASPORT_MEDIA_CLEANUP_VERSION ) {
		return;
	}

	extrasport_cleanup_all_media_duplicates();
	update_option( 'extrasport_media_cleanup_version', EXTRASPORT_MEDIA_CLEANUP_VERSION, false );
}
add_action( 'load-upload.php', 'extrasport_maybe_cleanup_media_library' );
add_action( 'load-media.php', 'extrasport_maybe_cleanup_media_library' );
