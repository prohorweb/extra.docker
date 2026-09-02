<?php
/**
 * Seed default membership cards for /cards/.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EXTRASPORT_CARDS_SEED_VERSION', 1 );

/**
 * Seed membership cards when none exist.
 *
 * @param bool $force Re-seed even when version matches.
 * @return int Number of created posts.
 */
function extrasport_seed_membership_cards( $force = false ) {
	if ( ! $force && (int) get_option( 'extrasport_cards_seed_version', 0 ) >= EXTRASPORT_CARDS_SEED_VERSION ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'membership_card',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( ! $force && ! empty( $existing ) ) {
		update_option( 'extrasport_cards_seed_version', EXTRASPORT_CARDS_SEED_VERSION, false );
		return 0;
	}

	$created = 0;

	foreach ( extrasport_get_default_membership_plans() as $index => $plan ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'membership_card',
				'post_title'  => $plan['title'],
				'post_status' => 'publish',
				'menu_order'  => $index,
			),
			true
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		update_post_meta( $post_id, EXTRASPORT_CARD_PRICE_META, $plan['price'] );
		update_post_meta( $post_id, EXTRASPORT_CARD_VIDEO_META, (int) $plan['video'] );
		++$created;
	}

	update_option( 'extrasport_cards_seed_version', EXTRASPORT_CARDS_SEED_VERSION, false );

	return $created;
}

/**
 * Auto-seed membership cards on theme setup.
 *
 * @return void
 */
function extrasport_maybe_seed_membership_cards() {
	extrasport_seed_membership_cards( false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_seed_membership_cards', 36 );
