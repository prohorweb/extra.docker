<?php
/**
 * Share helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shares archive URL.
 *
 * @return string
 */
function extrasport_get_shares_archive_url() {
	return get_post_type_archive_link( 'share' ) ?: home_url( '/actions/' );
}

/**
 * Get intro text for a share post.
 *
 * @param int|null $post_id Share post ID.
 * @return string
 */
function extrasport_get_share_intro( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$intro   = (string) get_post_meta( $post_id, '_share_excerpt', true );

	if ( $intro ) {
		return $intro;
	}

	$post = get_post( $post_id );
	return $post ? (string) get_the_excerpt( $post ) : '';
}

/**
 * Get other shares for the single share page.
 *
 * @param int $exclude_id Current share ID.
 * @param int $limit        Max items.
 * @return array<int, array{title: string, excerpt: string, date: string, image: string, url: string}>
 */
function extrasport_get_other_shares( $exclude_id = 0, $limit = 3 ) {
	$posts = get_posts(
		array(
			'post_type'      => 'share',
			'posts_per_page' => $limit,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post__not_in'   => $exclude_id ? array( (int) $exclude_id ) : array(),
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_share_only_url',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_share_only_url',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		)
	);

	if ( empty( $posts ) ) {
		return array();
	}

	return array_map( 'extrasport_normalize_share_post', $posts );
}

/**
 * VK share URL for the current page.
 *
 * @param string $url   Page URL.
 * @param string $title Share title.
 * @return string
 */
function extrasport_get_vk_share_url( $url, $title ) {
	return add_query_arg(
		array(
			'url'   => $url,
			'title' => $title,
		),
		'https://vk.com/share.php'
	);
}
