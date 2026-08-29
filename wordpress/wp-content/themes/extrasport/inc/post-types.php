<?php
/**
 * Register Custom Post Types
 *
 * Yii2-compatible permalinks:
 * - service:        /services/{slug}/
 * - group_program:  /services/programs/{slug}/
 * - share:          /card/shares/{slug}/
 *
 * Nested CPTs register before `service` to reduce rewrite collisions.
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Group Program Post Type (nested under /services/programs/)
 */
function extrasport_register_program_post_type() {
	$args = array(
		'label'               => esc_html__( 'Group Programs', 'extrasport' ),
		'description'         => esc_html__( 'Group programs and memberships', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'services/programs',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'taxonomies'          => array( 'program_type' ),
		'rewrite'             => array(
			'slug'       => 'services/programs',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-calendar',
	);
	register_post_type( 'group_program', $args );
}
add_action( 'init', 'extrasport_register_program_post_type', 0 );

/**
 * Register Share Post Type (Yii2: /card/shares/)
 */
function extrasport_register_share_post_type() {
	$args = array(
		'label'               => esc_html__( 'Shares & Offers', 'extrasport' ),
		'description'         => esc_html__( 'Club shares, promotions and special offers', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'card/shares',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'rewrite'             => array(
			'slug'       => 'card/shares',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-tag',
	);
	register_post_type( 'share', $args );
}
add_action( 'init', 'extrasport_register_share_post_type', 0 );

/**
 * Register Service Post Type
 */
function extrasport_register_service_post_type() {
	$args = array(
		'label'               => esc_html__( 'Services', 'extrasport' ),
		'description'         => esc_html__( 'Services offered by the club', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'services',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'taxonomies'          => array( 'service_category' ),
		'rewrite'             => array(
			'slug'       => 'services',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-briefcase',
	);
	register_post_type( 'service', $args );
}
add_action( 'init', 'extrasport_register_service_post_type', 0 );

/**
 * Register Event Post Type (optional)
 */
function extrasport_register_event_post_type() {
	$args = array(
		'label'               => esc_html__( 'Events', 'extrasport' ),
		'description'         => esc_html__( 'Club events and competitions', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'events',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'rewrite'             => array(
			'slug'       => 'events',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-tickets',
	);
	register_post_type( 'event', $args );
}
add_action( 'init', 'extrasport_register_event_post_type', 0 );

/**
 * Register Banner Post Type (Баннеры на главной)
 */
function extrasport_register_banner_post_type() {
	$args = array(
		'label'               => esc_html__( 'Banners', 'extrasport' ),
		'description'         => esc_html__( 'Homepage carousel banners', 'extrasport' ),
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => true,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
		'menu_icon'           => 'dashicons-images-alt',
	);
	register_post_type( 'banner', $args );
}
add_action( 'init', 'extrasport_register_banner_post_type', 0 );

/**
 * Register Lead Post Type (form submissions archive)
 */
function extrasport_register_lead_post_type() {
	$args = array(
		'label'               => esc_html__( 'Leads', 'extrasport' ),
		'description'         => esc_html__( 'Form submissions stored before email/CRM dispatch', 'extrasport' ),
		'public'              => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => false,
		'has_archive'         => false,
		'hierarchical'        => false,
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'supports'            => array( 'title' ),
		'menu_icon'           => 'dashicons-email-alt',
	);
	register_post_type( 'lead', $args );
}
add_action( 'init', 'extrasport_register_lead_post_type', 0 );

/**
 * Top-priority rewrite rules for nested CPT slugs (avoid service collision).
 *
 * @return void
 */
function extrasport_register_nested_rewrite_rules() {
	add_rewrite_rule(
		'^services/programs/?$',
		'index.php?post_type=group_program',
		'top'
	);

	add_rewrite_rule(
		'^services/programs/([^/]+)/?$',
		'index.php?group_program=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^card/shares/?$',
		'index.php?post_type=share',
		'top'
	);

	add_rewrite_rule(
		'^card/shares/([^/]+)/?$',
		'index.php?share=$matches[1]',
		'top'
	);
}
add_action( 'init', 'extrasport_register_nested_rewrite_rules', 20 );
