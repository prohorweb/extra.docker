<?php
/**
 * Seed group program category pages under /services/group-programs/.
 *
 * Extrasport roster follows production https://piter.extrasport.ru/services/programs/
 * (not the Yii DB category titles).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION', 4 );

/**
 * Canonical group-program cards for extrasport (Piter production order/titles).
 *
 * @return array<int, array{slug: string, title: string, image?: string, link_to?: string}>
 */
function extrasport_get_extrasport_group_programs_roster() {
	return array(
		array(
			'slug'    => 'detskij-klub',
			'title'   => 'Детский клуб',
			'image'   => 'Junior-Yoga-ot-6-let-1556304345.jpg',
			'link_to' => 'detskij-klub',
		),
		array(
			'slug'    => 'bassejn',
			'title'   => 'Бассейн',
			'image'   => 'Obucenie-plavaniu-dla-detej-1556304372.jpg',
			'link_to' => 'bassejn',
		),
		array(
			'slug'  => 'smesannyj-format',
			'title' => 'СТЕП- Аэробика.',
			'image' => 'Step-Interval-1556304045.jpg',
		),
		array(
			'slug'  => 'specialnye-klassy',
			'title' => 'TRX и Жиросжигание',
			'image' => 'Fit-Ball-1556303856.jpg',
		),
		array(
			'slug'  => 'silovye-klassy',
			'title' => 'Силовые классы',
			'image' => 'Body-Sculpt-1556303798.jpg',
		),
		array(
			'slug'  => 'aerobnye-klassy',
			'title' => 'Специальные классы',
			'image' => 'IMG_20230913_131015_631-1695325447.jpg',
		),
		array(
			'slug'  => 'skola-funkcionalnogo-treninga',
			'title' => 'Йога',
			'image' => 'sport-fitnes-fon-vzglyad-devushka-1338996-(1)-1695326043.jpg',
		),
		array(
			'slug'  => 'tancevalnye-napravlenia',
			'title' => 'Танцевальные направления',
			'image' => 'Strip-Dance-1556303903.jpg',
		),
		array(
			'slug'  => 'cycle',
			'title' => 'Велотренинг',
			'image' => 'Cycle-Strength-1556303993.jpg',
		),
		array(
			'slug'    => 'tematiceskie-programmy-helluin',
			'title'   => 'Боевые искусства',
			'image'   => '1483561029_240083-1695326186.jpg',
			'link_to' => 'boevye-iskusstva',
		),
		array(
			'slug'  => 'latina-pro',
			'title' => 'Latina Pro',
			'image' => '',
		),
		array(
			'slug'  => 'rastazka',
			'title' => 'Растяжка',
			'image' => '',
		),
	);
}

/**
 * Legacy demo slugs => current program child slugs.
 *
 * @return array<string, string>
 */
function extrasport_get_group_program_legacy_slug_redirects() {
	return array(
		'step-aerobics'  => 'smesannyj-format',
		'trx'            => 'specialnye-klassy',
		'power-classes'  => 'silovye-klassy',
		'special'        => 'aerobnye-klassy',
		'dance'          => 'tancevalnye-napravlenia',
		'stretch'        => 'rastazka',
		'yoga'           => 'skola-funkcionalnogo-treninga',
		'martial-arts'   => 'tematiceskie-programmy-helluin',
		'velotraining'   => 'cycle',
	);
}

/**
 * Import a group program card image from the public Piter site.
 *
 * @param string $filename Image filename under uploads/image/group_programs/.
 * @return int Attachment ID or 0.
 */
function extrasport_import_production_group_program_image( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( ! $filename ) {
		return 0;
	}

	$source = 'production/group_programs/' . $filename;
	$existing = extrasport_find_attachment_by_import_source( $source );
	if ( $existing ) {
		return $existing;
	}

	// Prefer local Yii upload mirror when available.
	$local = extrasport_import_service_image( 'group_programs/' . $filename );
	if ( $local ) {
		update_post_meta( $local, EXTRASPORT_IMPORT_SOURCE_META_KEY, $source );
		return $local;
	}

	$remote = 'https://piter.extrasport.ru/uploads/image/group_programs/' . rawurlencode( $filename );
	return extrasport_import_remote_image( $remote, $source );
}

/**
 * Sync extrasport group-program children from the production roster.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_extrasport_group_programs( $force = false ) {
	if ( ! extrasport_is_extrasport_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_group_programs_seed_version', 0 ) >= EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( 'group-programs' );
	if ( ! $parent_id ) {
		return;
	}

	update_post_meta( $parent_id, '_service_card_mode', 'group' );

	$valid_slugs = array();
	$menu_order  = 0;

	foreach ( extrasport_get_extrasport_group_programs_roster() as $item ) {
		$slug = sanitize_title( (string) ( $item['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$link_to = sanitize_title( (string) ( $item['link_to'] ?? '' ) );

		$image = (string) ( $item['image'] ?? '' );

		// Linked top-level services are shown as cards only — no duplicate child posts.
		if ( $link_to ) {
			$mirror_id = extrasport_find_service_child_by_slug( $parent_id, $slug );
			if ( $mirror_id ) {
				wp_delete_post( $mirror_id, true );
			}
			if ( $image ) {
				extrasport_import_production_group_program_image( $image );
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

		if ( $image ) {
			$attachment_id = extrasport_import_production_group_program_image( $image );
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

	update_option( 'extrasport_group_programs_seed_version', EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION, false );
}

/**
 * Group-program roster for the current club.
 *
 * @return array<int, array{slug: string, title: string, image?: string, link_to?: string}>
 */
function extrasport_get_current_group_programs_roster() {
	if ( extrasport_is_devision_site() && function_exists( 'extrasport_get_devision_group_programs_roster' ) ) {
		return extrasport_get_devision_group_programs_roster();
	}

	if ( extrasport_is_extrasport_site() ) {
		return extrasport_get_extrasport_group_programs_roster();
	}

	return array();
}

/**
 * Create or update child service pages for group programs.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_group_programs( $force = false ) {
	if ( extrasport_is_extrasport_site() ) {
		extrasport_seed_extrasport_group_programs( $force );
		return;
	}

	if ( extrasport_is_devision_site() && function_exists( 'extrasport_seed_devision_group_programs' ) ) {
		extrasport_seed_devision_group_programs( $force );
	}
}

/**
 * Seed group programs when services admin is opened.
 *
 * @param WP_Screen|null $screen Current screen.
 * @return void
 */
function extrasport_seed_group_programs_on_screen( $screen ) {
	if ( ! $screen instanceof WP_Screen || 'edit' !== $screen->base || 'service' !== $screen->post_type ) {
		return;
	}

	extrasport_seed_group_programs( false );
}
add_action( 'current_screen', 'extrasport_seed_group_programs_on_screen' );

/**
 * Seed group programs on front-end bootstrap (once per version).
 *
 * @return void
 */
function extrasport_maybe_seed_group_programs() {
	if ( is_admin() && ! wp_doing_cron() ) {
		return;
	}

	extrasport_seed_group_programs( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_group_programs', 34 );
