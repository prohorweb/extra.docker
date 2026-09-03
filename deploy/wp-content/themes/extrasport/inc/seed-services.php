<?php
/**
 * Demo service posts for the services hierarchy.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_SERVICES_SEED_VERSION', 2 );
define( 'EXTRASPORT_SERVICES_CHILDREN_SEED_VERSION', 2 );
define( 'EXTRASPORT_SERVICES_MEDIA_VERSION', 7 );

/**
 * All unique Yii image sources referenced by service seeds.
 *
 * @return array<int, string>
 */
function extrasport_get_service_image_import_sources() {
	$sources = array();

	foreach ( extrasport_get_service_seed_items() as $item ) {
		if ( ! empty( $item['image'] ) ) {
			$sources[] = ltrim( (string) $item['image'], '/' );
		}
	}

	foreach ( extrasport_get_service_child_seed_templates() as $children ) {
		foreach ( $children as $item ) {
			if ( ! empty( $item['image'] ) ) {
				$sources[] = ltrim( (string) $item['image'], '/' );
			}
		}
	}

	return array_values( array_unique( $sources ) );
}

/**
 * Find attachment IDs whose file name matches a Yii source stem.
 *
 * @param string $source Import source key.
 * @return array<int, int>
 */
function extrasport_find_attachment_ids_for_import_source( $source ) {
	global $wpdb;

	$source   = ltrim( (string) $source, '/' );
	$filename = basename( $source );
	$stem     = pathinfo( $filename, PATHINFO_FILENAME );

	if ( ! $stem ) {
		return array();
	}

	$like = '%' . $wpdb->esc_like( $stem ) . '%';
	$ids  = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT p.ID
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
			WHERE p.post_type = 'attachment'
				AND pm.meta_key = '_wp_attached_file'
				AND pm.meta_value LIKE %s",
			$like
		)
	);

	$ids = array_map( 'intval', is_array( $ids ) ? $ids : array() );

	$meta_id = extrasport_find_attachment_by_import_source( $source );
	if ( $meta_id ) {
		$ids[] = $meta_id;
	}

	return array_values( array_unique( array_filter( $ids ) ) );
}

/**
 * Pick the attachment that should be kept for a source group.
 *
 * @param array<int, int> $attachment_ids Attachment IDs.
 * @return int
 */
function extrasport_pick_service_attachment_to_keep( array $attachment_ids ) {
	return extrasport_pick_attachment_to_keep( $attachment_ids );
}

/**
 * Remove duplicate service imports and tag the canonical attachment.
 *
 * @return void
 */
function extrasport_cleanup_duplicate_service_attachments() {
	global $wpdb;

	foreach ( extrasport_get_service_image_import_sources() as $source ) {
		$attachment_ids = extrasport_find_attachment_ids_for_import_source( $source );

		if ( count( $attachment_ids ) <= 1 ) {
			if ( ! empty( $attachment_ids[0] ) ) {
				update_post_meta( $attachment_ids[0], EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );
			}
			continue;
		}

		$keep_id = extrasport_pick_service_attachment_to_keep( $attachment_ids );
		if ( ! $keep_id ) {
			continue;
		}

		update_post_meta( $keep_id, EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );

		foreach ( $attachment_ids as $attachment_id ) {
			if ( (int) $attachment_id === (int) $keep_id ) {
				continue;
			}

			$post_ids = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
					$attachment_id
				)
			);

			foreach ( $post_ids as $post_id ) {
				update_post_meta( (int) $post_id, '_thumbnail_id', $keep_id );
			}

			wp_delete_attachment( (int) $attachment_id, true );
		}
	}
}

/**
 * Seed templates for top-level services.
 *
 * @return array<int, array<string, mixed>>
 */
function extrasport_get_service_seed_items() {
	$items = array(
		array(
			'slug'       => 'personal_training',
			'title'      => 'Персональный тренинг',
			'excerpt'    => 'Индивидуальные занятия с инструктором.',
			'image'      => 'services/personal-1556301977.jpg',
			'card_mode'  => 'page',
			'menu_order' => 10,
			'content'    => '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>',
		),
		array(
			'slug'       => 'bassejn',
			'title'      => 'Бассейн',
			'excerpt'    => 'Плавание и aqua-программы.',
			'image'      => 'services/pool-1556302050.jpg',
			'card_mode'  => 'page',
			'menu_order' => 20,
			'content'    => '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>',
		),
		array(
			'slug'       => 'detskij-klub',
			'title'      => 'Детский клуб',
			'excerpt'    => 'Секции и программы для детей.',
			'image'      => 'services/kids-1556302084.jpg',
			'card_mode'  => 'page',
			'menu_order' => 30,
			'content'    => '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>',
		),
		array(
			'slug'       => 'group-programs',
			'title'      => 'Групповые программы',
			'excerpt'    => 'Направления групповых занятий.',
			'image'      => 'services/group_prog-1556302019.jpg',
			'card_mode'  => 'group',
			'menu_order' => 40,
			'content'    => '',
		),
		array(
			'slug'       => 'boevye-iskusstva',
			'title'      => 'Боевые искусства',
			'excerpt'    => 'Единоборства и силовые классы.',
			'image'      => 'services/fight-1556302125.jpg',
			'card_mode'  => 'group',
			'menu_order' => 50,
			'content'    => '',
		),
	);

	return array_values(
		array_filter(
			$items,
			static function ( $item ) {
				return ! in_array( (string) ( $item['slug'] ?? '' ), extrasport_get_excluded_service_slugs_for_current_site(), true );
			}
		)
	);
}

/**
 * Service slugs that should not exist on the current club site.
 *
 * @return array<int, string>
 */
function extrasport_get_excluded_service_slugs_for_current_site() {
	if ( extrasport_is_devision_site() ) {
		return array( 'boevye-iskusstva', 'boks', 'kikboksing', 'muaj-taj', 'grappling' );
	}

	return array();
}

/**
 * Remove excluded services (and children) for the current club.
 *
 * @return void
 */
function extrasport_cleanup_excluded_services_for_current_site() {
	$excluded = extrasport_get_excluded_service_slugs_for_current_site();
	if ( ! $excluded ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( 'boevye-iskusstva' );
	if ( $parent_id && extrasport_is_devision_site() ) {
		$children = get_posts(
			array(
				'post_type'              => 'service',
				'post_parent'            => $parent_id,
				'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
				'posts_per_page'         => -1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		foreach ( $children as $child_id ) {
			wp_delete_post( (int) $child_id, true );
		}

		wp_delete_post( $parent_id, true );
	}

	foreach ( $excluded as $slug ) {
		$post_id = extrasport_find_service_by_slug( $slug );
		if ( $post_id ) {
			wp_delete_post( $post_id, true );
		}
	}
}

/**
 * Find child service by slug and parent ID.
 *
 * @param int    $parent_id Parent service ID.
 * @param string $slug      Child slug.
 * @return int
 */
function extrasport_find_service_child_by_slug( $parent_id, $slug ) {
	$post = get_posts(
		array(
			'post_type'      => 'service',
			'post_parent'    => (int) $parent_id,
			'name'           => sanitize_title( $slug ),
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);

	return ! empty( $post ) ? (int) $post[0] : 0;
}

/**
 * Import Yii service images and assign them as featured images.
 *
 * @param bool $force Replace existing thumbnails.
 * @return void
 */
function extrasport_seed_service_media( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_services_media_version', 0 ) >= EXTRASPORT_SERVICES_MEDIA_VERSION ) {
		return;
	}

	extrasport_cleanup_all_media_duplicates();

	$image_cache = array();

	foreach ( extrasport_get_service_seed_items() as $item ) {
		$post_id = extrasport_find_top_level_service_by_slug( $item['slug'] );
		if ( $post_id ) {
			extrasport_assign_service_media_thumbnail( $post_id, $item['image'], $image_cache, true );
		}
	}

	foreach ( extrasport_get_service_child_seed_templates() as $parent_slug => $children ) {
		$parent_id = extrasport_find_top_level_service_by_slug( $parent_slug );
		if ( ! $parent_id ) {
			continue;
		}

		foreach ( $children as $item ) {
			$post_id = extrasport_find_service_child_by_slug( $parent_id, $item['slug'] );
			if ( $post_id ) {
				extrasport_assign_service_media_thumbnail( $post_id, $item['image'], $image_cache, true );
			}
		}
	}

	update_option( 'extrasport_services_media_version', EXTRASPORT_SERVICES_MEDIA_VERSION, false );
}

/**
 * Assign a Yii service image to a post thumbnail.
 *
 * @param int                  $post_id     Post ID.
 * @param string               $filename    Image file name.
 * @param array<string, int>   $image_cache Attachment cache.
 * @param bool                 $force       Replace existing thumbnail.
 * @return void
 */
function extrasport_assign_service_media_thumbnail( $post_id, $source, array &$image_cache, $force = false ) {
	$source = ltrim( (string) $source, '/' );

	if ( ! $source ) {
		return;
	}

	if ( ! isset( $image_cache[ $source ] ) ) {
		$image_cache[ $source ] = extrasport_import_service_image( $source );
	}

	if ( empty( $image_cache[ $source ] ) ) {
		return;
	}

	if ( $force || ! has_post_thumbnail( $post_id ) ) {
		set_post_thumbnail( $post_id, $image_cache[ $source ] );
		extrasport_regenerate_attachment_metadata( $image_cache[ $source ] );
	}
}

/**
 * Seed demo services for the current site.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_services( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_services_seed_version', 0 ) >= EXTRASPORT_SERVICES_SEED_VERSION ) {
		extrasport_seed_service_children( $force );
		extrasport_seed_service_media( $force );
		return;
	}

	$existing = wp_count_posts( 'service' );
	if ( ! $force && $existing && (int) $existing->publish > 0 ) {
		update_option( 'extrasport_services_seed_version', EXTRASPORT_SERVICES_SEED_VERSION, false );
		extrasport_seed_service_children( $force );
		extrasport_seed_service_media( $force );
		return;
	}

	$image_cache = array();

	foreach ( extrasport_get_service_seed_items() as $item ) {
		$post_id = extrasport_find_top_level_service_by_slug( $item['slug'] );

		if ( ! $post_id ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'service',
					'post_status'  => 'publish',
					'post_title'   => $item['title'],
					'post_name'    => $item['slug'],
					'post_excerpt' => $item['excerpt'],
					'post_content' => $item['content'],
					'post_parent'  => 0,
					'menu_order'   => (int) $item['menu_order'],
				),
				true
			);
		} else {
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_title'   => $item['title'],
					'post_excerpt' => $item['excerpt'],
					'menu_order'   => (int) $item['menu_order'],
				)
			);
		}

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_service_card_mode', $item['card_mode'] );
		update_post_meta( $post_id, '_service_intro', $item['excerpt'] );
		delete_post_meta( $post_id, '_service_list_source' );

		extrasport_assign_service_media_thumbnail( (int) $post_id, $item['image'], $image_cache, false );
	}

	update_option( 'extrasport_services_seed_version', EXTRASPORT_SERVICES_SEED_VERSION, false );
	extrasport_seed_service_children( $force );
	extrasport_seed_service_media( $force );
}

/**
 * Seed child services under grouped parents.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_service_children( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_services_children_seed_version', 0 ) >= EXTRASPORT_SERVICES_CHILDREN_SEED_VERSION ) {
		return;
	}

	$image_cache = array();

	foreach ( extrasport_get_service_child_seed_templates() as $parent_slug => $children ) {
		$parent_id = extrasport_find_top_level_service_by_slug( $parent_slug );

		if ( ! $parent_id ) {
			continue;
		}

		update_post_meta( $parent_id, '_service_card_mode', 'group' );
		delete_post_meta( $parent_id, '_service_list_source' );

		foreach ( $children as $item ) {
			$post_id = extrasport_find_service_child_by_slug( $parent_id, $item['slug'] );

			if ( ! $post_id ) {
				$post_id = wp_insert_post(
					array(
						'post_type'    => 'service',
						'post_status'  => 'publish',
						'post_title'   => $item['title'],
						'post_name'    => $item['slug'],
						'post_excerpt' => $item['excerpt'],
						'post_content' => '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>',
						'post_parent'  => $parent_id,
						'menu_order'   => (int) $item['menu_order'],
					),
					true
				);
			} else {
				wp_update_post(
					array(
						'ID'           => $post_id,
						'post_title'   => $item['title'],
						'post_excerpt' => $item['excerpt'],
						'post_parent'  => $parent_id,
						'menu_order'   => (int) $item['menu_order'],
					)
				);
			}

			if ( is_wp_error( $post_id ) || ! $post_id ) {
				continue;
			}

			update_post_meta( $post_id, '_service_intro', $item['excerpt'] );
			extrasport_assign_service_media_thumbnail( (int) $post_id, $item['image'], $image_cache, false );
		}
	}

	update_option( 'extrasport_services_children_seed_version', EXTRASPORT_SERVICES_CHILDREN_SEED_VERSION, false );
}

/**
 * Seed services when archive is empty.
 *
 * @return void
 */
function extrasport_maybe_seed_services() {
	if ( is_admin() && ! wp_doing_cron() ) {
		extrasport_seed_service_children( false );
		extrasport_seed_service_media( false );
		extrasport_seed_group_programs( false );
		return;
	}

	extrasport_seed_services( false );
	extrasport_seed_group_programs( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_services', 35 );
