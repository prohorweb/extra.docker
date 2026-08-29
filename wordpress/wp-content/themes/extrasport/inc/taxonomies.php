<?php
/**
 * Register Custom Taxonomies
 *
 * @package ExtraSport
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Service Category Taxonomy
 */
function extrasport_register_service_category_taxonomy() {
    $args = array(
        'label'              => esc_html__( 'Service Categories', 'extrasport' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_rest'       => true,
        'hierarchical'       => true,
        'rewrite'            => array(
            'slug'       => 'service-category',
            'with_front' => false,
        ),
    );
    register_taxonomy( 'service_category', array( 'service' ), $args );
}
add_action( 'init', 'extrasport_register_service_category_taxonomy' );

/**
 * Register Program Type Taxonomy
 */
function extrasport_register_program_type_taxonomy() {
    $args = array(
        'label'              => esc_html__( 'Program Types', 'extrasport' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_rest'       => true,
        'hierarchical'       => true,
        'rewrite'            => array(
            'slug'       => 'program-type',
            'with_front' => false,
        ),
    );
    register_taxonomy( 'program_type', array( 'group_program' ), $args );
}
add_action( 'init', 'extrasport_register_program_type_taxonomy' );
