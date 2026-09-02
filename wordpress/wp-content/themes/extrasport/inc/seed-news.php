<?php
/**
 * Import news from the legacy Yii2 database (Piter club).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_NEWS_SEED_VERSION', 2 );

/**
 * Resolve Yii news image on disk.
 *
 * @param string $filename Image filename.
 * @return string
 */
function extrasport_resolve_yii_news_image_path( $filename ) {
	return extrasport_resolve_yii_upload_image_path( 'news', sanitize_file_name( (string) $filename ) );
}

/**
 * Import Yii news image.
 *
 * @param string $filename Image filename.
 * @return int Attachment ID or 0.
 */
function extrasport_import_news_photo( $filename ) {
	$filename = sanitize_file_name( (string) $filename );
	if ( ! $filename ) {
		return 0;
	}

	$source = 'yii/news/' . $filename;

	$existing_id = extrasport_find_attachment_by_import_source( $source );
	if ( $existing_id ) {
		return $existing_id;
	}

	$path = extrasport_resolve_yii_news_image_path( $filename );
	if ( ! $path ) {
		return 0;
	}

	return extrasport_import_local_image( $path, $source );
}

/**
 * Whether news import should run on the current site.
 *
 * @return bool
 */
function extrasport_should_seed_news_for_current_site() {
	return 'extrasport' === extrasport_get_current_club_slug();
}

/**
 * Seed news once per site/version.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_news( $force = false ) {
	if ( ! extrasport_should_seed_news_for_current_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_news_seed_version', 0 ) >= EXTRASPORT_NEWS_SEED_VERSION ) {
		return;
	}

	$yii = extrasport_get_yii_db();
	if ( ! $yii ) {
		return;
	}

	$rows = $yii->get_results(
		"SELECT id, title, date, intro, img, content, alias, meta_title, meta_keywords, meta_description, position
		FROM news
		WHERE status = 10
		ORDER BY date DESC, position ASC, id DESC"
	);

	if ( ! is_array( $rows ) ) {
		return;
	}

	foreach ( $rows as $row ) {
		$slug = sanitize_title( (string) $row->alias );
		if ( ! $slug ) {
			continue;
		}

		$post_id = extrasport_find_news_post_id_by_slug( $slug );
		$date    = sanitize_text_field( (string) $row->date );
		$time    = $date ? strtotime( $date . ' 12:00:00' ) : false;
		$title   = sanitize_text_field( (string) $row->title );
		$content = wp_kses_post( (string) $row->content );
		$intro   = extrasport_build_news_intro( $content, $title );
		if ( ! $intro ) {
			$intro = sanitize_textarea_field( (string) $row->intro );
		}

		$post_data = array(
			'post_type'    => 'news',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $intro,
			'post_status'  => 'publish',
			'menu_order'   => (int) $row->position,
		);

		if ( $time ) {
			$post_data['post_date']     = wp_date( 'Y-m-d H:i:s', $time );
			$post_data['post_date_gmt'] = get_gmt_from_date( $post_data['post_date'] );
		}

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

		update_post_meta( $post_id, EXTRASPORT_NEWS_DATE_META, $date );
		update_post_meta( $post_id, EXTRASPORT_NEWS_INTRO_META, $intro );
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_TITLE, sanitize_text_field( (string) $row->meta_title ) );
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_KEYWORDS, sanitize_text_field( (string) $row->meta_keywords ) );
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_DESCRIPTION, sanitize_textarea_field( (string) $row->meta_description ) );

		if ( ! empty( $row->img ) ) {
			$attachment_id = extrasport_import_news_photo( (string) $row->img );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
	}

	$seo = $yii->get_row( "SELECT title, keywords, description FROM seo WHERE type = 'news' LIMIT 1" );
	if ( $seo ) {
		update_option(
			'extrasport_news_archive_seo',
			array(
				'title'       => sanitize_text_field( (string) $seo->title ),
				'keywords'    => sanitize_text_field( (string) $seo->keywords ),
				'description' => sanitize_textarea_field( (string) $seo->description ),
			),
			false
		);
	}

	update_option( 'extrasport_news_seed_version', EXTRASPORT_NEWS_SEED_VERSION, false );
}

/**
 * Seed news on front-end bootstrap.
 *
 * @return void
 */
function extrasport_maybe_seed_news() {
	if ( is_admin() ) {
		return;
	}

	extrasport_seed_news( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_news', 37 );
