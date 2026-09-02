<?php
/**
 * News helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_NEWS_DATE_META', '_news_date' );
define( 'EXTRASPORT_NEWS_INTRO_META', '_news_intro' );
define( 'EXTRASPORT_NEWS_META_TITLE', '_news_meta_title' );
define( 'EXTRASPORT_NEWS_META_KEYWORDS', '_news_meta_keywords' );
define( 'EXTRASPORT_NEWS_META_DESCRIPTION', '_news_meta_description' );

/**
 * News archive URL.
 *
 * @return string
 */
function extrasport_get_news_archive_url() {
	return get_post_type_archive_link( 'news' ) ?: home_url( '/news/' );
}

/**
 * Find news post by slug in any status.
 *
 * @param string $slug News slug.
 * @return int Post ID or 0.
 */
function extrasport_find_news_post_id_by_slug( $slug ) {
	$slug = sanitize_title( (string) $slug );
	if ( ! $slug ) {
		return 0;
	}

	$posts = get_posts(
		array(
			'post_type'              => 'news',
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
 * Intro text for a news post.
 *
 * @param int|null $post_id News post ID.
 * @return string
 */
function extrasport_get_news_intro( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$intro   = (string) get_post_meta( $post_id, EXTRASPORT_NEWS_INTRO_META, true );

	if ( $intro ) {
		return $intro;
	}

	$post = get_post( $post_id );
	return $post ? (string) get_the_excerpt( $post ) : '';
}

/**
 * Material date stored from Yii (Y-m-d).
 *
 * @param int|null $post_id News post ID.
 * @return string
 */
function extrasport_get_news_date( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$date    = (string) get_post_meta( $post_id, EXTRASPORT_NEWS_DATE_META, true );

	if ( $date ) {
		return $date;
	}

	$post = get_post( $post_id );
	return $post ? get_the_date( 'Y-m-d', $post ) : '';
}

/**
 * Russian month name in genitive case (мая, ноября, …).
 *
 * @param int $month_num Month number 1–12.
 * @return string
 */
function extrasport_get_russian_month_genitive( $month_num ) {
	static $months = array(
		1  => 'января',
		2  => 'февраля',
		3  => 'марта',
		4  => 'апреля',
		5  => 'мая',
		6  => 'июня',
		7  => 'июля',
		8  => 'августа',
		9  => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря',
	);

	return $months[ (int) $month_num ] ?? '';
}

/**
 * Date parts for list cards (Yii-style).
 *
 * @param string $date Date string (Y-m-d).
 * @return array{day: string, month: string, year: string}
 */
function extrasport_format_news_date_parts( $date ) {
	$date = (string) $date;

	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})/', $date, $matches ) ) {
		return array(
			'day'   => '',
			'month' => '',
			'year'  => '',
		);
	}

	return array(
		'day'   => (string) (int) $matches[3],
		'month' => extrasport_get_russian_month_genitive( (int) $matches[2] ),
		'year'  => $matches[1],
	);
}

/**
 * Normalize news post into card data.
 *
 * @param WP_Post $news News post.
 * @return array{title: string, excerpt: string, date: string, day: string, month: string, year: string, url: string}
 */
function extrasport_normalize_news_post( WP_Post $news ) {
	$parts = extrasport_format_news_date_parts( extrasport_get_news_date( $news->ID ) );

	return array(
		'title'   => $news->post_title,
		'excerpt' => extrasport_get_news_intro( $news->ID ),
		'date'    => extrasport_get_news_date( $news->ID ),
		'day'     => $parts['day'],
		'month'   => $parts['month'],
		'year'    => $parts['year'],
		'url'     => get_permalink( $news->ID ),
	);
}

/**
 * Breadcrumbs for a single news page.
 *
 * @param int|null $post_id News post ID.
 * @return array<int, array{label: string, url: string}>
 */
function extrasport_get_news_breadcrumbs( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );

	if ( ! $post instanceof WP_Post || 'news' !== $post->post_type ) {
		return array();
	}

	return array(
		array(
			'label' => __( 'О клубе', 'extrasport' ),
			'url'   => extrasport_get_about_page_url( 'club' ),
		),
		array(
			'label' => __( 'Новости', 'extrasport' ),
			'url'   => extrasport_get_news_archive_url(),
		),
		array(
			'label' => $post->post_title,
			'url'   => '',
		),
	);
}

/**
 * Archive SEO from seeded option.
 *
 * @return array{title: string, keywords: string, description: string}
 */
function extrasport_get_news_archive_seo() {
	$seo = get_option(
		'extrasport_news_archive_seo',
		array(
			'title'       => '',
			'keywords'    => '',
			'description' => '',
		)
	);

	return is_array( $seo ) ? $seo : array();
}

/**
 * News archive query.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_news_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'news' ) ) {
		return;
	}

	$query->set( 'posts_per_page', 10 );
	$query->set( 'meta_key', EXTRASPORT_NEWS_DATE_META );
	$query->set( 'orderby', 'meta_value' );
	$query->set( 'order', 'DESC' );
}
add_action( 'pre_get_posts', 'extrasport_news_archive_query' );

/**
 * Flush rewrite rules when news routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_news_rewrite() {
	if ( get_option( 'extrasport_news_rewrite_version' ) === '1' ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_news_rewrite_version', '1', false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_news_rewrite', 99 );

/**
 * Archive document title.
 *
 * @param string $title Document title.
 * @return string
 */
function extrasport_news_document_title( $title ) {
	if ( is_post_type_archive( 'news' ) ) {
		$seo = extrasport_get_news_archive_seo();
		if ( ! empty( $seo['title'] ) ) {
			return (string) $seo['title'];
		}
	}

	if ( is_singular( 'news' ) ) {
		$meta = (string) get_post_meta( get_the_ID(), EXTRASPORT_NEWS_META_TITLE, true );
		if ( $meta ) {
			return $meta;
		}
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'extrasport_news_document_title' );

/**
 * News archive and single meta tags.
 *
 * @return void
 */
function extrasport_news_head_meta() {
	if ( is_post_type_archive( 'news' ) ) {
		$seo = extrasport_get_news_archive_seo();
		if ( ! empty( $seo['keywords'] ) ) {
			echo '<meta name="keywords" content="' . esc_attr( (string) $seo['keywords'] ) . '">' . "\n";
		}
		if ( ! empty( $seo['description'] ) ) {
			echo '<meta name="description" content="' . esc_attr( (string) $seo['description'] ) . '">' . "\n";
		}
		return;
	}

	if ( ! is_singular( 'news' ) ) {
		return;
	}

	$keywords    = (string) get_post_meta( get_the_ID(), EXTRASPORT_NEWS_META_KEYWORDS, true );
	$description = (string) get_post_meta( get_the_ID(), EXTRASPORT_NEWS_META_DESCRIPTION, true );

	if ( $keywords ) {
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
	}
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'extrasport_news_head_meta', 1 );
