<?php
/**
 * Breadcrumb helpers.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Services archive URL.
 *
 * @return string
 */
function extrasport_get_services_archive_url() {
	return get_post_type_archive_link( 'service' ) ?: home_url( '/services/' );
}

/**
 * Render breadcrumb navigation.
 *
 * @param array<int, array{label: string, url?: string}> $items    Trail items without home.
 * @param array{class?: string, list_class?: string}   $options Optional CSS classes.
 * @return void
 */
function extrasport_render_breadcrumbs( array $items, array $options = array() ) {
	if ( empty( $items ) ) {
		return;
	}

	get_template_part(
		'components/breadcrumbs',
		null,
		array(
			'items'      => $items,
			'class'      => $options['class'] ?? '',
			'list_class' => $options['list_class'] ?? '',
		)
	);
}

/**
 * Breadcrumbs for a single service page.
 *
 * @param int|null $post_id Post ID.
 * @return array<int, array{label: string, url: string}>
 */
function extrasport_get_service_breadcrumbs( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );

	if ( ! $post instanceof WP_Post || 'service' !== $post->post_type ) {
		return array();
	}

	$items = array(
		array(
			'label' => __( 'Услуги', 'extrasport' ),
			'url'   => extrasport_get_services_archive_url(),
		),
	);

	if ( (int) $post->post_parent > 0 ) {
		$parent = get_post( (int) $post->post_parent );
		if ( $parent instanceof WP_Post ) {
			$items[] = array(
				'label' => $parent->post_title,
				'url'   => extrasport_get_service_card_url( $parent ),
			);
		}
	}

	$items[] = array(
		'label' => $post->post_title,
		'url'   => '',
	);

	return $items;
}

/**
 * Breadcrumbs for a grouped service list page.
 *
 * @param int|null $post_id Group service post ID.
 * @return array<int, array{label: string, url: string}>
 */
function extrasport_get_service_group_breadcrumbs( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return array();
	}

	return array(
		array(
			'label' => __( 'Услуги', 'extrasport' ),
			'url'   => extrasport_get_services_archive_url(),
		),
		array(
			'label' => $post->post_title,
			'url'   => '',
		),
	);
}
