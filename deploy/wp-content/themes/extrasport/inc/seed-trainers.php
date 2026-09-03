<?php
/**
 * Import trainers from the legacy Yii2 database.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_TRAINERS_SEED_VERSION', 3 );

/**
 * Resolve Yii trainer photo on disk.
 *
 * @param string $filename Image filename.
 * @return string
 */
function extrasport_resolve_yii_trainer_image_path( $filename ) {
	return extrasport_resolve_yii_upload_image_path( 'trainer', sanitize_file_name( (string) $filename ) );
}

/**
 * Resolve Yii trainer banner on disk.
 *
 * @param string $filename Banner filename.
 * @return string
 */
function extrasport_resolve_yii_trainer_banner_path( $filename ) {
	$filename = sanitize_file_name( (string) $filename );

	foreach ( array( 'banners/1440', 'banners' ) as $subdir ) {
		$path = extrasport_resolve_yii_upload_image_path( $subdir, $filename );
		if ( $path ) {
			return $path;
		}
	}

	return '';
}

/**
 * Import Yii trainer photo.
 *
 * @param string $filename Image filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_trainer_photo( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	$source   = 'yii/trainers/' . $filename;

	$existing_id = extrasport_find_attachment_by_import_source( $source );
	if ( $existing_id ) {
		return $existing_id;
	}

	$path = extrasport_resolve_yii_trainer_image_path( $filename );
	if ( ! $path ) {
		return 0;
	}

	return extrasport_import_local_image( $path, $source );
}

/**
 * Import Yii trainer banner image.
 *
 * @param string $filename Banner filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_trainer_banner_image( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	$source   = 'yii/trainer-banners/' . $filename;

	$existing_id = extrasport_find_attachment_by_import_source( $source );
	if ( $existing_id ) {
		return $existing_id;
	}

	$path = extrasport_resolve_yii_trainer_banner_path( $filename );
	if ( ! $path ) {
		return 0;
	}

	return extrasport_import_local_image( $path, $source );
}

/**
 * Find trainer post by slug in any status.
 *
 * @param string $slug Trainer slug.
 * @return int Post ID or 0.
 */
function extrasport_find_trainer_post_id_by_slug( $slug ) {
	$slug = sanitize_title( (string) $slug );
	if ( ! $slug ) {
		return 0;
	}

	$posts = get_posts(
		array(
			'post_type'              => 'trainer',
			'name'                   => $slug,
			'post_status'            => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return ! empty( $posts[0] ) ? (int) $posts[0] : 0;
}

/**
 * Seed trainer directions from Yii (trainer_options_type).
 *
 * @param wpdb $yii Yii DB connection.
 * @return array<int, int> Yii option ID => WP term ID.
 */
function extrasport_seed_trainer_directions( $yii ) {
	$map         = array();
	$valid_terms = array();
	$rows        = $yii->get_results(
		'SELECT id, title, position FROM trainer_options_type WHERE status = 10 ORDER BY position ASC'
	);

	if ( ! is_array( $rows ) ) {
		return $map;
	}

	foreach ( $rows as $row ) {
		$yii_id = (int) $row->id;
		$slug   = (string) $yii_id;
		$title  = (string) $row->title;
		$term   = get_term_by( 'slug', $slug, 'trainer_direction' );

		if ( ! $term ) {
			$by_name = term_exists( $title, 'trainer_direction' );
			if ( $by_name ) {
				$term_id = (int) ( is_array( $by_name ) ? $by_name['term_id'] : $by_name );
				wp_update_term(
					$term_id,
					'trainer_direction',
					array(
						'name' => $title,
						'slug' => $slug,
					)
				);
			} else {
				$created = wp_insert_term(
					$title,
					'trainer_direction',
					array(
						'slug' => $slug,
					)
				);
				if ( is_wp_error( $created ) ) {
					continue;
				}
				$term_id = (int) $created['term_id'];
			}
		} else {
			$term_id = (int) $term->term_id;
			if ( $term->name !== $title ) {
				wp_update_term(
					$term_id,
					'trainer_direction',
					array(
						'name' => $title,
					)
				);
			}
		}

		update_term_meta( $term_id, EXTRASPORT_TRAINER_DIRECTION_YII_META, $yii_id );

		$map[ $yii_id ]        = $term_id;
		$valid_terms[]         = $term_id;
	}

	extrasport_cleanup_trainer_direction_terms( $valid_terms );

	return $map;
}

/**
 * Remove duplicate trainer direction terms not linked to Yii options.
 *
 * @param array<int> $valid_term_ids Valid term IDs.
 * @return void
 */
function extrasport_cleanup_trainer_direction_terms( array $valid_term_ids ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'trainer_direction',
			'hide_empty' => false,
			'fields'     => 'ids',
		)
	);

	if ( ! is_array( $terms ) ) {
		return;
	}

	foreach ( $terms as $term_id ) {
		if ( ! in_array( (int) $term_id, $valid_term_ids, true ) ) {
			wp_delete_term( (int) $term_id, 'trainer_direction' );
		}
	}
}

/**
 * Seed trainers once per site/version.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_trainers( $force = false ) {
	if ( ! extrasport_is_extrasport_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_trainers_seed_version', 0 ) >= EXTRASPORT_TRAINERS_SEED_VERSION ) {
		return;
	}

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return;
	}

	$direction_map = extrasport_seed_trainer_directions( $yii );

	$rows = $yii->get_results(
		'SELECT id, status, position, title, post, img, content, alias, meta_title, meta_keywords, meta_description
		FROM trainers
		ORDER BY position ASC'
	);

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			$slug = sanitize_title( (string) $row->alias );
			if ( ! $slug ) {
				continue;
			}

			$post_id = extrasport_find_trainer_post_id_by_slug( $slug );
			$data    = array(
				'post_type'    => 'trainer',
				'post_title'   => (string) $row->title,
				'post_name'    => $slug,
				'post_content' => extrasport_normalize_yii_html( $row->content ),
				'post_status'  => 10 === (int) $row->status ? 'publish' : 'draft',
				'menu_order'   => (int) $row->position,
			);

			if ( $post_id ) {
				$data['ID'] = $post_id;
				wp_update_post( $data );
			} else {
				$inserted = wp_insert_post( $data, true );
				if ( is_wp_error( $inserted ) ) {
					continue;
				}
				$post_id = (int) $inserted;
			}

			if ( ! $post_id ) {
				continue;
			}

			update_post_meta( $post_id, EXTRASPORT_TRAINER_POST_META, sanitize_text_field( (string) $row->post ) );
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_TITLE, sanitize_text_field( (string) $row->meta_title ) );
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_KEYWORDS, sanitize_text_field( (string) $row->meta_keywords ) );
			update_post_meta( $post_id, EXTRASPORT_TRAINER_META_DESCRIPTION, sanitize_textarea_field( (string) $row->meta_description ) );

			$attachment_id = 0;
			if ( ! empty( $row->img ) ) {
				$attachment_id = extrasport_import_trainer_photo( (string) $row->img );
				if ( $attachment_id ) {
					set_post_thumbnail( $post_id, $attachment_id );
				}
			}

			$option_rows = $yii->get_results(
				$yii->prepare(
					'SELECT option_id FROM trainer_options WHERE trainer_id = %d',
					(int) $row->id
				)
			);

			$term_ids = array();
			if ( is_array( $option_rows ) ) {
				foreach ( $option_rows as $option_row ) {
					$yii_option_id = (int) $option_row->option_id;
					if ( isset( $direction_map[ $yii_option_id ] ) ) {
						$term_ids[] = (int) $direction_map[ $yii_option_id ];
					}
				}
			}

			if ( $term_ids ) {
				extrasport_set_trainer_directions( $post_id, array_unique( $term_ids ) );
			} else {
				extrasport_set_trainer_directions( $post_id, extrasport_get_all_trainer_direction_term_ids() );
			}
			extrasport_ensure_trainer_has_directions( $post_id );

			$banner = $yii->get_row(
				$yii->prepare(
					'SELECT img1440 FROM banners WHERE trainer_id = %d ORDER BY id ASC LIMIT 1',
					(int) $row->id
				)
			);

			if ( $banner && ! empty( $banner->img1440 ) ) {
				$banner_filename = sanitize_file_name( (string) $banner->img1440 );
				$thumb_filename  = ! empty( $row->img ) ? sanitize_file_name( (string) $row->img ) : '';
				$banner_id       = 0;

				if ( $attachment_id && $thumb_filename && $banner_filename === $thumb_filename ) {
					$banner_id = (int) $attachment_id;
				} else {
					$banner_id = extrasport_import_trainer_banner_image( $banner_filename );
				}

				if ( $banner_id ) {
					update_post_meta( $post_id, EXTRASPORT_TRAINER_BANNER_META, $banner_id );
				}
			}
		}
	}

	$seo = $yii->get_row( "SELECT title, keywords, description FROM seo WHERE type = 'trainer' LIMIT 1" );
	if ( $seo ) {
		update_option(
			'extrasport_trainers_archive_seo',
			array(
				'title'       => sanitize_text_field( (string) $seo->title ),
				'keywords'    => sanitize_text_field( (string) $seo->keywords ),
				'description' => sanitize_textarea_field( (string) $seo->description ),
			),
			false
		);
	}

	update_option( 'extrasport_trainers_seed_version', EXTRASPORT_TRAINERS_SEED_VERSION, false );

	if ( function_exists( 'extrasport_sync_trainers_roster' ) ) {
		extrasport_sync_trainers_roster( true );
	}
}

/**
 * Seed trainers on front-end bootstrap.
 *
 * @return void
 */
function extrasport_maybe_seed_trainers() {
	if ( is_admin() ) {
		return;
	}

	extrasport_seed_trainers( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_trainers', 38 );
