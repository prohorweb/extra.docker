<?php
/**
 * Front page placeholder content until CPT entries are imported.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo shares for the homepage actions block.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_share_placeholders( $uri ) {
	$archive_url = get_post_type_archive_link( 'share' ) ?: home_url( '/card/shares/' );

	return array(
		array(
			'title'   => 'Год ФИТНЕС+БАССЕЙН  ВСЕГО 5500!!!',
			'excerpt' => 'МАССАЖ в ПОДАРОК!!',
			'date'    => 'До 29 июня!',
			'image'   => $uri . '/assets/img/shares/leto.jpg',
			'url'     => trailingslashit( $archive_url ) . 'leto/',
		),
		array(
			'title'   => 'Программы обучения плаванию',
			'excerpt' => 'Для детей разных возрастов и подготовки.',
			'date'    => 'Запись открыта!',
			'image'   => $uri . '/assets/img/shares/bassein.jpg',
			'url'     => trailingslashit( $archive_url ) . 'bassein/',
		),
		array(
			'title'   => 'Спортивные секции для детей!',
			'excerpt' => 'Детский абонемент от 4000р',
			'date'    => 'Запись открыта!',
			'image'   => $uri . '/assets/img/shares/fitnes.jpg',
			'url'     => trailingslashit( $archive_url ) . 'fitnes/',
		),
	);
}

/**
 * Normalize a share post into card data.
 *
 * @param WP_Post $share Share post.
 * @return array{title: string, excerpt: string, date: string, image: string, url: string}
 */
function extrasport_normalize_share_post( WP_Post $share ) {
	$share_excerpt = get_post_meta( $share->ID, '_share_excerpt', true );

	return array(
		'title'   => $share->post_title,
		'excerpt' => $share_excerpt ?: wp_trim_words( $share->post_content, 15 ),
		'date'    => (string) get_post_meta( $share->ID, '_share_date', true ),
		'image'   => get_the_post_thumbnail_url( $share->ID, 'large' ) ?: '',
		'url'     => get_permalink( $share->ID ),
	);
}

/**
 * Shares for the homepage — CPT first, demo fallback.
 *
 * @param string $uri Theme URI.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_front_page_shares( $uri ) {
	$share_posts = get_posts(
		array(
			'post_type'      => 'share',
			'posts_per_page' => 6,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( empty( $share_posts ) ) {
		return extrasport_get_share_placeholders( $uri );
	}

	return array_map( 'extrasport_normalize_share_post', $share_posts );
}
