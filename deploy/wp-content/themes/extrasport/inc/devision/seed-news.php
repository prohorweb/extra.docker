<?php
/**
 * Import De-vision news from production (/dv/news/ + pagination).
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_DEVISION_NEWS_SEED_VERSION', 2 );

/**
 * Production news list URLs for De-vision.
 *
 * @return array<int, string>
 */
function extrasport_get_devision_news_list_urls() {
	return array(
		'dv/news/',
		'news/index/?page=2',
		'news/index/?page=3',
		'news/index/?page=4',
		'news/index/?page=5',
	);
}

/**
 * Map Russian genitive month name to number.
 *
 * @param string $month Month name.
 * @return int
 */
function extrasport_devision_news_month_number( $month ) {
	$map = array(
		'января'   => 1,
		'февраля'  => 2,
		'марта'    => 3,
		'апреля'   => 4,
		'мая'      => 5,
		'июня'     => 6,
		'июля'     => 7,
		'августа'  => 8,
		'сентября' => 9,
		'октября'  => 10,
		'ноября'   => 11,
		'декабря'  => 12,
	);

	return (int) ( $map[ mb_strtolower( trim( (string) $month ) ) ] ?? 0 );
}

/**
 * Parse news cards from a list HTML page.
 *
 * @param string $html Raw HTML.
 * @return array<int, array{slug: string, title: string, intro: string, date: string}>
 */
function extrasport_parse_devision_news_list_html( $html ) {
	$items = array();

	if ( ! $html || ! preg_match_all( '/news-blog__item[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/', $html, $blocks ) ) {
		return $items;
	}

	foreach ( $blocks[0] as $block ) {
		if ( ! preg_match( '/\/dv\/news\/([^"\/]+)\//', $block, $slug_match ) ) {
			continue;
		}

		$slug = sanitize_title( $slug_match[1] );
		if ( ! $slug ) {
			continue;
		}

		$title = '';
		if ( preg_match( '/news-block__title[\s\S]*?<a[^>]*>([\s\S]*?)<\/a>/', $block, $title_match ) ) {
			$title = sanitize_text_field( html_entity_decode( wp_strip_all_tags( $title_match[1] ), ENT_QUOTES, 'UTF-8' ) );
		}

		$intro = '';
		if ( preg_match( '/news-block__text[^>]*>([\s\S]*?)<\/div>/', $block, $intro_match ) ) {
			$intro = sanitize_textarea_field( html_entity_decode( wp_strip_all_tags( $intro_match[1] ), ENT_QUOTES, 'UTF-8' ) );
		}

		$date = '';
		if ( preg_match( '/<h3[^>]*>(\d{1,2})<\/h3>\s*<span[^>]*>([^<]+)<\/span>\s*<span[^>]*>(\d{4})\s*года<\/span>/u', $block, $date_match ) ) {
			$month = extrasport_devision_news_month_number( $date_match[2] );
			if ( $month ) {
				$date = sprintf( '%04d-%02d-%02d', (int) $date_match[3], $month, (int) $date_match[1] );
			}
		}

		$items[] = array(
			'slug'  => $slug,
			'title' => $title,
			'intro' => $intro,
			'date'  => $date,
		);
	}

	return $items;
}

/**
 * Fetch and parse a De-vision news single page.
 *
 * @param string $slug News slug.
 * @return array{content: string, meta_title: string, meta_description: string, meta_keywords: string, image: string}
 */
function extrasport_fetch_devision_production_news_page( $slug ) {
	$slug  = sanitize_title( (string) $slug );
	$empty = array(
		'content'          => '',
		'meta_title'       => '',
		'meta_description' => '',
		'meta_keywords'    => '',
		'image'            => '',
	);

	if ( ! $slug ) {
		return $empty;
	}

	$html = extrasport_fetch_devision_production_html( 'dv/news/' . $slug . '/' );
	if ( ! $html ) {
		return $empty;
	}

	if ( preg_match( '/<div class="news-block__text">([\s\S]*?)<\/div>\s*<\/div>/', $html, $content_match ) ) {
		$empty['content'] = extrasport_normalize_yii_html( $content_match[1] );
	}

	if ( preg_match( '/<meta name="description" content="([^"]*)"/', $html, $desc_match ) ) {
		$empty['meta_description'] = sanitize_textarea_field( html_entity_decode( $desc_match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '/<meta name="keywords" content="([^"]*)"/', $html, $keywords_match ) ) {
		$empty['meta_keywords'] = sanitize_text_field( html_entity_decode( $keywords_match[1], ENT_QUOTES, 'UTF-8' ) );
	}

	if ( preg_match( '/<h2[^>]*class="section-heading"[^>]*>(.*?)<\/h2>/s', $html, $title_match ) ) {
		$empty['meta_title'] = sanitize_text_field( wp_strip_all_tags( $title_match[1] ) );
	}

	if ( preg_match( '/uploads\/image\/(?:ckeditor|news)\/([^"\'\?]+)/', $html, $image_match ) ) {
		$empty['image'] = sanitize_file_name( wp_basename( html_entity_decode( $image_match[1], ENT_QUOTES, 'UTF-8' ) ) );
	}

	return $empty;
}

/**
 * Sideload remote images inside news HTML and rewrite src to local URLs.
 *
 * @param string $html News HTML.
 * @return string
 */
function extrasport_sideload_devision_news_content_images( $html ) {
	$html = (string) $html;
	if ( ! $html ) {
		return '';
	}

	return (string) preg_replace_callback(
		'/(src=["\'])(https?:\/\/de-vision\.ru)?(\/uploads\/image\/(?:ckeditor|news)\/[^"\']+)(["\'])/i',
		static function ( $matches ) {
			$path   = html_entity_decode( $matches[3], ENT_QUOTES, 'UTF-8' );
			$url    = 'https://de-vision.ru' . $path;
			$source = 'production/news/' . sanitize_file_name( wp_basename( $path ) );
			$att_id = extrasport_import_remote_image( $url, $source );

			if ( ! $att_id ) {
				return $matches[0];
			}

			$local = wp_get_attachment_url( $att_id );
			if ( ! $local ) {
				return $matches[0];
			}

			return $matches[1] . esc_url( $local ) . $matches[4];
		},
		$html
	);
}

/**
 * Import a De-vision news featured image from production.
 *
 * @param string $filename Image filename.
 * @param string $subdir   news|ckeditor.
 * @return int
 */
function extrasport_import_devision_news_photo( $filename, $subdir = 'ckeditor' ) {
	$filename = sanitize_file_name( (string) $filename );
	$subdir   = sanitize_key( (string) $subdir );
	if ( ! $filename ) {
		return 0;
	}

	$source = 'production/news/' . $filename;
	$remote = extrasport_get_legacy_club_public_base_url() . '/uploads/image/' . $subdir . '/' . rawurlencode( $filename );

	return extrasport_import_remote_image( $remote, $source );
}

/**
 * Collect news list items from all production pages.
 *
 * @return array<string, array{slug: string, title: string, intro: string, date: string}>
 */
function extrasport_collect_devision_news_list_items() {
	$items = array();

	foreach ( extrasport_get_devision_news_list_urls() as $path ) {
		$html = extrasport_fetch_devision_production_html( $path );
		foreach ( extrasport_parse_devision_news_list_html( $html ) as $item ) {
			$slug = $item['slug'];
			if ( ! $slug ) {
				continue;
			}
			$items[ $slug ] = $item;
		}
	}

	return $items;
}

/**
 * Seed De-vision news from production pages.
 *
 * @param bool $force Force re-import.
 * @return void
 */
function extrasport_seed_devision_news( $force = false ) {
	if ( ! extrasport_is_devision_site() ) {
		return;
	}

	if ( ! $force && (int) get_option( 'extrasport_devision_news_seed_version', 0 ) >= EXTRASPORT_DEVISION_NEWS_SEED_VERSION ) {
		return;
	}

	$items = extrasport_collect_devision_news_list_items();
	if ( ! $items ) {
		return;
	}

	$published_slugs = array();

	foreach ( $items as $item ) {
		$slug = sanitize_title( (string) ( $item['slug'] ?? '' ) );
		if ( ! $slug ) {
			continue;
		}

		$published_slugs[] = $slug;
		$remote            = extrasport_fetch_devision_production_news_page( $slug );
		$content           = extrasport_sideload_devision_news_content_images( (string) ( $remote['content'] ?? '' ) );
		$title             = sanitize_text_field( (string) ( $item['title'] ?? '' ) );
		$list_intro        = sanitize_textarea_field( (string) ( $item['intro'] ?? '' ) );
		$intro             = extrasport_build_news_intro( $content, $title );
		if ( ! $intro ) {
			$intro = $list_intro;
		}
		$post_id = extrasport_find_news_post_id_by_slug( $slug );
		$date    = sanitize_text_field( (string) ( $item['date'] ?? '' ) );
		$time    = $date ? strtotime( $date . ' 12:00:00' ) : false;

		$post_data = array(
			'post_type'    => 'news',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $intro,
			'post_status'  => 'publish',
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
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_TITLE, sanitize_text_field( (string) ( $remote['meta_title'] ?: $item['title'] ) ) );
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_KEYWORDS, sanitize_text_field( (string) ( $remote['meta_keywords'] ?? '' ) ) );
		update_post_meta( $post_id, EXTRASPORT_NEWS_META_DESCRIPTION, sanitize_textarea_field( (string) ( $remote['meta_description'] ?? '' ) ) );

		if ( ! empty( $remote['image'] ) ) {
			$subdir        = false !== strpos( (string) $remote['content'], '/ckeditor/' ) ? 'ckeditor' : 'news';
			$attachment_id = extrasport_import_devision_news_photo( (string) $remote['image'], $subdir );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
	}

	$existing = get_posts(
		array(
			'post_type'              => 'news',
			'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'trash' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $existing as $existing_id ) {
		$post = get_post( (int) $existing_id );
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		if ( in_array( $post->post_name, $published_slugs, true ) ) {
			continue;
		}
		wp_delete_post( (int) $existing_id, true );
	}

	update_option(
		'extrasport_news_archive_seo',
		array(
			'title'       => __( 'Новости клуба De-vision', 'extrasport' ),
			'keywords'    => '',
			'description' => __( 'Новости фитнес-клуба De-vision в ТРЦ «Родео Драйв».', 'extrasport' ),
		),
		false
	);

	update_option( 'extrasport_devision_news_seed_version', EXTRASPORT_DEVISION_NEWS_SEED_VERSION, false );
}
