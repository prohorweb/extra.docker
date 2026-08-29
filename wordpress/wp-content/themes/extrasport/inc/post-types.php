<?php
/**
 * Register Custom Post Types
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
        'has_archive'         => true,
        'hierarchical'        => false,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'taxonomies'          => array( 'service_category' ),
        'rewrite'             => array(
            'slug'       => 'services',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-briefcase',
    );
    register_post_type( 'service', $args );
}
add_action( 'init', 'extrasport_register_service_post_type' );

/**
 * Register Group Program Post Type
 */
function extrasport_register_program_post_type() {
    $args = array(
        'label'               => esc_html__( 'Programs', 'extrasport' ),
        'description'         => esc_html__( 'Group programs and memberships', 'extrasport' ),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'has_archive'         => true,
        'hierarchical'        => false,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'taxonomies'          => array( 'program_type' ),
        'rewrite'             => array(
            'slug'       => 'programs',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-calendar',
    );
    register_post_type( 'group_program', $args );
}
add_action( 'init', 'extrasport_register_program_post_type' );

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
        'has_archive'         => true,
        'hierarchical'        => false,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite'             => array(
            'slug'       => 'events',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-tickets',
    );
    register_post_type( 'event', $args );
}
add_action( 'init', 'extrasport_register_event_post_type' );

/**
 * Register Share Post Type (Акции, предложения)
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
        'has_archive'         => true,
        'hierarchical'        => false,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
        'rewrite'             => array(
            'slug'       => 'shares',
            'with_front' => false,
        ),
        'menu_icon'           => 'dashicons-tag',
    );
    register_post_type( 'share', $args );
}
add_action( 'init', 'extrasport_register_share_post_type' );

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
add_action( 'init', 'extrasport_register_banner_post_type' );
