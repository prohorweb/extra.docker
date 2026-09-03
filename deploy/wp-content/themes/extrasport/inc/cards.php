<?php
/**
 * Membership cards — admin CPT and front-end helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CARD_PRICE_META', '_card_price' );
define( 'EXTRASPORT_CARD_VIDEO_META', '_card_video' );

/**
 * Register membership card CPT (managed in wp-admin, rendered on /cards/).
 *
 * @return void
 */
function extrasport_register_membership_card_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Карта', 'extrasport' ),
			__( 'Карты', 'extrasport' )
		),
		'description'         => __( 'Абонементы клуба: срок и цена на странице /cards/', 'extrasport' ),
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'page-attributes' ),
		'menu_icon'           => 'dashicons-tickets-alt',
		'capability_type'     => 'post',
	);
	register_post_type( 'membership_card', $args );
}
add_action( 'init', 'extrasport_register_membership_card_post_type', 0 );

/**
 * Default membership plans when CPT is empty.
 *
 * @return array<int, array{title: string, price: string, video: int, modal_id: string}>
 */
function extrasport_get_default_membership_plans() {
	return array(
		array(
			'title'    => '1 месяц',
			'price'    => '5900',
			'video'    => 1,
			'modal_id' => 'cardModal0',
		),
		array(
			'title'    => '3 месяца',
			'price'    => '6900',
			'video'    => 2,
			'modal_id' => 'cardModal1',
		),
		array(
			'title'    => '6 месяцев',
			'price'    => '8500',
			'video'    => 3,
			'modal_id' => 'cardModal2',
		),
		array(
			'title'    => '12 месяцев',
			'price'    => '17800',
			'video'    => 4,
			'modal_id' => 'cardModal3',
		),
	);
}

/**
 * Normalize membership card post into plan card data.
 *
 * @param WP_Post $post Membership card post.
 * @return array{title: string, price: string, video: int, modal_id: string}
 */
function extrasport_normalize_membership_card( WP_Post $post ) {
	$video = (int) get_post_meta( $post->ID, EXTRASPORT_CARD_VIDEO_META, true );

	return array(
		'title'    => $post->post_title,
		'price'    => (string) get_post_meta( $post->ID, EXTRASPORT_CARD_PRICE_META, true ),
		'video'    => $video > 0 ? $video : 1,
		'modal_id' => 'cardModal-' . $post->ID,
	);
}

/**
 * Membership plans for the /cards/ page — CPT first, demo fallback.
 *
 * @return array<int, array{title: string, price: string, video: int, modal_id: string}>
 */
function extrasport_get_membership_plans() {
	$posts = get_posts(
		array(
			'post_type'              => 'membership_card',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	if ( ! empty( $posts ) ) {
		return apply_filters( 'extrasport_membership_plans', array_map( 'extrasport_normalize_membership_card', $posts ) );
	}

	return apply_filters( 'extrasport_membership_plans', extrasport_get_default_membership_plans() );
}
