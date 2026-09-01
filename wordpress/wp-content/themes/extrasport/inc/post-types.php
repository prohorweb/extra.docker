<?php
/**
 * Register Custom Post Types
 *
 * Yii2-compatible permalinks:
 * - service:  /services/{slug}/ or /services/{parent}/{child}/
 * - share:    /card/shares/{slug}/
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once EXTRASPORT_DIR . '/inc/post-type-labels.php';

/**
 * Register Share Post Type (Yii2: /card/shares/)
 */
function extrasport_register_share_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Акция', 'extrasport' ),
			__( 'Акции', 'extrasport' )
		),
		'description'         => __( 'Акции, спецпредложения и промо клуба', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'card/shares',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
		'rewrite'             => array(
			'slug'       => 'card/shares',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-megaphone',
	);
	register_post_type( 'share', $args );
}
add_action( 'init', 'extrasport_register_share_post_type', 0 );

/**
 * Register Service Post Type
 */
function extrasport_register_service_post_type() {
	$args = array(
		'labels'              => extrasport_get_hierarchical_post_labels(
			__( 'Услуга', 'extrasport' ),
			__( 'Услуги', 'extrasport' )
		),
		'description'         => __( 'Услуги клуба: одиночные страницы и группы с дочерними услугами', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'services',
		'hierarchical'        => true,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
		'rewrite'             => array(
			'slug'         => 'services',
			'with_front'   => false,
			'hierarchical' => true,
			'feeds'        => false,
			'pages'        => true,
		),
		'menu_icon'           => 'dashicons-universal-access-alt',
	);
	register_post_type( 'service', $args );
}
add_action( 'init', 'extrasport_register_service_post_type', 0 );

/**
 * Register Event Post Type
 */
function extrasport_register_event_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Мероприятие', 'extrasport' ),
			__( 'Мероприятия', 'extrasport' )
		),
		'description'         => __( 'События, соревнования и активности клуба', 'extrasport' ),
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
		'menu_icon'           => 'dashicons-calendar-alt',
	);
	register_post_type( 'event', $args );
}
add_action( 'init', 'extrasport_register_event_post_type', 0 );

/**
 * Register Trainer Post Type (Yii2: /es/command/ → /trainers/)
 */
function extrasport_register_trainer_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Тренер', 'extrasport' ),
			__( 'Тренеры', 'extrasport' )
		),
		'description'         => __( 'Тренеры и инструкторы клуба', 'extrasport' ),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'has_archive'         => 'trainers',
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'             => array(
			'slug'       => 'trainers',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => true,
		),
		'menu_icon'           => 'dashicons-groups',
	);
	register_post_type( 'trainer', $args );
}
add_action( 'init', 'extrasport_register_trainer_post_type', 0 );

/**
 * Register Banner Post Type
 */
function extrasport_register_banner_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Баннер', 'extrasport' ),
			__( 'Баннеры', 'extrasport' )
		),
		'description'         => __( 'Слайды карусели на главной странице', 'extrasport' ),
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_rest'        => true,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'thumbnail', 'custom-fields' ),
		'menu_icon'           => 'dashicons-format-gallery',
	);
	register_post_type( 'banner', $args );
}
add_action( 'init', 'extrasport_register_banner_post_type', 0 );

/**
 * Register Lead Post Type (form submissions archive)
 */
function extrasport_register_lead_post_type() {
	$args = array(
		'labels'              => extrasport_get_flat_post_labels(
			__( 'Заявка', 'extrasport' ),
			__( 'Заявки', 'extrasport' )
		),
		'description'         => __( 'Заявки с форм сайта до отправки в CRM или почту', 'extrasport' ),
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
	add_rewrite_tag( '%service_parent%', '([^/]+)' );

	add_rewrite_rule(
		'^services/([^/]+)/([^/]+)/?$',
		'index.php?post_type=service&service_parent=$matches[1]&name=$matches[2]',
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

/**
 * Allow nested service parent slug in query vars.
 *
 * @param array<string> $vars Public query vars.
 * @return array<string>
 */
function extrasport_register_service_query_vars( $vars ) {
	$vars[] = 'service_parent';

	return $vars;
}
add_filter( 'query_vars', 'extrasport_register_service_query_vars' );

/**
 * Resolve /services/{parent}/{child}/ to a child service post.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_nested_service_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$parent_slug = $query->get( 'service_parent' );
	if ( ! $parent_slug ) {
		return;
	}

	$parent_id = extrasport_find_top_level_service_by_slug( $parent_slug );
	if ( ! $parent_id ) {
		return;
	}

	$query->set( 'post_type', 'service' );
	$query->set( 'post_parent', $parent_id );
	$query->is_archive           = false;
	$query->is_post_type_archive = false;
}
add_action( 'pre_get_posts', 'extrasport_nested_service_query' );

/**
 * Flush rewrite rules after nested service routes change.
 *
 * @return void
 */
function extrasport_maybe_flush_service_rewrite() {
	if ( get_option( 'extrasport_service_rewrite_version' ) === '2' ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'extrasport_service_rewrite_version', '2', false );
}
add_action( 'after_setup_theme', 'extrasport_maybe_flush_service_rewrite', 99 );

/**
 * Shares archive — show all promotions, same order as homepage.
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_share_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'share' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'extrasport_share_archive_query' );

/**
 * Services archive — only top-level sections (single + group).
 *
 * @param WP_Query $query Main query.
 * @return void
 */
function extrasport_service_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'service' ) ) {
		$query->set( 'post_parent', 0 );
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'extrasport_service_archive_query' );
