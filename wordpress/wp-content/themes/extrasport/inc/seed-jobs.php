<?php
/**
 * Import jobs from the legacy Yii2 database (Piter club).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_JOBS_SEED_VERSION', 1 );

/**
 * Whether jobs import should run on the current site.
 *
 * @return bool
 */
function extrasport_should_seed_jobs_for_current_site() {
	return 'extrasport' === extrasport_get_current_club_slug();
}

/**
 * Seed jobs once per site/version.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_jobs( $force = false ) {
	if ( ! extrasport_should_seed_jobs_for_current_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_jobs_seed_version', 0 ) >= EXTRASPORT_JOBS_SEED_VERSION ) {
		return;
	}

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return;
	}

	$rows = $yii->get_results(
		"SELECT id, status, position, title, content, alias, meta_title, meta_keywords, meta_description
		FROM jobs
		WHERE status = 10
		ORDER BY position ASC, id ASC"
	);

	if ( ! is_array( $rows ) ) {
		return;
	}

	foreach ( $rows as $row ) {
		$slug = sanitize_title( (string) $row->alias );
		if ( ! $slug ) {
			$slug = sanitize_title( (string) $row->title );
		}
		if ( ! $slug ) {
			continue;
		}

		$post_id = extrasport_find_job_post_id_by_slug( $slug );

		$post_data = array(
			'post_type'    => 'job',
			'post_name'    => $slug,
			'post_title'   => sanitize_text_field( (string) $row->title ),
			'post_content' => wp_kses_post( (string) $row->content ),
			'post_status'  => 'publish',
			'menu_order'   => (int) $row->position,
		);

		if ( $post_id ) {
			$post_data['ID'] = $post_id;
			wp_update_post( $post_data );
		} else {
			$inserted = wp_insert_post( $post_data, true );
			if ( is_wp_error( $inserted ) || ! $inserted ) {
				continue;
			}
			$post_id = (int) $inserted;
		}

		update_post_meta( $post_id, EXTRASPORT_JOB_META_TITLE, sanitize_text_field( (string) $row->meta_title ) );
		update_post_meta( $post_id, EXTRASPORT_JOB_META_KEYWORDS, sanitize_text_field( (string) $row->meta_keywords ) );
		update_post_meta( $post_id, EXTRASPORT_JOB_META_DESCRIPTION, sanitize_textarea_field( (string) $row->meta_description ) );
	}

	$seo = $yii->get_row( "SELECT title, keywords, description, text FROM seo WHERE type = 'jobs' LIMIT 1" );
	if ( $seo ) {
		update_option(
			'extrasport_jobs_archive_seo',
			array(
				'title'       => sanitize_text_field( (string) $seo->title ),
				'keywords'    => sanitize_text_field( (string) $seo->keywords ),
				'description' => sanitize_textarea_field( (string) $seo->description ),
				'text'        => wp_kses_post( (string) $seo->text ),
			),
			false
		);
	}

	update_option( 'extrasport_jobs_seed_version', EXTRASPORT_JOBS_SEED_VERSION, false );
}

/**
 * Seed jobs on front-end bootstrap.
 *
 * @return void
 */
function extrasport_maybe_seed_jobs() {
	if ( is_admin() ) {
		return;
	}

	extrasport_seed_jobs( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_jobs', 37 );
