<?php
/**
 * Seed De-vision group programs under /services/group-programs/.
 *
 * Roster follows production https://de-vision.ru/services/programs/
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_GROUP_PROGRAMS_SEED_VERSION', 2 );

/**
 * Canonical group-program cards for De-vision (production order/titles).
 *
 * @return array<int, array{slug: string, title: string, image?: string, image_url?: string, link_to?: string}>
 */
function extrasport_get_devision_group_programs_roster() {
	return array(
		array(
			'slug'    => 'detskij-klub',
			'title'   => 'Детский клуб',
			'image'   => 'deti-1563358900.jpg',
			'link_to' => 'detskij-klub',
		),
		array(
			'slug'      => 'mind-body',
			'title'     => 'Mind Body',
			'image'     => 'sport-fitnes-fon-vzglyad-devushka-1338996-(1)-1626684256.jpg',
			'image_url' => 'https://de-vision.ru/uploads/image/group_programs/sport-fitnes-fon-vzglyad-devushka-1338996-(1)-1626684256.jpg',
		),
		array(
			'slug'  => 'skola-funkcionalnogo-treninga',
			'title' => 'Школа функционального тренинга',
			'image' => 'tren-1563364155.jpg',
		),
		array(
			'slug'  => 'silovye-klassy',
			'title' => 'Силовые классы',
			'image' => 'sila-1563362797.jpg',
		),
		array(
			'slug'  => 'tancevalnye-napravlenia',
			'title' => 'Танцевальные направления',
			'image' => 'tanec-1563363021.jpg',
		),
		array(
			'slug'  => 'cycle',
			'title' => 'Cycle',
			'image' => 'velik-1563364254.jpg',
		),
		array(
			'slug'  => 'aerobnye-klassy',
			'title' => 'Аэробные классы',
			'image' => 'aero-1563362609.jpg',
		),
		array(
			'slug'  => 'specialnye-klassy',
			'title' => 'Специальные классы',
			'image' => 'box-1628289855.jpg',
		),
		array(
			'slug'  => 'smesannyj-format',
			'title' => 'Смешанный формат',
			'image' => 'smeh-1563364456.jpg',
		),
	);
}

/**
 * Import a De-vision group program card image from production.
 *
 * @param string $filename  Image filename under uploads/image/group_programs/.
 * @param string $remote_url Optional exact production URL (needed when filename contains parentheses).
 * @return int Attachment ID or 0.
 */
function extrasport_import_devision_group_program_image( $filename, $remote_url = '' ) {
	$filename   = basename( (string) $filename );
	$remote_url = esc_url_raw( (string) $remote_url );

	if ( ! $filename && $remote_url ) {
		$filename = basename( wp_parse_url( $remote_url, PHP_URL_PATH ) ?: '' );
	}

	if ( ! $filename ) {
		return 0;
	}

	$source   = 'devision/group_programs/' . $filename;
	$existing = extrasport_find_attachment_by_import_source( $source );
	if ( $existing ) {
		return $existing;
	}

	$uploads_dir = extrasport_get_yii_uploads_dir();
	if ( $uploads_dir ) {
		$local_path = $uploads_dir . 'group_programs/' . $filename;
		if ( file_exists( $local_path ) ) {
			$local = extrasport_import_local_image( $local_path, $source );
			if ( $local ) {
				return $local;
			}
		}
	}

	if ( ! $remote_url ) {
		$remote_url = 'https://de-vision.ru/uploads/image/group_programs/' . implode(
			'/',
			array_map( 'rawurlencode', explode( '/', $filename ) )
		);
	}

	return extrasport_import_remote_image( $remote_url, $source );
}

/**
 * Sync De-vision group-program children from the production roster.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_devision_group_programs( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_group_programs_seed_version', 0 ) >= EXTRASPORT_DEVISION_GROUP_PROGRAMS_SEED_VERSION ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( 'group-programs' );
	if ( ! $parent_id ) {
		return;
	}

	update_post_meta( $parent_id, '_service_card_mode', 'group' );

	$valid_slugs = array();
	$menu_order  = 0;

	foreach ( extrasport_get_devision_group_programs_roster() as $item ) {
		$slug = sanitize_title( (string) ( $item['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$link_to   = sanitize_title( (string) ( $item['link_to'] ?? '' ) );
		$image     = (string) ( $item['image'] ?? '' );
		$image_url = esc_url_raw( (string) ( $item['image_url'] ?? '' ) );

		// Linked top-level services are shown as cards only — no duplicate child posts.
		if ( $link_to ) {
			$mirror_id = extrasport_find_service_child_by_slug( $parent_id, $slug );
			if ( $mirror_id ) {
				wp_delete_post( $mirror_id, true );
			}
			if ( $image || $image_url ) {
				extrasport_import_devision_group_program_image( $image, $image_url );
			}
			continue;
		}

		$valid_slugs[] = $slug;
		$menu_order   += 10;
		$title         = sanitize_text_field( (string) ( $item['title'] ?? '' ) );
		$post_id       = extrasport_find_service_child_by_slug( $parent_id, $slug );
		$post_data     = array(
			'post_type'   => 'service',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_parent' => $parent_id,
			'menu_order'  => $menu_order,
		);

		if ( $post_id ) {
			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );
		} else {
			$post_data['post_excerpt'] = '';
			$post_data['post_content'] = '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>';
			$inserted                  = wp_insert_post( $post_data, true );
			if ( is_wp_error( $inserted ) || ! $inserted ) {
				continue;
			}
			$post_id = (int) $inserted;
		}

		delete_post_meta( $post_id, '_service_card_mode' );
		delete_post_meta( $post_id, EXTRASPORT_SERVICE_LINK_TO_META );

		if ( $image || $image_url ) {
			$attachment_id = extrasport_import_devision_group_program_image( $image, $image_url );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		} else {
			delete_post_thumbnail( $post_id );
		}
	}

	foreach ( extrasport_get_service_children( $parent_id ) as $child ) {
		if ( ! in_array( $child->post_name, $valid_slugs, true ) ) {
			wp_delete_post( (int) $child->ID, true );
		}
	}

	update_option( 'extrasport_devision_group_programs_seed_version', EXTRASPORT_DEVISION_GROUP_PROGRAMS_SEED_VERSION, false );
}
