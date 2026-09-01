<?php
/**
 * Seed group program category pages from the legacy Yii2 database.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION', 1 );

/**
 * Yii group_programs rows used as child pages under group-programs.
 *
 * @return array<int, object>
 */
function extrasport_get_yii_group_program_rows() {
	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return array();
	}

	$rows = $yii->get_results(
		'SELECT id, alias, title, intro, img, position
		FROM group_programs
		WHERE status = 10 AND id NOT IN (1, 2)
		ORDER BY position ASC'
	);

	return is_array( $rows ) ? $rows : array();
}

/**
 * Legacy demo slugs => Yii aliases.
 *
 * @return array<string, string>
 */
function extrasport_get_group_program_legacy_slug_redirects() {
	return array(
		'step-aerobics'  => 'aerobnye-klassy',
		'trx'            => 'skola-funkcionalnogo-treninga',
		'power-classes'  => 'silovye-klassy',
		'special'        => 'specialnye-klassy',
		'dance'          => 'tancevalnye-napravlenia',
		'stretch'        => 'smesannyj-format',
		'yoga'           => 'specialnye-klassy',
		'martial-arts'   => 'smesannyj-format',
	);
}

/**
 * Plain intro text for a Yii group program row.
 *
 * @param object $row Group program row.
 * @return string
 */
function extrasport_get_yii_group_program_intro( $row ) {
	if ( ! $row ) {
		return '';
	}

	$intro = trim( wp_strip_all_tags( html_entity_decode( (string) ( $row->intro ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
	if ( $intro ) {
		return wp_html_excerpt( $intro, 220, '&hellip;' );
	}

	return extrasport_get_intro_from_html( extrasport_build_yii_group_program_content( (string) $row->alias ) );
}

/**
 * Create or update child service pages for Yii group programs.
 *
 * @param bool $force Force re-seed.
 * @return void
 */
function extrasport_seed_group_programs( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_group_programs_seed_version', 0 ) >= EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION ) {
		return;
	}

	$yii_rows = extrasport_get_yii_group_program_rows();
	if ( ! $yii_rows ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( 'group-programs' );
	if ( ! $parent_id ) {
		return;
	}

	update_post_meta( $parent_id, '_service_card_mode', 'group' );

	$valid_slugs = array();
	$image_cache = array();
	$menu_order  = 0;

	foreach ( $yii_rows as $row ) {
		$slug = sanitize_title( (string) $row->alias );
		if ( ! $slug ) {
			continue;
		}

		$valid_slugs[] = $slug;
		$menu_order   += 10;

		$content   = extrasport_build_yii_group_program_content( (string) $row->alias );
		$intro     = extrasport_get_yii_group_program_intro( $row );
		$excerpt   = trim( wp_strip_all_tags( html_entity_decode( (string) ( $row->intro ?? '' ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		$post_id   = extrasport_find_service_child_by_slug( $parent_id, $slug );
		$post_data = array(
			'post_type'    => 'service',
			'post_status'  => 'publish',
			'post_title'   => (string) $row->title,
			'post_name'    => $slug,
			'post_excerpt' => $excerpt,
			'post_content' => $content ?: '<p>' . esc_html__( 'Контент услуги будет добавлен позже.', 'extrasport' ) . '</p>',
			'post_parent'  => $parent_id,
			'menu_order'   => $menu_order,
		);

		if ( ! $post_id ) {
			$post_id = wp_insert_post( $post_data, true );
		} else {
			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );
		}

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, '_service_intro', $intro );
		delete_post_meta( $post_id, '_service_card_mode' );

		if ( ! empty( $row->img ) ) {
			extrasport_assign_service_media_thumbnail(
				(int) $post_id,
				'group_programs/' . ltrim( (string) $row->img, '/' ),
				$image_cache,
				true
			);
		}
	}

	foreach ( extrasport_get_service_children( $parent_id ) as $child ) {
		if ( ! in_array( $child->post_name, $valid_slugs, true ) ) {
			wp_trash_post( $child->ID );
		}
	}

	extrasport_seed_group_programs_parent_meta( $parent_id );

	update_option( 'extrasport_group_programs_seed_version', EXTRASPORT_GROUP_PROGRAMS_SEED_VERSION, false );
}

/**
 * Import archive intro for the group-programs parent page.
 *
 * @param int $parent_id Parent service ID.
 * @return void
 */
function extrasport_seed_group_programs_parent_meta( $parent_id ) {
	$yii = extrasport_get_yii_db();
	if ( ! $yii || ! $parent_id ) {
		return;
	}

	$params = $yii->get_row( 'SELECT meta_description FROM group_programs_params WHERE id = 1 LIMIT 1' );
	if ( $params && ! empty( $params->meta_description ) ) {
		$intro = wp_html_excerpt(
			trim( wp_strip_all_tags( html_entity_decode( (string) $params->meta_description, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) ),
			220,
			'&hellip;'
		);
		if ( $intro ) {
			update_post_meta( $parent_id, '_service_intro', $intro );
		}
	}

	$service = $yii->get_row(
		$yii->prepare(
			'SELECT content FROM services WHERE alias = %s AND status = 10 LIMIT 1',
			'programs'
		)
	);

	if ( $service && ! empty( $service->content ) ) {
		$content = extrasport_normalize_yii_html( $service->content );
		if ( $content ) {
			wp_update_post(
				array(
					'ID'           => $parent_id,
					'post_content' => $content,
				)
			);
		}
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
